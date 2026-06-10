@extends('admin.layouts.layouts')

@section('title', 'Edit Follow Up')

@section('content')
    <style>
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #dee2e6;
            max-width: 1200px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .form-col {
            flex: 1;
        }

        .form-col-2 {
            flex: 2;
        }

        .form-col-3 {
            flex: 3;
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

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }

        .btn {
            padding: 10px 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: rgb(8, 104, 56);
            color: white;
        }

        .btn-primary:hover {
            background: #067945;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
            border-radius: 4px;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .required::after {
            content: " *";
            color: #e74c3c;
        }

        .section-divider {
            display: flex;
            align-items: center;
            width: 100%;
        }

        .section-divider .title {
            white-space: nowrap;
            font-size: 16px;
            font-weight: 500;
            color: #666;
            margin-right: 10px;
        }

        .section-divider .line {
            flex-grow: 1;
            height: 1px;
            background: #dcdcdc;
        }

        .section-divider .icon-box {
            /* width: 26px;
                                    height: 26px;
                                    border-radius: 5px; */
            display: flex;
            align-items: center;
            justify-content: center;
            /* margin-left: 10px; */
        }

        .section-divider .icon-box i {
            color: #067945;
            font-size: 23px;
        }

        .hidden-field {
            display: none;
        }

        .form-section {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background: #fdfdfd;
        }

        .form-section h3 {
            margin-bottom: 10px;
            font-size: 18px;
            color: #444;
        }

        .medicine-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
            /* padding: 10px;
                                        border: 1px dashed #ddd;
                                        border-radius: 4px;
                                        background: white; */
        }

        .medicine-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 10px;
        }

        .form-col {
            flex: 1;
            min-width: 200px;
            display: flex;
            flex-direction: column;
        }

        .form-col.full-width {
            flex: 1 1 100%;
        }

        /* New styles for inline buttons */
        .field-with-button {
            display: flex;
            align-items: flex-end;
            gap: 8px;
        }

        .field-with-button .form-col {
            flex: 1;
            margin-bottom: 0;
        }

        .inline-btn {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            margin-bottom: 5px;
        }

        .inline-btn.add {
            background-color: #28a745;
            color: white;
        }

        .inline-btn.remove {
            background-color: #dc3545;
            color: white;
        }

        .inline-btn:hover {
            opacity: 0.9;
        }

        .fnf-title {
            font-size: 20px;
        }

        .pro_filed {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 15px;
            width: 100%;
            align-items: flex-start;
        }

        .pro_filed .form {
            position: relative;
            flex: 1;
            min-width: 200px;
        }

        @media (max-width: 768px) {
            .pro_filed {
                flex-direction: column;
                gap: 15px;
            }

            .pro_filed .form {
                width: 100% !important;
                min-width: 100% !important;
                margin-right: 0 !important;
            }

            .form-col {
                min-width: 100% !important;
            }
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

        .select-with-button {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .select-with-button select {
            flex: 1;
        }

        /* Styles for input with button */
        .input-with-button {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .input-with-button input {
            flex: 1;
        }

        .btn-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #28a745;
            color: white;
            border: none;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .btn-circle.small {
            width: 30px;
            height: 30px;
            font-size: 16px;
            margin-left: 8px;
        }

        .btn-circle.danger {
            background-color: #dc3545;
        }

        .btn-circle.danger:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }

        .btn-circle:hover {
            background-color: #218838;
            transform: scale(1.05);
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

        .separate_payment {
            padding: 20px 20px 20px;
            border: 1px solid #8ec038 !important;
            border-radius: 5px;
            background: #f6f6f6;
            margin-top: 20px
        }

        #foc.form-check-input {
            accent-color: green !important;
            /* modern browsers */
            -webkit-appearance: checkbox;
            /* try to keep native look on WebKit */
        }

        /* extra fallback for browsers that ignore accent-color */
        #foc.form-check-input:checked {
            background-color: green !important;

        }
    </style>

    <div class="col-md-12 col-lg-10 m-auto p-0">
        <div class="card rounded shadow mb-5">
            <div class="card-header">
                <h3 class="bold font-up fnf-title text-success">Edit Follow Up</h3>
            </div>
            <div class="row">
                <div class="col-md-12 m-auto">
                    <div class="bg-light rounded-5">
                        <section class="w-100 p-4 pb-4">
                            <div class="">
                                <form
                                    action="{{ route('update.follow.up', ['patient_id' => $patient->patient_id, 'followup_id' => $followup->id]) }}"
                                    method="POST" id="followupForm">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="inquiry_id" value="{{ $patient->id }}">
                                    <input type="hidden" name="patient_id" value="{{ $patient->patient_id }}">
                                    <input type="hidden" id="branch_id" name="branch_id" value="SVC-0005">
                                    <input type="hidden" name="branch" value="SVC">

                                    <div class="section-divider">Personal Information</div>
                                    <div class="pt-4">
                                        <div class="pro_filed">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="patient_name" class="required">Patient Name</label>
                                                    <input type="text" name="patient_name"
                                                        value="{{ old('patient_name', $patient->patient_name) }}" readonly>
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="address" class="required">Address</label>
                                                    <textarea name="address"
                                                        readonly>{{ old('address', $patient->address) }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pro_filed pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="followup_date" class="required">FollowUp Date</label>
                                                    <input type="date" id="followup_date" name="followup_date"
                                                        value="{{ old('followup_date', $followup->followup_date ? \Carbon\Carbon::parse($followup->followup_date)->format('Y-m-d') : '') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="followups_time">FollowUp Time</label>
                                                    <input type="time" id="followups_time" name="followups_time"
                                                        value="{{ old('followups_time', $followup->followups_time ? \Carbon\Carbon::parse($followup->followups_time)->format('H:i') : '') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pro_filed pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="gender" class="required">Gender</label>
                                                    <select name="gender">
                                                        <option value="male" {{ $patient->getMeta('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                                        <option value="female" {{ $patient->getMeta('gender') == 'female' ? 'selected' : '' }}>Female
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="weight">Weight (kg)</label>
                                                    <div class="dynamic-fields-container" id="weight-container">
                                                        @php
                                                            $allWeights = $followupMetaValues['weight'] ?? [];
                                                        @endphp

                                                        @forelse ($allWeights as $index => $weight)
                                                            <div class="dynamic-field-group">
                                                                <input type="number" name="weight[]" class="dynamic-field-input"
                                                                    value="{{ $weight }}" placeholder="Enter weight">
                                                            </div>
                                                        @empty
                                                            <div class="dynamic-field-group">
                                                                <input type="number" name="weight[]" class="dynamic-field-input"
                                                                    value="" placeholder="Enter weight">
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pro_filed pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="phone">Phone Number</label>
                                                    <input type="number" id="phone" name="phone"
                                                        value="{{ $patient->getMeta('phone') }}">
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label class="required">Age</label>
                                                    <div class="d-flex gap-2">
                                                        <div class="flex-grow-1" style="min-width: 0;">
                                                            <input type="number" id="age_years" placeholder="Years" min="0" class="form-control" style="padding: 6px 8px;" oninput="updateAgeString()">
                                                        </div>
                                                        <div class="flex-grow-1" style="min-width: 0;">
                                                            <input type="number" id="age_months" placeholder="Months" min="0" max="11" class="form-control" style="padding: 6px 8px;" oninput="updateAgeString()">
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="age" name="age" value="{{ old('age', $patient->age) }}" required>
                                                </div>
                                            </div>
                                        </div>

                                        @php
                                            $ptStatusValues = $followupMetaValues['pt_status'] ?? [];
                                            $temperatureValues = $followupMetaValues['temperature'] ?? [];
                                            $pulseValues = $followupMetaValues['pulse'] ?? [];
                                            $bloodPressureValues = $followupMetaValues['blood_pressure'] ?? [];
                                            $spo2Values = $followupMetaValues['spo2'] ?? [];
                                            $rbsValues = $followupMetaValues['rbs'] ?? [];
                                            $diagnosisValues = $followupMetaValues['diagnosis'] ?? [];
                                            $cholesterolValues = $followupMetaValues['s_cholesterol'] ?? [];
                                            $triglycerideValues = $followupMetaValues['s_triglyceride'] ?? [];
                                            $hdlValues = $followupMetaValues['hdl'] ?? [];
                                            $ldlValues = $followupMetaValues['ldl'] ?? [];
                                            $vldlValues = $followupMetaValues['vldl'] ?? [];
                                            $nonHdlCValues = $followupMetaValues['non_hdl_c'] ?? [];
                                            $cholHdlRatioValues = $followupMetaValues['chol_hdl_ratio'] ?? [];
                                            $urineValues = $followupMetaValues['urine'] ?? [];
                                            $crpValues = $followupMetaValues['crp'] ?? [];
                                            $st3Values = $followupMetaValues['s_t3'] ?? [];
                                            $st4Values = $followupMetaValues['s_t4'] ?? [];
                                            $stshValues = $followupMetaValues['s_tsh'] ?? [];
                                            $sb12Values = $followupMetaValues['s_b12'] ?? [];
                                            $sd3Values = $followupMetaValues['s_d3'] ?? [];
                                            $esrValues = $followupMetaValues['esr'] ?? [];
                                            $specificTestValues = $followupMetaValues['specific_test'] ?? [];
                                        @endphp

                                        <div class="pro_filed pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="pt_status">PT.Status</label>
                                                    <div class="dynamic-fields-container" id="pt_status-container">
                                                        @forelse ($ptStatusValues as $index => $val)
                                                            <div class="dynamic-field-group">
                                                                <select name="pt_status[]" class="dynamic-field-input">
                                                                    <option value="IPD" {{ $val == 'IPD' ? 'selected' : '' }}>IPD
                                                                    </option>
                                                                    <option value="OPD" {{ $val == 'OPD' ? 'selected' : '' }}>OPD
                                                                    </option>
                                                                    <option value="Home Visit" {{ $val == 'Home Visit' ? 'selected' : '' }}>Home
                                                                        Visit
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        @empty
                                                            <div class="dynamic-field-group">
                                                                <select name="pt_status[]" class="dynamic-field-input">
                                                                    <option value="">Select Status</option>
                                                                    <option value="IPD">IPD</option>
                                                                    <option value="OPD">OPD</option>
                                                                    <option value="Home Visit">Home Visit</option>
                                                                </select>
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="temperature">Temperature (°C)</label>
                                                    <div class="dynamic-fields-container" id="temperature-container">
                                                        @forelse ($temperatureValues as $index => $val)
                                                            <div class="dynamic-field-group">
                                                                <input type="number" name="temperature[]"
                                                                    class="dynamic-field-input"
                                                                    value="{{ $val === 'null' ? '' : $val }}"
                                                                    placeholder="Enter temperature">
                                                            </div>
                                                        @empty
                                                            <div class="dynamic-field-group">
                                                                <input type="number" name="temperature[]"
                                                                    class="dynamic-field-input" value=""
                                                                    placeholder="Enter temperature">
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pro_filed pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="pulse">Pulse</label>
                                                    <div class="dynamic-fields-container" id="pulse-container">
                                                        @forelse ($pulseValues as $index => $val)
                                                            <div class="dynamic-field-group">
                                                                <input type="text" name="pulse[]" class="dynamic-field-input"
                                                                    value="{{ $val === 'null' ? '' : $val }}"
                                                                    placeholder="Enter pulse rate">
                                                            </div>
                                                        @empty
                                                            <div class="dynamic-field-group">
                                                                <input type="text" name="pulse[]" class="dynamic-field-input"
                                                                    value="" placeholder="Enter pulse rate">
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="blood_pressure">Blood Pressure</label>
                                                    <div class="dynamic-fields-container" id="blood-pressure-container">
                                                        @forelse ($bloodPressureValues as $index => $val)
                                                            <div class="dynamic-field-group">
                                                                <input type="text" name="blood_pressure[]"
                                                                    class="dynamic-field-input"
                                                                    value="{{ $val === 'null' ? '' : $val }}"
                                                                    placeholder="e.g., 120/80">
                                                            </div>
                                                        @empty
                                                            <div class="dynamic-field-group">
                                                                <input type="text" name="blood_pressure[]"
                                                                    class="dynamic-field-input" value=""
                                                                    placeholder="e.g., 120/80">
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pro_filed pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="spo2">SpO2 (%)</label>
                                                    <div class="dynamic-fields-container" id="spo2-container">
                                                        @forelse ($spo2Values as $index => $val)
                                                            <div class="dynamic-field-group">
                                                                <input type="number" name="spo2[]" class="dynamic-field-input"
                                                                    value="{{ $val === 'null' ? '' : $val }}"
                                                                    placeholder="Enter SpO2">
                                                            </div>
                                                        @empty
                                                            <div class="dynamic-field-group">
                                                                <input type="number" name="spo2[]" class="dynamic-field-input"
                                                                    value="" placeholder="Enter SpO2">
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="rbs">RBS</label>
                                                    <div class="dynamic-fields-container" id="rbs-container">
                                                        @forelse ($rbsValues as $index => $val)
                                                            <div class="dynamic-field-group">
                                                                <input type="text" name="rbs[]" class="dynamic-field-input"
                                                                    value="{{ $val === 'null' ? '' : $val }}"
                                                                    placeholder="Enter RBS">
                                                            </div>
                                                        @empty
                                                            <div class="dynamic-field-group">
                                                                <input type="text" name="rbs[]" class="dynamic-field-input"
                                                                    value="" placeholder="Enter RBS">
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pro_filed pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="diagnosis">Diagnosis</label>
                                                    <div class="dynamic-fields-container" id="diagnosis-container">
                                                        @forelse ($diagnosisValues as $index => $val)
                                                            <div class="dynamic-field-group">
                                                                <textarea name="diagnosis[]" class="dynamic-field-input"
                                                                    placeholder="Enter diagnosis">{{ $val === 'null' ? '' : $val }}</textarea>
                                                            </div>
                                                        @empty
                                                            <div class="dynamic-field-group">
                                                                <textarea name="diagnosis[]" class="dynamic-field-input"
                                                                    placeholder="Enter diagnosis"></textarea>
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="pro_filed pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="investigation">Investigation</label>
                                                    <textarea id="investigation"
                                                        name="investigation">{{ $followupMetaValues['investigation'][0] ?? $patient->getMeta('investigation') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="past_history">Past History</label>
                                                    <textarea id="past_history"
                                                        name="past_history">{{ $followupMetaValues['past_history'][0] ?? $patient->getMeta('past_history') }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pro_filed pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="family_history">Family History</label>
                                                    <textarea id="family_history"
                                                        name="family_history">{{ $followupMetaValues['family_history'][0] ?? $patient->getMeta('family_history') }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="section-divider mt-4">
                                            <div class="title">Lipid Profile</div>
                                            <div class="line"></div>
                                            <div class="icon-box" onclick="toggleSection(this, 'lipid-profile-section')">
                                                <i class="bi bi-dash-lg" id="lipid-toggle-icon"></i>
                                            </div>
                                        </div>
                                        <div class="lipid-profile-section" id="lipid-profile-section">
                                            <!-- Row 1: S. Cholesterol, S. Triglyceride -->
                                            <div class="pro_filed pt-3">
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="s_cholesterol">S. Cholesterol</label>
                                                        <div class="dynamic-fields-container" id="cholesterol-container">
                                                            @forelse ($cholesterolValues as $index => $val)
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="s_cholesterol[]"
                                                                        class="dynamic-field-input"
                                                                        value="{{ $val === 'null' ? '' : $val }}"
                                                                        placeholder="S. Cholesterol">
                                                                </div>
                                                            @empty
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="s_cholesterol[]"
                                                                        class="dynamic-field-input" value=""
                                                                        placeholder="S. Cholesterol">
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="s_triglyceride">S. Triglyceride</label>
                                                        <div class="dynamic-fields-container" id="triglyceride-container">
                                                            @forelse ($triglycerideValues as $index => $val)
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="s_triglyceride[]"
                                                                        class="dynamic-field-input"
                                                                        value="{{ $val === 'null' ? '' : $val }}"
                                                                        placeholder="S. Triglyceride">
                                                                </div>
                                                            @empty
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="s_triglyceride[]"
                                                                        class="dynamic-field-input" value=""
                                                                        placeholder="S. Triglyceride">
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Row 2: HDL, LDL -->
                                            <div class="pro_filed pt-3">
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="hdl">HDL</label>
                                                        <div class="dynamic-fields-container" id="hdl-container">
                                                            @forelse ($hdlValues as $index => $val)
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="hdl[]" class="dynamic-field-input"
                                                                        value="{{ $val === 'null' ? '' : $val }}"
                                                                        placeholder="HDL">
                                                                </div>
                                                            @empty
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="hdl[]" class="dynamic-field-input"
                                                                        value="" placeholder="HDL">
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="ldl">LDL</label>
                                                        <div class="dynamic-fields-container" id="ldl-container">
                                                            @forelse ($ldlValues as $index => $val)
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="ldl[]" class="dynamic-field-input"
                                                                        value="{{ $val === 'null' ? '' : $val }}"
                                                                        placeholder="LDL">
                                                                </div>
                                                            @empty
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="ldl[]" class="dynamic-field-input"
                                                                        value="" placeholder="LDL">
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Row 3: VLDL, Non-HDL C -->
                                            <div class="pro_filed pt-3">
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="vldl">VLDL</label>
                                                        <div class="dynamic-fields-container" id="vldl-container">
                                                            @forelse ($vldlValues as $index => $val)
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="vldl[]" class="dynamic-field-input"
                                                                        value="{{ $val === 'null' ? '' : $val }}"
                                                                        placeholder="VLDL">
                                                                </div>
                                                            @empty
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="vldl[]" class="dynamic-field-input"
                                                                        value="" placeholder="VLDL">
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="non_hdl_c">Non-HDL C</label>
                                                        <div class="dynamic-fields-container" id="non-hdl-c-container">
                                                            @forelse ($nonHdlCValues as $index => $val)
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="non_hdl_c[]"
                                                                        class="dynamic-field-input"
                                                                        value="{{ $val === 'null' ? '' : $val }}"
                                                                        placeholder="Non-HDL C">
                                                                </div>
                                                            @empty
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="non_hdl_c[]"
                                                                        class="dynamic-field-input" value=""
                                                                        placeholder="Non-HDL C">
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Row 4: Chol/HDL ratio -->
                                            <div class="pro_filed pt-3">
                                                <div class="form">
                                                    <div class="form-col-2">
                                                        <label for="chol_hdl_ratio">Chol/HDL ratio</label>
                                                        <div class="dynamic-fields-container" id="chol-hdl-ratio-container">
                                                            @forelse ($cholHdlRatioValues as $index => $val)
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="chol_hdl_ratio[]"
                                                                        class="dynamic-field-input"
                                                                        value="{{ $val === 'null' ? '' : $val }}"
                                                                        placeholder="Chol/HDL ratio">
                                                                </div>
                                                            @empty
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="chol_hdl_ratio[]"
                                                                        class="dynamic-field-input" value=""
                                                                        placeholder="Chol/HDL ratio">
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="section-divider mt-4">
                                            <div class="title">Laboratory Tests</div>
                                            <div class="line"></div>
                                            <div class="icon-box" onclick="toggleSection(this, 'lab-tests-section')">
                                                <i class="bi bi-dash-lg" id="lab-tests-toggle-icon"></i>
                                            </div>
                                        </div>

                                        <div class="lab-tests-section">
                                            @php
                                                $hbValues = $followupMetaValues['hb'] ?? [];
                                                $tcValues = $followupMetaValues['tc'] ?? [];
                                                $pcValues = $followupMetaValues['pc'] ?? [];
                                                $mpValues = $followupMetaValues['mp'] ?? [];
                                                $hb1acValues = $followupMetaValues['hb1ac'] ?? [];
                                                $fbsValues = $followupMetaValues['fbs'] ?? [];
                                                $pp2bsValues = $followupMetaValues['pp2bs'] ?? [];
                                                $sWidalValues = $followupMetaValues['s_widal'] ?? [];
                                                $usgValues = $followupMetaValues['usg'] ?? [];
                                                $xrayValues = $followupMetaValues['x_ray'] ?? [];
                                                $sgptValues = $followupMetaValues['sgpt'] ?? [];
                                                $sCreatinineValues = $followupMetaValues['s_creatinine'] ?? [];
                                                $ns1agValues = $followupMetaValues['ns1ag'] ?? [];
                                                $dengueIgmValues = $followupMetaValues['dengue_igm'] ?? [];
                                                $cholesterolValues = $followupMetaValues['s_cholesterol'] ?? [];
                                                $triglycerideValues = $followupMetaValues['s_triglyceride'] ?? [];
                                                $hdlValues = $followupMetaValues['hdl'] ?? [];
                                                $ldlValues = $followupMetaValues['ldl'] ?? [];
                                                $vldlValues = $followupMetaValues['vldl'] ?? [];
                                                $nonHdlCValues = $followupMetaValues['non_hdl_c'] ?? [];
                                                $cholHdlRatioValues = $followupMetaValues['chol_hdl_ratio'] ?? [];
                                                $sb12Values = $followupMetaValues['s_b12'] ?? [];
                                                $sd3Values = $followupMetaValues['s_d3'] ?? [];
                                                $urineValues = $followupMetaValues['urine'] ?? [];
                                                $crpValues = $followupMetaValues['crp'] ?? [];
                                                $st3Values = $followupMetaValues['s_t3'] ?? [];
                                                $st4Values = $followupMetaValues['s_t4'] ?? [];
                                                $stshValues = $followupMetaValues['s_tsh'] ?? [];
                                                $esrValues = $followupMetaValues['esr'] ?? [];
                                                $specificTestValues = $followupMetaValues['specific_test'] ?? [];
                                            @endphp

                                            <!-- Row 10: Urine, CRP -->
                                            <div class="pro_filed pt-3">
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="urine">Urine</label>
                                                        <div class="dynamic-fields-container" id="urine-container">
                                                            @forelse ($urineValues as $index => $val)
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="urine[]"
                                                                        class="dynamic-field-input"
                                                                        value="{{ $val === 'null' ? '' : $val }}"
                                                                        placeholder="Urine">
                                                                </div>
                                                            @empty
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="urine[]"
                                                                        class="dynamic-field-input" value=""
                                                                        placeholder="Urine">
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="crp">CRP</label>
                                                        <div class="dynamic-fields-container" id="crp-container">
                                                            @forelse ($crpValues as $index => $val)
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="crp[]" class="dynamic-field-input"
                                                                        value="{{ $val === 'null' ? '' : $val }}"
                                                                        placeholder="CRP">
                                                                </div>
                                                            @empty
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="crp[]" class="dynamic-field-input"
                                                                        value="" placeholder="CRP">
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="pro_filed pt-3">
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="s_t3">S.T3</label>
                                                        <div class="dynamic-fields-container" id="st3-container">
                                                            @forelse ($st3Values as $index => $val)
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="s_t3[]" class="dynamic-field-input"
                                                                        value="{{ $val === 'null' ? '' : $val }}"
                                                                        placeholder="S.T3">
                                                                </div>
                                                            @empty
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="s_t3[]" class="dynamic-field-input"
                                                                        value="" placeholder="S.T3">
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="s_t4">S.T4</label>
                                                        <div class="dynamic-fields-container" id="st4-container">
                                                            @forelse ($st4Values as $index => $val)
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="s_t4[]" class="dynamic-field-input"
                                                                        value="{{ $val === 'null' ? '' : $val }}"
                                                                        placeholder="S.T4">
                                                                </div>
                                                            @empty
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="s_t4[]" class="dynamic-field-input"
                                                                        value="" placeholder="S.T4">
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="pro_filed pt-3">
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="s_tsh">S.TSH</label>
                                                        <div class="dynamic-fields-container" id="stsh-container">
                                                            @forelse ($stshValues as $index => $val)
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="s_tsh[]"
                                                                        class="dynamic-field-input"
                                                                        value="{{ $val === 'null' ? '' : $val }}"
                                                                        placeholder="S.TSH">
                                                                </div>
                                                            @empty
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="s_tsh[]"
                                                                        class="dynamic-field-input" value=""
                                                                        placeholder="S.TSH">
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="s_b12">S.B12</label>
                                                        <div class="dynamic-fields-container" id="sb12-container">
                                                            @forelse ($sb12Values as $index => $val)
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="s_b12[]"
                                                                        class="dynamic-field-input"
                                                                        value="{{ $val === 'null' ? '' : $val }}"
                                                                        placeholder="S.B12">
                                                                </div>
                                                            @empty
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="s_b12[]"
                                                                        class="dynamic-field-input" value=""
                                                                        placeholder="S.B12">
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="pro_filed pt-3">
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="s_d3">S.D3</label>
                                                        <div class="dynamic-fields-container" id="sd3-container">
                                                            @forelse ($sd3Values as $index => $val)
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="s_d3[]" class="dynamic-field-input"
                                                                        value="{{ $val === 'null' ? '' : $val }}"
                                                                        placeholder="S.D3">
                                                                </div>
                                                            @empty
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="s_d3[]" class="dynamic-field-input"
                                                                        value="" placeholder="S.D3">
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="esr">ESR</label>
                                                        <div class="dynamic-fields-container" id="esr-container">
                                                            @forelse ($esrValues as $index => $val)
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="esr[]" class="dynamic-field-input"
                                                                        value="{{ $val === 'null' ? '' : $val }}"
                                                                        placeholder="ESR">
                                                                </div>
                                                            @empty
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="esr[]" class="dynamic-field-input"
                                                                        value="" placeholder="ESR">
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="pro_filed pt-3">
                                                <div class="form">
                                                    <div class="form-col-2">
                                                        <label for="specific_test">Any specific Test</label>
                                                        <div class="dynamic-fields-container" id="specific-test-container">
                                                            @forelse ($specificTestValues as $index => $val)
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="specific_test[]"
                                                                        class="dynamic-field-input"
                                                                        value="{{ $val === 'null' ? '' : $val }}"
                                                                        placeholder="Any specific Test">
                                                                </div>
                                                            @empty
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="specific_test[]"
                                                                        class="dynamic-field-input" value=""
                                                                        placeholder="Any specific Test">
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Inside Treatment Section -->
                                        <div class="pro_filed pt-3">
                                            <div class="form">
                                                <div class="section-divider mt-4">
                                                    <div class="title">Inside Treatment</div>
                                                    <div class="line"></div>
                                                    <!-- Remove the second parameter from onclick -->
                                                    <div class="icon-box" onclick="insideSection(this)">
                                                        <i class="bi bi-dash-lg" id="inside-toggle-icon"></i>
                                                    </div>
                                                </div>

                                                <div id="inside-treatment-section">
                                                    <div id="inside-treatment-container">
                                                        @php
                                                            $insideTreatments = $treatments['inside'] ?? [];
                                                        @endphp
                                                        @forelse ($insideTreatments as $index => $treatment)
                                                            <div class="pro_filed pt-3">
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>Medicine</label>
                                                                        <input type="text" name="inside_medicine[]"
                                                                            value="{{ $treatment['medicine'] ?? '' }}">
                                                                    </div>
                                                                </div>
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>Dose</label>
                                                                        <select name="inside_dose[]">
                                                                            <option value="">Select Dose</option>
                                                                            <option value="1 – 0 – 0" {{ ($treatment['dose'] ?? '') == '1 – 0 – 0' ? 'selected' : '' }}>
                                                                                1 – 0 – 0</option>
                                                                            <option value="0 – 0 – 1" {{ ($treatment['dose'] ?? '') == '0 – 0 – 1' ? 'selected' : '' }}>
                                                                                0 – 0 – 1</option>
                                                                            <option value="1 – 0 – 1" {{ ($treatment['dose'] ?? '') == '1 – 0 – 1' ? 'selected' : '' }}>
                                                                                1 – 0 – 1</option>
                                                                            <option value="1 – 1 – 0" {{ ($treatment['dose'] ?? '') == '1 – 1 – 0' ? 'selected' : '' }}>
                                                                                1 – 1 – 0</option>
                                                                            <option value="0 – 1 – 0" {{ ($treatment['dose'] ?? '') == '0 – 1 – 0' ? 'selected' : '' }}>
                                                                                0 – 1 – 0</option>
                                                                            <option value="0 – 1 – 1" {{ ($treatment['dose'] ?? '') == '0 – 1 – 1' ? 'selected' : '' }}>
                                                                                0 – 1 – 1</option>
                                                                            <option value="1/2 – 0 – 1/2" {{ ($treatment['dose'] ?? '') == '1/2 – 0 – 1/2' ? 'selected' : '' }}>
                                                                                1/2 – 0 – 1/2</option>
                                                                            <option value="1/2 – 1/2 – 1/2" {{ ($treatment['dose'] ?? '') == '1/2 – 1/2 – 1/2' ? 'selected' : '' }}>
                                                                                1/2 – 1/2 – 1/2</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>When</label>
                                                                        <div class="select-with-button">
                                                                            <select name="inside_timing[]">
                                                                                <option value="">Select Timing
                                                                                </option>
                                                                                <option value="Before Food" {{ ($treatment['timing'] ?? '') == 'Before Food' ? 'selected' : '' }}>
                                                                                    Before Food</option>
                                                                                <option value="After Food" {{ ($treatment['timing'] ?? '') == 'After Food' ? 'selected' : '' }}>
                                                                                    After Food</option>
                                                                            </select>
                                                                            @if ($index === 0)
                                                                                <button type="button" class="btn-circle small"
                                                                                    onclick="addInsideRow()">+</button>
                                                                            @else
                                                                                <button type="button"
                                                                                    class="btn-circle small remove"
                                                                                    onclick="this.closest('.pro_filed').remove()">-</button>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="pro_filed pt-3">
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>Medicine</label>
                                                                        <input type="text" name="inside_medicine[]">
                                                                    </div>
                                                                </div>
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>Dose</label>
                                                                        <select name="inside_dose[]">
                                                                            <option value="">Select Dose</option>
                                                                            <option>1 – 0 – 0</option>
                                                                            <option>0 – 0 – 1</option>
                                                                            <option>1 – 0 – 1</option>
                                                                            <option>1 – 1 – 0</option>
                                                                            <option>0 – 1 – 0</option>
                                                                            <option>0 – 1 – 1</option>
                                                                            <option>1/2 – 0 – 1/2</option>
                                                                            <option>1/2 – 1/2 – 1/2</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>When</label>
                                                                        <div class="select-with-button">
                                                                            <select name="inside_timing[]">
                                                                                <option value="">Select Timing
                                                                                </option>
                                                                                <option>Before Food</option>
                                                                                <option>After Food</option>
                                                                            </select>
                                                                            <button type="button" class="btn-circle small"
                                                                                onclick="addInsideRow()">+</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Homeopathic Treatment Section -->
                                            <div class="form">
                                                <div class="section-divider mt-4">
                                                    <div class="title">Homeopathic Treatment</div>
                                                    <div class="line"></div>
                                                    <!-- Remove the second parameter from onclick -->
                                                    <div class="icon-box" onclick="toggleHomeopathicSection(this)">
                                                        <i class="bi bi-dash-lg" id="homeo-treatment-toggle-icon"></i>
                                                    </div>
                                                </div>

                                                <div id="homeo-treatment-section">
                                                    <div id="homeo-treatment-container">
                                                        @php
                                                            $homeoTreatments = $treatments['homeo'] ?? [];
                                                        @endphp
                                                        @forelse ($homeoTreatments as $index => $treatment)
                                                            <div class="pro_filed pt-3">
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>Medicine</label>
                                                                        <input type="text" name="homeo_medicine[]"
                                                                            value="{{ $treatment['medicine'] ?? '' }}">
                                                                    </div>
                                                                </div>
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>When</label>
                                                                        <div class="select-with-button">
                                                                            <select name="homeo_timing[]">
                                                                                <option value="">Select Timing
                                                                                </option>
                                                                                <option value="Before Food" {{ ($treatment['timing'] ?? '') == 'Before Food' ? 'selected' : '' }}>
                                                                                    Before Food</option>
                                                                                <option value="After Food" {{ ($treatment['timing'] ?? '') == 'After Food' ? 'selected' : '' }}>
                                                                                    After Food</option>
                                                                            </select>
                                                                            @if ($index === 0)
                                                                                <button type="button" class="btn-circle small"
                                                                                    onclick="addHomeoRow()">+</button>
                                                                            @else
                                                                                <button type="button"
                                                                                    class="btn-circle small remove"
                                                                                    onclick="this.closest('.pro_filed').remove()">-</button>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="pro_filed pt-3">
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>Medicine</label>
                                                                        <input type="text" name="homeo_medicine[]">
                                                                    </div>
                                                                </div>
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>When</label>
                                                                        <div class="select-with-button">
                                                                            <select name="homeo_timing[]">
                                                                                <option value="">Select Timing
                                                                                </option>
                                                                                <option>Before Food</option>
                                                                                <option>After Food</option>
                                                                            </select>
                                                                            <button type="button" class="btn-circle small"
                                                                                onclick="addHomeoRow()">+</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Prescription and Indoor Treatment Section -->
                                        <div class="pro_filed pt-3">
                                            <div class="form">
                                                <div class="section-divider mt-4">
                                                    <div class="title">Prescription</div>
                                                    <div class="line"></div>
                                                    <div class="icon-box"
                                                        onclick="togglePrescriptionSection(this, 'prescription-section')">
                                                        <i class="bi bi-dash-lg" id="prescription-toggle-icon"></i>
                                                    </div>
                                                </div>

                                                <div id="prescription-section">
                                                    <div id="prescription-container">
                                                        @php
                                                            $prescriptionTreatments = $treatments['prescription'] ?? [];
                                                        @endphp
                                                        @forelse ($prescriptionTreatments as $index => $treatment)
                                                            <div class="pro_filed pt-3">
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>Medicine</label>
                                                                        <input type="text" name="prescription_medicine[]"
                                                                            value="{{ $treatment['medicine'] ?? '' }}">
                                                                    </div>
                                                                </div>
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>Dose</label>
                                                                        <select name="prescription_dose[]">
                                                                            <option value="">Select Dose</option>
                                                                            <option value="1 – 0 – 0" {{ ($treatment['dose'] ?? '') == '1 – 0 – 0' ? 'selected' : '' }}>
                                                                                1 – 0 – 0</option>
                                                                            <option value="0 – 0 – 1" {{ ($treatment['dose'] ?? '') == '0 – 0 – 1' ? 'selected' : '' }}>
                                                                                0 – 0 – 1</option>
                                                                            <option value="1 – 0 – 1" {{ ($treatment['dose'] ?? '') == '1 – 0 – 1' ? 'selected' : '' }}>
                                                                                1 – 0 – 1</option>
                                                                            <option value="1 – 1 – 0" {{ ($treatment['dose'] ?? '') == '1 – 1 – 0' ? 'selected' : '' }}>
                                                                                1 – 1 – 0</option>
                                                                            <option value="0 – 1 – 0" {{ ($treatment['dose'] ?? '') == '0 – 1 – 0' ? 'selected' : '' }}>
                                                                                0 – 1 – 0</option>
                                                                            <option value="0 – 1 – 1" {{ ($treatment['dose'] ?? '') == '0 – 1 – 1' ? 'selected' : '' }}>
                                                                                0 – 1 – 1</option>
                                                                            <option value="1/2 – 0 – 1/2" {{ ($treatment['dose'] ?? '') == '1/2 – 0 – 1/2' ? 'selected' : '' }}>
                                                                                1/2 – 0 – 1/2</option>
                                                                            <option value="1/2 – 1/2 – 1/2" {{ ($treatment['dose'] ?? '') == '1/2 – 1/2 – 1/2' ? 'selected' : '' }}>
                                                                                1/2 – 1/2 – 1/2</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>When</label>
                                                                        <div class="select-with-button">
                                                                            <select name="prescription_timing[]">
                                                                                <option value="">Select Timing
                                                                                </option>
                                                                                <option value="Before Food" {{ ($treatment['timing'] ?? '') == 'Before Food' ? 'selected' : '' }}>
                                                                                    Before Food</option>
                                                                                <option value="After Food" {{ ($treatment['timing'] ?? '') == 'After Food' ? 'selected' : '' }}>
                                                                                    After Food</option>
                                                                            </select>
                                                                            @if ($index === 0)
                                                                                <button type="button" class="btn-circle small"
                                                                                    onclick="addPrescriptionRow()">+</button>
                                                                            @else
                                                                                <button type="button"
                                                                                    class="btn-circle small remove"
                                                                                    onclick="this.closest('.pro_filed').remove()">-</button>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="pro_filed pt-3">
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>Medicine</label>
                                                                        <input type="text" name="prescription_medicine[]">
                                                                    </div>
                                                                </div>
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>Dose</label>
                                                                        <select name="prescription_dose[]">
                                                                            <option value="">Select Dose</option>
                                                                            <option>1 – 0 – 0</option>
                                                                            <option>0 – 0 – 1</option>
                                                                            <option>1 – 0 – 1</option>
                                                                            <option>1 – 1 – 0</option>
                                                                            <option>0 – 1 – 0</option>
                                                                            <option>0 – 1 – 1</option>
                                                                            <option>1/2 – 0 – 1/2</option>
                                                                            <option>1/2 – 1/2 – 1/2</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>When</label>
                                                                        <div class="select-with-button">
                                                                            <select name="prescription_timing[]">
                                                                                <option value="">Select Timing
                                                                                </option>
                                                                                <option>Before Food</option>
                                                                                <option>After Food</option>
                                                                            </select>
                                                                            <button type="button" class="btn-circle small"
                                                                                onclick="addPrescriptionRow()">+</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Indoor Treatment Section -->
                                            <div class="form">
                                                <div class="section-divider mt-4">
                                                    <div class="title">Indoor Treatment</div>
                                                    <div class="line"></div>
                                                    <div class="icon-box"
                                                        onclick="toggleIndoorSection(this, 'indoor-treatment-section')">
                                                        <i class="bi bi-dash-lg" id="indoor-treatment-toggle-icon"></i>
                                                    </div>
                                                </div>

                                                <div id="indoor-treatment-section">
                                                    <div id="indoor-treatment-container">
                                                        @php
                                                            $indoorTreatments = $treatments['indoor'] ?? [];
                                                        @endphp
                                                        @forelse ($indoorTreatments as $index => $treatment)
                                                            <div class="pro_filed pt-3">
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>Medicine</label>
                                                                        <input type="text" name="indoor_medicine[]"
                                                                            value="{{ $treatment['medicine'] ?? '' }}">
                                                                    </div>
                                                                </div>
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>Dose</label>
                                                                        <select name="indoor_dose[]">
                                                                            <option value="">Select Dose</option>
                                                                            <option value="1 – 0 – 0" {{ ($treatment['dose'] ?? '') == '1 – 0 – 0' ? 'selected' : '' }}>
                                                                                1 – 0 – 0</option>
                                                                            <option value="0 – 0 – 1" {{ ($treatment['dose'] ?? '') == '0 – 0 – 1' ? 'selected' : '' }}>
                                                                                0 – 0 – 1</option>
                                                                            <option value="1 – 0 – 1" {{ ($treatment['dose'] ?? '') == '1 – 0 – 1' ? 'selected' : '' }}>
                                                                                1 – 0 – 1</option>
                                                                            <option value="1 – 1 – 0" {{ ($treatment['dose'] ?? '') == '1 – 1 – 0' ? 'selected' : '' }}>
                                                                                1 – 1 – 0</option>
                                                                            <option value="0 – 1 – 0" {{ ($treatment['dose'] ?? '') == '0 – 1 – 0' ? 'selected' : '' }}>
                                                                                0 – 1 – 0</option>
                                                                            <option value="0 – 1 – 1" {{ ($treatment['dose'] ?? '') == '0 – 1 – 1' ? 'selected' : '' }}>
                                                                                0 – 1 – 1</option>
                                                                            <option value="1/2 – 0 – 1/2" {{ ($treatment['dose'] ?? '') == '1/2 – 0 – 1/2' ? 'selected' : '' }}>
                                                                                1/2 – 0 – 1/2</option>
                                                                            <option value="1/2 – 1/2 – 1/2" {{ ($treatment['dose'] ?? '') == '1/2 – 1/2 – 1/2' ? 'selected' : '' }}>
                                                                                1/2 – 1/2 – 1/2</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>Note</label>
                                                                        <div class="input-with-button">
                                                                            <input type="text" name="indoor_note[]"
                                                                                value="{{ $treatment['note'] ?? '' }}">
                                                                            @if ($index === 0)
                                                                                <button type="button" class="btn-circle small"
                                                                                    onclick="addIndoorRow()">+</button>
                                                                            @else
                                                                                <button type="button"
                                                                                    class="btn-circle small remove"
                                                                                    onclick="this.closest('.pro_filed').remove()">-</button>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="pro_filed pt-3">
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>Medicine</label>
                                                                        <input type="text" name="indoor_medicine[]">
                                                                    </div>
                                                                </div>
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>Dose</label>
                                                                        <select name="indoor_dose[]">
                                                                            <option value="">Select Dose</option>
                                                                            <option>1 – 0 – 0</option>
                                                                            <option>0 – 0 – 1</option>
                                                                            <option>1 – 0 – 1</option>
                                                                            <option>1 – 1 – 0</option>
                                                                            <option>0 – 1 – 0</option>
                                                                            <option>0 – 1 – 1</option>
                                                                            <option>1/2 – 0 – 1/2</option>
                                                                            <option>1/2 – 1/2 – 1/2</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>Note</label>
                                                                        <div class="input-with-button">
                                                                            <input type="text" name="indoor_note[]">
                                                                            <button type="button" class="btn-circle small"
                                                                                onclick="addIndoorRow()">+</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Other Treatment Section -->
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="section-divider mt-4">
                                                    <div class="title">Other Treatment</div>
                                                    <div class="line"></div>
                                                    <div class="icon-box"
                                                        onclick="toggleOtherSection(this, 'other-treatment-section')">
                                                        <i class="bi bi-dash-lg" id="other-treatment-toggle-icon"></i>
                                                    </div>
                                                </div>

                                                <div id="other-treatment-section">
                                                    <div id="other-treatment-container">
                                                        @php
                                                            $otherTreatments = $treatments['other'] ?? [];
                                                        @endphp
                                                        @forelse ($otherTreatments as $index => $treatment)
                                                            <div class="pro_filed pt-3">
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>Medicine</label>
                                                                        <div class="input-with-button">
                                                                            <input type="text" name="other_medicine[]"
                                                                                value="{{ $treatment['medicine'] ?? '' }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>Note</label>
                                                                        <div class="input-with-button">
                                                                            <input type="text" name="other_note[]"
                                                                                value="{{ $treatment['note'] ?? '' }}">
                                                                            @if ($index === 0)
                                                                                <button type="button" class="btn-circle small"
                                                                                    onclick="addOtherRow()">+</button>
                                                                            @else
                                                                                <button type="button"
                                                                                    class="btn-circle small remove"
                                                                                    onclick="this.closest('.pro_filed').remove()">-</button>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="pro_filed pt-3">
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>Medicine</label>
                                                                        <div class="input-with-button">
                                                                            <input type="text" name="other_medicine[]">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form">
                                                                    <div class="form-col">
                                                                        <label>Note</label>
                                                                        <div class="input-with-button">
                                                                            <input type="text" name="other_note[]">
                                                                            <button type="button" class="btn-circle small"
                                                                                onclick="addOtherRow()">+</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pro_filed pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="reference_by">Reference by</label>
                                                    <input type="text" id="reference_by" name="reference_by"
                                                        placeholder="Reference by"
                                                        value="{{ $followupMetaValues['reference_by'][0] ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="referto">Refer to</label>
                                                    <input type="text" id="referto" name="referto" placeholder="Refer to"
                                                        value="{{ $followupMetaValues['referto'][0] ?? '' }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pro_filed pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="notes">Notes</label>
                                                    <input type="text" id="notes" name="notes" placeholder="Notes"
                                                        value="{{ $followupMetaValues['notes'][0] ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center bg-light pt-3">
                                            <input type="checkbox" name="foc" id="foc" class="form-check-input me-3" {{ $followupMetaValues['foc'][0] ?? ($followup->foc ?? $patient->getMeta('foc')) ? 'checked' : '' }}>

                                            <label for="foc" class="mb-0 fw-semibold">
                                                FOC (No payment is collected from patient)
                                            </label>
                                        </div>
                                        <div id="payment_section">
                                            <div class="pro_filed pt-3">
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="total_payment">Total Payment</label>
                                                        <input type="number" id="total_payment" name="total_payment"
                                                            placeholder="Total amount" step="0.01"
                                                            value="{{ $followupMetaValues['total_payment'][0] ?? ($patient->getMeta('total_payment') ?? '') }}">
                                                    </div>
                                                </div>
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="discount_payment">Discount</label>
                                                        <input type="number" id="discount_payment" name="discount_payment"
                                                            placeholder="Discount amount" step="0.01"
                                                            value="{{ $followupMetaValues['discount_payment'][0] ?? ($patient->getMeta('discount_payment') ?? '') }}">
                                                    </div>
                                                </div>
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="given_payment">Given Payment</label>
                                                        <input type="number" id="given_payment" name="given_payment"
                                                            placeholder="Amount paid" step="0.01"
                                                            value="{{ $followupMetaValues['given_payment'][0] ?? ($patient->getMeta('given_payment') ?? '') }}">
                                                    </div>
                                                </div>
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="due_payment">Due Payment</label>
                                                        <input type="number" id="due_payment" name="due_payment"
                                                            placeholder="Due amount" step="0.01"
                                                            value="{{ $followupMetaValues['due_payment'][0] ?? ($patient->getMeta('due_payment') ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="section-divider mt-4">
                                                <div class="title">Given Payment</div>
                                                <div class="line"></div>
                                            </div>

                                            <div class="pro_filed separate_payment pt-3">
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="cash_payment">Cash Payment</label>
                                                        <input type="number" id="cash_payment" name="cash_payment"
                                                            placeholder="Cash payment" step="0.01"
                                                            value="{{ $followupMetaValues['cash_payment'][0] ?? ($patient->getMeta('cash_payment') ?? '') }}">
                                                    </div>
                                                </div>
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="gp_payment">Google Pay</label>
                                                        <input type="number" id="gp_payment" name="gp_payment"
                                                            placeholder="Google Pay" step="0.01"
                                                            value="{{ $followupMetaValues['gp_payment'][0] ?? ($patient->getMeta('gp_payment') ?? '') }}">
                                                    </div>
                                                </div>
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="cheque_payment">Cheque Payment</label>
                                                        <input type="number" id="cheque_payment" name="cheque_payment"
                                                            placeholder="Cheque Payment" step="0.01"
                                                            value="{{ $followupMetaValues['cheque_payment'][0] ?? ($patient->getMeta('cheque_payment') ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <a href="{{ route('svc.profile', ['id' => $patient->id]) }}"
                                            class="btn btn-secondary">Cancel</a>
                                        <button type="submit" class="btn btn-primary" id="submitBtn">Update Follow
                                            Up</button>
                                    </div>
                                </form>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Age String Parser and Combination Functions
        function parseAgeString(ageStr) {
            let years = 0;
            let months = 0;

            if (!ageStr) {
                return { years: '', months: '' };
            }

            ageStr = ageStr.toString().trim();

            if (/^\d+$/.test(ageStr)) {
                return { years: parseInt(ageStr), months: '' };
            }

            let yearsMatch = ageStr.match(/(\d+)\s*Year/i);
            if (yearsMatch) {
                years = parseInt(yearsMatch[1]);
            }

            let monthsMatch = ageStr.match(/(\d+)\s*Month/i);
            if (monthsMatch) {
                months = parseInt(monthsMatch[1]);
            }

            if (!yearsMatch && !monthsMatch) {
                let numeric = parseFloat(ageStr);
                if (!isNaN(numeric)) {
                    years = Math.floor(numeric);
                    months = Math.round((numeric - years) * 12);
                }
            }

            return { 
                years: years || '', 
                months: months || '' 
            };
        }

        function updateAgeString() {
            const yearsInput = document.getElementById('age_years');
            const monthsInput = document.getElementById('age_months');
            const hiddenAge = document.getElementById('age');

            let years = parseInt(yearsInput.value) || 0;
            let months = parseInt(monthsInput.value) || 0;

            let ageParts = [];
            if (years > 0) {
                ageParts.push(years + (years === 1 ? ' Year' : ' Years'));
            }
            if (months > 0) {
                ageParts.push(months + (months === 1 ? ' Month' : ' Months'));
            }

            // Default if both are zero/empty
            if (ageParts.length === 0) {
                if (yearsInput.value !== '' || monthsInput.value !== '') {
                    hiddenAge.value = '0';
                } else {
                    hiddenAge.value = '';
                }
            } else {
                hiddenAge.value = ageParts.join(', ');
            }
        }

        function toggleSection(element, sectionId) {
            const section = document.getElementById(sectionId);
            const icon = element.querySelector('i');
            if (section.style.display === 'none' || section.style.display === '') {
                section.style.display = 'block';
                if (icon) icon.classList.replace('bi-plus-lg', 'bi-dash-lg');
            } else {
                section.style.display = 'none';
                if (icon) icon.classList.replace('bi-dash-lg', 'bi-plus-lg');
            }
        }

        function insideSection(element) {
            const section = document.getElementById('inside-treatment-section');
            const icon = element.querySelector('i');
            if (section.style.display === 'none' || section.style.display === '') {
                section.style.display = 'block';
                if (icon) icon.classList.replace('bi-plus-lg', 'bi-dash-lg');
            } else {
                section.style.display = 'none';
                if (icon) icon.classList.replace('bi-dash-lg', 'bi-plus-lg');
            }
        }

        function toggleIndoorSection(element) {
            const section = document.getElementById('indoor-treatment-section');
            const icon = element.querySelector('i');
            if (section.style.display === 'none' || section.style.display === '') {
                section.style.display = 'block';
                if (icon) icon.classList.replace('bi-plus-lg', 'bi-dash-lg');
            } else {
                section.style.display = 'none';
                if (icon) icon.classList.replace('bi-dash-lg', 'bi-plus-lg');
            }
        }

        function toggleOtherSection(element) {
            const section = document.getElementById('other-treatment-section');
            const icon = element.querySelector('i');
            if (section.style.display === 'none' || section.style.display === '') {
                section.style.display = 'block';
                if (icon) icon.classList.replace('bi-plus-lg', 'bi-dash-lg');
            } else {
                section.style.display = 'none';
                if (icon) icon.classList.replace('bi-dash-lg', 'bi-plus-lg');
            }
        }

        const focCheckbox = document.getElementById('foc');
        const paymentSection = document.getElementById('payment_section');
        const totalPayment = document.getElementById('total_payment');
        const discountPayment = document.getElementById('discount_payment');
        const givenPayment = document.getElementById('given_payment');
        const duePayment = document.getElementById('due_payment');

        if (focCheckbox) {
            focCheckbox.addEventListener('change', togglePaymentSection);
        }

        function togglePaymentSection() {
            const hasOldValues =
                (totalPayment ? totalPayment.value : false) ||
                (discountPayment ? discountPayment.value : false) ||
                (givenPayment ? givenPayment.value : false) ||
                (duePayment ? duePayment.value : false) ||
                (document.getElementById('cash_payment') ? document.getElementById('cash_payment').value : false) ||
                (document.getElementById('gp_payment') ? document.getElementById('gp_payment').value : false) ||
                (document.getElementById('cheque_payment') ? document.getElementById('cheque_payment').value : false);

            if (focCheckbox.checked) {
                if (!hasOldValues) {
                    if (paymentSection) paymentSection.style.display = 'none';
                } else {
                    if (paymentSection) paymentSection.style.display = 'block';
                }
            } else {
                if (paymentSection) paymentSection.style.display = 'block';
            }
        }

        window.onload = function () {
            // Parse initial age on page load
            const rawAge = document.getElementById('age').value;
            const parsedAge = parseAgeString(rawAge);
            document.getElementById('age_years').value = parsedAge.years;
            document.getElementById('age_months').value = parsedAge.months;
        };
    </script>
@endsection