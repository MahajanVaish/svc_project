<div class="history-readonly-container" style="max-height: 70vh; overflow-y: auto;">
    <style>
        .history-readonly-container .section-divider {
            background: #f8f9fa;
            padding: 10px;
            margin: 15px 0;
            border-left: 4px solid #20c063;
            font-weight: bold;
            color: #2c3e50;
        }
        .history-readonly-container .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        .history-readonly-container .form-col {
            flex: 1;
            min-width: 200px;
        }
        .history-readonly-container .form-col-2 {
            flex: 2;
        }
        .history-readonly-container .readonly-field {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 8px 12px;
            min-height: 38px;
            display: flex;
            align-items: center;
        }
        .history-readonly-container .readonly-textarea {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 8px 12px;
            min-height: 80px;
            white-space: pre-wrap;
        }
        .history-readonly-container label {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
            display: block;
            font-size: 14px;
        }
        .history-readonly-container .dynamic-field-group {
            margin-bottom: 10px;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }
        .history-readonly-container .badge {
            margin: 2px;
            font-size: 12px;
        }
        .history-readonly-container .medicine-row {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>

    <div class="alert alert-success mb-3">
        <strong>Follow-up Date:</strong> {{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}
    </div>

    <!-- Personal Information -->
    <div class="section-divider">Personal Information</div>

    <div class="form-row">
        <div class="form-col">
            <label>Patient Name</label>
            <div class="readonly-field">{{ $patient->patient_name }}</div>
        </div>
        <div class="form-col">
            <label>Address</label>
            <div class="readonly-field">{{ $patient->address }}</div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-col">
            <label>FollowUp Date</label>
            <div class="readonly-field">{{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}</div>
        </div>
        <div class="form-col">
            <label>Gender</label>
            <div class="readonly-field">{{ ucfirst($patient->gender) }}</div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-col">
            <label>Age</label>
            <div class="readonly-field">{{ $patient->age }}</div>
        </div>
        <div class="form-col">
            <label>Phone Number</label>
            <div class="readonly-field">{{ $patient->getMeta('phone') ?? 'N/A' }}</div>
        </div>
    </div>

    <!-- Weight -->
    @if(!empty($followupMetaValues['weight']))
    <div class="form-row">
        <div class="form-col">
            <label>Weight (kg)</label>
            <div class="dynamic-fields-container">
                @foreach($followupMetaValues['weight'] as $weight)
                <div class="dynamic-field-group">
                    <div class="readonly-field">{{ $weight }} kg</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Vital Signs -->
    <div class="section-divider">Vital Signs</div>

    <div class="form-row">
        @if(!empty($followupMetaValues['pt_status']))
        <div class="form-col">
            <label>PT Status</label>
            <div class="dynamic-fields-container">
                @foreach($followupMetaValues['pt_status'] as $status)
                <div class="dynamic-field-group">
                    <div class="readonly-field">{{ $status }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if(!empty($followupMetaValues['temperature']))
        <div class="form-col">
            <label>Temperature (°C)</label>
            <div class="dynamic-fields-container">
                @foreach($followupMetaValues['temperature'] as $temp)
                <div class="dynamic-field-group">
                    <div class="readonly-field">{{ $temp }}°C</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if(!empty($followupMetaValues['pulse']))
        <div class="form-col">
            <label>Pulse</label>
            <div class="dynamic-fields-container">
                @foreach($followupMetaValues['pulse'] as $pulse)
                <div class="dynamic-field-group">
                    <div class="readonly-field">{{ $pulse }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="form-row">
        @if(!empty($followupMetaValues['blood_pressure']))
        <div class="form-col">
            <label>Blood Pressure</label>
            <div class="dynamic-fields-container">
                @foreach($followupMetaValues['blood_pressure'] as $bp)
                <div class="dynamic-field-group">
                    <div class="readonly-field">{{ $bp }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if(!empty($followupMetaValues['spo2']))
        <div class="form-col">
            <label>SpO2 (%)</label>
            <div class="dynamic-fields-container">
                @foreach($followupMetaValues['spo2'] as $spo2)
                <div class="dynamic-field-group">
                    <div class="readonly-field">{{ $spo2 }}%</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if(!empty($followupMetaValues['rbs']))
        <div class="form-col">
            <label>RBS</label>
            <div class="dynamic-fields-container">
                @foreach($followupMetaValues['rbs'] as $rbs)
                <div class="dynamic-field-group">
                    <div class="readonly-field">{{ $rbs }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Diagnosis -->
    @if(!empty($followupMetaValues['diagnosis']))
    <div class="section-divider">Diagnosis</div>
    <div class="form-row">
        <div class="form-col-2">
            <div class="dynamic-fields-container">
                @foreach($followupMetaValues['diagnosis'] as $diagnosis)
                <div class="dynamic-field-group">
                    <div class="readonly-textarea">{{ $diagnosis }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Medical Information -->
    <div class="section-divider">Medical Information</div>

    <div class="form-row">
        @if(!empty($followupMetaValues['investigation']))
        <div class="form-col-2">
            <label>Investigation</label>
            <div class="readonly-textarea">{{ $followupMetaValues['investigation'][0] ?? '' }}</div>
        </div>
        @endif

        @if(!empty($followupMetaValues['past_history']))
        <div class="form-col-2">
            <label>Past History</label>
            <div class="readonly-textarea">{{ $followupMetaValues['past_history'][0] ?? '' }}</div>
        </div>
        @endif

        @if(!empty($followupMetaValues['family_history']))
        <div class="form-col-2">
            <label>Family History</label>
            <div class="readonly-textarea">{{ $followupMetaValues['family_history'][0] ?? '' }}</div>
        </div>
        @endif
    </div>

    <!-- Laboratory Tests -->
    @php
        $labFields = [
            'hb' => 'HB', 'tc' => 'TC', 'pc' => 'PC', 'mp' => 'MP',
            'hb1ac' => 'HB1AC', 'fbs' => 'FBS', 'pp2bs' => 'PP2BS',
            's_widal' => 'S.widal', 'usg' => 'USG', 'x_ray' => 'X-ray',
            'sgpt' => 'SGPT', 's_creatinine' => 'S. Creatinine',
            'ns1ag' => 'NS1Ag', 'dengue_igm' => 'Dengue IGM',
            's_cholesterol' => 'S. Cholesterol', 's_triglyceride' => 'S. Triglyceride',
            'hdl' => 'HDL', 'ldl' => 'LDL', 'vldl' => 'VLDL',
            's_b12' => 'S.B12', 's_d3' => 'S.D3', 'urine' => 'Urine',
            's_t3' => 'S.T3', 'crp' => 'CRP', 's_t4' => 'S.T4',
            's_tsh' => 'S.TSH', 'esr' => 'ESR'
        ];
        $hasLabData = false;
        foreach($labFields as $key => $label) {
            if(!empty($followupMetaValues[$key])) {
                $hasLabData = true;
                break;
            }
        }
    @endphp

    @if($hasLabData)
    <div class="section-divider">Laboratory Tests</div>
    <div class="form-row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
        @foreach($labFields as $key => $label)
            @if(!empty($followupMetaValues[$key]))
            <div class="form-col">
                <label>{{ $label }}</label>
                <div class="dynamic-fields-container">
                    @foreach($followupMetaValues[$key] as $value)
                    <div class="dynamic-field-group">
                        <div class="readonly-field">{{ $value }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endforeach
    </div>
    @endif

    <!-- Treatments -->
    @php $hasTreatments = false; @endphp
    @foreach($treatments as $type => $rows)
        @if(count($rows) > 0)
            @php $hasTreatments = true; @endphp
        @endif
    @endforeach

    @if($hasTreatments)
    <div class="section-divider">Treatments</div>

    @foreach($treatments as $type => $rows)
        @if(count($rows) > 0)
        <div class="treatment-section mb-4">
            <h6 class="text-primary mb-3">{{ ucfirst($type) }} Treatment</h6>
            <div class="form-row">
                @foreach($rows as $index => $row)
                <div class="form-col-2">
                    <div class="medicine-row">
                        <div class="mb-2">
                            <strong>Medicine:</strong> {{ $row['medicine'] ?? 'N/A' }}
                        </div>
                        @if(isset($row['dose']) && $row['dose'])
                        <div class="mb-2">
                            <strong>Dose:</strong> {{ $row['dose'] }}
                        </div>
                        @endif
                        @if(isset($row['timing']) && $row['timing'])
                        <div class="mb-2">
                            <strong>Timing:</strong> {{ $row['timing'] }}
                        </div>
                        @endif
                        @if(isset($row['note']) && $row['note'])
                        <div class="mb-2">
                            <strong>Note:</strong> {{ $row['note'] }}
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        @endif
    @endforeach
    @endif

    @if(!$hasLabData && !$hasTreatments && empty($followupMetaValues['diagnosis']))
    <div class="alert alert-warning text-center">
        No detailed information available for this follow-up.
    </div>
    @endif
</div>
