@extends('admin.layouts.layouts')

@section('title', 'Monthly Assessment')

@section('content')

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-emerald: #086838;
            --accent-mint: #d1e7dd;
            --soft-gray: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --glass-border: rgba(226, 232, 240, 0.8);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--soft-gray);
        }

        .premium-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin: 2.5rem 0 1.5rem;
            position: relative;
        }

        .section-header span {
            background: #fff;
            padding-right: 15px;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--primary-emerald);
            z-index: 1;
        }

        .section-header:after {
            content: "";
            flex-grow: 1;
            height: 1px;
            background: linear-gradient(to right, #e2e8f0, transparent);
        }

        .form-floating-custom {
            margin-bottom: 1.25rem;
        }

        .form-label-custom {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            display: block;
        }

        .premium-input {
            width: 100%;
            padding: 10px 16px;
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            color: var(--text-dark);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .premium-input:focus {
            outline: none;
            border-color: var(--primary-emerald);
            box-shadow: 0 0 0 4px rgba(8, 104, 56, 0.1);
            background: #fff;
        }

        .premium-input:read-only {
            background: var(--soft-gray);
            cursor: not-allowed;
            color: var(--text-muted);
        }

        .btn-premium {
            background: var(--primary-emerald);
            color: #fff;
            padding: 12px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            border: none;
            box-shadow: 0 4px 12px rgba(8, 104, 56, 0.2);
            transition: all 0.3s ease;
        }

        .btn-premium:hover {
            background: #067945;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(8, 104, 56, 0.3);
            color: #fff;
        }

        .btn-secondary-premium {
            background: #fff;
            color: var(--text-dark);
            border: 1.5px solid #e2e8f0;
            padding: 12px 32px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-secondary-premium:hover {
            background: var(--soft-gray);
            border-color: #cbd5e1;
        }

        .measurement-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .input-group-premium {
            position: relative;
        }

        .input-unit {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            color: var(--text-muted);
            pointer-events: none;
        }

        @media (max-width: 640px) {
            .measurement-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--soft-gray);
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <div class="container py-4">
        <div class="premium-card p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 fw-bold mb-0" style="color: var(--primary-emerald);">Monthly Assessment</h2>
                <div class="badge px-3 py-2" style="background: var(--accent-mint); color: var(--primary-emerald); border-radius: 20px;">
                    <i class="fas fa-calendar-check me-2"></i>Patient Records
                </div>
            </div>

            <!-- Alert Message -->
            <div id="alertContainer" class="mb-4" style="display: none;">
                <div id="alertBox" class="alert alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
                    <span id="alertMessage"></span>
                    <button type="button" class="btn-close" onclick="this.parentElement.parentElement.style.display='none'"></button>
                </div>
            </div>

            <form id="monthlyAssessmentForm">
                @csrf

                <div class="section-header"><span>Basic Context</span></div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label-custom">Assessment Date</label>
                        <input type="date" id="assessmentDate" name="assessment_date" value="{{ date('Y-m-d') }}" class="premium-input" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-custom">Branch</label>
                        <select name="branch_id" id="branchSelect" class="premium-input" required>
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                                @if($branch->delete_status == '0' || $branch->delete_status == '')
                                    <option value="{{ $branch->branch_id }}">{{ $branch->branch_name }}</option>
                                @endif
                             @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-custom">Search Patient</label>
                        <div class="input-group-premium">
                            <input type="text" id="patientSearch" class="premium-input" placeholder="Type name or ID..." disabled>
                            <span class="input-unit"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-custom">Select Patient</label>
                        <select name="patient_id" id="patientSelect" class="premium-input" required disabled>
                            <option value="">Choose Branch First</option>
                        </select>
                        <div class="text-muted small mt-1" id="patientCount" style="font-size: 11px;"></div>
                    </div>
                </div>

                <!-- Hidden fields for saving -->
                <input type="hidden" name="patient_name" id="patient_name_hidden">
                <input type="hidden" name="branch_name" id="branch_name_hidden">
                <input type="hidden" name="patient_code" id="patient_code_hidden">

                <div class="section-header"><span>Measurement Report</span></div>

                <div class="measurement-grid mb-4">
                    <div class="input-group-premium">
                        <label class="form-label-custom">Waist Upper</label>
                        <input type="number" step="0.1" name="waist_upper" id="measure_waist_upper" class="premium-input" placeholder="0.0">
                        <span class="input-unit">cm</span>
                    </div>
                    <div class="input-group-premium">
                        <label class="form-label-custom">Waist Middle</label>
                        <input type="number" step="0.1" name="waist_middle" id="measure_waist_middle" class="premium-input" placeholder="0.0">
                        <span class="input-unit">cm</span>
                    </div>
                    <div class="input-group-premium">
                        <label class="form-label-custom">Waist Lower</label>
                        <input type="number" step="0.1" name="waist_lower" id="measure_waist_lower" class="premium-input" placeholder="0.0">
                        <span class="input-unit">cm</span>
                    </div>
                </div>

                <div class="measurement-grid mb-4">
                    <div class="input-group-premium">
                        <label class="form-label-custom">Hips</label>
                        <input type="number" step="0.1" name="hips" id="measure_hips" class="premium-input" placeholder="0.0">
                        <span class="input-unit">cm</span>
                    </div>
                    <div class="input-group-premium">
                        <label class="form-label-custom">Thighs</label>
                        <input type="number" step="0.1" name="thighs" id="measure_thighs" class="premium-input" placeholder="0.0">
                        <span class="input-unit">cm</span>
                    </div>
                    <div class="input-group-premium">
                        <label class="form-label-custom">Arms</label>
                        <input type="number" step="0.1" name="arms" id="measure_arms" class="premium-input" placeholder="0.0">
                        <span class="input-unit">cm</span>
                    </div>
                </div>

                <div class="measurement-grid mb-4">
                    <div class="input-group-premium">
                        <label class="form-label-custom">Waist/Hips Ratio</label>
                        <input type="number" step="0.01" name="waist_hips" id="measure_waist_hips" class="premium-input" placeholder="0.00" readonly>
                    </div>
                    <div class="input-group-premium">
                        <label class="form-label-custom">Height</label>
                        <input type="number" step="0.01" name="height" id="measure_height" class="premium-input" placeholder="cm/m">
                    </div>
                    <div class="input-group-premium">
                        <label class="form-label-custom">Weight</label>
                        <input type="number" step="0.1" name="weight" id="measure_weight" class="premium-input" placeholder="0.0">
                        <span class="input-unit">kg</span>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="form-label-custom">BMI (kg/m²)</label>
                        <input type="number" step="0.1" name="bmi" id="measure_bmi" class="premium-input" placeholder="0.0" readonly>
                    </div>
                </div>

                <div class="section-header"><span>Body Composition Analysis</span></div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 rounded-4" style="background: rgba(8, 104, 56, 0.03); border: 1px solid rgba(8, 104, 56, 0.08);">
                            <label class="form-label-custom mb-3" style="color: var(--primary-emerald);">Fat Mass (%)</label>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="small text-muted mb-1">WBF</label>
                                    <input type="number" step="0.1" name="bca_vbf" id="bca_vbf" class="premium-input" placeholder="0.0">
                                </div>
                                <div class="col-6">
                                    <label class="small text-muted mb-1">Arms</label>
                                    <input type="number" step="0.1" name="bca_arms" id="bca_arms" class="premium-input" placeholder="0.0">
                                </div>
                                <div class="col-6">
                                    <label class="small text-muted mb-1">Trunk</label>
                                    <input type="number" step="0.1" name="bca_trunk" id="bca_trunk" class="premium-input" placeholder="0.0">
                                </div>
                                <div class="col-6">
                                    <label class="small text-muted mb-1">Legs</label>
                                    <input type="number" step="0.1" name="bca_legs" id="bca_legs" class="premium-input" placeholder="0.0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 rounded-4" style="background: rgba(30, 41, 59, 0.03); border: 1px solid rgba(30, 41, 59, 0.08);">
                            <label class="form-label-custom mb-3" style="color: var(--text-dark);">Skeletal Muscle Mass (kg)</label>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="small text-muted mb-1">WBF</label>
                                    <input type="number" step="0.1" name="muscle_vbf" id="muscle_vbf" class="premium-input" placeholder="0.0">
                                </div>
                                <div class="col-6">
                                    <label class="small text-muted mb-1">Arms</label>
                                    <input type="number" step="0.1" name="muscle_arms" id="muscle_arms" class="premium-input" placeholder="0.0">
                                </div>
                                <div class="col-6">
                                    <label class="small text-muted mb-1">Trunk</label>
                                    <input type="number" step="0.1" name="muscle_trunk" id="muscle_trunk" class="premium-input" placeholder="0.0">
                                </div>
                                <div class="col-6">
                                    <label class="small text-muted mb-1">Legs</label>
                                    <input type="number" step="0.1" name="muscle_legs" id="muscle_legs" class="premium-input" placeholder="0.0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-6">
                        <label class="form-label-custom">Additional Tags</label>
                        <div class="d-flex gap-3 mt-2">
                             <div class="input-group-premium flex-grow-1">
                                <label class="small text-muted mb-1">S.F. (%)</label>
                                <input type="number" step="0.1" name="bca_sf" id="bca_sf" class="premium-input" placeholder="0.0">
                            </div>
                            <div class="input-group-premium flex-grow-1">
                                <label class="small text-muted mb-1">V.F. (%)</label>
                                <input type="number" step="0.1" name="bca_vf" id="bca_vf" class="premium-input" placeholder="0.0">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3">
                    <button type="reset" class="btn btn-secondary-premium">Reset Data</button>
                    <button type="button" class="btn btn-premium" id="submitAssessmentBtn">
                        <i class="fas fa-paper-plane me-2"></i>Submit Final Report
                    </button>
                </div>
            </form>
        </div>
    </div>
        </div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const branchSelect = document.getElementById('branchSelect');
            const patientSelect = document.getElementById('patientSelect');
            const patientSearch = document.getElementById('patientSearch');
            const patientNameHidden = document.getElementById('patient_name_hidden');
            const branchNameHidden = document.getElementById('branch_name_hidden');
            const patientCodeHidden = document.getElementById('patient_code_hidden');
            const patientCount = document.getElementById('patientCount');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            function showAlert(type, message) {
                const container = document.getElementById('alertContainer');
                const box = document.getElementById('alertBox');
                const msg = document.getElementById('alertMessage');

                container.style.display = 'block';
                box.className = `alert alert-dismissible fade show alert-${type === 'success' ? 'success' : 'danger'}`;
                msg.textContent = message;

                window.scrollTo({ top: 0, behavior: 'smooth' });
                setTimeout(() => { container.style.display = 'none'; }, 5000);
            }

            let searchTimeout;
            
            function fetchPatients() {
                const branchId = branchSelect.value;
                const searchQuery = patientSearch.value;

                if (!branchId) {
                    patientSelect.innerHTML = '<option value="">Select Branch First</option>';
                    patientSelect.disabled = true;
                    patientSearch.disabled = true;
                    return;
                }

                patientSelect.innerHTML = '<option value="">Searching...</option>';
                patientSelect.disabled = true;
                patientSearch.disabled = false;

                fetch('/monthly-assessment/get-patients', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ 
                        branch_id: branchId,
                        search: searchQuery
                    })
                })
                .then(res => res.json())
                .then(data => {
                    patientSelect.innerHTML = '<option value="">Choose Patient</option>';
                    if (data.success && data.patients.length > 0) {
                        const isMobile = window.innerWidth <= 768;
                        data.patients.forEach(patient => {
                            const option = document.createElement('option');
                            option.value = patient.id;

                            let displayName = patient.patient_name;
                            if (isMobile && displayName.length > 18) {
                                displayName = displayName.substring(0, 18) + '..';
                            }

                            let idText = patient.patient_id ? `(ID: ${patient.patient_id})` : '';
                            if (isMobile && idText.length > 15) {
                                idText = idText.substring(0, 15) + '..';
                            }

                            option.textContent = displayName + ' ' + idText;
                            option.dataset.patientName = patient.patient_name;
                            option.dataset.patientCode = patient.patient_id;
                            patientSelect.appendChild(option);
                        });
                        patientSelect.disabled = false;
                        patientCount.textContent = `${data.patients.length} patients found`;
                    } else {
                        patientSelect.innerHTML = '<option value="">No patients found</option>';
                        patientCount.textContent = '0 patients found';
                    }
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    patientSelect.innerHTML = '<option value="">Error loading patients</option>';
                });
            }

            branchSelect.addEventListener('change', function () {
                const selectedBranch = this.options[this.selectedIndex];
                branchNameHidden.value = selectedBranch.text.trim();
                patientSearch.value = ''; // Reset search on branch change
                fetchPatients();
            });

            patientSearch.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(fetchPatients, 500); // 500ms debounce
            });

            patientSelect.addEventListener('change', function () {
                const option = this.options[this.selectedIndex];
                if (this.value) {
                    patientNameHidden.value = option.dataset.patientName;
                    patientCodeHidden.value = option.dataset.patientCode;
                }
            });

            // Auto Calculations
            const waist = document.getElementById('measure_waist_middle');
            const hips = document.getElementById('measure_hips');
            const whr = document.getElementById('measure_waist_hips');
            const weight = document.getElementById('measure_weight');
            const height = document.getElementById('measure_height');
            const bmi = document.getElementById('measure_bmi');

            const runMath = () => {
                const wVal = parseFloat(waist.value) || 0;
                const hVal = parseFloat(hips.value) || 0;
                const weVal = parseFloat(weight.value) || 0;
                const heVal = parseFloat(height?.value) || 0;

                if (wVal > 0 && hVal > 0) whr.value = (wVal / hVal).toFixed(2);

                if (weVal > 0 && heVal > 0) {
                    let heightM = heVal > 3 ? heVal / 100 : heVal;
                    bmi.value = (weVal / (heightM * heightM)).toFixed(1);
                } else {
                    bmi.value = '';
                }
            };

            [waist, hips, weight, height].forEach(el => {
                if (el) el.addEventListener('input', runMath);
            });

            async function submitAssessment(status) {
                if (!branchSelect.value || !patientSelect.value) {
                    showAlert('error', 'Please select branch and patient.');
                    return;
                }

                const btn = status === 'draft' ? document.getElementById('saveDraftBtn') : document.getElementById('submitAssessmentBtn');
                const origText = btn.textContent;
                btn.innerHTML = '<span class="loading-spinner"></span> Saving...';
                btn.disabled = true;

                const formData = {
                    branch_id: branchSelect.value,
                    patient_id: patientSelect.value,
                    patient_name: patientNameHidden.value,
                    patient_code: patientCodeHidden.value,
                    branch_name: branchNameHidden.value,
                    assessment_date: document.getElementById('assessmentDate').value,
                    status: status,
                    measurements: {},
                    diet: document.getElementById('diet')?.value || '',
                    exercise: document.getElementById('exercise')?.value || '',
                    sleep: document.getElementById('sleep')?.value || '',
                    water: document.getElementById('water')?.value || ''
                };

                const numerics = [
                    'waist_upper', 'waist_middle', 'waist_lower', 'hips', 'thighs', 'arms', 'waist_hips', 'weight', 'bmi',
                    'bca_vbf', 'bca_arms', 'bca_trunk', 'bca_legs', 'bca_sf', 'bca_vf',
                    'muscle_vbf', 'muscle_arms', 'muscle_trunk', 'muscle_legs'
                ];

                numerics.forEach(key => {
                    const el = document.getElementsByName(key)[0] || document.getElementById('measure_' + key);
                    if (el) formData.measurements[key] = el.value !== '' ? parseFloat(el.value) : null;
                });

                try {
                    const response = await fetch('/monthly-assessment/store', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify(formData)
                    });
                    const data = await response.json();
                    if (data.success) {
                        showAlert('success', data.message);
                        if (status === 'submitted') setTimeout(() => { location.reload(); }, 2000);
                    } else {
                        showAlert('error', data.message || 'Submission failed');
                    }
                } catch (e) {
                    showAlert('error', 'Connection error');
                } finally {
                    btn.textContent = origText;
                    btn.disabled = false;
                }
            }

            document.getElementById('submitAssessmentBtn').addEventListener('click', () => {
                // Basic validation before confirmation
                if (!branchSelect.value || !patientSelect.value) {
                    showAlert('error', 'Please select branch and patient.');
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to submit this monthly report?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, submit it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitAssessment('submitted');
                    }
                });
            });

            document.getElementById('monthlyAssessmentForm').addEventListener('submit', e => e.preventDefault());
        });
    </script>

@endsection