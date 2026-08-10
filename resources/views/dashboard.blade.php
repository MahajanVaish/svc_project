@extends('admin.layouts.layouts')
@section('title' , 'Admin Dashboard')
@section('content')

<div class="container-fluids display_data">
    <div class="row mb-5">

        {{-- Page Header --}}
        <div class="col-12 mb-3">
            <div class="card border-bottom-0">
                <div class="card-header">   
                    <div class="heading-action d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <h3 class="bold font-up fnf-title mb-0">Patient Summary</h3>
                            <div class="d-flex align-items-center gap-2">
                                <label for="globalStatusFilter" class="mb-0 text-muted small fw-bold text-nowrap">Filter Status:</label>
                                <select id="globalStatusFilter" class="form-select form-select-sm" style="width: 180px; border-radius: 6px; border: 1px solid #ced4da;">
                                    @php
                                        $userBranch = auth()->user()->user_branch ?? '';
                                        $isSvcBranch = ($userBranch === 'SVC-0005' || $userBranch === 'SVC' || request()->is('svc*') || (isset($branches) && $branches->count() === 1 && str_contains($branches->first()->branch_name ?? '', 'SVC')));
                                    @endphp
                                    @if($isSvcBranch)
                                        <option value="all">All Patients</option>
                                        <option value="new_patient">New Patient</option>
                                        <option value="old_patient">Old Patient</option>
                                        <option value="followup">Followup</option>
                                        <option value="ipd">IPD</option>
                                    @else
                                        <option value="all">All Patients</option>
                                        <option value="pending">Pending</option>
                                        <option value="joined">Joined</option>
                                        <option value="diet_chart">Diet Chart</option>
                                        <option value="online_abroad">Online / Abroad</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('financial.dashboard') }}" class="btn btn-sm text-white fw-bold d-inline-flex align-items-center gap-2 shadow-sm" style="background: linear-gradient(135deg, #006637, #10b981); border-radius: 8px; padding: 7px 16px; font-size: 13px;">
                                <i class="bi bi-graph-up-arrow"></i> Financial Dashboard
                            </a>
                            @auth
                                @if(auth()->user()->hasRole('Superadmin'))
                                    <a href="{{ route('followup.calendar') }}" class="fnf-btn btn btn-primary calander_btn">
                                        <i class="bi bi-calendar3"></i> Follow Up Calendar
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($branches->isNotEmpty())
        @foreach ($branches as $branch)
        @if($branch)

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">

                {{-- Branch Name --}}
                <div class="card-header">
                    <div class="heading-action">
                        <h3 class="bold font-up fnf-title">{{ $branch->branch_name }}</h3>
                    </div>
                </div>

                <div class="card-body p-0">

                    {{-- ══════════════════════════════════════
                         SECTION 1 — Quick Total Count
                    ══════════════════════════════════════ --}}
                    <div class="px-3 pt-3 pb-2">
                        <div class="total-box d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Total Patients (All Time)</span>
                            <span class="patient_count badge-total" id="patientCount{{ $branch->branch_id }}">
                                <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                            </span>
                        </div>
                    </div>

                    <hr class="my-0">

                    {{-- ══════════════════════════════════════
                         SECTION 2 — Patient List by Period
                    ══════════════════════════════════════ --}}
                    <div class="px-3 py-3">

                        {{-- Section Header --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-semibold" style="font-size:14px;">
                                <i class="bi bi-people-fill text-success me-1"></i> Patient List by Period
                            </span>
                            <button type="button" class="toggle-section-btn" data-target="listSection_{{ $branch->branch_id }}">
                                <i class="bi bi-chevron-down"></i>
                            </button>
                        </div>

                        <div id="listSection_{{ $branch->branch_id }}" class="list-section-body" style="display:none;">

                            {{-- Filters Row --}}
                            <div class="filter-row mb-3">
                                <div class="filter-grid">
                                    <div>
                                        <label class="filter-label">From</label>
                                        <input type="date" class="form-control form-control-sm list-from"
                                            value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                                    </div>
                                    <div>
                                        <label class="filter-label">To</label>
                                        <input type="date" class="form-control form-control-sm list-to"
                                            value="{{ now()->endOfMonth()->format('Y-m-d') }}">
                                    </div>
                                    <div>
                                        <label class="filter-label">Group by</label>
                                        <select class="form-select form-select-sm list-groupby">
                                            <option value="day">Day</option>
                                            <option value="month">Month</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="button" class="fnf-btn btn btn-primary btn-sm w-100 mt-2 loadPatientList"
                                    data-branch="{{ $branch->branch_id }}"
                                    data-branch-name="{{ $branch->branch_name }}">
                                    <i class="bi bi-search me-1"></i> Search Patients
                                </button>
                            </div>

                            {{-- Loading --}}
                            <div class="list-loading text-center py-3" style="display:none;">
                                <div class="spinner-border spinner-border-sm text-success" role="status"></div>
                                <span class="ms-2 small text-muted">Fetching patients...</span>
                            </div>

                            {{-- Empty --}}
                            <div class="list-empty text-center py-3" style="display:none;">
                                <i class="bi bi-inbox text-muted" style="font-size:24px;"></i>
                                <p class="small text-muted mt-1 mb-0">No patients found for this period.</p>
                            </div>

                            {{-- Results --}}
                            <div class="list-results" id="listResults_{{ $branch->branch_id }}" style="display:none;">

                                {{-- Summary bar --}}
                                <div class="result-summary mb-2">
                                    <span class="summary-text small text-muted"></span>
                                    <span class="summary-total badge bg-success ms-1"></span>
                                </div>

                                {{-- Accordion groups --}}
                                <div class="period-accordion" id="accordion_{{ $branch->branch_id }}"></div>

                            </div>
                        </div>
                    </div>

                </div>{{-- /card-body --}}
            </div>
        </div>

        @endif
        @endforeach
        @else
        <div class="col-12">
            <div class="alert alert-warning">
                No branch assigned to your account. Please contact administrator.
            </div>
        </div>
        @endif

    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     STYLES
══════════════════════════════════════════════════════════════ --}}
<style>
.display_data { padding: 20px; }

/* Card */
.card {
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
}
.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    padding: 14px 18px;
    border-radius: 10px 10px 0 0;
}
.heading-action { display:flex; justify-content:space-between; align-items:center; }
#globalStatusFilter {
    background-color: #ffffff;
    color: #495057;
    font-weight: 500;
    cursor: pointer;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
#globalStatusFilter:focus {
    border-color: #4bab35 !important;
    box-shadow: 0 0 0 0.2rem rgba(75, 171, 53, 0.25) !important;
}
.fnf-title { color:#2c3e50; font-weight:600; margin:0; font-size:16px; }

/* Buttons */
.fnf-btn, .calander_btn {
    background-color: #4bab35 !important;
    border: none !important;
    color: white !important;
    font-weight: 500;
}
.fnf-btn:hover, .calander_btn:hover { background-color: #3d9429 !important; }
.fnf-btn:disabled { background-color: #aaa !important; cursor:not-allowed; }

/* Total badge */
.total-box { background:#f0faf0; border-radius:8px; padding:10px 14px; }
.badge-total {
    background:#4bab35;
    color:white;
    font-size:18px;
    font-weight:700;
    padding:4px 14px;
    border-radius:20px;
    min-width:50px;
    text-align:center;
    display:inline-block;
}

/* Toggle button */
.toggle-section-btn {
    background:none;
    border:none;
    padding:2px 6px;
    cursor:pointer;
    color:#555;
    border-radius:4px;
    transition: background .15s;
}
.toggle-section-btn:hover { background:#f0f0f0; }
.toggle-section-btn:focus { outline:none; box-shadow:none; }

/* Filter grid */
.filter-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 8px;
}
.filter-label { font-size:11px; color:#888; margin-bottom:3px; display:block; }
.form-control:focus, .form-select:focus {
    outline:none !important;
    box-shadow:none !important;
    border-color:#ced4da !important;
}

/* Result summary */
.result-summary { display:flex; align-items:center; }

/* Period accordion */
.period-group {
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    margin-bottom: 8px;
    overflow: hidden;
}
.period-group-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 9px 12px;
    background: #f8f9fa;
    cursor: pointer;
    user-select: none;
    transition: background .15s;
}
.period-group-header:hover { background: #eef7ec; }
.period-group-label { font-weight:600; font-size:13px; color:#2c3e50; }
.period-group-meta { display:flex; align-items:center; gap:8px; }
.count-pill {
    background: #4bab35;
    color: white;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 9px;
    border-radius: 12px;
}
.period-chevron { color:#888; font-size:12px; transition:transform .2s; }
.period-chevron.open { transform: rotate(180deg); }

/* Patient list inside accordion */
.period-group-body { display:none; }
.patient-list { list-style:none; padding:0; margin:0; }
.patient-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 9px 12px;
    border-top: 1px solid #f0f0f0;
    transition: background .1s;
}
.patient-item:hover { background:#fafff9; }
.patient-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #e8f5e9;
    color: #4bab35;
    font-weight: 700;
    font-size: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.patient-info { flex:1; min-width:0; }
.patient-name { font-weight:600; font-size:13px; color:#2c3e50; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.patient-meta { font-size:11px; color:#888; margin-top:1px; }
.patient-meta span { margin-right:8px; }
.source-badge {
    font-size:10px;
    padding:1px 6px;
    border-radius:8px;
    font-weight:600;
    flex-shrink:0;
    align-self:center;
}
.source-SVC   { background:#dbeafe; color:#1d4ed8; }
.source-LHR   { background:#fef3c7; color:#92400e; }
.source-HYDRA { background:#ede9fe; color:#5b21b6; }
.source-ACC   { background:#fce7f3; color:#9d174d; }

/* Scrollable list */
.period-group-body { max-height: 280px; overflow-y: auto; }

/* Responsive */
@media (max-width: 576px) {
    .filter-grid { grid-template-columns: 1fr 1fr; }
    .filter-grid > div:last-child { grid-column: span 2; }
}
</style>

{{-- ══════════════════════════════════════════════════════════════
     SCRIPTS
══════════════════════════════════════════════════════════════ --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {

    // ── Load total counts function ───────────────────────────────────────────
    function loadAllTotals(status) {
        $(".card").each(function () {
            const card       = $(this);
            const branchId   = card.find(".loadPatientList").data("branch");
            const branchName = card.find(".loadPatientList").data("branch-name");
            if (!branchId) return;

            // Show loading spinner inside total badge
            card.find(".patient_count").html('<span class="spinner-border spinner-border-sm text-white" role="status"></span>');

            $.post('{{ route("get.total.patients") }}', {
                _token: '{{ csrf_token() }}',
                branch_id: branchId,
                branch_name: branchName,
                status: status
            }, function (res) {
                card.find(".patient_count").html(res.success ? res.patient_count : '0');
            }).fail(function () {
                card.find(".patient_count").html('0');
            });
        });
    }

    // ── Initialize counts ────────────────────────────────────────────────────
    const initialStatus = $("#globalStatusFilter").val() || 'all';
    loadAllTotals(initialStatus);

    // ── Handle global status filter change ───────────────────────────────────
    $("#globalStatusFilter").on("change", function () {
        const selectedStatus = $(this).val();
        loadAllTotals(selectedStatus);
        
        // Also auto-refresh currently opened branch patient lists if they are open
        $(".list-section-body:visible").each(function() {
            $(this).find(".loadPatientList").trigger("click");
        });
    });

    // ── Toggle section open/close ────────────────────────────────────────────
    $(document).on("click", ".toggle-section-btn", function () {
        const target = $(this).data("target");
        const body   = $("#" + target);
        const icon   = $(this).find("i");
        body.slideToggle(200);
        icon.toggleClass("bi-chevron-down bi-chevron-up");
    });

    // ── Period accordion toggle ──────────────────────────────────────────────
    $(document).on("click", ".period-group-header", function () {
        const body    = $(this).next(".period-group-body");
        const chevron = $(this).find(".period-chevron");
        body.slideToggle(150);
        chevron.toggleClass("open");
    });

    // ── Search patients ──────────────────────────────────────────────────────
    $(document).on("click", ".loadPatientList", function () {
        const btn        = $(this);
        const branchId   = btn.data("branch");
        const branchName = btn.data("branch-name");
        const section    = btn.closest(".list-section-body");
        const fromDate   = section.find(".list-from").val();
        const toDate     = section.find(".list-to").val();
        const groupBy    = section.find(".list-groupby").val();
        const resultsBox = $("#listResults_" + branchId);
        const accordion  = $("#accordion_" + branchId);
        const loading    = section.find(".list-loading");
        const empty      = section.find(".list-empty");
        const status     = $("#globalStatusFilter").val() || 'all';

        // Validation
        if (!fromDate || !toDate) {
            alert("Please select both From and To dates.");
            return;
        }
        if (fromDate > toDate) {
            alert("From date cannot be greater than To date.");
            return;
        }

        // Reset UI
        resultsBox.hide();
        empty.hide();
        loading.show();
        btn.prop("disabled", true);
        accordion.empty();

        $.ajax({
            url: '{{ route("get.patient.list") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                branch_id: branchId,
                branch_name: branchName,
                from_date: fromDate,
                to_date: toDate,
                group_by: groupBy,
                status: status
            },
            success: function (res) {
                loading.hide();
                btn.prop("disabled", false);

                if (!res.success || !res.groups || res.groups.length === 0) {
                    empty.show();
                    return;
                }

                // Summary bar
                const fromFmt = formatDate(res.from_date);
                const toFmt   = formatDate(res.to_date);
                resultsBox.find(".summary-text").text(fromFmt + " – " + toFmt);
                
                let statusLabel = 'patients';
                if (status === 'pending') statusLabel = 'Pending';
                else if (status === 'joined') statusLabel = 'Joined';
                else if (status === 'diet_chart') statusLabel = 'Diet Chart';
                else if (status === 'online_abroad') statusLabel = 'Online/Abroad';
                else if (status === 'new_patient') statusLabel = 'New Patients';
                else if (status === 'old_patient') statusLabel = 'Old Patients';
                else if (status === 'followup') statusLabel = 'Followup Patients';
                else if (status === 'ipd') statusLabel = 'IPD Patients';

                resultsBox.find(".summary-total").text(res.total + " " + statusLabel);

                // Build accordion
                $.each(res.groups, function (i, group) {
                    const isFirst = (i === 0);
                    let rows = '';
                    $.each(group.patients, function (j, p) {
                        const initials = getInitials(p.patient_name);
                        rows += `
                            <li class="patient-item">
                                <div class="patient-avatar">${initials}</div>
                                <div class="patient-info">
                                    <div class="patient-name">${escHtml(p.patient_name)}</div>
                                    <div class="patient-meta">
                                        <span><i class="bi bi-person-badge"></i> ${escHtml(p.patient_id)}</span>
                                        <span><i class="bi bi-calendar2"></i> ${escHtml(p.inquiry_date)}</span>
                                        ${p.age && p.age !== '—' ? `<span><i class="bi bi-clock"></i> ${escHtml(String(p.age))} yrs</span>` : ''}
                                    </div>
                                </div>
                                <span class="source-badge source-${p.source}">${p.source}</span>
                            </li>`;
                    });

                    accordion.append(`
                        <div class="period-group">
                            <div class="period-group-header">
                                <span class="period-group-label">
                                    <i class="bi bi-calendar-week me-1 text-success"></i>${escHtml(group.label)}
                                </span>
                                <div class="period-group-meta">
                                    <span class="count-pill">${group.count} patient${group.count !== 1 ? 's' : ''}</span>
                                    <i class="bi bi-chevron-down period-chevron ${isFirst ? 'open' : ''}"></i>
                                </div>
                            </div>
                            <div class="period-group-body" style="${isFirst ? 'display:block;' : 'display:none;'}">
                                <ul class="patient-list">${rows}</ul>
                            </div>
                        </div>`);
                });

                resultsBox.show();
            },
            error: function () {
                loading.hide();
                btn.prop("disabled", false);
                empty.text("Error loading data. Please try again.").show();
            }
        });
    });

    // ── Helpers ──────────────────────────────────────────────────────────────
    function formatDate(str) {
        if (!str) return '';
        const d = new Date(str);
        return String(d.getDate()).padStart(2,'0') + '-' +
               String(d.getMonth()+1).padStart(2,'0') + '-' +
               d.getFullYear();
    }

    function getInitials(name) {
        if (!name || name === 'N/A') return '?';
        const parts = name.trim().split(/\s+/);
        if (parts.length === 1) return parts[0][0].toUpperCase();
        return (parts[0][0] + parts[parts.length-1][0]).toUpperCase();
    }

    function escHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g,'&amp;')
            .replace(/</g,'&lt;')
            .replace(/>/g,'&gt;')
            .replace(/"/g,'&quot;');
    }
});
</script>
@endsection
