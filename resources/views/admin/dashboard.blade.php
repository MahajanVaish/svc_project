@extends('admin.layouts.layouts')

@section('title', 'Dashboard')

@section('content')

    <div class="container-fluid display_data p-4">
        <div class="row mb-4">
            <div class="col-12">
                <div
                    class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center bg-white dark:bg-slate-800 p-4 rounded-3 shadow-sm border border-slate-200 dark:border-slate-700 gap-3">
                    <h4 class="mb-0 text-slate-800 dark:text-slate-100 fw-bold">Patient Summary</h4>
                    @auth
                        @if(auth()->user()->hasRole('Superadmin'))
                            <div class="d-flex gap-2">
                                <a href="{{ route('followup.calendar') }}"
                                    class="btn btn-primary d-flex align-items-center gap-2">
                                    <i class="bi bi-calendar-check"></i> Follow Up Calendar
                                </a>
                                <a href="{{ route('admin.payment.summary') }}"
                                    class="btn btn-success d-flex align-items-center gap-2">
                                    <i class="bi bi-graph-up"></i> Payment Summary
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        <div class="row">
            @if($branches->isNotEmpty())
                @foreach ($branches as $branch)
                    @if($branch)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                                <div
                                    class="card-header bg-white dark:bg-slate-800 border-bottom border-slate-100 dark:border-slate-700 p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 text-slate-700 dark:text-slate-200 fw-semibold">{{ $branch->branch_name }}</h5>
                                        <span
                                            class="badge bg-light text-secondary dark:bg-slate-700 dark:text-slate-300 border dark:border-slate-600">ID:
                                            {{ $branch->branch_id }}</span>
                                    </div>
                                </div>

                                <div class="card-body bg-white dark:bg-slate-800 p-4">
                                    <form class="filterForm" data-branch="{{ $branch->branch_id }}">
                                        @csrf

                                        <div class="filter_fields mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span
                                                    class="text-xs text-uppercase text-secondary dark:text-slate-400 fw-bold tracking-wider">Filter
                                                    Data</span>
                                                <button type="button"
                                                    class="btn btn-sm btn-link text-decoration-none p-0 text-secondary dark:text-slate-400 toggle-filter">
                                                    <i class="bi bi-chevron-down"></i>
                                                </button>
                                            </div>

                                            <div class="show_filter_field bg-slate-50 dark:bg-slate-900 p-3 rounded-2 border border-slate-100 dark:border-slate-700"
                                                style="display:none;">
                                                <input type="hidden" name="branch_id" value="{{ $branch->branch_id }}">

                                                <div class="row g-2 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label small text-secondary dark:text-slate-400">From
                                                            Date</label>
                                                        <input type="date" name="from_date"
                                                            class="form-control form-control-sm border-slate-200 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small text-secondary dark:text-slate-400">To
                                                            Date</label>
                                                        <input type="date" name="to_date"
                                                            class="form-control form-control-sm border-slate-200 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200">
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-end">
                                                    <button type="button" class="btn btn-sm btn-primary submitFilter">
                                                        Apply Filter
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="display_filter_data">
                                        <div class="mb-3">
                                            <p class="text-xs text-muted dark:text-slate-400 text-uppercase mb-1 date_label">Total
                                                Patients (All Sources)</p>
                                            <div class="d-flex align-items-center">
                                                <h2 class="patient_count fw-bold text-teal-600 dark:text-teal-400 mb-0">0</h2>
                                            </div>
                                        </div>

                                        <!-- SVC/LHR/HYDRA Branch Metrics -->
                                        <div class="patient_breakdown_counts mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-sm text-secondary dark:text-slate-300">New Patients</span>
                                                <span
                                                    class="new_patient_count badge bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200 rounded-pill px-3">0</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-sm text-secondary dark:text-slate-300">Followup Patients</span>
                                                <span
                                                    class="followup_patient_count badge bg-amber-50 text-amber-700 dark:bg-amber-900/40 dark:text-amber-200 rounded-pill px-3">0</span>
                                            </div>
                                            <!-- SVC Only: IPD & OPD -->
                                            <div class="svc_only_counts" style="display: none;">
                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                    <span class="text-sm text-secondary dark:text-slate-300">IPD Patients</span>
                                                    <span
                                                        class="ipd_patient_count badge bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-200 rounded-pill px-3">0</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                    <span class="text-sm text-secondary dark:text-slate-300">OPD Patients</span>
                                                    <span
                                                        class="opd_patient_count badge bg-cyan-50 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-200 rounded-pill px-3">0</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Other Branch Metrics -->
                                        <div class="other_branch_metrics mb-3" style="display: none;">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-sm text-secondary dark:text-slate-300">DC</span>
                                                <span
                                                    class="diet_chart_count badge bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-200 rounded-pill px-3">0</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-sm text-secondary dark:text-slate-300">Followup</span>
                                                <span
                                                    class="other_followup_count badge bg-blue-50 text-blue-700 dark:bg-blue-900/40 dark:text-blue-200 rounded-pill px-3">0</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-sm text-secondary dark:text-slate-300">Joined</span>
                                                <span
                                                    class="joined_count badge bg-green-50 text-green-700 dark:bg-green-900/40 dark:text-green-200 rounded-pill px-3">0</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-sm text-secondary dark:text-slate-300">Pending</span>
                                                <span
                                                    class="pending_count badge bg-orange-50 text-orange-700 dark:bg-orange-900/40 dark:text-orange-200 rounded-pill px-3">0</span>
                                            </div>
                                        </div>

                                        <div class="online_abroad_metrics mt-3" style="border-top: 1px dashed #e2e8f0; padding-top: 10px;">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-sm fw-bold text-teal-700 dark:text-teal-400">
                                                    <i class="bi bi-globe me-1"></i> Online / Abroad
                                                </span>
                                                <span
                                                    class="online_abroad_count badge bg-teal-50 text-teal-700 dark:bg-teal-900/40 dark:text-teal-200 rounded-pill px-3">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="col-12">
                    <div
                        class="alert alert-light border shadow-sm text-center py-5 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300">
                        <i class="bi bi-info-circle text-secondary dark:text-slate-400 fs-4 d-block mb-3"></i>
                        No branches found.
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Open/close filter box
            $(document).on('click', '.toggle-filter', function () {
                let card = $(this).closest('.card');
                let box = card.find('.show_filter_field');
                let icon = $(this).find("i");

                if (box.is(":visible")) {
                    box.slideUp(200);
                    icon.removeClass("bi-chevron-up").addClass("bi-chevron-down");
                } else {
                    box.slideDown(200);
                    icon.removeClass("bi-chevron-down").addClass("bi-chevron-up");
                }
            });

            // Load total patient count for all branches (from all sources)
            $(".card").each(function () {
                let card = $(this);
                let branch = card.find("form").data("branch");

                let breakdownBlock = card.find('.patient_breakdown_counts');
                let otherBranchBlock = card.find('.other_branch_metrics');
                let svcOnlyBlock = card.find('.svc_only_counts');

                // Initial state: hide specific blocks, show total count as 0
                breakdownBlock.hide();
                otherBranchBlock.hide();
                svcOnlyBlock.hide();

                $.post("{{ route('get.total.patients') }}", {
                    _token: "{{ csrf_token() }}",
                    branch_id: branch
                }, function (res) {
                    if (res.success) {
                        // Update total count
                        card.find(".patient_count").text(res.patient_count ?? 0);

                        // Dynamic Visibility based on response data
                        let hasInquiryData = (res.new_patient_count > 0 || res.followup_patient_count > 0 || res.patient_count > 0);
                        let hasDietData = (res.diet_chart_count > 0 || res.joined_count > 0);

                        breakdownBlock.toggle(hasInquiryData);
                        otherBranchBlock.toggle(hasDietData && !hasInquiryData); // Prefer inquiry breakdown if both exist
                        svcOnlyBlock.toggle(res.ipd_patient_count > 0 || res.opd_patient_count > 0);

                        if (hasInquiryData) {
                            card.find(".new_patient_count").text(res.new_patient_count ?? 0);
                            card.find(".followup_patient_count").text(res.followup_patient_count ?? 0);
                            card.find(".ipd_patient_count").text(res.ipd_patient_count ?? 0);
                            card.find(".opd_patient_count").text(res.opd_patient_count ?? 0);
                            card.find(".online_abroad_count").text(res.online_abroad_count ?? 0);
                            card.find(".date_label").text('Total Patients (All Sources)');
                        }

                        if (hasDietData) {
                            card.find(".diet_chart_count").text(res.diet_chart_count ?? 0);
                            card.find(".other_followup_count").text(res.followup_count ?? 0);
                            card.find(".joined_count").text(res.joined_count ?? 0);
                            card.find(".pending_count").text(res.pending_count ?? 0);
                            if (!hasInquiryData) card.find(".date_label").text('Diet Chart Patients');
                        }
                    } else {
                        card.find(".patient_count").text('0');
                        card.find(".new_patient_count").text('0');
                        card.find(".followup_patient_count").text('0');
                        card.find(".diet_chart_count").text('0');
                        card.find(".other_followup_count").text('0');
                        card.find(".joined_count").text('0');
                        card.find(".pending_count").text('0');
                        card.find(".date_label").text('Error loading data');
                    }
                }).fail(function (xhr, status, error) {
                    console.error('AJAX Error:', error);
                    card.find(".patient_count").text('-');
                    card.find(".new_patient_count").text('0');
                    card.find(".followup_patient_count").text('0');
                    card.find(".diet_chart_count").text('0');
                    card.find(".other_followup_count").text('0');
                    card.find(".joined_count").text('0');
                    card.find(".pending_count").text('0');
                });
            });

            // Apply filter
            $(document).on("click", ".submitFilter", function () {
                let card = $(this).closest(".card");
                let form = card.find(".filterForm");
                let branch = form.data("branch");
                // Show loading state
                let submitBtn = $(this);
                let originalText = submitBtn.text();
                submitBtn.prop('disabled', true).text('Loading...');

                let breakdownBlock = card.find('.patient_breakdown_counts');
                let otherBranchBlock = card.find('.other_branch_metrics');
                let svcOnlyBlock = card.find('.svc_only_counts');

                $.post("{{ route('get.filtered.patients') }}", form.serialize(), function (res) {
                    if (res.success) {
                        // Update total count
                        card.find(".patient_count").text(res.patient_count ?? 0);

                        // Dynamic Visibility
                        let hasInquiryData = (res.new_patient_count > 0 || res.followup_patient_count > 0 || res.patient_count > 0);
                        let hasDietData = (res.diet_chart_count > 0 || res.joined_count > 0);

                        breakdownBlock.toggle(hasInquiryData);
                        otherBranchBlock.toggle(hasDietData && !hasInquiryData);
                        svcOnlyBlock.toggle(res.ipd_patient_count > 0 || res.opd_patient_count > 0);

                        if (hasInquiryData) {
                            card.find(".new_patient_count").text(res.new_patient_count ?? 0);
                            card.find(".followup_patient_count").text(res.followup_patient_count ?? 0);
                            card.find(".ipd_patient_count").text(res.ipd_patient_count ?? 0);
                            card.find(".opd_patient_count").text(res.opd_patient_count ?? 0);
                            card.find(".online_abroad_count").text(res.online_abroad_count ?? 0);
                        }

                        if (hasDietData) {
                            card.find(".diet_chart_count").text(res.diet_chart_count ?? 0);
                            card.find(".other_followup_count").text(res.followup_count ?? 0);
                            card.find(".joined_count").text(res.joined_count ?? 0);
                            card.find(".pending_count").text(res.pending_count ?? 0);
                        }

                        // Update date label
                        let fromDate = form.find("[name='from_date']").val();
                        let toDate = form.find("[name='to_date']").val();

                        if (fromDate && toDate) {
                            let formattedFrom = formatDate(fromDate);
                            let formattedTo = formatDate(toDate);
                            card.find(".date_label").text(`Filtered: ${formattedFrom} - ${formattedTo}`);
                        } else {
                            if (hasDietData && !hasInquiryData) {
                                card.find(".date_label").text('Diet Chart Patients');
                            } else {
                                card.find(".date_label").text('Total Patients (All Sources)');
                            }
                        }
                    } else {
                        card.find(".patient_count").text('0');
                        card.find(".new_patient_count").text('0');
                        card.find(".followup_patient_count").text('0');
                        card.find(".diet_chart_count").text('0');
                        card.find(".other_followup_count").text('0');
                        card.find(".joined_count").text('0');
                        card.find(".pending_count").text('0');
                        card.find(".date_label").text('Error filtering data');
                    }
                }).fail(function (xhr, status, error) {
                    console.error('AJAX Error:', error);
                    card.find(".patient_count").text('Error');
                    card.find(".new_patient_count").text('0');
                    card.find(".followup_patient_count").text('0');
                    card.find(".diet_chart_count").text('0');
                    card.find(".other_followup_count").text('0');
                    card.find(".joined_count").text('0');
                    card.find(".pending_count").text('0');
                    card.find(".date_label").text('Error filtering data');
                }).always(function () {
                    // Restore button state
                    submitBtn.prop('disabled', false).text(originalText);
                });
            });

            // Helper function to format date
            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            }
        });
    </script>
@endsection