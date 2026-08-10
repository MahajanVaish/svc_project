@extends('admin.layouts.layouts')
@section('title', 'Patient Ledger History')
@section('content')

<div class="col-md-12 col-lg-10 m-auto p-0">
    <!-- Patient Profile Summary Header -->
    <div class="card shadow-sm border-0 mb-4 overflow-hidden" style="border-radius: 16px; background: linear-gradient(135deg, #086838, #10b981);">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex align-items-center justify-content-between text-white flex-wrap gap-4">
                <div class="d-flex align-items-center">
                    <div class="avatar-large me-4 d-flex align-items-center justify-content-center fw-bold shadow-lg" 
                         style="width: 80px; height: 80px; background: #fff; color: #086838; border-radius: 20px; font-size: 32px; border: 4px solid rgba(255,255,255,0.2);">
                        {{ substr($patient->patient_name ?? 'P', 0, 1) }}
                    </div>
                    <div>
                        <h1 class="mb-1 fw-bold h3">{{ $patient->patient_name ?? 'N/A' }}</h1>
                        <div class="d-flex flex-wrap gap-2 align-items-center ">
                            <span class="badge bg-white bg-opacity-25 text-black border border-white border-opacity-25 px-3 py-2 rounded-pill small">
                                <i class="fas fa-id-card me-1 small opacity-75"></i> {{ $patient->patient_id ?? 'No ID' }}
                            </span>
                            <span class="small opacity-75 border-start border-white border-opacity-25 ps-2">
                                <i class="fas fa-map-marker-alt me-1"></i> {{ $branch_id }} Branch
                            </span>
                        </div>
                    </div>  
                </div>

                <div class="d-flex gap-3 ledger-stats-container">
                    <div class="stat-card text-center p-3 rounded-4 shadow-sm">
                        <div class="text-uppercase small opacity-75 mb-1" style="font-size: 10px; letter-spacing: 1px; font-weight: 700;">Total Billed</div>
                        <div class="h4 fw-bold mb-0">₹{{ number_format($totalBilled, 2) }}</div>
                    </div>
                    <div class="stat-card text-center p-3 rounded-4 shadow-sm">
                        <div class="text-uppercase small opacity-75 mb-1" style="font-size: 10px; letter-spacing: 1px; font-weight: 700;">Total Paid</div>
                        <div class="h4 fw-bold mb-0 text-success-light">₹{{ number_format($totalPaid, 2) }}</div>
                    </div>
                    @php 
                        $currentRealBalance = $totalBilled - $totalPaid - $totalDiscount;
                    @endphp
                    <div class="stat-card featured text-center p-3 rounded-4 shadow">
                        <div class="text-uppercase small mb-1" style="font-size: 10px; letter-spacing: 1px; font-weight: 800; color: #086838;">Current Balance</div>
                        <div class="h4 fw-bold mb-0 {{ $currentRealBalance > 0 ? 'text-danger' : ($currentRealBalance < 0 ? 'text-primary' : 'text-success') }}">
                            ₹{{ number_format($currentRealBalance, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Program Payment Analysis Section -->
    <div class="row mb-4">
        @foreach($programGroups as $id => $group)
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; border-left: 5px solid {{ $group['is_completed'] ? '#10b981' : '#f59e0b' }} !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="text-uppercase text-muted fw-bold mb-1" style="font-size: 11px; letter-spacing: 1px;">Program Plan</h6>
                            <h5 class="fw-bold mb-0" style="color: #086838;">{{ $group['program_name'] ?? 'Service' }}</h5>
                        </div>
                        <span class="badge {{ $group['is_completed'] ? 'bg-success' : 'bg-warning' }} rounded-pill px-3 py-2">
                            {{ $group['is_completed'] ? 'Fully Paid' : 'Payment Pending' }}
                        </span>
                    </div>

                    <div class="row mb-3">
                        <div class="col-4">
                            <div class="text-muted small">Price</div>
                            <div class="h6 fw-bold mb-0">₹{{ number_format($group['actual_price'], 2) }}</div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="text-muted small">Discount</div>
                            <div class="h6 fw-bold mb-0 text-info">₹{{ number_format($group['total_discount'], 2) }}</div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="text-muted small">Paid</div>
                            <div class="h6 fw-bold mb-0 text-success">₹{{ number_format($group['total_received'], 2) }}</div>
                        </div>
                    </div>

                    @php 
                        $satisfiedAmount = $group['total_received'] + $group['total_discount'];
                        $percentage = $group['actual_price'] > 0 ? min(100, ($satisfiedAmount / $group['actual_price']) * 100) : 0;
                    @endphp
                    <div class="progress rounded-pill mb-3" style="height: 8px; background: rgba(0,0,0,0.05);">
                        <div class="progress-bar {{ $group['is_completed'] ? 'bg-success' : 'bg-warning' }}" role="progressbar" style="width: {{ $percentage }}%"></div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            <i class="fas fa-history me-1 opacity-50"></i> {{ $group['payment_count'] }} Payments Received
                        </div>
                        @if(!$group['is_completed'])
                        <div class="fw-bold text-danger small">
                            Balance: ₹{{ number_format($group['actual_price'] - $group['total_received'] - $group['total_discount'], 2) }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Ledger Details Table -->
    <div class="card shadow-sm border-0 mb-5" style="border-radius: 16px; background: var(--card-bg, #fff);">
        <div class="card-header border-0 bg-transparent py-4 px-4 d-flex flex-wrap gap-3 justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-0" style="color: #086838;">Receipt History</h5>
                <p class="text-muted small mb-0">Detailed list of all payments received from this patient</p>
            </div>
            <div class="actions d-flex gap-2 align-items-center flex-wrap">
                <!-- Filter Form -->
                <form method="GET" action="{{ url()->current() }}" class="d-flex gap-2 align-items-center me-2 no-print">
                    <input type="date" name="start_date" class="form-control form-control-sm border-0 bg-light rounded-3 px-3 shadow-none" 
                           value="{{ request('start_date') }}" placeholder="Start Date" style="height: 38px;">
                    <span class="text-muted small">to</span>
                    <input type="date" name="end_date" class="form-control form-control-sm border-0 bg-light rounded-3 px-3 shadow-none" 
                           value="{{ request('end_date') }}" placeholder="End Date" style="height: 38px;">
                    
                    <button type="submit" class="btn btn-sm btn-success px-3 rounded-3 shadow-sm border-0" 
                            style="background: #086838; height: 38px; font-weight: 600;">
                        <i class="fas fa-filter"></i>
                    </button>

                    @if(request()->hasAny(['start_date', 'end_date']))
                        <a href="{{ url()->current() }}" class="btn btn-sm btn-light border px-3 rounded-3 shadow-sm d-flex align-items-center" 
                           style="height: 38px;" title="Reset Filter">
                            <i class="fas fa-sync-alt opacity-75"></i>
                        </a>
                    @endif
                </form>

                <button onclick="window.print()" class="btn btn-sm btn-print px-4 py-2 rounded-3 fw-bold shadow-sm" style="height: 38px;">
                    <i class="fas fa-print me-2"></i>Print Statement
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light bg-opacity-50">
                        <tr>
                            <th class="ps-4 text-muted text-uppercase small py-3" style="letter-spacing: 1px;">Date & Time</th>
                            <th class="text-muted text-uppercase small" style="letter-spacing: 1px;">Payment Description</th>
                            <th class="text-center text-muted text-uppercase small" style="letter-spacing: 1px;">Mode</th>
                            <th class="text-end text-muted text-uppercase small" style="letter-spacing: 1px;">Amount Received</th>
                            <th class="text-end pe-4 text-muted text-uppercase small" style="letter-spacing: 1px;">Patient Balance</th>
                        </tr>
                    </thead>
                    <tbody class="ledger-body">
                        @php $hasTransactions = false; @endphp
                        @foreach ($transactions as $t)
                        @php $hasTransactions = true; @endphp
                        <tr class="row-{{ $t->type }} transaction-row">
                            <td class="ps-4 py-4">
                                <div class="fw-bold text-dark">{{ $t->created_at->format('d M, Y') }}</div>
                                <div class="text-muted small mt-1"><i class="far fa-clock me-1 small opacity-50"></i> {{ $t->created_at->format('h:i A') }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark mb-1">
                                    {{ $t->description }}
                                </div>
                                
                                @if($t->invoice)
                                    <!-- Display All Programs from Invoice Data -->
                                    @php
                                        $programsData = $t->invoice->programs_data;
                                        if (is_string($programsData)) $programsData = json_decode($programsData, true);
                                        
                                        $chargesData = $t->invoice->charges_data;
                                        if (is_string($chargesData)) $chargesData = json_decode($chargesData, true);
                                    @endphp

                                    @if(!empty($programsData) && is_array($programsData))
                                        @foreach($programsData as $prog)
                                            <span class="badge bg-program px-2 py-1 rounded bg-opacity-10 text-success small fw-medium mb-1 me-1" style="font-size: 11px; background: rgba(8, 104, 56, 0.08); color: #086838 !important;">
                                                <i class="fas fa-layer-group me-1 opacity-50"></i> {{ $prog['program_name'] ?? 'Service' }}
                                            </span>
                                        @endforeach
                                    @elseif($t->invoice->program)
                                        <span class="badge bg-program px-2 py-1 rounded bg-opacity-10 text-success small fw-medium mb-1 me-1" style="font-size: 11px; background: rgba(8, 104, 56, 0.08); color: #086838 !important;">
                                            <i class="fas fa-layer-group me-1 opacity-50"></i> {{ $t->invoice->program->program_name }}
                                        </span>
                                    @elseif($t->program)
                                        <span class="badge bg-program px-2 py-1 rounded bg-opacity-10 text-success small fw-medium mb-1 me-1" style="font-size: 11px; background: rgba(8, 104, 56, 0.08); color: #086838 !important;">
                                            <i class="fas fa-layer-group me-1 opacity-50"></i> {{ $t->program->program_name }}
                                        </span>
                                    @elseif(!empty($onlineProgramLabel))
                                        {{-- Fallback for online/abroad patients with no programs_data in invoice --}}
                                        <span class="badge px-2 py-1 rounded small fw-medium mb-1 me-1" style="font-size: 11px; background: rgba(139, 92, 246, 0.1); color: #7c3aed !important;">
                                            <i class="fas fa-globe me-1 opacity-50"></i> {{ $onlineProgramLabel }}
                                        </span>
                                    @endif

                                    <!-- Display Charges from Invoice Data -->
                                    @if(!empty($chargesData) && is_array($chargesData))
                                        @php
                                            $consolidatedCharges = [];
                                            foreach($chargesData as $charge) {
                                                $name = $charge['charge_name'] ?? 'Charge';
                                                
                                                // Branch renaming fallback
                                                if (in_array($name, ['Registration Charges', 'Registration', 'SVC-Charge', 'Followup Charges', 'Follow up charges', 'Consulting charges', 'Registration & Consultation Charges'])) {
                                                    if ($branch_id === 'LB-0007') $name = 'LHR Service';
                                                    elseif ($branch_id === 'BH-00023') $name = 'Hydra Service';
                                                    elseif ($branch_id === 'SVC-0005') $name = 'SVC Service';
                                                    else $name = 'FNF Service';
                                                }
                                                
                                                if (!in_array($name, $consolidatedCharges)) {
                                                    $consolidatedCharges[] = $name;
                                                }
                                            }
                                        @endphp
                                        @foreach($consolidatedCharges as $chargeName)
                                            <span class="badge px-2 py-1 rounded bg-opacity-10 text-primary small fw-medium mb-1 me-1" style="font-size: 11px; background: rgba(13, 110, 253, 0.08); color: #0d6efd !important;">
                                                <i class="fas fa-file-invoice-dollar me-1 opacity-50"></i> {{ $chargeName }}
                                            </span>
                                        @endforeach
                                    @endif

                                    <div class="small text-muted mt-1">
                                        <a href="{{ route('view.invoice', ['id' => $t->invoice_id, 'transaction_id' => $t->id]) }}" target="_blank" class="text-success text-decoration-none fw-bold small">
                                            <i class="fas fa-file-invoice me-1"></i> Receipt #{{ $t->invoice->invoice_no }} (TRX-{{ $t->id }}) <i class="fas fa-external-link-alt ms-1" style="font-size: 10px;"></i>
                                        </a>
                                    </div>
                                @elseif($t->program)
                                    <span class="badge bg-program px-2 py-1 rounded bg-opacity-10 text-success small fw-medium" style="font-size: 11px; background: rgba(8, 104, 56, 0.08); color: #086838 !important;">
                                        <i class="fas fa-layer-group me-1 opacity-50"></i> {{ $t->program->program_name }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="type-pill {{ $t->type }}">
                                    @if($t->type == 'discount')
                                        DISCOUNT
                                    @elseif($t->type == 'credit')
                                        RECEIVED
                                    @else
                                        BILLED
                                    @endif
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="fw-bold fs-6 {{ $t->type == 'credit' || $t->type == 'discount' ? 'text-success' : 'text-danger' }}">
                                    {{ $t->type == 'credit' || $t->type == 'discount' ? '+' : '-' }} ₹{{ number_format($t->amount, 2) }}
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="fw-bold text-dark fs-6 font-monospace">₹{{ number_format($t->running_balance, 2) }}</div>
                            </td>
                        </tr>
                        @endforeach

                        @if(!$hasTransactions)
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="opacity-25 pb-3"><i class="fas fa-file-invoice-dollar fa-4x text-muted"></i></div>
                                <p class="text-muted fw-medium">No transaction records found for this account.</p>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light bg-opacity-25 border-0 py-4 text-center no-print">
            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary btn-sm px-4 py-2 rounded-pill border-0 shadow-none text-muted fw-bold">
                <i class="fas fa-arrow-left me-2"></i> Return to Financial Dashboard
            </a>
        </div>
    </div>
</div>

<style>
    /* Premium Theming */
    :root {
        --primary: #086838;
        --secondary: #10b981;
    }

    .stat-card {
        min-width: 140px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        transition: all 0.3s ease;
    }

    .stat-card.featured {
        background: #fff;
        color: #086838;
        border: none;
    }

    .text-success-light { color: #dcfce7; }

    .btn-print {
        background: rgba(8, 104, 56, 0.05);
        color: #086838;
        border: 1px solid rgba(8, 104, 56, 0.2);
        transition: all 0.2s ease;
    }

    .btn-print:hover {
        background: #086838;
        color: #fff;
        transform: translateY(-1px);
    }

    .transaction-row {
        transition: all 0.2s ease;
        border-bottom: 1px solid rgba(0,0,0,0.02) !important;
    }

    .transaction-row:hover {
        background: rgba(8, 104, 56, 0.01) !important;
        transform: scale(1.002);
    }

    .type-pill {
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.5px;
    }

    .type-pill.debit { background: #fee2e2; color: #b91c1c; }
    .type-pill.credit { background: #dcfce7; color: #15803d; }
    .type-pill.discount { background: #e0f2fe; color: #0369a1; }

    .row-debit { border-left: 5px solid #ef4444; }
    .row-credit { border-left: 5px solid #10b981; }
    .row-discount { border-left: 5px solid #0ea5e9; }

    /* Dark Mode Absolute Zero-White Protocol */
    html.dark .card { background: #1a222c !important; border: 1px solid #2d3748 !important; }
    html.dark .bg-light { background: #121821 !important; color: #718096 !important; }
    html.dark .text-dark { color: #f3f4f6 !important; }
    html.dark .text-muted { color: #718096 !important; }
    html.dark .stat-card.featured { background: #1a222c; border: 1px solid #10b981; color: #fff; }
    html.dark .stat-card.featured .text-uppercase { color: #10b981 !important; }
    html.dark .type-pill.debit { background: rgba(239, 68, 68, 0.2); color: #f87171; }
    html.dark .type-pill.credit { background: rgba(16, 185, 129, 0.2); color: #4ade80; }
    html.dark .btn-print { background: #2d3748; color: #f3f4f6; border-color: #4a5568; }
    html.dark .transaction-row:hover { background: rgba(255,255,255,0.02) !important; }
    html.dark .bg-program { background: rgba(16, 185, 129, 0.1) !important; color: #4ade80 !important; }

    @media print {
        @page { margin: 1cm; }
        .card-footer, .actions, .btn-print, #sidebar-wrapper, .navbar { display: none !important; }
        .card { border: 1px solid #eee !important; box-shadow: none !important; margin: 0 !important; }
        .col-md-12 { padding: 0 !important; width: 100% !important; margin: 0 !important; }
        .card-body.p-4 { padding: 0 !important; }
        .stat-card { border: 1px solid #eee !important; color: #000 !important; background: #fff !important; backdrop-filter: none !important; }
        .text-white { color: #000 !important; }
        .card[style*="linear-gradient"] { background: #fff !important; border: 2px solid #086838 !important; }
        .avatar-large { border: 2px solid #086838 !important; }
    }
</style>

@endsection
