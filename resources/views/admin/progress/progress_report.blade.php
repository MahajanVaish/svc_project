@extends('admin.layouts.layouts')

@section('title', 'Progress Report')

<!-- Add Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@section('content')
<div class="container px-0">
    <div class="card shadow-sm mt-3">
        <div class="card-header bg-light px-3 py-3">
            <h5 class="mb-0 fw-bold" style="color: #197040">Progress Report</h5>
        </div>
        <div class="card-body bg-light">



            <style>
                label {
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

                .form-group {
                    margin-bottom: 12px;
                }

                #progressForm {
                    min-height: 450px;
                    position: relative;
                    /* Essential for Select2 dropdownParent logic */
                }

                /* Custom Select2 styling to match theme */
                .select2-container--default .select2-selection--single {
                    height: 38px;
                    border: 1px solid #dee2e6;
                    border-radius: 6px;
                    padding-top: 4px;
                }

                .select2-container--default .select2-selection--single .select2-selection__arrow {
                    height: 36px;
                }

                .select2-dropdown {
                    border: 1px solid #197040;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                }

                .select2-container--default .select2-results__option--highlighted[aria-selected] {
                    background-color: #197040;
                }

                /* Force Select2 dropdown to open downwards */
                .force-dropdown-below {
                    top: 100% !important;
                    left: 0 !important;
                    bottom: auto !important;
                    position: absolute !important;
                }

                /* Ensure dropdown stays within form boundaries if possible */
                .select2-results__options {
                    max-height: 250px;
                }

                /* + / - button styles */
                .btn-add-row {
                    background-color: #197040;
                    color: #fff;
                    border: none;
                    border-radius: 50%;
                    width: 28px;
                    height: 28px;
                    font-size: 18px;
                    line-height: 1;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    padding: 0;
                    transition: background 0.2s;
                    flex-shrink: 0;
                }
                .btn-add-row:hover {
                    background-color: #145c33;
                }
                .btn-remove-row {
                    background-color: #dc3545;
                    color: #fff;
                    border: none;
                    border-radius: 50%;
                    width: 28px;
                    height: 28px;
                    font-size: 20px;
                    line-height: 1;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    padding: 0;
                    transition: background 0.2s;
                    flex-shrink: 0;
                }
                .btn-remove-row:hover {
                    background-color: #a71d2a;
                }
                .program-body-row {
                    margin-bottom: 0;
                }
                .body-part-input-wrap {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }
            </style>

            @php
                $prefillBranchId = request()->query('branch_id');
                $prefillPatientId = request()->query('patient_id');
                $prefillPatientName = '';
                $prefillBranchName = '';
                if ($prefillBranchId) {
                    $bObj = \App\Models\Branch::where('branch_id', $prefillBranchId)->first();
                    if ($bObj) {
                        $prefillBranchName = $bObj->branch_name;
                    }
                }
                if ($prefillPatientId) {
                    $p = \App\Models\AccInquiry::find($prefillPatientId);
                    if ($p) {
                        $prefillPatientName = trim(($p->patient_f_name ?? '') . ' ' . ($p->patient_m_name ?? '') . ' ' . ($p->patient_l_name ?? ''));
                        if ($prefillPatientName === '') {
                            $prefillPatientName = $p->patient_id;
                        }
                        if ($p->patient_id) {
                            $prefillPatientName .= " (ID: {$p->patient_id})";
                        }
                    }
                }
            @endphp
            <form action="{{ route('progress_report.store') }}" method="POST" id="progressForm">
                @csrf
                <div class="row g-3 mb-3 pb-2">
                    <!-- Branch Selection -->
                    <div class="form-group col-md-4">
                        <label for="branch">Select Branch <span class="text-danger">*</span></label>
                        <select class="form-control" name="branch_id" id="branch" required>
                            <option value="">Select branch</option>
                            @foreach($branches as $b)
                            <option value="{{ $b->branch_id }}" {{ $prefillBranchId == $b->branch_id ? 'selected' : '' }}>{{ $b->branch_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Patient Search -->
                    <div class="form-group col-md-4">
                        <label for="patient_search">Search Patient</label>
                        <input type="text" id="patient_search" class="form-control" placeholder="Type name or ID..." {{ $prefillBranchId ? '' : 'disabled' }}>
                    </div>

                    <!-- Patient Selection -->
                    <div class="form-group col-md-4">
                        <label for="patient_name">Select Patient <span class="text-danger">*</span></label>
                        <select class="form-control" name="patient_id" id="patient_name" required {{ $prefillPatientId ? '' : 'disabled' }}>
                            @if($prefillPatientId)
                                <option value="{{ $prefillPatientId }}" selected data-patient-name="{{ $prefillPatientName }}">{{ $prefillPatientName }}</option>
                            @else
                                <option value="">Select branch first</option>
                            @endif
                        </select>
                        <div class="text-muted small mt-1" id="patientCount"></div>
                    </div>

                    <!-- Hidden field for patient name -->
                    <input type="hidden" name="patient_name" id="patient_name_hidden" value="{{ $prefillPatientId ? $prefillPatientName : '' }}">

                    <!-- Hidden field for branch name -->
                    <input type="hidden" name="branch_name" id="branch_name_hidden" value="{{ $prefillBranchId ? $prefillBranchName : '' }}">

                    <!-- Date and Time -->
                    <div class="form-group col-md-6">
                        <label for="date">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="date" id="date" value="{{ date('Y-m-d') }}"
                            required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="time">Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="time" id="time" value="{{ date('H:i') }}"
                            required>
                    </div>

                    <!-- Program and Body Part - First (default) row -->
                    <div class="col-12 program-body-row" id="program_body_row_0">
                        <div class="row g-3">
                            <!-- Program Name -->
                            <div class="form-group col-md-6" id="program_container_0" style="position: relative;">
                                <label>Select Program Name</label>
                                <select class="form-control program-select" name="program_name[]" id="program_name_0">
                                    <option value="">Select Program Name</option>
                                    @if(isset($programs))
                                    @foreach($programs as $program)
                                    <option value="{{ $program->program_name }}">{{ $program->program_name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <!-- Body Part -->
                            <div class="form-group col-md-6">
                                <label>Body Part &amp; Functions</label>
                                <div class="body-part-input-wrap">
                                    <input type="text" class="form-control" name="body_part[]" id="body_part_0"
                                        placeholder="Enter body part &amp; functions">
                                    <button type="button" class="btn-add-row" id="addProgramRowBtn" title="Add row">
                                        <span style="margin-top:-2px;">+</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Extra rows will be inserted here -->
                    <div id="extra_rows_container"></div>

                    <!-- Hidden JSON store for all-programs list (used by JS for new rows) -->
                    <script id="all_programs_json" type="application/json">
                        [
                            @if(isset($programs))
                            @foreach($programs as $i => $program)
                            {"value": "{{ addslashes($program->program_name) }}"}{{ !$loop->last ? ',' : '' }}
                            @endforeach
                            @endif
                        ]
                    </script>

                    <!-- Divider to ensure metrics start on a new row -->
                    <div class="col-12 mt-0 mb-2">
                        <hr class="opacity-10">
                    </div>

                    <!-- Metrics Row -->
                    <div class="form-group col-md-3">
                        <label for="bp_p">BP</label>
                        <input type="text" class="form-control" name="bp_p" id="bp_p" placeholder="BP">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="pulse">Pulse</label>
                        <input type="text" class="form-control" name="pulse" id="pulse" placeholder="Pulse">
                    </div>

                    <div class="form-group col-md-2">
                        <label for="weight">Weight (kg)</label>
                        <input type="number" step="0.1" class="form-control" name="weight" id="weight"
                            placeholder="Weight" onchange="calculateBMI()">
                    </div>

                    <div class="form-group col-md-2">
                        <label for="height">Height (cm)</label>
                        <input type="number" step="0.1" class="form-control" name="height" id="height"
                            placeholder="Height" onchange="calculateBMI()">
                    </div>

                    <div class="form-group col-md-2">
                        <label for="bmi">BMI</label>
                        <input type="text" class="form-control" name="bmi" id="bmi" placeholder="BMI" readonly
                            style="background-color: #f8f9fa;">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="councilor_doctor">Councilor/Doctor Note</label>
                        <input type="text" class="form-control" name="councilor_doctor" id="councilor_doctor"
                            placeholder="Councilor/Doctor Note">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="exercise">Exercise</label>
                        <input type="text" class="form-control" name="exercise" id="exercise" placeholder="Exercise">
                    </div>
                </div>

                <!-- Additional Fields Row -->
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="diet">Diet</label>
                        <input type="text" class="form-control" name="diet" id="diet" placeholder="Diet">
                    </div>
                    
                    <div class="form-group col-md-3">
                        <label for="sleep">Sleep</label>
                        <input type="text" class="form-control" name="sleep" id="sleep" placeholder="Sleep">
                    </div>
                    
                    <div class="form-group col-md-3">
                        <label for="water">Water</label>
                        <input type="text" class="form-control" name="water" id="water" placeholder="Water">
                    </div>
                    
                    <div class="form-group col-md-3">
                        <label for="medication">Medication</label>
                        <input type="text" class="form-control" name="medication" id="medication" placeholder="Medication">
                    </div>
                </div>

                <div class="border-bottom mb-3"></div>
                <div class="text-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save"></i> Submit
                    </button>
                    <button type="reset" class="btn btn-secondary px-4">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const branchSelect = document.getElementById('branch');
        const patientSelect = document.getElementById('patient_name');
        const patientNameHidden = document.getElementById('patient_name_hidden');
        const branchNameHidden = document.getElementById('branch_name_hidden');
        const patientCount = document.getElementById('patientCount');

        let searchTimeout;

        function fetchPatients() {
            const branchId = branchSelect.value;
            const searchQuery = document.getElementById('patient_search').value;

            if (!branchId) {
                patientSelect.innerHTML = '<option value="">Select branch first</option>';
                patientSelect.disabled = true;
                document.getElementById('patient_search').disabled = true;
                patientCount.textContent = 'Select branch first';
                return;
            }

            patientSelect.disabled = false;
            document.getElementById('patient_search').disabled = false;

            const currentValue = patientSelect.value;
            const currentOption = patientSelect.options[patientSelect.selectedIndex];
            const currentText = currentOption ? currentOption.text : '';
            const currentPatientName = patientNameHidden.value;

            patientSelect.innerHTML = '<option value="">Searching...</option>';
            patientCount.textContent = 'Searching...';

            fetch(`/progress/patients/${branchId}?search=${encodeURIComponent(searchQuery)}`)
                .then(res => res.ok ? res.json() : Promise.reject('Network error'))
                .then(data => {
                    const patients = data.patients || [];
                    patientSelect.innerHTML = '<option value="">Select patient</option>';

                    let foundCurrent = false;
                    patients.forEach(patient => {
                        const option = document.createElement('option');
                        const rawName = (patient.patient_name || '').toString().trim();
                        const rawId = (patient.patient_id || '').toString().trim();

                        option.value = patient.id;

                        let displayText = rawName !== '' ? rawName : (rawId !== '' ? `Patient (ID: ${rawId})` : 'Patient');
                        if (rawName !== '' && rawId !== '') {
                            displayText += ` (ID: ${rawId})`;
                        }

                        option.textContent = displayText;
                        option.dataset.patientName = rawName !== '' ? rawName : displayText;
                        if (patient.id == currentValue) {
                            option.selected = true;
                            foundCurrent = true;
                        }
                        patientSelect.appendChild(option);
                    });

                    if (currentValue && !foundCurrent) {
                        const option = document.createElement('option');
                        option.value = currentValue;
                        option.textContent = currentText || currentPatientName;
                        option.selected = true;
                        option.dataset.patientName = currentPatientName;
                        patientSelect.appendChild(option);
                    }

                    if (patients.length === 0 && !currentValue) {
                        patientSelect.innerHTML = '<option value="">No patients found</option>';
                        patientCount.textContent = '0 patients found';
                    } else {
                        patientCount.textContent = `${patients.length} patients found`;
                    }
                })
                .catch(() => {
                    if (currentValue) {
                        patientSelect.innerHTML = '';
                        const option = document.createElement('option');
                        option.value = currentValue;
                        option.textContent = currentText || currentPatientName;
                        option.selected = true;
                        option.dataset.patientName = currentPatientName;
                        patientSelect.appendChild(option);
                        patientCount.textContent = '';
                    } else {
                        patientSelect.innerHTML = '<option value="">Error loading patients</option>';
                        patientCount.textContent = 'Error loading patients';
                    }
                });
        }

        // Trigger fetch if pre-filled on load
        if (branchSelect.value) {
            fetchPatients();
        }

        branchSelect.addEventListener('change', function () {
            const selectedBranch = this.options[this.selectedIndex];
            branchNameHidden.value = selectedBranch.text;

            document.getElementById('patient_search').value = '';
            // Reset patient select
            patientSelect.innerHTML = '<option value="">Select patient</option>';
            patientSelect.value = '';
            patientNameHidden.value = '';
            fetchPatients();
        });

        // Search input listener
        const patientSearchElem = document.getElementById('patient_search');
        if (patientSearchElem) {
            patientSearchElem.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(fetchPatients, 500);
            });
        }

        patientSelect.addEventListener('change', function () {
            const heightInput = document.getElementById('height');
            const weightInput = document.getElementById('weight');
            const bmiInput = document.getElementById('bmi');

            if (this.value) {
                const selectedOption = this.options[this.selectedIndex];
                patientNameHidden.value = selectedOption.dataset.patientName || selectedOption.textContent;

                // Clear previous patient's metrics immediately to avoid stale values
                heightInput.value = '';
                weightInput.value = '';
                bmiInput.value = '';

                // Auto-fill Height/Weight from pre-feed (patient master data) but still editable
                fetch(`/progress/patient-prefill/${this.value}`)
                    .then(res => res.ok ? res.json() : Promise.reject())
                    .then(payload => {
                        if (!payload || !payload.success || !payload.data) return;

                        if (payload.data.height !== null && payload.data.height !== undefined && payload.data.height !== '') {
                            heightInput.value = payload.data.height;
                        }
                        if (payload.data.weight !== null && payload.data.weight !== undefined && payload.data.weight !== '') {
                            weightInput.value = payload.data.weight;
                        }
                        
                        // Dropdown retains all programs loaded from server
                        
                        calculateBMI();
                    })
                    .catch(() => {
                        // Ignore prefill errors; manual entry still works
                    });
            } else {
                patientNameHidden.value = '';
                heightInput.value = '';
                weightInput.value = '';
                bmiInput.value = '';
            }
        });

        document.getElementById('progressForm').addEventListener('submit', function (e) {
            if (!branchSelect.value) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Branch Required',
                    text: 'Please select a branch'
                });
                branchSelect.focus();
                return false;
            }
            if (!patientSelect.value) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Patient Required',
                    text: 'Please select a patient'
                });
                patientSelect.focus();
                return false;
            }
            if (!patientNameHidden.value) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Incomplete Data',
                    text: 'Patient information is incomplete'
                });
                return false;
            }
        });
    });

    // BMI Calculation Function
    function calculateBMI() {
        const weight = parseFloat(document.getElementById('weight').value);
        const height = parseFloat(document.getElementById('height').value);
        const bmiField = document.getElementById('bmi');

        if (weight > 0 && height > 0) {
            // Convert height from cm to meters
            const heightInMeters = height / 100;
            // Calculate BMI: Weight (kg) / (Height (m) × Height (m))
            const bmi = weight / (heightInMeters * heightInMeters);
            // Round to 2 decimal places
            bmiField.value = bmi.toFixed(2);
        } else {
            bmiField.value = '';
        }
    }

    // -----------------------------------------------
    // All programs list (from embedded JSON)
    // -----------------------------------------------
    var allProgramsList = [];
    try {
        allProgramsList = JSON.parse(document.getElementById('all_programs_json').textContent);
    } catch(e) { allProgramsList = []; }

    // -----------------------------------------------
    // Initialize Select2 for the FIRST program row
    // -----------------------------------------------
    if (typeof $ !== 'undefined' && $.fn.select2) {
        $(document).ready(function () {
            $('#program_name_0').select2({
                placeholder: "Select Program Name",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#program_container_0'),
                dropdownCssClass: 'force-dropdown-below'
            });
        });
    }

    // -----------------------------------------------
    // + / - Row functionality
    // -----------------------------------------------
    var rowCounter = 1; // starts after the default row (index 0)

    document.getElementById('addProgramRowBtn').addEventListener('click', function() {
        addProgramRow();
    });

    function addProgramRow() {
        var idx = rowCounter++;
        var container = document.getElementById('extra_rows_container');

        // Always use the full programs list so every new row shows ALL programs
        var optionsHtml = '<option value="">Select Program Name</option>';
        allProgramsList.forEach(function(p) {
            optionsHtml += '<option value="' + escapeHtml(p.value) + '">' + escapeHtml(p.value) + '</option>';
        });

        var rowHtml =
            '<div class="col-12 program-body-row" id="program_body_row_' + idx + '">' +
                '<div class="row g-3">' +
                    '<div class="form-group col-md-6" id="program_container_' + idx + '" style="position:relative;">' +
                        '<label>Select Program Name</label>' +
                        '<select class="form-control program-select" name="program_name[]" id="program_name_' + idx + '">' +
                            optionsHtml +
                        '</select>' +
                    '</div>' +
                    '<div class="form-group col-md-6">' +
                        '<label>Body Part &amp; Functions</label>' +
                        '<div class="body-part-input-wrap">' +
                            '<input type="text" class="form-control" name="body_part[]" id="body_part_' + idx + '" placeholder="Enter body part &amp; functions">' +
                            '<button type="button" class="btn-remove-row" data-row="' + idx + '" title="Remove row">' +
                                '<span style="margin-top:-2px;">&#8722;</span>' +
                            '</button>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>';

        container.insertAdjacentHTML('beforeend', rowHtml);

        // Init Select2 on the new select
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('#program_name_' + idx).select2({
                placeholder: "Select Program Name",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#program_container_' + idx),
                dropdownCssClass: 'force-dropdown-below'
            });
        }

        // Attach remove handler
        document.querySelector('.btn-remove-row[data-row="' + idx + '"]').addEventListener('click', function() {
            removeRow(idx);
        });
    }

    function removeRow(idx) {
        var row = document.getElementById('program_body_row_' + idx);
        if (row) {
            // Destroy Select2 before removing
            if (typeof $ !== 'undefined' && $.fn.select2) {
                try { $('#program_name_' + idx).select2('destroy'); } catch(e) {}
            }
            row.remove();
        }
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

</script>

@endsection     