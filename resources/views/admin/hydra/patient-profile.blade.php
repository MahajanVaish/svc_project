@extends('admin.layouts.layouts')

@section('content')
    <style>
        .main_content {
            width: 100%;
            max-width: 1500px;
            margin: 0 auto;
            padding: 20px 15px;
        }

        .card.profile_cart {
            box-shadow: none;
        }

        .mb-5,
        .my-5 {
            margin-bottom: 3rem !important;
        }

        .card-header {
            background-color: rgba(0, 0, 0, .03);
        }

        .heading-action {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .fnf-title {
            font-weight: 600;
            color: #006637;
            padding-bottom: 0;
            line-height: 1.3em;
            margin-bottom: 0px !important;
        }

        .profile_cart .fnf-title {
            font-size: 18px;
        }

        .profile_txt_color {
            color: #4cb034;
            font-weight: 600;
        }

        .dataTables_wrapper {
            position: relative;
            clear: both;
        }

        .dataTables_wrapper table:not(.variations) {
            border: 1px solid #eee;
            margin: 0 0 15px;
            text-align: left;
            width: 100%;
        }

        .dataTables_wrapper table thead {
            background: #006637 !important;
        }

        .dataTables_wrapper table thead th {
            color: #fff !important;
            background-color: #006637 !important;
        }

        .dataTables_wrapper .dataTables_info {
            clear: both;
            float: left;
            padding-top: .755em;
        }

        .dataTables_wrapper .dataTables_paginate {
            float: right;
            text-align: right;
            padding-top: .25em;
        }

        .dub_tab_field {
            display: flex;
            gap: 20px;
        }

        .add_progressBtn_div,
        .add_diet_div {
            background: #006637;
            padding: 6px 20px;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
        }

        .add_progressBtn_div:hover,
        .add_diet_div:hover {
            background: #005629;
            color: #fff;
        }

        .add_call_record {
            color: #4cb034;
            text-decoration: none;
            font-weight: 600;
        }

        .add_call_record:hover {
            color: #3a9a2a;
        }

        a {
            color: #4cb034;
        }

        .dataTables_paginate .paginate_button.current {
            background: #006637 !important;
            color: #fff !important;
            border: none !important;
            margin: 0.5em;
            padding: 4px 10px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            margin: 0.25em !important;
            padding: 4px 10px !important;
            border: none !important;
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 1rem;
        }

        .bg-white {
            background-color: #fff !important;
        }

        .card_toggle {
            position: relative;
            cursor: pointer;
        }

        .diet_bg {
            background: #f8f8f8;
        }

        .show_details {
            position: relative;
            cursor: pointer;
        }

        .toggle-icon {
            transition: transform 0.3s ease;
        }

        .rotate-icon {
            transform: rotate(180deg);
        }

        .patient_opd_details .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }

        .patient_opd_details .col-md-3 {
            flex: 0 0 25%;
            max-width: 25%;
            padding: 0 15px;
        }

        .patient_opd_details .col-md-4 {
            flex: 0 0 33.333%;
            max-width: 33.333%;
            padding: 0 15px;
        }

        .patient_opd_details .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 0 15px;
        }

        .patient_opd_details .py-3 {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }

        .patient_opd_details .label-text {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .patient_opd_details .input-field {
            border: none;
            border-bottom: 1px solid #ddd;
            background: transparent;
            width: 100%;
            padding: 5px 0;
            color: #333;
        }

        .patient_opd_details .input-field:focus {
            outline: none;
            border-bottom-color: #4cb034;
        }

        .timeline-view {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-icon {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            position: absolute;
            left: -30px;
            top: 5px;
        }

        .timeline-view .timeline-item::before {
            content: '';
            position: absolute;
            left: -24px;
            top: 15px;
            bottom: -20px;
            width: 2px;
            background: #e0e0e0;
        }

        .timeline-view .timeline-item:last-child::before {
            display: none;
        }

        .badge-light {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #495057;
        }

        .empty-state {
            padding: 40px 20px;
            text-align: center;
        }

        .empty-state i {
            opacity: 0.5;
            margin-bottom: 15px;
        }

        .medical-question {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .medical-answer {
            color: #666;
            padding-left: 15px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .yes-badge {
            background-color: #d4edda;
            color: #155724;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .no-badge {
            background-color: #f8d7da;
            color: #721c24;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .not-specified {
            color: #6c757d;
            font-style: italic;
        }

        .image-container {
            position: relative;
            margin-bottom: 15px;
        }

        .image-actions {
            position: absolute;
            bottom: 10px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .image-container:hover .image-actions {
            opacity: 1;
        }

        .btn-image-action {
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
        }

        .btn-image-action:hover {
            background: rgba(0, 0, 0, 0.9);
            color: white;
        }

        @media (max-width: 992px) {
            .card-body.px-5 {
                padding-left: 15px !important;
                padding-right: 15px !important;
            }

            .patient_profile .col-lg-4,
            .patient_profile .col-lg-8 {
                padding: 0 15px !important;
            }

            .heading-action {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .heading-action .d-flex {
                width: 100%;
            }

            .add_progressBtn_div {
                flex: 1;
                text-align: center;
                padding: 8px 10px;
                font-size: 13px;
            }
        }

        @media (max-width: 768px) {
            .dub_tab_field {
                flex-direction: column;
            }

            .patient_opd_details .col-md-3,
            .patient_opd_details .col-md-4,
            .patient_opd_details .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .dataTables_wrapper table {
                font-size: 13px;
                min-width: 600px;
            }

            .card-header .heading-action {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .card-header .heading-action h3 {
                margin-bottom: 10px;
            }

            .profile-image-container {
                width: 120px;
                height: 120px;
            }
        }

        @media (max-width: 576px) {

            .patient_opd_details .col-md-3,
            .patient_opd_details .col-md-4,
            .patient_opd_details .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .dataTables_wrapper .dataTables_paginate {
                float: none;
                text-align: center;
                margin-top: 10px;
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }

            .dataTables_wrapper .dataTables_info {
                float: none;
                text-align: center;
                margin-bottom: 10px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .followup-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .followup-actions {
                width: 100%;
            }

            .followup-actions .add_progressBtn_div {
                width: 100%;
            }
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            border-left: 4px solid #4cb034;
        }

        .info-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 500;
            color: #333;
        }

        .payment-summary {
            background: #f0f7ff;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .payment-row:last-child {
            border-bottom: none;
            font-weight: 600;
            color: #006637;
        }

        /* New styles for Follow UP section */
        .followup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .followup-actions {
            display: flex;
            gap: 10px;
        }

        /* Profile Image Styles */
        .patient-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .profile-image-wrapper {
            position: relative;
            display: inline-block;
        }

        .profile-image-container {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid #eee;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-image-container i {
            font-size: 60px;
            color: #adb5bd;
        }

        .upload-label {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: #007bff;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid white;
            transition: all 0.2s;
            z-index: 10;
        }

        .upload-label:hover {
            background: #0056b3;
            transform: scale(1.1);
        }

        .save-profile-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 6px 15px;
            border-radius: 4px;
            font-size: 13px;
            margin-top: 10px;
            display: none;
            transition: all 0.2s;
        }

        .save-profile-btn:hover {
            background: #218838;
            transform: translateY(-1px);
        }
    </style>

    <div class="main_content">
        <div class="card profile_cart mb-5">
            <div class="card-header">
                <div class="heading-action">
                    <h3 class="bold font-up fnf-title">Patient Profile</h3>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('hydra.edit', $inquiry->id) }}" class="add_progressBtn_div">
                            <i class="fas fa-edit me-2"></i>Edit Profile
                        </a>
                        <a href="{{ route('hydra.followup.create', ['id' => $inquiry->id]) }}" class="add_progressBtn_div">
                            <i class="fas fa-plus me-2"></i>Add Follow Up
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body px-5">
                <div class="data_detail">
                    <div class="patient_profile">
                        <section>
                            <!-- Basic Information -->
                            <div class="row">
                                <div class="col-lg-4 p-0">
                                    <div class="card mb-4">
                                        <div class="card-body py-2 text-center">
                                            <div class="patient-avatar mb-3">
                                                <form
                                                    action="{{ route('hydra.patient.profile.update-image', $inquiry->id) }}"
                                                    method="POST" enctype="multipart/form-data" id="profileImageForm">
                                                    @csrf
                                                    <div class="profile-image-wrapper">
                                                        <div class="profile-image-container" id="profileImagePreview">
                                                            @if($inquiry->profile_image && Storage::disk('public')->exists($inquiry->profile_image))
                                                                <img src="{{ asset('storage/' . $inquiry->profile_image) }}"
                                                                    alt="Profile Image">
                                                            @else
                                                                <i class="fas fa-user text-muted"></i>
                                                            @endif
                                                        </div>
                                                        <label for="profile_image_input" class="upload-label"
                                                            title="Change Profile Image">
                                                            <i class="fas fa-camera"></i>
                                                        </label>
                                                        <input type="file" name="profile_image" id="profile_image_input"
                                                            class="d-none" accept="image/*"
                                                            onchange="previewPatientImage(this)">
                                                    </div>
                                                    <div id="imageSaveContainer" class="text-center">
                                                        <button type="submit" class="save-profile-btn" id="saveImageBtn">
                                                            <i class="fas fa-save me-1"></i> Save Image
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                            <h5 class="my-3 profile_txt_color mb-2 pb-0">
                                                {{ $inquiry->patient_name ?? 'N/A' }}
                                            </h5>
                                            <p class="text-muted mb-1 pb-0">
                                                HYDRA-{{ str_pad($inquiry->id, 7, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-8 pr-0">
                                    <div class="card mb-3">
                                        <div class="card-body py-2">

                                            <!-- Full Name -->
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <p class="mb-0 profile_txt_color">Full Name</p>
                                                </div>
                                                <div class="col-md-9">
                                                    <p class="text-muted mb-0">{{ $inquiry->patient_name ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <hr class="my-2">

                                            <!-- Address -->
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <p class="mb-0 profile_txt_color">Address</p>
                                                </div>
                                                <div class="col-md-9">
                                                    <p class="text-muted mb-0">
                                                        {{ $inquiry->address ?? 'No address provided' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <hr class="my-2">

                                            <!-- Age -->
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <p class="mb-0 profile_txt_color">Age</p>
                                                </div>
                                                <div class="col-md-9">
                                                    <p class="text-muted mb-0">{{ $inquiry->age ?? 'N/A' }} years</p>
                                                </div>
                                            </div>
                                            <hr class="my-2">

                                            <!-- Gender -->
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <p class="mb-0 profile_txt_color">Gender</p>
                                                </div>
                                                <div class="col-md-9">
                                                    <p class="text-muted mb-0">{{ ucfirst($inquiry->gender ?? 'N/A') }}</p>
                                                </div>
                                            </div>
                                            <hr class="my-2">

                                            <!-- Reference By -->
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <p class="mb-0 profile_txt_color">Reference By</p>
                                                </div>
                                                <div class="col-md-9">
                                                    <p class="text-muted mb-0">
                                                        {{ $inquiry->reference_by ?? 'Not specified' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <!-- Client Type -->
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <p class="mb-0 profile_txt_color">Client Type</p>
                                                </div>
                                                <div class="col-md-9">
                                                    <p class="text-muted mb-0">{{ ucfirst($inquiry->client_type ?? 'New') }}</p>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <!-- Programs Detail -->
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <p class="mb-0 profile_txt_color">Programs Detail</p>
                                                </div>
                                                <div class="col-md-9">
                                                    <p class="text-muted mb-0">
                                                        @php
                                                            $hydraPrograms = is_array($inquiry->program_name)
                                                                ? $inquiry->program_name
                                                                : (json_decode($inquiry->program_name, true) ?? [$inquiry->program_name]);
                                                        @endphp
                                                        {{ !empty(array_filter((array)$hydraPrograms)) ? implode(', ', array_filter((array)$hydraPrograms)) : '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Payment Section -->
                            <div class="patient_data_box mb-4">
                                <div class="row">
                                    <div class="col-lg-12 p-0">
                                        <div class="card-header mb-2">
                                            <div
                                                class="heading-action responsive-block d-flex justify-content-between align-items-center">
                                                <h3 class="bold font-up fnf-title">Payment Information</h3>
                                            </div>
                                        </div>

                                        <div id="payment_table_wrapper"
                                            class="dataTables_wrapper no-footer table-container">
                                            <table class="table caption-top table-striped dataTable no-footer"
                                                id="payment_table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Total Amount</th>
                                                        <th>Paid Amount</th>
                                                        <th>Payment Method</th>
                                                        <th>Due Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="odd">
                                                        <td>1</td>
                                                        <td>₹{{ number_format($inquiry->total_payment ?? 0, 2) }}</td>
                                                        <td>₹{{ number_format($inquiry->given_payment ?? 0, 2) }}</td>
                                                        <td>
                                                            @if($inquiry->foc)
                                                                <span class="badge bg-info">FOC</span>
                                                            @else
                                                                @php
                                                                    $totalPaid = $inquiry->given_payment ?? 0;
                                                                    $cash = $inquiry->cash_payment ?? 0;
                                                                    $online = $inquiry->google_pay ?? 0;

                                                                    if ($totalPaid > 0 && $cash == 0 && $online == 0) {
                                                                        if ($inquiry->payment_mode == 'Cash')
                                                                            $cash = $totalPaid;
                                                                        else
                                                                            $online = $totalPaid;
                                                                    }
                                                                @endphp
                                                                <div class="small">
                                                                    <strong>Cash:</strong> ₹{{ number_format($cash, 2) }}<br>
                                                                    <strong>Online:</strong> ₹{{ number_format($online, 2) }}
                                                                </div>
                                                                @if($inquiry->payment_mode && $cash == 0 && $online == 0)
                                                                    <span
                                                                        class="badge bg-success mt-1">{{ $inquiry->payment_mode }}</span>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="{{ $inquiry->due_payment > 0 ? 'text-danger fw-bold' : 'text-success' }}">
                                                                ₹{{ number_format($inquiry->due_payment ?? 0, 2) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Follow Up Records (Only Inquiry Data) -->
                            <div class="patient_data_box mb-4">
                                <div class="row">
                                    <div class="col-lg-12 p-0">
                                        <div class="followup-header">
                                            <h3 class="bold font-up fnf-title mb-0">Follow UP</h3>
                                            <div class="followup-actions">
                                                <a href="{{ route('hydra.edit', $inquiry->id) }}"
                                                    class="add_progressBtn_div">
                                                    <i class="fas fa-edit me-2"></i>Edit
                                                </a>
                                            </div>
                                        </div>
                                        <div id="followup_table_wrapper"
                                            class="dataTables_wrapper no-footer table-container">
                                            <table class="table caption-top table-striped dataTable no-footer"
                                                id="followup_table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Date</th>
                                                        <th>Time</th>
                                                        <th>Session</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="odd">
                                                        <td>1</td>
                                                        <td>
                                                            @if($inquiry->inquiry_date)
                                                                {{ \Carbon\Carbon::parse($inquiry->inquiry_date)->format('d/m/Y') }}
                                                            @else
                                                                --
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($inquiry->inquiry_time)
                                                                {{ \Carbon\Carbon::parse($inquiry->inquiry_time)->format('h:i A') }}
                                                            @else
                                                                --
                                                            @endif
                                                        </td>
                                                        <td>{{ $inquiry->session ?? '--' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="dataTables_info" id="followup_table_info" role="status"
                                                aria-live="polite">
                                                Showing 1 to 1 of 1 entries
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Health Metrics Section -->
                            <div class="patient_data_box mb-4">
                                <div class="row">
                                    <div class="col-lg-12 p-0">
                                        <div class="card-header mb-2">
                                            <div
                                                class="heading-action responsive-block d-flex justify-content-between align-items-center">
                                                <h3 class="bold font-up fnf-title">Health Metrics</h3>
                                            </div>
                                        </div>
                                        <div class="info-grid px-3">
                                            <div class="info-card">
                                                <div class="info-label">Diet</div>
                                                <div class="info-value">{{ $inquiry->diet ?? '--' }}</div>
                                            </div>
                                            <div class="info-card">
                                                <div class="info-label">Exercise</div>
                                                <div class="info-value">{{ $inquiry->exercise ?? '--' }}</div>
                                            </div>
                                            <div class="info-card">
                                                <div class="info-label">Sleep</div>
                                                <div class="info-value">{{ $inquiry->sleep ?? '--' }}</div>
                                            </div>
                                            <div class="info-card">
                                                <div class="info-label">Water</div>
                                                <div class="info-value">{{ $inquiry->water ?? '--' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize popovers
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });

            // Function to show alert using SweetAlert2
            function showAlert(type, message) {
                Swal.fire({
                    ...getSwalConfig(type === 'danger' ? 'error' : type),
                    icon: type === 'danger' ? 'error' : type,
                    title: type.charAt(0).toUpperCase() + type.slice(1) + '!',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false,
                });
            }
        });

        // Preview Patient Image
        window.previewPatientImage = function (input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var previewContainer = document.getElementById('profileImagePreview');
                    previewContainer.innerHTML = '<img src="' + e.target.result + '" alt="Profile Image">';
                    document.getElementById('saveImageBtn').style.display = 'inline-block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection