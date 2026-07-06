@extends('admin.layouts.layouts')

@section('title', isset($lead) ? 'Edit Inquiry' : 'Add New Inquiry')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4 justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-0">
                    <h2 class="mb-0" style="color: var(--accent-solid);">
                        <i class="{{ isset($lead) ? 'fas fa-edit' : 'fas fa-user-plus' }}"></i>
                        {{ isset($lead) ? 'Edit Inquiry' : 'New Inquiry' }}
                    </h2>
                    <div class="d-flex gap-2">
                        <a href="javascript:history.back()" class="btn btn-outline-secondary shadow-sm"
                            style="border-radius: 8px;">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body p-4">
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

                            /* Inline field error messages */
                            .field-error {
                                display: none;
                                color: #dc3545;
                                font-size: 12px;
                                margin-top: 4px;
                                font-weight: 500;
                            }
                            .field-error:not(:empty) {
                                display: block !important;
                            }
                            .input-invalid {
                                border-color: #dc3545 !important;
                                box-shadow: 0 0 0 0.2rem rgba(220,53,69,.15) !important;
                            }

                            /* Autocomplete Styles */
                            .autocomplete-container {
                                position: relative;
                            }

                            .autocomplete-dropdown {
                                position: absolute;
                                top: 100%;
                                left: 0;
                                right: 0;
                                background: white;
                                border: 1px solid #ced4da;
                                border-top: none;
                                max-height: 250px;
                                overflow-y: auto;
                                z-index: 1000;
                                display: none;
                                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                                border-radius: 0 0 8px 8px;
                                transition: all 0.2s ease;
                            }

                            .autocomplete-dropdown.show {
                                display: block;
                            }

                            .autocomplete-item {
                                padding: 10px 15px;
                                cursor: pointer;
                                border-bottom: 1px solid #f1f3f5;
                                font-size: 0.9rem;
                                color: #495057;
                                transition: background-color 0.2s;
                            }

                            .autocomplete-item:last-child {
                                border-bottom: none;
                            }

                            .autocomplete-item:hover,
                            .autocomplete-item.selected {
                                background-color: #f8f9fa;
                                color: #086838;
                            }

                            .autocomplete-item.add-new {
                                color: #086838;
                                font-weight: 600;
                                background-color: #fdfdfd;
                                border-top: 1px solid #eee;
                            }

                            .autocomplete-item.add-new:hover {
                                background-color: #eef7f2;
                            }

                            /* Multi-Select Styles */
                            .multi-select-container {
                                width: 100%;
                            }

                            .selected-items {
                                min-height: 40px;
                                border: 1px solid #ced4da;
                                border-radius: 0.375rem;
                                padding: 8px;
                                margin-bottom: 8px;
                                background-color: #f8f9fa;
                                display: flex;
                                flex-wrap: wrap;
                                gap: 6px;
                                align-items: center;
                            }

                            .selected-item {
                                background-color: #086838;
                                color: white;
                                padding: 4px 10px;
                                border-radius: 15px;
                                font-size: 13px;
                                display: inline-flex;
                                align-items: center;
                                gap: 6px;
                                animation: fadeIn 0.2s ease-in;
                            }

                            .selected-item .remove-item {
                                cursor: pointer;
                                background: none;
                                border: none;
                                color: white;
                                font-size: 14px;
                                padding: 0;
                                width: 16px;
                                height: 16px;
                                border-radius: 50%;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: background-color 0.2s;
                            }

                            .selected-item .remove-item:hover {
                                background-color: rgba(255, 255, 255, 0.2);
                            }

                            .selected-items:empty::before {
                                content: "No items selected";
                                color: #6c757d;
                                font-style: italic;
                                font-size: 13px;
                            }

                            .custom-radio-card {
                                padding: 0 !important;
                                margin: 0 !important;
                            }

                            .radio-card-label {
                                display: flex !important;
                                flex-direction: column;
                                align-items: center;
                                justify-content: center;
                                padding: 15px 25px;
                                border: 2px solid #e0e0e0;
                                border-radius: 12px;
                                cursor: pointer;
                                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                                background: #fff;
                                min-width: 180px;
                                text-align: center;
                                gap: 8px;
                            }

                            .radio-card-label i {
                                font-size: 1.5rem;
                                color: #6c757d;
                                transition: all 0.3s;
                            }

                            .radio-card-label span {
                                font-weight: 500;
                                color: #495057;
                                font-size: 0.95rem;
                            }

                            .custom-radio-card input:checked+.radio-card-label {
                                border-color: var(--accent-solid);
                                background-color: rgba(40, 167, 69, 0.05);
                                box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15);
                                transform: translateY(-2px);
                            }

                            .custom-radio-card input:checked+.radio-card-label i {
                                color: var(--accent-solid);
                                transform: scale(1.1);
                            }

                            .custom-radio-card input:checked+.radio-card-label span {
                                color: var(--accent-solid);
                                font-weight: 600;
                            }

                            .radio-card-label:hover {
                                border-color: var(--accent-solid);
                                background-color: rgba(40, 167, 69, 0.02);
                            }

                            .cursor-pointer {
                                cursor: pointer;
                            }

                            #focCheck:checked+label {
                                color: var(--accent-solid) !important;
                            }

                            .custom-checkbox:hover {
                                border-color: var(--accent-solid) !important;
                                background-color: rgba(40, 167, 69, 0.05) !important;
                            }

                            @keyframes fadeIn {
                                from {
                                    opacity: 0;
                                    transform: scale(0.8);
                                }

                                to {
                                    opacity: 1;
                                    transform: scale(1);
                                }
                            }
                        </style>
                        <form action="{{ route('store.inquiry') }}" method="POST" id="inquiryForm">
                            @if(isset($lead->id))
                                <input type="hidden" name="id" value="{{ $lead->id }}">
                            @endif
                            @csrf

                            <!-- Hidden fields -->
                            <input type="hidden" name="existing_patient_id" id="existingPatientId"
                                value="{{ isset($lead) ? $lead->patient_id ?? '' : '' }}">
                            <input type="hidden" name="form_source" value="diet_chart">

                            @if (isset($lead) && $lead->id)
                                <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                            @endif

                            {{-- Hidden Diet Chart input removed to allow user to select process stage purely from checkboxes --}}

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label class="form-label d-block fw-bold"
                                        style="color: var(--accent-solid); font-size: 1.1rem; margin-bottom: 12px;">
                                        <i class="fas fa-id-card"></i> Patient Identity / Source
                                    </label>
                                    <div class="d-flex gap-4">
                                        <div class="form-check custom-radio-card">
                                            <input class="form-check-input d-none" type="radio" name="is_online_abroad"
                                                id="source_general" value="0" {{ (old('is_online_abroad', $lead->is_online_abroad ?? 0) == 0 && request('is_online_abroad') != 1) ? 'checked' : '' }}>
                                            <label class="radio-card-label" for="source_general">
                                                <i class="fas fa-home"></i>
                                                <span>General (Local/Indian)</span>
                                            </label>
                                        </div>
                                        <div class="form-check custom-radio-card">
                                            <input class="form-check-input d-none" type="radio" name="is_online_abroad"
                                                id="source_online" value="1" {{ (old('is_online_abroad', $lead->is_online_abroad ?? 0) == 1 || request('is_online_abroad') == 1) ? 'checked' : '' }}>
                                            <label class="radio-card-label" for="source_online">
                                                <i class="fas fa-globe"></i>
                                                <span>Online / Abroad Patient</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Select branch <span class="text-danger">*</span></label>
                                        @if(isset($lead) && $lead->id)
                                            <input type="text" class="form-control" value="{{ $lead->branch ?? '' }}" readonly>
                                            <input type="hidden" name="branch" value="{{ $lead->branch_id ?? '' }}">
                                        @else
                                            <select class="form-control" id="branch" name="branch" required>
                                                <option value="">Select branch</option>
                                                @foreach ($branches as $b)
                                                    <option value="{{ $b->branch_id }}" {{ (old('branch') == $b->branch_id) ? 'selected' : '' }}>{{ $b->branch_name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="field-error" id="branch-error"></div>
                                        @endif
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="patient_f_name" id="patientFName"
                                                value="{{ old('patient_f_name', $lead->patient_f_name ?? '') }}" required>
                                            <div class="field-error" id="fname-error"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Middle Name</label>
                                            <input type="text" class="form-control" name="patient_m_name" id="patientMName"
                                                value="{{ old('patient_m_name', $lead->patient_m_name ?? '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="patient_l_name" id="patientLName"
                                                value="{{ old('patient_l_name', $lead->patient_l_name ?? '') }}" required>
                                            <div class="field-error" id="lname-error"></div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Phone Number</label>
                                            <input type="text" class="form-control" name="phone_no" id="phoneInput"
                                                value="{{ old('phone_no', $lead->phone_no ?? '') }}">
                                            <div class="field-error" id="phone-error"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Age</label>
                                            <input type="number" class="form-control" name="age" id="ageInput"
                                                value="{{ old('age', $lead->age ?? '') }}">
                                            <div class="field-error" id="age-error"></div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Gender</label>
                                            <select class="form-select" name="gender">
                                                <option value="">Select Gender</option>
                                                <option value="Male" {{ (old('gender', $lead->gender ?? '') == 'Male') ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ (old('gender', $lead->gender ?? '') == 'Female') ? 'selected' : '' }}>Female</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Email ID</label>
                                            <input type="email" class="form-control" name="email" id="emailInput"
                                                value="{{ old('email', $lead->email ?? '') }}">
                                            <div class="field-error" id="email-error"></div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea class="form-control" name="address" id="addressInput"
                                            rows="2">{{ old('address', $lead->address ?? '') }}</textarea>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Reference By</label>
                                            <input type="text" class="form-control" name="reference_by"
                                                value="{{ old('reference_by', $lead->refrance ?? '') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Reference to</label>
                                            <input type="text" class="form-control" name="reference_to"
                                                value="{{ old('reference_to', $lead->reference_to ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Height (cm) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.1" class="form-control" name="height"
                                                id="heightInput" value="{{ old('height', $lead->height ?? '') }}"
                                                oninput="calculateMetrics()">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Weight (kg) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.1" class="form-control" name="weight"
                                                id="weightInput" value="{{ old('weight', $lead->weight ?? '') }}"
                                                oninput="calculateMetrics()">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">BMI <small class="text-muted">(auto)</small></label>
                                            <input type="text" class="form-control" id="bmiInput" name="bmi" readonly
                                                style="background-color: #f8f9fa;"
                                                value="{{ old('bmi', $lead->bmi ?? '') }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Visit Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="inquiry_date"
                                                value="{{ old('inquiry_date', isset($lead) ? $lead->getRawOriginal('inquiry_date') : date('Y-m-d')) }}"
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Time <span class="text-danger">*</span></label>
                                            <input type="time" class="form-control" name="inquiry_time"
                                                value="{{ old('inquiry_time', isset($lead) ? $lead->getRawOriginal('inquiry_time') : date('H:i')) }}"
                                                required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Complaint <span class="text-danger">*</span></label>
                                                <div class="multi-select-container">
                                                    <div class="selected-items" id="complain-selected">
                                                        <!-- Selected complaints will appear here -->
                                                    </div>
                                                    <div class="autocomplete-container">
                                                        <input type="text" class="form-control" id="complain"
                                                            placeholder="Type to add complaint..." autocomplete="off">
                                                        <div class="autocomplete-dropdown" id="complain-dropdown"></div>
                                                    </div>
                                                    <input type="hidden" name="complain" id="complain-hidden"
                                                        value="{{ old('complain', $lead->complain ?? '') }}">
                                                    <div class="field-error" id="complain-error"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Diagnosis <span class="text-danger">*</span></label>
                                                <div class="multi-select-container">
                                                    <div class="selected-items" id="diagnosis-selected">
                                                        <!-- Selected diagnoses will appear here -->
                                                    </div>
                                                    <div class="autocomplete-container">
                                                        <input type="text" class="form-control" id="diagnosis"
                                                            placeholder="Type to add diagnosis..." autocomplete="off">
                                                        <div class="autocomplete-dropdown" id="diagnosis-dropdown"></div>
                                                    </div>
                                                    <input type="hidden" name="diagnosis" id="diagnosis-hidden"
                                                        value="{{ old('diagnosis', $lead->diagnosis ?? '') }}">
                                                    <div class="field-error" id="diagnosis-error"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Attended By</label>
                                            <select class="form-select" name="inquery_given_by">
                                                <option value="">Select Doctor</option>
                                                @foreach($doctors as $doctor)
                                                    <option value="{{ $doctor->name }}" {{ (old('inquery_given_by', $lead->inquery_given_by ?? '') == $doctor->name) ? 'selected' : '' }}>
                                                        {{ $doctor->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Client Type</label>
                                            <select class="form-select" name="client_old_new">
                                                <option value="New" {{ (old('client_old_new', $lead->client_old_new ?? '') == 'New') ? 'selected' : '' }}>New</option>
                                                <option value="Old" {{ (old('client_old_new', $lead->client_old_new ?? '') == 'Old') ? 'selected' : '' }}>Old</option>
                                            </select>
                                        </div>
                                    </div>
                                    <style>
                                        .stage-container {
                                            display: flex;
                                            gap: 12px;
                                            flex-wrap: wrap;
                                            margin-top: 5px;
                                        }

                                        .stage-pill {
                                            cursor: pointer;
                                            padding: 8px 18px;
                                            border-radius: 25px;
                                            border: 2px solid #e9ecef;
                                            background-color: #fff;
                                            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
                                            display: flex;
                                            align-items: center;
                                            gap: 8px;
                                            font-size: 14px;
                                            font-weight: 500;
                                            color: #6c757d;
                                            user-select: none;
                                        }

                                        .stage-pill:hover {
                                            border-color: #dee2e6;
                                            background-color: #f8f9fa;
                                            transform: translateY(-1px);
                                        }

                                        .stage-pill i {
                                            font-size: 14px;
                                            opacity: 0.7;
                                        }

                                        .stage-input {
                                            display: none;
                                        }

                                        .stage-input:checked+.stage-pill {
                                            background-color: #086838;
                                            color: white;
                                            border-color: #086838;
                                            box-shadow: 0 4px 10px rgba(8, 104, 56, 0.2);
                                        }

                                        .stage-input:checked+.stage-pill i {
                                            opacity: 1;
                                        }
                                    </style>

                                    <div class="mb-3" id="processStageContainer">
                                        <label class="form-label d-block mb-3">Process Stage <span
                                                class="text-danger">*</span></label>
                                        <div class="stage-container">
                                            <div class="stage-item">
                                                <input class="stage-input" type="checkbox" name="user_status[]"
                                                    value="Pending" id="status_pending" {{ in_array('Pending', old('user_status', $selectedStatuses ?? [])) ? 'checked' : '' }}>
                                                <label class="stage-pill" for="status_pending">
                                                    <i class="fas fa-clock"></i> Pending
                                                </label>
                                            </div>

                                            <div class="stage-item">
                                                <input class="stage-input" type="checkbox" name="user_status[]"
                                                    value="Diet Chart" id="status_diet" {{ in_array('Diet Chart', old('user_status', $selectedStatuses ?? [])) ? 'checked' : '' }}>
                                                <label class="stage-pill" for="status_diet">
                                                    <i class="fas fa-utensils"></i> Diet Chart
                                                </label>
                                            </div>

                                            <div class="stage-item">
                                                <input class="stage-input" type="checkbox" name="user_status[]"
                                                    value="Joined" id="status_joined" {{ in_array('Joined', old('user_status', $selectedStatuses ?? [])) ? 'checked' : '' }}>
                                                <label class="stage-pill" for="status_joined">
                                                    <i class="fas fa-user-check"></i> Joined
                                                </label>
                                            </div>

                                            <div class="stage-item">
                                                <input class="stage-input" type="checkbox" name="user_status[]"
                                                    value="InBody" id="status_inbody" {{ in_array('InBody', old('user_status', $selectedStatuses ?? [])) ? 'checked' : '' }}>
                                                <label class="stage-pill" for="status_inbody">
                                                    <i class="fas fa-weight"></i> InBody
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Health Metrics Section -->
                            <div class="mt-4" id="healthMetricsSection">
                                <label class="form-label d-block fw-bold"
                                    style="color: var(--accent-solid); font-size: 1.1rem; margin-bottom: 12px;">
                                    <i class="fas fa-heartbeat"></i> Health Metrics
                                </label>
                                <div class="row bg-light p-3 rounded" style="border: 1px solid #e0e0e0;">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Dietary Habits</label>
                                        <input type="text" id="diet" name="diet" class="form-control" placeholder="Diet"
                                            value="{{ old('diet', $optMeta['diet'] ?? '') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Exercise Regimen</label>
                                        <input type="text" id="exercise" name="exercise" class="form-control"
                                            placeholder="Exercise"
                                            value="{{ old('exercise', $optMeta['exercise'] ?? '') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Sleep Pattern</label>
                                        <input type="text" id="sleep" name="sleep" class="form-control" placeholder="Sleep"
                                            value="{{ old('sleep', $optMeta['sleep'] ?? '') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Water Intake</label>
                                        <input type="text" id="water" name="water" class="form-control" placeholder="Water"
                                            value="{{ old('water', $optMeta['water'] ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row align-items-center mb-4">
                                <div class="col-md-4">
                                    <div class="form-check p-0 m-0">
                                        <div class="custom-checkbox d-flex align-items-center bg-light p-3 rounded cursor-pointer"
                                            style="border: 1px solid #e0e0e0; transition: all 0.3s ease;">
                                            <input class="form-check-input ms-0 me-3 cursor-pointer" type="checkbox"
                                                name="inquiry_foc" value="1" id="focCheck" {{ old('inquiry_foc') ? 'checked' : (isset($lead) && $lead->getRawOriginal('inquiry_foc') === 'Yes' ? 'checked' : '') }} style="width: 20px; height: 20px;">
                                            <label class="form-check-label fw-bold mb-0 cursor-pointer" for="focCheck"
                                                style="font-size: 14px; color: #495057;">
                                                FOC (Free of Charge Inquiry)
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div id="joinedChargesContainer" style="display: none;">
                                        <div class="bg-light p-3 rounded"
                                            style="border: 1px solid #28a745; background-color: rgba(40, 167, 69, 0.02) !important;">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label class="form-label fw-bold mb-0 text-success"
                                                    style="font-size: 14px;">
                                                    <i class="fas fa-tag"></i> Joined Charges
                                                </label>
                                                <button type="button" id="add_charge_row" class="btn btn-success btn-sm"
                                                    style="border-radius: 50%; width: 28px; height: 28px; padding: 0;">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <div id="charge_rows_wrapper">
                                                @php
                                                    $savedProgramIds = [];
                                                    $savedSessions = [];
                                                    $savedMonths = [];
                                                    if (isset($optMeta['joined_program_ids'])) {
                                                        $raw = $optMeta['joined_program_ids'];
                                                        if (is_array($raw)) {
                                                            $savedProgramIds = $raw;
                                                        } else {
                                                            $savedProgramIds = json_decode($raw, true) ?: (array) $raw;
                                                        }
                                                    }
                                                    if (!empty($optMeta['programs_array'])) {
                                                        $progArr = json_decode($optMeta['programs_array'], true) ?: [];
                                                        foreach ($progArr as $index => $p) {
                                                            $savedSessions[] = $p['session'] ?? '';
                                                            $savedMonths[] = $p['months'] ?? '';
                                                            
                                                            // Fallback: If we don't have a program ID at this index but we have a program name, resolve it
                                                            if (empty($savedProgramIds[$index]) && !empty($p['program'])) {
                                                                $progObj = \App\Models\ManageProgram::where('program_name', $p['program'])
                                                                    ->where('delete_status', 0)
                                                                    ->first();
                                                                if ($progObj) {
                                                                    $savedProgramIds[$index] = $progObj->id;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    // Filter out empty IDs so we don't render empty selects
                                                    $savedProgramIds = array_values(array_filter($savedProgramIds));
                                                @endphp

                                                @if(count($savedProgramIds) > 0)
                                                    @foreach($savedProgramIds as $index => $savedId)
                                                        @php
                                                            $s_val = $savedSessions[$index] ?? '';
                                                            $m_val = $savedMonths[$index] ?? '';
                                                        @endphp
                                                        <div class="charge-row d-flex align-items-center gap-2 mb-2">
                                                            <div class="flex-grow-1">
                                                                <select class="form-select border-success joined-charge-select"
                                                                    name="joined_program_id[]"
                                                                    style="border-radius: 8px; font-size: 13px;">
                                                                    <option value="">Select Charge</option>
                                                                    @foreach($joinedPrograms as $program)
                                                                        <option value="{{ $program->id }}" {{ (string) $program->id === (string) $savedId ? 'selected' : '' }}
                                                                            data-price="{{ $program->program_price }}"
                                                                            data-name="{{ $program->program_name }}">
                                                                            {{ $program->program_name }} -
                                                                            ₹{{ $program->program_price }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div style="width: 100px;">
                                                                <input type="number" class="form-control session-input"
                                                                    name="session[]" value="{{ $s_val }}" placeholder="Session"
                                                                    min="0" style="border-radius: 8px; font-size: 13px;">
                                                            </div>
                                                            <div style="width: 100px;">
                                                                <input type="number" class="form-control months-input"
                                                                    name="months[]" value="{{ $m_val }}" placeholder="Months"
                                                                    min="0" style="border-radius: 8px; font-size: 13px;">
                                                            </div>
                                                            <button type="button"
                                                                class="btn btn-outline-danger btn-sm remove-charge-row"
                                                                style="{{ $index > 0 ? 'display: block;' : 'display: none;' }} border-radius: 50%; width: 24px; height: 24px; padding: 0;">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="charge-row d-flex align-items-center gap-2 mb-2">
                                                        <div class="flex-grow-1">
                                                            <select class="form-select border-success joined-charge-select"
                                                                name="joined_program_id[]"
                                                                style="border-radius: 8px; font-size: 13px;">
                                                                <option value="">Select Charge</option>
                                                                @foreach($joinedPrograms as $program)
                                                                    <option value="{{ $program->id }}"
                                                                        data-price="{{ $program->program_price }}"
                                                                        data-name="{{ $program->program_name }}">
                                                                        {{ $program->program_name }} -
                                                                        ₹{{ $program->program_price }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div style="width: 100px;">
                                                            <input type="number" class="form-control session-input"
                                                                name="session[]" placeholder="Session" min="0"
                                                                style="border-radius: 8px; font-size: 13px;">
                                                        </div>
                                                        <div style="width: 100px;">
                                                            <input type="number" class="form-control months-input"
                                                                name="months[]" placeholder="Months" min="0"
                                                                style="border-radius: 8px; font-size: 13px;">
                                                        </div>
                                                        <button type="button"
                                                            class="btn btn-outline-danger btn-sm remove-charge-row"
                                                            style="display: none; border-radius: 50%; width: 24px; height: 24px; padding: 0;">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                            <input type="hidden" name="program_names" id="selected_program_names">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Online/Abroad Program Dropdown -->
                            <div class="row mb-3" id="onlineProgramDropdownRow" style="display: none;">
                                <div class="col-md-4">
                                    <label class="form-label">Online/Abroad Program</label>
                                    <select class="form-select" id="online_program_select">
                                        <option value="">Select Program</option>
                                        <option value="7000" data-name="1 Month Program - ₹7,000" {{ (old('program_names', $optMeta['online_program_label'] ?? $optMeta['program_name'] ?? '') == '1 Month Program - ₹7,000') ? 'selected' : '' }}>1 month program - ₹7,000</option>
                                        <option value="21000" data-name="3 Months Program - ₹21,000" {{ (old('program_names', $optMeta['online_program_label'] ?? $optMeta['program_name'] ?? '') == '3 Months Program - ₹21,000') ? 'selected' : '' }}>3 months program - ₹21,000</option>
                                        <option value="26000" data-name="4 Months Program - ₹26,000" {{ (old('program_names', $optMeta['online_program_label'] ?? $optMeta['program_name'] ?? '') == '4 Months Program - ₹26,000') ? 'selected' : '' }}>4 months program - ₹26,000</option>
                                        <option value="36000" data-name="6 Months Program - ₹36,000" {{ (old('program_names', $optMeta['online_program_label'] ?? $optMeta['program_name'] ?? '') == '6 Months Program - ₹36,000') ? 'selected' : '' }}>6 months program - ₹36,000</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Balanced Payment Row - Visible always, but FOC affects calculations -->
                            <div id="paymentRow" class="row align-items-end mb-3">
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Registration Charges (₹)</label>
                                    <input type="number" class="form-control" name="total_payment" id="total_payment"
                                        value="{{ old('total_payment', $lead->payment ?? '0') }}">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Discount (₹)</label>
                                    <input type="number" step="0.01" class="form-control" name="discount_payment"
                                        id="discount_payment"
                                        value="{{ old('discount_payment', $lead->discount_payment ?? '') }}"
                                        placeholder="0.00">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Cash (₹)</label>
                                    <input type="number" step="0.01" class="form-control" name="cash_payment" id="cash_payment"
                                        value="{{ old('cash_payment', $optMeta['cash_payment'] ?? '') }}"
                                        placeholder="0.00">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">G-Pay (₹)</label>
                                    <input type="number" step="0.01" class="form-control" name="gpay_payment" id="gpay_payment"
                                        value="{{ old('gpay_payment', $optMeta['gpay_payment'] ?? '') }}"
                                        placeholder="0.00">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Cheque (₹)</label>
                                    <input type="number" step="0.01" class="form-control" name="cheque_payment" id="cheque_payment"
                                        value="{{ old('cheque_payment', $optMeta['cheque_payment'] ?? '') }}"
                                        placeholder="0.00">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Paid Amount (₹)</label>
                                    <input type="number" step="0.01" class="form-control" name="given_payment"
                                        id="given_payment"
                                        value="{{ old('given_payment', $optMeta['given_payment'] ?? '') }}"
                                        placeholder="0.00" readonly style="background-color: #f8f9fa;">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Due Balance (₹)</label>
                                    <input type="number" class="form-control" id="due_payment" name="due_payment" value="0" readonly
                                        style="background-color: #f8f9fa;">
                                </div>
                            </div>

                            <div class="d-flex flex-column flex-sm-row justify-content-end mt-4 gap-2">
                                <button type="submit" class="btn btn-primary btn-lg btn-mobile-full">
                                    <i class="fas fa-save"></i> {{ isset($lead) ? 'Update' : 'Submit' }}
                                </button>
                                <a href="{{ route('diet.chart') }}" class="btn btn-secondary btn-lg btn-mobile-full">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>

    <script>
        // Metrics Calculation Function (BMI, IBW, etc.)
        function calculateMetrics() {
            let height = parseFloat(document.getElementById('heightInput')?.value);
            let weight = parseFloat(document.getElementById('weightInput')?.value);
            let bmiInput = document.getElementById('bmiInput');
            let ibwInput = document.getElementById('ibwInput');
            let overWeightInput = document.getElementById('overWeightInput');
            let underWeightInput = document.getElementById('underWeightInput');
            let targetWeightInput = document.getElementById('targetWeightInput');

            // BMI Calculation
            if (height && weight && height > 0 && bmiInput) {
                let heightMeter = height / 100;
                let bmi = (weight / (heightMeter * heightMeter)).toFixed(2);
                bmiInput.value = bmi;
            } else if (bmiInput) {
                bmiInput.value = '';
            }

            // IBW Calculation: Height (cm) - 100 (as per user request)
            if (height && height > 100) {
                let ibw = height - 100;
                if (ibwInput) ibwInput.value = ibw.toFixed(2);
                if (targetWeightInput) targetWeightInput.value = ibw.toFixed(2);

                if (weight) {
                    if (weight > ibw) {
                        if (overWeightInput) overWeightInput.value = (weight - ibw).toFixed(2);
                        if (underWeightInput) underWeightInput.value = '0.00';
                    } else if (weight < ibw) {
                        if (underWeightInput) underWeightInput.value = (ibw - weight).toFixed(2);
                        if (overWeightInput) overWeightInput.value = '0.00';
                    } else {
                        if (overWeightInput) overWeightInput.value = '0.00';
                        if (underWeightInput) underWeightInput.value = '0.00';
                    }
                }
            } else {
                if (ibwInput) ibwInput.value = '';
                if (overWeightInput) overWeightInput.value = '';
                if (underWeightInput) underWeightInput.value = '';
                if (targetWeightInput) targetWeightInput.value = '';
            }
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function () {
            // Metrics Calculation
            const heightInput = document.getElementById('heightInput');
            const weightInput = document.getElementById('weightInput');

            if (heightInput) heightInput.addEventListener('input', calculateMetrics);
            if (weightInput) weightInput.addEventListener('input', calculateMetrics);

            // FOC Checkbox and Payment Calculation Handler
            const focCheck = document.getElementById('focCheck');
            const totalPaymentInput = document.getElementById('total_payment');
            const givenPaymentInput = document.getElementById('given_payment');
            const discountInput = document.getElementById('discount_payment');
            const statusCheckboxes = document.querySelectorAll('input[name="user_status[]"]');
            const statusDiet = document.getElementById('status_diet');
            const statusInbody = document.getElementById('status_inbody');
            const statusPending = document.getElementById('status_pending');
            const statusJoined = document.getElementById('status_joined');

            const joinedChargesContainer = document.getElementById('joinedChargesContainer');
            const joinedChargesDropdown = document.getElementById('joined_charges_dropdown');

            function calculateDue() {
                const total = parseFloat(totalPaymentInput?.value) || 0;
                const given = parseFloat(givenPaymentInput?.value) || 0;
                const discount = parseFloat(discountInput?.value) || 0;

                let due = 0;
                if (focCheck && focCheck.checked) {
                    due = 0;
                } else {
                    due = Math.max(0, total - given - discount);
                }

                if (document.getElementById('due_payment')) {
                    document.getElementById('due_payment').value = due.toFixed(2);
                }
            }

            function toggleJoinedCharges() {
                if (statusJoined && joinedChargesContainer) {
                    joinedChargesContainer.style.display = statusJoined.checked ? 'block' : 'none';
                    if (!statusJoined.checked) {
                        // Reset all charge rows
                        const rows = document.querySelectorAll('.charge-row');
                        rows.forEach((row, index) => {
                            if (index === 0) {
                                row.querySelector('select').value = '';
                                row.querySelector('.remove-charge-row').style.display = 'none';
                            } else {
                                row.remove();
                            }
                        });
                        calculateRegistrationCharges();
                    }
                }
            }

            function updatePricingAndSelection(clickedCheckbox) {
                if (clickedCheckbox.id === 'status_inbody' && clickedCheckbox.checked) {
                    if (statusDiet) statusDiet.checked = true;
                    if (statusPending) statusPending.checked = false;
                    if (statusJoined) statusJoined.checked = false;
                }

                if (clickedCheckbox.id === 'status_diet' && clickedCheckbox.checked) {
                    if (statusPending) statusPending.checked = false;
                    if (statusJoined) statusJoined.checked = false;
                }

                if ((clickedCheckbox.id === 'status_pending' || clickedCheckbox.id === 'status_joined') && clickedCheckbox.checked) {
                    statusCheckboxes.forEach(cb => {
                        if (cb.id !== clickedCheckbox.id) cb.checked = false;
                    });
                    toggleJoinedCharges();
                }

                const healthMetricsSection = document.getElementById('healthMetricsSection');
                if (healthMetricsSection) {
                    healthMetricsSection.style.display = 'block';
                }

                calculateRegistrationCharges();
            }

            const dietChartPrice = {{ $dietChartPrice ?? 2000 }};
            const inbodyPrice = {{ $inbodyPrice ?? 800 }};

            const sourceOnline = document.getElementById('source_online');
            const sourceGeneral = document.getElementById('source_general');
            const onlineProgramDropdownRow = document.getElementById('onlineProgramDropdownRow');
            const onlineProgramSelect = document.getElementById('online_program_select');

            function toggleOnlineProgram() {
                if (sourceOnline && onlineProgramDropdownRow) {
                    const isOnline = sourceOnline.checked;
                    onlineProgramDropdownRow.style.display = isOnline ? 'block' : 'none';

                    const processStageContainer = document.getElementById('processStageContainer');
                    if (processStageContainer) {
                        processStageContainer.style.display = isOnline ? 'none' : 'block';
                    }

                    const healthMetricsSection = document.getElementById('healthMetricsSection');
                    if (healthMetricsSection) {
                        healthMetricsSection.style.display = 'block';
                    }

                    calculateRegistrationCharges();
                }
            }

            if (sourceOnline) sourceOnline.addEventListener('change', toggleOnlineProgram);
            if (sourceGeneral) sourceGeneral.addEventListener('change', toggleOnlineProgram);
            if (onlineProgramSelect) onlineProgramSelect.addEventListener('change', calculateRegistrationCharges);

            // Call on load
            setTimeout(toggleOnlineProgram, 100);

            function calculateRegistrationCharges() {
                let totalPrice = 0;
                let programNames = [];

                if (sourceOnline && sourceOnline.checked) {
                    const opt = onlineProgramSelect.options[onlineProgramSelect.selectedIndex];
                    if (opt && opt.value) {
                        totalPrice = parseFloat(opt.value) || 0;
                        if (opt.dataset.name) {
                            programNames.push(opt.dataset.name);
                        }
                    }
                } else if (statusJoined && statusJoined.checked) {
                    const rows = document.querySelectorAll('.charge-row');
                    rows.forEach(row => {
                        const select = row.querySelector('.joined-charge-select');
                        const sInput = row.querySelector('.session-input');
                        const mInput = row.querySelector('.months-input');

                        if (select) {
                            const opt = select.options[select.selectedIndex];
                            if (opt && opt.value) {
                                const basePrice = parseFloat(opt.dataset.price) || 0;
                                let sCount = 0;
                                let mCount = 0;

                                if (sInput && sInput.value) sCount = parseInt(sInput.value, 10);
                                if (mInput && mInput.value) {
                                    const match = mInput.value.match(/\d+/);
                                    if (match) mCount = parseInt(match[0], 10);
                                }

                                const multiplier = Math.max(1, sCount, mCount);
                                totalPrice += basePrice * multiplier;

                                if (opt.dataset.name) {
                                    programNames.push(opt.dataset.name);
                                }
                            }
                        }
                    });
                } else if (statusDiet && statusDiet.checked && statusInbody && statusInbody.checked) {
                    totalPrice = dietChartPrice + inbodyPrice;
                } else if (statusDiet && statusDiet.checked) {
                    totalPrice = dietChartPrice;
                } else if (statusInbody && statusInbody.checked) {
                    totalPrice = inbodyPrice;
                }

                if (totalPaymentInput) {
                    if (focCheck && focCheck.checked) {
                        totalPaymentInput.value = 0;
                        if (discountInput) discountInput.value = 0;
                        if (givenPaymentInput) givenPaymentInput.value = 0;
                    } else {
                        totalPaymentInput.value = totalPrice;
                    }
                    calculateDue();
                }

                const hiddenNamesInput = document.getElementById('selected_program_names');
                if (hiddenNamesInput) {
                    hiddenNamesInput.value = programNames.join(', ');
                }
            }

            // Repeater logic for Joined Charges
            const addChargeBtn = document.getElementById('add_charge_row');
            const chargeRowsWrapper = document.getElementById('charge_rows_wrapper');

            if (addChargeBtn && chargeRowsWrapper) {
                addChargeBtn.addEventListener('click', function () {
                    const firstRow = chargeRowsWrapper.querySelector('.charge-row');
                    const newRow = firstRow.cloneNode(true);

                    const select = newRow.querySelector('select');
                    select.value = '';

                    const removeBtn = newRow.querySelector('.remove-charge-row');
                    removeBtn.style.display = 'block';

                    chargeRowsWrapper.appendChild(newRow);
                });

                chargeRowsWrapper.addEventListener('click', function (e) {
                    if (e.target.closest('.remove-charge-row')) {
                        e.target.closest('.charge-row').remove();
                        calculateRegistrationCharges();
                    }
                });

                chargeRowsWrapper.addEventListener('input', function (e) {
                    if (e.target.classList.contains('joined-charge-select') || e.target.classList.contains('session-input') || e.target.classList.contains('months-input')) {
                        console.log('Charge row input changed');
                        calculateRegistrationCharges();

                        // Update hidden names input
                        const selectedNames = [];
                        document.querySelectorAll('.joined-charge-select').forEach(select => {
                            const opt = select.options[select.selectedIndex];
                            if (opt && opt.value) {
                                selectedNames.push(opt.dataset.name);
                            }
                        });
                        const hiddenNamesInput = document.getElementById('selected_program_names');
                        if (hiddenNamesInput) {
                            hiddenNamesInput.value = selectedNames.join(', ');
                        }
                    }
                });
            }

            const paymentRow = document.getElementById('paymentRow');
            const togglePaymentRow = (recalculate = true) => {
                if (focCheck) {
                    const isFOC = focCheck.checked;
                    if (isFOC) {
                        if (givenPaymentInput) { givenPaymentInput.value = '0'; givenPaymentInput.readOnly = true; }
                        if (discountInput) { discountInput.value = '0'; discountInput.readOnly = true; }
                        if (totalPaymentInput) { totalPaymentInput.readOnly = true; }
                    } else {
                        if (givenPaymentInput) { givenPaymentInput.readOnly = false; }
                        if (discountInput) { discountInput.readOnly = false; }
                        if (totalPaymentInput) { totalPaymentInput.readOnly = false; }
                    }
                    if (recalculate) {
                        calculateRegistrationCharges();
                    }
                }
            };

            const onPaymentInput = (el) => {
                const val = parseFloat(el.value) || 0;
                if (val > 0 && focCheck && focCheck.checked) {
                    focCheck.checked = false;
                    togglePaymentRow();
                }
                calculateDue();
            };

            if (totalPaymentInput) {
                totalPaymentInput.addEventListener('input', function () { onPaymentInput(this); });
                totalPaymentInput.addEventListener('keyup', calculateDue);
            }

            if (givenPaymentInput) {
                givenPaymentInput.addEventListener('input', function () { onPaymentInput(this); });
                givenPaymentInput.addEventListener('keyup', calculateDue);
            }

            // Split payments
            const cashInput = document.getElementById('cash_payment');
            const gpayInput = document.getElementById('gpay_payment');
            const chequeInput = document.getElementById('cheque_payment');

            function calculateGivenPayment() {
                const cash = parseFloat(cashInput ? cashInput.value : 0) || 0;
                const gpay = parseFloat(gpayInput ? gpayInput.value : 0) || 0;
                const cheque = parseFloat(chequeInput ? chequeInput.value : 0) || 0;
                if (givenPaymentInput) {
                    givenPaymentInput.value = (cash + gpay + cheque).toFixed(2);
                    onPaymentInput(givenPaymentInput);
                }
            }

            [cashInput, gpayInput, chequeInput].forEach(input => {
                if (input) {
                    input.addEventListener('input', calculateGivenPayment);
                    input.addEventListener('keyup', calculateGivenPayment);
                }
            });

            if (discountInput) {
                discountInput.addEventListener('input', function () { onPaymentInput(this); });
                discountInput.addEventListener('keyup', calculateDue);
            }

            if (focCheck) {
                focCheck.addEventListener('change', function () {
                    togglePaymentRow();
                    if (this.checked) {
                        if (discountInput) discountInput.value = '';
                        if (givenPaymentInput) givenPaymentInput.value = '';
                    }
                    calculateRegistrationCharges();
                });

                // Initial toggle
                togglePaymentRow({{ isset($lead) ? 'false' : 'true' }});
            }

            statusCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    updatePricingAndSelection(this);
                });
            });

            // Initialize
            if (statusInbody?.checked) {
                if (statusDiet) statusDiet.checked = true;
            }
            toggleJoinedCharges();
            @if(!isset($lead))
                calculateRegistrationCharges();
            @else
                calculateDue();
            @endif
            calculateMetrics();

            // Obsolote single dropdown handler removed as it is replaced by repeater logic

            // FOC Container Click Handler
            const focContainer = document.querySelector('.custom-checkbox');
            if (focContainer && focCheck) {
                focContainer.addEventListener('click', function (e) {
                    if (e.target !== focCheck && e.target.tagName !== 'LABEL') {
                        focCheck.checked = !focCheck.checked;
                        focCheck.dispatchEvent(new Event('change'));
                    }
                });
            }

            // Form validation and confirmation
            const inquiryForm = document.getElementById('inquiryForm');

            // Helper: show inline error
            function showFieldError(fieldId, errorId, message) {
                const field = document.getElementById(fieldId);
                const errorDiv = document.getElementById(errorId);
                if (field) {
                    field.classList.remove('is-invalid');
                    field.classList.add('input-invalid');
                }
                if (errorDiv) {
                    errorDiv.textContent = message;
                    errorDiv.style.display = 'block';
                }
            }

            // Helper: clear inline error
            function clearFieldError(fieldId, errorId) {
                const field = document.getElementById(fieldId);
                const errorDiv = document.getElementById(errorId);
                if (field) {
                    field.classList.remove('input-invalid');
                    field.classList.remove('is-invalid');
                }
                if (errorDiv) {
                    errorDiv.textContent = '';
                    errorDiv.style.display = 'none';
                }
            }

            // Live phone validation
            const phoneInput = document.getElementById('phoneInput');
            if (phoneInput) {
                phoneInput.addEventListener('input', function () {
                    const phone = this.value.trim();
                    if (phone && !/^[\d\s\-\+\(\)]{10,15}$/.test(phone.replace(/[\s\-\+\(\)]/g, ''))) {
                        showFieldError('phoneInput', 'phone-error', 'Enter a valid phone number (10-15 digits).');
                    } else {
                        clearFieldError('phoneInput', 'phone-error');
                    }
                });
                phoneInput.addEventListener('blur', function () {
                    const phone = this.value.trim();
                    if (phone && !/^[\d\s\-\+\(\)]{10,15}$/.test(phone.replace(/[\s\-\+\(\)]/g, ''))) {
                        showFieldError('phoneInput', 'phone-error', 'Enter a valid phone number (10-15 digits).');
                    } else {
                        clearFieldError('phoneInput', 'phone-error');
                    }
                });
            }

            // Live email validation
            const emailInput = document.getElementById('emailInput');
            if (emailInput) {
                emailInput.addEventListener('blur', function () {
                    const email = this.value.trim();
                    if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                        showFieldError('emailInput', 'email-error', 'Please enter a valid email address.');
                    } else {
                        clearFieldError('emailInput', 'email-error');
                    }
                });
                emailInput.addEventListener('input', function () {
                    if (this.classList.contains('is-invalid') && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value.trim())) {
                        clearFieldError('emailInput', 'email-error');
                    }
                });
            }

            // Live age validation
            const ageInput = document.getElementById('ageInput');
            if (ageInput) {
                ageInput.addEventListener('input', function () {
                    const age = parseInt(this.value);
                    if (this.value && (age < 0 || age > 150)) {
                        showFieldError('ageInput', 'age-error', 'Please enter a valid age (0-150).');
                    } else {
                        clearFieldError('ageInput', 'age-error');
                    }
                });
            }

            if (inquiryForm) {
                inquiryForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    let hasError = false;

                    // Branch validation (only for new inquiry)
                    const branchSelect = document.getElementById('branch');
                    if (branchSelect && !branchSelect.value) {
                        showFieldError('branch', 'branch-error', 'Please select a branch.');
                        hasError = true;
                    } else if (branchSelect) {
                        clearFieldError('branch', 'branch-error');
                    }

                    // First Name
                    const patientFName = document.getElementById('patientFName');
                    if (patientFName && !patientFName.value.trim()) {
                        showFieldError('patientFName', 'fname-error', 'First name is required.');
                        hasError = true;
                    } else if (patientFName) {
                        clearFieldError('patientFName', 'fname-error');
                    }

                    // Last Name
                    const patientLName = document.getElementById('patientLName');
                    if (patientLName && !patientLName.value.trim()) {
                        showFieldError('patientLName', 'lname-error', 'Last name is required.');
                        hasError = true;
                    } else if (patientLName) {
                        clearFieldError('patientLName', 'lname-error');
                    }

                    // Phone validation (if filled)
                    const phone = phoneInput ? phoneInput.value.trim() : '';
                    if (phone && !/^[\d\s\-\+\(\)]{10,15}$/.test(phone.replace(/[\s\-\+\(\)]/g, ''))) {
                        showFieldError('phoneInput', 'phone-error', 'Enter a valid phone number (10-15 digits).');
                        hasError = true;
                    }

                    // Process Stage validation
                    const isOnline = document.getElementById('source_online')?.checked;
                    const statusChecked = document.querySelectorAll('input[name="user_status[]"]:checked');
                    if (!isOnline && statusChecked.length === 0) {
                        const stageContainer = document.getElementById('processStageContainer');
                        if (stageContainer) {
                            let stageErr = document.getElementById('stage-error');
                            if (!stageErr) {
                                stageErr = document.createElement('div');
                                stageErr.id = 'stage-error';
                                stageErr.style.cssText = 'color:#dc3545;font-size:12px;margin-top:6px;';
                                stageContainer.appendChild(stageErr);
                            }
                            stageErr.textContent = 'Please select at least one process stage.';
                        }
                        hasError = true;
                    } else {
                        const stageErr = document.getElementById('stage-error');
                        if (stageErr) stageErr.textContent = '';
                    }

                    if (hasError) {
                        // Scroll to first error
                        const firstInvalid = inquiryForm.querySelector('.input-invalid, .field-error:not(:empty)');
                        if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return;
                    }

                    // All good — confirm and submit
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you want to save this inquiry?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, save it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            inquiryForm.submit();
                        }
                    });
                });

                // Clear errors on input for required fields
                ['patientFName', 'patientLName'].forEach(function(id) {
                    const el = document.getElementById(id);
                    if (el) el.addEventListener('input', function() {
                        if (this.value.trim()) {
                            const errMap = { patientFName: 'fname-error', patientLName: 'lname-error' };
                            clearFieldError(id, errMap[id]);
                        }
                    });
                });
                const branchEl = document.getElementById('branch');
                if (branchEl) branchEl.addEventListener('change', function() {
                    if (this.value) clearFieldError('branch', 'branch-error');
                });
            }

            // Auto-format date and time for new entries only
            @if(!isset($lead) || !$lead->id)
                const inquiryDateInput = document.querySelector('input[name="inquiry_date"]');
                if (inquiryDateInput && !inquiryDateInput.value) {
                    inquiryDateInput.value = new Date().toISOString().split('T')[0];
                }
                const inquiryTimeInput = document.querySelector('input[name="inquiry_time"]');
                if (inquiryTimeInput && !inquiryTimeInput.value) {
                    const now = new Date();
                    inquiryTimeInput.value = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
                }
            @endif

                }); // end DOMContentLoaded

        // Make calculateMetrics globally available
        window.calculateMetrics = calculateMetrics;

        // Autocomplete logic
        let suggestionsData = {
            complaints: [],
            diagnoses: []
        };

        function loadSuggestions() {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch('{{ route("get.suggestions") }}', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        // Don't crash — just init with empty arrays
                        initAutocomplete();
                        return null;
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data) return;
                    if (data.complaints && data.complaints.length > 0) suggestionsData.complaints = data.complaints;
                    if (data.diagnoses && data.diagnoses.length > 0) suggestionsData.diagnoses = data.diagnoses;
                    initAutocomplete();
                })
                .catch(error => {
                    console.warn('Could not load suggestions, autocomplete will work without preloaded data.');
                    initAutocomplete();
                });
        }

        function initAutocomplete() {
            setupMultiSelect('complain', suggestionsData.complaints);
            setupMultiSelect('diagnosis', suggestionsData.diagnoses);
        }

        function setupMultiSelect(fieldId, suggestions) {
            const input = document.getElementById(fieldId);
            const dropdown = document.getElementById(fieldId + '-dropdown');
            const selectedContainer = document.getElementById(fieldId + '-selected');
            const hiddenInput = document.getElementById(fieldId + '-hidden');

            let selectedItems = [];
            if (hiddenInput && hiddenInput.value) {
                selectedItems = hiddenInput.value.split(',').map(i => i.trim()).filter(i => i);
            }

            let selectedIndex = -1;

            if (!input || !dropdown || !selectedContainer || !hiddenInput) return;

            updateSelectedDisplay();

            input.addEventListener('focus', function () {
                showMultiSelectSuggestions(input, dropdown, suggestions, selectedItems);
            });

            input.addEventListener('input', function () {
                const value = this.value.toLowerCase();
                showMultiSelectSuggestions(input, dropdown, suggestions, selectedItems, value);
            });

            input.addEventListener('keydown', function (e) {
                const items = dropdown.querySelectorAll('.autocomplete-item:not(.no-results)');

                switch (e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                        updateSelection(items, selectedIndex);
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        selectedIndex = Math.max(selectedIndex - 1, -1);
                        updateSelection(items, selectedIndex);
                        break;
                    case 'Enter':
                        e.preventDefault();
                        if (selectedIndex >= 0 && items[selectedIndex]) {
                            addItem(items[selectedIndex].textContent);
                            input.value = '';
                            dropdown.classList.remove('show');
                            selectedIndex = -1;
                        } else if (input.value.trim()) {
                            addItem(input.value.trim());
                            input.value = '';
                            dropdown.classList.remove('show');
                            selectedIndex = -1;
                        }
                        break;
                    case 'Escape':
                        dropdown.classList.remove('show');
                        selectedIndex = -1;
                        break;
                    case 'Backspace':
                        if (input.value === '' && selectedItems.length > 0) {
                            removeItem(selectedItems[selectedItems.length - 1]);
                        }
                        break;
                }
            });

            document.addEventListener('click', function (e) {
                if (!input.contains(e.target) && !dropdown.contains(e.target) && !selectedContainer.contains(e.target)) {
                    dropdown.classList.remove('show');
                    selectedIndex = -1;
                }
            });

            function addItem(itemText) {
                if (!selectedItems.includes(itemText)) {
                    selectedItems.push(itemText);
                    updateSelectedDisplay();
                    updateHiddenInput();

                    if (!suggestions.includes(itemText)) {
                        suggestions.push(itemText);
                        suggestions.sort();
                    }
                }
            }

            function removeItem(itemText) {
                const index = selectedItems.indexOf(itemText);
                if (index > -1) {
                    selectedItems.splice(index, 1);
                    updateSelectedDisplay();
                    updateHiddenInput();
                }
            }

            function updateSelectedDisplay() {
                selectedContainer.innerHTML = '';
                if (selectedItems.length === 0) return;

                selectedItems.forEach(item => {
                    const itemElement = document.createElement('div');
                    itemElement.className = 'selected-item';
                    itemElement.innerHTML = `${item} <button type="button" class="remove-item"><i class="fas fa-times"></i></button>`;

                    const removeBtn = itemElement.querySelector('.remove-item');
                    removeBtn.addEventListener('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        removeItem(item);
                    });

                    selectedContainer.appendChild(itemElement);
                });
            }

            function updateHiddenInput() {
                hiddenInput.value = selectedItems.join(', ');
            }

            input.multiSelect = { addItem, removeItem };
        }

        function showMultiSelectSuggestions(input, dropdown, suggestions, selectedItems, filter = '') {
            dropdown.innerHTML = '';
            let selectedIndex = -1;

            const filteredSuggestions = suggestions.filter(s =>
                s.toLowerCase().includes(filter) && !selectedItems.includes(s)
            );

            if (filteredSuggestions.length === 0 && filter) {
                const addNew = document.createElement('div');
                addNew.className = 'autocomplete-item add-new';
                addNew.innerHTML = `<i class="fas fa-plus"></i> Add "${filter}" as new ${input.id}`;
                addNew.addEventListener('click', function () {
                    saveNewMedicalCondition(filter, input.id === 'complain' ? 'complaint' : 'diagnosis', input);
                });
                dropdown.appendChild(addNew);
            } else {
                filteredSuggestions.forEach(suggestion => {
                    const item = document.createElement('div');
                    item.className = 'autocomplete-item';
                    item.textContent = suggestion;
                    item.addEventListener('click', function () {
                        if (input.multiSelect) input.multiSelect.addItem(suggestion);
                        input.value = '';
                        dropdown.classList.remove('show');
                    });
                    dropdown.appendChild(item);
                });
            }

            if (filteredSuggestions.length > 0 || filter) {
                dropdown.classList.add('show');
            } else {
                dropdown.classList.remove('show');
            }
        }

        function saveNewMedicalCondition(name, type, inputElement) {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch('{{ route("save.medical.condition") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ name: name, type: type })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (inputElement.multiSelect) {
                            inputElement.multiSelect.addItem(name);
                        }
                        const dropdownId = inputElement.id + '-dropdown';
                        document.getElementById(dropdownId)?.classList.remove('show');
                        inputElement.value = '';

                        Swal.fire({
                            title: 'Saved!',
                            text: `New ${type} added successfully.`,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                })
                .catch(error => console.error('Error saving:', error));
        }

        function updateSelection(items, selectedIndex) {
            items.forEach((item, index) => {
                if (index === selectedIndex) item.classList.add('selected');
                else item.classList.remove('selected');
            });
        }

        // Load suggestions on init
        loadSuggestions();
    </script>

    <style>
        /* Custom Status Badges */
        .status-badge {
            padding: 5px 12px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.75rem;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid transparent;
            margin-right: 5px;
        }

        .badge-pending {
            background-color: rgba(217, 119, 6, 0.1);
            color: #f59e0b;
            border-color: rgba(217, 119, 6, 0.2);
        }

        .badge-diet {
            background-color: rgba(8, 145, 178, 0.1);
            color: #22d3ee;
            border-color: rgba(8, 145, 178, 0.2);
        }

        .badge-joined {
            background-color: rgba(22, 163, 74, 0.1);
            color: #4ade80;
            border-color: rgba(22, 163, 74, 0.2);
        }

        .badge-active {
            background-color: rgba(124, 58, 237, 0.1);
            color: #a78bfa;
            border-color: rgba(124, 58, 237, 0.2);
        }

        /* Dark Mode Specific Badge Overrides */
        .dark .badge-pending {
            background-color: rgba(245, 158, 11, 0.15) !important;
            color: #fbbf24 !important;
        }

        .dark .badge-diet {
            background-color: rgba(34, 211, 238, 0.15) !important;
            color: #67e8f9 !important;
        }

        .dark .badge-joined {
            background-color: rgba(74, 222, 128, 0.15) !important;
            color: #86efac !important;
        }

        .dark .badge-active {
            background-color: rgba(167, 139, 250, 0.15) !important;
            color: #c4b5fd !important;
        }

        .card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-subtle);
            box-shadow: var(--shadow-md);
            border-radius: 15px;
            color: var(--text-primary);
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-header {
            background-color: var(--bg-hover);
            border-bottom: 1px solid var(--border-subtle);
            border-radius: 15px 15px 0 0 !important;
        }

        .btn-primary {
            background-color: #086838;
            border-color: #086838;
            color: white;
        }

        .btn-primary:hover {
            background-color: #06502b;
            border-color: #06502b;
            color: white;
        }
    </style>
@endsection