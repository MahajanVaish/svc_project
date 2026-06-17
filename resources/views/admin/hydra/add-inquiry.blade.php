@extends('admin.layouts.layouts')

@section('title', 'Add Hydra Inquiry')

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
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .section-divider:hover {
            background: var(--accent-glow);
        }

        .section-divider-static {
            cursor: default;
        }

        .section-divider-static:hover {
            background: var(--bg-main);
        }

        .section-divider:after {
            content: "\f078";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            margin-left: auto;
            transition: transform 0.3s ease;
            font-size: 14px;
        }

        .section-divider-static:after {
            content: "";
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
            color: var(--text-secondary);
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .required:after {
            content: " *";
            color: #ef4444;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            font-size: 14px;
            background: var(--bg-main);
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
            background: var(--accent-solid);
            color: white;
            padding: 12px 35px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-submit:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            color: white;
        }

        .btn-cancel {
            background: var(--bg-card);
            border: 2px solid var(--border-subtle);
            padding: 12px 35px;
            border-radius: 8px;
            font-weight: 600;
            color: var(--text-secondary);
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: var(--bg-main);
            border-color: var(--text-secondary);
            color: var(--text-primary);
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
            padding: 20px;
            border: 1px solid var(--border-subtle);
            margin-bottom: 25px;
        }

        .branch-info-label {
            font-size: 11px;
            color: var(--text-muted);
            margin-bottom: 4px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .branch-info-value {
            font-weight: 700;
            color: var(--accent-solid);
            font-size: 16px;
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
            background-color: var(--bg-main);
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
            color: var(--text-secondary);
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
            background-color: var(--bg-main);
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
            background-color: var(--bg-main);
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
            background: var(--accent-solid);
        }

        .custom-radio-item input:checked~.radio-checkmark:after {
            display: block;
        }

        .radio-label {
            font-weight: 500;
            color: var(--text-secondary);
            font-size: 15px;
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
                    <h4 class="mb-0">Add New Hydra Inquiry</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('hydra.pending') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-11 col-lg-10 m-auto">
                <div class="form-container">
                    <form action="{{ route('hydra.add.inquiry.store') }}" method="POST" id="addInquiryForm">
                        @csrf

                        <div class="section-divider-static section-divider">Branch Information</div>
                        <!-- Branch Information -->
                        <div class="branch-info-card">
                            <div class="pro_filed mb-0">
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
                            </div>
                        </div>

                        <div class="section-divider active" onclick="toggleSection(this)">Patient Information</div>
                        <div class="accordion-content">
                            <div class="pro_filed">
                                <div class="form">
                                    <label for="patient_name" class="required">Patient Name</label>
                                    <input type="text" id="patient_name" name="patient_name"
                                        value="{{ old('patient_name') }}" required placeholder="Enter patient name">
                                </div>
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
                                    <label for="age" class="required">Age</label>
                                    <input type="number" id="age" name="age" value="{{ old('age') }}" required min="1"
                                        max="120" placeholder="Enter age">
                                </div>
                                <div class="form">
                                    <label for="phone_number">Phone Number</label>
                                    <input type="text" id="phone_number" name="phone_number"
                                        value="{{ old('phone_number') }}" placeholder="Enter phone number">
                                </div>
                            </div>

                            <div class="pro_filed">
                                <div class="form">
                                    <label for="inquiry_date" class="required">Inquiry Date</label>
                                    <input type="date" id="inquiry_date" name="inquiry_date"
                                        value="{{ old('inquiry_date', date('Y-m-d')) }}" required>
                                </div>
                                <div class="form">
                                    <label for="inquiry_time">Inquiry Time</label>
                                    <input type="time" id="inquiry_time" name="inquiry_time"
                                        value="{{ old('inquiry_time') }}">
                                </div>
                            </div>

                            <div class="pro_filed">
                                <div class="form">
                                    <label for="reference_by">Reference By</label>
                                    <input type="text" id="reference_by" name="reference_by"
                                        value="{{ old('reference_by') }}" placeholder="Reference person name">
                                </div>
                                <div class="form">
                                    <label for="status_name" class="required">Patient Status</label>
                                    <select id="status_name" name="status_name" required>
                                        <option value="pending" {{ old('status_name', 'pending') == 'pending' ? 'selected' : '' }}>Pending Status</option>
                                        <option value="joined" {{ old('status_name') == 'joined' ? 'selected' : '' }}>Joined
                                            Status</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div id="sessionFollowUpSection" style="display: none;">
                            <div class="section-divider active" onclick="toggleSection(this)">Session & Follow Up</div>
                            <div class="accordion-content">
                                <div class="pro_filed">
                                    <div class="form">
                                        <label for="session">Session Details</label>
                                        <input type="text" id="session" name="session" value="{{ old('session') }}"
                                            placeholder="Enter session details">
                                    </div>
                                    <div class="form">
                                        <label for="next_follow_up">Next Follow Up Date</label>
                                        <input type="date" id="next_follow_up" name="next_follow_up"
                                            value="{{ old('next_follow_up') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="section-divider active" onclick="toggleSection(this)">Health Metrics</div>
                            <div class="accordion-content">
                                <div class="pro_filed">
                                    <div class="form">
                                        <label for="diet">Diet</label>
                                        <input type="text" id="diet" name="diet" placeholder="Diet" value="{{ old('diet') }}">
                                    </div>
                                    <div class="form">
                                        <label for="exercise">Exercise</label>
                                        <input type="text" id="exercise" name="exercise" placeholder="Exercise"
                                            value="{{ old('exercise') }}">
                                    </div>
                                </div>
                                <div class="pro_filed">
                                    <div class="form">
                                        <label for="sleep">Sleep</label>
                                        <input type="text" id="sleep" name="sleep" placeholder="Sleep"
                                            value="{{ old('sleep') }}">
                                    </div>
                                    <div class="form">
                                        <label for="water">Water</label>
                                        <input type="text" id="water" name="water" placeholder="Water"
                                            value="{{ old('water') }}">
                                    </div>
                                </div>
                            </div> -->

                        <div class="section-divider active" onclick="toggleSection(this)">Payment Information</div>
                        <div class="accordion-content">
                            <div class="mb-3">
                                <label class="custom-checkbox-container">
                                    <input type="checkbox" id="focCheckbox" name="foc" value="1" {{ old('foc') ? 'checked' : '' }}>
                                    <span class="checkbox-checkmark"></span>
                                    <span class="checkbox-label">FOC (No payment is collected from patient)</span>
                                </label>
                            </div>

                            <div class="separate_payment">
                                <div class="pro_filed">
                                    <div class="form">
                                        <label for="totalPayment">Total Payment (₹)</label>
                                        <input type="number" step="0.01" name="total_payment" id="totalPayment"
                                            value="{{ old('total_payment') }}" placeholder="0.00">
                                    </div>
                                    <div class="form">
                                        <label for="givenPayment">Given Payment (₹)</label>
                                        <input type="number" step="0.01" name="given_payment" id="givenPayment"
                                            value="{{ old('given_payment') }}" placeholder="0.00" readonly style="background-color: #f8f9fa;">
                                    </div>
                                </div>

                                <div class="pro_filed mt-3">
                                    <div class="form">
                                        <label for="cash_payment">Cash Payment (₹)</label>
                                        <input type="number" step="0.01" name="cash_payment" id="cash_payment" class="payment-input" placeholder="0.00" value="{{ old('cash_payment') }}">
                                    </div>
                                    <div class="form">
                                        <label for="gp_payment">G-Pay Payment (₹)</label>
                                        <input type="number" step="0.01" name="gp_payment" id="gp_payment" class="payment-input" placeholder="0.00" value="{{ old('gp_payment') }}">
                                    </div>
                                    <div class="form">
                                        <label for="cheque_payment">Cheque Payment (₹)</label>
                                        <input type="number" step="0.01" name="cheque_payment" id="cheque_payment" class="payment-input" placeholder="0.00" value="{{ old('cheque_payment') }}">
                                    </div>
                                    <div class="form">
                                        <label for="duePayment">Due Payment (₹)</label>
                                        <input type="number" step="0.01" name="due_payment" id="duePayment"
                                            value="{{ old('due_payment') }}" readonly placeholder="0.00"
                                            style="background-color: #f8f9fa;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-5 text-center">
                            <button type="submit" class="btn btn-submit me-3 mb-3 mb-3">
                                <i class="fas fa-save me-2"></i> Submit Inquiry
                            </button>
                            <a href="{{ route('hydra.pending') }}" class="btn btn-cancel">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSection(header) {
            header.classList.toggle('active');
            const content = header.nextElementSibling;
            content.classList.toggle('collapsed');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const focCheckbox = document.getElementById('focCheckbox');
            const totalPayment = document.getElementById('totalPayment');
            const discountPayment = document.getElementById('discountPayment');
            const givenPayment = document.getElementById('givenPayment');
            const duePayment = document.getElementById('duePayment');
            const cashPayment = document.getElementById('cash_payment');
            const gpPayment = document.getElementById('gp_payment');
            const chequePayment = document.getElementById('cheque_payment');
            const statusNameSelect = document.getElementById('status_name');
            const sessionFollowUpSection = document.getElementById('sessionFollowUpSection');

            function toggleSessionFollowUp() {
                if (statusNameSelect.value === 'joined') {
                    sessionFollowUpSection.style.display = 'block';
                } else {
                    sessionFollowUpSection.style.display = 'none';
                }
            }

            if (statusNameSelect) {
                statusNameSelect.addEventListener('change', toggleSessionFollowUp);
                toggleSessionFollowUp(); // Initial check
            }

            const paymentInputs = [totalPayment, cashPayment, gpPayment, chequePayment];

            function calculateDuePayment() {
                const total = parseFloat(totalPayment?.value) || 0;
                const cash = parseFloat(cashPayment?.value) || 0;
                const gpay = parseFloat(gpPayment?.value) || 0;
                const cheque = parseFloat(chequePayment?.value) || 0;
                const given = cash + gpay + cheque;
                
                if (givenPayment) givenPayment.value = given.toFixed(2);

                const due = total - given;
                if (duePayment) duePayment.value = Math.max(0, due).toFixed(2);

                if (duePayment) {
                    if (due > 0) {
                        duePayment.style.color = '#ef4444';
                    } else {
                        duePayment.style.color = 'var(--accent-solid)';
                    }
                }
            }

            paymentInputs.forEach(input => {
                if (input && input.id !== 'duePayment') {
                    input.addEventListener('input', calculateDuePayment);
                }
            });

            const separatePaymentSection = document.querySelector('.separate_payment');

            if (focCheckbox) {
                focCheckbox.addEventListener('change', function () {
                    if (this.checked) {
                        separatePaymentSection.style.display = 'none';
                        paymentInputs.forEach(field => {
                            if (field) {
                                field.value = '0';
                            }
                        });
                        duePayment.value = '0.00';
                    } else {
                        separatePaymentSection.style.display = 'block';
                        paymentInputs.forEach(field => {
                            if (field && field.value === '0') {
                                field.value = '';
                            }
                        });
                        calculateDuePayment();
                    }
                });

                if (focCheckbox.checked) {
                    focCheckbox.dispatchEvent(new Event('change'));
                }
            }

            calculateDuePayment();

            // Form submission confirmation
            const addInquiryForm = document.getElementById('addInquiryForm');
            if (addInquiryForm) {
                addInquiryForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you want to add this new inquiry?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: 'var(--accent-solid)',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, add it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading state
                            const submitBtn = addInquiryForm.querySelector('button[type="submit"]');
                            if (submitBtn) {
                                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Submitting...';
                                submitBtn.disabled = true;
                            }
                            addInquiryForm.submit();
                        }
                    });
                });
            }
        });
    </script>

@endsection