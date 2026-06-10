<div class="full-history-container" style="max-height: 80vh; overflow-y: auto; padding: 10px;">
    <style>
        .full-history-container .history-section {
            margin-bottom: 25px;
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .full-history-container .history-section-title {
            color: #2c3e50;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 18px;
        }
        .full-history-container .history-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
        }
        .full-history-container .history-field {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        .full-history-container .history-label {
            font-weight: bold;
            color: #2c3e50;
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .full-history-container .history-value {
            color: #495057;
            font-size: 15px;
            word-break: break-word;
        }
        .full-history-container .history-value-empty {
            color: #6c757d;
            font-style: italic;
        }
        .full-history-container .treatment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .full-history-container .treatment-table th {
            background: #007bff;
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-size: 14px;
            font-weight: bold;
        }
        .full-history-container .treatment-table td {
            padding: 10px 15px;
            border-bottom: 1px solid #dee2e6;
        }
        .full-history-container .treatment-table tr:hover {
            background: #f1f3f4;
        }
        .full-history-container .treatment-table tr:last-child td {
            border-bottom: none;
        }
        .full-history-container .medication-badge {
            background: #e3f2fd;
            color: #1976d2;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-right: 5px;
            margin-bottom: 5px;
            display: inline-block;
        }
        .full-history-container .visit-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .full-history-container .section-divider {
            height: 2px;
            background: linear-gradient(to right, transparent, #007bff, transparent);
            margin: 25px 0;
        }
        .full-history-container .text-area-field {
            grid-column: 1 / -1;
        }
        .full-history-container .text-area-field .history-value {
            white-space: pre-wrap;
            line-height: 1.6;
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
    </style>

    <!-- Visit Header -->
    <div class="visit-header">
        <h4 class="mb-2">
            <i class="bi bi-file-medical me-2"></i>
            Complete Medical History
        </h4>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <strong>Patient:</strong> {{ $patient->patient_name }} ({{ $patient->patient_id }})<br>
                <strong>Visit:</strong> {{ \Carbon\Carbon::parse($followup->followup_date)->format('M d, Y') }} 
                at {{ \Carbon\Carbon::parse($followup->followups_time)->format('h:i A') }}
            </div>
            <div class="text-end">
                <span class="badge bg-light text-dark">
                    <i class="bi bi-clock me-1"></i>
                    {{ \Carbon\Carbon::parse($followup->created_at)->format('h:i A') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Patient Information -->
    <div class="history-section">
        <h6 class="history-section-title">
            <i class="bi bi-person-vcard me-2"></i>Patient Information
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
                <span class="history-label">Phone Number</span>
                <span class="history-value">{{ $patient->getMeta('phone') ?? 'N/A' }}</span>
            </div>
        </div>
    </div>

    <!-- Visit Information -->
    <div class="history-section">
        <h6 class="history-section-title">
            <i class="bi bi-calendar2-check me-2"></i>Visit Information
        </h6>
        <div class="history-grid">
            <div class="history-field">
                <span class="history-label">Visit Date</span>
                <span class="history-value">{{ \Carbon\Carbon::parse($followup->followup_date)->format('F d, Y') }}</span>
            </div>
            <div class="history-field">
                <span class="history-label">Visit Time</span>
                <span class="history-value">{{ \Carbon\Carbon::parse($followup->followups_time)->format('h:i A') }}</span>
            </div>
            <div class="history-field">
                <span class="history-label">Recorded On</span>
                <span class="history-value">{{ \Carbon\Carbon::parse($followup->created_at)->format('F d, Y h:i A') }}</span>
            </div>
            @if($followup->next_follow_date)
            <div class="history-field">
                <span class="history-label">Next Follow-up Date</span>
                <span class="history-value text-success">
                    <i class="bi bi-calendar2-plus me-1"></i>
                    {{ \Carbon\Carbon::parse($followup->next_follow_date)->format('F d, Y') }}
                </span>
            </div>
            @endif
        </div>
    </div>

    <!-- Vital Signs -->
    <div class="history-section">
        <h6 class="history-section-title">
            <i class="bi bi-heart-pulse me-2"></i>Vital Signs
        </h6>
        <div class="history-grid">
            @php
                $vitalSigns = [
                    'pt_status' => ['label' => 'PT Status', 'icon' => 'bi-person-badge'],
                    'weight' => ['label' => 'Weight (kg)', 'icon' => 'bi-speedometer2'],
                    'temperature' => ['label' => 'Temperature (°C)', 'icon' => 'bi-thermometer'],
                    'pulse' => ['label' => 'Pulse', 'icon' => 'bi-heart'],
                    'blood_pressure' => ['label' => 'Blood Pressure', 'icon' => 'bi-heart-pulse'],
                    'spo2' => ['label' => 'SpO2 (%)', 'icon' => 'bi-activity'],
                    'rbs' => ['label' => 'RBS', 'icon' => 'bi-droplet']
                ];
            @endphp
            
            @foreach($vitalSigns as $key => $info)
                @php
                    $metaValue = $followup->getMeta($key);
                @endphp
                <div class="history-field">
                    <span class="history-label">
                        <i class="{{ $info['icon'] }} me-1"></i>{{ $info['label'] }}
                    </span>
                    <span class="history-value {{ !$metaValue ? 'history-value-empty' : '' }}">
                        {{ $metaValue ?? 'Not Recorded' }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Diagnosis & Medical History -->
    <div class="history-section">
        <h6 class="history-section-title">
            <i class="bi bi-clipboard2-pulse me-2"></i>Medical Information
        </h6>
        <div class="history-grid" style="grid-template-columns: 1fr;">
            @php
                $medicalFields = [
                    'diagnosis' => ['label' => 'Diagnosis', 'icon' => 'bi-clipboard-check'],
                    'investigation' => ['label' => 'Investigation', 'icon' => 'bi-search'],
                    'past_history' => ['label' => 'Past History', 'icon' => 'bi-clock-history'],
                    'family_history' => ['label' => 'Family History', 'icon' => 'bi-people']
                ];
            @endphp
            
            @foreach($medicalFields as $key => $info)
                @php
                    $metaValue = $followup->getMeta($key);
                @endphp
                @if($metaValue)
                <div class="history-field text-area-field">
                    <span class="history-label">
                        <i class="{{ $info['icon'] }} me-1"></i>{{ $info['label'] }}
                    </span>
                    <div class="history-value">{{ $metaValue }}</div>
                </div>
                @endif
            @endforeach
        </div>
    </div>

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
        $labValues = [];
        foreach($labTests as $key => $label) {
            $metaValue = $followup->getMeta($key);
            if ($metaValue) {
                $hasLabTests = true;
                $labValues[$key] = ['label' => $label, 'value' => $metaValue];
            }
        }
    @endphp
    
    @if($hasLabTests)
    <div class="history-section">
        <h6 class="history-section-title">
            <i class="bi bi-flask me-2"></i>Laboratory Tests
        </h6>
        <div class="history-grid">
            @foreach($labValues as $key => $data)
            <div class="history-field">
                <span class="history-label">{{ $data['label'] }}</span>
                <span class="history-value">{{ $data['value'] }}</span>
            </div>
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
                'inside' => ['label' => 'Inside Treatment', 'icon' => 'bi-house-door', 'color' => 'primary'],
                'homeo' => ['label' => 'Homeopathic Treatment', 'icon' => 'bi-droplet', 'color' => 'info'],
                'prescription' => ['label' => 'Prescription', 'icon' => 'bi-prescription', 'color' => 'success'],
                'indoor' => ['label' => 'Indoor Treatment', 'icon' => 'bi-building', 'color' => 'warning'],
                'other' => ['label' => 'Other Treatment', 'icon' => 'bi-plus-circle', 'color' => 'secondary']
            ];
        @endphp
        
        @foreach($treatmentTypes as $type => $typeInfo)
            @php
                $typeTreatments = $followup->treatments->where('type', $type);
            @endphp
            @if($typeTreatments->count() > 0)
            <div class="mb-4">
                <h6 class="text-{{ $typeInfo['color'] }} mb-3">
                    <i class="{{ $typeInfo['icon'] }} me-2"></i>{{ $typeInfo['label'] }}
                </h6>
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
                            <td>
                                <i class="bi bi-capsule me-1 text-{{ $typeInfo['color'] }}"></i>
                                {{ $treatment->medicine }}
                            </td>
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

    <!-- Other Information -->
    @php
        $otherFields = [
            'reference_by' => ['label' => 'Reference By', 'icon' => 'bi-person-check'],
            'referto' => ['label' => 'Refer To', 'icon' => 'bi-arrow-right-circle'],
            'notes' => ['label' => 'Notes', 'icon' => 'bi-sticky']
        ];
        
        $hasOther = false;
        foreach($otherFields as $key => $info) {
            $metaValue = $followup->getMeta($key);
            if ($metaValue) {
                $hasOther = true;
            }
        }
    @endphp
    
    @if($hasOther)
    <div class="history-section">
        <h6 class="history-section-title">
            <i class="bi bi-info-circle me-2"></i>Other Information
        </h6>
        <div class="history-grid">
            @foreach($otherFields as $key => $info)
                @php
                    $metaValue = $followup->getMeta($key);
                @endphp
                @if($metaValue)
                <div class="history-field">
                    <span class="history-label">
                        <i class="{{ $info['icon'] }} me-1"></i>{{ $info['label'] }}
                    </span>
                    <span class="history-value">{{ $metaValue }}</span>
                </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    <!-- Summary -->
    <div class="history-section bg-light">
        <h6 class="history-section-title">
            <i class="bi bi-file-text me-2"></i>Visit Summary
        </h6>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <strong>Total Treatments:</strong>
                    <span class="badge bg-primary ms-2">{{ $followup->treatments->count() }}</span>
                </div>
                <div class="mb-3">
                    <strong>Laboratory Tests:</strong>
                    <span class="badge bg-info ms-2">{{ count($labValues) }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <strong>Vital Signs Recorded:</strong>
                    <span class="badge bg-success ms-2">
                        @php
                            $vitalCount = 0;
                            foreach($vitalSigns as $key => $info) {
                                if ($followup->getMeta($key)) $vitalCount++;
                            }
                        @endphp
                        {{ $vitalCount }}/{{ count($vitalSigns) }}
                    </span>
                </div>
                <div class="mb-3">
                    <strong>Visit Duration:</strong>
                    <span class="badge bg-secondary ms-2">
                        {{ \Carbon\Carbon::parse($followup->created_at)->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>