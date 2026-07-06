@extends('admin.layouts.layouts')
@section('title', 'Financial Dashboard')
@section('content')

    <div class="col-md-12 col-lg-10 m-auto p-0">
        <div class="card shadow-sm border-0 mb-5 overflow-hidden"
            style="border-radius: 16px; background: var(--card-bg, #fff);">
            <div class="card-header border-0 py-4 px-4" style="background: transparent;">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h3 class="fw-bold mb-1" style="color: #086838; letter-spacing: -0.5px;">Financial Dashboard</h3>
                        <p class="text-muted small mb-0 fw-medium">Summarized patient accounts and revenue tracking</p>
                    </div>
                    <div class="stats-pills d-flex gap-3">
                        <div class="stat-item px-3 py-2 rounded-pill bg-light border d-flex align-items-center shadow-sm">
                            <i class="fas fa-users-cog me-2 text-success opacity-75"></i>
                            <span class="text-muted small fw-bold">Active Accounts:</span>
                            <span class="fw-bold ms-2 text-dark">{{ $summary->total() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body px-4 pb-4">
                <!-- Filter Section -->
                <div class="bg-light p-4 rounded-4 mb-4 border search-box-themed shadow-sm">
                    <form method="GET" action="{{ route('transactions.index') }}" id="filterForm">
                        <div class="row g-3 align-items-end">
                            <!-- Search -->
                            <div class="col-lg-4 col-md-12">
                                <label class="form-label small text-muted fw-bold mb-2">SEARCH ACCOUNT</label>
                                <div class="input-group border rounded-3 overflow-hidden bg-white shadow-sm">
                                    <span class="input-group-text bg-white border-0"><i
                                            class="fas fa-search text-muted opacity-50"></i></span>
                                    <input type="text" name="search" class="form-control border-0 ps-0"
                                        placeholder="Patient Name or ID..." value="{{ request('search') }}">
                                </div>
                            </div>

                            
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label small text-muted fw-bold mb-2">TIME PERIOD</label>
                                <select name="date_filter" id="dateFilterSelect"
                                    class="form-select border rounded-3 shadow-sm" style="height: 45px;">
                                    <option value="" {{ request('date_filter') == '' ? 'selected' : '' }}>All Time History
                                    </option>
                                    <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today's
                                        Summary</option>
                                    <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>This Week
                                    </option>
                                    <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>This Month
                                    </option>
                                    <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom
                                        Range</option>
                                </select>
                            </div>

                            <!-- Custom Range (Hidden until 'custom' selected) -->
                            <div class="col-lg-5 col-md-6" id="customDateRange"
                                style="display: {{ request('date_filter') == 'custom' ? 'block' : 'none' }};">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label small text-muted fw-bold mb-2">START DATE</label>
                                        <input type="date" name="start_date" class="form-control border rounded-3 shadow-sm"
                                            value="{{ request('start_date') }}" style="height: 45px;">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small text-muted fw-bold mb-2">END DATE</label>
                                        <input type="date" name="end_date" class="form-control border rounded-3 shadow-sm"
                                            value="{{ request('end_date') }}" style="height: 45px;">
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-lg-auto ms-auto d-flex gap-2 pb-1">
                                <button type="submit"
                                    class="btn btn-success px-4 rounded-3 d-flex align-items-center justify-content-center shadow"
                                    style="background: #086838; border: none; height: 45px; min-width: 120px; fw-bold">
                                    <i class="fas fa-filter me-2 small"></i> Apply Filter
                                </button>
                                @if(request()->hasAny(['search', 'date_filter']))
                                    <a href="{{ route('transactions.index') }}"
                                        class="btn btn-outline-secondary px-3 rounded-3 d-flex align-items-center justify-content-center border-2 border-opacity-10"
                                        style="height: 45px;">
                                        <i class="fas fa-sync-alt opacity-75"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <script>
                    document.getElementById('dateFilterSelect').addEventListener('change', function () {
                        const customRange = document.getElementById('customDateRange');
                        if (this.value === 'custom') {
                            customRange.style.display = 'block';
                        } else {
                            customRange.style.display = 'none';
                            if (this.value !== '') {
                                document.getElementById('filterForm').submit();
                            }
                        }
                    });
                </script>

                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="text-white"
                            style="background: #086838; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1)); border: none;">
                            <tr>
                                <th class="ps-4 py-3 text-uppercase small border-0" style="letter-spacing: 1px;">Patient
                                    Identity</th>
                                <th class="text-uppercase small border-0" style="letter-spacing: 1px;">Branch</th>
                                <th class="text-end text-uppercase small border-0" style="letter-spacing: 1px;">Actual Value
                                </th>
                                <th class="text-end text-uppercase small border-0" style="letter-spacing: 1px;">Total
                                    Received</th>
                                <th class="text-center text-uppercase small border-0" style="letter-spacing: 1px;">Payments
                                </th>
                                <th class="text-center text-uppercase small border-0" style="letter-spacing: 1px;">Status
                                </th>
                                <th class="text-center pe-4 text-uppercase small border-0" style="letter-spacing: 1px;">
                                    Ledger</th>
                                <th class="text-center pe-4 text-uppercase small border-0" style="letter-spacing: 1px;">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($summary as $row)
                                <tr class="account-row" style="white-space: nowrap;">
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3 d-flex align-items-center justify-content-center fw-bold text-white shadow-sm position-relative overflow-hidden"
                                                style="width: 44px; height: 44px; background: linear-gradient(135deg, #086838, #10b981); border-radius: 12px; border: 2px solid rgba(255,255,255,0.8);">
                                                <span class="avatar-initial">
                                                    {{ substr($row->patient->patient_name ?? 'P', 0, 1) }}
                                                </span>
                                                @if(!empty($row->profile_image_url))
                                                    <img src="{{ $row->profile_image_url }}" alt="Profile"
                                                        style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:12px;"
                                                        onload="this.closest('.avatar-circle').querySelector('.avatar-initial').style.display='none';"
                                                        onerror="this.remove();">
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark fs-6">{{ $row->patient->patient_name ?? 'N/A' }}</div>
                                                <div class="d-flex flex-wrap gap-1 align-items-center mt-1">
                                                    <span class="badge bg-light text-muted font-monospace py-1"
                                                        style="font-size: 10px; border: 1px solid rgba(0,0,0,0.05);">
                                                        {{ $row->patient->patient_id ?? 'No ID' }}
                                                    </span>
                                                    @if($row->invoice)
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary font-monospace py-1"
                                                            style="font-size: 10px; border: 1px solid rgba(0,0,0,0.05); background: rgba(108,117,125,0.08); color: #6c757d !important;">
                                                            #{{ $row->invoice->invoice_no }}
                                                        </span>
                                                        
                                                        @php
                                                            $progNames = [];
                                                            $pData = $row->invoice->programs_data;
                                                            if (is_string($pData)) $pData = json_decode($pData, true);
                                                            if (!empty($pData) && is_array($pData)) {
                                                                foreach($pData as $p) {
                                                                    $progNames[] = $p['program_name'];
                                                                }
                                                            }
                                                            
                                                            $cData = $row->invoice->charges_data;
                                                            if (is_string($cData)) $cData = json_decode($cData, true);
                                                            if (!empty($cData) && is_array($cData)) {
                                                                foreach($cData as $c) {
                                                                    $cName = $c['charge_name'] ?? 'Charge';
                                                                    if (in_array($cName, ['Registration Charges', 'Registration', 'SVC-Charge', 'Followup Charges', 'Follow up charges', 'Consulting charges', 'Registration & Consultation Charges'])) {
                                                                        if ($row->branch_id === 'LB-0007') $cName = 'LHR Service';
                                                                        elseif ($row->branch_id === 'BH-00023') $cName = 'Hydra Service';
                                                                        elseif ($row->branch_id === 'SVC-0005') $cName = 'SVC Service';
                                                                        else $cName = 'FNF Service';
                                                                    }
                                                                    $progNames[] = $cName;
                                                                }
                                                            }
                                                            if (empty($pData) && empty($cData) && $row->invoice->program) {
                                                                $progNames[] = $row->invoice->program->program_name;
                                                            }
                                                        @endphp
                                                        @if(!empty($progNames))
                                                            <span class="badge bg-success bg-opacity-10 text-success py-1"
                                                                style="font-size: 10px; border: 1px solid rgba(8,104,56,0.05); background: rgba(8, 104, 56, 0.05); color: #086838 !important;">
                                                                {{ implode(', ', array_unique($progNames)) }}
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-warning bg-opacity-10 text-warning py-1 font-monospace"
                                                            style="font-size: 10px; border: 1px solid rgba(245,158,11,0.05); background: rgba(245,158,11,0.08); color: #f59e0b !important;">
                                                            General Account
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill fw-bold text-success px-3 py-2"
                                            style="background: rgba(8, 104, 56, 0.08); font-size: 10px;">
                                            <i class="fas fa-map-marker-alt me-1 opacity-50"></i> {{ $row->branch_id }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold d-none d-sm-table-cell">
                                        ₹{{ number_format($row->total_billed, 2) }}</td>
                                    <td class="text-end text-success fw-bold">+ ₹{{ number_format($row->total_paid, 2) }}</td>
                                    <td class="text-center font-monospace small">
                                        <span class="badge bg-light text-dark border">{{ $row->payment_count }} Times</span>
                                    </td>
                                    <td class="text-center">
                                        @if($row->balance > 0)
                                            <span class="balance-pill due">
                                                ₹{{ number_format($row->balance, 2) }} Due
                                            </span>
                                        @else
                                            <span class="balance-pill paid">
                                                <i class="fas fa-check-circle me-1 small"></i> Balanced
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center pe-4">
                                        <a href="{{ route('transactions.ledger', [$row->patient_id, $row->branch_id]) }}"
                                            class="btn-ledger shadow-sm" title="Open Patient Ledger">
                                            <i class="fas fa-id-badge fs-5"></i>
                                        </a>
                                    </td>
                                    <td class="text-center pe-4">
                                        <button class="btn-delete-txn"
                                            title="Delete all transactions & invoice for this patient"
                                            data-patient-id="{{ $row->patient_id }}"
                                            data-invoice-id="{{ $row->invoice_id ?? '' }}"
                                            data-name="{{ $row->patient->patient_name ?? 'this patient' }}"
                                            onclick="confirmDeleteTransaction(this)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted opacity-50">
                                            <i class="fas fa-search-dollar fa-4x mb-3"></i>
                                            <p class="fw-bold">No financial accounts matching your search.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 px-3 d-flex justify-content-between align-items-center">
                    <div class="text-muted small fw-medium">
                        Showing {{ $summary->firstItem() ?? 0 }} to {{ $summary->lastItem() ?? 0 }} of
                        {{ $summary->total() ?? 0 }} entries
                    </div>
                    <div>
                        {{ $summary->withQueryString()->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary: #086838;
            --secondary: #10b981;
        }

        .account-row {
            transition: all 0.2s ease;
            border-bottom: 1px solid rgba(0, 0, 0, 0.02) !important;
        }

        .account-row:hover {
            background: rgba(8, 104, 56, 0.015) !important;
            transform: translateY(-1px);
        }

        .balance-pill {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 30px;
            font-weight: 800;
            font-size: 11px;
            min-width: 110px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .balance-pill.due {
            background: #fee2e2;
            color: #dc2626;
            box-shadow: 0 2px 4px rgba(220, 38, 38, 0.1);
        }

        .balance-pill.paid {
            background: #dcfce7;
            color: #16a34a;
            box-shadow: 0 2px 4px rgba(22, 163, 74, 0.1);
        }

        .btn-ledger {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            color: var(--primary);
            border: 1px solid rgba(8, 104, 56, 0.2);
            border-radius: 14px;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .btn-ledger:hover {
            background: var(--primary);
            color: #fff;
            transform: rotate(-5deg) scale(1.1);
            box-shadow: 0 4px 12px rgba(8, 104, 56, 0.2);
        }

        .btn-delete-txn {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            color: #dc2626;
            border: 1px solid rgba(220, 38, 38, 0.3);
            border-radius: 12px;
            transition: all 0.25s ease;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-delete-txn:hover {
            background: #dc2626;
            color: #fff;
            transform: scale(1.08);
            box-shadow: 0 4px 10px rgba(220,38,38,0.25);
        }
        html.dark .btn-delete-txn {
            background: #2d3748;
            border-color: rgba(220,38,38,0.4);
            color: #f87171;
        }
        html.dark .btn-delete-txn:hover {
            background: #dc2626;
            color: #fff;
        }

        /* Dark Mode Overrides */
        html.dark .card {
            background: #1a222c !important;
            border: 1px solid #2d3748 !important;
        }

        html.dark .text-dark {
            color: #f3f4f6 !important;
        }

        html.dark .text-muted {
            color: #8a99af !important;
        }

        html.dark .bg-light {
            background: #121821 !important;
            color: #8a99af !important;
            border-color: #2d3748 !important;
        }

        html.dark .search-box-themed {
            border-color: #2d3748 !important;
        }

        html.dark .input-group-text,
        html.dark .form-control,
        html.dark .form-select {
            background: #0b1118 !important;
            border-color: #2d3748 !important;
            color: #f3f4f6 !important;
        }

        html.dark .account-row:hover {
            background: rgba(255, 255, 255, 0.02) !important;
        }

        html.dark .balance-pill.due {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        html.dark .balance-pill.paid {
            background: rgba(16, 185, 129, 0.2);
            color: #4ade80;
        }

        html.dark .btn-ledger {
            background: #2d3748;
            border-color: #313d4a;
            color: #10b981;
        }

        html.dark .btn-ledger:hover {
            background: #10b981;
            color: #000;
        }
    </style>

    <script>
    function confirmDeleteTransaction(btn) {
        const name      = btn.dataset.name;
        const patientId = btn.dataset.patientId;
        const invoiceId = btn.dataset.invoiceId;

        Swal.fire({
            title: 'Delete Transactions?',
            html: `Delete all transactions & invoice for <strong>${name}</strong>?<br>
                   <small class="text-danger">This cannot be undone.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Yes, Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (!result.isConfirmed) return;

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

            fetch('{{ route("transaction.delete") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ patient_id: patientId, invoice_id: invoiceId })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Deleted!', data.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error!', data.message || 'Something went wrong', 'error');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-trash-alt"></i>';
                }
            })
            .catch(() => {
                Swal.fire('Error!', 'Network error', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-trash-alt"></i>';
            });
        });
    }
    </script>
@endsection