@extends('admin.layouts.layouts')

@section('title', 'Indoor Patients (IPD)')

@section('content')

    <style>
        /* Import Poppins font */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        /* Apply Poppins to entire page */
        body {
            font-family: 'Poppins', sans-serif !important;
        }

        .search-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
        }

        .search-row {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .search-field {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-field label {
            font-weight: bold;
            color: #2c3e50;
            white-space: nowrap;
        }

        .search-field input,
        .search-field select {
            padding: 8px 12px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            font-size: 14px;
        }

        .search-btn {
            background: rgb(8, 104, 56);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .patient-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 14px;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            min-width: 1000px;
        }

        .patient-table th {
            background: #006637;
            color: white;
            font-weight: bold;
            padding: 15px 10px;
            text-align: left;
            border: none;
            font-size: 14px;
        }

        .patient-table td {
            padding: 12px 10px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
            font-size: 13px;
        }

        .patient-table tr:nth-child(even) {
            background: #f8f9fa;
        }

        .patient-table tr:hover {
            background: #e9f7ef;
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 24px;
            font-weight: bold;
            color: #006637;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            background: transparent;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-profile-square {
            border: 1px solid #28a745;
            color: #28a745 !important;
        }

        .btn-profile-square:hover {
            background-color: #28a745;
            color: white !important;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: #6c757d;
        }

        .pagination-buttons {
            display: flex;
            gap: 8px;
        }

        .pagination-buttons .btn {
            padding: 6px 12px;
            background: #006637;
            color: white;
            border-radius: 4px;
            text-decoration: none;
        }

        .pagination-buttons .btn[disabled] {
            background: #ccc;
            cursor: not-allowed;
        }

        .dual-search-container {
            display: flex;
            align-items: center;
            gap: 15px;
            width: 100%;
        }

        .search-input {
            padding: 8px 12px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            font-size: 14px;
            width: 100%;
        }

        .global-search-wrapper {
            flex: 1;
            min-width: 250px;
        }

        .per-page-wrapper {
            width: 150px;
        }

        .search-btn-wrapper {
            display: flex;
            align-items: flex-end;
            padding-bottom: 2px;
        }

        @media (max-width: 768px) {
            .header-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .dual-search-container {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
            }

            .per-page-wrapper,
            .global-search-wrapper,
            .search-btn-wrapper {
                width: 100%;
                min-width: 100%;
            }

            .search-btn-wrapper {
                margin-top: 5px;
            }

            .search-btn-wrapper .btn {
                width: 100%;
            }

            .search-label {
                margin-top: 5px;
            }
        }

        .search-label {
            font-weight: 600;
            color: #006637;
            margin-bottom: 5px;
            display: block;
        }

        .badge-ipd {
            background-color: rgba(0, 102, 55, 0.1);
            color: #006637;
            padding: 4px 12px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
        }

        /* Apply Poppins to entire page but exclude icons */
        body,
        .main-content,
        .card,
        .table,
        .modal-content,
        input,
        select,
        textarea,
        button {
            font-family: 'Poppins', sans-serif !important;
        }

        /* Ensure FontAwesome and Bootstrap Icons preserve their font families */
        .fas,
        .far,
        .fal,
        .fab,
        .fa,
        .fa-solid,
        .fa-regular,
        .fa-brands,
        .bi,
        [class^="fa-"],
        [class*=" fa-"],
        [class^="bi-"],
        [class*=" bi-"] {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands", "bootstrap-icons", "FontAwesome" !important;
        }

        /* Indoor Treatment Modal Styles */
        .indoor-patient-info {
            background: #f0f7f2;
            border: 1px solid #c8e6d4;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 8px 24px;
        }

        .indoor-patient-info .info-item {
            font-size: 14px;
            color: #333;
        }

        .indoor-patient-info .info-item strong {
            color: #006637;
            font-weight: 600;
            margin-right: 6px;
        }

        /* Add New Date Slot Button */
        .add-slot-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: white !important;
            color: #006637 !important;
            border: 1.5px solid #006637 !important;
            border-radius: 8px !important;
            padding: 10px 16px !important;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 18px;
        }

        .add-slot-btn:hover {
            background: #f0f7f2 !important;
        }

        /* Date Slot Card */
        .date-slot-card {
            background: #fff;
            border-radius: 12px;
            overflow: visible;
            margin-bottom: 16px;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .date-slot-header {
            background: linear-gradient(135deg, #006637 0%, #004d2a 100%);
            color: white;
            padding: 12px 16px;
            border-radius: 12px 12px 0 0;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .date-slot-header label {
            font-weight: 500;
            margin: 0;
            font-size: 14px;
        }

        .date-slot-header input {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 13px;
        }

        .date-slot-header input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .slot-at-separator {
            color: rgba(255, 255, 255, 0.8);
            font-weight: bold;
        }

        .medicine-count-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            margin-left: auto;
        }

        .remove-slot-btn {
            background: rgba(220, 53, 69, 0.9);
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .remove-slot-btn:hover {
            background: rgba(220, 53, 69, 1);
        }

        .date-slot-body {
            padding: 16px;
        }

        .medicines-header {
            display: grid;
            grid-template-columns: 1fr 1fr 36px;
            gap: 10px;
            margin-bottom: 12px;
            font-weight: 600;
            color: #495057;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .medicine-rows-container {
            margin-bottom: 12px;
        }

        .medicine-row {
            display: grid;
            grid-template-columns: 1fr 1fr 36px;
            gap: 10px;
            align-items: center;
            margin-bottom: 8px;
        }

        .medicine-row input {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 7px 10px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .medicine-row input:focus {
            outline: none;
            border-color: #006637;
            box-shadow: 0 0 0 3px rgba(0, 102, 55, 0.1);
        }

        .medicine-row input::placeholder {
            color: #adb5bd;
        }

        .delete-medicine-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 6px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .delete-medicine-btn:hover {
            background: #c82333;
        }

        .add-medicine-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .add-medicine-btn:hover {
            background: #218838;
        }

        /* Modal Footer Buttons */
        .btn-cancel-indoor {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-cancel-indoor:hover {
            background: #5a6268;
        }

        .btn-save-indoor {
            background: #006637;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-save-indoor:hover {
            background: #004d2a;
        }

        @media (max-width: 576px) {

            .medicine-row,
            .medicines-header {
                grid-template-columns: 1fr;
                gap: 5px;
            }

            .medicines-header {
                display: none;
            }

            .medicine-row {
                background: #f8f9fa;
                padding: 10px;
                border-radius: 8px;
                border: 1px solid #eee;
                position: relative;
                padding-bottom: 45px;
            }

            .medicine-row input {
                width: 100%;
            }

            .delete-medicine-btn {
                position: absolute;
                bottom: 10px;
                right: 10px;
                width: calc(100% - 20px);
            }

            .pagination {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .date-slot-header {
                flex-direction: column;
                align-items: stretch;
            }

            .date-slot-header input {
                width: 100%;
            }

            .slot-at-separator {
                text-align: center;
                margin: 5px 0;
            }

            .medicine-count-badge {
                margin: 5px 0;
                text-align: center;
            }
        }
    </style>

    <div class="header-row">
        <div class="section-title">
            <i class="bi bi-hospital"></i> Indoor Patients (IPD)
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="search-section">
        <form method="GET" action="{{ route('indoor.patients') }}" id="searchForm">
            <div class="dual-search-container">
                <div class="global-search-wrapper">
                    <label class="search-label">Search Patients</label>
                    <input type="text" name="global_search" class="search-input" placeholder="Name, ID, or diagnosis..."
                        value="{{ request('global_search') }}" id="globalSearchInput">
                </div>
                <div class="per-page-wrapper">
                    <label class="search-label">Show Entries</label>
                    <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 entries</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 entries</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 entries</option>
                    </select>
                </div>
                <div class="search-btn-wrapper">
                    <button type="submit" class="btn btn-primary"
                        style="background-color: #006637; border: none; height: 38px;">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="patient-table">
            <thead>
                <tr>
                    <th>Profile</th>
                    <th>Patient Id</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Age</th>
                    <th>Diagnosis</th>
                    <th>Inquiry Date</th>
                    <th>Added On</th>
                    <th>Status</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($patients as $patient)
                            <tr>
                                <td class="profile-icon">
                                    @php
                                        $profileImage = $patient->getMeta('profile_image');
                                    @endphp
                                    <a href="{{ route('ipd.profile', $patient->id) }}" title="View Profile">
                                        @if ($profileImage && file_exists(public_path($profileImage)))
                                            <img src="{{ asset($profileImage) }}" alt="Profile"
                                                style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                                        @else
                                            <i class="far fa-address-card"></i>
                                        @endif
                                    </a>
                                </td>
                                {{-- <td style="font-weight: 600; color: #006637;">{{ $patient->patient_id }}</td> --}}
                                <td class="patient_id">
                                    <a href="{{ route('ipd.profile', $patient->id) }}" style="color: #28a745; text-decoration: none;"
                                        title="View Profile">
                                        {{ $patient->patient_id }}
                                    </a>
                                </td>
                                <td>{{ $patient->patient_name }}</td>
                                <td>{{ $patient->address }}</td>
                                <td>{{ $patient->age }}</td>
                                <td>
                                    @if($patient->diagnosis)
                                        @php
                                            $diagnoses = explode(', ', $patient->diagnosis);
                                            $diagnoses = array_filter($diagnoses);
                                            if (!empty($diagnoses)) {
                                                echo '<span class="badge bg-info me-1">' . implode('</span><span class="badge bg-info me-1">', array_slice($diagnoses, 0, 3)) . '</span>';
                                                if (count($diagnoses) > 3) {
                                                    echo '<span class="badge bg-secondary">+' . (count($diagnoses) - 3) . ' more</span>';
                                                }
                                            }
                                        @endphp
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $patient->inquiry_date ? \Carbon\Carbon::parse($patient->inquiry_date)->format('d/m/Y') : '-' }}
                                </td>
                                <td>{{ $patient->created_at->format('d/m/Y') }}</td>
                                <td><span class="badge-ipd">Indoor (IPD)</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="action-btn btn-profile-square" onclick="openIndoorModal({{ json_encode([
                        'id' => $patient->id,
                        'name' => $patient->patient_name,
                        'age' => $patient->age,
                        'diagnosis' => $patient->diagnosis ?? 'N/A',
                        'complaints' => $patient->getMeta('complain') ?? 'N/A',
                        'treatments' => $patient->treatments
                    ]) }})" title="Manage Treatment">
                                            <i class="bi bi-hospital"></i>
                                        </button>
                                        <button type="button" class="action-btn btn-profile-square" onclick="openPaymentModal({{ json_encode([
                        'id' => $patient->id,
                        'name' => $patient->patient_name,
                        'patient_id' => $patient->patient_id,
                        'invoice' => $patient->invoice
                    ]) }})" title="Payment Details" style="border-color: #28a745; color: #28a745 !important;">
                                            <i class="fas fa-rupee-sign"></i>
                                        </button>
                                        <a href="{{ route('ipd.profile', $patient->id) }}" class="action-btn btn-profile-square"
                                            title="View Profile">
                                            <i class="fas fa-address-card"></i>
                                        </a>
                                        <a href="{{ route('edit.svc.inquiry', $patient->id) }}" class="action-btn btn-profile-square"
                                            title="Edit Inquiry" style="border-color: #007bff; color: #007bff !important;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                @empty
                    <tr>
                        <td colspan="9" class="empty-state">
                            <i class="bi bi-info-circle" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
                            No indoor patients found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($patients->hasPages())
        <div class="pagination">
            <div>
                Showing {{ $patients->firstItem() }} to {{ $patients->lastItem() }} of {{ $patients->total() }} patients
            </div>
            <div class="pagination-buttons">
                @if ($patients->onFirstPage())
                    <span class="btn" disabled>Previous</span>
                @else
                    <a href="{{ $patients->previousPageUrl() }}" class="btn">Previous</a>
                @endif

                @if ($patients->hasMorePages())
                    <a href="{{ $patients->nextPageUrl() }}" class="btn">Next</a>
                @else
                    <span class="btn" disabled>Next</span>
                @endif
            </div>
        </div>
    @endif

    {{-- Indoor Treatment Modal --}}
    <div class="modal fade" id="indoorTreatmentModal" tabindex="-1" aria-labelledby="indoorTreatmentModalLabel"
        aria-hidden="true">
 <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <form action="" method="POST" id="indoorTreatmentForm">
                    @csrf

                    {{-- Modal Header --}}
                    <div class="modal-header" style="background-color: #006637; color: white;">
                        <h5 class="modal-title" id="indoorTreatmentModalLabel" style="color: white;">
                            <i class="bi bi-hospital-fill"></i> Manage Indoor Treatment Logs
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="modal-body">

                        {{-- Patient Info --}}
                        <div class="indoor-patient-info mb-4" style="background: #f8f9fa; border-left: 4px solid #006637; padding: 15px; border-radius: 6px;">
                            <div class="row g-3">
                                <div class="col-md-3"><strong>Name:</strong> <span id="modal-patient-name"></span></div>
                                <div class="col-md-2"><strong>Age:</strong> <span id="modal-patient-age"></span></div>
                                <div class="col-md-3"><strong>Diagnosis:</strong> <span id="modal-patient-diagnosis"></span></div>
                                <div class="col-md-4"><strong>Complaints:</strong> <span id="modal-patient-complaints"></span></div>
                            </div>
                        </div>

                        {{-- Section: Treatment History --}}
                        <div class="mb-4">
                            <h6 class="d-flex align-items-center gap-2 mb-3" style="color: #006637; font-weight: 700; border-bottom: 2px solid #e9ecef; padding-bottom: 8px;">
                                <i class="fas fa-history"></i> Past Treatment History
                            </h6>
                            <div id="indoorHistoryContainer">
                                <!-- Loaded dynamically via JS -->
                            </div>
                        </div>

                        {{-- Section: Add New Treatment Entry --}}
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3" style="border-bottom: 2px solid #e9ecef; padding-bottom: 8px;">
                                <h6 class="m-0" style="color: #006637; font-weight: 700;">
                                    <i class="bi bi-plus-circle-fill"></i> Add New Treatment Entry
                                </h6>
                                <button type="button" class="add-slot-btn btn btn-sm" onclick="addIndoorDateSlot()" style="background-color: #006637; color: white;">
                                    <i class="bi bi-plus-lg"></i> Add Another Slot
                                </button>
                            </div>

                            {{-- Slots Container --}}
                            <div id="indoorSlotsContainer">
                                <!-- Empty slots added dynamically -->
                            </div>
                        </div>

                    </div>

                    {{-- Modal Footer --}}
                    <div class="modal-footer justify-content-end gap-2" style="background-color: #f8f9fa;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn-save-indoor btn btn-primary" style="background-color: #006637; border-color: #006637;">
                            <i class="bi bi-check-lg me-1"></i> Submit New Logs
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- Payment Details Modal --}}
    <div class="modal fade" id="paymentDetailsModal" tabindex="-1" aria-labelledby="paymentDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <div class="modal-header" style="background: linear-gradient(135deg, #006637, #28a745); color: white;">
                    <h5 class="modal-title" id="paymentDetailsModalLabel" style="color: white; font-weight: 700;">
                        <i class="fas fa-file-invoice-dollar me-2"></i> Payment & Charges Status
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    {{-- Financial Summary Cards --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="p-3 text-center" style="background: rgba(40, 167, 69, 0.1); border-radius: 10px; border: 1px solid rgba(40, 167, 69, 0.2);">
                                <span class="d-block text-muted" style="font-size: 11px; text-transform: uppercase; font-weight: 600;">Total Charges</span>
                                <h4 class="m-0 mt-1" style="color: #28a745; font-weight: 700;">₹<span id="modal-pay-total">0</span></h4>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 text-center" style="background: rgba(23, 162, 184, 0.1); border-radius: 10px; border: 1px solid rgba(23, 162, 184, 0.2);">
                                <span class="d-block text-muted" style="font-size: 11px; text-transform: uppercase; font-weight: 600;">Paid Amount</span>
                                <h4 class="m-0 mt-1" style="color: #17a2b8; font-weight: 700;">₹<span id="modal-pay-paid">0</span></h4>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 text-center" style="background: rgba(220, 53, 69, 0.1); border-radius: 10px; border: 1px solid rgba(220, 53, 69, 0.2);">
                                <span class="d-block text-muted" style="font-size: 11px; text-transform: uppercase; font-weight: 600;">Due Balance</span>
                                <h4 class="m-0 mt-1" style="color: #dc3545; font-weight: 700;">₹<span id="modal-pay-due">0</span></h4>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 text-center" style="background: rgba(255, 193, 7, 0.1); border-radius: 10px; border: 1px solid rgba(255, 193, 7, 0.2);">
                                <span class="d-block text-muted" style="font-size: 11px; text-transform: uppercase; font-weight: 600;">Discount</span>
                                <h4 class="m-0 mt-1" style="color: #ffc107; font-weight: 700;">₹<span id="modal-pay-discount">0</span></h4>
                            </div>
                        </div>
                    </div>

                    {{-- Form: Update Charges --}}
                    <div style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 10px; padding: 20px;">
                        <h6 class="mb-3" style="color: #006637; font-weight: 700;"><i class="fas fa-edit me-1"></i> Update Patient Charges</h6>
                        <form action="" method="POST" id="paymentUpdateForm">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 12px; font-weight: 600;">Total Charges (₹)</label>
                                    <input type="number" step="0.01" class="form-control" name="total_payment" id="input-pay-total" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 12px; font-weight: 600;">Given Payment (₹)</label>
                                    <input type="number" step="0.01" class="form-control" name="given_payment" id="input-pay-given" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 12px; font-weight: 600;">Discount (₹)</label>
                                    <input type="number" step="0.01" class="form-control" name="discount_payment" id="input-pay-discount" value="0">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 12px; font-weight: 600;">Payment Method</label>
                                    <select class="form-select" name="payment_method" id="input-pay-method">
                                        <option value="Cash">Cash</option>
                                        <option value="Card">Card</option>
                                        <option value="UPI">UPI</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 12px; font-weight: 600;">Charge Date</label>
                                    <input type="date" class="form-control" name="charge_date" value="{{ now()->format('Y-m-d') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 12px; font-weight: 600;">Charge Time</label>
                                    <input type="time" class="form-control" name="charge_time" value="{{ now()->format('H:i') }}">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label" style="font-size: 12px; font-weight: 600;">Shift / Reference Note</label>
                                    <input type="text" class="form-control" name="charge_shift" placeholder="e.g. Morning Shift / IPD Routine">
                                </div>
                                <div class="col-12 text-end mt-3">
                                    <button type="submit" class="btn btn-success px-4" style="background-color: #28a745; border-color: #28a745;">
                                        <i class="fas fa-save me-1"></i> Save Updates
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let indoorSlotCounter = 0;

        function openIndoorModal(data) {
            document.getElementById('modal-patient-name').textContent = data.name || '-';
            document.getElementById('modal-patient-age').textContent = data.age || '-';
            document.getElementById('modal-patient-diagnosis').textContent = data.diagnosis || 'N/A';
            document.getElementById('modal-patient-complaints').textContent = data.complaints || 'N/A';

            const form = document.getElementById('indoorTreatmentForm');
            form.action = `/svc-profile/${data.id}/indoor-treatment`;

            // Display existing treatments in History container
            const historyContainer = document.getElementById('indoorHistoryContainer');
            historyContainer.innerHTML = '';

            if (data.treatments && data.treatments.length > 0) {
                // Group treatments by date+time
                const treatmentGroups = {};
                data.treatments.forEach(t => {
                    const key = (t.date || 'No Date') + '||' + (t.time || 'No Time');
                    if (!treatmentGroups[key]) {
                        treatmentGroups[key] = [];
                    }
                    treatmentGroups[key].push(t);
                });

                Object.keys(treatmentGroups).forEach(groupKey => {
                    const [date, time] = groupKey.split('||');
                    const medicines = treatmentGroups[groupKey];
                    
                    // Format Date/Time nicely
                    let displayDate = date;
                    if (date !== 'No Date') {
                        const parts = date.split('-');
                        if (parts.length === 3) displayDate = `${parts[2]}/${parts[1]}/${parts[0]}`;
                    }
                    
                    let displayTime = time;
                    if (time !== 'No Time' && time.includes(':')) {
                        const tparts = time.split(':');
                        let h = parseInt(tparts[0], 10);
                        const ampm = h >= 12 ? 'PM' : 'AM';
                        h = h % 12;
                        h = h ? h : 12;
                        displayTime = `${h}:${tparts[1]} ${ampm}`;
                    }

                    const historyCard = document.createElement('div');
                    historyCard.className = 'card mb-2 border-0 shadow-sm';
                    historyCard.style.background = '#fff';
                    historyCard.style.borderLeft = '3px solid #17a2b8';
                    
                    let medsListHtml = medicines.map(m => `
                        <div class="d-flex justify-content-between align-items-center py-1" style="font-size: 13px; border-bottom: 1px solid #f0f0f0;">
                            <span style="font-weight: 500; color: #333;"><i class="bi bi-capsule me-2" style="color: #006637;"></i>${m.medicine || '-'}</span>
                            <span class="text-muted" style="font-size: 12px; font-style: italic;">${m.note || ''}</span>
                        </div>
                    `).join('');

                    historyCard.innerHTML = `
                        <div class="card-header py-1 px-3 d-flex justify-content-between align-items-center" style="background: #f8f9fa; border-bottom: none;">
                            <span style="font-size: 12px; font-weight: 600; color: #006637;">
                                <i class="bi bi-calendar-event me-1"></i> ${displayDate} &nbsp;|&nbsp; <i class="bi bi-clock me-1"></i> ${displayTime}
                            </span>
                            <span class="badge bg-light text-dark border">${medicines.length} ${medicines.length === 1 ? 'item' : 'items'}</span>
                        </div>
                        <div class="card-body py-2 px-3">
                            ${medsListHtml}
                        </div>
                    `;
                    historyContainer.appendChild(historyCard);
                });
            } else {
                historyContainer.innerHTML = '<div class="text-muted text-center py-2" style="font-size: 13px;">No past treatment logs recorded yet.</div>';
            }

            // Clear inputs container and add one new empty slot for current logging
            const container = document.getElementById('indoorSlotsContainer');
            container.innerHTML = '';
            indoorSlotCounter = 0;
            
            // Set current date/time as default for new entry
            const now = new Date();
            const yyyy = now.getFullYear();
            const mm = String(now.getMonth() + 1).padStart(2, '0');
            const dd = String(now.getDate()).padStart(2, '0');
            const hh = String(now.getHours()).padStart(2, '0');
            const min = String(now.getMinutes()).padStart(2, '0');
            
            createIndoorSlot(`${yyyy}-${mm}-${dd}`, `${hh}:${min}`, [], 0);

            const modal = new bootstrap.Modal(document.getElementById('indoorTreatmentModal'));
            modal.show();
        }

        function createIndoorSlot(date = '', time = '', medicines = [], slotIndex) {
            const container = document.getElementById('indoorSlotsContainer');

            const slot = document.createElement('div');
            slot.className = 'date-slot-card mb-3 p-3';
            slot.style.border = '1px solid #ced4da';
            slot.style.borderRadius = '8px';
            slot.style.background = '#fff';
            
            const indexToUse = slotIndex !== undefined ? slotIndex : indoorSlotCounter++;
            slot.setAttribute('data-slot', indexToUse);

            // Default at least one empty medicine row
            const medsToRender = medicines.length > 0 ? medicines : [{medicine: '', note: ''}];

            slot.innerHTML = `
                    <div class="date-slot-header d-flex align-items-center gap-2 mb-3 pb-2" style="border-bottom: 1px solid #dee2e6;">
                        <label style="font-weight: 600; font-size: 13px; color: #495057;">Date &amp; Time:</label>
                        <input type="date" class="form-control form-control-sm w-auto" name="slot_date[${indexToUse}]" value="${date}" required>
                        <span class="slot-at-separator">@</span>
                        <input type="time" class="form-control form-control-sm w-auto" name="slot_time[${indexToUse}]" value="${time}">
                        <button type="button" class="btn btn-sm btn-outline-danger ms-auto d-flex align-items-center gap-1" onclick="removeIndoorSlot(this)" style="padding: 2px 8px; font-size: 12px;">
                            <i class="bi bi-trash"></i> Drop Slot
                        </button>
                    </div>
                    <div class="date-slot-body">
                        <div class="medicine-rows-container">
                            ${medsToRender.map(med => `
                                <div class="medicine-row d-flex gap-2 mb-2">
                                    <input type="text" class="form-control form-control-sm flex-grow-1" name="slot_medicine[${indexToUse}][]" value="${med.medicine || ''}" placeholder="Enter medicine name / action" autocomplete="off" required>
                                    <input type="text" class="form-control form-control-sm w-25" name="slot_note[${indexToUse}][]" value="${med.note || ''}" placeholder="Dosage / Note">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deleteMedicineRow(this)" title="Remove Row">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1" onclick="addMedicineRow(this, ${indexToUse})" style="font-size: 12px;">
                            <i class="bi bi-plus"></i> Add Item Row
                        </button>
                    </div>
                `;

            container.appendChild(slot);
        setTimeout(() => {
                const modalEl = container.closest('.modal');
                if (modalEl) {
                    modalEl.scrollTo({ top: modalEl.scrollHeight, behavior: 'smooth' });
                }
                slot.scrollIntoView({ behavior: 'smooth', block: 'end' });
            }, 100);
        }

        function addIndoorDateSlot() {
            const now = new Date();
            const yyyy = now.getFullYear();
            const mm = String(now.getMonth() + 1).padStart(2, '0');
            const dd = String(now.getDate()).padStart(2, '0');
            const hh = String(now.getHours()).padStart(2, '0');
            const min = String(now.getMinutes()).padStart(2, '0');
            
            createIndoorSlot(`${yyyy}-${mm}-${dd}`, `${hh}:${min}`, [], indoorSlotCounter++);
        }

        function addMedicineRow(btn, slotIndex) {
            const card = btn.closest('.date-slot-card');
            const rowsContainer = card.querySelector('.medicine-rows-container');

            const row = document.createElement('div');
            row.className = 'medicine-row d-flex gap-2 mb-2';
            row.innerHTML = `
                    <input type="text" class="form-control form-control-sm flex-grow-1" name="slot_medicine[${slotIndex}][]" placeholder="Enter medicine name / action" autocomplete="off" required>
                    <input type="text" class="form-control form-control-sm w-25" name="slot_note[${slotIndex}][]" placeholder="Dosage / Note">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deleteMedicineRow(this)" title="Remove Row">
                        <i class="bi bi-x-lg"></i>
                    </button>
                `;

            rowsContainer.appendChild(row);
            row.querySelector('input').focus();
        }

        function deleteMedicineRow(btn) {
            const card = btn.closest('.date-slot-card');
            const rowsContainer = card.querySelector('.medicine-rows-container');
            const rows = rowsContainer.querySelectorAll('.medicine-row');

            if (rows.length > 1) {
                btn.closest('.medicine-row').remove();
            } else {
                // Clear values if last row
                btn.closest('.medicine-row').querySelectorAll('input').forEach(i => i.value = '');
            }
        }

        function removeIndoorSlot(btn) {
            const container = document.getElementById('indoorSlotsContainer');
            const slots = container.querySelectorAll('.date-slot-card');

            if (slots.length > 1) {
                btn.closest('.date-slot-card').remove();
            } else {
                // Clear values
                btn.closest('.date-slot-card').querySelectorAll('input[type="text"]').forEach(i => i.value = '');
            }
        }

        function openPaymentModal(data) {
            const inv = data.invoice || {};
            
            // Format numbers nicely
            const total = parseFloat(inv.total_payment || inv.price || 0).toFixed(2);
            const paid = parseFloat(inv.given_payment || 0).toFixed(2);
            const due = parseFloat(inv.due_payment || 0).toFixed(2);
            const disc = parseFloat(inv.discount || 0).toFixed(2);

            document.getElementById('modal-pay-total').textContent = total;
            document.getElementById('modal-pay-paid').textContent = paid;
            document.getElementById('modal-pay-due').textContent = due;
            document.getElementById('modal-pay-discount').textContent = disc;

            // Populate form
            document.getElementById('input-pay-total').value = parseFloat(total);
            document.getElementById('input-pay-given').value = parseFloat(paid);
            document.getElementById('input-pay-discount').value = parseFloat(disc);

            const form = document.getElementById('paymentUpdateForm');
            form.action = `/svc-profile/${data.id}/update-charges`;

            const modal = new bootstrap.Modal(document.getElementById('paymentDetailsModal'));
            modal.show();
        }
    </script>
@endsection