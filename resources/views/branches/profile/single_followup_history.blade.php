@php
    // Extract meta values from followup
    $metaValues = [];
    foreach ($metaKeys ?? [] as $key) {
        $values = $followup->metas
            ->filter(function($meta) use ($key) {
                return $meta->meta_key === $key || 
                       \Illuminate\Support\Str::startsWith($meta->meta_key, $key . '_');
            })
            ->sortBy(function($meta) {
                if (preg_match('/_(\d+)$/', $meta->meta_key, $matches)) {
                    return (int)$matches[1];
                }
                return 0;
                })
            ->pluck('meta_value')
            ->values()
            ->toArray();
        
        $metaValues[$key] = $values;
    }
    
    // Extract treatments by type
    $treatmentsByType = [
        'inside' => [],
        'homeo' => [],
        'prescription' => [],
        'indoor' => [],
        'other' => []
    ];
    
    foreach ($followup->treatments as $treatment) {
        $type = $treatment->type;
        if (isset($treatmentsByType[$type])) {
            $treatmentsByType[$type][] = [
                'medicine' => $treatment->medicine,
                'dose' => $treatment->dose,
                'timing' => $treatment->timing,
                'note' => $treatment->note
            ];
        }
    }
@endphp

@if(!isset($compact))
<div class="section-divider">Visit Information</div>
<div class="form-row">
    <div class="form-col">
        <label>Visit Time</label>
        <div class="readonly-field">
            {{ \Carbon\Carbon::parse($followup->followups_time)->format('h:i A') }}
        </div>
    </div>
    <div class="form-col">
        <label>Recorded On</label>
        <div class="readonly-field">
            {{ \Carbon\Carbon::parse($followup->created_at)->format('M d, Y h:i A') }}
        </div>
    </div>
</div>
@endif

<!-- Vital Signs -->
@if(!empty($metaValues['pt_status']) || !empty($metaValues['temperature']) || 
    !empty($metaValues['pulse']) || !empty($metaValues['blood_pressure']))
<div class="section-divider">Vital Signs</div>
<div class="form-row">
    @if(!empty($metaValues['pt_status']))
    <div class="form-col">
        <label>PT Status</label>
        <div class="readonly-field">
            @foreach($metaValues['pt_status'] as $status)
                <span class="badge bg-primary">{{ $status }}</span>
            @endforeach
        </div>
    </div>
    @endif
    
    @if(!empty($metaValues['temperature']))
    <div class="form-col">
        <label>Temperature</label>
        <div class="readonly-field">
            @foreach($metaValues['temperature'] as $temp)
                <span class="badge bg-info">{{ $temp }}°C</span>
            @endforeach
        </div>
    </div>
    @endif
    
    @if(!empty($metaValues['pulse']))
    <div class="form-col">
        <label>Pulse</label>
        <div class="readonly-field">
            @foreach($metaValues['pulse'] as $pulse)
                <span class="badge bg-success">{{ $pulse }}</span>
            @endforeach
        </div>
    </div>
    @endif
    
    @if(!empty($metaValues['blood_pressure']))
    <div class="form-col">
        <label>Blood Pressure</label>
        <div class="readonly-field">
            @foreach($metaValues['blood_pressure'] as $bp)
                <span class="badge bg-warning">{{ $bp }}</span>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endif

<!-- Diagnosis -->
@if(!empty($metaValues['diagnosis']))
<div class="section-divider">Diagnosis</div>
<div class="form-row">
    <div class="form-col-2">
        <div class="readonly-textarea">
            @foreach($metaValues['diagnosis'] as $diagnosis)
                {{ $diagnosis }}<br>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Treatments -->
@php $hasTreatments = false; @endphp
@foreach($treatmentsByType as $type => $rows)
    @if(count($rows) > 0)
        @php $hasTreatments = true; @endphp
    @endif
@endforeach

@if($hasTreatments)
<div class="section-divider">Treatments</div>
@foreach($treatmentsByType as $type => $rows)
    @if(count($rows) > 0)
    <div class="mb-3">
        <h6 class="text-primary">{{ ucfirst($type) }} Treatment</h6>
        @foreach($rows as $row)
        <div class="medicine-row mb-2 p-2 bg-light rounded">
            <div><strong>Medicine:</strong> {{ $row['medicine'] }}</div>
            @if($row['dose'])
                <div><strong>Dose:</strong> {{ $row['dose'] }}</div>
            @endif
            @if($row['timing'])
                <div><strong>Timing:</strong> {{ $row['timing'] }}</div>
            @endif
            @if($row['note'])
                <div><strong>Note:</strong> {{ $row['note'] }}</div>
            @endif
        </div>
        @endforeach
    </div>
    @endif
@endforeach
@endif

<!-- Next Follow-up -->
@if($followup->next_follow_date)
<div class="section-divider">Next Appointment</div>
<div class="form-row">
    <div class="form-col">
        <label>Next Follow-up Date</label>
        <div class="readonly-field">
            {{ \Carbon\Carbon::parse($followup->next_follow_date)->format('M d, Y') }}
        </div>
    </div>
</div>
@endif