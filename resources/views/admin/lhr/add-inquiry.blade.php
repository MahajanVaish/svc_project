@extends('admin.layouts.layouts')

@section('content')

    <style>
        .section-divider {
            display: flex;
            align-items: center;
            width: 100%;
            margin: 10px 0;
            font-size: 16px;
            font-weight: 600;
            color: var(--accent-solid);
            cursor: pointer;
            padding: 10px 15px;
            background: var(--bg-main);
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .section-divider:hover {
            background: var(--accent-glow);
        }

        .section-divider:after {
            content: "\f078";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            margin-left: auto;
            transition: transform 0.3s ease;
            font-size: 14px;
        }

        .section-divider.active:after {
            transform: rotate(180deg);
        }

        .accordion-content {
            max-height: 2000px;
            overflow: hidden;
            transition: max-height 0.4s ease-out, padding 0.4s ease;
            padding: 20px 5px;
        }

        .accordion-content.collapsed {
            max-height: 0;
            padding-top: 0;
            padding-bottom: 0;
        }

        .form-container {
            background: var(--bg-card);
            padding: 30px;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-subtle);
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .pro_filed {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            width: 100%;
        }

        .pro_filed .form {
            flex: 1;
            position: relative;
        }

        label {
            font-weight: 600;
            color: var(--text-primary);
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .required:after {
            content: " *";
            color: #e74c3c;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            font-size: 14px;
            background: var(--bg-input);
            color: var(--text-primary);
            transition: all 0.3s ease;
            outline: none;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--accent-solid);
            box-shadow: 0 0 0 3px var(--accent-glow);
            background: var(--bg-main);
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .btn-submit {
            background: #086838;
            color: white;
            padding: 12px 35px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(8, 104, 56, 0.2);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
        }

        .btn-submit:hover {
            background: #067945;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(8, 104, 56, 0.3);
            color: white;
        }

        .btn-cancel {
            background: white;
            border: 2px solid #dee2e6;
            padding: 12px 35px;
            border-radius: 8px;
            font-weight: 600;
            color: #6c757d;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-cancel:hover {
            background: #f8f9fa;
            border-color: #adb5bd;
            color: #495057;
        }

        .separate_payment {
            padding: 25px;
            border: 1px solid var(--border-subtle) !important;
            border-radius: 12px;
            background: var(--bg-main);
            margin-top: 20px;
        }

        .branch-info-card {
            background: var(--bg-main);
            border-radius: 12px;
            padding: 25px 40px;
            border: 1px solid var(--border-subtle);
            margin-bottom: 30px;
        }

        .branch-info-label {
            font-size: 11px;
            color: var(--text-secondary);
            margin-bottom: 6px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .branch-info-value {
            font-weight: 700;
            color: var(--accent-solid);
            font-size: 17px;
        }

        /* Page Title Styling */
        .page-title-box h4 {
            color: var(--accent-solid);
            font-size: 24px;
            font-weight: 600;
        }

        /* Custom Checkbox */
        .custom-checkbox-container {
            display: inline-flex;
            align-items: center;
            cursor: pointer;
            user-select: none;
            margin-bottom: 20px;
            padding: 5px 0;
            transition: all 0.3s ease;
        }

        .custom-checkbox-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .checkbox-checkmark {
            height: 22px;
            width: 22px;
            background-color: var(--bg-input);
            border: 2px solid var(--border-subtle);
            border-radius: 6px;
            margin-right: 12px;
            position: relative;
            transition: all 0.3s ease;
        }

        .custom-checkbox-container input:checked~.checkbox-checkmark {
            background-color: var(--accent-solid);
            border-color: var(--accent-solid);
        }

        .checkbox-checkmark:after {
            content: "";
            position: absolute;
            display: none;
            left: 7px;
            top: 3px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .custom-checkbox-container input:checked~.checkbox-checkmark:after {
            display: block;
        }

        .checkbox-label {
            font-weight: 500;
            color: var(--text-primary);
            font-size: 14px;
        }

        /* Custom Radio Buttons */
        .custom-radio-group {
            display: flex;
            gap: 30px;
            padding: 10px 0;
        }

        .custom-radio-item {
            position: relative;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .custom-radio-item input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .radio-checkmark {
            height: 20px;
            width: 20px;
            background-color: var(--bg-input);
            border: 2px solid var(--border-subtle);
            border-radius: 50%;
            display: inline-block;
            position: relative;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .custom-radio-item:hover input~.radio-checkmark {
            border-color: var(--accent-solid);
        }

        .custom-radio-item input:checked~.radio-checkmark {
            background-color: var(--bg-input);
            border-color: var(--accent-solid);
        }

        .radio-checkmark:after {
            content: "";
            position: absolute;
            display: none;
            top: 4px;
            left: 4px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #0d6efd;
        }

        .custom-radio-item input:checked~.radio-checkmark:after {
            display: block;
        }

        .radio-label {
            font-weight: 500;
            color: var(--text-primary);
            font-size: 15px;
        }

        /* Picture Uploads */
        .picture-upload-box {
            background: var(--bg-input);
            border: 2px dashed var(--border-subtle);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .picture-upload-box:hover {
            border-color: var(--accent-solid);
            background: var(--accent-glow);
        }

        .upload-icon {
            font-size: 24px;
            color: var(--text-secondary);
            margin-bottom: 10px;
        }

        .upload-text {
            font-size: 13px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .pro_filed {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-11 col-lg-10 m-auto">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Add New Inquiry</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('lhr.pending') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-11 col-lg-10 m-auto">
                <div class="form-container">
                    <form id="inquiryForm" method="POST" action="{{ route('lhr.add.inquiry.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="current_patient_id" value="">

                        <!-- Branch Information -->
                        <div class="branch-info-card">
                            <div class="pro_filed">
                                @if ($branchId)
                                    <div class="form">
                                        <div class="branch-info-label">Branch Name</div>
                                        <div class="branch-info-value">{{ $branchName }}</div>
                                        <input type="hidden" name="branch" value="{{ $branchName }}">
                                    </div>
                                    <div class="form">
                                        <div class="branch-info-label">Branch ID</div>
                                        <div class="branch-info-value">{{ $branchId }}</div>
                                        <input type="hidden" name="branch_id" value="{{ $branchId }}">
                                    </div>
                                @else
                                    <div class="form">
                                        <label for="branchName" class="required">Select Branch</label>
                                        <select id="branchName" name="branch_id" required>
                                            <option value="">Select Branch</option>
                                            @foreach($branches as $b)
                                                <option value="{{ $b->branch_id }}">{{ $b->branch_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form">
                                        <label for="branchId" class="required">Branch ID</label>
                                        <select id="branchId" name="branch" required>
                                            <option value="">Branch ID</option>
                                        </select>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="section-divider active" onclick="toggleSection(this)">Patient Information</div>
                        <div class="accordion-content">
                            <div class="pro_filed">
                                <div class="form">
                                    <label for="patient_name" class="required">Patient Name</label>
                                    <input type="text" id="patient_name" name="patient_name" required
                                        placeholder="Enter patient name" value="{{ old('patient_name') }}">
                                </div>
                                <div class="form">
                                    <label for="inquiry_date">Inquiry Date</label>
                                    <input type="date" id="inquiry_date" name="inquiry_date"
                                        value="{{ old('inquiry_date', date('Y-m-d')) }}">
                                </div>
                            </div>
                            <div class="pro_filed">
                                <div class="form">
                                    <label for="address">Address</label>
                                    <textarea id="address" name="address" rows="1"
                                        placeholder="Enter complete address">{{ old('address') }}</textarea>
                                </div>
                            </div>
                            <div class="pro_filed">
                                <div class="form">
                                    <label for="gender" class="required">Gender</label>
                                    <select id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female
                                        </option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div class="form">
                                    <label for="age" class="required">Age</label>
                                    <div style="display: flex; gap: 10px;">
                                        <input type="number" id="age" name="age" required min="1" max="120"
                                            placeholder="Age" value="{{ old('age') }}" style="flex: 1;">
                                        <select name="year" style="width: 100px;">
                                            <option value="Year" selected>Year</option>
                                            <option value="Month">Month</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="pro_filed">
                                <div class="form">
                                    <label for="staff_name">Therapist Name</label>
                                    <input type="text" id="staff_name" name="staff_name" placeholder="Staff Name"
                                        value="{{ old('staff_name') }}">
                                </div>
                                <div class="form">
                                    <label for="status_name">Status</label>
                                    <select id="status_name" name="status_name">
                                        <option value="pending" {{ old('status_name') == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="joined" {{ old('status_name') == 'joined' ? 'selected' : '' }}>Joined
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="section-divider active" onclick="toggleSection(this)">Treatment Information</div>
                        <div class="accordion-content">


                            <div id="area_session_section" style="display: none;">
                                <div id="treatment_rows_container">
                                    <div class="treatment-row border-bottom pb-4 mb-4 position-relative">
                                        <div class="pro_filed">
                                            <div class="form">
                                                <label for="area" class="required">Select Program</label>
                                                <select name="area[0][]" multiple class="form-control select2-area">
                                                    @foreach($programs as $program)
                                                                                                <option value="{{ $program->program_name }}"
                                                                                                    data-price="{{ $program->program_price }}"
                                                                                                    data-short-name="{{ $program->program_short_name }}">{{
                                                        $program->program_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form">
                                                <label for="session">Session</label>
                                                <input type="number" name="session[]" placeholder="Enter session details"
                                                    value="{{ old('session.0') }}">
                                            </div>
                                            <div class="form">
                                                <label for="area_code">Area Code</label>
                                                <input type="text" name="area_code[]" placeholder="Area Code"
                                                    value="{{ old('area_code.0') }}" class="area-code-input" readonly>
                                            </div>
                                            <div class="form" style="flex: 0.5;">
                                                <label>Price (₹)</label>
                                                <input type="number" step="0.01" class="row-price-display" value="0.00" style="background-color: #ffffff;">
                                            </div>
                                        </div>

                                        <div class="session-status-container mb-3" style="display: none;">
                                            <div class="session-badge-wrapper">
                                                <span class="badge"
                                                    style="background-color: #0dcaf0; color: #fff; padding: 8px 12px; font-size: 14px; border-radius: 4px; display: inline-flex; align-items: center; gap: 8px;">
                                                    <i class="fas fa-history"></i>
                                                    <span class="session-text">Sessions: Total 0, Used 0, Remaining 0</span>
                                                </span>
                                            </div>
                                        </div>



                                        <button type="button" class="btn btn-danger btn-sm remove-row-btn position-absolute"
                                            style="top: -10px; right: 0; display: none; border-radius: 50%; width: 30px; height: 30px;">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="text-end mb-4">
                                    <button type="button" id="add_treatment_row" class="btn btn-success btn-sm">
                                        <i class="fas fa-plus me-1"></i> Add More Treatment
                                    </button>
                                </div>
                            </div>



                        </div>

                        <div class="section-divider collapsed" onclick="toggleSection(this)">Medical Information</div>
                        <div class="accordion-content collapsed">
                            <div class="card_custom mb-4">
                                <div class="mb-4">
                                    <label>Do you have any hormonal issues?</label>
                                    <div class="custom-radio-group">
                                        <label class="custom-radio-item">
                                            <input type="radio" name="hormonal_issues" value="yes" {{
        old('hormonal_issues') == 'yes' ? 'checked' : '' }}>
                                            <span class="radio-checkmark"></span>
                                            <span class="radio-label">Yes</span>
                                        </label>
                                        <label class="custom-radio-item">
                                            <input type="radio" name="hormonal_issues" value="no" {{
        old('hormonal_issues') == 'no' || !old('hormonal_issues') ? 'checked' : '' }}>
                                            <span class="radio-checkmark"></span>
                                            <span class="radio-label">No</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label>Any medication or treatment for hair loss?</label>
                                    <div class="custom-radio-group">
                                        <label class="custom-radio-item">
                                            <input type="radio" name="medication" value="yes" {{ old('medication') == 'yes'
        ? 'checked' : '' }}>
                                            <span class="radio-checkmark"></span>
                                            <span class="radio-label">Yes</span>
                                        </label>
                                        <label class="custom-radio-item">
                                            <input type="radio" name="medication" value="no" {{ old('medication') == 'no' ||
        !old('medication') ? 'checked' : '' }}>
                                            <span class="radio-checkmark"></span>
                                            <span class="radio-label">No</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label>Before you took hair treatment from somewhere else?</label>
                                    <div class="custom-radio-group">
                                        <label class="custom-radio-item">
                                            <input type="radio" name="previous_treatment" value="yes" {{
        old('previous_treatment') == 'yes' ? 'checked' : '' }}>
                                            <span class="radio-checkmark"></span>
                                            <span class="radio-label">Yes</span>
                                        </label>
                                        <label class="custom-radio-item">
                                            <input type="radio" name="previous_treatment" value="no" {{
        old('previous_treatment') == 'no' || !old('previous_treatment') ? 'checked'
        : '' }}>
                                            <span class="radio-checkmark"></span>
                                            <span class="radio-label">No</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label>PCOD, Thyroid Issue?</label>
                                    <div class="custom-radio-group">
                                        <label class="custom-radio-item">
                                            <input type="radio" name="pcod_thyroid" value="yes" {{
        old('pcod_thyroid') == 'yes' ? 'checked' : '' }}>
                                            <span class="radio-checkmark"></span>
                                            <span class="radio-label">Yes</span>
                                        </label>
                                        <label class="custom-radio-item">
                                            <input type="radio" name="pcod_thyroid" value="no" {{ old('pcod_thyroid') == 'no'
        || !old('pcod_thyroid') ? 'checked' : '' }}>
                                            <span class="radio-checkmark"></span>
                                            <span class="radio-label">No</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label>Do you suffer from any skin conditions, allergies, or diseases?</label>
                                    <div class="custom-radio-group">
                                        <label class="custom-radio-item">
                                            <input type="radio" name="skin_conditions" value="yes" {{
        old('skin_conditions') == 'yes' ? 'checked' : '' }}>
                                            <span class="radio-checkmark"></span>
                                            <span class="radio-label">Yes</span>
                                        </label>
                                        <label class="custom-radio-item">
                                            <input type="radio" name="skin_conditions" value="no" {{
        old('skin_conditions') == 'no' || !old('skin_conditions') ? 'checked' : '' }}>
                                            <span class="radio-checkmark"></span>
                                            <span class="radio-label">No</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label>Which procedure are you currently utilizing for hair removal?</label>
                                    <div class="d-flex gap-4">
                                        <label class="custom-checkbox-container">
                                            <input type="checkbox" name="procedure[]" value="waxing" {{
        is_array(old('procedure')) && in_array('waxing', old('procedure'))
        ? 'checked' : '' }}>
                                            <span class="checkbox-checkmark"></span>
                                            <span class="checkbox-label">Waxing</span>
                                        </label>
                                        <label class="custom-checkbox-container">
                                            <input type="checkbox" name="procedure[]" value="threading" {{
        is_array(old('procedure')) && in_array('threading', old('procedure'))
        ? 'checked' : '' }}>
                                            <span class="checkbox-checkmark"></span>
                                            <span class="checkbox-label">Threading</span>
                                        </label>
                                        <label class="custom-checkbox-container">
                                            <input type="checkbox" name="procedure[]" value="cream" {{
        is_array(old('procedure')) && in_array('cream', old('procedure'))
        ? 'checked' : '' }}>
                                            <span class="checkbox-checkmark"></span>
                                            <span class="checkbox-label">Cream</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label>Are there any ongoing skin treatments?</label>
                                    <div class="custom-radio-group">
                                        <label class="custom-radio-item">
                                            <input type="radio" name="ongoing_treatments" value="yes" {{
        old('ongoing_treatments') == 'yes' ? 'checked' : '' }}>
                                            <span class="radio-checkmark"></span>
                                            <span class="radio-label">Yes</span>
                                        </label>
                                        <label class="custom-radio-item">
                                            <input type="radio" name="ongoing_treatments" value="no" {{
        old('ongoing_treatments') == 'no' || !old('ongoing_treatments') ? 'checked'
        : '' }}>
                                            <span class="radio-checkmark"></span>
                                            <span class="radio-label">No</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label>Does your body have any implantations or tattoos?</label>
                                    <div class="custom-radio-group">
                                        <label class="custom-radio-item">
                                            <input type="radio" name="implants_tattoos" value="yes" {{
        old('implants_tattoos') == 'yes' ? 'checked' : '' }}>
                                            <span class="radio-checkmark"></span>
                                            <span class="radio-label">Yes</span>
                                        </label>
                                        <label class="custom-radio-item">
                                            <input type="radio" name="implants_tattoos" value="no" {{
        old('implants_tattoos') == 'no' || !old('implants_tattoos') ? 'checked' : ''
                                                                }}>
                                            <span class="radio-checkmark"></span>
                                            <span class="radio-label">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="section-divider collapsed" onclick="toggleSection(this)">Follow Up & Notes</div>
                        <div class="accordion-content collapsed">
                            <div class="card_custom mb-4">
                                <div class="pro_filed">
                                    <div class="form">
                                        <label for="reference_by">Reference By</label>
                                        <input type="text" id="reference_by" name="reference_by" placeholder="Reference By"
                                            value="{{ old('reference_by') }}">
                                    </div>
                                </div>

                                <div class="pro_filed">
                                    <div class="form">
                                        <label for="notes">Notes</label>
                                        <textarea id="notes" name="notes" rows="2"
                                            placeholder="Enter notes">{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FOC Payment Section -->
                        <div class="card_custom mb-4">
                            <div class="mb-4">
                                <label class="custom-checkbox-container">
                                    <input type="checkbox" name="foc" value="1" id="focCheck" {{ old('foc') ? 'checked' : '' }}>
                                    <span class="checkbox-checkmark"></span>
                                    <span class="checkbox-label fw-bold">FOC (Free of Charge Inquiry)</span>
                                </label>
                            </div>

                            <!-- Balanced Payment Row - Hidden when FOC is checked -->
                            <div id="paymentRow">
                                <div class="row align-items-end mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Registration Charges (₹)</label>
                                        <input type="number" class="form-control" name="total_payment" id="total_payment"
                                            value="{{ old('total_payment', '0') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Discount (₹)</label>
                                        <input type="number" step="0.01" class="form-control" name="discount_payment"
                                            id="discount_payment" placeholder="0.00" value="{{ old('discount_payment', '0') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Given Payment (₹)</label>
                                        <input type="number" step="0.01" class="form-control" name="paid_amount"
                                            id="paid_amount" placeholder="0.00" value="{{ old('paid_amount') }}" readonly style="background-color: #f8f9fa;">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Due Payment (₹)</label>
                                        <input type="number" class="form-control" name="due_payment" id="due_payment"
                                            value="{{ old('due_payment') }}" readonly style="background-color: #f8f9fa;">
                                    </div>
                                </div>
                                <div class="row align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label">Cash Payment (₹)</label>
                                        <input type="number" step="0.01" class="form-control payment-input" name="cash_payment" id="cash_payment" placeholder="0.00" value="{{ old('cash_payment') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">G-Pay Payment (₹)</label>
                                        <input type="number" step="0.01" class="form-control payment-input" name="gp_payment" id="gp_payment" placeholder="0.00" value="{{ old('gp_payment') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Cheque Payment (₹)</label>
                                        <input type="number" step="0.01" class="form-control payment-input" name="cheque_payment" id="cheque_payment" placeholder="0.00" value="{{ old('cheque_payment') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions mt-5 pt-4 text-center border-top">
                            <button type="submit" class="btn-submit me-3">
                                <i class="fas fa-save me-2"></i> Submit Inquiry Now
                            </button>
                            <a href="{{ route('lhr.pending') }}" class="btn-cancel">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Select2 and other dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        function toggleSection(header) {
            header.classList.toggle('active');
            header.classList.toggle('collapsed');
            const content = header.nextElementSibling;
            content.classList.toggle('collapsed');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const branches = @json($branches ?? []);
            const branchNameSelect = document.getElementById('branchName');
            const branchIdSelect = document.getElementById('branchId');

            if (branchNameSelect && branchIdSelect) {
                branchNameSelect.addEventListener('change', function () {
                    const selectedId = this.value;
                    branchIdSelect.innerHTML = '<option value="">Branch ID</option>';
                    if (selectedId) {
                        const branch = branches.find(b => b.branch_id === selectedId);
                        if (branch) {
                            const option = document.createElement('option');
                            option.value = branch.branch_id;
                            option.text = branch.branch_id;
                            branchIdSelect.appendChild(option);
                            branchIdSelect.value = branch.branch_id;
                        }
                    }
                });
            }

            // FOC Checkbox and Payment Calculation Handler
            const focCheck = document.getElementById('focCheck');
            const totalPaymentInput = document.getElementById('total_payment');
            const discountPaymentInput = document.getElementById('discount_payment');
            const givenPaymentInput = document.getElementById('paid_amount');
            const duePaymentInput = document.getElementById('due_payment');
            const paymentAmountInput = document.getElementById('payment_amount');
            const cashPaymentInput = document.getElementById('cash_payment');
            const gpPaymentInput = document.getElementById('gp_payment');
            const chequePaymentInput = document.getElementById('cheque_payment');

            function calculateDue() {
                if (!totalPaymentInput || !discountPaymentInput || !duePaymentInput) return;
                
                const cash = parseFloat(cashPaymentInput?.value) || 0;
                const gpay = parseFloat(gpPaymentInput?.value) || 0;
                const cheque = parseFloat(chequePaymentInput?.value) || 0;
                const given = cash + gpay + cheque;
                
                if (givenPaymentInput) {
                    givenPaymentInput.value = given.toFixed(2);
                }
                
                const total = parseFloat(totalPaymentInput.value) || 0;
                const discount = parseFloat(discountPaymentInput.value) || 0;
                const due = (total - discount) - given;
                duePaymentInput.value = Math.max(0, due).toFixed(2);
                
                // Auto-fill payment amount if it's empty
                if (paymentAmountInput && (!paymentAmountInput.value || paymentAmountInput.value == '0')) {
                    // paymentAmountInput.value = given; // Optional: auto-fill payment amount
                }
            }

            [totalPaymentInput, discountPaymentInput, cashPaymentInput, gpPaymentInput, chequePaymentInput].forEach(input => {
                if (input) {
                    input.addEventListener('input', calculateDue);
                }
            });

            if (focCheck) {
                const paymentRow = document.getElementById('paymentRow');
                focCheck.addEventListener('change', function () {
                    if (this.checked) {
                        if (paymentRow) paymentRow.style.display = 'none';
                        if (totalPaymentInput) totalPaymentInput.value = '0';
                        if (discountPaymentInput) discountPaymentInput.value = '0';
                        if (cashPaymentInput) cashPaymentInput.value = '0';
                        if (gpPaymentInput) gpPaymentInput.value = '0';
                        if (chequePaymentInput) chequePaymentInput.value = '0';
                        if (givenPaymentInput) givenPaymentInput.value = '0';
                        if (paymentAmountInput) paymentAmountInput.value = '0';
                    } else {
                        if (paymentRow) paymentRow.style.display = 'block';
                        if (totalPaymentInput) totalPaymentInput.value = '0';
                        if (discountPaymentInput && discountPaymentInput.value === '0') discountPaymentInput.value = '0';
                        if (cashPaymentInput && cashPaymentInput.value === '0') cashPaymentInput.value = '';
                        if (gpPaymentInput && gpPaymentInput.value === '0') gpPaymentInput.value = '';
                        if (chequePaymentInput && chequePaymentInput.value === '0') chequePaymentInput.value = '';
                    }
                    calculateDue();
                });

                // Initialize state
                if (focCheck.checked) {
                    if (paymentRow) paymentRow.style.display = 'none';
                    if (totalPaymentInput) totalPaymentInput.value = '0';
                    if (discountPaymentInput) discountPaymentInput.value = '0';
                    if (cashPaymentInput) cashPaymentInput.value = '0';
                    if (gpPaymentInput) gpPaymentInput.value = '0';
                    if (chequePaymentInput) chequePaymentInput.value = '0';
                    if (givenPaymentInput) givenPaymentInput.value = '0';
                    if (paymentAmountInput) paymentAmountInput.value = '0';
                }
            }

            // Calculate due on page load
            calculateDue();

            // Form submission confirmation
            const inquiryForm = document.getElementById('inquiryForm');
            if (inquiryForm) {
                inquiryForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you want to submit this inquiry?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#086838',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, submit it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading
                            const submitBtn = inquiryForm.querySelector('button[type="submit"]');
                            if (submitBtn) {
                                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Submitting...';
                                submitBtn.disabled = true;
                            }

                            // Submit form
                            inquiryForm.submit();
                        }
                    });
                });
            }

            // Auto-hide alerts
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    if (alert && alert.classList.contains('show')) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                });
            }, 5000);

            // Initialize Select2 for multiple selection
            function initSelect2(element) {
                $(element).select2({
                    placeholder: "Select Program",
                    allowClear: true,
                    width: '100%'
                });
            }

            $('.select2-area').each(function () {
                initSelect2(this);
            });

            // Auto-fill Area Code based on Program selection and calculate total price
            $(document).on('change', '.select2-area', function () {
                const selectedOptions = $(this).find('option:selected');
                const row = $(this).closest('.treatment-row');
                const areaCodeInput = row.find('.area-code-input');

                let shortNames = [];
                selectedOptions.each(function () {
                    const shortName = $(this).data('short-name');
                    if (shortName) {
                        shortNames.push(shortName);
                    }
                });

                areaCodeInput.val(shortNames.join(', '));
                
                // Calculate and show individual row price
                let rowPrice = 0;
                selectedOptions.each(function() {
                    const price = parseFloat($(this).data('price')) || 0;
                    rowPrice += price;
                });
                row.find('.row-price-display').val(rowPrice.toFixed(2));
                
                // Calculate total price from all selected programs across all rows
                calculateTotalProgramPrice();
            });

            function calculateTotalProgramPrice() {
                let totalPrice = 0;
                $('.row-price-display').each(function() {
                    const price = parseFloat($(this).val()) || 0;
                    totalPrice += price;
                });
                
                // Update Registration Charges (total_payment)
                if (totalPaymentInput && !focCheck.checked) {
                    totalPaymentInput.value = totalPrice.toFixed(2);
                    calculateDue();
                }
            }

            // Sync global total when individual row prices are manually changed
            $(document).on('input', '.row-price-display', function() {
                calculateTotalProgramPrice();
            });



            // Add Treatment Row
            let rowCount = 1;
            const container = document.getElementById('treatment_rows_container');
            const addBtn = document.getElementById('add_treatment_row');

            addBtn.addEventListener('click', function () {
                const firstRow = container.querySelector('.treatment-row');

                // If Select2 is initialized, we should destroy it before cloning to avoid cloning Select2 markup
                const existingSelect = $(firstRow).find('.select2-area');
                if (existingSelect.data('select2')) {
                    existingSelect.select2('destroy');
                }

                const newRow = firstRow.cloneNode(true);

                // Re-initialize Select2 on the first row
                initSelect2(existingSelect);

                // Reset inputs and update names/indices
                newRow.querySelectorAll('input, select').forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        let newName = name.replace(/\[\d+\]/, `[${rowCount}]`);
                        input.setAttribute('name', newName);
                    }

                    if (input.type === 'checkbox') {
                        input.checked = false;
                    } else if (input.tagName === 'SELECT') {
                        if (!input.classList.contains('select2-area')) {
                            input.selectedIndex = 0;
                        }
                    } else {
                        if (name && name.includes('treatment_date')) {
                            input.value = new Date().toISOString().split('T')[0];
                        } else if (name && name.includes('treatment_time')) {
                            const now = new Date();
                            input.value = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
                        } else {
                            input.value = '';
                        }
                    }
                });



                // Show remove button
                newRow.querySelector('.remove-row-btn').style.display = 'block';

                container.appendChild(newRow);

                const selectElement = newRow.querySelector('.select2-area');
                initSelect2(selectElement);
                
                // Reset price display for new row
                newRow.querySelector('.row-price-display').value = '0.00';
                
                rowCount++;
            });

            // Remove Treatment Row
            container.addEventListener('click', function (e) {
                if (e.target.closest('.remove-row-btn')) {
                    e.target.closest('.treatment-row').remove();
                }
            });

            // Dynamic Session Tracking
            function updateSessionBadge(row) {
                const areaSelect = $(row).find('.select2-area');
                const sessionInput = $(row).find('input[name="session[]"]');
                const badgeContainer = $(row).find('.session-status-container');
                const sessionText = badgeContainer.find('.session-text');
                const patientId = $('#current_patient_id').val();

                const selectedAreas = areaSelect.val();
                const totalSessions = parseInt(sessionInput.val()) || 0;

                if (patientId && selectedAreas && selectedAreas.length > 0 && totalSessions > 0) {
                    const primaryArea = selectedAreas[0];

                    $.ajax({
                        url: "{{ route('lhr.get.used.sessions') }}",
                        method: "GET",
                        data: {
                            patient_id: patientId,
                            area: primaryArea
                        },
                        success: function (response) {
                            const used = response.used_sessions || 0;
                            const remaining = Math.max(0, totalSessions - used);

                            sessionText.text(`Sessions: Total ${totalSessions}, Used ${used}, Remaining ${remaining}`);
                            badgeContainer.fadeIn();
                        },
                        error: function () {
                            badgeContainer.hide();
                        }
                    });
                } else if (totalSessions > 0) {
                    sessionText.text(`Sessions: Total ${totalSessions}, Used 0, Remaining ${totalSessions}`);
                    badgeContainer.fadeIn();
                } else {
                    badgeContainer.fadeOut();
                }
            }

            $(document).on('change', '.select2-area, input[name="session[]"]', function () {
                const row = $(this).closest('.treatment-row');
                updateSessionBadge(row);
            });

            // Toggle Area & Session based on Status
            const statusSelect = document.getElementById('status_name');
            const areaSessionSection = document.getElementById('area_session_section');
            
            function toggleAreaSession() {
                if (!statusSelect || !areaSessionSection) return;
                
                const isJoined = statusSelect.value === 'joined';
                console.log('Status changed:', statusSelect.value, 'Is Joined:', isJoined);
                
                if (isJoined) {
                    areaSessionSection.style.display = 'block';
                    // Auto-open the accordion if it's collapsed
                    const accordion = areaSessionSection.closest('.accordion-content');
                    if (accordion && accordion.classList.contains('collapsed')) {
                        const header = accordion.previousElementSibling;
                        if (header && header.classList.contains('section-divider')) {
                            toggleSection(header);
                        }
                    }
                } else {
                    areaSessionSection.style.display = 'none';
                }

                // Dynamically set/remove required attribute and labels
                const areaSelects = areaSessionSection.querySelectorAll('.select2-area');
                const sessionInputs = areaSessionSection.querySelectorAll('input[name="session[]"]');
                const sessionLabels = areaSessionSection.querySelectorAll('label[for="session"]');

                areaSelects.forEach(sel => {
                    sel.required = isJoined;
                });
                sessionInputs.forEach(input => {
                    input.required = isJoined;
                });
                sessionLabels.forEach(label => {
                    if (isJoined) label.classList.add('required');
                    else label.classList.remove('required');
                });
            }

            if (statusSelect) {
                statusSelect.addEventListener('change', toggleAreaSession);
                // Initial check
                setTimeout(() => {
                    toggleAreaSession();
                    // Initialize row prices on load
                    $('.select2-area').trigger('change');
                }, 100); 
            }
        });
    </script>


    <style>
        /* Custom Select2 styling to match theme */
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #ced4da;
            border-radius: 4px;
            min-height: 44px;
            padding: 4px 8px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #086838;
            border: 1px solid #086838;
            color: white;
            border-radius: 4px;
            padding: 2px 6px;
            margin-top: 6px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-right: 5px;
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            padding-right: 5px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            background: transparent;
            color: #ffdddd;
        }
    </style>

@endsection