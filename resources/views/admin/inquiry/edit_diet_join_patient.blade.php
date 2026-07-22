@extends('admin.layouts.layouts')

@section('title', 'Edit Diet Chart - ' . ($patient->patient_name ?? 'Patient'))

@section('content')
    @php
        $latestMeta = $optMeta;
    @endphp
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-0">
                    <h2 class="mb-0" style="color: var(--accent-solid);">
                        <i class="fas fa-edit"></i> Edit Diet Chart - {{ $patient->patient_name ?? 'Patient' }}
                    </h2>
                    <div class="d-flex gap-2">
                        <a href="javascript:history.back()" class="btn btn-outline-secondary shadow-sm"
                            style="border-radius: 8px; font-weight: 500;">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

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
        </style>
        <form action="{{ route('update.diet.chart', $optData->id) }}" method="POST" id="dietChartForm"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="patient_id" value="{{ $patient->patient_id ?? '' }}">
            <input type="hidden" name="patient_name" value="{{ $patient->patient_name ?? '' }}">
            <input type="hidden" name="branch_id" value="{{ $patient->branch_id ?? '' }}">
            <input type="hidden" name="branch" value="{{ $patient->branch ?? '' }}">
            <input type="hidden" name="redirect_to" value="{{ $redirect_to ?? '' }}">

            <div class="card">
                <div class="card-body">
                    <!-- Patient Info Summary -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h4 class="border-bottom pb-2 mb-3">Patient Details</h4>
                        </div>

                        <!-- First Row - Basic Info -->
                        <div class="col-md-12 mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Patient ID:</label>
                                    <input type="text" class="form-control" value="{{ $patient->patient_id ?? 'N/A' }}"
                                        readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Patient Name:</label>
                                    <input type="text" class="form-control" name="patient_name"
                                        value="{{ $patient->patient_f_name ?? '' }}" placeholder="Enter patient name"
                                        readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Branch Name:</label>
                                    <input type="text" class="form-control" name="branch_name"
                                        value="{{ $patient->branch ?? '' }}" placeholder="Enter branch name" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date:</label>
                                    <input type="date" class="form-control" name="pod_bd_date"
                                        value="{{ $latestMeta['pod_bd_date'] ?? date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Second Row - Medical Info -->
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Birth Date:</label>
                                    <input type="date" class="form-control" name="birth_date"
                                        value="{{ $patient->dob ?? $latestMeta['birth_date'] ?? '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Blood Group:</label>
                                    <select class="form-select" name="blood_group">
                                        <option value="">Select Blood Group</option>
                                        <option value="A+" {{ ($optData->blood_group ?? '') == 'A+' ? 'selected' : '' }}>A+
                                        </option>
                                        <option value="A-" {{ ($optData->blood_group ?? '') == 'A-' ? 'selected' : '' }}>A-
                                        </option>
                                        <option value="B+" {{ ($optData->blood_group ?? '') == 'B+' ? 'selected' : '' }}>B+
                                        </option>
                                        <option value="B-" {{ ($optData->blood_group ?? '') == 'B-' ? 'selected' : '' }}>B-
                                        </option>
                                        <option value="O+" {{ ($optData->blood_group ?? '') == 'O+' ? 'selected' : '' }}>O+
                                        </option>
                                        <option value="O-" {{ ($optData->blood_group ?? '') == 'O-' ? 'selected' : '' }}>O-
                                        </option>
                                        <option value="AB+" {{ ($optData->blood_group ?? '') == 'AB+' ? 'selected' : '' }}>AB+
                                        </option>
                                        <option value="AB-" {{ ($optData->blood_group ?? '') == 'AB-' ? 'selected' : '' }}>AB-
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">ideal Body Weight:</label>
                                    <input type="number" step="0.01" class="form-control" name="lead_body_weight"
                                        placeholder="Enter lead body weight"
                                        value="{{ $latestMeta['lead_body_weight'] ?? $patient->lead_body_weight ?? '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Under Weight:</label>
                                    <input type="number" step="0.01" class="form-control" name="under_weight"
                                        placeholder="Enter under weight"
                                        value="{{ $latestMeta['under_weight'] ?? $patient->under_weight ?? '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Over Weight:</label>
                                    <input type="number" step="0.01" class="form-control" name="over_weight"
                                        placeholder="Enter over weight"
                                        value="{{ $latestMeta['over_weight'] ?? $patient->over_weight ?? '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Target Weight:</label>
                                    <input type="number" step="0.01" class="form-control" name="target_weight"
                                        placeholder="Enter target weight"
                                        value="{{ $latestMeta['target_weight'] ?? $patient->target_weight ?? '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">PA/H:</label>
                                    <input type="number" step="0.01" class="form-control" name="pa_h"
                                        placeholder="Enter PA/H value"
                                        value="{{ $latestMeta['pa_h'] ?? $patient->pa_h ?? '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">F/H:</label>
                                    <input type="number" step="0.01" class="form-control" name="f_h"
                                        placeholder="Enter F/H value"
                                        value="{{ $latestMeta['f_h'] ?? $patient->f_h ?? '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">BMI:</label>
                                    <input type="text" class="form-control" name="pod_bmr" id="bmiInput"
                                        value="{{ $latestMeta['pod_bmr'] ?? $patient->bmi ?? '' }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">BMR:</label>
                                    <input type="text" class="form-control" name="pod_bmr_value" id="bmrValueInput"
                                        value="{{ $latestMeta['pod_bmr_value'] ?? '' }}">
                                </div>
                            </div>
                            
                            <!-- Hidden inputs for BMR calculation -->
                            <input type="hidden" id="patientAge" value="{{ $patient->age ?? 0 }}">
                            <input type="hidden" id="patientGender" value="{{ $patient->gender ?? 'Other' }}">
                        </div>
                    </div>

                    <!-- Height, Weight, BMI Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Height (cm)</label>
                                    <input type="number" step="0.01" class="form-control" name="pod_data"
                                        value="{{ $patient->height ?? '' }}" id="heightInput">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Current Weight (kg)</label>
                                    <input type="number" step="0.01" class="form-control" name="pod_bdy_weight"
                                        value="{{ $patient->weight ?? '' }}" id="weightInput">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">LMP Date</label>
                                    <input type="date" class="form-control" name="pod_bdy_lmp"
                                        value="{{ $latestMeta['pod_bdy_lmp'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Calories</label>
                                    <input type="text" class="form-control" name="pod_calories" id="caloriesInput"
                                        value="{{ $latestMeta['pod_calories'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">F/H</label>
                                    <input type="text" class="form-control" name="pod_fh"
                                        value="{{ $latestMeta['pod_fh'] ?? '' }}" placeholder="DM or family history">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Medication -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Any Medication</label>
                                <textarea class="form-control" name="pod_medication"
                                    rows="2">{{ $latestMeta['pod_medication'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Lipid Profile Fields -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h4 class="border-bottom pb-2 mb-3">Lipid Profile</h4>

                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">S-Cholesterol</label>
                                    <input type="text" class="form-control" name="pod_s_sholesterol"
                                        placeholder="Enter S-Cholesterol"
                                        value="{{ $latestMeta['pod_s_sholesterol'] ?? '' }}">
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label class="form-label">S-Triglyceride</label>
                                    <input type="text" class="form-control" name="pod_s_triglyceride"
                                        placeholder="Enter S-Triglyceride"
                                        value="{{ $latestMeta['pod_s_triglyceride'] ?? '' }}">
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label class="form-label">HDL</label>
                                    <input type="text" class="form-control" name="pod_hdl" placeholder="Enter HDL"
                                        value="{{ $latestMeta['pod_hdl'] ?? '' }}">
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label class="form-label">LDL</label>
                                    <input type="text" class="form-control" name="pod_ldl" placeholder="Enter LDL"
                                        value="{{ $latestMeta['pod_ldl'] ?? '' }}">
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label class="form-label">VLDL</label>
                                    <input type="text" class="form-control" name="pod_vldl" placeholder="Enter VLDL"
                                        value="{{ $latestMeta['pod_vldl'] ?? '' }}">
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Thyroid & Other Tests -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h4 class="border-bottom pb-2 mb-3">Thyroid & Blood Tests</h4>

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">TSH</label>
                                    <input type="text" class="form-control" name="pod_tsh"
                                        value="{{ $latestMeta['pod_tsh'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">T3</label>
                                    <input type="text" class="form-control" name="pod_t3"
                                        value="{{ $latestMeta['pod_t3'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">T4</label>
                                    <input type="text" class="form-control" name="pod_t4"
                                        value="{{ $latestMeta['pod_t4'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Vitamin B12</label>
                                    <input type="text" class="form-control" name="pod_b12"
                                        value="{{ $latestMeta['pod_b12'] ?? '' }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Vitamin D3</label>
                                    <input type="text" class="form-control" name="pod_vit_d3"
                                        value="{{ $latestMeta['pod_vit_d3'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Hemoglobin</label>
                                    <input type="text" class="form-control" name="pod_hb"
                                        value="{{ $latestMeta['pod_hb'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">HbA1c</label>
                                    <input type="text" class="form-control" name="pod_hbac"
                                        value="{{ $latestMeta['pod_hbac'] ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sugar Tests -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h4 class="border-bottom pb-2 mb-3">Sugar Tests</h4>

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">RBS</label>
                                    <input type="text" class="form-control" name="pod_sugar_rbs"
                                        value="{{ $latestMeta['pod_sugar_rbs'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">FBS</label>
                                    <input type="text" class="form-control" name="pod_sugar_fbs"
                                        value="{{ $latestMeta['pod_sugar_fbs'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">PP2BS</label>
                                    <input type="text" class="form-control" name="pod_sugar_pp2bs"
                                        value="{{ $latestMeta['pod_sugar_pp2bs'] ?? '' }}">
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Assigned Programs Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h4 class="border-bottom pb-2 mb-3">Assigned Programs</h4>

                            <!-- Main Program Row -->
                            <div class="program-rows-container">
                                <!-- First Row -->
                                <div class="program-row mb-3 permanent-row">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Select Program</label>
                                            <select class="form-select program-select" name="selected_program[]">
                                                <option value="">Select Program</option>
                                                @foreach($available_programs as $prog)
                                                    <option value="{{ $prog->program_name }}" {{ ($latestMeta['selected_program'] ?? '') == $prog->program_name ? 'selected' : '' }}>{{ $prog->program_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Session</label>
                                            <input type="text" class="form-control session-input" name="session[]"
                                                placeholder="Enter session details"
                                                value="{{ $latestMeta['session'] ?? '' }}">
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <div class="d-flex align-items-end">
                                                <div class="flex-grow-1">
                                                    <label class="form-label">Months</label>
                                                    <input type="number" class="form-control months-input" name="months[]"
                                                        placeholder="Enter number of months" min="1"
                                                        value="{{ $latestMeta['months'] ?? '' }}">
                                                </div>
                                                <div class="ms-2" style="padding-bottom: 8px;">
                                                    <button type="button" class="btn btn-success btn-sm add-program-btn"
                                                        onclick="addProgramRow()">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Additional rows will be inserted here -->
                                @if(isset($latestMeta['programs_array']))
                                    @php $programs = json_decode($latestMeta['programs_array'], true); @endphp
                                    @if($programs)
                                        @foreach($programs as $index => $program)
                                            @if($index > 0)
                                                <div class="program-row mb-3 additional-program-row">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label">Select Program</label>
                                                            <select class="form-select program-select" name="selected_program[]">
                                                                <option value="">Select Program</option>
                                                                @foreach($available_programs as $prog)
                                                                    <option value="{{ $prog->program_name }}" {{ $program['program'] == $prog->program_name ? 'selected' : '' }}>
                                                                        {{ $prog->program_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label">Session</label>
                                                            <input type="text" class="form-control session-input" name="session[]"
                                                                value="{{ $program['session'] ?? '' }}">
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <div class="d-flex align-items-end">
                                                                <div class="flex-grow-1">
                                                                    <label class="form-label">Months</label>
                                                                    <input type="number" class="form-control months-input" name="months[]"
                                                                        value="{{ $program['months'] ?? '' }}">
                                                                </div>
                                                                <div class="ms-2" style="padding-bottom: 8px;">
                                                                    <button type="button" class="btn btn-danger btn-sm remove-program-btn"
                                                                        onclick="removeProgramRow(this)">
                                                                        <i class="fas fa-minus"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="diet-history-section">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="mb-0">Update Current Diet Chart</h3>
                        </div>

                        <div class="diet-form-grid">
                            <!-- Left Column -->
                            <div class="form-column">
                                <!-- Time and Activity Section -->
                                <div class="time-activity-section">
                                    <h4>Time & Activity</h4>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-field">
                                                <label for="waking_time">Waking Time</label>
                                                <input type="time" id="waking_time" name="waking_time" class="form-control"
                                                    value="{{ $latestMeta['waking_time'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-field">
                                                <label for="sleeping_time">Sleeping Time</label>
                                                <input type="time" id="sleeping_time" name="sleeping_time"
                                                    class="form-control" value="{{ $latestMeta['sleeping_time'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-field">
                                        <label for="activity">Activity</label>
                                        <select id="activity" name="activity" class="form-select">
                                            <option value="">Select Activity</option>
                                            <option value="sedantary" {{ ($latestMeta['activity'] ?? '') == 'sedantary' ? 'selected' : '' }}>Sedantary</option>
                                            <option value="Athletes" {{ ($latestMeta['activity'] ?? '') == 'Athletes' ? 'selected' : '' }}>Athletes</option>
                                            <option value="lightly_active" {{ ($latestMeta['activity'] ?? '') == 'lightly_active' ? 'selected' : '' }}>Lightly Active</option>
                                            <option value="moderately_active" {{ ($latestMeta['activity'] ?? '') == 'moderately_active' ? 'selected' : '' }}>Moderately Active</option>
                                            <option value="active" {{ ($latestMeta['activity'] ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="vigorous" {{ ($latestMeta['activity'] ?? '') == 'vigorous' ? 'selected' : '' }}>Vigorous</option>
                                        </select>
                                    </div>

                                    <div class="form-field">
                                        <label for="early_morning">Early Morning</label>
                                        <input type="text" id="early_morning" name="early_morning" class="form-control"
                                            placeholder="Enter early morning activity"
                                            value="{{ $latestMeta['early_morning'] ?? '' }}">
                                    </div>

                                    <div class="form-field">
                                        <label for="bed_time">Bed Time</label>
                                        <input type="time" id="bed_time" name="bed_time" class="form-control"
                                            placeholder="Enter bed time" value="{{ $latestMeta['bed_time'] ?? '' }}">
                                    </div>
                                </div>

                                <!-- Occupation -->
                                <div class="form-field">
                                    <label for="occupation">Occupation</label>
                                    <input type="text" id="occupation" name="occupation" class="form-control"
                                        placeholder="E.g.: House wife" value="{{ $latestMeta['occupation'] ?? '' }}">
                                </div>

                                <!-- Meal Times -->
                                <div class="meal-times">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="meal-item">
                                                <label class="form-label">Breakfast</label>
                                                <input type="text" name="breakfast" class="form-control"
                                                    placeholder="Enter breakfast details"
                                                    value="{{ $latestMeta['breakfast'] ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <div class="meal-item">
                                                <label class="form-label">Lunch</label>
                                                <input type="text" name="lunch" class="form-control"
                                                    placeholder="Enter lunch details"
                                                    value="{{ $latestMeta['lunch'] ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <div class="meal-item">
                                                <label class="form-label">Dinner</label>
                                                <input type="text" name="dinner" class="form-control"
                                                    placeholder="Enter dinner details"
                                                    value="{{ $latestMeta['dinner'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="meal-item">
                                                <label class="form-label">Brunch</label>
                                                <input type="text" name="brunch" class="form-control"
                                                    placeholder="Enter brunch details"
                                                    value="{{ $latestMeta['brunch'] ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <div class="meal-item">
                                                <label class="form-label">Snacks</label>
                                                <input type="text" name="snacks" class="form-control"
                                                    placeholder="Enter snacks details"
                                                    value="{{ $latestMeta['snacks'] ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <div class="meal-item">
                                                <label class="form-label">Early Morning Meal</label>
                                                <input type="text" name="early_morning_meal" class="form-control"
                                                    placeholder="Enter early morning details"
                                                    value="{{ $latestMeta['early_morning_meal'] ?? '' }}">
                                            </div>
                                        </div>

                                        <!-- Water Intake -->
                                        <div class="form-field">
                                            <label for="water_intake">Water Intake</label>
                                            <div class="input-group">
                                                <input type="number" id="water_intake" name="water_intake"
                                                    class="form-control" placeholder="6"
                                                    value="{{ $latestMeta['water_intake'] ?? '' }}">
                                                <select class="form-select" name="water_unit" style="max-width: 120px;">
                                                    <option value="">Select</option>
                                                    <option value="glass" {{ ($latestMeta['water_unit'] ?? '') == 'glass' ? 'selected' : '' }}>Glass</option>
                                                    <option value="liter" {{ ($latestMeta['water_unit'] ?? '') == 'liter' ? 'selected' : '' }}>Liter</option>
                                                    <option value="ml" {{ ($latestMeta['water_unit'] ?? '') == 'ml' ? 'selected' : '' }}>ML</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Fasting Day -->
                                        <div class="form-field">
                                            <label for="fasting_day">Fasting Day</label>
                                            <input type="text" id="fasting_day" name="fasting_day" class="form-control"
                                                placeholder="E.g.: Tuesday (1 meal pattern - noon)"
                                                value="{{ $latestMeta['fasting_day'] ?? '' }}">
                                        </div>

                                        <!-- Habit -->
                                        <div class="form-field">
                                            <label for="habit">Habit</label>
                                            <input type="text" id="habit" name="habit" class="form-control"
                                                placeholder="Enter habits" value="{{ $latestMeta['habit'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="form-column">
                                <!-- Food Choices -->
                                <div class="form-field">
                                    <label for="food_choices">Food Choices</label>
                                    <div class="d-flex gap-3 mt-1 flex-wrap">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="food_choices" id="veg"
                                                value="vegetarian" {{ ($latestMeta['food_choices'] ?? '') == 'vegetarian' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="veg">Veg.</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="food_choices" id="nonveg"
                                                value="non-vegetarian" {{ ($latestMeta['food_choices'] ?? '') == 'non-vegetarian' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="nonveg">Non Veg.</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="food_choices" id="veg_egg"
                                                value="veg_egg" {{ ($latestMeta['food_choices'] ?? '') == 'veg_egg' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="veg_egg">Veg. + Egg</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="food_choices" id="jain"
                                                value="jain" {{ ($latestMeta['food_choices'] ?? '') == 'jain' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="jain">Jain</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Milk -->
                                <div class="form-field">
                                    <label for="milk">Milk</label>
                                    <input type="text" id="milk" name="milk" class="form-control"
                                        placeholder="E.g.: amul Taza" value="{{ $latestMeta['milk'] ?? '' }}">
                                </div>

                                <!-- Salt -->
                                <div class="form-field">
                                    <label for="salt">Salt</label>
                                    <input type="text" id="salt" name="salt" class="form-control"
                                        placeholder="E.g.: Iodised" value="{{ $latestMeta['salt'] ?? '' }}">
                                </div>

                                <!-- Food Allergy -->
                                <div class="form-field">
                                    <label for="food_allergy">Food Allergy or Aversion</label>
                                    <input type="text" id="food_allergy" name="food_allergy" class="form-control"
                                        placeholder="Enter food allergies or aversions"
                                        value="{{ $latestMeta['food_allergy'] ?? '' }}">
                                </div>

                                <!-- Walking Time -->
                                <div class="form-field">
                                    <label for="walking_time">Walking Time</label>
                                    <input type="time" id="walking_time" name="walking_time" class="form-control"
                                        placeholder="Enter walking time" value="{{ $latestMeta['walking_time'] ?? '' }}">
                                </div>

                                <!-- Physical Activity -->
                                <div class="form-field">
                                    <label for="physical_activity">Physical Activity</label>
                                    <select id="physicalActivityInput" name="physical_activity" class="form-select">
                                        <option value="" {{ ($latestMeta['physical_activity'] ?? '') == '' ? 'selected' : '' }}>Select Activity</option>
                                        <option value="Sedentary (Very Low Activity)" {{ ($latestMeta['physical_activity'] ?? '') == 'Sedentary (Very Low Activity)' ? 'selected' : '' }}>Sedentary (Very Low
                                            Activity)</option>
                                        <option value="Lightly Active" {{ ($latestMeta['physical_activity'] ?? '') == 'Lightly Active' ? 'selected' : '' }}>Lightly Active</option>
                                        <option value="Moderately Active" {{ ($latestMeta['physical_activity'] ?? '') == 'Moderately Active' ? 'selected' : '' }}>Moderately Active</option>
                                        <option value="Very Active" {{ ($latestMeta['physical_activity'] ?? '') == 'Very Active' ? 'selected' : '' }}>Very Active</option>
                                        <option value="Extra Active" {{ ($latestMeta['physical_activity'] ?? '') == 'Extra Active' ? 'selected' : '' }}>Extra Active</option>
                                    </select>
                                </div>

                                <!-- Oil -->
                                <div class="form-field">
                                    <label for="oil">Oil</label>
                                    <input type="text" id="oil" name="oil" class="form-control"
                                        placeholder="E.g.: Sunflower" value="{{ $latestMeta['oil'] ?? '' }}">
                                </div>

                                <!-- Anything Else -->
                                <div class="form-field">
                                    <label for="anything_else">Anything Else</label>
                                    <input type="text" id="anything_else" name="anything_else" class="form-control"
                                        placeholder="Enter any additional information"
                                        value="{{ $latestMeta['anything_else'] ?? '' }}">
                                </div>

                                <!-- Alcohol/Habits -->
                                <div class="form-field">
                                    <label for="alcohol">Alcohol / Other Habits</label>
                                    <input type="text" id="alcohol" name="alcohol" class="form-control"
                                        placeholder="Enter details" value="{{ $latestMeta['alcohol'] ?? '' }}">
                                </div>

                                <!-- Fast Food -->
                                <div class="form-field">
                                    <label for="fast_food">Fast Food / Hotel Food</label>
                                    <input type="text" id="fast_food" name="fast_food" class="form-control"
                                        placeholder="Frequency of outside food"
                                        value="{{ $latestMeta['fast_food'] ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <!-- Measurement History Table -->
                        <div class="mt-4">
                            <h4 class="mb-3 text-success font-weight-bold"
                                style="border-bottom: 2px solid #28a745; display: inline-block;"><i
                                    class="fas fa-ruler-combined"></i> Measurement History</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm text-center">
                                    <thead class="bg-success text-white">
                                        <tr>
                                            <th>Date</th>
                                            <th>Waist (cm)</th>
                                            <th>Hips (cm)</th>
                                            <th>Thighs (cm)</th>
                                            <th>Arms (cm)</th>
                                            <th>Waist / Hips</th>
                                            <th>Wt (kg)</th>
                                            <th>BMI</th>
                                        </tr>
                                    </thead>
                                    <tbody id="measurementHistoryBody">
                                        @forelse($measurements ?? [] as $m)
                                            <tr>
                                                <td>{{ $m->assessment_date ? $m->assessment_date->format('d/m/Y') : '-' }}</td>
                                                <td>{{ $m->waist_middle ?? '-' }}</td>
                                                <td>{{ $m->hips ?? '-' }}</td>
                                                <td>{{ $m->thighs ?? '-' }}</td>
                                                <td>{{ $m->arms ?? '-' }}</td>
                                                <td>{{ $m->waist_hips_ratio ?? '-' }}</td>
                                                <td class="fw-bold">{{ $m->weight ?? '-' }}</td>
                                                <td>{{ $m->bmi ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-muted italic">No measurement history available.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- Health Metrics Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h4 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-heartbeat"></i> Health Metrics
                            </h4>

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Dietary Habits</label>
                                    <input type="text" id="diet" name="diet" class="form-control"
                                        placeholder="Diet" value="{{ $latestMeta['diet'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Exercise Regimen</label>
                                    <input type="text" id="exercise" name="exercise" class="form-control"
                                        placeholder="Exercise" value="{{ $latestMeta['exercise'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Sleep Pattern</label>
                                    <input type="text" id="sleep" name="sleep" class="form-control"
                                        placeholder="Sleep" value="{{ $latestMeta['sleep'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Water Intake</label>
                                    <input type="text" id="water" name="water" class="form-control"
                                        placeholder="Water" value="{{ $latestMeta['water'] ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Laboratory Investigation Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h4 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-flask"></i> Laboratory Investigation
                            </h4>

                            <div class="row">
                                <!-- First Row -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">S. Insulin</label>
                                    <input type="text" id="s_insulin" name="s_insulin" class="form-control"
                                        placeholder="Enter S. Insulin value" value="{{ $latestMeta['s_insulin'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">SGPT</label>
                                    <input type="text" id="sgpt" name="sgpt" class="form-control"
                                        placeholder="Enter SGPT value" value="{{ $latestMeta['sgpt'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">S. Creatinine</label>
                                    <input type="text" id="s_creatinine" name="s_creatinine" class="form-control"
                                        placeholder="Enter S. Creatinine value"
                                        value="{{ $latestMeta['s_creatinine'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">S. Uric Acid</label>
                                    <input type="text" id="s_uric_acid" name="s_uric_acid" class="form-control"
                                        placeholder="Enter S. Uric Acid value"
                                        value="{{ $latestMeta['s_uric_acid'] ?? '' }}">
                                </div>
                            </div>

                            <div class="row">
                                <!-- Second Row -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">RA Test</label>
                                    <input type="text" id="ra_test" name="ra_test" class="form-control"
                                        placeholder="Enter RA Test value" value="{{ $latestMeta['ra_test'] ?? '' }}">
                                </div>

                                {{-- Blood / CBC Fields --}}
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">HB</label>
                                    <input type="text" id="hb" name="hb" class="form-control" placeholder="HB"
                                        value="{{ $latestMeta['hb'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">TC</label>
                                    <input type="text" id="tc" name="tc" class="form-control" placeholder="TC"
                                        value="{{ $latestMeta['tc'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">PC</label>
                                    <input type="text" id="pc" name="pc" class="form-control" placeholder="PC"
                                        value="{{ $latestMeta['pc'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">MP</label>
                                    <input type="text" id="mp_lab" name="mp_lab" class="form-control"
                                        placeholder="MP" value="{{ $latestMeta['mp_lab'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">ESR</label>
                                    <input type="text" id="esr" name="esr" class="form-control" placeholder="ESR"
                                        value="{{ $latestMeta['esr'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">CRP</label>
                                    <input type="text" id="crp" name="crp" class="form-control" placeholder="CRP"
                                        value="{{ $latestMeta['crp'] ?? '' }}">
                                </div>

                                {{-- Sugar Tests --}}
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">HB1AC</label>
                                    <input type="text" id="hb1ac" name="hb1ac" class="form-control"
                                        placeholder="HB1AC" value="{{ $latestMeta['hb1ac'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">FBS</label>
                                    <input type="text" id="fbs" name="fbs" class="form-control" placeholder="FBS"
                                        value="{{ $latestMeta['fbs'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">PP2BS</label>
                                    <input type="text" id="pp2bs" name="pp2bs" class="form-control"
                                        placeholder="PP2BS" value="{{ $latestMeta['pp2bs'] ?? '' }}">
                                </div>

                                {{-- Serology / Infection --}}
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">S. Widal</label>
                                    <input type="text" id="s_widal" name="s_widal" class="form-control"
                                        placeholder="S. Widal" value="{{ $latestMeta['s_widal'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">NS1 Ag</label>
                                    <input type="text" id="ns1ag" name="ns1ag" class="form-control"
                                        placeholder="NS1 Ag" value="{{ $latestMeta['ns1ag'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Dengue IGM</label>
                                    <input type="text" id="dengue_igm" name="dengue_igm" class="form-control"
                                        placeholder="Dengue IGM" value="{{ $latestMeta['dengue_igm'] ?? '' }}">
                                </div>

                                {{-- Vitamins --}}
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">S. B12</label>
                                    <input type="text" id="s_b12" name="s_b12" class="form-control"
                                        placeholder="S. B12" value="{{ $latestMeta['s_b12'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">S. D3</label>
                                    <input type="text" id="s_d3" name="s_d3" class="form-control"
                                        placeholder="S. D3" value="{{ $latestMeta['s_d3'] ?? '' }}">
                                </div>

                                {{-- Thyroid Tests --}}
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">S. T3</label>
                                    <input type="text" id="s_t3" name="s_t3" class="form-control"
                                        placeholder="S. T3" value="{{ $latestMeta['s_t3'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">S. T4</label>
                                    <input type="text" id="s_t4" name="s_t4" class="form-control"
                                        placeholder="S. T4" value="{{ $latestMeta['s_t4'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">S. TSH</label>
                                    <input type="text" id="s_tsh" name="s_tsh" class="form-control"
                                        placeholder="S. TSH" value="{{ $latestMeta['s_tsh'] ?? '' }}">
                                </div>

                                {{-- Urine --}}
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Urine</label>
                                    <input type="text" id="urine_lab" name="urine_lab" class="form-control"
                                        placeholder="Urine" value="{{ $latestMeta['urine_lab'] ?? '' }}">
                                </div>

                                {{-- Any Specific Test --}}
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Any Specific Test</label>
                                    <input type="text" id="specific_test" name="specific_test" class="form-control"
                                        placeholder="Any specific Test"
                                        value="{{ $latestMeta['specific_test'] ?? '' }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">USG Abdomen</label>
                                    <textarea id="usg_abdomen" name="usg_abdomen" class="form-control" rows="3"
                                        placeholder="Enter USG Abdomen details">{{ $latestMeta['usg_abdomen'] ?? '' }}</textarea>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Chest X-ray</label>
                                    <textarea id="chest_xray" name="chest_xray" class="form-control" rows="3"
                                        placeholder="Enter Chest X-ray details">{{ $latestMeta['chest_xray'] ?? '' }}</textarea>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">MRI-CT Scan</label>
                                    <textarea id="mri_ct_scan" name="mri_ct_scan" class="form-control" rows="3"
                                        placeholder="Enter MRI-CT Scan details">{{ $latestMeta['mri_ct_scan'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h4 class="border-bottom pb-2 mb-3">Patient Images</h4>
                            <!-- Additional Images (for meta) -->
                            <div class="row">
                                <div class="col-md-6">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <div class="mb-3">
                                            <label class="form-label small">Before Picture {{ $i }}</label>
                                            <input type="file" class="form-control form-control-sm"
                                                name="before_picture_{{ $i }}" accept="image/*">
                                            @if(!empty($latestMeta['before_picture_' . $i]))
                                                <input type="hidden" name="existing_before_picture_{{ $i }}"
                                                    value="{{ $latestMeta['before_picture_' . $i] }}">
                                                <div class="mt-2 preview-image-container">
                                                    <a href="{{ asset('before/' . $latestMeta['before_picture_' . $i]) }}"
                                                        target="_blank">
                                                        <img src="{{ asset('before/' . $latestMeta['before_picture_' . $i]) }}"
                                                            alt="Before Picture {{ $i }}" class="img-thumbnail"
                                                            style="max-height: 100px;">
                                                    </a>
                                                    <div class="small text-muted">Stored Image</div>
                                                </div>
                                            @endif
                                        </div>
                                    @endfor
                                </div>
                                <div class="col-md-6">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <div class="mb-3">
                                            <label class="form-label small">After Picture {{ $i }}</label>
                                            <input type="file" class="form-control form-control-sm"
                                                name="after_picture_{{ $i }}" accept="image/*">
                                            @if(!empty($latestMeta['after_picture_' . $i]))
                                                <input type="hidden" name="existing_after_picture_{{ $i }}"
                                                    value="{{ $latestMeta['after_picture_' . $i] }}">
                                                <div class="mt-2 preview-image-container">
                                                    <a href="{{ asset('after/' . $latestMeta['after_picture_' . $i]) }}"
                                                        target="_blank">
                                                        <img src="{{ asset('after/' . $latestMeta['after_picture_' . $i]) }}"
                                                            alt="After Picture {{ $i }}" class="img-thumbnail"
                                                            style="max-height: 100px;">
                                                    </a>
                                                    <div class="small text-muted">Stored Image</div>
                                                </div>
                                            @endif
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Update Diet Chart
                            </button>
                            <a href="{{ route('joined.inquiry') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Date-wise Diet History Timeline (Simplified Accordion) -->
                <div class="my-5 py-3">
                    <div class="accordion shadow-sm" id="dietHistoryAccordion">
                        <div class="accordion-item border-success border-opacity-25">
                            <h2 class="accordion-header" id="headingTimeline">
                                <button class="accordion-button collapsed bg-success bg-opacity-10 text-success fw-bold"
                                    type="button" data-bs-toggle="collapse" data-bs-target="#collapseTimeline"
                                    aria-expanded="false" aria-controls="collapseTimeline">
                                    <i class="fas fa-calendar-alt me-2"></i> Date-wise Diet History Timeline
                                    ({{ $dietHistory->count() }} Records)
                                </button>
                            </h2>
                            <div id="collapseTimeline" class="accordion-collapse collapse" aria-labelledby="headingTimeline"
                                data-bs-parent="#dietHistoryAccordion">
                                <div class="accordion-body p-0">
                                    @if(!empty($dietHistory) && $dietHistory->count() > 0)
                                        <div class="list-group list-group-flush">
                                            @foreach($dietHistory as $history)
                                                @php
                                                    $metaArray = [];
                                                    foreach ($history->meta as $m) {
                                                        $metaArray[$m->meta_key] = $m->meta_value;
                                                    }
                                                @endphp
                                                <div
                                                    class="list-group-item diet-history-entry timeline-entry bg-white border-start border-4 border-success border-opacity-25 py-3 px-4">
                                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-success text-white rounded px-2 py-1 me-2 shadow-sm"
                                                                style="font-size: 0.8rem; font-weight: 700;">
                                                                {{ $history->created_at->format('d M, Y') }}
                                                            </div>
                                                            <span class="text-muted small fw-bold">
                                                                <i
                                                                    class="far fa-clock me-1 text-success"></i>{{ $history->created_at->format('h:i A') }}
                                                            </span>
                                                        </div>

                                                        <div class="d-flex align-items-center gap-2">
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-bold"
                                                                onclick="loadDietHistory({{ json_encode($metaArray) }})">
                                                                <i class="fas fa-file-import me-1"></i> Apply
                                                            </button>
                                                            <button type="button"
                                                                class="btn btn-sm btn-light text-primary rounded-pill px-3 py-1 fw-bold"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#fullData-{{ $history->id }}">
                                                                <i class="fas fa-eye me-1"></i> Details
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2"
                                                                onclick="deleteHistory({{ $history->id }}, this)">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="collapse mt-3" id="fullData-{{ $history->id }}">
                                                        <div class="bg-white p-3 rounded border border-success border-opacity-25"
                                                            style="font-size: 0.85rem;">
                                                            <div class="row g-3">
                                                                <div class="col-md-3">
                                                                    <p class="mb-1 text-muted small fw-bold text-uppercase">
                                                                        Breakfast</p>
                                                                    <p class="mb-0 text-dark font-weight-bold">
                                                                        {{ $metaArray['breakfast'] ?? '---' }}</p>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <p class="mb-1 text-muted small fw-bold text-uppercase">Lunch
                                                                    </p>
                                                                    <p class="mb-0 text-dark font-weight-bold">
                                                                        {{ $metaArray['lunch'] ?? '---' }}</p>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <p class="mb-1 text-muted small fw-bold text-uppercase">Dinner
                                                                    </p>
                                                                    <p class="mb-0 text-dark font-weight-bold">
                                                                        {{ $metaArray['dinner'] ?? '---' }}</p>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <p class="mb-1 text-muted small fw-bold text-uppercase">Water /
                                                                        Activity</p>
                                                                    <p class="mb-0 text-dark">
                                                                        {{ $metaArray['water_intake'] ?? '-' }}
                                                                        {{ $metaArray['water_unit'] ?? '' }} |
                                                                        {{ $metaArray['activity'] ?? '---' }}</p>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <ul class="list-unstyled mb-0 border-top pt-2">
                                                                        <li><span class="text-muted">Occupation:</span>
                                                                            {{ $metaArray['occupation'] ?? 'N/A' }}</li>
                                                                        <li><span class="text-muted">Waking Time:</span>
                                                                            {{ $metaArray['waking_time'] ?? 'N/A' }}</li>
                                                                        <li><span class="text-muted">Sleeping Time:</span>
                                                                            {{ $metaArray['sleeping_time'] ?? 'N/A' }}</li>
                                                                    </ul>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <ul class="list-unstyled mb-0 border-top pt-2">
                                                                        <li><span class="text-muted">Brunch:</span>
                                                                            {{ $metaArray['brunch'] ?? 'N/A' }}</li>
                                                                        <li><span class="text-muted">Snacks:</span>
                                                                            {{ $metaArray['snacks'] ?? 'N/A' }}</li>
                                                                        <li><span class="text-muted">Food Choice:</span>
                                                                            {{ ucfirst($metaArray['food_choices'] ?? 'N/A') }}</li>
                                                                    </ul>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <ul class="list-unstyled mb-0 border-top pt-2">
                                                                        <li><span class="text-muted">Milk:</span>
                                                                            {{ $metaArray['milk'] ?? 'N/A' }}</li>
                                                                        <li><span class="text-muted">Allergy:</span>
                                                                            {{ $metaArray['food_allergy'] ?? 'N/A' }}</li>
                                                                        <li><span class="text-muted">Habit:</span>
                                                                            {{ $metaArray['habit'] ?? 'N/A' }}</li>
                                                                    </ul>
                                                                </div>
                                                                <div class="col-12 mt-2 border-top pt-2">
                                                                    <span class="text-muted">Anything Else:</span> <span
                                                                        class="text-dark">{{ $metaArray['anything_else'] ?? 'None' }}</span>
                                                                </div>
                                                                <div class="col-12 mt-2 border-top pt-2">
                                                                    <div class="row">
                                                                        <div class="col-md-2 col-6">
                                                                            <span class="text-muted">Height:</span> <strong>{{ $metaArray['pod_data'] ?? '---' }} cm</strong>
                                                                        </div>
                                                                        <div class="col-md-2 col-6">
                                                                            <span class="text-muted">Weight:</span> <strong>{{ $metaArray['pod_bdy_weight'] ?? '---' }} kg</strong>
                                                                        </div>
                                                                        <div class="col-md-2 col-6">
                                                                            <span class="text-muted">BMI:</span> <strong>{{ $metaArray['pod_bmr'] ?? '---' }}</strong>
                                                                        </div>
                                                                        <div class="col-md-3 col-6">
                                                                            <span class="text-muted">BMR:</span> <strong>{{ $metaArray['pod_bmr_value'] ?? '---' }} kcal</strong>
                                                                        </div>
                                                                        <div class="col-md-3 col-12">
                                                                            <span class="text-muted">Calories Req:</span> <strong>{{ $metaArray['pod_calories'] ?? '---' }} kcal</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-5 text-muted">
                                            <i class="fas fa-info-circle me-1"></i> No previous diet history found.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div> <!-- Card Body End -->
                </div> <!-- Card End -->
        </form>
    </div> <!-- Container End -->

    <!-- Diet History Modal -->
    <div class="modal fade" id="dietHistoryModal" tabindex="-1" aria-labelledby="dietHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="dietHistoryModalLabel">
                        <i class="fas fa-history me-2"></i> Diet History Archives -
                        {{ $patient->patient_name ?? 'Patient' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Date & Time</th>
                                    <th>Breakfast</th>
                                    <th>Lunch</th>
                                    <th>Dinner</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dietHistory as $history)
                                    @php
                                        $metaArray = [];
                                        foreach ($history->meta as $m) {
                                            $metaArray[$m->meta_key] = $m->meta_value;
                                        }
                                    @endphp
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $history->created_at->format('d M, Y') }}</div>
                                            <small class="text-muted">{{ $history->created_at->format('h:i A') }}</small>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;">
                                                {{ $metaArray['breakfast'] ?? '---' }}</div>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;">
                                                {{ $metaArray['lunch'] ?? '---' }}</div>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;">
                                                {{ $metaArray['dinner'] ?? '---' }}</div>
                                        </td>
                                        <td class="text-center pe-4">
                                            <button class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm"
                                                onclick="loadDietHistory({{ json_encode($metaArray) }})"
                                                data-bs-dismiss="modal">
                                                <i class="fas fa-file-import me-1"></i> Apply to Form
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fas fa-info-circle me-1"></i> No diet history archives found for this
                                            patient.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-calculate BMI
            function calculateBMI() {
                const hEl = document.getElementById('heightInput');
                const wEl = document.getElementById('weightInput');
                const bEl = document.getElementById('bmiInput');

                if (!hEl || !wEl || !bEl) return;

                const height = parseFloat(hEl.value);
                const weight = parseFloat(wEl.value);

                if (height && weight && height > 0) {
                    const heightMeter = height / 100;
                    const bmi = (weight / (heightMeter * heightMeter)).toFixed(2);
                    bEl.value = bmi;
                } else {
                    bEl.value = '';
                }
            }

            const heightInput = document.getElementById('heightInput');
            const weightInput = document.getElementById('weightInput');

            if (heightInput) {
                heightInput.addEventListener('input', function() {
                    calculateBMI();
                    calculateBMRAndTDEE();
                });
            }
            
            if (weightInput) {
                weightInput.addEventListener('input', function() {
                    calculateBMI();
                    calculateBMRAndTDEE();
                });
            }
            
            const physicalActivityInput = document.getElementById('physicalActivityInput');
            if (physicalActivityInput) {
                physicalActivityInput.addEventListener('change', function () {
                    calculateBMRAndTDEE();
                });
            }

            // ─── AUTO-CALCULATE BMR AND TDEE (CALORIES REQ) ─────────────────────
            function calculateBMRAndTDEE() {
                const hEl = document.getElementById('heightInput');
                const wEl = document.getElementById('weightInput');
                const bmrEl = document.getElementById('bmrValueInput');
                const calEl = document.getElementById('caloriesInput');
                const ageEl = document.getElementById('patientAge');
                const genderEl = document.getElementById('patientGender');
                const activityEl = document.getElementById('physicalActivityInput');

                if (!hEl || !wEl || !bmrEl || !calEl) return;

                const height = parseFloat(hEl.value);
                const weight = parseFloat(wEl.value);
                const age = parseInt(ageEl ? ageEl.value : 0) || 30;
                const gender = genderEl ? genderEl.value : 'male';

                if (height && weight && height > 0) {
                    let bmr = 0;
                    if (gender.toLowerCase() === 'female') {
                        bmr = (10 * weight) + (6.25 * height) - (5 * age) - 161;
                    } else {
                        // Default to Male formula
                        bmr = (10 * weight) + (6.25 * height) - (5 * age) + 5;
                    }
                    
                    bmrEl.value = Math.round(bmr);

                    if (activityEl && activityEl.value) {
                        const activityMultipliers = {
                            "Sedentary (Very Low Activity)": 1.2,
                            "Lightly Active": 1.375,
                            "Moderately Active": 1.55,
                            "Very Active": 1.725,
                            "Extra Active": 1.9
                        };
                        
                        const multiplier = activityMultipliers[activityEl.value] || 1.2;
                        const tdee = bmr * multiplier;
                        calEl.value = Math.round(tdee);
                    } else {
                        calEl.value = '';
                    }
                } else {
                    bmrEl.value = '';
                    calEl.value = '';
                }
            }

            // Initial calculation
            calculateBMI();
            calculateBMRAndTDEE();

            // Auto-calculate due payment
            function calculateDuePayment() {
                const totalEl = document.querySelector('[name="total_payment"]');
                const discountEl = document.querySelector('[name="discount_payment"]');
                const givenEl = document.querySelector('[name="given_payment"]');
                const dueEl = document.querySelector('[name="due_payment"]');

                if (!totalEl || !dueEl) return;

                const total = parseFloat(totalEl.value) || 0;
                const discount = parseFloat(discountEl?.value) || 0;
                const given = parseFloat(givenEl?.value) || 0;

                const due = total - discount - given;
                dueEl.value = due > 0 ? due.toFixed(2) : '0.00';
            }

            const paymentFields = ['total_payment', 'discount_payment', 'given_payment'];
            paymentFields.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (field) field.addEventListener('input', calculateDuePayment);
            });

            // Initial calculation
            calculateDuePayment();

            calculateDuePayment();

            // Add preview for file uploads
            document.querySelectorAll('input[type="file"]').forEach(input => {
                input.addEventListener('change', function () {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        const container = this.closest('.mb-3') || this.closest('.mb-2');
                        let preview = container.querySelector('.preview-image-container');

                        if (!preview) {
                            preview = document.createElement('div');
                            preview.className = 'mt-2 preview-image-container';
                            container.appendChild(preview);
                        }

                        reader.onload = function (e) {
                            preview.innerHTML = `
                                    <div class="d-inline-block border p-1 rounded bg-light">
                                        <img src="${e.target.result}" class="img-thumbnail" style="max-height: 100px;">
                                        <div class="small text-primary mt-1 fw-bold"><i class="fas fa-check-circle"></i> New Selection</div>
                                    </div>
                                `;
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            });

            // Form submission confirmation
            const dietForm = document.getElementById('dietChartForm');
            if (dietForm) {
                dietForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you want to update this diet chart?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, update it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            dietForm.submit();
                        }
                    });
                });
            }
        });

        function addProgramRow() {
            const container = document.querySelector('.program-rows-container');
            const newRow = document.createElement('div');
            newRow.className = 'program-row mb-3 additional-program-row';
            newRow.innerHTML = `
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Select Program</label>
                            <select class="form-select program-select" name="selected_program[]">
                                <option value="">Select Program</option>
                                @foreach($available_programs as $all_prog)
                                    <option value="{{ $all_prog->program_name }}">{{ $all_prog->program_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Session</label>
                            <input type="text" class="form-control session-input" name="session[]" placeholder="Enter session details">
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-end">
                                <div class="flex-grow-1">
                                    <label class="form-label">Months</label>
                                    <input type="number" class="form-control months-input" name="months[]" placeholder="Enter number of months" min="1">
                                </div>
                                <div class="ms-2" style="padding-bottom: 8px;">
                                    <button type="button" class="btn btn-danger btn-sm remove-program-btn" onclick="removeProgramRow(this)">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            container.appendChild(newRow);
        }

        function removeProgramRow(button) {
            button.closest('.program-row').remove();
        }

        function loadDietHistory(meta) {
            if (!meta) return;
            for (let key in meta) {
                let value = meta[key];
                if (key === 'food_choices') {
                    let radio = document.querySelector(\`input[name="\${key}"][value="\${value}"]\`);
                        if (radio) radio.checked = true;
                        continue;
                    }
                    let element = document.getElementsByName(key)[0];
                    if (element) element.value = value;
                }

                if (typeof calculateBMI === 'function') calculateBMI();
                if (typeof calculateDuePayment === 'function') calculateDuePayment();
                if (typeof calculateBMRAndTDEE === 'function') calculateBMRAndTDEE();

                Swal.fire({
                    icon: 'success',
                    title: 'History Loaded',
                    text: 'Previous diet history has been loaded into the form.',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });

                // Scroll to form nicely
                document.querySelector('.diet-history-section').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            async function deleteHistory(id, button) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to delete this diet history entry? This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/ admin / delete -diet - history / ${ id }`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                }
                            });
                            const data = await response.json();

                            if(data.success) {
                                Swal.fire('Deleted!', data.message, 'success');
                                // Remove the timeline item with animation
                                const item = button.closest('.timeline-entry');
                                item.style.transition = 'all 0.5s ease';
                                item.style.opacity = '0';
                                item.style.transform = 'translateX(-50px)';
                                setTimeout(() => item.remove(), 500);
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        } catch (e) {
                            Swal.fire('Error', 'Connection failed', 'error');
                        }
                    }
                });
            }
    </script>

    <style>
        .add-program-btn,
        .remove-program-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .additional-program-row {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #ddd;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .diet-history-section {
            background: var(--bg-card);
            border-radius: 8px;
            padding: 25px;
            margin: 20px 0;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-subtle);
        }

        .diet-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        @media (max-width: 768px) {
            .diet-form-grid {
                grid-template-columns: 1fr;
            }
        }

        .form-column {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-field {
            display: flex;
            flex-direction: column;
        }
    </style>
@endsection