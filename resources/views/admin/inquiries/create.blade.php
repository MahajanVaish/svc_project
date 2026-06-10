@extends('admin.layouts.layouts')

@section('content')
<div class="container-fluid">
    <!-- Page Header - Match Screenshot -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0" style="color: #333; font-weight: 600;">
                    New Inquiry
                </h1>
                <div>
                    <a href="{{ route('followup.patients.appointment') }}" class="btn btn-outline-secondary btn-sm me-2" 
                       style="font-size: 13px; padding: 6px 15px; border-radius: 4px;">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- New Inquiry Form - Match Screenshot Exactly -->
    <div class="card border-0 shadow-sm" style="border-radius: 8px;">
        <div class="card-body p-4">
            <form method="POST" action="{{ url('/add-inquiry') }}" id="inquiryForm">
                @csrf
                
                <div class="row">
                    <!-- Column 1 -->
                    <div class="col-md-6">
                        <!-- Select branch -->
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 13px; font-weight: 500; color: #333; margin-bottom: 6px;">
                                Select branch
                            </label>
                            <select name="branch" class="form-control form-control-sm" 
                                    style="font-size: 13px; padding: 8px 12px; border-radius: 4px; border: 1px solid #ced4da;">
                                <option value="">Select Branch</option>
                                <option value="Main Branch">Main Branch</option>
                                <option value="City Center">City Center</option>
                                <option value="Westside Clinic">Westside Clinic</option>
                                <option value="North Clinic">North Clinic</option>
                                <option value="South Clinic">South Clinic</option>
                            </select>
                        </div>

                        <!-- First Name -->
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 13px; font-weight: 500; color: #333; margin-bottom: 6px;">
                                First Name
                            </label>
                            <input type="text" name="first_name" class="form-control form-control-sm" required
                                   style="font-size: 13px; padding: 8px 12px; border-radius: 4px; border: 1px solid #ced4da;"
                                   placeholder="Enter first name">
                        </div>

                        <!-- Middle Name -->
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 13px; font-weight: 500; color: #333; margin-bottom: 6px;">
                                Middle Name
                            </label>
                            <input type="text" name="middle_name" class="form-control form-control-sm"
                                   style="font-size: 13px; padding: 8px 12px; border-radius: 4px; border: 1px solid #ced4da;"
                                   placeholder="Enter middle name">
                        </div>

                        <!-- Last Name -->
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 13px; font-weight: 500; color: #333; margin-bottom: 6px;">
                                Last Name
                            </label>
                            <input type="text" name="last_name" class="form-control form-control-sm"
                                   style="font-size: 13px; padding: 8px 12px; border-radius: 4px; border: 1px solid #ced4da;"
                                   placeholder="Enter last name">
                        </div>

                        <!-- Select Gender -->
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 13px; font-weight: 500; color: #333; margin-bottom: 6px;">
                                Select Gender
                            </label>
                            <select name="gender" class="form-control form-control-sm"
                                    style="font-size: 13px; padding: 8px 12px; border-radius: 4px; border: 1px solid #ced4da;">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Email ID -->
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 13px; font-weight: 500; color: #333; margin-bottom: 6px;">
                                Email ID
                            </label>
                            <input type="email" name="email" class="form-control form-control-sm"
                                   style="font-size: 13px; padding: 8px 12px; border-radius: 4px; border: 1px solid #ced4da;"
                                   placeholder="Enter email address">
                        </div>

                        <!-- Height (cm) -->
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 13px; font-weight: 500; color: #333; margin-bottom: 6px;">
                                Height (cm)
                            </label>
                            <input type="number" name="height" class="form-control form-control-sm"
                                   style="font-size: 13px; padding: 8px 12px; border-radius: 4px; border: 1px solid #ced4da;"
                                   placeholder="Enter height in cm" step="0.1" min="0">
                        </div>

                        <!-- BMI -->
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 13px; font-weight: 500; color: #333; margin-bottom: 6px;">
                                BMI
                            </label>
                            <input type="number" name="bmi" class="form-control form-control-sm"
                                   style="font-size: 13px; padding: 8px 12px; border-radius: 4px; border: 1px solid #ced4da;"
                                   placeholder="BMI will be calculated" step="0.01" min="0" readonly>
                        </div>

                        <!-- Reference By -->
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 13px; font-weight: 500; color: #333; margin-bottom: 6px;">
                                Reference By
                            </label>
                            <input type="text" name="reference_by" class="form-control form-control-sm"
                                   style="font-size: 13px; padding: 8px 12px; border-radius: 4px; border: 1px solid #ced4da;"
                                   placeholder="Enter reference">
                        </div>

                        <!-- Time -->
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 13px; font-weight: 500; color: #333; margin-bottom: 6px;">
                                Time
                            </label>
                            <input type="time" name="time" class="form-control form-control-sm"
                                   style="font-size: 13px; padding: 8px 12px; border-radius: 4px; border: 1px solid #ced4da;"
                                   value="2:47 PM" readonly>
                        </div>

                        <!-- Diagnosis -->
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 13px; font-weight: 500; color: #333; margin-bottom: 6px;">
                                Diagnosis
                            </label>
                            <textarea name="diagnosis" class="form-control form-control-sm" rows="3"
                                      style="font-size: 13px; padding: 8px 12px; border-radius: 4px; border: 1px solid #ced4da;"
                                      placeholder="Enter diagnosis"></textarea>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="col-md-6">
                        <!-- Phone Number -->
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 13px; font-weight: 500; color: #333; margin-bottom: 6px;">
                                Phone Number
                            </label>
                            <input type="tel" name="phone" class="form-control form-control-sm" required
                                   style="font-size: 13px; padding: 8px 12px; border-radius: 4px; border: 1px solid #ced4da;"
                                   placeholder="Enter phone number">
                        </div>

                        <!-- Age -->
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 13px; font-weight: 500; color: #333; margin-bottom: 6px;">
                                Age
                            </label>
                            <input type="number" name="age" class="form-control form-control-sm"
                                   style="font-size: 13px; padding: 8px 12px; border-radius: 4px; border: 1px solid #ced4da;"
                                   placeholder="Enter age" min="0" max="150">
                        </div>

                        <!-- Weight (kg) -->
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 13px; font-weight: 500; color: #333; margin-bottom: 6px;">
                                Weight (kg)
                            </label>
                            <input type="number" name="weight" class="form-control form-control-sm"
                                   style="font-size: 13px; padding: 8px 12px; border-radius: 4px; border: 1px solid #ced4da;"
                                   placeholder="Enter weight in kg" step="0.1" min="0">
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 13px; font-weight: 500; color: #333; margin-bottom: 6px;">
                                Address
                            </label>
                            <textarea name="address" class="form-control form-control-sm" rows="2"
                                      style="font-size: 13px; padding: 8px 12px; border-radius: 4px; border: 1px solid #ced4da;"
                                      placeholder="Enter address"></textarea>
                        </div>

                        <!-- Date -->
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 13px; font-weight: 500; color: #333; margin-bottom: 6px;">
                                Date
                            </label>
                            <input type="date" name="date" class="form-control form-control-sm"
                                   style="font-size: 13px; padding: 8px 12px; border-radius: 4px; border: 1px solid #ced4da;"
                                   value="04/12/2025" readonly>
                        </div>

                        <!-- Inquiry by -->
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 13px; font-weight: 500; color: #333; margin-bottom: 6px;">
                                Inquiry by
                            </label>
                            <input type="text" name="inquiry_by" class="form-control form-control-sm"
                                   style="font-size: 13px; padding: 8px 12px; border-radius: 4px; border: 1px solid #ced4da;"
                                   placeholder="Enter inquiry by">
                        </div>

                        <!-- Select Client -->
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 13px; font-weight: 500; color: #333; margin-bottom: 6px;">
                                Select Client
                            </label>
                            <select name="client" class="form-control form-control-sm"
                                    style="font-size: 13px; padding: 8px 12px; border-radius: 4px; border: 1px solid #ced4da;">
                                <option value="">Select Client</option>
                                <option value="Walk-in">Walk-in</option>
                                <option value="Referral">Referral</option>
                                <option value="Online">Online</option>
                                <option value="Phone">Phone</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <!-- Payment -->
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 13px; font-weight: 500; color: #333; margin-bottom: 6px;">
                                Payment
                            </label>
                            <select name="payment" class="form-control form-control-sm"
                                    style="font-size: 13px; padding: 8px 12px; border-radius: 4px; border: 1px solid #ced4da;">
                                <option value="">Select Payment Status</option>
                                <option value="Paid">Paid</option>
                                <option value="Pending">Pending</option>
                                <option value="Partial">Partial</option>
                                <option value="Free">Free</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- FOC Checkbox -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="foc" id="focCheckbox" value="1">
                            <label class="form-check-label" for="focCheckbox" style="font-size: 13px; color: #333;">
                                FOC (No payment is collected from patient)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-sm"
                                    style="font-size: 13px; padding: 8px 30px; border-radius: 4px;">
                                <i class="fas fa-paper-plane me-1"></i> Submit
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for BMI Calculation and Form Handling -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get height and weight inputs
        const heightInput = document.querySelector('input[name="height"]');
        const weightInput = document.querySelector('input[name="weight"]');
        const bmiInput = document.querySelector('input[name="bmi"]');
        
        // Function to calculate BMI
        function calculateBMI() {
            const height = parseFloat(heightInput.value) / 100; // Convert cm to meters
            const weight = parseFloat(weightInput.value);
            
            if (height > 0 && weight > 0) {
                const bmi = weight / (height * height);
                bmiInput.value = bmi.toFixed(2);
            } else {
                bmiInput.value = '';
            }
        }
        
        // Add event listeners for BMI calculation
        if (heightInput && weightInput && bmiInput) {
            heightInput.addEventListener('input', calculateBMI);
            weightInput.addEventListener('input', calculateBMI);
        }
        
        // Set current date and time
        function setCurrentDateTime() {
            const now = new Date();
            
            // Format date as DD/MM/YYYY
            const day = String(now.getDate()).padStart(2, '0');
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const year = now.getFullYear();
            
            // Format time as HH:MM AM/PM
            let hours = now.getHours();
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            const timeStr = `${hours}:${minutes} ${ampm}`;
            
            // Set date and time fields
            const dateField = document.querySelector('input[name="date"]');
            const timeField = document.querySelector('input[name="time"]');
            
            if (dateField) {
                dateField.value = `${day}/${month}/${year}`;
            }
            
            if (timeField) {
                timeField.value = timeStr;
            }
        }
        
        // Call function to set current date and time
        setCurrentDateTime();
        
        // Form validation
        const form = document.getElementById('inquiryForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const firstName = document.querySelector('input[name="first_name"]').value.trim();
                const phone = document.querySelector('input[name="phone"]').value.trim();
                
                if (!firstName) {
                    e.preventDefault();
                    alert('Please enter first name');
                    document.querySelector('input[name="first_name"]').focus();
                    return false;
                }
                
                if (!phone) {
                    e.preventDefault();
                    alert('Please enter phone number');
                    document.querySelector('input[name="phone"]').focus();
                    return false;
                }
                
                // Phone number validation (basic)
                const phoneRegex = /^[0-9+\-\s()]{10,}$/;
                if (!phoneRegex.test(phone)) {
                    e.preventDefault();
                    alert('Please enter a valid phone number (minimum 10 digits)');
                    document.querySelector('input[name="phone"]').focus();
                    return false;
                }
                
                return true;
            });
        }
        
        // Auto-format phone number
        const phoneInput = document.querySelector('input[name="phone"]');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 10) {
                    value = value.substring(0, 10);
                }
                
                // Format as XXX-XXX-XXXX
                if (value.length > 6) {
                    value = value.substring(0, 6) + '-' + value.substring(6);
                }
                if (value.length > 3) {
                    value = value.substring(0, 3) + '-' + value.substring(3);
                }
                
                e.target.value = value;
            });
        }
        
        // Age validation
        const ageInput = document.querySelector('input[name="age"]');
        if (ageInput) {
            ageInput.addEventListener('input', function(e) {
                let value = parseInt(e.target.value);
                if (isNaN(value)) {
                    e.target.value = '';
                } else if (value < 0) {
                    e.target.value = 0;
                } else if (value > 150) {
                    e.target.value = 150;
                }
            });
        }
        
        // Height validation
        const heightInputField = document.querySelector('input[name="height"]');
        if (heightInputField) {
            heightInputField.addEventListener('input', function(e) {
                let value = parseFloat(e.target.value);
                if (isNaN(value)) {
                    e.target.value = '';
                } else if (value < 0) {
                    e.target.value = 0;
                } else if (value > 300) {
                    e.target.value = 300;
                }
            });
        }
        
        // Weight validation
        const weightInputField = document.querySelector('input[name="weight"]');
        if (weightInputField) {
            weightInputField.addEventListener('input', function(e) {
                let value = parseFloat(e.target.value);
                if (isNaN(value)) {
                    e.target.value = '';
                } else if (value < 0) {
                    e.target.value = 0;
                } else if (value > 500) {
                    e.target.value = 500;
                }
            });
        }
        
        // FOC Checkbox functionality
        const focCheckbox = document.getElementById('focCheckbox');
        const paymentSelect = document.querySelector('select[name="payment"]');
        
        if (focCheckbox && paymentSelect) {
            focCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    paymentSelect.value = 'Free';
                    paymentSelect.disabled = true;
                    paymentSelect.style.backgroundColor = '#f8f9fa';
                } else {
                    paymentSelect.disabled = false;
                    paymentSelect.style.backgroundColor = '';
                    if (paymentSelect.value === 'Free') {
                        paymentSelect.value = '';
                    }
                }
            });
        }
        
        // Success/Error message handling
        @if(session('success'))
            alert('{{ session('success') }}');
        @endif
        
        @if(session('error'))
            alert('Error: {{ session('error') }}');
        @endif
    });
</script>

<!-- CSS for additional styling -->
<style>
    /* Form styling */
    .form-control-sm {
        border-radius: 4px;
        border: 1px solid #ced4da;
        transition: all 0.2s;
        height: 36px;
        font-size: 13px;
    }
    
    .form-control-sm:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        outline: 0;
    }
    
    textarea.form-control-sm {
        height: auto;
        min-height: 80px;
        resize: vertical;
    }
    
    .form-label {
        font-weight: 500;
        color: #333;
        display: block;
        margin-bottom: 6px;
        font-size: 13px;
    }
    
    /* Card styling */
    .card {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 8px;
        background-color: #fff;
    }
    
    .card-body {
        padding: 24px;
    }
    
    /* Button styling */
    .btn {
        font-weight: 500;
        transition: all 0.2s;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }
    
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        padding: 8px 30px;
        border-radius: 4px;
    }
    
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    /* Checkbox styling */
    .form-check-input {
        width: 16px;
        height: 16px;
        margin-top: 0.2em;
        border-radius: 3px;
        border: 1px solid #ced4da;
    }
    
    .form-check-input:checked {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .form-check-label {
        font-size: 13px;
        color: #333;
        margin-left: 5px;
        cursor: pointer;
    }
    
    /* Required field indicator */
    input:required, select:required {
        border-left: 3px solid #dc3545 !important;
    }
    
    /* Readonly field styling */
    input[readonly] {
        background-color: #f8f9fa;
        cursor: not-allowed;
        color: #6c757d;
    }
    
    /* Disabled select styling */
    select:disabled {
        background-color: #f8f9fa;
        cursor: not-allowed;
        color: #6c757d;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-body {
            padding: 20px;
        }
        
        .col-md-6 {
            margin-bottom: 15px;
        }
        
        .d-flex.justify-content-end {
            justify-content: center !important;
        }
        
        .btn-primary {
            width: 100%;
        }
    }
    
    @media (max-width: 576px) {
        .card-body {
            padding: 16px;
        }
        
        .h3 {
            font-size: 20px;
        }
    }
    
    /* Page header styling */
    .h3 {
        color: #333;
        font-weight: 600;
        font-size: 24px;
    }
    
    /* Row spacing */
    .row {
        margin-bottom: 10px;
    }
    
    .mb-3 {
        margin-bottom: 16px !important;
    }
    
    /* Back button styling */
    .btn-outline-secondary.btn-sm {
        border: 1px solid #dee2e6;
        color: #495057;
    }
    
    /* Submit button icon */
    .btn-primary i {
        font-size: 12px;
    }
</style>
@endsection