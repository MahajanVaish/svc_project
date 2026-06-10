@extends('admin.layouts.layouts')

@section('title', 'SVC Invoice')


@section('content')
    <!-- Include Select2 CSS directly here since stack('styles') might be missing in layout -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Custom Select2 styling to match Bootstrap 5 form-select */
        .select2-container .select2-selection--multiple {
            min-height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #086838;
            border: 1px solid #086838;
            color: #fff;
            border-radius: 4px;
            padding-left: 5px;
            padding-right: 5px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff;
            margin-right: 5px;
            border-right: 1px solid rgba(255, 255, 255, 0.3);
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
    </style>



    <div class="col-md-12 col-lg-10 m-auto p-0">
        <div class="card rounded shadow mb-5">
            <div class="card-header">
                <div class="heading-action">
                    <h3 class="bold font-up fnf-title">Invoice</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-auto">
                    <div class="bg-light rounded-5">
                        <div class="w-100 p-4 pb-4">
                            <style>
                                label.form-label {
                                    font-weight: 600;
                                    color: #5a6268;
                                    display: block;
                                    margin-bottom: 4px;
                                    font-size: 13px;
                                }

                                .form-control,
                                .form-select {
                                    padding: 6px 10px;
                                    font-size: 13px;
                                    border-radius: 6px;
                                }

                                .mb-3 {
                                    margin-bottom: 1rem !important;
                                }

                                @media (max-width: 768px) {
                                    .w-100.p-4 {
                                        padding: 15px !important;
                                    }
                                    .btn-primary.px-5 {
                                        width: 100% !important;
                                        padding-left: 0 !important;
                                        padding-right: 0 !important;
                                    }
                                    .select2-container {
                                        width: 100% !important;
                                    }
                                    .row.mb-3 > div {
                                        margin-bottom: 15px;
                                    }
                                    .row {
                                        flex-direction: column;
                                    }
                                    .col-md-4, .col-md-3, .col-md-6, .col-md-12 {
                                        width: 100% !important;
                                    }
                                }
                            </style>
                            <form id="invoiceForm" method="POST" action="{{ route('store.invoice') }}">
                                @csrf
                                <!-- First Row -->
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Select branch</label>
                                            <select class="form-select" name="branch_id" id="branch_select" required>
                                                <option value="">Select Branch</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->branch_id }}" {{ old('branch_id') == $branch->branch_id ? 'selected' : '' }}>
                                                        {{ $branch->branch_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('branch_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Name</label>
                                            <select class="form-select" name="patient_id" id="patient_select" required>
                                                <option value="">Select Patient</option>
                                                @foreach ($patients as $patient)
                                                    <option value="{{ $patient->id }}"
                                                        data-patient-id="{{ $patient->patient_id }}"
                                                        data-address="{{ $patient->address }}" data-age="{{ $patient->age }}"
                                                        data-diagnosis="{{ $patient->diagnosis }}"
                                                        data-inquiry-date="{{ $patient->inquiry_date }}"
                                                        data-phone="{{ $patient->phone }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                                        {{ $patient->patient_name }} ({{ $patient->patient_id }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('patient_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3" id="charges_container" style="display: none;">
                                            <label class="form-label fw-bold">Select Charges</label>
                                            <select class="form-select" name="charges[]" id="charges_select" multiple>
                                                @foreach ($charges as $charge)
                                                    <option value="{{ $charge->id }}" data-price="{{ $charge->charges_price }}"
                                                        {{ old('charges') && in_array($charge->id, old('charges')) ? 'selected' : '' }}>
                                                        {{ $charge->charges_name }} - ₹{{ $charge->charges_price }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3" id="program_container" style="display: none;">
                                            <label class="form-label fw-bold">Select Program</label>
                                            <select class="form-control" name="program_ids[]" id="program_select" multiple>
                                                @foreach ($programs as $program)
                                                    <option value="{{ $program->id }}"
                                                        data-price="{{ $program->program_price }}"
                                                        data-branch="{{ $program->branch ?? '' }}" {{ old('program_ids') && in_array($program->id, old('program_ids')) ? 'selected' : '' }}>
                                                        {{ $program->program_name }} - ₹{{ $program->program_price }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('program_ids')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        @error('charges')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Second Row -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Invoice No.</label>
                                            <input type="text" class="form-control" id="invoice_no" name="invoice_no"
                                                placeholder="Invoice No." readonly value="{{ old('invoice_no') }}">
                                            @error('invoice_no')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Date</label>
                                            <input type="date" class="form-control" id="invoice_date" name="invoice_date"
                                                value="{{ old('invoice_date', date('Y-m-d')) }}">
                                            @error('invoice_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Third Row -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Address</label>
                                            <input type="text" class="form-control" id="patient_address" name="address"
                                                placeholder="Enter address" value="{{ old('address') }}">
                                            @error('address')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Phone Number</label>
                                            <input type="text" class="form-control" id="patient_phone" name="phone"
                                                placeholder="Enter phone" value="{{ old('phone') }}">
                                            @error('phone')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Fourth Row -->
                                <!-- Patient Selected Program History & Diet H/O Assigned Programs -->
                                <div class="row mb-3" id="invoice_history_container">
                              
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Selected Programs & Session</label>
                                            <div class="border rounded p-2 bg-white shadow-sm" style="min-height: 80px; max-height: 120px; overflow-y: auto; font-size: 13px; border-left: 4px solid #086838 !important;">
                                                <div id="lhr_details_content" style="display: none;">
                                                    <div class="row pt-1">
                                                        <div class="col-md-12 mb-2">
                                                            <span class="fw-bold text-success" style="font-size: 13px;">Selected Area:</span>
                                                            <span id="lhr_area_display" class="ms-1 fw-medium" style="font-size: 13px; color: #333;"></span>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <span class="fw-bold text-success" style="font-size: 13px;">Session:</span>
                                                            <span id="lhr_session_display" class="ms-1 fw-medium" style="font-size: 13px; color: #333;"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="lhr_details_empty">
                                                    <span class="text-muted">No area and session data found for this patient.</span>
                                                </div>
                                            </div>
                                            <small class="text-muted">*Displays chosen area and session from patient inquiry</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6" id="assigned_programs_container" style="display: none;">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-success">Include in Invoice Receipt</label>
                                            <div class="border rounded p-2 bg-white shadow-sm" style="min-height: 80px; max-height: 120px; overflow-y: auto; font-size: 13px; border-left: 4px solid #086838 !important;">
                                                <div id="assigned_programs_list">
                                                    <!-- Dynamic checkboxes will be added here -->
                                                </div>
                                            </div>
                                            <small class="text-muted">*Select specific programs to include on the receipt. If none are selected, the latest saved program will be used.</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Section -->
                                <div class="row g-3">
                                    <div class="col-12 col-sm-6 col-md-4 col-lg">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Pending Due Payment</label>
                                            <input type="number" step="0.01" min="0" class="form-control" id="pending_due"
                                                name="pending_due" value="{{ old('pending_due', '0') }}">
                                            @error('pending_due')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 col-lg">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Total Payment</label>
                                            <input type="text" class="form-control" id="total_payment" name="total_payment"
                                                readonly value="{{ old('total_payment') }}">
                                            @error('total_payment')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 col-lg">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Discount Payment</label>
                                            <input type="number" step="0.01" min="0" class="form-control" id="discount"
                                                name="discount" value="{{ old('discount', '0') }}">
                                            @error('discount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 col-lg">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Given Payment</label>
                                            <input type="number" step="0.01" min="0" class="form-control" id="given_payment"
                                                name="given_payment" value="{{ old('given_payment', '0') }}">
                                            @error('given_payment')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 col-lg">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Due Payment</label>
                                            <input type="text" class="form-control" id="due_payment" name="due_payment"
                                                readonly value="{{ old('due_payment') }}">
                                            @error('due_payment')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 col-lg">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Payment Method</label>
                                            <select class="form-select" name="payment_method" id="payment_method" required>
                                                <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                                <option value="Online" {{ old('payment_method') == 'Online' ? 'selected' : '' }}>Online</option>
                                                <option value="Cheque" {{ old('payment_method') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                                <option value="Card" {{ old('payment_method') == 'Card' ? 'selected' : '' }}>Card</option>
                                            </select>
                                            @error('payment_method')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>




                                <!-- Last Row -->
                                <div class="mt-5">
                                    <div class="d-flex align-items-end justify-content-end">
                                        <button type="submit" class="btn btn-primary fw-bold px-5 py-2">Generate
                                            Invoice</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-lg-10 m-auto p-0" id="previous_invoices">
        <!-- Grid row -->
        <div class="card rounded shadow mb-5">
            <div class="card-header">
                <div class="heading-action">
                    <h3 class="bold font-up fnf-title">Previous Invoices</h3>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('add.invoice') }}#previous_invoices" id="invoiceFilterForm">
                    <div
                        class="custom_filter_section d-md-block d-lg-flex align-items-end gap-3 mb-4 p-3 bg-light rounded-3 border">
                        <div class="filter-item flex-grow-1">
                            <label class="form-label small fw-bold text-muted mb-1 text-uppercase">Page Size</label>
                            <select name="per_page" class="form-select form-select-sm" id="per_page_select"
                                onchange="this.form.submit()">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 per page</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per page</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per page</option>
                            </select>
                        </div>

                        <div class="filter-item flex-grow-1">
                            <label class="form-label small fw-bold text-muted mb-1 text-uppercase">Date Period</label>
                            <select name="date_filter" id="dateFilterSelect" class="form-select form-select-sm"
                                onchange="toggleCustomDates(this.value)">
                                <option value="" {{ request('date_filter') == '' ? 'selected' : '' }}>All Time</option>
                                <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>This Week
                                </option>
                                <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>This Month
                                </option>
                                <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Range
                                </option>
                            </select>
                        </div>

                        <div id="customDateRange" class="d-flex gap-2 flex-grow-1"
                            style="display: {{ request('date_filter') == 'custom' ? 'flex' : 'none' }} !important;">
                            <div class="flex-grow-1">
                                <label class="form-label small fw-bold text-muted mb-1 text-uppercase">Start</label>
                                <input type="date" name="start_date" class="form-control form-control-sm"
                                    value="{{ request('start_date') }}">
                            </div>
                            <div class="flex-grow-1">
                                <label class="form-label small fw-bold text-muted mb-1 text-uppercase">End</label>
                                <input type="date" name="end_date" class="form-control form-control-sm"
                                    value="{{ request('end_date') }}">
                            </div>
                        </div>

                        <div class="filter-item flex-grow-2" style="min-width: 250px;">
                            <label class="form-label small fw-bold text-muted mb-1 text-uppercase">Search Invoices</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0"><i
                                        class="fas fa-search opacity-50"></i></span>
                                <input type="text" id="search_input" name="search" class="form-control border-start-0"
                                    placeholder="Patient Name or Invoice #" value="{{ request('search') }}">
                            </div>
                        </div>

                        <div class="filter-item d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-success px-3 fw-bold flex-grow-1">Apply</button>
                            @if(request()->hasAny(['search', 'date_filter', 'per_page']))
                                <a href="{{ route('add.invoice') }}#previous_invoices"
                                    class="btn btn-sm btn-outline-secondary px-2 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-sync-alt"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                <style>
                    @media (max-width: 991px) {
                        .custom_filter_section {
                            flex-direction: column !important;
                            align-items: stretch !important;
                        }
                        .filter-item {
                            width: 100%;
                        }
                        #customDateRange {
                            flex-direction: column !important;
                            gap: 10px !important;
                        }
                    }
                </style>

                <script>
                    function toggleCustomDates(val) {
                        const customRange = document.getElementById('customDateRange');
                        if (val === 'custom') {
                            customRange.style.setProperty('display', 'flex', 'important');
                        } else {
                            customRange.style.setProperty('display', 'none', 'important');
                            if (val !== '') {
                                document.getElementById('invoiceFilterForm').submit();
                            }
                        }
                    }
                </script>
                <!--Table-->
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table table-striped table-hover mb-0" id="invoices_table">
                        <thead style="background-color: #035c23; color: white;">
                            <tr>
                                <th class="py-2.5 px-3" style="width: 5%;">#</th>
                                <th class="py-2.5 px-3" style="width: 20%;">Name</th>
                                <th class="py-2.5 px-3" style="width: 10%;">Date</th>
                                <th class="py-2.5 px-3" style="width: 15%;">Invoice No</th>
                                <th class="py-2.5 px-3" style="width: 15%;">Due Amt</th>
                                <th class="py-2.5 px-3" style="width: 10%;">Status</th>
                                <th class="py-2.5 px-3 text-center" style="width: 25%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $index => $invoice)
                                                <tr style="white-space: nowrap;">
                                                    <td class="py-2.5 px-3">{{ ($invoices->currentPage() - 1) * $invoices->perPage() + $index + 1 }}</td>
                                                    <td class="py-2.5 px-3">{{ $invoice->resolved_patient->patient_name ?? 'N/A' }}</td>
                                                    <td class="py-2.5 px-3">{{ $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') : 'N/A' }}</td>
                                                    <td class="py-2.5 px-3">{{ $invoice->invoice_no ?? 'N/A' }}</td>
                                                    <td class="py-2.5 px-3">₹{{ number_format($invoice->due_payment, 2) }} / ₹{{ number_format($invoice->total_payment - $invoice->discount, 2) }}</td>
                                                    <td class="py-2.5 px-3">
                                                        @if($invoice->due_payment <= 0) <span class="badge bg-success">Paid</span>
                                                        @elseif($invoice->given_payment > 0)
                                                            <span class="badge bg-warning text-dark">Partial</span>
                                                        @else
                                                            <span class="badge bg-danger">Unpaid</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center py-2.5 px-3">
                                                        <div class="d-flex justify-content-end gap-3">
                                                            <!-- View -->
                                                            <a href="{{ route('view.invoice', $invoice->id) }}" target="_blank" title="View">
                                                                <i class="fas fa-file-alt" style="color: rgb(8, 104, 56); cursor: pointer;"></i>
                                                            </a>

                                                            <!-- Download -->
                                                            <a href="{{ route('download.invoice', $invoice->id) }}" title="Download">
                                                                <i class="fas fa-download" style="color: rgb(8, 104, 56); cursor: pointer;"></i>
                                                            </a>

                                                            <!-- Pay Button -->
                                                            @if($invoice->due_payment > 0)
                                                                <button type="button" class="btn btn-sm btn-primary pay-btn p-0 px-2"
                                                                    style="font-size: 12px; height: 24px;" data-bs-toggle="modal"
                                                                    data-bs-target="#payModal" data-id="{{ $invoice->id }}"
                                                                    data-invoice-no="{{ $invoice->invoice_no }}"
                                                                    data-due="{{ $invoice->due_payment }}"
                                                                    data-given="{{ $invoice->given_payment }}">
                                                                    Pay
                                                                </button>
                                                            @endif

                                                            <!-- Delete Button -->
                                                            <form id="delete-invoice-form-{{ $invoice->id }}"
                                                                action="{{ route('delete.invoice', $invoice->id) }}" method="POST"
                                                                style="display: none;">
                                                                @csrf
                                                            </form>
                                                            <button type="button" class="btn btn-outline-danger btn-sm delete-invoice-btn"
                                                                data-id="{{ $invoice->id }}" title="Delete">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-3">No invoices found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination and Info -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $invoices->firstItem() ?? 0 }} to {{ $invoices->lastItem() ?? 0 }} of
                        {{ $invoices->total() }} entries
                    </div>
                    <div>
                        {{ $invoices->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>



    <!-- Payment Modal -->
    <div class="modal fade" id="payModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add Payment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('invoice.add.payment') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="invoice_id" id="modal_invoice_id">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Invoice No</label>
                            <input type="text" class="form-control" id="modal_invoice_no" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Given Payment</label>
                            <input type="number" class="form-control" id="modal_given_payment" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Remaining Due Amount</label>
                            <input type="number" class="form-control" id="modal_pending_due" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Payment Amount (₹)</label>
                            <input type="number" class="form-control" name="amount" id="modal_amount" required min="1"
                                step="0.01">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Payment Date</label>
                            <input type="date" class="form-control" name="payment_date" value="{{ date('Y-m-d') }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Payment Method</label>
                            <select class="form-select" name="payment_method" required>
                                <option value="Cash">Cash</option>
                                <option value="Online">Online</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Card">Card</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Payment Modal Handler
            const payModal = document.getElementById('payModal');
            if (payModal) {
                payModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');
                    const invoiceNo = button.getAttribute('data-invoice-no');
                    const due = button.getAttribute('data-due');
                    const given = button.getAttribute('data-given');

                    document.getElementById('modal_invoice_id').value = id;
                    document.getElementById('modal_invoice_no').value = invoiceNo;
                    document.getElementById('modal_given_payment').value = given;
                    document.getElementById('modal_pending_due').value = due;
                    document.getElementById('modal_amount').value = due; // Default to full due amount
                    document.getElementById('modal_amount').max = due; // Prevent paying more than due
                });
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include SweetAlert2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <!-- Include Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



    <script>

        document.addEventListener('DOMContentLoaded', function () {
            console.log('Invoice Management JS Initialized');

            // Unified click handler for deletion (Delegated)
            document.addEventListener('click', function (e) {
                const deleteBtn = e.target.closest('.delete-invoice-btn');
                if (deleteBtn) {
                    e.preventDefault();
                    const id = deleteBtn.getAttribute('data-id');
                    console.log('Captured delete request for ID:', id);

                    if (typeof Swal === 'undefined') {
                        if (confirm("Are you sure you want to delete this invoice?")) {
                            const form = document.getElementById('delete-invoice-form-' + id);
                            if (form) form.submit();
                        }
                        return;
                    }

                    Swal.fire({
                        title: 'Delete Invoice?',
                        text: "This will permanently delete the invoice and all transactions. This action cannot be undone!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, cancel',
                        reverseButtons: true,
                        background: '#fff'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.getElementById('delete-invoice-form-' + id);
                            if (form) {
                                form.submit();
                            } else {
                                console.error('Delete form not found for ID:', id);
                            }
                        }
                    });
                }
            });

            // Initialize field visibility on page load
            document.addEventListener('DOMContentLoaded', function() {
                updateBranchFields();
                
                // Also update patient program list visibility
                const invoiceHistoryContainer = document.getElementById('invoice_history_container');
                const branchId = branchSelect ? branchSelect.value : '{{ auth()->user()->user_branch }}';
                
                if (branchId === 'SVC-0005') {
                    if (invoiceHistoryContainer) invoiceHistoryContainer.style.display = 'none';
                } else {
                    if (invoiceHistoryContainer) invoiceHistoryContainer.style.display = 'block';
                }
            });

            // Get elements
            const branchSelect = document.getElementById('branch_select');
            const patientSelect = document.getElementById('patient_select');
            const programSelect = document.getElementById('program_select');
            const chargesSelect = document.getElementById('charges_select');
            // const patientProgramList = document.getElementById('patient_program_list');
            const invoiceHistoryContainer = document.getElementById('invoice_history_container');

            // Check if user is SVC
            const isSVC = '{{ auth()->user()->user_branch }}' === 'SVC-0005';

            // Dynamic check for SVC branch
            function isCurrentSVC() {
                return branchSelect && branchSelect.value === 'SVC-0005';
            }

            // Function to update field visibility and filtering based on branch
            function updateBranchFields() {
                const branchId = branchSelect.value;
                const chargesContainer = document.getElementById('charges_container');
                const programContainer = document.getElementById('program_container');

                if (!branchId) {
                    if (chargesContainer) chargesContainer.style.display = 'none';
                    if (programContainer) programContainer.style.display = 'none';
                    return;
                }

                if (branchId === 'SVC-0005') {
                    // SVC Branch: Hide all program-related fields, show only charges
                    if (chargesContainer) chargesContainer.style.display = 'block';
                    if (programContainer) programContainer.style.display = 'none';
                    if (invoiceHistoryContainer) invoiceHistoryContainer.style.display = 'none';
                    // Reset program selection
                    if (programSelect) $(programSelect).val(null).trigger('change');
                } else {
                    // Other branches: Show program fields, hide charges
                    if (chargesContainer) chargesContainer.style.display = 'none';
                    if (programContainer) programContainer.style.display = 'block';
                    if (invoiceHistoryContainer) invoiceHistoryContainer.style.display = 'block';
                    // Reset charges selection
                    if (chargesSelect) $(chargesSelect).val(null).trigger('change');

                    // Filter programs
                    if (programSelect) {
                        const options = programSelect.options;
                        for (let i = 0; i < options.length; i++) {
                            const opt = options[i];
                            const branchAttr = opt.getAttribute('data-branch');

                            if (branchId === 'LB-0007') {
                                // LHR Branch logic: Programs specifically marked LHR or ALL
                                if (branchAttr === 'LHR' || branchAttr === 'ALL') {
                                    opt.disabled = false;
                                    $(opt).show();
                                } else {

                                    opt.disabled = true;
                                    $(opt).hide();
                                }
                            } else {
                                // Other Branches: Show anything that is NOT LHR
                                if (branchAttr !== 'LHR') {
                                    opt.disabled = false;
                                    $(opt).show();
                                } else {
                                    opt.disabled = true;
                                    $(opt).hide();
                                }
                            }
                        }

                        // Refresh Select2 to show/hide options
                        if (typeof $ !== 'undefined' && $.fn.select2 && $(programSelect).data('select2')) {
                            $(programSelect).select2('destroy');
                            $(programSelect).select2({
                                placeholder: "Select Program",
                                allowClear: true,
                                width: '100%'
                            });
                        }
                    }
                }
                calculatePayments(false);
            }

            // Initialize Select2 for both if they exist
            if (programSelect) {
                if (typeof $ !== 'undefined' && $.fn.select2) {
                    $(programSelect).select2({
                        placeholder: "Select Program",
                        allowClear: true,
                        width: '100%'
                    });
                    $(programSelect).on('change', function () {
                        calculatePayments(true);
                    });
                } else {
                    programSelect.addEventListener('change', function () {
                        calculatePayments(true);
                    });
                }
            }

            if (chargesSelect) {
                if (typeof $ !== 'undefined' && $.fn.select2) {
                    $(chargesSelect).select2({
                        placeholder: "Select Charges",
                        allowClear: true,
                        width: '100%'
                    });
                    $(chargesSelect).on('change', function () {
                        calculatePayments(true);
                    });
                } else {
                    chargesSelect.addEventListener('change', function () {
                        calculatePayments(true);
                    });
                }
            }

            // Function to check if branch is selected
            function isBranchSelected() {
                return branchSelect && branchSelect.value !== '';
            }

            // Helper to check if current branch is SVC
            function isCurrentSVC() {
                return branchSelect && branchSelect.value === 'SVC-0005';
            }

            // Function to show SweetAlert notification
            function showBranchAlert() {
                Swal.fire({
                    title: 'Branch Required!',
                    text: 'Please select branch first',
                    icon: 'warning',
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    toast: true,
                    background: '#fff',
                    iconColor: '#ff9800',
                    customClass: {
                        popup: 'premium-toast',
                        title: 'premium-toast-title',
                        content: 'premium-toast-content'
                    }
                });
            }

            // Branch selection - Fetch Patients
            if (branchSelect) {
                branchSelect.addEventListener('change', function () {
                    const branchId = this.value;
                    const patientDropdown = document.getElementById('patient_select');

                    // Toggle visibility and filter programs
                    updateBranchFields();

                    // Clear existing options
                    patientDropdown.innerHTML = '<option value="">Select Patient</option>';

                    // Clear dependent fields
                    document.getElementById('patient_address').value = '';
                    document.getElementById('patient_phone').value = '';
                    document.getElementById('invoice_no').value = '';
                    // if (patientProgramList) patientProgramList.value = '';

                    if (branchId) {
                        // Show loading state
                        patientDropdown.innerHTML = '<option value="">Loading patients...</option>';

                        // Fetch patients for this branch
                        fetch("{{ route('invoice.get.patients') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({ branch_id: branchId })
                        })
                            .then(response => {
                                if (!response.ok) throw new Error('Network response was not ok');
                                return response.json();
                            })
                            .then(data => {
                                patientDropdown.innerHTML = '<option value="">Select Patient</option>';
                                if (data.success && data.patients.length > 0) {
                                    data.patients.forEach(patient => {
                                        const option = document.createElement('option');
                                        option.value = patient.id;
                                        const rawName = (patient.patient_name || '').toString().trim();
                                        const rawId = (patient.patient_id || '').toString().trim();
                                        let displayText = rawName !== '' ? rawName : (rawId !== '' ? `Patient (ID: ${rawId})` : 'Patient');
                                        if (rawName !== '' && rawId !== '') {
                                            displayText += ` (${rawId})`;
                                        }
                                        option.textContent = displayText;

                                        // Set data attributes
                                        option.setAttribute('data-patient-id', patient.patient_id);
                                        option.setAttribute('data-address', patient.address || '');
                                        option.setAttribute('data-age', patient.age || '');
                                        option.setAttribute('data-diagnosis', patient.diagnosis || '');
                                        option.setAttribute('data-inquiry-date', patient.inquiry_date || '');
                                        option.setAttribute('data-phone', patient.phone || '');

                                        patientDropdown.appendChild(option);
                                    });
                                } else {
                                    const option = document.createElement('option');
                                    option.textContent = "No patients found for this branch";
                                    option.disabled = true;
                                    patientDropdown.appendChild(option);
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching patients:', error);
                                patientDropdown.innerHTML = '<option value="">Error loading patients</option>';
                                Swal.fire({
                                    title: 'Fetch Error!',
                                    text: 'Failed to load patients. This could be due to a network change or session timeout. Please refresh and try again.',
                                    icon: 'error',
                                    confirmButtonColor: '#086838'
                                });
                            });
                    }
                });

                // Initialize display on load
                updateBranchFields();
            }

            if (patientSelect) {
                // Patient selection validation focus
                patientSelect.addEventListener('focus', function () {
                    if (!isBranchSelected()) {
                        showBranchAlert();
                        if (branchSelect) branchSelect.focus();
                    }
                });

                // Unified patient details handler
                function patientDetailsHandler() {
                    if (!isBranchSelected()) {
                        return;
                    }

                    const selectedOption = patientSelect.options[patientSelect.selectedIndex];
                    if (selectedOption && selectedOption.value) {
                        // Update address automatically
                        if (document.getElementById('patient_address')) document.getElementById('patient_address').value = selectedOption.getAttribute('data-address') || '';

                        // Update phone number automatically
                        if (document.getElementById('patient_phone')) document.getElementById('patient_phone').value = selectedOption.getAttribute('data-phone') || '';

                        // Generate invoice number automatically
                        const patientId = selectedOption.getAttribute('data-patient-id');
                        if (document.getElementById('invoice_no')) document.getElementById('invoice_no').value = `${patientId}`;

                        // Set inquiry date as invoice date if available
                        // const inquiryDate = selectedOption.getAttribute('data-inquiry-date');
                        // if (inquiryDate && document.getElementById('invoice_date')) {
                        //     // document.getElementById('invoice_date').value = inquiryDate;
                        //     const formattedDate = inquiryDate.split('T')[0];
                        //     document.getElementById('invoice_date').value = formattedDate;
                        // }

                        // --- FETCH PATIENT PROGRAM/CHARGE HISTORY & DUE ---
                        const dbId = selectedOption.value; // Get the user ID
                        const literalId = selectedOption.getAttribute('data-patient-id');
                        fetch("{{ url('/get-patient-programs') }}/" + dbId + "?literal_id=" + literalId)
                            .then(response => response.json())
                            .then(data => {
                                // 1. Update Invoice History
                                // if (data.program_history && data.program_history.length > 0) {
                                //     patientProgramList.value = data.program_history.join('\n');
                                // } else {
                                //     patientProgramList.value = "No previous history found.";
                                // }

                                // 2. Update Assigned Programs from Diet H/O (Removed as requested)
                                
                                // Handle LHR Area & Session
                                const lhrContent = document.getElementById('lhr_details_content');
                                const lhrEmpty = document.getElementById('lhr_details_empty');
                                const lhrAreaSpan = document.getElementById('lhr_area_display');
                                const lhrSessionSpan = document.getElementById('lhr_session_display');

                                if (data.lhr_area || data.lhr_session) {
                                    if(lhrContent) lhrContent.style.display = 'block';
                                    if(lhrEmpty) lhrEmpty.style.display = 'none';

                                    // Handle area (which might be JSON array or string)
                                    let areaText = data.lhr_area || 'Not specified';
                                    try {
                                        if (areaText.startsWith('[') || areaText.startsWith('{')) {
                                            const areaArray = JSON.parse(areaText);
                                            areaText = Array.isArray(areaArray) ? areaArray.join(', ') : areaText;
                                        }
                                    } catch (e) {
                                        // Keep as original string if not valid JSON
                                    }

                                    // Clean up phrasing for better presentation
                                    if (typeof areaText === 'string') {
                                        areaText = areaText.split(',').map(s => {
                                            return s.trim().replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
                                        }).join(', ');
                                    }

                                    if(lhrAreaSpan) lhrAreaSpan.textContent = areaText;
                                    if(lhrSessionSpan) lhrSessionSpan.textContent = data.lhr_session || 'Not specified';
                                } else {
                                    if(lhrContent) lhrContent.style.display = 'none';
                                    if(lhrEmpty) lhrEmpty.style.display = 'block';
                                }

                                // Handle Assigned Programs Checkboxes
                                const assignedContainer = document.getElementById('assigned_programs_container');
                                const assignedList = document.getElementById('assigned_programs_list');
                                if (assignedList) {
                                    assignedList.innerHTML = '';
                                    if (data.assigned_programs && data.assigned_programs.length > 0) {
                                        if (assignedContainer) assignedContainer.style.display = 'block';
                                        
                                        data.assigned_programs.forEach((prog, index) => {
                                            const div = document.createElement('div');
                                            div.className = 'form-check mb-1 d-flex align-items-center';
                                            
                                            const checkbox = document.createElement('input');
                                            checkbox.className = 'form-check-input me-2';
                                            checkbox.type = 'checkbox';
                                            checkbox.name = 'selected_assigned_programs[]';
                                            checkbox.value = JSON.stringify({
                                                program_name: prog.program,
                                                session: prog.session || '',
                                                months: prog.months || '',
                                                price: prog.total || 0
                                            });
                                            checkbox.id = `assigned_prog_${index}`;
                                            
                                            const label = document.createElement('label');
                                            label.className = 'form-check-label';
                                            label.htmlFor = `assigned_prog_${index}`;
                                            label.style.cursor = 'pointer';
                                            
                                            let labelText = prog.program;
                                            let details = [];
                                            if (prog.session && prog.session !== 'N/A') details.push(`Session: ${prog.session}`);
                                            if (prog.months && prog.months !== 'N/A') details.push(`Months: ${prog.months}`);
                                            if (prog.total) details.push(`₹${prog.total}`);
                                            
                                            if (details.length > 0) {
                                                labelText += ` (${details.join(', ')})`;
                                            }
                                            label.textContent = labelText;
                                            
                                            div.appendChild(checkbox);
                                            div.appendChild(label);
                                            assignedList.appendChild(div);
                                        });
                                    } else {
                                        if (assignedContainer) assignedContainer.style.display = 'none';
                                    }
                                }

                                // Handle First Consultation status
                                if (data.is_first_consultation) {
                                    handleFirstConsultation();
                                }

                                // 4. Auto-populate Pending Due Payment
                                const pendingDueField = document.getElementById('pending_due');
                                if (pendingDueField) {
                                    pendingDueField.value = data.total_due || 0;
                                    calculatePayments(false); // Trigger recalculation WITHOUT auto-filling given_payment
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching programs:', error);
                                // patientProgramList.value = "Error fetching history.";
                            });

                    } else {
                        // Clear fields if no patient selected
                        if (document.getElementById('patient_address')) document.getElementById('patient_address').value = '';
                        if (document.getElementById('patient_phone')) document.getElementById('patient_phone').value = '';
                        if (document.getElementById('invoice_no')) document.getElementById('invoice_no').value = '';
                        if (document.getElementById('pending_due')) document.getElementById('pending_due').value = '0';
                        // if (patientProgramList) patientProgramList.value = '';
                        const assignedContainer = document.getElementById('assigned_programs_container');
                        const assignedList = document.getElementById('assigned_programs_list');
                        if (assignedList) assignedList.innerHTML = '';
                        if (assignedContainer) assignedContainer.style.display = 'none';
                        calculatePayments(false);
                    }
                }

                // Helper to auto-select program from assigned list
                function addProgramToSelect(programName) {
                    const programSelect = document.getElementById('program_select');
                    const chargesSelect = document.getElementById('charges_select');

                    let found = false;
                    if (isCurrentSVC()) {
                        if (chargesSelect) {
                            for (let i = 0; i < chargesSelect.options.length; i++) {
                                const opt = chargesSelect.options[i];
                                if (opt.textContent.toLowerCase().includes(programName.toLowerCase())) {
                                    const $select = $(chargesSelect);
                                    let vals = $select.val() || [];
                                    if (!vals.includes(opt.value)) {
                                        vals.push(opt.value);
                                        $select.val(vals).trigger('change');
                                    }
                                    found = true;
                                    break;
                                }
                            }
                        }
                    } else {
                        if (programSelect) {
                            for (let i = 0; i < programSelect.options.length; i++) {
                                const opt = programSelect.options[i];
                                if (opt.textContent.toLowerCase().includes(programName.toLowerCase())) {
                                    const $select = $(programSelect);
                                    let vals = $select.val() || [];
                                    if (!vals.includes(opt.value)) {
                                        vals.push(opt.value);
                                        $select.val(vals).trigger('change');
                                    }
                                    found = true;
                                    break;
                                }
                            }
                        }
                    }

                    if (!found) {
                        Swal.fire({
                            title: 'Not Found',
                            text: `Program "${programName}" not found in current branch selection list.`,
                            icon: 'warning',
                            toast: true,
                            position: 'top-end',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                }

                // Helper to handle first consultation automatic fee
                function handleFirstConsultation() {
                    if (isCurrentSVC()) {
                        const chargesSelect = document.getElementById('charges_select');
                        if (chargesSelect) {
                            // Find "Consulting charges" (usually Rs 200)
                            for (let i = 0; i < chargesSelect.options.length; i++) {
                                const opt = chargesSelect.options[i];
                                const text = opt.textContent.toLowerCase();
                                if (text.includes('consulting') && (text.includes('200') || text.includes('200.00'))) {
                                    const $select = $(chargesSelect);
                                    let vals = $select.val() || [];
                                    if (!vals.includes(opt.value)) {
                                        vals.push(opt.value);
                                        $select.val(vals).trigger('change');

                                        Swal.fire({
                                            title: 'First Consultation',
                                            text: 'Consulting charges (₹200) added automatically for first visit.',
                                            icon: 'info',
                                            toast: true,
                                            position: 'top-end',
                                            timer: 4000,
                                            showConfirmButton: false,
                                            background: '#f0f9ff'
                                        });
                                    }
                                    break;
                                }
                            }
                        }
                    }
                }

                // Attach listeners
                patientSelect.addEventListener('change', patientDetailsHandler);

                // Initial trigger on load if already selected
                if (patientSelect.value) {
                    patientDetailsHandler();
                }
            }

            // Add event listeners for payment fields
            ['pending_due', 'discount'].forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.addEventListener('input', function () {
                        calculatePayments(true); // Auto-fill given payment when debt/discount changes
                    });
                }
            });

            const givenPaymentField = document.getElementById('given_payment');
            if (givenPaymentField) {
                givenPaymentField.addEventListener('input', function () {
                    calculatePayments(false); // Do not auto-fill when manually typing in given_payment itself
                });
            }

            // Payment calculation function
            function calculatePayments(autoFillGiven = false) {
                let totalPrice = 0;

                if (isCurrentSVC()) {
                    // Calculate total from selected charges
                    const chargesSelect = document.getElementById('charges_select');
                    if (chargesSelect) {
                        let selectedOptions = [];

                        // Check if Select2 is being used
                        if (typeof $ !== 'undefined' && $.fn.select2 && $(chargesSelect).data('select2')) {
                            // Get selected values from Select2
                            const selectedValues = $(chargesSelect).val() || [];
                            // Convert values to option elements
                            for (let i = 0; i < selectedValues.length; i++) {
                                // Find option by value
                                const option = chargesSelect.querySelector(`option[value="${selectedValues[i]}"]`);
                                if (option) selectedOptions.push(option);
                            }
                        } else {
                            // Fallback to native multi-select
                            selectedOptions = Array.from(chargesSelect.selectedOptions || []);
                        }

                        for (let i = 0; i < selectedOptions.length; i++) {
                            const price = parseFloat(selectedOptions[i].getAttribute('data-price')) || 0;
                            totalPrice += price;
                        }
                    }
                } else {
                    // Get Program Price for non-SVC users - Handle multiple programs
                    const programSelect = document.getElementById('program_select');
                    if (programSelect) {
                        let selectedOptions = [];
                        if (typeof $ !== 'undefined' && $.fn.select2 && $(programSelect).data('select2')) {
                            const selectedValues = $(programSelect).val() || [];
                            selectedValues.forEach(val => {
                                const option = programSelect.querySelector(`option[value="${val}"]`);
                                if (option) selectedOptions.push(option);
                            });
                        } else {
                            selectedOptions = Array.from(programSelect.selectedOptions || []);
                        }

                        selectedOptions.forEach(opt => {
                            totalPrice += parseFloat(opt.getAttribute('data-price')) || 0;
                        });
                    }
                }

                const pendingDue = parseFloat(document.getElementById('pending_due').value) || 0;
                const discount = parseFloat(document.getElementById('discount').value) || 0;

                const totalPayment = totalPrice + pendingDue;
                const netPayment = Math.max(0, totalPayment - discount);

                const totalField = document.getElementById('total_payment');
                if (totalField) {
                    totalField.value = totalPayment.toFixed(2);
                }

                if (autoFillGiven) {
                    const givenField = document.getElementById('given_payment');
                    if (givenField) {
                        givenField.value = netPayment.toFixed(2);
                    }
                }

                const givenPayment = parseFloat(document.getElementById('given_payment').value) || 0;
                const duePayment = Math.max(0, netPayment - givenPayment);

                const dueField = document.getElementById('due_payment');
                if (dueField) {
                    dueField.value = duePayment > 0 ? duePayment.toFixed(2) : '0.00';
                }
            }

            // Form submission
            const invoiceForm = document.getElementById('invoiceForm');
            if (invoiceForm) {
                invoiceForm.addEventListener('submit', function (e) {
                    // Client-side validation only
                    if (!isBranchSelected()) {
                        e.preventDefault();
                        showBranchAlert();
                        if (branchSelect) branchSelect.focus();
                        return;
                    }

                    const patientSelect = document.getElementById('patient_select');
                    if (!patientSelect.value) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Patient Required!',
                            text: 'Please select a patient',
                            icon: 'warning',
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 4000,
                            toast: true,
                            background: '#fff',
                            iconColor: '#ff9800',
                            customClass: {
                                popup: 'premium-toast',
                                title: 'premium-toast-title',
                                content: 'premium-toast-content'
                            }
                        });
                        return;
                    }

                    const programSelect = document.getElementById('program_select');
                    const chargesSelect = document.getElementById('charges_select');

                    // Validate based on current mode
                    if (isCurrentSVC()) {
                        // For SVC users, check if charges are selected
                        let selectedCharges = [];

                        // Check if Select2 is being used
                        if (typeof $ !== 'undefined' && $.fn.select2 && $(chargesSelect).data('select2')) {
                            // Get selected values from Select2
                            selectedCharges = $(chargesSelect).val() || [];
                        } else {
                            // Fallback to native multi-select
                            selectedCharges = Array.from(chargesSelect.selectedOptions).map(option => option.value);
                        }

                        if (!chargesSelect || selectedCharges.length === 0 || selectedCharges[0] === '') {
                            e.preventDefault();
                            Swal.fire({
                                title: 'Charges Required!',
                                text: 'Please select at least one charge',
                                icon: 'warning',
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 4000,
                                toast: true,
                                background: '#fff',
                                iconColor: '#ff9800',
                                customClass: {
                                    popup: 'premium-toast',
                                    title: 'premium-toast-title',
                                    content: 'premium-toast-content'
                                }
                            });
                            return;
                        }
                    } else {
                        // For others, check if program is selected
                        let selectedPrograms = [];
                        if (typeof $ !== 'undefined' && $.fn.select2 && $(programSelect).data('select2')) {
                            selectedPrograms = $(programSelect).val() || [];
                        } else {
                            selectedPrograms = Array.from(programSelect.selectedOptions).map(option => option.value);
                        }

                        if (!programSelect || selectedPrograms.length === 0 || selectedPrograms[0] === '') {
                            e.preventDefault();
                            Swal.fire({
                                title: 'Program Required!',
                                text: 'Please select at least one Program',
                                icon: 'warning',
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 4000,
                                toast: true,
                                background: '#fff',
                                iconColor: '#ff9800',
                                customClass: {
                                    popup: 'premium-toast',
                                    title: 'premium-toast-title',
                                    content: 'premium-toast-content'
                                }
                            });
                            return;
                        }
                    }

                    // If all validations pass, form will submit normally
                    const submitButton = this.querySelector('button[type="submit"]');
                    submitButton.textContent = 'Generating...';
                    submitButton.disabled = true;
                });
            }

            // Search functionality
            const searchInput = document.getElementById('search_input');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const searchTerm = this.value.toLowerCase();
                    const rows = document.querySelectorAll('#invoices_table tbody tr');

                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }

            // Per page functionality
            const perPageSelect = document.getElementById('per_page_select');
            if (perPageSelect) {
                perPageSelect.addEventListener('change', function () {
                    const perPage = this.value;
                    const url = new URL(window.location.href);
                    url.searchParams.set('per_page', perPage);
                    window.location.href = url.toString();
                });
            }

            // Initialize calculations
            calculatePayments();

            // Success Message from session
            @if (session('success'))
                Swal.fire({
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: false,
                    background: '#fff',
                    customClass: { popup: 'premium-toast' }
                });
            @endif
        });
    </script>

    <style>
        .btn-remove {
            background-color: red !important;
            color: white !important;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 !important;
        }

        .heading-action {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .delete-invoice-btn {
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .delete-invoice-btn:hover {
            background-color: #dc3545;
            color: white;
        }

        .premium-toast {
            border-left: 5px solid #ff9800 !important;
            padding: 1rem 1.25rem !important;
            width: auto !important;
            min-width: 300px !important;
            max-width: 400px !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            display: flex !important;
            align-items: center !important;
        }

        .premium-toast-title {
            font-size: 1rem !important;
            font-weight: 700 !important;
            margin: 0 !important;
            color: #1f2937 !important;
            text-align: left !important;
        }

        .premium-toast-content {
            font-size: 0.85rem !important;
            margin: 0 !important;
            color: #4b5563 !important;
            text-align: left !important;
        }

        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }

        .card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }

        .pagination {
            margin-bottom: 0;
        }

        .fnf-title {
            color: #333;
            font-weight: 600;
            margin: 0;
        }

        .custom_filter_section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .left_filter {
            display: flex;
            align-items: center;
        }

        .right_filter {
            display: flex;
            align-items: center;
        }

        .dataTables_length select {
            padding: 6px 12px;
            border-radius: 4px;
            border: 1px solid #ced4da;
            background-color: white;
        }

        .ctm_search {
            display: flex;
            align-items: center;
            margin-bottom: 0;
        }

        .ctm_search input {
            margin-left: 8px;
            padding: 6px 12px;
            border-radius: 4px;
            border: 1px solid #ced4da;
        }

        .table th {
            background-color: #086838 !important;
            color: white;
            font-weight: 500;
            padding: 12px 8px;
        }

        .table td {
            padding: 12px 8px;
            vertical-align: middle;
        }

        .table-responsive {
            border-radius: 4px;
            overflow: hidden;
        }

        .pagination-info {
            margin-top: 15px;
            color: #6c757d;
        }

        .pagination {
            margin-top: 15px;
        }

        .pagination .page-link {
            color: #086838;
            border: 1px solid #dee2e6;
        }

        .pagination .page-item.active .page-link {
            background-color: #086838;
            border-color: #086838;
        }

        .text-center {
            text-align: center;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .table tbody tr:hover {
            background-color: #e9ecef;
        }

        .rounded-circle {
            background-color: #8ec038;
        }

        .rounded-circle i {
            color: white
        }

        /* Premium Alert Styling */
        .premium-alert {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            position: relative;
            border: 1px solid transparent;
            animation: slideDown 0.4s ease-out;
        }

        .premium-alert.success {
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .dark .premium-alert.success {
            background: #064e3b;
            /* Dark emerald background */
            border-color: rgba(16, 185, 129, 0.2);
            color: #ecfdf5;
            /* Off-white text for high contrast */
        }

        .premium-alert.error {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .dark .premium-alert.error {
            background: #7f1d1d;
            /* Dark red background */
            border-color: rgba(239, 68, 68, 0.2);
            color: #fef2f2;
            /* Off-white text */
        }

        .alert-icon {
            font-size: 20px;
            flex-shrink: 0;
        }

        .alert-content {
            font-size: 15px;
            font-weight: 500;
            flex-grow: 1;
        }

        .alert-close {
            background: none;
            border: none;
            color: currentColor;
            opacity: 0.5;
            cursor: pointer;
            padding: 5px;
            transition: opacity 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .alert-close:hover {
            opacity: 1;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .refresh-btn {
            background: rgb(62, 95, 168);
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 !important;
        }

        .refresh-btn i {
            color: white;
        }

        .add-row-btn {
            background: #8ec038;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 !important;
        }

        .add-row-btn i {
            color: white;
        }

        input {
            outline: none
        }

        textarea {
            outline: none
        }

        label {
            color: #5a6268
        }

        select {
            outline: none
        }

        label {
            font-weight: bold;
            color: #2c3e50;
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            font-size: 14px;
            font-family: Arial, sans-serif;
        }

        .fnf-title {
            font-weight: 600;
            color: #006637;
            padding-bottom: 0;
            line-height: 1.3em;
            font-size: 18px
        }
    </style>

@endsection