<div class="single-visit-details">
    <div class="visit-time-header">
        <h5 class="mb-0">
            Visit Details - {{ \Carbon\Carbon::parse($followup->followups_time)->format('h:i A') }}
        </h5>
    </div>
    
    <div class="single-visit-container">
        <!-- Same content as the single visit section above -->
        <!-- You can copy the single visit section from the previous partial -->
        <!-- Or keep it simple with just the table view -->
        
        <table class="history-table">
            <tr>
                <td width="30%"><strong>Visit Time:</strong></td>
                <td>{{ \Carbon\Carbon::parse($followup->followups_time)->format('h:i A') }}</td>
            </tr>
            <tr>
                <td><strong>Recorded On:</strong></td>
                <td>{{ \Carbon\Carbon::parse($followup->created_at)->format('M d, Y h:i A') }}</td>
            </tr>
            
            @foreach($metaKeys as $key)
                @php
                    $metaValue = $followup->getMeta($key);
                    $label = str_replace('_', ' ', ucfirst($key));
                @endphp
                @if($metaValue)
                <tr>
                    <td><strong>{{ $label }}:</strong></td>
                    <td>{{ $metaValue }}</td>
                </tr>
                @endif
            @endforeach
            
            @if($followup->next_follow_date)
            <tr>
                <td><strong>Next Follow-up:</strong></td>
                <td>{{ \Carbon\Carbon::parse($followup->next_follow_date)->format('M d, Y') }}</td>
            </tr>
            @endif
        </table>
        
        @if($followup->treatments && $followup->treatments->count() > 0)
        <h6 class="mt-3">Treatments</h6>
        <table class="history-table">
            @foreach($followup->treatments as $treatment)
            <tr>
                <td>{{ ucfirst($treatment->type) }}</td>
                <td>{{ $treatment->medicine }}</td>
                <td>{{ $treatment->dose ?? '-' }}</td>
                <td>{{ $treatment->timing ?? '-' }}</td>
                <td>{{ $treatment->note ?? '-' }}</td>
            </tr>
            @endforeach
        </table>
        @endif
    </div>
</div>