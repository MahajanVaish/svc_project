@extends('admin.layouts.layouts')

@section('title', 'Add SVC Inquiry')

@section('content')
    <style>
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
            display: flex;
            padding-left: 10px;
            align-items: center;
            justify-content: center;
        }

        .section-divider .icon-box i {
            color: #067945;
            font-size: 23px;
        }

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

        .pro_filed {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 15px;
            width: 100%;
            align-items: flex-start;
        }

        .pro_filed .form {
            flex: 1;
            position: relative;
            min-width: 200px;
        }

        @media (max-width: 768px) {
            .pro_filed {
                flex-direction: column;
                gap: 15px;
            }
            .pro_filed .form {
                width: 100%;
                min-width: 100%;
            }
            .form-col {
                min-width: 100% !important;
            }
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

        label {
            font-weight: bold;
            color: #5a6268;
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
            outline: none;
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
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .required::after {
            content: " *";
            color: #e74c3c;
        }

        .hidden-field {
            display: none;
        }

        .fnf-title {
            font-size: 20px;
            color: #086838;
            font-weight: bold;
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
            -webkit-appearance: checkbox;
        }

        #foc.form-check-input:checked {
            background-color: green !important;
        }

        /* Dynamic Fields Styles */
        .dynamic-field-group {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dynamic-field-input {
            flex: 1;
        }

        .btn-add, .btn-remove {
            padding: 8px 12px;
            border: none;
            border-radius: 60%;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 25px;
            height: 26px;
            color: white;
        }

        .btn-add { background: #28a745; }
        .btn-add:hover { background: #3c703e; }
        .btn-remove { background: #bd1f2f; }
        .btn-remove:hover { background: #c82333; }

        .dynamic-fields-container {
            margin-bottom: 15px;
        }

        /* Custom Autocomplete Styling */
        .autocomplete-container {
            position: relative !important;
            width: 100% !important;
            display: block !important;
        }

        .autocomplete-dropdown {
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            width: 100% !important;
            background: white !important;
            border: 1px solid #ced4da !important;
            border-top: none !important;
            border-radius: 0 0 8px 8px !important;
            max-height: 250px !important;
            overflow-y: auto !important;
            z-index: 2000 !important;
            display: none;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15) !important;
        }

        .autocomplete-dropdown.show {
            display: block !important;
        }

        .autocomplete-item {
            padding: 10px 15px !important;
            cursor: pointer !important;
            border-bottom: 1px solid #f8f9fa !important;
            font-size: 14px !important;
            color: #333 !important;
            transition: all 0.2s ease !important;
        }

        .autocomplete-item:last-child {
            border-bottom: none !important;
        }

        .autocomplete-item:hover,
        .autocomplete-item.selected {
            background-color: #f0faf4 !important;
            color: #0d6832 !important;
        }

        /* Prevent table-responsive from clipping the dropdown */
        .table-responsive {
            overflow: visible !important;
        }

        /* Multi-Select Styles */
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
            background-color: #067945;
            color: white;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .selected-item .remove-item {
            cursor: pointer;
            background: none;
            border: none;
            color: white;
            font-size: 14px;
        }

        /* Treatment Table Styles */
        .treatment-section {
            margin-bottom: 1.5rem;
        }

        .treatment-table {
            width: 100%;
            margin-top: 0.5rem;
        }

        .treatment-table thead th {
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.5rem;
            border-bottom: 2px solid #dee2e6;
            color: #6c757d;
            text-transform: uppercase;
        }

        .treatment-table tbody td {
            padding: 0.5rem;
            vertical-align: middle;
        }
    </style>


    <div class="col-md-12 col-lg-10 m-auto p-0">

        <div class="card rounded shadow mb-5">
            <div class="card-header d-flex align-items-center">
                <a href="{{ route('svc.profile', $patient->id) }}" class="btn btn-secondary btn-sm me-3">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                <h3 class="bold font-up fnf-title text-success mb-0">Add FollowUps</h3>
            </div>
            <div class="row">
                <div class="col-md-12 m-auto">
                    <div class="bg-light rounded-5">
                        <section class="w-100 p-4 pb-4">
                            <div class="date-filter-section mb-4">
                                <form method="GET" action="{{ route('add.follow.up', $patient->patient_id) }}" 
                                      class="date-filter-form d-flex gap-3 align-items-end justify-content-between">
                                    <div style="width: 300px">
                                        <label for="date">Select Date:</label>
                                        <select name="date" id="date" onchange="loadTimesForDate(this.value)" class="form-select">
                                            <option value="">Select Date</option>
                                            @foreach ($followupDates as $date => $followups)
                                                <option value="{{ $date }}" {{ $selectedDate == $date ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                                                    ({{ count($followups) }} visits)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div style="width: 200px" id="time-selector-container">
                                        <label for="time">Select Time:</label>
                                        <select name="time" id="time" class="form-select" onchange="this.form.submit()">
                                            <option value="">All Times</option>
                                            @if($selectedDate && isset($followupDates[$selectedDate]))
                                                @foreach($followupDates[$selectedDate] as $followup)
                                                    @php
                                                        $timeMeta = $followup->metas->firstWhere('meta_key', 'followups_time');
                                                        $timeValue = $timeMeta ? $timeMeta->meta_value : '00:00:00';
                                                    @endphp
                                                    <option value="{{ $timeValue }}" 
                                                            {{ $selectedTime == $timeValue ? 'selected' : '' }}>
                                                        {{ $timeMeta ? \Carbon\Carbon::parse($timeMeta->meta_value)->format('h:i A') : 'N/A' }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <button type="button" class="btn btn-success btn-lg text-white" 
                                                onclick="openHistoryModal('{{ $selectedDate }}', '{{ $selectedTime }}')">
                                            📋 View History
                                        </button>
                                    </div>
                                </form>
                                
                                @if ($selectedDate)
                                    <div class="date-info mt-2">
                                        <strong>Showing:</strong> 
                                        {{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}
                                        @if($selectedTime)
                                            at {{ \Carbon\Carbon::parse($selectedTime)->format('h:i A') }}
                                        @endif
                                        @if(isset($followupDates[$selectedDate]))
                                            (Total {{ count($followupDates[$selectedDate]) }} visits on this date)
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="">
                                <form action="{{ route('store.follow.up', $patient->patient_id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="inquiry_id" value="{{ $patient->id }}">

                                    <input type="text" name="patient_id" value="{{ $patient->patient_id }}"
                                        class="hidden-field">
                                    <input type="hidden" id="branch_id" name="branch_id" value="SVC-0005">
                                    <div class="section-divider">Personal Information</div>
                                    <div class="pt-4">
                                        <div class="pro_filed ">
                                            <div class="form">
                                                <div class="form-col">
                                                    <div class="form-col">
                                                        <label for="patient_name" class="required">Patient Name</label>
                                                        <input type="text" name="patient_name"
                                                            value="{{ old('patient_name', $patient->patient_name) }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="address" class="required">Address</label>
                                                    <input name="address"
                                                        value="{{ old('address', $patient->address) }}" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pro_filed pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="follow_date">Date</label>
                                                    <input type="date" id="follow_date" name="followup_date"
                                                        placeholder="Date" value="{{ $selectedDate }}">
                                                </div>
                                            </div>



                                    <div class="form">
                                        <div class="form-col">
                                            <label for="followups_time">FollowUp Time</label>
                                            <input type="time" id="followups_time" name="followups_time" 
                                                value="{{ old('followups_time', $selectedTime ?: \Carbon\Carbon::now()->format('H:i')) }}">
                                        </div>
                                    </div>
                                                                            </div>
                                        <div class="pro_filed pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="gender" class="required">Gender</label>
                                                    <select name="gender">
                                                        <option value="male"
                                                            {{ $patient->gender == 'male' ? 'selected' : '' }}>Male
                                                        </option>
                                                        <option value="female"
                                                            {{ $patient->gender == 'female' ? 'selected' : '' }}>
                                                            Female
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="doctor_id">Assigned Doctor</label>
                                                    <select name="doctor_id" id="doctor_id">
                                                        <option value="">Select Doctor</option>
                                                        @foreach($doctors as $doctor)
                                                            <option value="{{ $doctor->id }}" 
                                                                {{ (old('doctor_id', $followup->doctor_id ?? '') == $doctor->id) ? 'selected' : '' }}>
                                                                {{ $doctor->name }}
                                                            </option>
                                                        @endforeach
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
                                                            <div class="">
                                                                <input type="number" name="weight[]"
                                                                    class="dynamic-field-input" value="{{ $weight }}"
                                                                    placeholder="Enter weight">
                                                            </div>
                                                        @empty
                                                            <div class="">
                                                                <input type="number" name="weight[]"
                                                                    class="dynamic-field-input" value=""
                                                                    placeholder="Enter weight">
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
                                        @endphp
                                        <div class="pro_filed pt-3">

                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="pt_status">PT.Status</label>
                                                    <div class="dynamic-fields-container" id="pt_status-container">
                                                        @forelse ($ptStatusValues as $index => $val)
                                                            <div class="">
                                                                <select name="pt_status[]" class="dynamic-field-input">
                                                                    <option value="IPD"
                                                                        {{ $val == 'IPD' ? 'selected' : '' }}>IPD
                                                                    </option>
                                                                    <option value="OPD"
                                                                        {{ $val == 'OPD' ? 'selected' : '' }}>OPD
                                                                    </option>
                                                                    <option value="Home Visit"
                                                                        {{ $val == 'Home Visit' ? 'selected' : '' }}>
                                                                        Home
                                                                        Visit
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        @empty
                                                            <div class="">
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
                                                                <input type="text" name="temperature[]"
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
                                                            <div class="">
                                                                <input type="text" name="pulse[]"
                                                                    class="dynamic-field-input"
                                                                    value="{{ $val === 'null' ? '' : $val }}"
                                                                    placeholder="Enter pulse rate">
                                                            </div>
                                                        @empty
                                                            <div class="">
                                                                <input type="text" name="pulse[]"
                                                                    class="dynamic-field-input" value=""
                                                                    placeholder="Enter pulse rate">
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
                                                            <div class="">
                                                                <input type="text" name="blood_pressure[]"
                                                                    class="dynamic-field-input"
                                                                    value="{{ $val === 'null' ? '' : $val }}"
                                                                    placeholder="e.g., 120/80">
                                                            </div>
                                                        @empty
                                                            <div class="">
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
                                                            <div class="">
                                                                <input type="number" name="spo2[]"
                                                                    class="dynamic-field-input"
                                                                    value="{{ $val === 'null' ? '' : $val }}"
                                                                    placeholder="Enter SpO2">
                                                            </div>
                                                        @empty
                                                            <div class="">
                                                                <input type="number" name="spo2[]"
                                                                    class="dynamic-field-input" value=""
                                                                    placeholder="Enter SpO2">
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
                                                            <div class="">
                                                                <input type="text" name="rbs[]"
                                                                    class="dynamic-field-input"
                                                                    value="{{ $val === 'null' ? '' : $val }}"
                                                                    placeholder="Enter RBS">
                                                            </div>
                                                        @empty
                                                            <div class="">
                                                                <input type="text" name="rbs[]"
                                                                    class="dynamic-field-input" value=""
                                                                    placeholder="Enter RBS">
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="section-divider mt-4">Medical Information</div>
                                        <div class="pro_filed pt-2">
                                            <div class="form">
                                                <div class="form-col-2">
                                                    <label for="complain">Complaint</label>
                                                    <div class="multi-select-container">
                                                        <div class="selected-items" id="complain-selected">
                                                            <!-- Selected complaints will appear here -->
                                                        </div>
                                                        <div class="autocomplete-container">
                                                            <input type="text" id="complain"
                                                                placeholder="Type to add complaints..." class="form-control"
                                                                autocomplete="off">
                                                            <div class="autocomplete-dropdown" id="complain-dropdown"></div>
                                                        </div>
                                                        @php
                                                            $complaintValues = $followupMetaValues['complain'] ?? [];
                                                            $complaintString = is_array($complaintValues) ? implode(', ', array_filter($complaintValues, fn($v) => !empty($v) && $v !== 'null')) : $complaintValues;
                                                        @endphp
                                                        <input type="hidden" name="complain" id="complain-hidden" value="{{ $complaintString }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pro_filed pt-3">
                                            <div class="form">
                                                <div class="form-col-2">
                                                    <label for="diagnosis">Diagnosis</label>
                                                    <div class="multi-select-container">
                                                        <div class="selected-items" id="diagnosis-selected">
                                                            <!-- Selected diagnoses will appear here -->
                                                        </div>
                                                        <div class="autocomplete-container">
                                                            <input type="text" id="diagnosis" 
                                                                placeholder="Type to add diagnoses..."  class="form-control" autocomplete="off">
                                                            <div class="autocomplete-dropdown" id="diagnosis-dropdown"></div>
                                                        </div>
                                                        <input type="hidden" name="diagnosis" id="diagnosis-hidden" value="{{ implode(', ', array_filter($diagnosisValues, fn($v) => !empty($v) && $v !== 'null')) }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pro_filed pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="investigation">Investigation</label>
                                                    <textarea id="investigation" name="investigation"> {{ !empty($followupMetaValues['investigation'][0]) ? $followupMetaValues['investigation'][0] : ($patient->getMeta('investigation') ?? '') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="past_history">Past History</label>
                                                    <textarea id="past_history" name="past_history"> {{ !empty($followupMetaValues['past_history'][0]) ? $followupMetaValues['past_history'][0] : ($patient->getMeta('past_history') ?? '') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pro_filed pt-3">
                                            <div class="form">
                                                <div class="form-col-2">
                                                    <label for="family_history">Family History</label>
                                                    <textarea id="family_history" name="family_history"> {{ !empty($followupMetaValues['family_history'][0]) ? $followupMetaValues['family_history'][0] : ($patient->getMeta('family_history') ?? '') }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        @php
                                            $hbValues = $followupMetaValues['hb'] ?? [];
                                            $tcValues = $followupMetaValues['tc'] ?? [];
                                            $pcValues = $followupMetaValues['pc'] ?? [];
                                            $mpValues = $followupMetaValues['MP'] ?? [];
                                            $hb1acValues = $followupMetaValues['HB1AC'] ?? [];
                                            $fbsValues = $followupMetaValues['fbs'] ?? [];
                                            $pp2bsValues = $followupMetaValues['pp2bs'] ?? [];
                                            $sWidalValues = $followupMetaValues['S_widal'] ?? [];
                                            $usgValues = $followupMetaValues['USG'] ?? [];
                                            $xrayValues = $followupMetaValues['X_ray'] ?? [];
                                            $sgptValues = $followupMetaValues['SGPT'] ?? [];
                                            $sCreatinineValues = $followupMetaValues['s_creatinine'] ?? [];
                                            $ns1agValues = $followupMetaValues['NS1Ag'] ?? [];
                                            $dengueIgmValues = $followupMetaValues['DengueIGM'] ?? [];
                                            $cholesterolValues = $followupMetaValues['s_cholesterol'] ?? [];
                                            $triglycerideValues = $followupMetaValues['STriglyceride'] ?? [];
                                            $hdlValues = $followupMetaValues['HDL'] ?? [];
                                            $ldlValues = $followupMetaValues['LDL'] ?? [];
                                            $vldlValues = $followupMetaValues['VLDL'] ?? [];
                                            $nonHdlCValues = $followupMetaValues['non_hdl_c'] ?? [];  
                                            $cholHdlRatioValues = $followupMetaValues['chol_hdl_ratio'] ?? [];
                                            $sb12Values = $followupMetaValues['SB12'] ?? [];
                                            $sd3Values = $followupMetaValues['SD3'] ?? [];
                                            $urineValues = $followupMetaValues['Urine'] ?? [];
                                            $crpValues = $followupMetaValues['CRP'] ?? [];
                                            $st3Values = $followupMetaValues['St3'] ?? [];
                                            $st4Values = $followupMetaValues['St4'] ?? [];
                                            $stshValues = $followupMetaValues['STSH'] ?? [];
                                            $esrValues = $followupMetaValues['ESR'] ?? [];
                                            $specificTestValues = $followupMetaValues['specific_test'] ?? [];

                                            // Check for existing data to set accordion state
                                            $hasLipidData = false;
                                            $lipidCheckKeys = ['s_cholesterol', 'STriglyceride', 'HDL', 'LDL', 'VLDL', 'non_hdl_c', 'chol_hdl_ratio'];
                                            foreach($lipidCheckKeys as $key) {
                                                if(!empty($followupMetaValues[$key])) {
                                                    foreach($followupMetaValues[$key] as $val) {
                                                        if(!empty($val) && $val !== 'null') { $hasLipidData = true; break 2; }
                                                    }
                                                }
                                            }

                                            $labCheckKeys = ['hb', 'tc', 'pc', 'MP', 'HB1AC', 'fbs', 'pp2bs', 'S_widal', 'USG', 'X_ray', 'SGPT', 's_creatinine', 'NS1Ag', 'DengueIGM', 'SB12', 'SD3', 'Urine', 'CRP', 'St3', 'St4', 'STSH', 'ESR', 'specific_test'];
                                            $hasLabData = false;
                                            foreach($labCheckKeys as $key) {
                                                if(!empty($followupMetaValues[$key])) {
                                                    foreach($followupMetaValues[$key] as $val) {
                                                        if(!empty($val) && $val !== 'null') { $hasLabData = true; break 2; }
                                                    }
                                                }
                                            }
                                        @endphp

                                        <!-- Lipid Profile Section -->
                                        <div class="section-divider mt-4">
                                            <div class="title">Lipid Profile</div>
                                            <div class="line"></div>
                                            <div class="icon-box" onclick="toggleSection(this, 'lipid-profile-section')">
                                                <i class="bi bi-{{ $hasLipidData ? 'dash' : 'plus' }}-lg" id="lipid-toggle-icon"></i>
                                            </div>
                                        </div>

                                        <div class="lipid-profile-section" id="lipid-profile-section" style="display: {{ $hasLipidData ? 'block' : 'none' }};">
                                            <div class="row pt-3">
                                                <div class="col-md-3 mb-3">
                                                    <label for="s_cholesterol">S. Cholesterol</label>
                                                    <div class="dynamic-fields-container" id="cholesterol-container">
                                                        @forelse ($cholesterolValues as $val)
                                                            <div class="dynamic-field-group">
                                                                <input type="text" name="s_cholesterol[]" class="form-control" value="{{ $val === 'null' ? '' : $val }}" placeholder="S. Cholesterol">
                                                            </div>
                                                        @empty
                                                            <div class="dynamic-field-group">
                                                                <input type="text" name="s_cholesterol[]" class="form-control" value="" placeholder="S. Cholesterol">
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="STriglyceride">S. Triglyceride</label>
                                                    <div class="dynamic-fields-container" id="triglyceride-container">
                                                        @forelse ($triglycerideValues as $val)
                                                            <div class="dynamic-field-group">
                                                                <input type="text" name="STriglyceride[]" class="form-control" value="{{ $val === 'null' ? '' : $val }}" placeholder="S. Triglyceride">
                                                            </div>
                                                        @empty
                                                            <div class="dynamic-field-group">
                                                                <input type="text" name="STriglyceride[]" class="form-control" value="" placeholder="S. Triglyceride">
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="HDL">HDL</label>
                                                    <div class="dynamic-fields-container" id="hdl-container">
                                                        @forelse ($hdlValues as $val)
                                                            <div class="dynamic-field-group">
                                                                <input type="text" name="HDL[]" class="form-control" value="{{ $val === 'null' ? '' : $val }}" placeholder="HDL">
                                                            </div>
                                                        @empty
                                                            <div class="dynamic-field-group">
                                                                <input type="text" name="HDL[]" class="form-control" value="" placeholder="HDL">
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="LDL">LDL</label>
                                                    <div class="dynamic-fields-container" id="ldl-container">
                                                        @forelse ($ldlValues as $val)
                                                            <div class="dynamic-field-group">
                                                                <input type="text" name="LDL[]" class="form-control" value="{{ $val === 'null' ? '' : $val }}" placeholder="LDL">
                                                            </div>
                                                        @empty
                                                            <div class="dynamic-field-group">
                                                                <input type="text" name="LDL[]" class="form-control" value="" placeholder="LDL">
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="VLDL">VLDL</label>
                                                    <div class="dynamic-fields-container" id="vldl-container">
                                                        @forelse ($vldlValues as $val)
                                                            <div class="dynamic-field-group">
                                                                <input type="text" name="VLDL[]" class="form-control" value="{{ $val === 'null' ? '' : $val }}" placeholder="VLDL">
                                                            </div>
                                                        @empty
                                                            <div class="dynamic-field-group">
                                                                <input type="text" name="VLDL[]" class="form-control" value="" placeholder="VLDL">
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="non_hdl_c">Non-HDL C</label>
                                                    <div class="dynamic-fields-container" id="non-hdl-c-container">
                                                        @forelse ($nonHdlCValues as $val)
                                                            <div class="dynamic-field-group">
                                                                <input type="text" name="non_hdl_c[]" class="form-control" value="{{ $val === 'null' ? '' : $val }}" placeholder="Non-HDL C">
                                                            </div>
                                                        @empty
                                                            <div class="dynamic-field-group">
                                                                <input type="text" name="non_hdl_c[]" class="form-control" value="" placeholder="Non-HDL C">
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="chol_hdl_ratio">Chol/HDL ratio</label>
                                                    <div class="dynamic-fields-container" id="chol-hdl-ratio-container">
                                                        @forelse ($cholHdlRatioValues as $val)
                                                            <div class="dynamic-field-group">
                                                                <input type="text" name="chol_hdl_ratio[]" class="form-control" value="{{ $val === 'null' ? '' : $val }}" placeholder="Chol/HDL ratio">
                                                            </div>
                                                        @empty
                                                            <div class="dynamic-field-group">
                                                                <input type="text" name="chol_hdl_ratio[]" class="form-control" value="" placeholder="Chol/HDL ratio">
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Laboratory Tests Section -->
                                        <div class="section-divider mt-4">
                                            <div class="title">Laboratory Tests</div>
                                            <div class="line"></div>
                                            <div class="icon-box" onclick="toggleSection(this, 'lab-investigation-section')">
                                                <i class="bi bi-{{ $hasLabData ? 'dash' : 'plus' }}-lg" id="lab-toggle-icon"></i>
                                            </div>
                                        </div>

                                        <div class="lab-investigation-section" id="lab-investigation-section" style="display: {{ $hasLabData ? 'block' : 'none' }};">
                                            <div class="row pt-3">
                                                @php
                                                    $lab_items = [
                                                        ['label' => 'HB', 'name' => 'hb', 'values' => $hbValues],
                                                        ['label' => 'TC', 'name' => 'tc', 'values' => $tcValues],
                                                        ['label' => 'PC', 'name' => 'pc', 'values' => $pcValues],
                                                        ['label' => 'MP', 'name' => 'MP', 'values' => $mpValues],
                                                        ['label' => 'HB1AC', 'name' => 'HB1AC', 'values' => $hb1acValues],
                                                        ['label' => 'FBS', 'name' => 'fbs', 'values' => $fbsValues],
                                                        ['label' => 'PP2BS', 'name' => 'pp2bs', 'values' => $pp2bsValues],
                                                        ['label' => 'S.widal', 'name' => 'S_widal', 'values' => $sWidalValues],
                                                        ['label' => 'USG', 'name' => 'USG', 'values' => $usgValues],
                                                        ['label' => 'X-ray', 'name' => 'X_ray', 'values' => $xrayValues],
                                                        ['label' => 'SGPT', 'name' => 'SGPT', 'values' => $sgptValues],
                                                        ['label' => 'S. Creatinine', 'name' => 's_creatinine', 'values' => $sCreatinineValues],
                                                        ['label' => 'NS1Ag', 'name' => 'NS1Ag', 'values' => $ns1agValues],
                                                        ['label' => 'Dengue IGM', 'name' => 'DengueIGM', 'values' => $dengueIgmValues],
                                                        ['label' => 'S.B12', 'name' => 'SB12', 'values' => $sb12Values],
                                                        ['label' => 'S.D3', 'name' => 'SD3', 'values' => $sd3Values],
                                                        ['label' => 'Urine', 'name' => 'Urine', 'values' => $urineValues],
                                                        ['label' => 'CRP', 'name' => 'CRP', 'values' => $crpValues],
                                                        ['label' => 'S.T3', 'name' => 'St3', 'values' => $st3Values],
                                                        ['label' => 'S.T4', 'name' => 'St4', 'values' => $st4Values],
                                                        ['label' => 'S.TSH', 'name' => 'STSH', 'values' => $stshValues],
                                                        ['label' => 'ESR', 'name' => 'ESR', 'values' => $esrValues],
                                                        ['label' => 'Any specific Test', 'name' => 'specific_test', 'values' => $specificTestValues],
                                                    ];
                                                @endphp

                                                @foreach ($lab_items as $item)
                                                    <div class="col-md-3 mb-3">
                                                        <label>{{ $item['label'] }}</label>
                                                        <div class="dynamic-fields-container">
                                                            @forelse ($item['values'] as $val)
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="{{ $item['name'] }}[]" class="form-control" value="{{ $val === 'null' ? '' : $val }}" placeholder="{{ $item['label'] }}">
                                                                </div>
                                                            @empty
                                                                <div class="dynamic-field-group">
                                                                    <input type="text" name="{{ $item['name'] }}[]" class="form-control" value="" placeholder="{{ $item['label'] }}">
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <!-- INSIDE TREATMENT -->
                                        <div class="treatment-section mb-4">
                                            <div class="section-divider">
                                                <div class="title">Inside Treatment</div>
                                                <div class="line"></div>
                                                <div class="icon-box" onclick="toggleInsideSection(this)">
                                                    <i class="bi bi-dash-lg" id="inside-toggle-icon"></i>
                                                </div>
                                            </div>
                                            
                                            <div id="inside-section" class="mt-3">
                                                <table class="table table-borderless treatment-table">
                                                    <thead>
                                                        <tr class="text-muted small">
                                                            <th style="width: 30%">Medicine</th>
                                                            <th style="width: 20%">Dose</th>
                                                            <th style="width: 15%">Days</th>
                                                            <th style="width: 20%">Timing</th>
                                                            <th style="width: 15%"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="inside-treatment-body">
                                                        @php
                                                        $insideTreatments = $treatments['inside'] ?? [];
                                                        @endphp
                                                        
                                                        @forelse($insideTreatments as $index => $treatment)
                                                        <tr>
                                                            <td><input type="text" name="inside_medicine[]" class="form-control form-control-sm" value="{{ $treatment['medicine'] ?? '' }}" placeholder="Medicine name" autocomplete="off"></td>
                                                            <td>
                                                                <div class="autocomplete-container">
                                                                    <input type="text" name="inside_dose[]" class="form-control form-control-sm dose-input" value="{{ $treatment['dose'] ?? '' }}" placeholder="Dose" autocomplete="off">
                                                                    <div class="autocomplete-dropdown"></div>
                                                                </div>
                                                            </td>
                                                            <td><input type="text" name="inside_days[]" class="form-control form-control-sm" value="{{ $treatment['days'] ?? '' }}" placeholder="Days"></td>
                                                            <td>
                                                                <select name="inside_timing[]" class="form-select form-select-sm">
                                                                    <option value="">Select</option>
                                                                    <option value="Before Food" {{ ($treatment['timing'] ?? '') == 'Before Food' ? 'selected' : '' }}>Before Food</option>
                                                                    <option value="After Food" {{ ($treatment['timing'] ?? '') == 'After Food' ? 'selected' : '' }}>After Food</option>
                                                                    <option value="With Food" {{ ($treatment['timing'] ?? '') == 'With Food' ? 'selected' : '' }}>With Food</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                @if($index > 0)
                                                                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()"><i class="bi bi-trash"></i> Remove</button>
                                                                @else
                                                                <button type="button" class="btn btn-success btn-sm" onclick="addInsideRow()"><i class="bi bi-plus"></i> Add</button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td><input type="text" name="inside_medicine[]" class="form-control form-control-sm" placeholder="Medicine name" autocomplete="off"></td>
                                                            <td>
                                                                <div class="autocomplete-container">
                                                                    <input type="text" name="inside_dose[]" class="form-control form-control-sm dose-input" placeholder="Dose" autocomplete="off">
                                                                    <div class="autocomplete-dropdown"></div>
                                                                </div>
                                                            </td>
                                                            <td><input type="text" name="inside_days[]" class="form-control form-control-sm" placeholder="Days"></td>
                                                            <td>
                                                                <select name="inside_timing[]" class="form-select form-select-sm">
                                                                    <option value="">Select</option>
                                                                    <option>Before Food</option>
                                                                    <option>After Food</option>
                                                                    <option>With Food</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-success btn-sm" onclick="addInsideRow()"><i class="bi bi-plus"></i> Add</button>
                                                            </td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- HOMEOPATHIC TREATMENT -->
                                        <div class="treatment-section mb-4">
                                            <div class="section-divider">
                                                <div class="title">Homeopathic Treatment</div>
                                                <div class="line"></div>
                                                <div class="icon-box" onclick="toggleHomeopathicSection(this)">
                                                    <i class="bi bi-dash-lg" id="homeopathic-toggle-icon"></i>
                                                </div>
                                            </div>
                                            
                                            <div id="homeo-section" class="mt-3">
                                                <table class="table table-borderless treatment-table">
                                                    <thead>
                                                        <tr class="text-muted small">
                                                            <th style="width: 30%">Medicine</th>
                                                            <th style="width: 20%">Dose</th>
                                                            <th style="width: 15%">Days</th>
                                                            <th style="width: 20%">Timing</th>
                                                            <th style="width: 15%"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="homeo-treatment-body">
                                                        @php
                                                        $homeoTreatments = $treatments['homeo'] ?? [];
                                                        @endphp
                                                        
                                                        @forelse($homeoTreatments as $index => $treatment)
                                                        <tr>
                                                            <td><input type="text" name="homeo_medicine[]" class="form-control form-control-sm" value="{{ $treatment['medicine'] ?? '' }}" placeholder="Medicine name" autocomplete="off"></td>
                                                            <td>
                                                                <div class="autocomplete-container">
                                                                    <input type="text" name="homeo_dose[]" class="form-control form-control-sm dose-input" value="{{ $treatment['dose'] ?? '' }}" placeholder="Dose" autocomplete="off">
                                                                    <div class="autocomplete-dropdown"></div>
                                                                </div>
                                                            </td>
                                                            <td><input type="text" name="homeo_days[]" class="form-control form-control-sm" value="{{ $treatment['days'] ?? '' }}" placeholder="Days"></td>
                                                            <td>
                                                                <select name="homeo_timing[]" class="form-select form-select-sm">
                                                                    <option value="">Select</option>
                                                                    <option value="Before Food" {{ ($treatment['timing'] ?? '') == 'Before Food' ? 'selected' : '' }}>Before Food</option>
                                                                    <option value="After Food" {{ ($treatment['timing'] ?? '') == 'After Food' ? 'selected' : '' }}>After Food</option>
                                                                    <option value="With Food" {{ ($treatment['timing'] ?? '') == 'With Food' ? 'selected' : '' }}>With Food</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                @if($index > 0)
                                                                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()"><i class="bi bi-trash"></i></button>
                                                                @else
                                                                <button type="button" class="btn btn-success btn-sm" onclick="addHomeoRow()"><i class="bi bi-plus"></i> Add</button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td><input type="text" name="homeo_medicine[]" class="form-control form-control-sm" placeholder="Medicine name" autocomplete="off"></td>
                                                            <td>
                                                                <div class="autocomplete-container">
                                                                    <input type="text" name="homeo_dose[]" class="form-control form-control-sm dose-input" placeholder="Dose" autocomplete="off">
                                                                    <div class="autocomplete-dropdown"></div>
                                                                </div>
                                                            </td>
                                                            <td><input type="text" name="homeo_days[]" class="form-control form-control-sm" placeholder="Days"></td>
                                                            <td>
                                                                <select name="homeo_timing[]" class="form-select form-select-sm">
                                                                    <option value="">Select</option>
                                                                    <option>Before Food</option>
                                                                    <option>After Food</option>
                                                                    <option>With Food</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-success btn-sm" onclick="addHomeoRow()"><i class="bi bi-plus"></i> Add</button>
                                                            </td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- PRESCRIPTION -->
                                        <div class="treatment-section mb-4">
                                            <div class="section-divider">
                                                <div class="title">Prescription</div>
                                                <div class="line"></div>
                                                <div class="icon-box" onclick="togglePrescriptionSection(this)">
                                                    <i class="bi bi-dash-lg" id="prescription-toggle-icon"></i>
                                                </div>
                                            </div>
                                            
                                            <div id="prescription-section" class="mt-3">
                                                <table class="table table-borderless treatment-table">
                                                    <thead>
                                                        <tr class="text-muted small">
                                                            <th style="width: 30%">Medicine</th>
                                                            <th style="width: 20%">Dose</th>
                                                            <th style="width: 15%">Days</th>
                                                            <th style="width: 20%">Timing</th>
                                                            <th style="width: 15%"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="prescription-treatment-body">
                                                        @php
                                                        $prescriptionTreatments = $treatments['prescription'] ?? [];
                                                        @endphp
                                                        
                                                        @forelse($prescriptionTreatments as $index => $treatment)
                                                        <tr>
                                                            <td><input type="text" name="prescription_medicine[]" class="form-control form-control-sm" value="{{ $treatment['medicine'] ?? '' }}" placeholder="Medicine name" autocomplete="off"></td>
                                                            <td>
                                                                <div class="autocomplete-container">
                                                                    <input type="text" name="prescription_dose[]" class="form-control form-control-sm dose-input" value="{{ $treatment['dose'] ?? '' }}" placeholder="Dose" autocomplete="off">
                                                                    <div class="autocomplete-dropdown"></div>
                                                                </div>
                                                            </td>
                                                            <td><input type="text" name="prescription_days[]" class="form-control form-control-sm" value="{{ $treatment['days'] ?? '' }}" placeholder="Days"></td>
                                                            <td>
                                                                <select name="prescription_timing[]" class="form-select form-select-sm">
                                                                    <option value="">Select</option>
                                                                    <option value="Before Food" {{ ($treatment['timing'] ?? '') == 'Before Food' ? 'selected' : '' }}>Before Food</option>
                                                                    <option value="After Food" {{ ($treatment['timing'] ?? '') == 'After Food' ? 'selected' : '' }}>After Food</option>
                                                                    <option value="With Food" {{ ($treatment['timing'] ?? '') == 'With Food' ? 'selected' : '' }}>With Food</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                @if($index > 0)
                                                                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()"><i class="bi bi-trash"></i></button>
                                                                @else
                                                                <button type="button" class="btn btn-success btn-sm" onclick="addPrescriptionRow()"><i class="bi bi-plus"></i> Add</button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td><input type="text" name="prescription_medicine[]" class="form-control form-control-sm" placeholder="Medicine name" autocomplete="off"></td>
                                                            <td>
                                                                <div class="autocomplete-container">
                                                                    <input type="text" name="prescription_dose[]" class="form-control form-control-sm dose-input" placeholder="Dose" autocomplete="off">
                                                                    <div class="autocomplete-dropdown"></div>
                                                                </div>
                                                            </td>
                                                            <td><input type="text" name="prescription_days[]" class="form-control form-control-sm" placeholder="Days"></td>
                                                            <td>
                                                                <select name="prescription_timing[]" class="form-select form-select-sm">
                                                                    <option value="">Select</option>
                                                                    <option>Before Food</option>
                                                                    <option>After Food</option>
                                                                    <option>With Food</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-success btn-sm" onclick="addPrescriptionRow()"><i class="bi bi-plus"></i> Add</button>
                                                            </td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- INDOOR TREATMENT -->
                                        <div class="treatment-section mb-4">
                                            <div class="section-divider">
                                                <div class="title">Indoor Treatment</div>
                                                <div class="line"></div>
                                                <div class="icon-box" onclick="toggleIndoorSection(this)">
                                                    <i class="bi bi-dash-lg" id="indoor-toggle-icon"></i>
                                                </div>
                                            </div>
                                            
                                            <div id="indoor-section" class="mt-3">
                                                <table class="table table-borderless treatment-table">
                                                    <thead>
                                                        <tr class="text-muted small">
                                                            <th style="width: 25%">Medicine</th>
                                                            <th style="width: 15%">Dose</th>
                                                            <th style="width: 10%">Days</th>
                                                            <th style="width: 15%">Date</th>
                                                            <th style="width: 10%">Time</th>
                                                            <th style="width: 15%">Note</th>
                                                            <th style="width: 10%"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="indoor-treatment-body">
                                                        @php
                                                        $indoorTreatments = $treatments['indoor'] ?? [];
                                                        @endphp
                                                        
                                                        @forelse($indoorTreatments as $index => $treatment)
                                                        <tr>
                                                            <td><input type="text" name="indoor_medicine[]" class="form-control form-control-sm" value="{{ $treatment['medicine'] ?? '' }}" placeholder="Medicine name" autocomplete="off"></td>
                                                            <td>
                                                                <div class="autocomplete-container">
                                                                    <input type="text" name="indoor_dose[]" class="form-control form-control-sm dose-input" value="{{ $treatment['dose'] ?? '' }}" placeholder="Dose" autocomplete="off">
                                                                    <div class="autocomplete-dropdown"></div>
                                                                </div>
                                                            </td>
                                                            <td><input type="text" name="indoor_days[]" class="form-control form-control-sm" value="{{ $treatment['days'] ?? '' }}" placeholder="Days"></td>
                                                            <td><input type="date" name="indoor_date[]" class="form-control form-control-sm" value="{{ $treatment['date'] ?? '' }}"></td>
                                                            <td><input type="time" name="indoor_time[]" class="form-control form-control-sm" value="{{ $treatment['time'] ?? '' }}"></td>
                                                            <td><input type="text" name="indoor_note[]" class="form-control form-control-sm" value="{{ $treatment['note'] ?? '' }}" placeholder="Note"></td>
                                                            <td>
                                                                @if($index > 0)
                                                                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()"><i class="bi bi-trash"></i></button>
                                                                @else
                                                                <button type="button" class="btn btn-success btn-sm" onclick="addMedicineRow()"><i class="bi bi-plus"></i></button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td><input type="text" name="indoor_medicine[]" class="form-control form-control-sm" placeholder="Medicine name" autocomplete="off"></td>
                                                            <td>
                                                                <div class="autocomplete-container">
                                                                    <input type="text" name="indoor_dose[]" class="form-control form-control-sm dose-input" placeholder="Dose" autocomplete="off">
                                                                    <div class="autocomplete-dropdown"></div>
                                                                </div>
                                                            </td>
                                                            <td><input type="text" name="indoor_days[]" class="form-control form-control-sm" placeholder="Days"></td>
                                                            <td><input type="date" name="indoor_date[]" class="form-control form-control-sm"></td>
                                                            <td><input type="time" name="indoor_time[]" class="form-control form-control-sm"></td>
                                                            <td><input type="text" name="indoor_note[]" class="form-control form-control-sm" placeholder="Note"></td>
                                                            <td>
                                                                <button type="button" class="btn btn-success btn-sm" onclick="addMedicineRow()"><i class="bi bi-plus"></i>Add</button>
                                                            </td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- OTHER TREATMENT -->
                                        <div class="treatment-section mb-4">
                                            <div class="section-divider">
                                                <div class="title">Other Treatment</div>
                                                <div class="line"></div>
                                                <div class="icon-box" onclick="toggleOtherSection(this)">
                                                    <i class="bi bi-dash-lg" id="other-toggle-icon"></i>
                                                </div>
                                            </div>
                                            
                                            <div id="other-section" class="mt-3">
                                                <table class="table table-borderless treatment-table">
                                                    <thead>
                                                        <tr class="text-muted small">
                                                            <th style="width: 40%">Medicine</th>
                                                            <th style="width: 45%">Note</th>
                                                            <th style="width: 15%"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="other-treatment-body">
                                                        @php
                                                        $otherTreatments = $treatments['other'] ?? [];
                                                        @endphp
                                                        
                                                        @forelse($otherTreatments as $index => $treatment)
                                                        <tr>
                                                            <td><input type="text" name="other_medicine[]" class="form-control form-control-sm" value="{{ $treatment['medicine'] ?? '' }}" placeholder="Medicine name" autocomplete="off"></td>
                                                            <td><input type="text" name="other_note[]" class="form-control form-control-sm" value="{{ $treatment['note'] ?? '' }}" placeholder="Note"></td>
                                                            <td>
                                                                @if($index > 0)
                                                                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()"><i class="bi bi-trash"></i> Remove</button>
                                                                @else
                                                                <button type="button" class="btn btn-success btn-sm" onclick="addOtherRow()"><i class="bi bi-plus"></i> Add</button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td><input type="text" name="other_medicine[]" class="form-control form-control-sm" placeholder="Medicine name" autocomplete="off"></td>
                                                            <td><input type="text" name="other_note[]" class="form-control form-control-sm" placeholder="Note"></td>
                                                            <td>
                                                                <button type="button" class="btn btn-success btn-sm" onclick="addOtherRow()"><i class="bi bi-plus"></i> Add</button>
                                                            </td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="pro_filed pt-3">
                                             <div class="form">
                                                 <div class="form-col">
                                                     <label for="reference_by">Reference by</label>
                                                     <input type="text" id="reference_by" name="reference_by"
                                                         placeholder="Reference by" value="{{ !empty($followupMetaValues['reference_by'][0]) ? $followupMetaValues['reference_by'][0] : ($patient->getMeta('reference_by') ?? '') }}">
                                                 </div>
                                             </div>
                                             <div class="form">
                                                 <div class="form-col">
                                                     <label for="referto">Refer to</label>
                                                     <input type="text" id="referto" name="referto"
                                                         placeholder="Refer to" value="{{ $followupMetaValues['referto'][0] ?? $patient->getMeta('referto') }}">
                                                 </div>
                                             </div>
                                         </div>
                                        <div class="pro_filed pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="notes">Notes</label>
                                                    <input type="text" id="notes" name="notes" placeholder="Notes" value="{{ $followupMetaValues['notes'][0] ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="next_follow_date">Next follow up date</label>
                                                    <input type="date" id="next_follow_date" name="next_follow_date" placeholder="Next follow up date" value="{{ $followup->next_follow_date ?? '' }}">
                                                </div>
                                            </div>
                                        </div>

                                    <div class="section-divider mt-4">
                                        <div class="title">Payment Information</div>
                                        <div class="line"></div>
                                    </div>
                                    
                                    <div class="d-flex align-items-center bg-light p-3 mb-3">
                                        <input type="checkbox" name="foc" id="foc"
                                            class="form-check-input me-3" {{ !empty($followupMetaValues['foc'][0]) ? 'checked' : '' }}>

                                        <label for="foc" class="mb-0 fw-semibold">
                                            FOC (Free of Charge Inquiry)
                                        </label>
                                    </div>

                                    <div id="payment_section">
                                        <div class="pro_filed" style="flex-wrap: wrap;">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label class="required">Followup Charges</label>
                                                    <div class="multi-select-container">
                                                        <div class="selected-items" id="charge-selected">
                                                            <!-- Selected charges will appear here -->
                                                        </div>
                                                        <div class="autocomplete-container d-flex gap-2">
                                                            <input type="text" id="charge"
                                                                placeholder="Select Followup Charges..."
                                                                class="form-control" autocomplete="off">
                                                            <button type="button" class="btn btn-outline-primary"
                                                                id="add-custom-charge">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                            <div class="autocomplete-dropdown" id="charge-dropdown">
                                                            </div>
                                                        </div>
                                                        <div id="charge-hidden-inputs">
                                                            @php
                                                                $currentChargeIds = [];
                                                                if (!empty($followupMetaValues['charge_id'][0])) {
                                                                    $val = $followupMetaValues['charge_id'][0];
                                                                    $decoded = json_decode($val, true);
                                                                    $currentChargeIds = is_array($decoded) ? $decoded : [$val];
                                                                }
                                                            @endphp
                                                            @foreach($currentChargeIds as $id)
                                                                @php $chargeItem = $charges->firstWhere('id', $id); @endphp
                                                                @if($chargeItem)
                                                                    <input type="hidden" name="charge_id[]" value="{{ $id }}" data-price="{{ $chargeItem->charges_price }}" data-name="{{ $chargeItem->charges_name }}" class="charge-hidden">
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="total_payment">Total Amount</label>
                                                    <input type="number" id="total_payment" name="total_payment"
                                                        step="0.01" readonly placeholder="Total" value="{{ $followupMetaValues['total_payment'][0] ?? 0 }}">
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="discount_payment">Discount Amount</label>
                                                    <input type="number" id="discount_payment"
                                                        name="discount_payment" step="0.01" placeholder="Discount" value="{{ $followupMetaValues['discount_payment'][0] ?? 0 }}">
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="given_payment">Paid Amount</label>
                                                    <input type="number" id="given_payment" name="given_payment"
                                                        placeholder="Enter amount paid" step="0.01" value="{{ $followupMetaValues['given_payment'][0] ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="payment_method">Payment Method</label>
                                                    <select id="payment_method" name="payment_method">
                                                        @php 
                                                            $pm = !empty($followupMetaValues['payment_method'][0]) ? $followupMetaValues['payment_method'][0] : ($patient->getMeta('payment_method') ?? '');
                                                        @endphp
                                                        <option value="" {{ $pm == '' ? 'selected' : '' }}>Select Type</option>
                                                        <option value="Cash" {{ $pm == 'Cash' ? 'selected' : '' }}>Cash</option>
                                                        <option value="Online" {{ $pm == 'Online' ? 'selected' : '' }}>Online</option>
                                                        <option value="Cheque" {{ $pm == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="due_payment">Due Amount</label>
                                                    <input type="number" id="due_payment" name="due_payment" value="{{ (float)($followupMetaValues['total_payment'][0] ?? 0) - (float)($followupMetaValues['discount_payment'][0] ?? 0) - (float)($followupMetaValues['given_payment'][0] ?? 0) }}" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pro_filed pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="gp_payment">Google Pay</label>
                                                    <input type="number" id="gp_payment" name="gp_payment"
                                                        placeholder="Google Pay" step="0.01" value="{{ $followupMetaValues['gp_payment'][0] ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="cheque_payment">Cheque Payment</label>
                                                    <input type="number" id="cheque_payment" name="cheque_payment"
                                                        placeholder="Cheque Payment" step="0.01" value="{{ $followupMetaValues['cheque_payment'][0] ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        // Set default time only if value is empty
                                        const now = new Date().toTimeString().split(' ')[0].substring(0, 5);
                                        const followTimeEl = document.getElementById('followups_time');
                                        if (followTimeEl && !followTimeEl.value) followTimeEl.value = now;
                                           // FOC checkbox functionality
                                        const focCheckbox = document.getElementById('foc');
                                        const paymentSection = document.getElementById('payment_section');
                                        const totalPaymentInput = document.getElementById('total_payment');
                                        const givenPaymentInput = document.getElementById('given_payment');
                                        const duePaymentInput = document.getElementById('due_payment');
                                        const paymentMethod = document.getElementById('payment_method');
                                        
                                        if (focCheckbox && paymentSection) {
                                            function togglePaymentSection() {
                                                if (focCheckbox.checked) {
                                                    paymentSection.style.display = 'none';
                                                    if (totalPaymentInput) totalPaymentInput.value = '';
                                                    if (givenPaymentInput) givenPaymentInput.value = '';
                                                    if (duePaymentInput) duePaymentInput.value = '';
                                                    if (paymentMethod) paymentMethod.value = '';
                                                    
                                                    // Clear multi-select charges
                                                    const selectedContainer = document.getElementById('charge-selected');
                                                    const hiddenContainer = document.getElementById('charge-hidden-inputs');
                                                    if (selectedContainer) selectedContainer.innerHTML = '';
                                                    if (hiddenContainer) hiddenContainer.innerHTML = '';
                                                } else {
                                                    paymentSection.style.display = 'block';
                                                }
                                            }
                                            togglePaymentSection();
                                            focCheckbox.addEventListener('change', togglePaymentSection);
                                        }

                                        // Multi-Select Charges
                                        const charges = @json($charges);
                                        setupChargeMultiSelect('charge', charges);

                                        function setupChargeMultiSelect(fieldId, charges) {
                                            const input = document.getElementById(fieldId);
                                            const dropdown = document.getElementById(fieldId + '-dropdown');
                                            const selectedContainer = document.getElementById(fieldId + '-selected');
                                            const hiddenContainer = document.getElementById(fieldId + '-hidden-inputs');
                                            const selectedItems = [];

                                            // Show all charges initially on focus
                                            input.addEventListener('focus', () => {
                                                showChargeSuggestions(input, dropdown, charges, selectedItems);
                                            });

                                            input.addEventListener('input', function() {
                                                showChargeSuggestions(input, dropdown, charges, selectedItems, this.value.toLowerCase());
                                            });

                                            function showChargeSuggestions(input, dropdown, charges, selected, filter = '') {
                                                dropdown.innerHTML = '';
                                                const filtered = charges.filter(c =>
                                                    c.charges_name.toLowerCase().includes(filter) && !selected.includes(c.charges_name)
                                                );

                                                if (filtered.length > 0) {
                                                    filtered.forEach(charge => {
                                                        const item = document.createElement('div');
                                                        item.className = 'autocomplete-item';
                                                        item.textContent = `${charge.charges_name} - ₹${charge.charges_price}`;
                                                        item.onclick = () => {
                                                            addChargeItem(charge);
                                                            input.value = '';
                                                            dropdown.style.display = 'none';
                                                        };
                                                        dropdown.appendChild(item);
                                                    });
                                                    dropdown.style.display = 'block';
                                                } else {
                                                    dropdown.style.display = 'none';
                                                }
                                            }

                                            function addChargeItem(charge) {
                                                if (!selectedItems.includes(charge.charges_name)) {
                                                    selectedItems.push(charge.charges_name);

                                                    const tag = document.createElement('div');
                                                    tag.className = 'selected-item';
                                                    tag.innerHTML = `
                                                        ${charge.charges_name} - ₹${charge.charges_price}
                                                        <button type="button" class="remove-item">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    `;
                                                    tag.querySelector('.remove-item').addEventListener('click', function() {
                                                        removeChargeItem(charge);
                                                    });
                                                    selectedContainer.appendChild(tag);

                                                    const hidden = document.createElement('input');
                                                    hidden.type = 'hidden';
                                                    hidden.name = 'charge_id[]';
                                                    hidden.value = charge.id;
                                                    hidden.id = `charge-hidden-${charge.id}`;
                                                    hidden.className = 'charge-hidden';
                                                    hidden.setAttribute('data-price', charge.charges_price);
                                                    hiddenContainer.appendChild(hidden);

                                                    calculateTotal();
                                                }
                                            }

                                            function removeChargeItem(charge) {
                                                const index = selectedItems.indexOf(charge.charges_name);
                                                if (index > -1) selectedItems.splice(index, 1);

                                                const tags = selectedContainer.querySelectorAll('.selected-item');
                                                tags.forEach(t => {
                                                    if (t.textContent.includes(charge.charges_name)) t.remove();
                                                });

                                                const hidden = document.getElementById(`charge-hidden-${charge.id}`);
                                                if (hidden) hidden.remove();

                                                calculateTotal();
                                            }

                                            // Close dropdown on outside click
                                            document.addEventListener('click', (e) => {
                                                if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                                                    dropdown.style.display = 'none';
                                                }
                                            });

                                            // Initial population from hidden inputs (on edit)
                                            hiddenContainer.querySelectorAll('.charge-hidden').forEach(h => {
                                                const name = h.getAttribute('data-name');
                                                if (name) {
                                                    const c = charges.find(ch => ch.charges_name === name);
                                                    if (c) addChargeItem(c);
                                                }
                                            });
                                        }

                                        // Custom Charge Logic
                                        const addCustomBtn = document.getElementById('add-custom-charge');
                                        if (addCustomBtn) {
                                            addCustomBtn.addEventListener('click', async function () {
                                                const { value: formValues } = await Swal.fire({
                                                    title: 'Add Medication Charge',
                                                    html:
                                                        '<input id="swal-input1" class="swal2-input" placeholder="Charge Name (e.g. Medication)">' +
                                                        '<input id="swal-input2" type="number" class="swal2-input" placeholder="Amount">',
                                                    focusConfirm: false,
                                                    showCancelButton: true,
                                                    preConfirm: () => {
                                                        const name = document.getElementById('swal-input1').value;
                                                        const price = document.getElementById('swal-input2').value;
                                                        if (!name || !price) {
                                                            Swal.showValidationMessage('Please enter both name and amount');
                                                            return false;
                                                        }
                                                        return { name, price };
                                                    }
                                                });

                                                if (formValues) {
                                                    addCustomItem(formValues.name, formValues.price);
                                                }
                                            });
                                        }

                                        function addCustomItem(name, price) {
                                            const id = 'custom-' + Date.now();
                                            const selectedContainer = document.getElementById('charge-selected');
                                            const hiddenContainer = document.getElementById('charge-hidden-inputs');

                                            const tag = document.createElement('div');
                                            tag.className = 'selected-item';
                                            tag.innerHTML = `
                                                ${name} - ₹${price}
                                                <button type="button" class="remove-item">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            `;
                                            tag.querySelector('.remove-item').addEventListener('click', function () {
                                                tag.remove();
                                                const hiddenName = document.getElementById(`custom-name-${id}`);
                                                const hiddenPrice = document.getElementById(`custom-price-${id}`);
                                                if (hiddenName) hiddenName.remove();
                                                if (hiddenPrice) hiddenPrice.remove();
                                                calculateTotal();
                                            });
                                            selectedContainer.appendChild(tag);

                                            const hiddenName = document.createElement('input');
                                            hiddenName.type = 'hidden';
                                            hiddenName.name = 'custom_charge_name[]';
                                            hiddenName.value = name;
                                            hiddenName.id = `custom-name-${id}`;
                                            hiddenContainer.appendChild(hiddenName);

                                            const hiddenPrice = document.createElement('input');
                                            hiddenPrice.type = 'hidden';
                                            hiddenPrice.name = 'custom_charge_price[]';
                                            hiddenPrice.value = price;
                                            hiddenPrice.id = `custom-price-${id}`;
                                            hiddenPrice.className = 'charge-hidden';
                                            hiddenPrice.setAttribute('data-price', price);
                                            hiddenContainer.appendChild(hiddenPrice);

                                            calculateTotal();
                                        }

                                        function calculateTotal() {
                                            let total = 0;
                                            document.querySelectorAll('.charge-hidden').forEach(input => {
                                                total += parseFloat(input.getAttribute('data-price')) || 0;
                                            });

                                            const totalPaymentInput = document.getElementById('total_payment');
                                            const discountPaymentInput = document.getElementById('discount_payment');
                                            const givenPaymentInput = document.getElementById('given_payment');
                                            const duePaymentInput = document.getElementById('due_payment');

                                            const discount = parseFloat(discountPaymentInput?.value) || 0;
                                            totalPaymentInput.value = total.toFixed(2);
                                            
                                            // Auto-fill paid amount if empty
                                            if (!givenPaymentInput.value || givenPaymentInput.value == 0) {
                                                givenPaymentInput.value = (total - discount).toFixed(2);
                                            }
                                            
                                            calculateDuePayment();
                                        }

                                        function calculateDuePayment() {
                                            const totalPaymentInput = document.getElementById('total_payment');
                                            const discountPaymentInput = document.getElementById('discount_payment');
                                            const givenPaymentInput = document.getElementById('given_payment');
                                            const duePaymentInput = document.getElementById('due_payment');

                                            const total = parseFloat(totalPaymentInput?.value) || 0;
                                            const discount = parseFloat(discountPaymentInput?.value) || 0;
                                            const given = parseFloat(givenPaymentInput?.value) || 0;
                                            
                                            duePaymentInput.value = (total - discount - given).toFixed(2);
                                        }

                                        document.getElementById('discount_payment')?.addEventListener('input', calculateDuePayment);
                                        document.getElementById('given_payment')?.addEventListener('input', calculateDuePayment);

                                        // Initial calculation
                                        calculateTotal();
                                    });
                                </script>




                                        <!-- Form Actions -->
                                        <div class="form-actions">
                                            <a href="/search-svc-patient" class="btn btn-secondary">Cancel</a>
                                            <button type="submit" class="btn btn-primary">Save Inquiry</button>
                                        </div>
                                </form>
                            </div>
                        </section>  
                    </div>
                </div>
            </div>
        </div>
    </div>


    </div>
    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyModalLabel">
                        Follow-up History - <span id="modalDate"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="historyContent">
                        <!-- History content will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

        // FOC checkbox handler - only if element exists
        const focElement = document.getElementById('foc');
        if (focElement) {
            focElement.addEventListener('change', function() {
                const section = document.getElementById('payment_section');
                if (section) {
                    if (this.checked) {
                        section.style.display = "none";
                    } else {
                        section.style.display = "block";
                    }
                }
            });
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

        function toggleInsideSection(element) {
            const section = document.getElementById('inside-section');
            const icon = element.querySelector('i');
            if (section.style.display === 'none') {
                section.style.display = 'block';
                icon.classList.replace('bi-plus-lg', 'bi-dash-lg');
            } else {
                section.style.display = 'none';
                icon.classList.replace('bi-dash-lg', 'bi-plus-lg');
            }
        }

        function toggleHomeopathicSection(element) {
            const section = document.getElementById('homeo-section');
            const icon = element.querySelector('i');
            if (section.style.display === 'none') {
                section.style.display = 'block';
                icon.classList.replace('bi-plus-lg', 'bi-dash-lg');
            } else {
                section.style.display = 'none';
                icon.classList.replace('bi-dash-lg', 'bi-plus-lg');
            }
        }

        function togglePrescriptionSection(element) {
            const section = document.getElementById('prescription-section');
            const icon = element.querySelector('i');
            if (section.style.display === 'none') {
                section.style.display = 'block';
                icon.classList.replace('bi-plus-lg', 'bi-dash-lg');
            } else {
                section.style.display = 'none';
                icon.classList.replace('bi-dash-lg', 'bi-plus-lg');
            }
        }

        function toggleIndoorSection(element) {
            const section = document.getElementById('indoor-section');
            const icon = element.querySelector('i');
            if (section.style.display === 'none') {
                section.style.display = 'block';
                icon.classList.replace('bi-plus-lg', 'bi-dash-lg');
            } else {
                section.style.display = 'none';
                icon.classList.replace('bi-dash-lg', 'bi-plus-lg');
            }
        }

        function toggleOtherSection(element) {
            const section = document.getElementById('other-section');
            const icon = element.querySelector('i');
            if (section.style.display === 'none') {
                section.style.display = 'block';
                icon.classList.replace('bi-plus-lg', 'bi-dash-lg');
            } else {
                section.style.display = 'none';
                icon.classList.replace('bi-dash-lg', 'bi-plus-lg');
            }
        }

        // Global Medicine & Dose Suggestion Cache
        window.cachedMedicines = [];
        window.cachedMedicineDoses = {};
        window.cachedDoses = [];

        function loadMedicineSuggestions() {
            // Dynamically wrap all existing medicine inputs that aren't wrapped yet
            const medicineNames = [
                'inside_medicine[]',
                'prescription_medicine[]',
                'homeo_medicine[]',
                'indoor_medicine[]',
                'other_medicine[]'
            ];

            medicineNames.forEach(name => {
                document.querySelectorAll(`input[name="${name}"]`).forEach(input => {
                    input.classList.add('medicine-input');
                    let parent = input.parentElement;
                    if (parent && !parent.classList.contains('autocomplete-container')) {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'autocomplete-container';
                        parent.replaceChild(wrapper, input);
                        wrapper.appendChild(input);
                        
                        const dropdown = document.createElement('div');
                        dropdown.className = 'autocomplete-dropdown';
                        wrapper.appendChild(dropdown);
                    }
                });
            });

            fetch('/get-medicine-suggestions')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.cachedMedicines = data.medicines || [];
                        window.cachedMedicineDoses = data.medicine_doses || {};
                        window.cachedDoses = data.doses || [];
                        
                        // Initialize medicine autocomplete on all medicine inputs
                        document.querySelectorAll('.medicine-input').forEach(input => {
                            const dropdown = input.nextElementSibling;
                            if (dropdown && dropdown.classList.contains('autocomplete-dropdown')) {
                                setupMedicineAutocomplete(input, dropdown);
                            }
                        });
                    }
                })
                .catch(err => console.error("Error loading medicine suggestions:", err));
        }

        function addInsideRow() {
            const tbody = document.getElementById('inside-treatment-body');
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <div class="autocomplete-container">
                        <input type="text" name="inside_medicine[]" class="form-control form-control-sm medicine-input" placeholder="Medicine name" autocomplete="off">
                        <div class="autocomplete-dropdown"></div>
                    </div>
                </td>
                <td>
                    <div class="autocomplete-container">
                        <input type="text" name="inside_dose[]" class="form-control form-control-sm dose-input" placeholder="Dose" autocomplete="off">
                        <div class="autocomplete-dropdown"></div>
                    </div>
                </td>
                <td><input type="text" name="inside_days[]" class="form-control form-control-sm" placeholder="Days"></td>
                <td>
                    <select name="inside_timing[]" class="form-select form-select-sm">
                        <option value="">Select</option>
                        <option>Before Food</option>
                        <option>After Food</option>
                        <option>With Food</option>
                    </select>
                </td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()"><i class="bi bi-trash"></i></button></td>
            `;
            tbody.appendChild(tr);
            setupDoseAutocomplete(tr.querySelector('.dose-input'), tr.querySelector('.autocomplete-dropdown'));
            setupMedicineAutocomplete(tr.querySelector('.medicine-input'), tr.querySelector('.autocomplete-dropdown'));
        }

        function addHomeoRow() {
            const tbody = document.getElementById('homeo-treatment-body');
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <div class="autocomplete-container">
                        <input type="text" name="homeo_medicine[]" class="form-control form-control-sm medicine-input" placeholder="Medicine name" autocomplete="off">
                        <div class="autocomplete-dropdown"></div>
                    </div>
                </td>
                <td>
                    <div class="autocomplete-container">
                        <input type="text" name="homeo_dose[]" class="form-control form-control-sm dose-input" placeholder="Dose" autocomplete="off">
                        <div class="autocomplete-dropdown"></div>
                    </div>
                </td>
                <td><input type="text" name="homeo_days[]" class="form-control form-control-sm" placeholder="Days"></td>
                <td>
                    <select name="homeo_timing[]" class="form-select form-select-sm">
                        <option value="">Select</option>
                        <option>Before Food</option>
                        <option>After Food</option>
                        <option>With Food</option>
                    </select>
                </td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()"><i class="bi bi-trash"></i></button></td>
            `;
            tbody.appendChild(tr);
            setupDoseAutocomplete(tr.querySelector('.dose-input'), tr.querySelector('.autocomplete-dropdown'));
            setupMedicineAutocomplete(tr.querySelector('.medicine-input'), tr.querySelector('.autocomplete-dropdown'));
        }

        function addPrescriptionRow() {
            const tbody = document.getElementById('prescription-treatment-body');
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <div class="autocomplete-container">
                        <input type="text" name="prescription_medicine[]" class="form-control form-control-sm medicine-input" placeholder="Medicine name" autocomplete="off">
                        <div class="autocomplete-dropdown"></div>
                    </div>
                </td>
                <td>
                    <div class="autocomplete-container">
                        <input type="text" name="prescription_dose[]" class="form-control form-control-sm dose-input" placeholder="Dose" autocomplete="off">
                        <div class="autocomplete-dropdown"></div>
                    </div>
                </td>
                <td><input type="text" name="prescription_days[]" class="form-control form-control-sm" placeholder="Days"></td>
                <td>
                    <select name="prescription_timing[]" class="form-select form-select-sm">
                        <option value="">Select</option>
                        <option>Before Food</option>
                        <option>After Food</option>
                        <option>With Food</option>
                    </select>
                </td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()"><i class="bi bi-trash"></i> </button></td>
            `;
            tbody.appendChild(tr);
            setupDoseAutocomplete(tr.querySelector('.dose-input'), tr.querySelector('.autocomplete-dropdown'));
            setupMedicineAutocomplete(tr.querySelector('.medicine-input'), tr.querySelector('.autocomplete-dropdown'));
        }

        function addMedicineRow() {
            const tbody = document.getElementById('indoor-treatment-body');
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <div class="autocomplete-container">
                        <input type="text" name="indoor_medicine[]" class="form-control form-control-sm medicine-input" placeholder="Medicine name" autocomplete="off">
                        <div class="autocomplete-dropdown"></div>
                    </div>
                </td>
                <td>
                    <div class="autocomplete-container">
                        <input type="text" name="indoor_dose[]" class="form-control form-control-sm dose-input" placeholder="Dose" autocomplete="off">
                        <div class="autocomplete-dropdown"></div>
                    </div>
                </td>
                <td><input type="text" name="indoor_days[]" class="form-control form-control-sm" placeholder="Days"></td>
                <td><input type="date" name="indoor_date[]" class="form-control form-control-sm"></td>
                <td><input type="time" name="indoor_time[]" class="form-control form-control-sm"></td>
                <td><input type="text" name="indoor_note[]" class="form-control form-control-sm" placeholder="Note"></td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()"><i class="bi bi-trash"></i></button></td>
            `;
            tbody.appendChild(tr);
            setupDoseAutocomplete(tr.querySelector('.dose-input'), tr.querySelector('.autocomplete-dropdown'));
            setupMedicineAutocomplete(tr.querySelector('.medicine-input'), tr.querySelector('.autocomplete-dropdown'));
        }

        function setupMedicineAutocomplete(input, dropdown) {
            if (!input || !dropdown) return;
            let selectedIndex = -1;

            function showSuggestions(filter) {
                dropdown.innerHTML = '';
                selectedIndex = -1;

                const filtered = window.cachedMedicines.filter(s =>
                    s.toLowerCase().includes(filter.toLowerCase())
                );

                if (filtered.length > 0) {
                    filtered.forEach((suggestion, index) => {
                        const item = document.createElement('div');
                        item.className = 'autocomplete-item';
                        item.textContent = suggestion;
                        item.addEventListener('mousedown', (e) => {
                            e.preventDefault();
                            selectItem(suggestion);
                        });
                        dropdown.appendChild(item);
                    });
                    dropdown.classList.add('show');
                } else {
                    dropdown.classList.remove('show');
                }
            }

            function selectItem(value) {
                input.value = value;
                dropdown.classList.remove('show');
                selectedIndex = -1;

                // Auto-fill dose if present in cache
                if (window.cachedMedicineDoses && window.cachedMedicineDoses[value]) {
                    const row = input.closest('tr');
                    if (row) {
                        const doseInput = row.querySelector('.dose-input');
                        if (doseInput) {
                            doseInput.value = window.cachedMedicineDoses[value];
                        }
                    }
                }
            }

            function updateSelection(items) {
                items.forEach((item, index) => {
                    if (index === selectedIndex) {
                        item.classList.add('selected');
                        item.scrollIntoView({ block: 'nearest' });
                    } else {
                        item.classList.remove('selected');
                    }
                });
            }

            input.addEventListener('focus', () => showSuggestions(input.value));
            input.addEventListener('input', (e) => showSuggestions(e.target.value));

            input.addEventListener('blur', () => {
                const val = input.value.trim();
                if (window.cachedMedicineDoses && window.cachedMedicineDoses[val]) {
                    const row = input.closest('tr');
                    if (row) {
                        const doseInput = row.querySelector('.dose-input');
                        if (doseInput && !doseInput.value) {
                            doseInput.value = window.cachedMedicineDoses[val];
                        }
                    }
                }
            });

            input.addEventListener('keydown', (e) => {
                const items = dropdown.querySelectorAll('.autocomplete-item');
                if (!dropdown.classList.contains('show') || items.length === 0) {
                    if (e.key === 'ArrowDown') showSuggestions(input.value);
                    return;
                }

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    selectedIndex = (selectedIndex + 1) % items.length;
                    updateSelection(items);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    selectedIndex = (selectedIndex - 1 + items.length) % items.length;
                    updateSelection(items);
                } else if (e.key === 'Enter') {
                    if (selectedIndex >= 0) {
                        e.preventDefault();
                        selectItem(items[selectedIndex].textContent);
                    }
                } else if (e.key === 'Escape') {
                    dropdown.classList.remove('show');
                }
            });

            document.addEventListener('click', (e) => {
                if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('show');
                }
            });
        }

        function setupDoseAutocomplete(input, dropdown) {
            if (!input || !dropdown) return;

            const defaultSuggestions = [
                "1 – 0 – 0", "0 – 0 – 1", "1 – 0 – 1", "1 – 1 – 1",
                "1/2 – 0 – 1/2", "1/2 – 1/2 – 1/2"
            ];

            let selectedIndex = -1;

            function showSuggestions(filter) {
                dropdown.innerHTML = '';
                selectedIndex = -1;

                let allSuggestions = [...defaultSuggestions];
                if (window.cachedDoses && window.cachedDoses.length > 0) {
                    window.cachedDoses.forEach(d => {
                        if (!allSuggestions.includes(d)) {
                            allSuggestions.push(d);
                        }
                    });
                }

                const filtered = allSuggestions.filter(s =>
                    s.toLowerCase().includes(filter.toLowerCase())
                );

                if (filtered.length > 0) {
                    filtered.forEach((suggestion, index) => {
                        const item = document.createElement('div');
                        item.className = 'autocomplete-item';
                        item.textContent = suggestion;
                        item.addEventListener('click', () => {
                            selectItem(suggestion);
                        });
                        dropdown.appendChild(item);
                    });
                    dropdown.classList.add('show');
                } else {
                    dropdown.classList.remove('show');
                }
            }

            function selectItem(value) {
                input.value = value;
                dropdown.classList.remove('show');
                selectedIndex = -1;
            }

            function updateSelection(items) {
                items.forEach((item, index) => {
                    if (index === selectedIndex) {
                        item.classList.add('selected');
                        item.scrollIntoView({ block: 'nearest' });
                    } else {
                        item.classList.remove('selected');
                    }
                });
            }

            input.addEventListener('focus', () => showSuggestions(input.value));
            input.addEventListener('input', (e) => showSuggestions(e.target.value));

            input.addEventListener('keydown', (e) => {
                const items = dropdown.querySelectorAll('.autocomplete-item');
                if (!dropdown.classList.contains('show') || items.length === 0) {
                    if (e.key === 'ArrowDown') showSuggestions(input.value);
                    return;
                }

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    selectedIndex = (selectedIndex + 1) % items.length;
                    updateSelection(items);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    selectedIndex = (selectedIndex - 1 + items.length) % items.length;
                    updateSelection(items);
                } else if (e.key === 'Enter') {
                    if (selectedIndex >= 0) {
                        e.preventDefault();
                        selectItem(items[selectedIndex].textContent);
                    }
                } else if (e.key === 'Escape') {
                    dropdown.classList.remove('show');
                }
            });

            document.addEventListener('click', (e) => {
                if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('show');
                }
            });
        }

        // Initialize autocomplete for all existing dose inputs
        document.addEventListener('DOMContentLoaded', function() {
            // Parse initial age on page load
            const rawAge = document.getElementById('age').value;
            const parsedAge = parseAgeString(rawAge);
            document.getElementById('age_years').value = parsedAge.years;
            document.getElementById('age_months').value = parsedAge.months;

            document.querySelectorAll('.autocomplete-container').forEach(container => {
                const input = container.querySelector('.dose-input');
                const dropdown = container.querySelector('.autocomplete-dropdown');
                if (input && dropdown) {
                    setupDoseAutocomplete(input, dropdown);
                }
            });
        });

        function openHistoryModal(selectedDate) {
            document.getElementById('modalDate').textContent = formatDate(selectedDate);

            document.getElementById('historyContent').innerHTML = `
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Loading history data...</p>
                </div>
            `;

            fetch(`/patient/{{ $patient->patient_id }}/followup-history?date=${selectedDate}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('historyContent').innerHTML = data.html;
                    } else {
                        document.getElementById('historyContent').innerHTML = `
                            <div class="alert alert-warning">
                                No history data found for this date.
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('historyContent').innerHTML = `
                        <div class="alert alert-danger">
                            Error loading history data.
                        </div>
                    `;
                });

            const historyModal = new bootstrap.Modal(document.getElementById('historyModal'));
            historyModal.show();
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        // In your JavaScript section
function formatTimeFromMeta(timeValue) {
    if (!timeValue) return 'N/A';
    // Assuming timeValue is in HH:MM:SS format
    const [hours, minutes] = timeValue.split(':');
    const date = new Date();
    date.setHours(hours, minutes);
    return date.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit'
    });
}
    </script>
    <script>
        // Load times for selected date
        function loadTimesForDate(date) {
            if (!date) {
                document.getElementById('time-selector-container').innerHTML = `
                    <label for="time">Select Time:</label>
                    <select name="time" id="time" class="form-select" disabled>
                        <option value="">Select date first</option>
                    </select>
                `;
                return;
            }
            
            // Fetch times for the selected date
            fetch(`/api/patient/{{ $patient->patient_id }}/followup-times?date=${date}`)
                .then(response => response.json())
                .then(data => {
                    let timeSelect = `
                        <label for="time">Select Time:</label>
                        <select name="time" id="time" class="form-select" onchange="this.form.submit()">
                            <option value="">All Times</option>`;
                    
                    if (data.times && data.times.length > 0) {
                        data.times.forEach(time => {
                            timeSelect += `<option value="${time.time}">${time.formatted}</option>`;
                        });
                    } else {
                        timeSelect += `<option value="">No visits on this date</option>`;
                    }
                    
                    timeSelect += `</select>`;
                    
                    document.getElementById('time-selector-container').innerHTML = timeSelect;
                })
                .catch(error => {
                    console.error('Error loading times:', error);
                });
        }
        
        // Open history modal with date and time
        function openHistoryModal(selectedDate, selectedTime) {
            if (!selectedDate) {
                alert('Please select a date first');
                return;
            }
            
            document.getElementById('modalDate').textContent = formatDate(selectedDate);
            if (selectedTime) {
                document.getElementById('modalDate').textContent += ' at ' + formatTime(selectedTime);
            }
        
            document.getElementById('historyContent').innerHTML = `
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Loading history data...</p>
                </div>
            `;
        
            let url = `/patient/{{ $patient->patient_id }}/followup-history?date=${selectedDate}`;
            if (selectedTime) {
                url += `&time=${selectedTime}`;
            }
        
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('historyContent').innerHTML = data.html;
                        // Update modal title with visit count
                        if (data.count > 1) {
                            document.getElementById('modalDate').textContent += ` (${data.count} visits)`;
                        }
                    } else {
                        document.getElementById('historyContent').innerHTML = data.html || `
                            <div class="alert alert-warning">
                                No history data found for this date.
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('historyContent').innerHTML = `
                        <div class="alert alert-danger">
                            Error loading history data. Please try again.
                        </div>
                    `;
                });
        
            const historyModal = new bootstrap.Modal(document.getElementById('historyModal'));
            historyModal.show();
        }
        
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
        
        function formatTime(timeString) {
            return new Date('2000-01-01T' + timeString).toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }
        
        // Function to load single visit details (called from history modal)
        function loadSingleVisit(followupId) {
            // Show loading in the history content area
            const historyContent = document.getElementById('historyContent');
            if (historyContent) {
                historyContent.innerHTML = `
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p>Loading full history...</p>
                    </div>
                `;
            }
            
            fetch(`/followup/${followupId}/full-details`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && historyContent) {
                        historyContent.innerHTML = data.html;
                    } else {
                        historyContent.innerHTML = `
                            <div class="alert alert-danger">
                                Error loading full history. Please try again.
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (historyContent) {
                        historyContent.innerHTML = `
                            <div class="alert alert-danger">
                                Error loading full history. Please try again.
                            </div>
                        `;
                    }
                });
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Set today's date if not already set
            const today = new Date().toISOString().split('T')[0];
            const now = new Date().toTimeString().split(' ')[0].substring(0, 5);
            
            const followDateInput = document.getElementById('follow_date');
            if (followDateInput && !followDateInput.value) {
                followDateInput.value = today;
            }
            
            const followTimeInput = document.getElementById('followups_time');
            if (followTimeInput && !followTimeInput.value) {
                followTimeInput.value = now;
            }

            // Initialize multi-select for diagnosis
            loadSuggestions();
        });

        let suggestionsData = {
            complaints: [],
            diagnoses: []
        };
        
        // Load suggestions from API and then initialize autocomplete
        function loadSuggestions() {
            console.log('Loading suggestions for follow-up...');
            if (typeof loadMedicineSuggestions === 'function') {
                loadMedicineSuggestions();
            }
            
            // Get CSRF token from meta tag
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            fetch('/get-suggestions', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Suggestions data received:', data);
                    
                    if (data.complaints) suggestionsData.complaints = data.complaints;
                    if (data.diagnoses) suggestionsData.diagnoses = data.diagnoses;
                    
                    // Initialize autocomplete with data
                    initAutocomplete();
                })
                .catch(error => {
                    console.error('Error loading suggestions:', error);
                    // Fallback to empty if API fails
                    initAutocomplete();
                });
        }
        
        function initAutocomplete() {
            console.log('Initializing multi-select fields...');
            setupMultiSelect('diagnosis', suggestionsData.diagnoses);
            setupMultiSelect('complain', suggestionsData.complaints);
            
            const allCharges = @json($charges);
            setupChargeMultiSelect('charge', allCharges);
        }

        function setupChargeMultiSelect(fieldId, charges) {
            const input = document.getElementById(fieldId);
            const dropdown = document.getElementById(fieldId + '-dropdown');
            const selectedContainer = document.getElementById(fieldId + '-selected');
            const hiddenContainer = document.getElementById(fieldId + '-hidden-inputs');
            const totalPaymentHidden = document.getElementById('total_payment');
            const givenPaymentInput = document.getElementById('given_payment');
            const duePaymentInput = document.getElementById('due_payment');
            
            let selectedItems = []; 
            
            if (!input || !dropdown || !selectedContainer || !hiddenContainer) return;

            function calculateTotal() {
                let total = 0;
                document.querySelectorAll('.charge-hidden').forEach(h => {
                    total += parseFloat(h.getAttribute('data-price')) || 0;
                });
                if (totalPaymentHidden) totalPaymentHidden.value = total.toFixed(2);
                
                const given = parseFloat(givenPaymentInput?.value) || 0;
                if (duePaymentInput) duePaymentInput.value = (total - given).toFixed(2);
            }

            if (givenPaymentInput) {
                givenPaymentInput.addEventListener('input', calculateTotal);
            }
            
            input.addEventListener('focus', function() {
                showChargeSuggestions(input, dropdown, charges, selectedItems);
            });
            
            input.addEventListener('input', function() {
                showChargeSuggestions(input, dropdown, charges, selectedItems, this.value.toLowerCase());
            });
            
            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('show');
                }
            });

            function showChargeSuggestions(input, dropdown, charges, selected, filter = '') {
                dropdown.innerHTML = '';
                const filtered = charges.filter(c => 
                    c.charges_name.toLowerCase().includes(filter) && !selected.includes(c.charges_name)
                );
                
                if (filtered.length > 0) {
                    filtered.forEach(charge => {
                        const item = document.createElement('div');
                        item.className = 'autocomplete-item';
                        item.textContent = `${charge.charges_name} - ₹${charge.charges_price}`;
                        item.addEventListener('click', function() {
                            addItem(charge);
                            input.value = '';
                            dropdown.classList.remove('show');
                        });
                        dropdown.appendChild(item);
                    });
                    dropdown.classList.add('show');
                } else {
                    dropdown.classList.remove('show');
                }
            }

            function addItem(charge) {
                if (!selectedItems.includes(charge.charges_name)) {
                    selectedItems.push(charge.charges_name);
                    
                    const tag = document.createElement('div');
                    tag.className = 'selected-item';
                    tag.innerHTML = `
                        ${charge.charges_name} - ₹${charge.charges_price}
                        <button type="button" class="remove-item">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    tag.querySelector('.remove-item').addEventListener('click', function() {
                        removeItem(charge);
                    });
                    selectedContainer.appendChild(tag);
                    
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'charge_id[]';
                    hidden.value = charge.id;
                    hidden.id = `charge-hidden-${charge.id}`;
                    hidden.className = 'charge-hidden';
                    hidden.setAttribute('data-price', charge.charges_price);
                    hiddenContainer.appendChild(hidden);
                    
                    calculateTotal();
                }
            }

            function removeItem(charge) {
                const index = selectedItems.indexOf(charge.charges_name);
                if (index > -1) {
                    selectedItems.splice(index, 1);
                    
                    const tags = selectedContainer.querySelectorAll('.selected-item');
                    tags.forEach(t => {
                        if (t.textContent.includes(charge.charges_name)) t.remove();
                    });
                    
                    const hidden = document.getElementById(`charge-hidden-${charge.id}`);
                    if (hidden) hidden.remove();
                    
                    calculateTotal();
                }
            }

            // Initialize from existing hidden inputs
            const initialHidden = hiddenContainer.querySelectorAll('.charge-hidden');
            initialHidden.forEach(h => {
                const chargeId = h.value;
                const charge = charges.find(c => c.id == chargeId);
                if (charge) {
                    selectedItems.push(charge.charges_name);
                    const tag = document.createElement('div');
                    tag.className = 'selected-item';
                    tag.innerHTML = `
                        ${charge.charges_name} - ₹${charge.charges_price}
                        <button type="button" class="remove-item">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    tag.querySelector('.remove-item').addEventListener('click', function() {
                        removeItem(charge);
                    });
                    selectedContainer.appendChild(tag);
                    if (!h.id) h.id = `charge-hidden-${charge.id}`;
                }
            });
        }
        
        function setupMultiSelect(fieldId, suggestions) {
            const input = document.getElementById(fieldId);
            const dropdown = document.getElementById(fieldId + '-dropdown');
            const selectedContainer = document.getElementById(fieldId + '-selected');
            const hiddenInput = document.getElementById(fieldId + '-hidden');
            
            if (!input || !dropdown || !selectedContainer || !hiddenInput) {
                console.error(`Missing elements for ${fieldId} multi-select`);
                return;
            }

            let selectedItems = [];
            let selectedIndex = -1;
            
            // Initial population from hidden input
            if (hiddenInput.value) {
                const initialValues = hiddenInput.value.split(',').map(v => v.trim()).filter(v => v);
                initialValues.forEach(val => addItem(val));
            }
            
            // Show suggestions when input gets focus
            input.addEventListener('focus', function() {
                showMultiSelectSuggestions(input, dropdown, suggestions, selectedItems);
            });
            
            // Filter suggestions on input
            input.addEventListener('input', function() {
                const value = this.value.toLowerCase();
                showMultiSelectSuggestions(input, dropdown, suggestions, selectedItems, value);
            });
            
            // Keyboard navigation
            input.addEventListener('keydown', function(e) {
                const items = dropdown.querySelectorAll('.autocomplete-item:not(.no-results)');
                
                switch(e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                        updateBtnSelection(items, selectedIndex);
                        break;
                        
                    case 'ArrowUp':
                        e.preventDefault();
                        selectedIndex = Math.max(selectedIndex - 1, -1);
                        updateBtnSelection(items, selectedIndex);
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
                }
            });
            
            // Hide dropdown when clicking outside
            document.addEventListener('click', function(e) {
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
                selectedItems.forEach(item => {
                    const itemElement = document.createElement('div');
                    itemElement.className = 'selected-item';
                    itemElement.innerHTML = `
                        ${item}
                        <button type="button" class="remove-item" data-item="${item}">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    
                    itemElement.querySelector('.remove-item').addEventListener('click', function(e) {
                        e.preventDefault();
                        removeItem(item);
                    });
                    
                    selectedContainer.appendChild(itemElement);
                });
            }
            
            function updateHiddenInput() {
                hiddenInput.value = selectedItems.join(', ');
            }
            
            // Store functions in the input element
            input.multiSelect = {
                addItem: addItem,
                removeItem: removeItem,
                selectedItems: () => selectedItems
            };
        }
        
        function showMultiSelectSuggestions(input, dropdown, suggestions, selectedItems, filter = '') {
            dropdown.innerHTML = '';
            
            const filteredSuggestions = (suggestions || []).filter(suggestion => 
                suggestion.toLowerCase().includes(filter) && !selectedItems.includes(suggestion)
            );
            
            if (filteredSuggestions.length === 0 && filter) {
                const addNew = document.createElement('div');
                addNew.className = 'autocomplete-item add-new';
                addNew.innerHTML = `<i class="fas fa-plus"></i> Add "${filter}"`;
                addNew.addEventListener('click', function() {
                    if (input.multiSelect) input.multiSelect.addItem(filter);
                    input.value = '';
                    dropdown.classList.remove('show');
                });
                dropdown.appendChild(addNew);
            } else {
                filteredSuggestions.forEach(suggestion => {
                    const item = document.createElement('div');
                    item.className = 'autocomplete-item';
                    item.textContent = suggestion;
                    item.addEventListener('click', function() {
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

        function updateBtnSelection(items, selectedIndex) {
            items.forEach((item, index) => {
                if (index === selectedIndex) {
                    item.classList.add('selected');
                } else {
                    item.classList.remove('selected');
                }
            });
        }
    </script>
@endsection

