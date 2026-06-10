@extends('admin.layouts.layouts')

@section('title', 'Meeting History')

@section('content')
<div style="font-size: 20px; font-weight: bold; color: #006637; margin-bottom: 15px;">Meeting History</div>
<div class="card" style="border: 1px solid var(--border-subtle)">
    <div class="card-header d-flex justify-content-between align-items-center">
        <!-- <h5 class="mb-0"><i class="fas fa-video me-2"></i>Meeting History</h5> -->
        <form method="get" action="{{ route('doctor.meeting-history') }}" class="d-flex gap-2">
            <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Search by patient or meeting ID" class="form-control" style="width:280px">
            <button type="submit" class="btn btn-success"><i class="fas fa-search"></i></button>
        </form>
    </div>
    <div class="card-body p-0">
        @if(session('success'))
            <div class="alert alert-success m-3">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger m-3">
                <ul class="mb-0">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if($meetings->count())
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Meeting ID</th>
                            <th>Patient ID</th>
                            <th>Patient Name</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($meetings as $m)
                            @php
                                $dateStr = $m->followup_date instanceof \Carbon\Carbon ? $m->followup_date->format('Y-m-d') : ($m->followup_date ?: null);
                                $timeStr = $m->followups_time ?? '00:00:00';
                                $start = $dateStr ? \Carbon\Carbon::parse($dateStr.' '.$timeStr) : null;
                                $end = $start ? (clone $start)->addMinutes(30) : null;
                                $endMeta = optional($m->metas->firstWhere('meta_key','followups_end_time'))->meta_value;
                                if ($endMeta) { $end = \Carbon\Carbon::parse(($dateStr ?: now()->format('Y-m-d')).' '.$endMeta); }
                                $formId = 'update-form-'.$m->id;
                            @endphp
                            <tr>
                                <td><span class="badge bg-success">{{ $m->zoom_meeting_id }}</span></td>
                                <td>{{ optional($m->inquiry)->patient_id }}</td>
                                <td>{{ optional($m->inquiry)->patient_name }}</td>
                                <td>
                                    <input type="date" name="date" value="{{ $start ? $start->format('Y-m-d') : '' }}" form="{{ $formId }}" class="form-control form-control-sm" style="min-width:140px">
                                </td>
                                <td>
                                    <input type="time" name="start_time" value="{{ $start ? $start->format('H:i') : '' }}" step="60" form="{{ $formId }}" class="form-control form-control-sm" style="min-width:110px">
                                </td>
                                <td>
                                    <input type="time" name="end_time" value="{{ $end ? $end->format('H:i') : '' }}" step="60" form="{{ $formId }}" class="form-control form-control-sm" style="min-width:110px">
                                </td>
                                <td>
                                    <form id="{{ $formId }}" method="POST" action="{{ route('doctor.meeting.update', $m->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Save</button>
                                    </form>
                                    <form method="POST" action="{{ route('doctor.meeting.delete', $m->id) }}" class="d-inline" onsubmit="return confirm('Remove this meeting from history?');">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-3 text-center text-muted">No meetings found.</div>
        @endif
    </div>
    @if(method_exists($meetings, 'links'))
        <div class="card-footer">
            {{ $meetings->links() }}
        </div>
    @endif
</div>
@endsection

