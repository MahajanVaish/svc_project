@extends('admin.layouts.layouts')

@section('content')

    <style>
        .section-divider {
            display: flex;
            align-items: center;
            width: 100%;
            margin: 30px 0 20px;
            font-size: 16px;
            font-weight: 600;
            color: #067945;
        }

        .section-divider:after {
            content: "";
            flex-grow: 1;
            height: 1px;
            background: #dcdcdc;
            margin-left: 15px;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #dee2e6;
            margin-bottom: 2rem;
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
            color: #5a6268;
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
            border: 1px solid #bdc3c7;
            border-radius: 8px;
            font-size: 14px;
            background: #f8fafc;
            transition: all 0.3s ease;
            outline: none;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #067945;
            box-shadow: 0 0 0 3px rgba(6, 121, 69, 0.1);
            background: white;
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
        }

        .btn-cancel:hover {
            background: #f8f9fa;
            border-color: #adb5bd;
            color: #495057;
        }

        .separate_payment {
            padding: 25px;
            border: 1px solid #e2f2ea !important;
            border-radius: 12px;
            background: #f9fdfc;
            margin-top: 20px;
        }

        /* Page Title Styling */
        .page-title-box h4 {
            color: #006637;
            font-size: 24px;
            font-weight: 600;
        }

        /* Patient Info Card */
        .patient-info-card {
            background: #f9fdfc;
            border-radius: 12px;
            padding: 25px 35px;
            border: 1px solid #e1f2ea;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .patient-info-label {
            font-size: 11px;
            color: #8c98a5;
            margin-bottom: 4px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .patient-info-details h2 {
            color: #006637;
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 5px 0;
            line-height: 1.2;
        }

        .patient-id-badge {
            font-weight: 600;
            color: #5a6268;
            font-size: 14px;
            display: inline-block;
        }

        .patient-meta {
            text-align: right;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .patient-meta-item {
            font-size: 14px;
            color: #5a6268;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .patient-meta-item i {
            color: #0d6efd;
            margin-right: 10px;
            width: 16px;
            text-align: center;
            font-size: 14px;
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
            background-color: #fff;
            border: 2px solid #bdc3c7;
            border-radius: 6px;
            margin-right: 12px;
            position: relative;
            transition: all 0.3s ease;
        }

        .custom-checkbox-container input:checked~.checkbox-checkmark {
            background-color: #0d6efd;
            border-color: #0d6efd;
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
            font-weight: 600;
            color: #067945;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .pro_filed {
                flex-direction: column;
                gap: 15px;
            }

            .patient-info-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .patient-meta {
                text-align: left;
            }
        }
    </style>

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-11 col-lg-10 m-auto">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Follow Up</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('hydra.patient.profile', $inquiry->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-11 col-lg-10 m-auto">
                <div class="form-container">
                    <form action="{{ route('hydra.followup.store', $inquiry->id) }}" method="POST" id="addFollowUpForm">
                        @csrf

                        <!-- Patient Info Card -->
                        <div class="patient-info-card">
                            <div class="patient-info-details">
                                <div class="patient-info-label">Patient Name</div>
                                <h2>{{ $inquiry->patient_name }}</h2>
                                <div class="patient-id-badge">ID: {{ $inquiry->patient_id }}</div>
                            </div>
                            <div class="patient-meta">
                                <div class="patient-meta-item">
                                    <i class="fas fa-phone-alt"></i>
                                    <span>{{ $inquiry->phone_number ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="section-divider">Patient Details</div>

                        <div class="pro_filed">
                            <div class="form">
                                <label class="required">Patient Name</label>
                                <input type="text" name="patient_name"
                                    value="{{ old('patient_name', $inquiry->patient_name) }}" required readonly>
                            </div>
                            <div class="form">
                                <label class="required">Gender</label>
                                <select name="gender" required readonly>
                                    <option value="">Select</option>
                                    <option value="male" {{ old('gender', $inquiry->gender) == 'male' ? 'selected' : '' }}>
                                        Male</option>
                                    <option value="female" {{ old('gender', $inquiry->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $inquiry->gender) == 'other' ? 'selected' : '' }}>
                                        Other</option>
                                </select>
                            </div>
                            <div class="form">
                                <label class="required">Age</label>
                                <input type="number" name="age" value="{{ old('age', $inquiry->age) }}" min="1" max="120"
                                    required>
                            </div>
                        </div>

                        <div class="section-divider">Follow Up</div>

                        <div class="pro_filed">
                            <div class="form">
                                <label class="required">Follow Up Date</label>
                                <input type="date" name="follow_up_date" value="{{ old('follow_up_date', date('Y-m-d')) }}"
                                    required>
                            </div>
                            <div class="form">
                                <label>Follow Up Time</label>
                                <input type="time" name="follow_up_time" value="{{ old('follow_up_time') }}">
                            </div>
                        </div>

                        <div class="pro_filed">
                            <div class="form">
                                <label>Next Follow Up Date</label>
                                <input type="date" name="next_follow_up_date" value="{{ old('next_follow_up_date') }}">
                            </div>
                        </div>

                        <div class="section-divider">Payment Information</div>

                        <div class="mb-3">
                            <label class="custom-checkbox-container">
                                <input type="checkbox" id="foc" name="foc" value="1" {{ old('foc') ? 'checked' : '' }}>
                                <span class="checkbox-checkmark"></span>
                                <span class="checkbox-label">FOC (No payment collected)</span>
                            </label>
                        </div>

                        <div class="separate_payment">
                            <div class="pro_filed">
                                <div class="form">
                                    <label>Total Payment (₹)</label>
                                    <input type="number" name="total_payment"
                                        value="{{ old('total_payment', $inquiry->total_payment) }}" min="0" step="0.01">
                                </div>
                                <div class="form">
                                    <label>Discount (₹)</label>
                                    <input type="number" name="discount_payment"
                                        value="{{ old('discount_payment', $inquiry->discount_payment) }}" min="0"
                                        step="0.01">
                                </div>
                                <div class="form">
                                    <label>Given Payment (₹)</label>
                                    <input type="number" name="given_payment"
                                        value="{{ old('given_payment', $inquiry->given_payment) }}" min="0" step="0.01">
                                </div>
                            </div>

                            <div class="pro_filed mt-3">
                                <div class="form">
                                    <label>Cash Payment (₹)</label>
                                    <input type="number" name="cash_payment"
                                        value="{{ old('cash_payment', $inquiry->cash_payment) }}" min="0" step="0.01">
                                </div>
                                <div class="form">
                                    <label>Google Pay (₹)</label>
                                    <input type="number" name="google_pay"
                                        value="{{ old('google_pay', $inquiry->google_pay) }}" min="0" step="0.01">
                                </div>
                            </div>
                        </div>

                        <div class="section-divider">Other Info</div>

                        <div class="pro_filed">
                            <div class="form">
                                <label>Phone Number</label>
                                <input type="text" name="phone_number"
                                    value="{{ old('phone_number', $inquiry->phone_number) }}">
                            </div>
                            <div class="form">
                                <label>Session</label>
                                <input type="text" name="session" value="{{ old('session', $inquiry->session) }}">
                            </div>
                        </div>

                        <div class="pro_filed">
                            <div class="form">
                                <label>Notes</label>
                                <textarea name="notes" rows="3">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-5 text-center">
                            <button type="submit" class="btn btn-submit me-3 mb-3">
                                <i class="fas fa-save me-2"></i> Save Follow Up
                            </button>
                            <button type="reset" class="btn btn-cancel">
                                <i class="fas fa-redo me-2"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-fill current date if empty
            const followUpDate = document.querySelector('input[name="follow_up_date"]');
            if (followUpDate && !followUpDate.value) {
                const today = new Date().toISOString().split('T')[0];
                followUpDate.value = today;
            }

            // Auto-fill current time if empty
            const followUpTime = document.querySelector('input[name="follow_up_time"]');
            if (followUpTime && !followUpTime.value) {
                const now = new Date();
                const hours = now.getHours().toString().padStart(2, '0');
                const minutes = now.getMinutes().toString().padStart(2, '0');
                followUpTime.value = `${hours}:${minutes}`;
            }

            // FOC checkbox functionality
            const focCheckbox = document.getElementById('foc');
            const separatePaymentSection = document.querySelector('.separate_payment');
            const paymentInputs = document.querySelectorAll('input[name*="payment"], input[name="google_pay"]');

            function handleFOC() {
                if (focCheckbox.checked) {
                    separatePaymentSection.style.display = 'none';
                    paymentInputs.forEach(input => {
                        input.value = '0';
                        input.disabled = true;
                    });
                } else {
                    separatePaymentSection.style.display = 'block';
                    paymentInputs.forEach(input => {
                        input.disabled = false;
                    });
                }
            }

            if (focCheckbox) {
                focCheckbox.addEventListener('change', handleFOC);
                // Initial check
                handleFOC();
            }

            // Form submission confirmation
            const addFollowUpForm = document.getElementById('addFollowUpForm');
            if (addFollowUpForm) {
                addFollowUpForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    Swal.fire({
                        ...getSwalConfig('question'),
                        title: 'Are you sure?',
                        text: 'Do you want to save this follow-up record?',
                        confirmButtonText: 'Yes, save it!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            }
        });
    </script>

@endsection