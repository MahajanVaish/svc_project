@extends('admin.layouts.layouts')

@php
    if (!function_exists('cleanLhrValue')) {
        function cleanLhrValue($value)
        {
            if ($value === null || $value === '')
                return '--';
            $cleaned = is_string($value) ? str_replace(['[', ']', '"'], '', $value) : $value;
            if (is_array($cleaned))
                $cleaned = implode(', ', $cleaned);
            return (empty($cleaned) || strtolower($cleaned) === 'null') ? '--' : $cleaned;
        }
    }
@endphp

@section('content')
    <style>
        .main_content {
            width: 100%;
            max-width: 1500px;
            margin: 0 auto;
            padding-top: 30px;
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
            color: var(--accent-solid);
            padding-bottom: 0;
            line-height: 1.3em;
            margin-bottom: 0px !important;
        }

        .profile_cart .fnf-title {
            font-size: 18px;
        }

        /* Timeline Styling */
        .clinical-timeline {
            position: relative;
            padding: 20px 0;
            margin-left: 20px;
        }

        .clinical-timeline::before {
            content: '';
            position: absolute;
            left: 9px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--border-subtle);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
            padding-left: 35px;
        }

        .timeline-dot {
            position: absolute;
            left: 0;
            top: 5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--accent-solid);
            border: 4px solid var(--bg-card);
            box-shadow: 0 0 0 2px var(--accent-glow);
            z-index: 2;
        }

        .timeline-content {
            background: var(--bg-main);
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid var(--accent-solid);
            box-shadow: var(--shadow-sm);
        }

        .timeline-date {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-secondary);
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .timeline-title {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .timeline-body {
            font-size: 13px;
            color: var(--text-secondary);
        }

        .timeline-badge {
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            background: #006637;
            color: #fff;
        }

        .timeline-item.inquiry .timeline-dot {
            background: #4cb034;
            box-shadow: 0 0 0 2px #4cb034;
        }

        .timeline-item.inquiry .timeline-content {
            border-left-color: #4cb034;
        }

        .journey-icon {
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #e6f4ea;
            color: #006637;
            border-radius: 8px;
            margin-right: 10px;
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
            background: #006637;
        }

        .dataTables_wrapper table tr th,
        .dataTables_wrapper table tr td {
            padding: 10px 15px !important;
            font-size: 13px;
        }

        .dataTables_wrapper table thead th {
            background: #006637 !important;
            color: #fff !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            border: none;
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
            margin: 0.5em !important;
            padding: 4px 10px !important;
            border: none !important;
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
            color: var(--text-secondary);
            margin-bottom: 5px;
        }

        .patient_opd_details .input-field {
            border: none;
            border-bottom: 1px solid var(--border-subtle);
            background: transparent;
            width: 100%;
            padding: 5px 0;
            color: var(--text-primary);
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
            border: 4px solid var(--border-subtle);
            background: var(--bg-main);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
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

        @media (max-width: 991px) {
            .main_content {
                padding: 15px;
            }
            .info-grid {
                grid-template-columns: repeat(2, 1fr);
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
                font-size: 11px;
            }

            .card-header .heading-action {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .card-header .heading-action h3 {
                margin-bottom: 5px;
            }

            .clinical-timeline {
                margin-left: 10px;
            }
            
            .timeline-item {
                padding-left: 25px;
            }

            .timeline-title {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .info-grid {
                grid-template-columns: 1fr;
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
                margin-top: 15px;
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 5px;
            }

            .dataTables_wrapper .dataTables_info {
                float: none;
                text-align: center;
                margin-bottom: 10px;
                font-size: 11px;
            }

            .fnf-title {
                font-size: 16px;
            }

            .add_progressBtn_div, .add_diet_div {
                width: 100%;
                text-align: center;
                display: block;
            }
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }

        .info-card {
            background: var(--bg-card);
            border-radius: 8px;
            padding: 12px 15px;
            border-left: 4px solid var(--accent-solid);
            box-shadow: var(--shadow-sm);
        }

        .info-label {
            font-size: 11px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .info-value {
            font-size: 15px;
            font-weight: 500;
            color: var(--text-primary);
        }
    </style>

    <div class="main_content">
        <div class="card profile_cart mb-5">
            <div class="card-header">
                <div class="heading-action">
                    <h3 class="bold font-up fnf-title">Patient Profile</h3>
                    <div class="d-flex gap-2">
                        <a href="{{ route('lhr.edit', $inquiry->id) }}" class="add_progressBtn_div">
                            <i class="fas fa-edit me-2"></i>Edit Profile
                        </a>
                        {{-- Change this button to open followup form --}}
                        <a href="{{ route('lhr.followup', $inquiry->id) }}" class="add_progressBtn_div">
                            <i class="fas fa-calendar-plus me-2"></i>Follow Up
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body px-5">
                <div class="data_detail">
                    <div class="patient_profile">
                        <section>
                            <div class="row">
                                <div class="col-lg-4 p-0">
                                    <div class="card mb-4">
                                        <div class="card-body py-2 text-center">
                                            <div class="patient-avatar mb-3">
                                                <form action="{{ route('lhr.patient.profile.update-image', $inquiry->id) }}"
                                                    method="POST" enctype="multipart/form-data" id="profileImageForm">
                                                    @csrf
                                                    <div class="profile-image-wrapper">
                                                        <div class="profile-image-container" id="profileImagePreview">
                                                            @if($inquiry->profile_image && Storage::disk('public')->exists($inquiry->profile_image))
                                                                <img src="{{ asset('storage/' . $inquiry->profile_image) }}"
                                                                    alt="Profile Image">
                                                            @elseif($inquiry->before_picture_1)
                                                                <img src="{{ asset('storage/' . $inquiry->before_picture_1) }}"
                                                                    alt="avatar">
                                                            @elseif($inquiry->after_picture_1)
                                                                <img src="{{ asset('storage/' . $inquiry->after_picture_1) }}"
                                                                    alt="avatar">
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
                                            <h5 class="my-3 profile_txt_color mb-2 pb-0">{{ $inquiry->patient_name }}</h5>
                                            <p class="text-muted mb-1 pb-0">LHR-{{ $inquiry->id }}</p>
                                            <p class="text-muted mb-1 pb-0">Client Type: {{ ucfirst($inquiry->client_type ?? 'New') }}</p>
                                            <p class="text-muted mb-1 pb-0">Mo: {{ $inquiry->mobile_no ?? 'N/A' }}</p>
                                            <p class="text-muted mb-1 pb-0">Email: {{ $inquiry->email ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-8 pr-0">
                                    <div class="card mb-3">
                                        <div class="card-body py-2">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0 profile_txt_color">Full Name</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0">{{ $inquiry->patient_name }}</p>
                                                </div>
                                            </div>
                                            <hr class="my-2">

                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0 profile_txt_color">Address</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0">
                                                        {{ $inquiry->address ?? 'No address provided' }}</p>
                                                </div>
                                            </div>
                                            <hr class="my-2">

                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0 profile_txt_color">Age</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0">{{ $inquiry->age }} years</p>
                                                </div>
                                            </div>
                                            <hr class="my-2">

                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0 profile_txt_color">Gender</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0">{{ ucfirst($inquiry->gender) }}</p>
                                                </div>
                                            </div>
                                            <hr class="my-2">

                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0 profile_txt_color">Reference By</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0">
                                                        {{ $inquiry->reference_by ?? 'Not specified' }}</p>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0 profile_txt_color">Programs Detail</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0">
                                                        @php
                                                            $lhrPrograms = is_array($inquiry->program_name)
                                                                ? $inquiry->program_name
                                                                : (json_decode($inquiry->program_name, true) ?? [$inquiry->program_name]);
                                                        @endphp
                                                        {{ !empty(array_filter((array)$lhrPrograms)) ? implode(', ', array_filter((array)$lhrPrograms)) : '-' }}
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
                                                @if($inquiry->total_payment > 0 || $inquiry->due_payment > 0)
                                                    <!-- <div class="badge bg-info p-2">
                                                        Total Due: ₹{{ number_format($inquiry->due_payment, 2) }}
                                                    </div> -->
                                                @endif
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <div id="payment_table_wrapper" class="dataTables_wrapper no-footer">
                                                <table class="table caption-top table-striped dataTable no-footer"
                                                    id="payment_table" aria-describedby="payment_table_info">
                                                    <thead>
                                                        <tr>
                                                            <th class="sorting_disabled">#</th>
                                                            <th class="sorting_disabled">Payment Method</th>
                                                            <th class="sorting_disabled">Total Amount</th>
                                                            <th class="sorting_disabled">Discount</th>
                                                            <th class="sorting_disabled">Amount Paid</th>
                                                            <th class="sorting_disabled">Due Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="odd">
                                                            <td>1</td>
                                                            <td>
                                                                <b>Cash:</b>
                                                                ₹{{ number_format(($inquiry->cash_payment ?? 0) > 0 ? $inquiry->cash_payment : (($inquiry->payment_method ?? '') == 'cash_payment' ? $inquiry->given_payment : 0), 2) }}<br>
                                                                <b>Google Pay:</b>
                                                                ₹{{ number_format(($inquiry->google_pay ?? 0) > 0 ? $inquiry->google_pay : (($inquiry->payment_method ?? '') == 'google_pay' ? $inquiry->given_payment : 0), 2) }}<br>
                                                                <b>Cheque:</b>
                                                                ₹{{ number_format(($inquiry->cheque_payment ?? 0) > 0 ? $inquiry->cheque_payment : (($inquiry->payment_method ?? '') == 'cheque_payment' ? $inquiry->given_payment : 0), 2) }}
                                                            </td>
                                                            <td>₹{{ number_format($inquiry->total_payment ?? 0, 2) }}</td>
                                                            <td>₹{{ number_format($inquiry->discount_payment ?? 0, 2) }}
                                                            </td>
                                                            <td>₹{{ number_format($inquiry->given_payment ?? 0, 2) }}</td>
                                                            <td>
                                                                <span
                                                                    class="{{ ($inquiry->due_payment ?? 0) > 0 ? 'text-danger fw-bold' : 'text-success' }}">
                                                                    ₹{{ number_format($inquiry->due_payment ?? 0, 2) }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div class="dataTables_info" id="payment_table_info" role="status"
                                                    aria-live="polite">
                                                    Showing 1 to 1 of 1 entries
                                                </div>
                                            </div>
                                        </div>{{-- end table-responsive --}}
                                    </div>
                                </div>
                            </div>

                            <!-- Patient Details Section with Medical Questions -->
                            <div class="row">
                                <div class="col-lg-12 p-0">
                                    <div class="card_custom mt-4 rounded-3 bg-white border">
                                        <!-- Header with Toggle Icon -->
                                        <div class="card-header" id="patientDetailsHeader">
                                            <b><span class="me-1 p-2">Patient Details & Medical Information</span></b>
                                            <span class="toggle-icon" id="toggleIcon"> <i
                                                    class="fas fa-angle-down"></i></span>
                                        </div>

                                        <!-- Patient Details Content (Initially hidden) -->
                                        <div class="patient_opd_details p-4" id="patientDetailsContent"
                                            style="display: none;">
                                            <div class="row">
                                                <!-- Medical Questions Section -->
                                                <div class="col-md-12 mb-4">
                                                    <h5 class="profile_txt_color mb-3">Medical Information</h5>

                                                    <!-- Question 1 -->
                                                    <div class="mb-3">
                                                        <div class="medical-question">1. Do you have any hormonal issues?
                                                        </div>
                                                        <div class="medical-answer">
                                                            @if($inquiry->hormonal_issues == 'yes')
                                                                <span class="yes-badge">Yes</span>
                                                            @elseif($inquiry->hormonal_issues == 'no')
                                                                <span class="no-badge">No</span>
                                                            @else
                                                                <span class="not-specified">Not specified</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Question 2 -->
                                                    <div class="mb-3">
                                                        <div class="medical-question">2. PCOD, Thyroid Issues?</div>
                                                        <div class="medical-answer">
                                                            @if($inquiry->pcod_thyroid == 'yes')
                                                                <span class="yes-badge">Yes</span>
                                                            @elseif($inquiry->pcod_thyroid == 'no')
                                                                <span class="no-badge">No</span>
                                                            @else
                                                                <span class="not-specified">Not specified</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Question 3 -->
                                                    <div class="mb-3">
                                                        <div class="medical-question">3. Are there any ongoing skin
                                                            treatments?</div>
                                                        <div class="medical-answer">
                                                            @if($inquiry->ongoing_treatments == 'yes')
                                                                <span class="yes-badge">Yes</span>
                                                            @elseif($inquiry->ongoing_treatments == 'no')
                                                                <span class="no-badge">No</span>
                                                            @else
                                                                <span class="not-specified">Not specified</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Question 4 -->
                                                    <div class="mb-3">
                                                        <div class="medical-question">4. Any medication or treatment for
                                                            hair loss?</div>
                                                        <div class="medical-answer">
                                                            @if($inquiry->medication == 'yes')
                                                                <span class="yes-badge">Yes</span>
                                                            @elseif($inquiry->medication == 'no')
                                                                <span class="no-badge">No</span>
                                                            @else
                                                                <span class="not-specified">Not specified</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Question 5 -->
                                                    <div class="mb-3">
                                                        <div class="medical-question">5. Do you suffer from any skin
                                                            conditions, allergies, or diseases?</div>
                                                        <div class="medical-answer">
                                                            @if($inquiry->skin_conditions == 'yes')
                                                                <span class="yes-badge">Yes</span>
                                                            @elseif($inquiry->skin_conditions == 'no')
                                                                <span class="no-badge">No</span>
                                                            @else
                                                                <span class="not-specified">Not specified</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Question 6 -->
                                                    <div class="mb-3">
                                                        <div class="medical-question">6. Before you took hair treatment from
                                                            somewhere else?</div>
                                                        <div class="medical-answer">
                                                            @if($inquiry->previous_treatment == 'yes')
                                                                <span class="yes-badge">Yes</span>
                                                            @elseif($inquiry->previous_treatment == 'no')
                                                                <span class="no-badge">No</span>
                                                            @else
                                                                <span class="not-specified">Not specified</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Question 7 -->
                                                    <div class="mb-3">
                                                        <div class="medical-question">7. Which procedure are you currently
                                                            utilizing for hair removal?</div>
                                                        <div class="medical-answer">
                                                            @php
                                                                $procedures = [];
                                                                if ($inquiry->procedure) {
                                                                    $decoded = json_decode($inquiry->procedure, true);
                                                                    if (is_array($decoded)) {
                                                                        $procedures = array_map('ucfirst', $decoded);
                                                                    } else {
                                                                        $procedures = [ucfirst($inquiry->procedure)];
                                                                    }
                                                                }
                                                            @endphp
                                                            @if(count($procedures) > 0)
                                                                <div class="d-flex flex-wrap gap-2">
                                                                    @foreach($procedures as $proc)
                                                                        <span
                                                                            class="badge bg-light text-dark border">{{ $proc }}</span>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <span class="not-specified">Not specified</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Question 8 -->
                                                    <div class="mb-3">
                                                        <div class="medical-question">8. Does your body have any
                                                            implantations or tattoos?</div>
                                                        <div class="medical-answer">
                                                            @if($inquiry->implants_tattoos == 'yes')
                                                                <span class="yes-badge">Yes</span>
                                                            @elseif($inquiry->implants_tattoos == 'no')
                                                                <span class="no-badge">No</span>
                                                            @else
                                                                <span class="not-specified">Not specified</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Treatment Information -->
                                                <div class="col-md-12">
                                                    <h5 class="profile_txt_color mb-3">Treatment Information</h5>
                                                </div>

                                                @php
                                                    $areas_raw = $inquiry->area;
                                                    $sessions_raw = $inquiry->session;
                                                    $codes_raw = $inquiry->area_code;
                                                    $energies_raw = $inquiry->energy;
                                                    $freqs_raw = $inquiry->frequency;
                                                    $shots_raw = $inquiry->shot;

                                                    $areas = is_string($areas_raw) && str_starts_with($areas_raw, '[') ? json_decode($areas_raw, true) : ($areas_raw ? [$areas_raw] : []);
                                                    $sessions = is_string($sessions_raw) && str_starts_with($sessions_raw, '[') ? json_decode($sessions_raw, true) : ($sessions_raw ? [$sessions_raw] : []);
                                                    $codes = is_string($codes_raw) && str_starts_with($codes_raw, '[') ? json_decode($codes_raw, true) : ($codes_raw ? [$codes_raw] : []);
                                                    $energies = is_string($energies_raw) && str_starts_with($energies_raw, '[') ? json_decode($energies_raw, true) : ($energies_raw ? [$energies_raw] : []);
                                                    $freqs = is_string($freqs_raw) && str_starts_with($freqs_raw, '[') ? json_decode($freqs_raw, true) : ($freqs_raw ? [$freqs_raw] : []);
                                                    $shots = is_string($shots_raw) && str_starts_with($shots_raw, '[') ? json_decode($shots_raw, true) : ($shots_raw ? [$shots_raw] : []);

                                                    $rowCount = max(1, count($areas), count($sessions));
                                                @endphp

                                                <div class="col-md-3 py-3">
                                                    <div class="label-text">Year</div>
                                                    <div class="input-field">
                                                        {{ $inquiry->year ?? '--' }}
                                                    </div>
                                                </div>

                                                <div class="col-md-3 py-3">
                                                    <div class="label-text">Staff Name</div>
                                                    <div class="input-field">
                                                        {{ $inquiry->staff_name ?? '--' }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3 py-3">
                                                <div class="label-text">Account</div>
                                                <div class="input-field">
                                                    {{ $inquiry->account ?? '--' }}
                                                </div>
                                            </div>

                                            <div class="col-md-3 py-3">
                                                <div class="label-text">Time</div>
                                                <div class="input-field">
                                                    @if($inquiry->time)
                                                        {{ \Carbon\Carbon::parse($inquiry->time)->format('h:i A') }}
                                                    @else
                                                        --
                                                    @endif
                                                </div>
                                            </div>

                                            @if($inquiry->notes)
                                                <div class="col-md-12 py-3">
                                                    <div class="label-text">Notes</div>
                                                    <div class="p-3 bg-light rounded">
                                                        {{ $inquiry->notes }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <!-- Follow Up Section - Showing Inquiry Data as First Follow Up -->
                    <div class="patient_data_box mb-4">
                        <div class="row">
                            <div class="col-lg-12 p-0">
                                <div class="card-header mb-2">
                                    <div
                                        class="heading-action responsive-block d-flex justify-content-between align-items-center">
                                        <h3 class="bold font-up fnf-title">Follow Up Records</h3>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('lhr.followup', $inquiry->id) }}" class="add_progressBtn_div">
                                                <i class="fas fa-plus me-2"></i> Edit Follow Up
                                            </a>
                                        </div>
                                    </div>
                                </div>
@php
    $areas = json_decode($inquiry->area, true) ?: (is_string($inquiry->area) ? [$inquiry->area] : []);
    $areaCodes = json_decode($inquiry->area_code, true) ?: (is_string($inquiry->area_code) ? [$inquiry->area_code] : []);
    $inquiryRowCount = count($areas);
@endphp
                <div class="table-responsive">
                    <div id="followup_table_wrapper" class="dataTables_wrapper no-footer">
                        <table class="table caption-top table-striped dataTable no-footer"
                            id="followup_table" aria-describedby="followup_table_info">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Body Part</th>
                                    <th>Area Code</th>
                                    <th>Session</th>
                                    <th>Energy</th>
                                    <th>Frequency</th>
                                    <th>Shots</th>
                                    <th>Staff Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- No initial inquiry row displayed; only actual follow-up records are shown -->
                                <!-- Additional Follow Up Records -->
                                @forelse($followUps as $index => $followUp)
                                    <tr class="even">
                                        <td>{{ $index + 2 }}</td>
                                        <td>{{ $followUp->inquiry_date ? \Carbon\Carbon::parse($followUp->inquiry_date)->format('d/m/Y') : '--' }}</td>
                                        <td>{{ cleanLhrValue($followUp->area) }}</td>
                                        <td>{{ cleanLhrValue($followUp->afra_code ?? $followUp->area_code) }}</td>
                                        <td>{{ $followUp->session ?? '--' }} <br><span class="badge bg-info-subtle text-info border border-info-subtle" style="font-size: 10px;">Follow-up session</span></td>
                                        <td>{{ cleanLhrValue($followUp->energy) }}</td>
                                        <td>{{ cleanLhrValue($followUp->frequency) }}</td>
                                        <td>{{ cleanLhrValue($followUp->shot) }}</td>
                                        <td>{{ $followUp->staff_name ?? '--' }}</td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                        <div class="dataTables_info" id="followup_table_info" role="status" aria-live="polite">
                            Showing {{ $followUps->firstItem() ?? 0 }} to {{ $followUps->lastItem() ?? 0 }} of {{ $followUps->total() }} entries
                        </div>
                        <!-- Pagination for Follow Up Records -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $followUps->appends(['program_page' => request('program_page')])->links('pagination::bootstrap-4') }}
                        </div>
                                    </div>
                                </div>{{-- end table-responsive --}}
                            </div>
                        </div>
                    </div>

                    <!-- Programs Section - Showing Inquiry Data as Program -->
                    <div class="patient_data_box mb-4">
                        <div class="row">
                            <div class="col-lg-12 p-0">
                                <div class="card-header mb-2">
                                    <div
                                        class="heading-action responsive-block d-flex justify-content-between align-items-center">
                                        <h3 class="bold font-up fnf-title">Programs</h3>
                                        <div class="d-flex gap-2">

                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <div id="programs_table_wrapper" class="dataTables_wrapper no-footer">
                                        <table class="table caption-top table-striped dataTable no-footer"
                                            id="programs_table" aria-describedby="programs_table_info">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Initial Date</th>
                                                    <th>Area</th>
                                                    <th>Total Sessions</th>
                                                    <th>Used Sessions</th>
                                                    <th>Remaining</th>
                                                    <th>Last Session Settings (E/F/S)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($programs as $program)
                                                    @php
                                                        $usedCount = $program['used_sessions'];
                                                        $totalSessions = (int) $program['total_sessions'];
                                                        $remaining = max(0, $totalSessions - $usedCount);
                                                        $lastSession = $program['last_session'];
                                                    @endphp
                                                    <tr class="odd">
                                                        <td>{{ $loop->iteration + ($programs->currentPage() - 1) * $programs->perPage() }}</td>
                                                        <td>{{ $inquiry->inquiry_date ? \Carbon\Carbon::parse($inquiry->inquiry_date)->format('d/m/Y') : '--' }}</td>
                                                        <td><b>{{ cleanLhrValue($program['area'] ?? '--') }}</b></td>
                                                        <td class="text-center">{{ $totalSessions }}</td>
                                                        <td class="text-center">
                                                            <span class="badge bg-success p-2">{{ $usedCount }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge {{ $remaining > 0 ? 'bg-warning text-dark' : 'bg-danger' }} p-2">{{ $remaining }}</span>
                                                        </td>
                                                        <td>
                                                            @if($lastSession)
                                                                E: {{ $lastSession->energy }} | F: {{ $lastSession->frequency }} | S: {{ $lastSession->shot }}
                                                                <br><small class="text-muted">Last on {{ \Carbon\Carbon::parse($lastSession->inquiry_date)->format('d/m/Y') }}</small>
                                                            @else
                                                                No follow-ups yet
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="dataTables_info" id="programs_table_info" role="status" aria-live="polite">
                                            Showing {{ $programs->firstItem() ?? 0 }} to {{ $programs->lastItem() ?? 0 }} of {{ $programs->total() }} entries
                                        </div>
                                        <!-- Pagination for Programs -->
                                        <div class="d-flex justify-content-center mt-3">
                                            {{ $programs->appends(['followup_page' => request('followup_page')])->links('pagination::bootstrap-4') }}
                                        </div>
                                    </div>
                                </div>{{-- end table-responsive --}}
                            </div>
                        </div>
                    </div>

                    <!-- Treatment Journey Timeline - Visual for non-IT people -->
                    <div class="patient_data_box mb-5">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-header mb-4">
                                    <div
                                        class="heading-action responsive-block d-flex justify-content-between align-items-center">
                                        <h3 class="bold font-up fnf-title"><i class="fas fa-route me-2"></i>Treatment
                                            Journey</h3>
                                    </div>
                                    <p class="text-muted small mb-0">Visual history of all clinical visits and treatments
                                    </p>
                                </div>

                                <div class="clinical-timeline">
                                    <!-- Inquiry Visit -->
                                    <div class="timeline-item inquiry">
                                        <div class="timeline-dot"></div>
                                        <div class="timeline-content">
                                            <div class="timeline-date">
                                                {{ $inquiry->inquiry_date ? \Carbon\Carbon::parse($inquiry->inquiry_date)->format('d M, Y') : 'Unknown' }}
                                            </div>
                                            <div class="timeline-title">
                                                <span><i class="fas fa-stethoscope journey-icon"></i>Initial
                                                    Consultation</span>
                                                <span class="timeline-badge">Patient Onboarded</span>
                                            </div>
                                            <div class="timeline-body">
                                                <p class="mb-1"><strong>Areas Enrolled:</strong>
                                                    {{ cleanLhrValue($inquiry->area) }}</p>
                                                <p class="mb-0 text-muted small"><i
                                                        class="fas fa-user-md me-1"></i>Consulted by
                                                    {{ $inquiry->staff_name ?? '--' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Follow up Visits -->
                                    @foreach($followUps as $index => $followUp)
                                        <div class="timeline-item">
                                            <div class="timeline-dot"></div>
                                            <div class="timeline-content">
                                                <div class="timeline-date">
                                                    {{ $followUp->inquiry_date ? \Carbon\Carbon::parse($followUp->inquiry_date)->format('d M, Y') : 'Unknown' }}
                                                </div>
                                                <div class="timeline-title">
                                                    <span><i class="fas fa-magic journey-icon"></i>Treatment Session
                                                        #{{ $followUps->total() - (($followUps->currentPage() - 1) * $followUps->perPage() + $index) }}</span>
                                                    <span class="timeline-badge bg-primary"><i class="fas fa-check-circle me-1"></i>Follow-up Session</span>
                                                </div>
                                                <div class="timeline-body">
                                                    <div class="d-flex flex-wrap gap-3">
                                                        <div><strong>Area:</strong> {{ cleanLhrValue($followUp->area) }}</div>
                                                        <div><strong>Settings:</strong> E:{{ cleanLhrValue($followUp->energy) }}
                                                            | F:{{ cleanLhrValue($followUp->frequency) }} |
                                                            S:{{ cleanLhrValue($followUp->shot) }}</div>
                                                    </div>
                                                    <p class="mt-2 mb-0 text-muted small"><i
                                                            class="fas fa-user-nurse me-1"></i>Handled by
                                                        {{ $followUp->staff_name ?? '--' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Future Step -->
                                    @php
                                        $lastFollowup = $followUps->last();
                                        $nextDate = $lastFollowup->next_follow_date ?? null;
                                    @endphp
                                    @if($nextDate && $nextDate != 'null' && $nextDate != '')
                                        <div class="timeline-item opacity-75">
                                            <div class="timeline-dot bg-secondary border-secondary shadow-none"></div>
                                            <div class="timeline-content border-left-secondary bg-white">
                                                <div class="timeline-date">
                                                    {{ \Carbon\Carbon::parse($nextDate)->format('d M, Y') }}</div>
                                                <div class="timeline-title">
                                                    <span><i class="fas fa-calendar-alt journey-icon text-secondary"></i>Next
                                                        Scheduled Visit</span>
                                                    <span class="timeline-badge bg-secondary">Upcoming</span>
                                                </div>
                                                <div class="timeline-body text-muted italic">
                                                    Keep track of patient progress for this upcoming session.
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <!-- Pagination for Treatment Journey -->
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $followUps->appends(['program_page' => request('program_page')])->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transformations Section -->
                    <div class="row mb-4">
                        <div class="col-lg-12 p-0 mt-4">
                            <div class="card-header mb-2">
                                <h3 class="bold font-up fnf-title">Before & After Images</h3>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-12 pl-0">
                                    <div class="card mb-4 mb-md-0">
                                        <div class="card-body py-2">
                                            <p
                                                class="py-2 border-bottom mb-2 d-flex justify-content-between align-items-center">
                                                <span class="me-1 p-2"><b>Before</b></span>
                                            </p>
                                            @if($inquiry->before_picture_1)
                                                <div class="image-container">
                                                    <img src="{{ asset('storage/' . $inquiry->before_picture_1) }}"
                                                        alt="Before Image" class="img-fluid rounded mb-3"
                                                        style="max-height: 400px; width: 100%; object-fit: contain;">
                                                </div>
                                            @else
                                                <div class="py-5 text-center">
                                                    <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                                    <p class="text-muted mb-0">Before image not uploaded</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12 pl-0">
                                    <div class="card mb-4 mb-md-0">
                                        <div class="card-body py-2">
                                            <p
                                                class="py-2 border-bottom mb-2 d-flex justify-content-between align-items-center">
                                                <span class="me-1 p-2"><b>After</b></span>
                                            </p>
                                            @if($inquiry->after_picture_1)
                                                <div class="image-container">
                                                    <img src="{{ asset('storage/' . $inquiry->after_picture_1) }}"
                                                        alt="After Image" class="img-fluid rounded mb-3"
                                                        style="max-height: 400px; width: 100%; object-fit: contain;">
                                                </div>
                                            @else
                                                <div class="py-5 text-center">
                                                    <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                                    <p class="text-muted mb-0">After image not uploaded</p>
                                                </div>
                                            @endif
                                        </div>
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

            // Patient Details Toggle
            const patientDetailsHeader = document.getElementById('patientDetailsHeader');
            const patientDetailsContent = document.getElementById('patientDetailsContent');
            const toggleIcon = document.getElementById('toggleIcon');

            if (patientDetailsHeader && patientDetailsContent) {
                patientDetailsHeader.addEventListener('click', function () {
                    if (patientDetailsContent.style.display === 'none') {
                        patientDetailsContent.style.display = 'block';
                        toggleIcon.classList.add('rotate-icon');
                    } else {
                        patientDetailsContent.style.display = 'none';
                        toggleIcon.classList.remove('rotate-icon');
                    }
                });
            }

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

            // Function to show alert
            function showAlert(type, message) {
                const existingAlerts = document.querySelectorAll('.alert-dismissible');
                existingAlerts.forEach(alert => alert.remove());

                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} alert-dismissible fade show mb-4`;
                alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle me-2"></i> 
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

                const mainContent = document.querySelector('.main_content');
                mainContent.parentNode.insertBefore(alertDiv, mainContent);

                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alertDiv);
                    bsAlert.close();
                }, 5000);
            }

            // Delete image button handler
            document.querySelectorAll('.delete-image').forEach(button => {
                button.addEventListener('click', function () {
                    const type = this.getAttribute('data-type');
                    const id = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: `Do you want to delete this ${type} image?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/lhr/${id}/delete-image`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                }
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire(
                                            'Deleted!',
                                            'Image deleted successfully.',
                                            'success'
                                        );
                                        setTimeout(() => {
                                            location.reload();
                                        }, 1500);
                                    } else {
                                        throw new Error(data.message || 'Failed to delete image');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    Swal.fire(
                                        'Error!',
                                        'Error: ' + error.message,
                                        'error'
                                    );
                                });
                        }
                    });
                });
            });
        });
    </script>
@endsection