<div class="history-readonly-container" style="max-height: 70vh; overflow-y: auto;">
    <style>
        .visit-time-header {
            background: #f8f9fa;
            padding: 12px 15px;
            border-left: 4px solid #086838;
            margin-bottom: 20px;
            border-radius: 8px;
            color: #212529;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .visit-time-badge {
            background: #6c757d;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .single-visit-container {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 20px;
            margin-bottom: 20px;
            background: #ffffff;
        }
        .multiple-visits-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            margin: 15px 0;
        }
        .visit-card {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .visit-card-header {
            background: #343a40;
            color: white;
            padding: 12px;
            border-radius: 4px 4px 0 0;
            margin: -15px -15px 15px -15px;
            text-align: center;
            font-weight: 500;
        }
        
        /* History table styles */
        .history-section {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            background: #ffffff;
        }
        .history-section-title {
            color: #212529;
            border-bottom: 2px solid #495057;
            padding-bottom: 8px;
            margin-bottom: 15px;
            font-weight: 600;
            font-size: 16px;
        }
        .history-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 12px;
        }
        .history-field {
            background: #f8f9fa;
            padding: 10px 12px;
            border-radius: 4px;
            border: 1px solid #e9ecef;
        }
        .history-label {
            font-weight: 600;
            color: #6c757d;
            display: block;
            font-size: 12px;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .history-value {
            color: #212529;
            font-size: 14px;
            word-break: break-word;
        }
        .history-value-empty {
            color: #adb5bd;
            font-style: italic;
        }
        .treatment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: #ffffff;
        }
        .treatment-table th {
            background: #f8f9fa;
            color: #495057;
            padding: 10px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }
        .treatment-table td {
            padding: 10px;
            border-bottom: 1px solid #e9ecef;
            color: #212529;
        }
        .treatment-table tr:hover {
            background: #f8f9fa;
        }
        .compact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 10px;
            margin-bottom: 10px;
        }
        .compact-field {
            background: #f8f9fa;
            padding: 8px 10px;
            border-radius: 4px;
            font-size: 12px;
            color: #212529;
            border: 1px solid #e9ecef;
        }
        .compact-field strong {
            color: #6c757d;
            font-size: 11px;
            display: block;
            margin-bottom: 2px;
        }
        .hover-opacity:hover {
            opacity: 0.8;
        }
        
        @media (max-width: 576px) {
            .multiple-visits-container {
                grid-template-columns: 1fr;
            }
            .history-grid {
                grid-template-columns: 1fr;
            }
            .visit-time-header {
                text-align: center;
            }
            .visit-time-header h5 {
                justify-content: center;
            }
            .visit-time-header .d-flex {
                justify-content: center;
            }
        }
    </style>

    @if(count($followups) === 1)
        <!-- Single Visit View - Show Full Details -->
        @php $followup = $followups->first(); @endphp
        <div class="visit-time-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <h5 class="mb-0 d-flex align-items-center flex-wrap gap-2">
                <i class="bi bi-calendar-check text-success fs-4"></i>
                <span class="fw-bold">Visit at {{ \Carbon\Carbon::parse($followup->followups_time)->format('h:i A') }}</span>
                <span class="visit-time-badge">Full History</span>
            </h5>
            @if($followup->zoom_join_url)
                <div class="d-flex flex-wrap gap-2">
                    @if(auth()->user()->user_role == 6 || auth()->user()->user_role == 1)
                        <a href="{{ route('zoom.join', $followup->id) }}" target="_blank" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 px-3">
                            <i class="bi bi-camera-video"></i> Start Zoom
                        </a>
                    @else
                        <a href="{{ route('zoom.join', $followup->id) }}" target="_blank" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 px-3">
                            <i class="bi bi-camera-video"></i> Join Zoom
                        </a>
                    @endif
                    <button type="button" class="btn btn-sm btn-info text-white d-inline-flex align-items-center gap-1 px-3" title="Copy Patient Link" onclick="copyPatientZoomLink('{{ route('zoom.join', $followup->id) }}')">
                        <i class="bi bi-clipboard"></i> Copy
                    </button>
                </div>
            @endif
        </div>
        
        <!-- Patient Information -->
        <div class="history-section">
            <h6 class="history-section-title">
                <i class="bi bi-person-circle me-2"></i>Patient Information
            </h6>
            <div class="history-grid">
                <div class="history-field">
                    <span class="history-label">Patient Name</span>
                    <span class="history-value">{{ $patient->patient_name }}</span>
                </div>
                <div class="history-field">
                    <span class="history-label">Patient ID</span>
                    <span class="history-value">{{ $patient->patient_id }}</span>
                </div>
                <div class="history-field">
                    <span class="history-label">Age</span>
                    <span class="history-value">{{ $patient->age }}</span>
                </div>
                <div class="history-field">
                    <span class="history-label">Gender</span>
                    <span class="history-value">{{ ucfirst($patient->gender) }}</span>
                </div>
                <div class="history-field">
                    <span class="history-label">Address</span>
                    <span class="history-value">{{ $patient->address }}</span>
                </div>
                <div class="history-field">
                    <span class="history-label">Phone</span>
                    <span class="history-value">{{ $patient->getMeta('phone') ?? 'N/A' }}</span>
                </div>
                <div class="history-field">
                    <span class="history-label">Blood Group</span>
                    <span class="history-value">{{ $patient->getMeta('blood_group') ?? $patient->blood_group ?? 'N/A' }}</span>
                </div>
                <div class="history-field">
                    <span class="history-label">Assigned Doctor</span>
                    <span class="history-value">{{ $followup->doctor ? $followup->doctor->name : 'N/A' }}</span>
                </div>
            </div>
        </div>
        
        <!-- Visit Information -->
        <div class="history-section">
            <h6 class="history-section-title">
                <i class="bi bi-clock-history me-2"></i>Visit Information
            </h6>
            <div class="history-grid">
                <div class="history-field">
                    <span class="history-label">Visit Date</span>
                    <span class="history-value">{{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}</span>
                </div>
                <div class="history-field">
                    <span class="history-label">Visit Time</span>
                    <span class="history-value">{{ \Carbon\Carbon::parse($followup->followups_time)->format('h:i A') }}</span>
                </div>
                <div class="history-field">
                    <span class="history-label">Recorded On</span>
                    <span class="history-value">{{ \Carbon\Carbon::parse($followup->created_at)->format('M d, Y h:i A') }}</span>
                </div>
                <div class="history-field">
                    <span class="history-label">Next Follow-up</span>
                    <span class="history-value {{ !$followup->next_follow_date ? 'history-value-empty' : '' }}">
                        {{ $followup->next_follow_date ? \Carbon\Carbon::parse($followup->next_follow_date)->format('M d, Y') : 'Not Scheduled' }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Vital Signs -->
        @php
            $vitalSigns = [
                'pt_status' => 'PT Status',
                'weight' => 'Weight (kg)',
                'temperature' => 'Temperature (°C)',
                'pulse' => 'Pulse',
                'blood_pressure' => 'Blood Pressure',
                'spo2' => 'SpO2 (%)',
                'rbs' => 'RBS'
            ];
            
            $hasVitals = false;
            foreach($vitalSigns as $key => $label) {
                $metaValue = $followup->getMeta($key);
                if ($metaValue) {
                    $hasVitals = true;
                    break;
                }
            }
        @endphp
        
        @if($hasVitals)
        <div class="history-section">
            <h6 class="history-section-title">
                <i class="bi bi-heart-pulse me-2"></i>Vital Signs
            </h6>
            <div class="history-grid">
                @foreach($vitalSigns as $key => $label)
                    @php
                        $metaValue = $followup->getMeta($key);
                    @endphp
                    <div class="history-field">
                        <span class="history-label">{{ $label }}</span>
                        <span class="history-value {{ !$metaValue ? 'history-value-empty' : '' }}">
                            {{ $metaValue ?? 'Not Recorded' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Diagnosis & Medical History -->
        @php
            $medicalFields = [
                'diagnosis' => 'Diagnosis',
                'investigation' => 'Investigation',
                'past_history' => 'Past History',
                'family_history' => 'Family History'
            ];
            
            $hasMedical = false;
            foreach($medicalFields as $key => $label) {
                $metaValue = $followup->getMeta($key);
                if ($metaValue) {
                    $hasMedical = true;
                    break;
                }
            }
        @endphp
        
        @if($hasMedical)
        <div class="history-section">
            <h6 class="history-section-title">
                <i class="bi bi-clipboard2-pulse me-2"></i>Medical Information
            </h6>
            <div class="history-grid" style="grid-template-columns: 1fr;">
                @foreach($medicalFields as $key => $label)
                    @php
                        $metaValue = $followup->getMeta($key);
                    @endphp
                    @if($metaValue)
                    <div class="history-field">
                        <span class="history-label">{{ $label }}</span>
                        <div class="history-value" style="white-space: pre-wrap;">{{ $metaValue }}</div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Laboratory Tests -->
        @php
            $labTests = [
                'hb' => 'HB',
                'tc' => 'TC',
                'pc' => 'PC',
                'mp' => 'MP',
                'hb1ac' => 'HB1AC',
                'fbs' => 'FBS',
                'pp2bs' => 'PP2BS',
                's_widal' => 'S.widal',
                'usg' => 'USG',
                'x_ray' => 'X-ray',
                'sgpt' => 'SGPT',
                's_creatinine' => 'S. Creatinine',
                'ns1ag' => 'NS1Ag',
                'dengue_igm' => 'Dengue IGM',
                's_cholesterol' => 'S. Cholesterol',
                's_triglyceride' => 'S. Triglyceride',
                'hdl' => 'HDL',
                'ldl' => 'LDL',
                'vldl' => 'VLDL',
                's_b12' => 'S.B12',
                's_d3' => 'S.D3',
                'urine' => 'Urine',
                'crp' => 'CRP',
                's_t3' => 'S.T3',
                's_t4' => 'S.T4',
                's_tsh' => 'S.TSH',
                'esr' => 'ESR',
                'specific_test' => 'Specific Test'
            ];
            
            $hasLabTests = false;
            foreach($labTests as $key => $label) {
                $metaValue = $followup->getMeta($key);
                if ($metaValue) {
                    $hasLabTests = true;
                    break;
                }
            }
        @endphp
        
        @if($hasLabTests)
        <div class="history-section">
            <h6 class="history-section-title">
                <i class="bi bi-flask me-2"></i>Laboratory Tests
            </h6>
            <div class="history-grid">
                @foreach($labTests as $key => $label)
                    @php
                        $metaValue = $followup->getMeta($key);
                    @endphp
                    @if($metaValue)
                    <div class="history-field">
                        <span class="history-label">{{ $label }}</span>
                        <span class="history-value">{{ $metaValue }}</span>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Treatments -->
        @if($followup->treatments && $followup->treatments->count() > 0)
        <div class="history-section">
            <h6 class="history-section-title">
                <i class="bi bi-capsule me-2"></i>Treatments
            </h6>
            
            @php
                $treatmentTypes = [
                    'inside' => 'Inside Treatment',
                    'homeo' => 'Homeopathic Treatment',
                    'prescription' => 'Prescription',
                    'indoor' => 'Indoor Treatment',
                    'other' => 'Other Treatment'
                ];
            @endphp
            
            @foreach($treatmentTypes as $type => $typeLabel)
                @php
                    $typeTreatments = $followup->treatments->where('type', $type);
                @endphp
                @if($typeTreatments->count() > 0)
                <div class="mb-3">
                    <h6 class="text-primary">{{ $typeLabel }}</h6>
                    <table class="treatment-table">
                        <thead>
                            <tr>
                                <th width="30%">Medicine</th>
                                <th width="20%">Dose</th>
                                <th width="20%">Timing</th>
                                <th width="30%">Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($typeTreatments as $treatment)
                            <tr>
                                <td>{{ $treatment->medicine }}</td>
                                <td>{{ $treatment->dose ?? '-' }}</td>
                                <td>{{ $treatment->timing ?? '-' }}</td>
                                <td>{{ $treatment->note ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            @endforeach
        </div>
        @endif
        
        <!-- References & Notes -->
        @php
            $referenceFields = [
                'reference_by' => 'Reference By',
                'referto' => 'Refer To',
                'notes' => 'Notes'
            ];
            
            $hasReferences = false;
            foreach($referenceFields as $key => $label) {
                $metaValue = $followup->getMeta($key);
                if ($metaValue) {
                    $hasReferences = true;
                    break;
                }
            }
        @endphp
        
        @if($hasReferences)
        <div class="history-section">
            <h6 class="history-section-title">
                <i class="bi bi-link-45deg me-2"></i>References & Notes
            </h6>
            <div class="history-grid" style="grid-template-columns: 1fr;">
                @foreach($referenceFields as $key => $label)
                    @php
                        $metaValue = $followup->getMeta($key);
                    @endphp
                    @if($metaValue)
                    <div class="history-field">
                        <span class="history-label">{{ $label }}</span>
                        <div class="history-value" style="white-space: pre-wrap;">{{ $metaValue }}</div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif
        
    @else
        <!-- Multiple Visits View -->
        <div class="visit-time-header">
            <h5 class="mb-0 d-flex align-items-center">
                <i class="bi bi-calendar3 me-2"></i>
                {{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}
                <span class="visit-time-badge">{{ count($followups) }} Visits</span>
            </h5>
        </div>
        
        <div class="alert alert-info d-flex align-items-center">
            <i class="bi bi-info-circle-fill me-2"></i>
            Showing {{ count($followups) }} visits for {{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}
        </div>
        
        <div class="multiple-visits-container">
            @foreach($followups as $index => $followup)
                <div class="visit-card">
                    <div class="visit-card-header d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Visit #{{ $index + 1 }}</strong><br>
                            <small class="opacity-75">{{ \Carbon\Carbon::parse($followup->followups_time)->format('h:i A') }}</small>
                        </div>
                        @if($followup->zoom_join_url)
                            <div class="d-flex gap-2">
                                @if(auth()->user()->user_role == 6 || auth()->user()->user_role == 1)
                                    <a href="{{ route('zoom.join', $followup->id) }}" target="_blank" title="Start Zoom" class="text-white hover-opacity" style="font-size: 1.2rem;">
                                        <i class="bi bi-camera-video-fill"></i>
                                    </a>
                                @else
                                    <a href="{{ route('zoom.join', $followup->id) }}" target="_blank" title="Join Zoom" class="text-white hover-opacity" style="font-size: 1.2rem;">
                                        <i class="bi bi-camera-video-fill"></i>
                                    </a>
                                @endif
                                <a href="javascript:void(0)" title="Copy Patient Link" class="text-white hover-opacity" style="font-size: 1.2rem;" onclick="copyPatientZoomLink('{{ route('zoom.join', $followup->id) }}')">
                                    <i class="bi bi-clipboard"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Compact View for Multiple Visits -->
                    <div class="compact-visit-details">
                        <!-- Vital Signs Summary -->
                        @php
                            $vitalSummary = [];
                            $vitalKeys = [
                                'weight' => 'Weight',
                                'temperature' => 'Temp',
                                'blood_pressure' => 'BP',
                                'pulse' => 'Pulse',
                                'spo2' => 'SpO2',
                                'rbs' => 'RBS'
                            ];
                        @endphp
                        
                        <div class="compact-grid">
                            @foreach($vitalKeys as $key => $label)
                                @php
                                    $value = $followup->getMeta($key);
                                @endphp
                                @if($value)
                                <div class="compact-field">
                                    <strong>{{ $label }}:</strong> {{ $value }}
                                </div>
                                @endif
                            @endforeach
                        </div>
                        
                        <!-- Diagnosis Summary -->
                        @php
                            $diagnosis = $followup->getMeta('diagnosis');
                        @endphp
                        @if($diagnosis && strlen($diagnosis) > 0)
                        <div class="mb-2 p-2 bg-light rounded">
                            <strong>Diagnosis:</strong><br>
                            <small>{{ Str::limit($diagnosis, 150) }}</small>
                        </div>
                        @endif
                        
                        <!-- Treatments Summary -->
                        @if($followup->treatments->count() > 0)
                        <div class="mb-2 p-2 bg-light rounded">
                            <strong>Treatments:</strong>
                            <div class="mt-1">
                                @foreach($followup->treatments as $treatment)
                                    <span class="badge bg-info me-1 mb-1">
                                        {{ $treatment->medicine }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- Laboratory Tests Summary -->
                        @php
                            $labKeys = ['hb', 'fbs', 'hb1ac', 's_cholesterol'];
                            $hasLabs = false;
                            $labValues = [];
                            foreach($labKeys as $key) {
                                $value = $followup->getMeta($key);
                                if ($value) {
                                    $hasLabs = true;
                                    $labValues[$key] = $value;
                                }
                            }
                        @endphp
                        
                        @if($hasLabs)
                        <div class="mb-2 p-2 bg-light rounded">
                            <strong>Key Tests:</strong>
                            <div class="mt-1">
                                @foreach($labValues as $key => $value)
                                    <span class="badge bg-secondary me-1 mb-1">
                                        {{ strtoupper($key) }}: {{ $value }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- Next Follow-up -->
                        @if($followup->next_follow_date)
                        <div class="mb-2 p-2 bg-success text-white rounded">
                            <strong>Next Visit:</strong>
                            {{ \Carbon\Carbon::parse($followup->next_follow_date)->format('M d, Y') }}
                        </div>
                        @endif
                    </div>
                    
                    <div class="text-end mt-3">
                        <button class="btn btn-sm btn-primary" 
                                onclick="loadSingleVisit('{{ $followup->id }}')">
                            <i class="bi bi-eye me-1"></i>View Full History
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>