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
            color: #28a745;
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
            /* margin-left: 10px; */
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

        /* Enhanced form layout for better alignment */
        .pro_filed {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            width: 100%;
            align-items: flex-start;
        }

        .pro_filed .form {
            flex: 1;
            position: relative;
            min-width: 0;
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

        /* Ensure consistent sizing for all form elements in medicine rows */
        .medicine-row .form-col input,
        .medicine-row .form-col select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .medicine-row .form-col {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 200px;
        }

        .medicine-row .form-col label {
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 14px;
            color: #333;
        }

        label {
            font-weight: bold;
            color: #030503;
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #28a745;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
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

        /* Custom Autocomplete Styling */
        .autocomplete-container {
            position: relative;
            width: 100%;
        }

        .autocomplete-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 4px 4px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .autocomplete-dropdown.show {
            display: block;
        }

        .autocomplete-item {
            padding: 10px 12px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
            transition: background-color 0.2s ease;
        }

        .autocomplete-item:last-child {
            border-bottom: none;
        }

        .autocomplete-item:hover {
            background-color: #f8f9fa;
        }

        .autocomplete-item.selected {
            background-color: #067945;
            color: white;
        }

        .autocomplete-item.no-results {
            color: #999;
            font-style: italic;
            cursor: default;
        }

        .autocomplete-item.no-results:hover {
            background-color: transparent;
        }

        /* Enhanced focus state for autocomplete fields */
        .autocomplete-container input:focus {
            border-color: #067945;
            box-shadow: 0 0 0 0.2rem rgba(6, 121, 69, 0.25);
        }

        /* Doctor Dose Container Styling */
        .doctor-dose-container {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        /* Dose Container Styling */
        .dose-container {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .doctor-dose-container select,
        .doctor-dose-container input,
        .dose-container select,
        .dose-container input {
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .doctor-dose-container select:focus,
        .doctor-dose-container input:focus,
        .dose-container select:focus,
        .dose-container input:focus {
            border-color: #067945;
            box-shadow: 0 0 0 0.2rem rgba(6, 121, 69, 0.25);
        }

        .doctor-dose-container select,
        .dose-container select {
            font-size: 0.875rem;
        }

        .doctor-dose-container input::placeholder,
        .dose-container input::placeholder {
            font-size: 0.8rem;
            font-style: italic;
            color: #6c757d;
        }

        /* Auto-suggestion styling */
        input[list] {
            position: relative;
        }

        input[list]::-webkit-calendar-picker-indicator {
            display: none;
        }

        .required::after {
            content: " *";
            color: #e74c3c;
        }

        /*
                                    .section-divider {
                                        border-bottom: 2px solid #1f911f;
                                        margin: 0 0;
                                        padding-top: 15px;
                                        font-weight: bold;
                                        color: #0a3f0a;
                                        font-size: 16px;
                                        padding-bottom: 10px
                                    } */

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
            color: #070606;
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

        /* Specific styling for treatment sections */
        #inside-section .pro_filed,
        #prescription-section .pro_filed,
        #indoor-treatment-container .pro_filed {
            align-items: flex-end;
            gap: 15px;
        }

        #inside-section .form,
        #prescription-section .form,
        #indoor-treatment-container .form {
            flex: 1;
            min-width: 0;
        }

        #inside-section .form-col,
        #prescription-section .form-col,
        #indoor-treatment-container .form-col {
            flex: 1;
            min-width: 180px;
        }

        /* Ensure consistent input heights */
        #inside-section input,
        #inside-section select,
        #prescription-section input,
        #prescription-section select,
        #indoor-treatment-container input,
        #indoor-treatment-container select {
            height: 38px;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
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

        /* Styles for the select with button */
        .select-with-button {
            display: flex;
            align-items: center;
        }

        .select-with-button select {
            flex: 1;
        }

        /* Styles for input with button */
        .input-with-button {
            display: flex;
            align-items: center;
        }

        .input-with-button input {
            flex: 1;
        }

        .timing-container,
        .note-container,
        .medicine-container {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        /* Ensure consistent sizing for all form elements in medicine rows */
        .medicine-row .form-col input,
        .medicine-row .form-col select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .medicine-row .form-col {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 200px;
        }

        .medicine-row .form-col label {
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 14px;
        }

        .fnf-title {
            font-size: 20px;
        }

        .pro_filed .form {
            position: relative;
            width: 100%;
            margin-right: 0 !important;
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
            background-color: #067945;
            color: white;
            padding: 4px 8px;
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

        .treatment-table tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .treatment-table .form-control-sm,
        .treatment-table .form-select-sm {
            font-size: 0.875rem;
            padding: 0.4rem 0.6rem;
            height: auto;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        .treatment-table .btn-sm {
            font-size: 0.8rem;
            padding: 0.3rem 0.6rem;
        }

        .treatment-table .btn-success,
        .treatment-table .btn-danger {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        .table-responsive {
            overflow: visible !important;
        }
    </style>

    <div class="col-md-12 col-lg-10 m-auto p-0">
        <!-- Display success/error messages -->
        @if(session('success'))
            <div id="success-message" style="display: none;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div id="error-message" style="display: none;">{{ session('error') }}</div>
        @endif

        <div class="card rounded shadow mb-5">
            <div class="card-header">
                <h3 class="bold font-up fnf-title text-success">Add SVC Patient Inquiry</h3>
            </div>
            <div class="row">
                <div class="col-md-12 m-auto">
                    <div class="bg-light rounded-5">
                        <section class="w-100 p-4 pb-4">
                            <div class="">
                                <form id="inquiryForm" action="/store-svc-inquiry" method="POST">
                                    @csrf

                                    <!-- Datalist for Dose suggestions removed as we use custom autocomplete -->
                                    <script>
                                        const doseSuggestions = [
                                            "1 – 0 – 0", "0 – 0 – 1", "1 – 0 – 1", "1 – 1 – 1",
                                            "1 – 1 – 0", "0 – 1 – 0", "0 – 1 – 1", "1/2 – 0 – 1/2",
                                            "1/2 – 1/2 – 1/2", "2 – 0 – 0", "0 – 0 – 2", "2 – 0 – 2",
                                            "1 – 1 – 1 – 1"
                                        ];
                                    </script>
                                    @if ($branchId)
                                        <input type="hidden" name="branch_id" value="{{ $branchId }}">
                                        <input type="hidden" name="branch" value="{{ $branchName }}">
                                    @else
                                        <label>Select Branch</label>
                                        <select id="branchName" name="branch_id" required>
                                            <option value=""> Select Branch </option>
                                            @foreach($branches as $b)
                                                <option value="{{ $b->branch_id }}">{{ $b->branch_name }}</option>
                                            @endforeach
                                        </select>

                                        <input type="hidden" name="branch" id="branchHidden" value="">
                                    @endif

                                    <div class="section-divider">
                                        <div class="title">Personal Information & Address</div>
                                        <div class="line"></div>
                                        <div class="icon-box" onclick="toggleGenericSection(this)">
                                            <i class="bi bi-dash-lg"></i>
                                        </div>
                                    </div>
                                    <div class="pt-3">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="patient_name" class="required">Patient Name</label>
                                                    <input type="text" id="patient_name" name="patient_name"
                                                        placeholder="Enter patient name" required class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="gender" class="required">Gender</label>
                                                    <select id="gender" name="gender" required class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="male">Male</option>
                                                        <option value="female">Female</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="required">Age</label>
                                                    <div class="d-flex gap-2">
                                                        <div class="flex-grow-1" style="min-width: 0;">
                                                            <input type="number" id="age_years" placeholder="Years" min="0"
                                                                class="form-control" style="padding: 6px 8px;"
                                                                oninput="updateAgeString()">
                                                        </div>
                                                        <div class="flex-grow-1" style="min-width: 0;">
                                                            <input type="number" id="age_months" placeholder="Months"
                                                                min="0" max="11" class="form-control"
                                                                style="padding: 6px 8px;" oninput="updateAgeString()">
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="age" name="age" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="phone">Phone Number</label>
                                                    <input type="number" id="phone" name="phone" placeholder="Phone number"
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-3 pt-2">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="inquiry_date">Inquiry Date</label>
                                                    <input type="date" id="inquiry_date" name="inquiry_date"
                                                        placeholder="Inquiry Date" class="form-control">
                                                </div>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function () {
                                                        const today = new Intl.DateTimeFormat('en-CA', {
                                                            timeZone: 'Asia/Kolkata'
                                                        }).format(new Date());
                                                        document.getElementById('inquiry_date').value = today;

                                                        const nowTime = new Intl.DateTimeFormat('en-GB', {
                                                            hour: '2-digit',
                                                            minute: '2-digit',
                                                            hour12: false,
                                                            timeZone: 'Asia/Kolkata'
                                                        }).format(new Date());

                                                        const timeInput = document.getElementById('inquiry_time');
                                                        if (timeInput) {
                                                            timeInput.value = nowTime;
                                                        }

                                                        const branchSelect = document.getElementById('branchName');
                                                        const branchHidden = document.getElementById('branchHidden');

                                                        if (branchSelect && branchHidden) {
                                                            branchSelect.addEventListener('change', function () {
                                                                const selectedOption = this.options[this.selectedIndex];
                                                                branchHidden.value = selectedOption.text;
                                                            });
                                                        }
                                                    });
                                                </script>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="inquiry_time">Inquiry Time</label>
                                                    <input type="time" id="inquiry_time" name="inquiry_time" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="address" class="required">Address Details</label>
                                                    <input type="text" id="address" name="address"
                                                        placeholder="Enter complete address" required class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="section-divider mt-4">
                                        <div class="title">Clinical Vitals & Complaints</div>
                                        <div class="line"></div>
                                        <div class="icon-box" onclick="toggleGenericSection(this)">
                                            <i class="bi bi-dash-lg"></i>
                                        </div>
                                    </div>
                                    <div class="pt-3">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
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
                                                        <input type="hidden" name="complain" id="complain-hidden" value="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="diagnosis" class="required">Diagnosis</label>
                                                    <div class="multi-select-container">
                                                        <div class="selected-items" id="diagnosis-selected">
                                                            <!-- Selected diagnoses will appear here -->
                                                        </div>
                                                        <div class="autocomplete-container">
                                                            <input type="text" id="diagnosis"
                                                                placeholder="Type to add diagnoses..." class="form-control"
                                                                autocomplete="off">
                                                            <div class="autocomplete-dropdown" id="diagnosis-dropdown">
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="diagnosis" id="diagnosis-hidden"
                                                            value="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-3 pt-3">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="temperature">Temp (°F / °C)</label>
                                                    <input type="number" step="0.1" min="0" id="temperature"
                                                        name="temperature" placeholder="e.g. 98.6" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="pulse">Pulse (bpm)</label>
                                                    <input type="text" id="pulse" name="pulse" placeholder="e.g. 72"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="blood_pressure">BP (mmHg)</label>
                                                    <input type="text" id="blood_pressure" name="blood_pressure"
                                                        placeholder="e.g. 120/80" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="spo2">SpO2 (%)</label>
                                                    <input type="number" id="spo2" name="spo2" placeholder="e.g. 98"
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="section-divider mt-4">
                                        <div class="title">Other Vitals & Patient Details</div>
                                        <div class="line"></div>
                                        <div class="icon-box" onclick="toggleGenericSection(this)">
                                            <i class="bi bi-dash-lg"></i>
                                        </div>
                                    </div>
                                    <div class="pt-3">
                                        <div class="row g-3">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="height">Height (cm)</label>
                                                    <input type="number" step="0.1" id="height" name="height"
                                                        placeholder="Height" onchange="calculateBMI()" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="weight">Weight (kg)</label>
                                                    <input type="number" step="0.1" id="weight" name="weight"
                                                        placeholder="Weight" onchange="calculateBMI()" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="bmi">BMI</label>
                                                    <input type="number" step="0.1" id="bmi" name="bmi" placeholder="BMI"
                                                        readonly style="background-color: #f8f9fa;" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="pt_status">PT.Status</label>
                                                    <select id="pt_status" name="pt_status" class="form-control">
                                                        <option value="">Select Status</option>
                                                        <option value="IPD">IPD</option>
                                                        <option value="OPD">OPD</option>
                                                        <option value="PNP">PNP</option>
                                                        <option value="Home Visit">Home Visit</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="rbs">RBS</label>
                                                    <input type="text" id="rbs" name="rbs" placeholder="RBS"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="doctor_id">Assign Doctor</label>
                                                    <select name="doctor_id" id="doctor_id" class="form-control">
                                                        <option value="">Select Doctor</option>
                                                        @foreach($doctors as $doctor)
                                                            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                                                {{ $doctor->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="section-divider mt-4">
                                            <div class="title">Medical Information</div>
                                            <div class="line"></div>
                                            <div class="icon-box" onclick="toggleGenericSection(this)">
                                                <i class="bi bi-plus-lg"></i>
                                            </div>
                                        </div>
                                        <div style="display: none;">
                                        <div class="pro_filed d-sm-block d-md-flex pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="investigation">Investigation</label>
                                                    <textarea id="investigation" name="investigation"
                                                        placeholder="Enter investigation details"></textarea>
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="past_history">Past History</label>
                                                    <textarea id="past_history" name="past_history"
                                                        placeholder="Enter past medical history"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pro_filed d-sm-block d-md-flex pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="family_history">Family History</label>
                                                    <textarea id="family_history" name="family_history"
                                                        placeholder="Enter family medical history"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        {{-- <div class="section-divider"></div> --}}
                                        <div class="section-divider mt-4">
                                            <div class="title" style="color: #28a745;">Lipid Profile :</div>
                                            <div class="line"></div>
                                        </div>
                                        <div class="row pt-3">
                                            <div class="col-md-3 mb-3">
                                                <label for="s_cholesterol">S.Cholesterol</label>
                                                <input type="text" id="s_cholesterol" name="s_cholesterol"
                                                    placeholder="Enter S.Cholesterol" class="form-control">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="STriglyceride">S.Triglycerides</label>
                                                <input type="text" id="STriglyceride" name="STriglyceride"
                                                    placeholder="Enter S.Triglycerides" class="form-control">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="HDL">HDL</label>
                                                <input type="text" id="HDL" name="HDL" placeholder="Enter HDL"
                                                    class="form-control">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="LDL">LDL</label>
                                                <input type="text" id="LDL" name="LDL" placeholder="Enter LDL"
                                                    class="form-control">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="VLDL">VLDL</label>
                                                <input type="text" id="VLDL" name="VLDL" placeholder="Enter VLDL"
                                                    class="form-control">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="non_hdl_c">Non-HDL C</label>
                                                <input type="text" id="non_hdl_c" name="non_hdl_c"
                                                    placeholder="Enter Non-HDL C" class="form-control">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="chol_hdl_ratio">Chol/HDL ratio</label>
                                                <input type="text" id="chol_hdl_ratio" name="chol_hdl_ratio"
                                                    placeholder="Enter Chol/HDL ratio" class="form-control">
                                            </div>
                                        </div>

                                        <div class="section-divider mt-4">
                                            <div class="title">Laboratory Investigation</div>
                                            <div class="line"></div>

                                            <div class="icon-box" onclick="toggleLabInvestigation(this)">
                                                <i class="bi bi-plus-lg" id="lab-toggle-icon"></i>
                                            </div>
                                        </div>
                                        <div class="inside-section" id="lab-investigation-container" style="display: none;">
                                            <!-- Maintained ID and display style -->
                                            <div class="row pt-3">
                                                <div class="col-md-3 mb-3">
                                                    <label for="hb">HB</label>
                                                    <input type="text" id="hb" name="hb" placeholder="HB"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="tc">TC</label>
                                                    <input type="text" id="tc" name="tc" placeholder="TC"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="pc">PC</label>
                                                    <input type="text" id="pc" name="pc" placeholder="PC"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="MP">MP</label>
                                                    <input type="text" id="MP" name="MP" placeholder="MP"
                                                        class="form-control">
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label for="HB1AC">HB1AC</label>
                                                    <input type="text" id="HB1AC" name="HB1AC" placeholder="HB1AC"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="fbs">FBS</label>
                                                    <input type="text" id="fbs" name="fbs" placeholder="FBS"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="pp2bs">PP2BS</label>
                                                    <input type="text" id="pp2bs" name="pp2bs" placeholder="PP2BS"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="S.widal">S.widal</label>
                                                    <input type="text" id="S.widal" name="S.widal" placeholder="S.widal"
                                                        class="form-control">
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label for="USG">USG</label>
                                                    <input type="text" id="USG" name="USG" placeholder="USG"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="X-ray">X-ray</label>
                                                    <input type="text" id="X-ray" name="X-ray" placeholder="X-ray"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="SGPT">SGPT</label>
                                                    <input type="text" id="SGPT" name="SGPT" placeholder="SGPT"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="s_creatinine">S. Creatinine</label>
                                                    <input type="text" id="s_creatinine" name="s_creatinine"
                                                        placeholder="S. Creatinine" class="form-control">
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label for="NS1Ag">NS1Ag</label>
                                                    <input type="text" id="NS1Ag" name="NS1Ag" placeholder="NS1Ag"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="DengueIGM">Dengue IGM</label>
                                                    <input type="text" id="DengueIGM" name="DengueIGM"
                                                        placeholder="Dengue IGM" class="form-control">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="Urine">Urine</label>
                                                    <input type="text" id="Urine" name="Urine" placeholder="Urine"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="CRP">CRP</label>
                                                    <input type="text" id="CRP" name="CRP" placeholder="CRP"
                                                        class="form-control">
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label for="SB12">S.B12</label>
                                                    <input type="text" id="SB12" name="SB12" placeholder="S.B12"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="SD3">S.D3</label>
                                                    <input type="text" id="SD3" name="SD3" placeholder="S.D3"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-6 mb-3"></div>

                                                <div class="col-md-3 mb-3">
                                                    <label for="St3">S.T3</label>
                                                    <input type="text" id="St3" name="St3" placeholder="S.T3"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="St4">S.T4</label>
                                                    <input type="text" id="St4" name="St4" placeholder="S.T4"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="STSH">S.TSH</label>
                                                    <input type="text" id="STSH" name="STSH" placeholder="S.TSH"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="ESR">ESR</label>
                                                    <input type="text" id="ESR" name="ESR" placeholder="ESR"
                                                        class="form-control">
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label for="specific_test">Any specific Test</label>
                                                    <input type="text" id="specific_test" name="specific_test"
                                                        placeholder="Any specific Test" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- INSIDE TREATMENT -->
                                        <div class="treatment-section mb-4">
                                            <div class="section-divider">
                                                <div class="title">Inside Treatment</div>
                                                <div class="line"></div>
                                                <div class="icon-box" onclick="toggleInsideSection(this)">
                                                    <i class="bi bi-plus-lg" id="inside-toggle-icon"></i>
                                                </div>
                                            </div>

                                            <div id="inside-section" class="mt-3" style="display: none;">
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
                                                        <tr>
                                                            <td><input type="text" name="inside_medicine[]"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="Medicine name" autocomplete="off"></td>
                                                            <td>
                                                                <div class="autocomplete-container">
                                                                    <input type="text" name="inside_dose[]"
                                                                        class="form-control form-control-sm dose-input"
                                                                        placeholder="Select or type dose"
                                                                        autocomplete="off">
                                                                    <div class="autocomplete-dropdown"></div>
                                                                </div>
                                                            </td>
                                                            <td><input type="text" name="inside_days[]"
                                                                    class="form-control form-control-sm" placeholder="Days">
                                                            </td>
                                                            <td>
                                                                <select name="inside_timing[]"
                                                                    class="form-select form-select-sm">
                                                                    <option value="">Select</option>
                                                                    <option>Before Food</option>
                                                                    <option>After Food</option>
                                                                    <option>With Food</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-success btn-sm"
                                                                    onclick="addInsideRow()">
                                                                    <i class="bi bi-plus"></i> Add
                                                                </button>
                                                            </td>
                                                        </tr>
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
                                                    <i class="bi bi-plus-lg" id="prescription-toggle-icon"></i>
                                                </div>
                                            </div>

                                            <div id="prescription-section" class="mt-3" style="display: none;">
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
                                                        <tr>
                                                            <td><input type="text" name="prescription_medicine[]"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="Medicine name" autocomplete="off"></td>
                                                            <td>
                                                                <div class="autocomplete-container">
                                                                    <input type="text" name="prescription_dose[]"
                                                                        class="form-control form-control-sm dose-input"
                                                                        placeholder="Select or type dose"
                                                                        autocomplete="off">
                                                                    <div class="autocomplete-dropdown"></div>
                                                                </div>
                                                            </td>
                                                            <td><input type="text" name="prescription_days[]"
                                                                    class="form-control form-control-sm" placeholder="Days">
                                                            </td>
                                                            <td>
                                                                <select name="prescription_timing[]"
                                                                    class="form-select form-select-sm">
                                                                    <option value="">Select</option>
                                                                    <option>Before Food</option>
                                                                    <option>After Food</option>
                                                                    <option>With Food</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-success btn-sm"
                                                                    onclick="addPrescriptionRow()">
                                                                    <i class="bi bi-plus"></i> Add
                                                                </button>
                                                            </td>
                                                        </tr>
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
                                                    <i class="bi bi-plus-lg" id="Homeopathic-toggle-icon"></i>
                                                </div>
                                            </div>

                                            <div id="homeo-treatment-container" class="mt-3" style="display: none;">
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
                                                        <tr>
                                                            <td><input type="text" name="homeo_medicine[]"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="Medicine name" autocomplete="off"></td>
                                                            <td>
                                                                    <div class="autocomplete-container">
                                                                        <input type="text" name="homeo_dose[]"
                                                                            class="form-control form-control-sm dose-input"
                                                                            placeholder="Select or type dose"
                                                                            autocomplete="off">
                                                                        <div class="autocomplete-dropdown"></div>
                                                                    </div>
                                                                </td>
                                                                <td><input type="text" name="homeo_days[]"
                                                                        class="form-control form-control-sm" placeholder="Days">
                                                                </td>
                                                            <td>
                                                                <select name="homeo_timing[]"
                                                                    class="form-select form-select-sm">
                                                                    <option value="">Select</option>
                                                                    <option>Before Food</option>
                                                                    <option>After Food</option>
                                                                    <option>With Food</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-success btn-sm"
                                                                    onclick="addHomeoRow()">
                                                                    <i class="bi bi-plus"></i> Add
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- INDOOR TREATMENT -->
                                        <div class="treatment-section mb-4" id="indoor-treatment-section" style="display: {{ old('pt_status') == 'IPD' ? 'block' : 'none' }};">
                                            <div class="section-divider">
                                                <div class="title">Indoor Treatment</div>
                                                <div class="line"></div>
                                                <div class="icon-box" onclick="toggleIndoorSection(this)">
                                                    <i class="bi bi-plus-lg" id="Indoor-toggle-icon"></i>
                                                </div>
                                            </div>

                                            <div id="indoor-treatment-container" class="mt-3" style="display: none;">
                                                <table class="table table-borderless treatment-table">
                                                    <thead>
                                                        <tr class="text-muted small">
                                                            <th style="width: 25%">Medicine</th>
                                                            <th style="width: 15%">Dose</th>
                                                                <th style="width: 10%">Days</th>
                                                            <th style="width: 12%">Date</th>
                                                            <th style="width: 10%">Time</th>
                                                            <th style="width: 20%">Note</th>
                                                            <th style="width: 15%"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="indoor-treatment-body">
                                                        <tr>
                                                            <td><input type="text" name="indoor_medicine[]"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="Medicine name" autocomplete="off"></td>
                                                            <td>
                                                                    <div class="autocomplete-container">
                                                                        <input type="text" name="indoor_dose[]"
                                                                            class="form-control form-control-sm dose-input"
                                                                            placeholder="Select or type dose"
                                                                            autocomplete="off">
                                                                        <div class="autocomplete-dropdown"></div>
                                                                    </div>
                                                                </td>
                                                                <td><input type="text" name="indoor_days[]"
                                                                        class="form-control form-control-sm" placeholder="Days">
                                                            </td>
                                                            <td><input type="date" name="indoor_date[]"
                                                                    class="form-control form-control-sm"></td>
                                                            <td><input type="time" name="indoor_time[]"
                                                                    class="form-control form-control-sm"></td>
                                                            <td><input type="text" name="indoor_note[]"
                                                                    class="form-control form-control-sm" placeholder="Note">
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-success btn-sm"
                                                                    onclick="addMedicineRow()">
                                                                    <i class="bi bi-plus"></i> Add
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        {{-- <div class="section-divider pt-2">Homeopathic Treatment</div> --}}


                                        <!-- OTHER TREATMENT -->
                                        <div class="treatment-section mb-4">
                                            <div class="section-divider">
                                                <div class="title">Other Treatment</div>
                                                <div class="line"></div>
                                                <div class="icon-box" onclick="toggleOtherSection(this)">
                                                    <i class="bi bi-plus-lg" id="other-toggle-icon"></i>
                                                </div>
                                            </div>

                                            <div id="other-treatment-container" class="mt-3" style="display: none;">
                                                <table class="table table-borderless treatment-table">
                                                    <thead>
                                                        <tr class="text-muted small">
                                                            <th style="width: 45%">Medicine</th>
                                                            <th style="width: 40%">Note</th>
                                                            <th style="width: 15%"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="other-treatment-body">
                                                        <tr>
                                                            <td><input type="text" name="other_medicine[]"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="Medicine name" autocomplete="off"></td>
                                                            <td><input type="text" name="other_note[]"
                                                                    class="form-control form-control-sm" placeholder="Note">
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-success btn-sm"
                                                                    onclick="addOtherRow()">
                                                                    <i class="bi bi-plus"></i> Add
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="pro_filed d-sm-block d-md-flex pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="reference_by">Reference by</label>
                                                    <input type="text" id="reference_by" name="reference_by"
                                                        placeholder="Reference by">
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="referto">Refer to</label>
                                                    <input type="text" id="referto" name="referto" placeholder="Refer to">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pro_filed d-sm-block d-md-flex pt-3">


                                            <div class="form">
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="next_follow_date">Next follow up date</label>
                                                        <input type="date" id="next_follow_date" name="next_follow_date"
                                                            placeholder="Next follow up date">
                                                    </div>
                                                </div>

                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function () {
                                                        // Get today's date in YYYY-MM-DD format
                                                        const today = new Date().toISOString().split('T')[0];

                                                        // Set min attribute to today's date
                                                        document.getElementById('next_follow_date').setAttribute('min', today);

                                                        console.log('Past dates disabled. Minimum selectable date:', today);
                                                    });
                                                </script>
                                            </div>


                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="notes">Notes</label>
                                                    <input type="text" id="notes" name="notes" placeholder="Notes">
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="section-divider pt-4">Payment Information</div>
                                        <div class="pro_filed d-sm-block d-md-flex pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="total_payment">Total Payment</label>
                                                    <input type="number" id="total_payment" name="total_payment"
                                                        placeholder="Total amount" step="0.01">
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="discount_payment">Discount</label>
                                                    <input type="number" id="discount_payment" name="discount_payment"
                                                        placeholder="Discount amount" step="0.01">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pro_filed d-sm-block d-md-flex pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="given_payment">Given Payment</label>
                                                    <input type="number" id="given_payment" name="given_payment"
                                                        placeholder="Amount paid" step="0.01">
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="due_payment">Due Payment</label>
                                                    <input type="number" id="due_payment" name="due_payment"
                                                        placeholder="Due amount" step="0.01" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pro_filed d-sm-block d-md-flex pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="cash_payment">Cash Payment</label>
                                                    <input type="number" id="cash_payment" name="cash_payment"
                                                        placeholder="Cash payment" step="0.01">
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="gp_payment">Google Pay</label>
                                                    <input type="number" id="gp_payment" name="gp_payment"
                                                        placeholder="Google Pay" step="0.01">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pro_filed d-sm-block d-md-flex pt-3">
                                            <div class="form">
                                                <div class="form-col">
                                                    <label for="cheque_payment">Cheque Payment</label>
                                                    <input type="number" id="cheque_payment" name="cheque_payment"
                                                        placeholder="Cheque Payment" step="0.01">
                                                </div>
                                            </div>

                                        </div> --}}
                                        {{-- <div class="d-flex align-items-center bg-light pt-3">
                                            <input type="checkbox" name="foc" id="foc" class="form-check-input me-3">

                                            <label for="foc" class="mb-0 fw-semibold">
                                                FOC (No payment is collected from patient)
                                            </label>
                                        </div> --}}

                                        {{-- <div class="section-divider pt-4">Payment Information</div> --}}

                                        {{-- <div id="payment_section">

                                            <div class="pro_filed d-sm-block d-md-flex pt-3">
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="total_payment">Total Payment</label>
                                                        <input type="number" id="total_payment" name="total_payment"
                                                            placeholder="Total amount" step="0.01">
                                                    </div>
                                                </div>

                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="discount_payment">Discount</label>
                                                        <input type="number" id="discount_payment" name="discount_payment"
                                                            placeholder="Discount amount" step="0.01">
                                                    </div>
                                                </div>

                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="given_payment">Given Payment</label>
                                                        <input type="number" id="given_payment" name="given_payment"
                                                            placeholder="Amount paid" step="0.01">
                                                    </div>
                                                </div>

                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="due_payment">Due Payment</label>
                                                        <input type="number" id="due_payment" name="due_payment"
                                                            placeholder="Due amount" step="0.01" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="section-divider mt-4">
                                                <div class="title">Given Payment</div>
                                                <div class="line"></div>
                                            </div>

                                            <div class="pro_filed d-sm-block d-md-flex separate_payment">
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="cash_payment">Cash Payment</label>
                                                        <input type="number" id="cash_payment" name="cash_payment"
                                                            placeholder="Cash payment" step="0.01">
                                                    </div>
                                                </div>

                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="gp_payment">Google Pay</label>
                                                        <input type="number" id="gp_payment" name="gp_payment"
                                                            placeholder="Google Pay" step="0.01">
                                                    </div>
                                                </div>

                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="cheque_payment">Cheque Payment</label>
                                                        <input type="number" id="cheque_payment" name="cheque_payment"
                                                            placeholder="Cheque Payment" step="0.01">
                                                    </div>
                                                </div>
                                            </div>

                                        </div> --}}

                                        <div class="section-divider mt-4">
                                            <div class="title">Payment Information</div>
                                            <div class="line"></div>
                                            <div class="icon-box" onclick="togglePaymentSection(this)">
                                                <i class="bi bi-plus-lg" id="payment-toggle-icon"></i>
                                            </div>
                                        </div>

                                        <div id="payment-info-section" style="display: none;">
                                            <div class="d-flex align-items-center bg-light p-3 mb-3">
                                                <input type="checkbox" name="foc" id="foc" class="form-check-input me-3">
                                                <label for="foc" class="mb-0 fw-semibold">
                                                    FOC (Free of Charge Inquiry)
                                                </label>
                                            </div>

                                            <div id="payment_section">
                                            <div class="pro_filed" style="flex-wrap: wrap;">
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label>Registration Charges</label>
                                                        <div class="multi-select-container">
                                                            <div class="selected-items" id="charge-selected"></div>
                                                            <div class="autocomplete-container d-flex gap-2">
                                                                <input type="text" id="charge"
                                                                    placeholder="Select Registration Charges..."
                                                                    class="form-control" autocomplete="off">
                                                                <button type="button" class="btn btn-outline-primary"
                                                                    id="add-custom-charge">
                                                                    <i class="fas fa-plus"></i>
                                                                </button>
                                                                <div class="autocomplete-dropdown" id="charge-dropdown"></div>
                                                            </div>
                                                            <div id="charge-hidden-inputs"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="total_payment">Total Amount</label>
                                                        <input type="number" id="total_payment" name="total_payment"
                                                            step="0.01" readonly placeholder="Total" value="0">
                                                    </div>
                                                </div>
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="discount_payment">Discount Amount</label>
                                                        <input type="number" id="discount_payment" name="discount_payment"
                                                            step="0.01" placeholder="Discount" value="0">
                                                    </div>
                                                </div>
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="given_payment">Paid Amount</label>
                                                        <input type="number" id="given_payment" name="given_payment"
                                                            placeholder="Enter amount paid" step="0.01">
                                                    </div>
                                                </div>
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="payment_method">Payment Method</label>
                                                        <select id="payment_method" name="payment_method">
                                                            <option value="">Select Type</option>
                                                            <option value="Cash">Cash</option>
                                                            <option value="Online">Online</option>
                                                            <option value="Cheque">Cheque</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form">
                                                    <div class="form-col">
                                                        <label for="due_payment">Due Amount</label>
                                                        <input type="number" id="due_payment" name="due_payment"
                                                            readonly value="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>

                                    </div>

                                    <script>
                                        function toggleGenericSection(el) {
                                            const section = el.parentElement.nextElementSibling;
                                            const icon = el.querySelector("i");
                                            const currentDisplay = window.getComputedStyle(section).display;
                                            if (currentDisplay === "none") {
                                                section.style.display = "block";
                                                icon.classList.remove("bi-plus-lg");
                                                icon.classList.add("bi-dash-lg");
                                            } else {
                                                section.style.display = "none";
                                                icon.classList.remove("bi-dash-lg");
                                                icon.classList.add("bi-plus-lg");
                                            }
                                        }

                                        function togglePaymentSection(el) {
                                            const section = el.parentElement.nextElementSibling;
                                            const icon = el.querySelector("i");
                                            const currentDisplay = window.getComputedStyle(section).display;
                                            if (currentDisplay === "none") {
                                                section.style.display = "block";
                                                icon.classList.remove("bi-plus-lg");
                                                icon.classList.add("bi-dash-lg");
                                            } else {
                                                section.style.display = "none";
                                                icon.classList.remove("bi-dash-lg");
                                                icon.classList.add("bi-plus-lg");
                                            }
                                        }
                                    </script>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            const givenPaymentInput = document.getElementById('given_payment');
                                            const totalPaymentInput = document.getElementById('total_payment');
                                            const duePaymentInput = document.getElementById('due_payment');
                                            const focCheckbox = document.getElementById('foc');
                                            const paymentSection = document.getElementById('payment_section');

                                            // Multi-Select Charges
                                            const allCharges = @json($charges);
                                            setupInquiryChargeMultiSelect('charge', allCharges);

                                            function setupInquiryChargeMultiSelect(fieldId, charges) {
                                                const input = document.getElementById(fieldId);
                                                const dropdown = document.getElementById(fieldId + '-dropdown');
                                                const selectedContainer = document.getElementById(fieldId + '-selected');
                                                const hiddenContainer = document.getElementById(fieldId + '-hidden-inputs');
                                                if (!input || !dropdown || !selectedContainer || !hiddenContainer) return;

                                                const selectedItems = [];

                                                input.multiSelect = {
                                                    clear: function() {
                                                        selectedItems.length = 0;
                                                        selectedContainer.innerHTML = '';
                                                        hiddenContainer.innerHTML = '';
                                                    }
                                                };

                                                input.addEventListener('focus', () => showChargeSuggestions(''));
                                                input.addEventListener('input', function () {
                                                    showChargeSuggestions(this.value.toLowerCase());
                                                });

                                                function showChargeSuggestions(filter) {
                                                    dropdown.innerHTML = '';
                                                    const filtered = charges.filter(c =>
                                                        c.charges_name.toLowerCase().includes(filter) &&
                                                        !selectedItems.includes(c.charges_name)
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
                                                    if (selectedItems.includes(charge.charges_name)) return;
                                                    selectedItems.push(charge.charges_name);

                                                    const tag = document.createElement('div');
                                                    tag.className = 'selected-item';
                                                    tag.innerHTML = `${charge.charges_name} - ₹${charge.charges_price}
                                                        <button type="button" class="remove-item"><i class="fas fa-times"></i></button>`;
                                                    tag.querySelector('.remove-item').addEventListener('click', () => removeChargeItem(charge, tag));
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

                                                function removeChargeItem(charge, tag) {
                                                    const idx = selectedItems.indexOf(charge.charges_name);
                                                    if (idx > -1) selectedItems.splice(idx, 1);
                                                    tag.remove();
                                                    const hidden = document.getElementById(`charge-hidden-${charge.id}`);
                                                    if (hidden) hidden.remove();
                                                    calculateTotal();
                                                }

                                                document.addEventListener('click', (e) => {
                                                    if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                                                        dropdown.style.display = 'none';
                                                    }
                                                });
                                            }

                                            // Custom charge button
                                            const addCustomBtn = document.getElementById('add-custom-charge');
                                            if (addCustomBtn) {
                                                addCustomBtn.addEventListener('click', async function () {
                                                    const { value: formValues } = await Swal.fire({
                                                        title: 'Add Custom Charge',
                                                        html:
                                                            '<input id="swal-input1" class="swal2-input" placeholder="Charge Name">' +
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
                                                    if (formValues) addCustomChargeItem(formValues.name, formValues.price);
                                                });
                                            }

                                            function addCustomChargeItem(name, price) {
                                                const id = 'custom-' + Date.now();
                                                const selectedContainer = document.getElementById('charge-selected');
                                                const hiddenContainer = document.getElementById('charge-hidden-inputs');

                                                const tag = document.createElement('div');
                                                tag.className = 'selected-item';
                                                tag.innerHTML = `${name} - ₹${price}
                                                    <button type="button" class="remove-item"><i class="fas fa-times"></i></button>`;
                                                tag.querySelector('.remove-item').addEventListener('click', function () {
                                                    tag.remove();
                                                    document.getElementById(`custom-name-${id}`)?.remove();
                                                    document.getElementById(`custom-price-${id}`)?.remove();
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
                                                document.querySelectorAll('.charge-hidden').forEach(h => {
                                                    total += parseFloat(h.getAttribute('data-price')) || 0;
                                                });
                                                if (totalPaymentInput) totalPaymentInput.value = total.toFixed(2);

                                                calculateGiven();
                                                calculateDue();
                                            }

                                            function calculateDue() {
                                                const total = parseFloat(totalPaymentInput?.value) || 0;
                                                const discount = parseFloat(document.getElementById('discount_payment')?.value) || 0;
                                                const given = parseFloat(givenPaymentInput?.value) || 0;
                                                if (duePaymentInput) duePaymentInput.value = Math.max(0, total - discount - given).toFixed(2);
                                            }

                                            document.getElementById('discount_payment')?.addEventListener('input', calculateDue);
                                            givenPaymentInput?.addEventListener('input', calculateDue);

                                            // FOC checkbox functionality
                                            if (focCheckbox && paymentSection) {
                                                function togglePaymentSection() {
                                                    const discountPaymentInput = document.getElementById('discount_payment');
                                                    const paymentMethodSelect = document.getElementById('payment_method');
                                                    const gpPaymentInput = document.getElementById('gp_payment');
                                                    const chequePaymentInput = document.getElementById('cheque_payment');
                                                    
                                                    if (focCheckbox.checked) {
                                                        paymentSection.style.display = 'none';
                                                        if (totalPaymentInput) totalPaymentInput.value = '0.00';
                                                        if (discountPaymentInput) discountPaymentInput.value = '0.00';
                                                        if (givenPaymentInput) givenPaymentInput.value = '0.00';
                                                        if (duePaymentInput) duePaymentInput.value = '0.00';
                                                        if (paymentMethodSelect) paymentMethodSelect.value = '';
                                                        if (gpPaymentInput) gpPaymentInput.value = '';
                                                        if (chequePaymentInput) chequePaymentInput.value = '';
                                                        
                                                        // Clear multi-select charges
                                                        const chargeInput = document.getElementById('charge');
                                                        if (chargeInput && chargeInput.multiSelect) {
                                                            chargeInput.multiSelect.clear();
                                                        } else {
                                                            const selectedContainer = document.getElementById('charge-selected');
                                                            const hiddenContainer = document.getElementById('charge-hidden-inputs');
                                                            if (selectedContainer) selectedContainer.innerHTML = '';
                                                            if (hiddenContainer) hiddenContainer.innerHTML = '';
                                                        }
                                                    } else {
                                                        paymentSection.style.display = 'block';
                                                    }
                                                    calculateDue();
                                                }
                                                togglePaymentSection();
                                                focCheckbox.addEventListener('change', togglePaymentSection);
                                            }
                                        });
                                    </script>
                                    <div class="form-actions">
                                        <a href="/svc-patient" class="btn btn-secondary">Cancel</a>
                                        <button type="submit" class="btn btn-primary" id="submitBtn">Save Inquiry</button>
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
        document.addEventListener('DOMContentLoaded', function () {
            // Set default dates
            const today = new Date().toISOString().split('T')[0];
            const now = new Date().toTimeString().split(' ')[0].substring(0, 5);

            document.getElementById('inquiry_date').value = today;
            document.getElementById('inquiry_time').value = now;

            // Toggle Indoor Treatment based on PT.Status
            const ptStatusSelect = document.getElementById('pt_status');
            const indoorTreatmentSection = document.getElementById('indoor-treatment-section');
            
            function toggleIndoorTreatment() {
                if (ptStatusSelect && indoorTreatmentSection) {
                    if (ptStatusSelect.value === 'IPD') {
                        indoorTreatmentSection.style.display = 'block';
                    } else {
                        indoorTreatmentSection.style.display = 'none';
                    }
                }
            }
            
            if (ptStatusSelect) {
                ptStatusSelect.addEventListener('change', toggleIndoorTreatment);
                toggleIndoorTreatment();
            }
        });

        function addMedicineRow(containerId) {
            const container = document.getElementById(containerId);
            const newRow = document.createElement('tr');

            // INSIDE TREATMENT
            if (containerId === 'inside-treatment-body') {
                newRow.innerHTML = `
                            <td><input type="text" name="inside_medicine[]" class="form-control form-control-sm" placeholder="Medicine name" autocomplete="off"></td>
                            <td>
                                <div class="autocomplete-container">
                                    <input type="text" name="inside_dose[]" class="form-control form-control-sm dose-input" placeholder="Select or type dose" autocomplete="off">
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
                                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        `;
                container.appendChild(newRow);
                setupDoseAutocomplete(newRow.querySelector('.dose-input'), newRow.querySelector('.autocomplete-dropdown'));
            }

            // PRESCRIPTION
            else if (containerId === 'prescription-treatment-body') {
                newRow.innerHTML = `
                            <td><input type="text" name="prescription_medicine[]" class="form-control form-control-sm" placeholder="Medicine name" autocomplete="off"></td>
                            <td>
                                <div class="autocomplete-container">
                                    <input type="text" name="prescription_dose[]" class="form-control form-control-sm dose-input" placeholder="Select or type dose" autocomplete="off">
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
                                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        `;
                container.appendChild(newRow);
                setupDoseAutocomplete(newRow.querySelector('.dose-input'), newRow.querySelector('.autocomplete-dropdown'));
            }

            // INDOOR TREATMENT
            else if (containerId === 'indoor-treatment-body') {
                newRow.innerHTML = `
                            <td><input type="text" name="indoor_medicine[]" class="form-control form-control-sm" placeholder="Medicine name" autocomplete="off"></td>
                            <td><input type="date" name="indoor_date[]" class="form-control form-control-sm"></td>
                            <td><input type="time" name="indoor_time[]" class="form-control form-control-sm"></td>
                            <td><input type="text" name="indoor_note[]" class="form-control form-control-sm" placeholder="Note"></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        `;
                container.appendChild(newRow);
            }
        }

        function setupDoseAutocomplete(input, dropdown) {
            let selectedIndex = -1;

            input.addEventListener('focus', function () {
                showDoseSuggestions(input, dropdown, '');
            });

            input.addEventListener('input', function () {
                const value = this.value.toLowerCase();
                showDoseSuggestions(input, dropdown, value);
            });

            input.addEventListener('keydown', function (e) {
                const items = dropdown.querySelectorAll('.autocomplete-item');

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
                            input.value = items[selectedIndex].textContent;
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

            document.addEventListener('click', function (e) {
                if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('show');
                    selectedIndex = -1;
                }
            });
        }

        function showDoseSuggestions(input, dropdown, filter) {
            dropdown.innerHTML = '';

            const filtered = doseSuggestions.filter(s => s.toLowerCase().includes(filter));

            if (filtered.length > 0) {
                filtered.forEach(suggestion => {
                    const item = document.createElement('div');
                    item.className = 'autocomplete-item';
                    item.textContent = suggestion;
                    item.addEventListener('mousedown', function (e) {
                        e.preventDefault();
                        input.value = suggestion;
                        dropdown.classList.remove('show');
                    });
                    dropdown.appendChild(item);
                });
                dropdown.classList.add('show');
            } else {
                dropdown.classList.remove('show');
            }
        }



        // HOMEOPATHY ROW
        function addHomeoRow(containerId) {
            const container = document.getElementById(containerId);
            const newRow = document.createElement('div');
            newRow.className = 'pro_filed d-sm-block d-md-flex pt-3';

            newRow.innerHTML = `
                    <div class="form">
                        <div class="form-col">
                            <label>Medicine</label>
                            <input type="text" name="homeo_medicine[]">
                        </div>
                    </div>

                    <div class="form">
                        <div class="form-col">
                            <div class="timing-container">
                                <label>When</label>
                                <div class="select-with-button">
                                        <select name="homeo_timing[]">
                                            <option value="">Select Timing</option>
                                            <option>Before Food</option>
                                            <option>After Food</option>
                                        </select>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeHomeoRow(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="form">
                        <div class="form-col">
                            <label>Note</label>
                            <div class="input-with-button">
                                   <input type="text" name="homeo_note[]">

                    <button type="button" class="btn-circle danger small" onclick="removeHomeoRow(this)">-</button>
                            </div>

                        </div>
                    </div>

                `;

            container.appendChild(newRow);
        }



        // OTHER SECTION ROW
        function addOtherRow(containerId) {
            const container = document.getElementById(containerId);
            const newRow = document.createElement('div');
            newRow.className = 'pro_filed d-sm-block d-md-flex pt-3';

            newRow.innerHTML = `
                    <div class="form">
                        <div class="form-col">
                            <label>Medicine</label>
                            <input type="text" name="other_medicine[]">
                        </div>
                    </div>

                    <div class="form">
                        <div class="form-col">
                            <label>Note</label>
                            <div class="input-with-button">
                                   <input type="text" name="other_note[]">

                    <button type="button" class="btn-circle danger small" onclick="this.closest('.pro_filed').remove()">-</button>
                            </div>

                        </div>
                    </div>

                `;

            container.appendChild(newRow);
        }

        function toggleLabInvestigation(iconBox) {
            let section = document.getElementById("lab-investigation-container");
            let icon = iconBox.querySelector("i");

            if (section.style.display === "none") {
                // SHOW section
                section.style.display = "block";
                icon.classList.remove("bi-plus-lg");
                icon.classList.add("bi-dash-lg");
            } else {
                // HIDE section
                section.style.display = "none";
                icon.classList.remove("bi-dash-lg");
                icon.classList.add("bi-plus-lg");
            }
        }

        function toggleInsideSection(iconBox) {
            let section = document.getElementById("inside-section");
            let icon = iconBox.querySelector("i");

            if (section.style.display === "none") {
                // SHOW section
                section.style.display = "block";
                icon.classList.remove("bi-plus-lg");
                icon.classList.add("bi-dash-lg");
            } else {
                // HIDE section
                section.style.display = "none";
                icon.classList.remove("bi-dash-lg");
                icon.classList.add("bi-plus-lg");
            }
        }

        function toggleHomeopathicSection(iconBox) {
            let section = document.getElementById("homeo-treatment-container");
            let icon = iconBox.querySelector("i");

            if (section.style.display === "none") {
                // SHOW section
                section.style.display = "block";
                icon.classList.remove("bi-plus-lg");
                icon.classList.add("bi-dash-lg");
            } else {
                // HIDE section
                section.style.display = "none";
                icon.classList.remove("bi-dash-lg");
                icon.classList.add("bi-plus-lg");
            }
        }

        function togglePrescriptionSection(el) {
            const section = document.getElementById("prescription-section");
            const icon = document.getElementById("prescription-toggle-icon");

            if (section.style.display === "none") {
                section.style.display = "block";
                icon.classList.remove("bi-plus-lg");
                icon.classList.add("bi-dash-lg");
            } else {
                section.style.display = "none";
                icon.classList.remove("bi-dash-lg");
                icon.classList.add("bi-plus-lg");
            }
        }

        function toggleIndoorSection(iconBox) {
            let section = document.getElementById("indoor-treatment-container");
            let icon = iconBox.querySelector("i");

            if (section.style.display === "none") {
                // SHOW section
                section.style.display = "block";
                icon.classList.remove("bi-plus-lg");
                icon.classList.add("bi-dash-lg");
            } else {
                // HIDE section
                section.style.display = "none";
                icon.classList.remove("bi-dash-lg");
                icon.classList.add("bi-plus-lg");
            }
        }

        function toggleOtherSection(iconBox) {
            let section = document.getElementById("other-treatment-container");
            let icon = iconBox.querySelector("i");

            if (section.style.display === "none") {
                // SHOW section
                section.style.display = "block";
                icon.classList.remove("bi-plus-lg");
                icon.classList.add("bi-dash-lg");
            } else {
                // HIDE section
                section.style.display = "none";
                icon.classList.remove("bi-dash-lg");
                icon.classList.add("bi-plus-lg");
            }
        }


        function addInsideRow() {
            let tbody = document.getElementById("inside-treatment-body");
            let newRow = document.createElement("tr");
            newRow.innerHTML = `
                        <td>
                            <div class="autocomplete-container">
                                <input type="text" name="inside_medicine[]" class="form-control form-control-sm medicine-input" placeholder="Medicine name" autocomplete="off">
                                <div class="autocomplete-dropdown"></div>
                            </div>
                        </td>
                        <td>
                            <div class="autocomplete-container">
                                <input type="text" name="inside_dose[]" class="form-control form-control-sm dose-input" placeholder="Select or type dose" autocomplete="off">
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
                            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </td>
                    `;
            tbody.appendChild(newRow);
            const doseInput = newRow.querySelector('.dose-input');
            const medicineInput = newRow.querySelector('.medicine-input');
            if (doseInput) setupDoseAutocomplete(doseInput, doseInput.nextElementSibling);
            if (medicineInput) setupMedicineAutocomplete(medicineInput, medicineInput.nextElementSibling);
        }

        function addPrescriptionRow() {
            let tbody = document.getElementById("prescription-treatment-body");
            let newRow = document.createElement("tr");
            newRow.innerHTML = `
                        <td>
                            <div class="autocomplete-container">
                                <input type="text" name="prescription_medicine[]" class="form-control form-control-sm medicine-input" placeholder="Medicine name" autocomplete="off">
                                <div class="autocomplete-dropdown"></div>
                            </div>
                        </td>
                        <td>
                            <div class="autocomplete-container">
                                <input type="text" name="prescription_dose[]" class="form-control form-control-sm dose-input" placeholder="Select or type dose" autocomplete="off">
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
                            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </td>
                    `;
            tbody.appendChild(newRow);
            const doseInput = newRow.querySelector('.dose-input');
            const medicineInput = newRow.querySelector('.medicine-input');
            if (doseInput) setupDoseAutocomplete(doseInput, doseInput.nextElementSibling);
            if (medicineInput) setupMedicineAutocomplete(medicineInput, medicineInput.nextElementSibling);
        }

        function addHomeoRow() {
            let tbody = document.getElementById("homeo-treatment-body");
            let newRow = document.createElement("tr");
            newRow.innerHTML = `
                        <td>
                            <div class="autocomplete-container">
                                <input type="text" name="homeo_medicine[]" class="form-control form-control-sm medicine-input" placeholder="Medicine name" autocomplete="off">
                                <div class="autocomplete-dropdown"></div>
                            </div>
                        </td>
                        <td>
                            <div class="autocomplete-container">
                                <input type="text" name="homeo_dose[]" class="form-control form-control-sm dose-input" placeholder="Select or type dose" autocomplete="off">
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
                            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </td>
                    `;
            tbody.appendChild(newRow);
            const doseInput = newRow.querySelector('.dose-input');
            const medicineInput = newRow.querySelector('.medicine-input');
            if (doseInput) setupDoseAutocomplete(doseInput, doseInput.nextElementSibling);
            if (medicineInput) setupMedicineAutocomplete(medicineInput, medicineInput.nextElementSibling);
        }

        function addMedicineRow() {
            let tbody = document.getElementById("indoor-treatment-body");
            let newRow = document.createElement("tr");
            newRow.innerHTML = `
                        <td>
                            <div class="autocomplete-container">
                                <input type="text" name="indoor_medicine[]" class="form-control form-control-sm medicine-input" placeholder="Medicine name" autocomplete="off">
                                <div class="autocomplete-dropdown"></div>
                            </div>
                        </td>
                        <td>
                            <div class="autocomplete-container">
                                <input type="text" name="indoor_dose[]" class="form-control form-control-sm dose-input" placeholder="Select or type dose" autocomplete="off">
                                <div class="autocomplete-dropdown"></div>
                            </div>
                        </td>
                        <td><input type="text" name="indoor_days[]" class="form-control form-control-sm" placeholder="Days"></td>
                        <td><input type="date" name="indoor_date[]" class="form-control form-control-sm"></td>
                        <td><input type="time" name="indoor_time[]" class="form-control form-control-sm"></td>
                        <td><input type="text" name="indoor_note[]" class="form-control form-control-sm" placeholder="Note"></td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                `;
            tbody.appendChild(newRow);
            const doseInput = newRow.querySelector('.dose-input');
            const medicineInput = newRow.querySelector('.medicine-input');
            if (doseInput) setupDoseAutocomplete(doseInput, doseInput.nextElementSibling);
            if (medicineInput) setupMedicineAutocomplete(medicineInput, medicineInput.nextElementSibling);
        }

        function addOtherRow() {
            let tbody = document.getElementById("other-treatment-body");
            let newRow = document.createElement("tr");
            newRow.innerHTML = `
                        <td>
                            <div class="autocomplete-container">
                                <input type="text" name="other_medicine[]" class="form-control form-control-sm medicine-input" placeholder="Medicine name" autocomplete="off">
                                <div class="autocomplete-dropdown"></div>
                            </div>
                        </td>
                        <td><input type="text" name="other_note[]" class="form-control form-control-sm" placeholder="Note"></td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </td>
                    `;
            tbody.appendChild(newRow);
            setupMedicineAutocomplete(newRow.querySelector('.medicine-input'), newRow.querySelector('.autocomplete-dropdown'));
        }

        function toggleInsideSection(el) {
            const section = el.parentElement.nextElementSibling;
            const icon = el.querySelector("i");

            const currentDisplay = window.getComputedStyle(section).display;

            if (currentDisplay === "none") {
                section.style.display = "block";
                icon.classList.remove("bi-plus-lg");
                icon.classList.add("bi-dash-lg");
            } else {
                section.style.display = "none";
                icon.classList.remove("bi-dash-lg");
                icon.classList.add("bi-plus-lg");
            }
        }
    </script>

    <script>
        const branches = @json($branches);

        const branchNameSelect = document.getElementById('branchName');
        const branchIdSelect = document.getElementById('branchId');

        branchNameSelect.addEventListener('change', function () {
            const selectedId = this.value;

            // Clear previous options
            branchIdSelect.innerHTML = '<option value="">-- Branch ID --</option>';

            if (selectedId) {
                const branch = branches.find(b => b.branch_id === selectedId);
                if (branch) {
                    const option = document.createElement('option');
                    option.value = branch.branch_id;
                    option.text = branch.branch_id;
                    branchIdSelect.appendChild(option);

                    // Optionally auto-select it
                    branchIdSelect.value = branch.branch_id;
                }
            }
        });
    </script>

    <script>
        // Age String Combination Function
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

        // BMI Calculation Function
        function calculateBMI() {
            const height = parseFloat(document.getElementById('height').value);
            const weight = parseFloat(document.getElementById('weight').value);
            const bmiField = document.getElementById('bmi');

            if (height > 0 && weight > 0) {
                // Convert height from cm to meters
                const heightInMeters = height / 100;
                // Calculate BMI: Weight (kg) / (Height (m) × Height (m))
                const bmi = weight / (heightInMeters * heightInMeters);
                // Round to 2 decimal places
                bmiField.value = bmi.toFixed(1);
            } else {
                bmiField.value = '';
            }
        }

        // Calculate BMI on page load if values exist
        document.addEventListener('DOMContentLoaded', function () {
            calculateBMI();

            // Initialize with sample data immediately
            suggestionsData.complaints = [
                'Fever', 'Cough', 'Headache', 'Abdominal Pain',
                'Chest Pain', 'Nausea', 'Vomiting', 'Weakness'
            ];
            suggestionsData.diagnoses = [
                'Viral Fever', 'Hypertension', 'Diabetes Mellitus',
                'Acute Gastroenteritis', 'Respiratory Infection'
            ];

            // Initialize autocomplete immediately with sample data
            initAutocomplete();

            // Then load real data from API
            loadSuggestions();

            // Then initialize doctor dose functionality
            initDoctorDose();

            // Initialize dose functionality
            initDoseFields();
        });

        // Doctor Dose functionality
        function initDoctorDose() {
            const dropdown = document.getElementById('doctor_dose_dropdown');
            const textbox = document.getElementById('doctor_dose');

            if (dropdown && textbox) {
                // When dropdown changes, update textbox
                dropdown.addEventListener('change', function () {
                    if (this.value) {
                        textbox.value = this.value;
                    }
                });

                // When textbox is typed, clear dropdown selection
                textbox.addEventListener('input', function () {
                    dropdown.value = '';
                });
            }
        }

        // Dose Fields functionality
        function initDoseFields() {
            // Handle all dose containers
            document.addEventListener('change', function (e) {
                if (e.target.name && e.target.name.includes('dose_dropdown')) {
                    // Find corresponding textbox
                    const container = e.target.closest('.dose-container');
                    if (container) {
                        const textbox = container.querySelector('input[name*="dose"]');
                        if (textbox && e.target.value) {
                            // Clear textbox and set dropdown value
                            textbox.value = '';
                            textbox.setAttribute('data-source', 'dropdown');
                            e.target.setAttribute('data-source', 'dropdown');
                        }
                    }
                }
            });

            document.addEventListener('input', function (e) {
                if (e.target.name && e.target.name.includes('dose') && e.target.type === 'text') {
                    // Find corresponding dropdown
                    const container = e.target.closest('.dose-container');
                    if (container) {
                        const dropdown = container.querySelector('select[name*="dose_dropdown"]');
                        if (dropdown) {
                            // Clear dropdown and set textbox as source
                            dropdown.value = '';
                            e.target.setAttribute('data-source', 'manual');
                            dropdown.setAttribute('data-source', 'manual');
                        }
                    }
                }
            });

            // Handle form submission to ensure only one dose value
            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (form.id !== 'inquiryForm') return;

                // Show SweetAlert confirmation
                e.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to save this SVC inquiry?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, save it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading safely
                        const submitBtn = form.querySelector('button[type="submit"]') || document.getElementById('submitBtn') || form.querySelector('.btn-primary');
                        const originalText = submitBtn ? submitBtn.innerHTML : 'Save Inquiry';
                        if (submitBtn) {
                            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...';
                            submitBtn.disabled = true;
                        }

                        // Process dose containers
                        const doseContainers = document.querySelectorAll('.dose-container');
                        doseContainers.forEach(container => {
                            const textbox = container.querySelector('input[name*="dose"]');
                            const dropdown = container.querySelector('select[name*="dose_dropdown"]');

                            if (textbox && dropdown) {
                                // If textbox has manual input, clear dropdown name to prevent submission
                                if (textbox.value && textbox.getAttribute('data-source') === 'manual') {
                                    dropdown.name = dropdown.name.replace('_dropdown', '_dropdown_disabled');
                                }
                                // If dropdown has value, disable textbox
                                else if (dropdown.value && dropdown.getAttribute('data-source') === 'dropdown') {
                                    textbox.name = textbox.name + '_disabled';
                                }
                            }
                        });

                        // Ensure diagnosis field is properly updated before submission
                        const diagnosisHidden = document.getElementById('diagnosis-hidden');
                        const complainHidden = document.getElementById('complain-hidden');

                        console.log('Form submission - Diagnosis value:', diagnosisHidden ? diagnosisHidden.value : 'NOT FOUND');
                        console.log('Form submission - Complain value:', complainHidden ? complainHidden.value : 'NOT FOUND');

                        // Check if required fields have values
                        if (!diagnosisHidden || !diagnosisHidden.value.trim()) {
                            e.preventDefault();
                            if (submitBtn) {
                                submitBtn.innerHTML = originalText;
                                submitBtn.disabled = false;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: 'Please select at least one diagnosis before submitting.',
                            });
                            return false;
                        }

                        // Submit form normally (will redirect with success message)
                        form.submit();

                        // Show success message after a delay (for cases where redirect is fast)
                        setTimeout(() => {
                            // Check if we're still on the same page (form submission failed)
                            if (document.contains(form)) {
                                if (submitBtn) {
                                    submitBtn.innerHTML = originalText;
                                    submitBtn.disabled = false;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Form submission failed. Please check the form and try again.',
                                });
                            }
                        }, 2000);
                    }
                });
            });

            // Alternative: Direct click handler for submit button as backup
            document.addEventListener('DOMContentLoaded', function () {
                const submitBtn = document.querySelector('#inquiryForm button[type="submit"]');
                if (submitBtn) {
                    submitBtn.addEventListener('click', function (e) {
                        // Let the submit handler deal with it
                    });
                }
            });

            // Check for success message on page load and show SweetAlert
            window.addEventListener('load', function () {
                // Check for success message from session
                const successMessage = document.getElementById('success-message');
                if (successMessage) {
                    const message = successMessage.textContent.trim();
                    if (message) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: message,
                            showConfirmButton: true,
                            confirmButtonColor: '#007bff',
                            timer: 5000,
                            timerProgressBar: true
                        });
                    }
                }

                // Check for error message from session
                const errorMessage = document.getElementById('error-message');
                if (errorMessage) {
                    const message = errorMessage.textContent.trim();
                    if (message) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: message,
                            showConfirmButton: true,
                            confirmButtonColor: '#dc3545'
                        });
                    }
                }

                // Also look for any existing success alert divs (fallback)
                const successAlert = document.querySelector('.alert-success');
                if (successAlert) {
                    const successMessage = successAlert.textContent.trim();
                    if (successMessage) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: successMessage,
                            showConfirmButton: true,
                            confirmButtonColor: '#007bff'
                        });
                    }
                }
            });
        }

        // Global test function to verify SweetAlert is working
        window.testSweetAlert = function () {
            Swal.fire({
                title: 'Test Alert',
                text: 'SweetAlert is working!',
                icon: 'success',
                confirmButtonColor: '#007bff'
            });
        };

        let suggestionsData = {
            complaints: [],
            diagnoses: []
        };

        // Load suggestions from API and then initialize autocomplete
        function loadSuggestions() {
            console.log('Loading suggestions...');
            if (typeof loadMedicineSuggestions === 'function') {
                loadMedicineSuggestions();
            }

            // Initialize existing dose fields
            document.querySelectorAll('.dose-input').forEach(input => {
                const dropdown = input.nextElementSibling;
                if (dropdown && dropdown.classList.contains('autocomplete-dropdown')) {
                    setupDoseAutocomplete(input, dropdown);
                }
            });

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
                    console.log('API response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Suggestions data received:', data);

                    // Update with real data if available
                    if (data.complaints && data.complaints.length > 0) {
                        suggestionsData.complaints = data.complaints;
                        console.log('Updated with real complaint data');
                    }

                    if (data.diagnoses && data.diagnoses.length > 0) {
                        suggestionsData.diagnoses = data.diagnoses;
                        console.log('Updated with real diagnosis data');
                    }

                    console.log('Final suggestions data:', suggestionsData);

                    // Reinitialize autocomplete with updated data
                    initAutocomplete();
                })
                .catch(error => {
                    console.error('Error loading suggestions:', error);
                    console.log('Continuing with sample data');
                });
        }

        // Initialize custom autocomplete
        function initAutocomplete() {
            console.log('Initializing multi-select autocomplete...');

            // Complaint field
            setupMultiSelect('complain', suggestionsData.complaints);

            // Diagnosis field
            setupMultiSelect('diagnosis', suggestionsData.diagnoses);
        }

        function setupMultiSelect(fieldId, suggestions) {
            const input = document.getElementById(fieldId);
            const dropdown = document.getElementById(fieldId + '-dropdown');
            const selectedContainer = document.getElementById(fieldId + '-selected');
            const hiddenInput = document.getElementById(fieldId + '-hidden');

            let selectedItems = [];
            let selectedIndex = -1;

            console.log(`${fieldId} elements found:`, {
                input: !!input,
                dropdown: !!dropdown,
                selectedContainer: !!selectedContainer,
                hiddenInput: !!hiddenInput,
                suggestions: suggestions.length
            });

            if (!input || !dropdown || !selectedContainer || !hiddenInput) {
                console.error(`Missing elements for ${fieldId} multi-select`);
                return;
            }

            // Show suggestions when input gets focus
            input.addEventListener('focus', function () {
                showMultiSelectSuggestions(input, dropdown, suggestions, selectedItems);
            });

            // Filter suggestions on input
            input.addEventListener('input', function () {
                const value = this.value.toLowerCase();
                showMultiSelectSuggestions(input, dropdown, suggestions, selectedItems, value);
            });

            // Keyboard navigation
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
                            // Add new item if typed
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
                            // Remove last item when backspace is pressed on empty input
                            removeItem(selectedItems[selectedItems.length - 1]);
                        }
                        break;
                }
            });

            // Hide dropdown when clicking outside
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

                    // Add to suggestions if new
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

                if (selectedItems.length === 0) {
                    return;
                }

                selectedItems.forEach(item => {
                    const itemElement = document.createElement('div');
                    itemElement.className = 'selected-item';
                    itemElement.innerHTML = `
                                ${item}
                                <button type="button" class="remove-item" data-item="${item}">
                                    <i class="fas fa-times"></i>
                                </button>
                            `;

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

            // Store functions in the input element for external access
            input.multiSelect = {
                addItem: addItem,
                removeItem: removeItem,
                selectedItems: () => selectedItems
            };
        }

        function showMultiSelectSuggestions(input, dropdown, suggestions, selectedItems, filter = '') {
            dropdown.innerHTML = '';
            selectedIndex = -1;

            if (!suggestions || suggestions.length === 0) {
                dropdown.classList.remove('show');
                return;
            }

            const filteredSuggestions = suggestions.filter(suggestion =>
                suggestion.toLowerCase().includes(filter) && !selectedItems.includes(suggestion)
            );

            if (filteredSuggestions.length === 0 && filter) {
                // Show "Add new" option when no matches found
                const addNew = document.createElement('div');
                addNew.className = 'autocomplete-item add-new';
                addNew.innerHTML = `<i class="fas fa-plus"></i> Add "${filter}" as new ${input.id}`;
                addNew.style.color = '#197040';
                addNew.style.fontWeight = 'bold';

                addNew.addEventListener('click', function () {
                    if (input.multiSelect) {
                        input.multiSelect.addItem(filter);
                    }
                    input.value = '';
                    dropdown.classList.remove('show');
                });

                dropdown.appendChild(addNew);
            } else {
                filteredSuggestions.forEach(suggestion => {
                    const item = document.createElement('div');
                    item.className = 'autocomplete-item';
                    item.textContent = suggestion;

                    item.addEventListener('click', function () {
                        if (input.multiSelect) {
                            input.multiSelect.addItem(suggestion);
                        }
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

            fetch('/save-medical-condition', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    name: name,
                    type: type
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update input value and mark as new
                        inputElement.value = name;
                        inputElement.setAttribute('data-is-existing', 'false');
                        inputElement.setAttribute('data-new-condition-id', data.condition.id);

                        // Add to suggestions data for future use
                        if (type === 'complaint') {
                            suggestionsData.complaints.push(name);
                            suggestionsData.complaints.sort();
                        } else {
                            suggestionsData.diagnoses.push(name);
                            suggestionsData.diagnoses.sort();
                        }

                        // Show success message
                        showNotification(data.message, 'success');

                        // Hide dropdown
                        const dropdownId = inputElement.id + '-dropdown';
                        const dropdown = document.getElementById(dropdownId);
                        if (dropdown) {
                            dropdown.classList.remove('show');
                        }
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error saving medical condition:', error);
                    showNotification('Error saving new condition', 'error');
                });
        }

        // Function to show notifications
        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;

            document.body.appendChild(notification);

            // Auto-remove after 3 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 3000);
        }

        function updateSelection(items, selectedIndex) {
            items.forEach((item, index) => {
                if (index === selectedIndex) {
                    item.classList.add('selected');
                } else {
                    item.classList.remove('selected');
                }
            });
        }

        // Global Medicine & Dose Suggestion Cache
        window.cachedMedicines = [];
        window.cachedMedicineDoses = {};
        window.cachedMedicineTimings = {};
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
                        window.cachedMedicineTimings = data.medicine_timings || {};
                        window.cachedDoses = data.doses || [];

                        // Initialize medicine autocomplete on all existing medicine inputs
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

                const row = input.closest('tr');
                if (row) {
                    // Auto-fill dose
                    if (window.cachedMedicineDoses && window.cachedMedicineDoses[value]) {
                        const doseInput = row.querySelector('.dose-input');
                        if (doseInput) doseInput.value = window.cachedMedicineDoses[value];
                    }
                    // Auto-select timing
                    if (window.cachedMedicineTimings && window.cachedMedicineTimings[value]) {
                        const timingSelect = row.querySelector('select[name$="_timing[]"]');
                        if (timingSelect) timingSelect.value = window.cachedMedicineTimings[value];
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
                const row = input.closest('tr');
                if (row) {
                    if (window.cachedMedicineDoses && window.cachedMedicineDoses[val]) {
                        const doseInput = row.querySelector('.dose-input');
                        if (doseInput && !doseInput.value) {
                            doseInput.value = window.cachedMedicineDoses[val];
                        }
                    }
                    if (window.cachedMedicineTimings && window.cachedMedicineTimings[val]) {
                        const timingSelect = row.querySelector('select[name$="_timing[]"]');
                        if (timingSelect && !timingSelect.value) {
                            timingSelect.value = window.cachedMedicineTimings[val];
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
    </script>
@endsection