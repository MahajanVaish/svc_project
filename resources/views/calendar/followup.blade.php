@extends('admin.layouts.layouts')

@section('title', 'Follow Up Calendar')

@section('content')
@php
    // Ensure variables are defined
    $currentCarbon = $currentCarbon ?? now();
    $view = $view ?? 'month';
    $currentDate = $currentDate ?? date('Y-m-d');
    $currentMonth = $currentMonth ?? $currentCarbon->month;
    $currentYear = $currentYear ?? $currentCarbon->year;
    $followUps = $followUps ?? collect();
    $eventsByDate = $eventsByDate ?? collect();
    $today = \Carbon\Carbon::today();
@endphp

<div class="container-fluid follow-up-calendar">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 600; color: rgb(25, 112, 64);">Follow Up</h1>
        </div>
    </div>

    <!-- Calendar Controls -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body py-3">
                    <div class="d-flex flex-column flex-md-row flex-wrap gap-2 justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: #2c3e50; font-family: 'Poppins', sans-serif; font-weight: 500;">
                            {{ $currentCarbon->format('F Y') }}
                        </h5>
                        <div class="d-flex">
                            <a href="{{ route('followup.calendar', ['view' => 'day', 'date' => $currentDate]) }}"
                                class="btn btn-xl {{ $view == 'day' ? 'btn-success' : 'btn-outline-success' }}"
                                style="border-radius: 0px !important; font-family: 'Poppins', sans-serif; font-weight: 500;">
                                Day
                            </a>
                            <a href="{{ route('followup.calendar', ['view' => 'week', 'date' => $currentDate]) }}"
                                class="btn btn-xl {{ $view == 'week' ? 'btn-success' : 'btn-outline-success' }}"
                                style="border-radius: 0px !important; font-family: 'Poppins', sans-serif; font-weight: 500;">
                                Week
                            </a>
                            <a href="{{ route('followup.calendar', ['view' => 'month', 'date' => $currentDate]) }}"
                                class="btn btn-xl {{ $view == 'month' ? 'btn-success' : 'btn-outline-success' }}"
                                style="border-radius: 0px !important; font-family: 'Poppins', sans-serif; font-weight: 500;">
                                Month
                            </a>
                        </div>
                        <div class="d-flex gap-2">
                            @if($view == 'day')
                            <a href="{{ route('followup.calendar', ['view' => 'day', 'date' => $currentCarbon->copy()->subDay()->format('Y-m-d')]) }}"
                                class="btn btn-sm btn-outline-success"
                                style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                                <i class="fas fa-chevron-left"></i> Previous Day
                            </a>
                            <a href="{{ route('followup.calendar', ['view' => 'day', 'date' => \Carbon\Carbon::today()->format('Y-m-d')]) }}"
                                class="btn btn-sm btn-success"
                                style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                                <i class="fas fa-calendar-day"></i> Today
                            </a>
                            <a href="{{ route('followup.calendar', ['view' => 'day', 'date' => $currentCarbon->copy()->addDay()->format('Y-m-d')]) }}"
                                class="btn btn-sm btn-outline-success"
                                style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                                Next Day <i class="fas fa-chevron-right"></i>
                            </a>
                            @elseif($view == 'week')
                            <a href="{{ route('followup.calendar', ['view' => 'week', 'date' => $currentCarbon->copy()->subWeek()->format('Y-m-d')]) }}"
                                class="btn btn-sm btn-outline-success"
                                style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                                <i class="fas fa-chevron-left"></i> Previous Week
                            </a>
                            <a href="{{ route('followup.calendar', ['view' => 'week', 'date' => \Carbon\Carbon::today()->format('Y-m-d')]) }}"
                                class="btn btn-sm btn-success"
                                style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                                <i class="fas fa-calendar-day"></i> Today
                            </a>
                            <a href="{{ route('followup.calendar', ['view' => 'week', 'date' => $currentCarbon->copy()->addWeek()->format('Y-m-d')]) }}"
                                class="btn btn-sm btn-outline-success"
                                style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                                Next Week <i class="fas fa-chevron-right"></i>
                            </a>
                            @else
                            <a href="{{ route('followup.calendar', ['view' => 'month', 'date' => $currentCarbon->copy()->subMonth()->format('Y-m-d')]) }}"
                                class="btn btn-sm btn-outline-success"
                                style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                                <i class="fas fa-chevron-left"></i> Previous Month
                            </a>
                            <a href="{{ route('followup.calendar', ['view' => 'month', 'date' => \Carbon\Carbon::today()->format('Y-m-d')]) }}"
                                class="btn btn-sm btn-success"
                                style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                                <i class="fas fa-calendar-day"></i> Today
                            </a>
                            <a href="{{ route('followup.calendar', ['view' => 'month', 'date' => $currentCarbon->copy()->addMonth()->format('Y-m-d')]) }}"
                                class="btn btn-sm btn-outline-success"
                                style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                                Next Month <i class="fas fa-chevron-right"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Views -->
    @if($view == 'day')
    <!-- DAY VIEW -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="row">
                        <!-- Time Column -->
                        <div class="col-auto day-view-time border-end" style="min-width: 70px; padding: 0;">
                            <div class="text-center py-3 border-bottom fw-bold"
                                style="background-color: rgb(25, 112, 64);;color: white; font-family: 'Poppins', sans-serif;">
                                Time
                            </div>
                            @for($hour = 0; $hour < 24; $hour++) 
                                <div class="time-slot text-center py-3 border-bottom" style="font-family: 'Poppins', sans-serif;">
                                    {{ sprintf('%02d:00', $hour) }}
                                </div>
                            @endfor
                        </div>

                        <!-- Day Column -->
                        <div class="col day-view-content" style="padding: 0;">
                            <div class="text-center py-3 border-bottom fw-bold"
                                style="background-color: rgb(25, 112, 64);; color: white; font-family: 'Poppins', sans-serif;">
                                {{ $currentCarbon->format('l, F j, Y') }}
                            </div>
                            @for($hour = 0; $hour < 24; $hour++) 
                                @php 
                                    // Get events for this hour (BOTH inquiries and follow-ups)
                                    $hourEvents = $followUps->filter(function($item) use ($hour, $currentCarbon) {
                                        if (!$item['event_date']) return false;
                                        $eventDate = \Carbon\Carbon::parse($item['event_date']);
                                        return $eventDate->hour == $hour &&
                                               $eventDate->format('Y-m-d') == $currentCarbon->format('Y-m-d');
                                    });

                                    // Separate past and upcoming events
                                    $pastHourEvents = $hourEvents->filter(function($item) {
                                        return $item['is_past']; // Past events
                                    });

                                    $upcomingHourEvents = $hourEvents->filter(function($item) {
                                        return !$item['is_past']; // Upcoming events
                                    });
                                @endphp
                                <div class="time-slot py-3 border-bottom" style="height: 60px;">
                                    <!-- Show Past Events (DISABLED) -->
                                    @foreach($pastHourEvents as $event)
                                        @php
                                            $bgColor = $event['type'] == 'inquiry' ? '#f0f8ff' : '#f8f9fa';
                                            $borderColor = $event['type'] == 'inquiry' ? '#95a5a6' : '#6c757d';
                                            $icon = $event['type'] == 'inquiry' ? '' : '';
                                        @endphp
                                        <div class="follow-up-item p-2 mb-1 rounded"
                                            style="background-color: {{ $bgColor }}; border-left: 3px solid {{ $borderColor }};
                                            font-size: 0.8rem; font-family: 'Poppins', sans-serif; opacity: 0.6; cursor: not-allowed;"
                                            title="Past {{ ucfirst($event['type']) }} - Date has passed">
                                            <div class="fw-bold text-muted">
                                                {{ \Carbon\Carbon::parse($event['event_date'])->format('H:i') }} - 
                                                {{ $event['patient_name'] ?? 'Unknown' }}
                                            </div>
                                            <div class="text-muted small">
                                                {{ $icon }} {{ ucfirst($event['type']) }} - Past
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Show Active Events (With Hover) -->
                                    @foreach($upcomingHourEvents as $event)
                                        @php
                                            $bgColor = $event['type'] == 'inquiry' ? '#e6f2ff' : '#e8f5e8';
                                            $borderColor = $event['type'] == 'inquiry' ? '#3498db' : 'rgb(25, 112, 64)';
                                            $icon = $event['type'] == 'inquiry' ? '' : '🔄';
                                        @endphp
                                        <div class="follow-up-item p-2 mb-1 rounded inquiry-hover-trigger"
                                            data-inquiry-id="{{ $event['id'] }}"
                                            style="background-color: {{ $bgColor }}; border-left: 3px solid {{ $borderColor }};
                                            font-size: 0.8rem; cursor: pointer; font-family: 'Poppins', sans-serif;">
                                            <div class="fw-bold">
                                                {{ \Carbon\Carbon::parse($event['event_date'])->format('H:i') }} - 
                                                {{ $event['patient_name'] ?? 'Unknown' }}
                                            </div>
                                            <div class="text-muted small">
                                                {{ $icon }} {{ ucfirst($event['type']) }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @elseif($view == 'week')
    <!-- WEEK VIEW -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="row">
                        <!-- Time Column -->
                        <div class="col-auto border-end" style="min-width: 80px; padding: 0;">
                            <div class="text-center py-3 border-bottom fw-bold"
                                style="background-color: rgb(25, 112, 64); color: white; height: 81px; min-width: 80px; text-align: center; display: flex; justify-content: center; align-items: center; font-family: 'Poppins', sans-serif;">
                                Time
                            </div>
                            @for($hour = 0; $hour < 24; $hour++) 
                                <div class="time-slot text-center py-3 border-bottom" style="font-family: 'Poppins', sans-serif;">
                                    {{ sprintf('%02d:00', $hour) }}
                                </div>
                            @endfor
                        </div>

                        <!-- Days Columns -->
                        @php
                            $startOfWeek = $currentCarbon->copy()->startOfWeek();
                            $weekDays = [];
                            for($i = 0; $i < 7; $i++) {
                                $weekDays[] = $startOfWeek->copy()->addDays($i);
                            }
                        @endphp

                        @foreach($weekDays as $day)
                            <div class="col" style="min-width: 140px; padding: 0;">
                                <div class="text-center py-3 border-bottom fw-bold"
                                    style="background-color: rgb(25, 112, 64);; color: white; font-family: 'Poppins', sans-serif;">
                                    <div>{{ $day->format('D') }}</div>
                                    <div>{{ $day->format('j') }}</div>
                                </div>
                                @for($hour = 0; $hour < 24; $hour++)
                                    @php
                                        $hourEvents = $followUps->filter(function($item) use ($day, $hour) {
                                            if (!$item['event_date']) return false;
                                            $eventDate = \Carbon\Carbon::parse($item['event_date']);
                                            return $eventDate->format('Y-m-d') == $day->format('Y-m-d') &&
                                                   $eventDate->hour == $hour;
                                        });

                                        $pastHourEvents = $hourEvents->filter(function($item) {
                                            return $item['is_past'];
                                        });

                                        $upcomingHourEvents = $hourEvents->filter(function($item) {
                                            return !$item['is_past'];
                                        });
                                    @endphp
                                    <div class="time-slot border-bottom" style="height: 60px; position: relative;">
                                        <!-- Show Past Events (Disabled) -->
                                        @foreach($pastHourEvents as $event)
                                            @php
                                                $bgColor = $event['type'] == 'inquiry' ? '#f0f8ff' : '#f8f9fa';
                                                $borderColor = $event['type'] == 'inquiry' ? '#95a5a6' : '#6c757d';
                                            @endphp
                                            <div class="follow-up-item p-1 m-1 rounded"
                                                style="background-color: {{ $bgColor }}; border-left: 3px solid {{ $borderColor }};
                                                font-size: 0.7rem; font-family: 'Poppins', sans-serif; opacity: 0.6; cursor: not-allowed;"
                                                title="Past {{ ucfirst($event['type']) }} - Date has passed">
                                                <div class="fw-bold text-truncate text-muted">
                                                    {{ \Carbon\Carbon::parse($event['event_date'])->format('H:i') }}
                                                </div>
                                                <div class="text-truncate small text-muted">
                                                    {{ $event['patient_name'] ?? 'Unknown' }}
                                                </div>
                                            </div>
                                        @endforeach

                                        <!-- Show Active Events (With Hover) -->
                                        @foreach($upcomingHourEvents as $event)
                                            @php
                                                $bgColor = $event['type'] == 'inquiry' ? '#e6f2ff' : '#e8f5e8';
                                                $borderColor = $event['type'] == 'inquiry' ? '#3498db' : 'rgb(25, 112, 64)';
                                                $icon = $event['type'] == 'inquiry' ? '' : '🔄';
                                            @endphp
                                            <div class="follow-up-item p-1 m-1 rounded inquiry-hover-trigger"
                                                data-inquiry-id="{{ $event['id'] }}"
                                                style="background-color: {{ $bgColor }}; border-left: 3px solid {{ $borderColor }};
                                                font-size: 0.7rem; cursor: pointer; font-family: 'Poppins', sans-serif;">
                                                <div class="fw-bold text-truncate">
                                                    {{ \Carbon\Carbon::parse($event['event_date'])->format('H:i') }}
                                                </div>
                                                <div class="text-truncate small">
                                                    {{ $icon }} {{ $event['patient_name'] ?? 'Unknown' }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endfor
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else
    <!-- MONTH VIEW -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 month-view-table" style="table-layout: fixed;">
                            <thead>
                                <tr style="background-color: rgb(25, 112, 64);; color: white;border-right: none">
                                    <th class="text-center py-3" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Sun</th>
                                    <th class="text-center py-3" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Mon</th>
                                    <th class="text-center py-3" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Tue</th>
                                    <th class="text-center py-3" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Wed</th>
                                    <th class="text-center py-3" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Thu</th>
                                    <th class="text-center py-3" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Fri</th>
                                    <th class="text-center py-3" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Sat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $firstDay = $currentCarbon->copy()->startOfMonth();
                                    $startDay = $firstDay->copy()->startOfWeek();
                                    $endDay = $firstDay->copy()->endOfMonth()->endOfWeek();
                                    $currentDateLoop = $startDay->copy();
                                @endphp

                                @while($currentDateLoop <= $endDay)
                                    <tr>
                                        @for($i = 0; $i < 7; $i++)
                                            @php
                                                $isCurrentMonth = $currentDateLoop->month == $currentMonth;
                                                $isToday = $currentDateLoop->isToday();
                                                $isPastDate = $currentDateLoop->lt($today);

                                                $dayEvents = $followUps->filter(function($item) use ($currentDateLoop) {
                                                    if (!$item['event_date']) return false;
                                                    $eventDate = \Carbon\Carbon::parse($item['event_date']);
                                                    return $eventDate->format('Y-m-d') == $currentDateLoop->format('Y-m-d');
                                                });

                                                $pastDayEvents = $dayEvents->filter(function($item) {
                                                    return $item['is_past'];
                                                });

                                                $upcomingDayEvents = $dayEvents->filter(function($item) {
                                                    return !$item['is_past'];
                                                });

                                                // Count events by type
                                                $inquiryCount = $dayEvents->where('type', 'inquiry')->count();
                                                $followupCount = $dayEvents->where('type', 'followup')->count();
                                                $totalEvents = $dayEvents->count();
                                            @endphp
                                            <td class="p-2" style="height: 120px; vertical-align: top; font-family: 'Poppins', sans-serif;
                                                {{ !$isCurrentMonth ? 'background-color: #f8f9fa; color: #6c757d;' : '' }}
                                                {{ $isToday ? 'background-color: #e3f2fd;' : '' }}
                                                {{ $isPastDate && $isCurrentMonth ? 'opacity: 0.7;' : '' }}">

                                                <!-- Date Number -->
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <span class="{{ $isToday ? 'badge bg-success' : '' }} {{ $isPastDate ? 'text-muted' : '' }}"
                                                        style="font-size: 0.9rem; font-family: 'Poppins', sans-serif;">
                                                        {{ $currentDateLoop->day }}
                                                    </span>
                                                    @if($isCurrentMonth && $totalEvents > 0)
                                                        <div class="d-flex gap-1">
                                                            @if($inquiryCount > 0)
                                                                <span class="badge {{ $isPastDate ? 'bg-secondary' : 'bg-primary' }}"
                                                                    style="font-size: 0.6rem; font-family: 'Poppins', sans-serif;"
                                                                    title="{{ $inquiryCount }} Inquiry(s)">
                                                                    {{ $inquiryCount }}
                                                                </span>
                                                            @endif
                                                            @if($followupCount > 0)
                                                                <span class="badge {{ $isPastDate || $pastDayEvents->where('type', 'followup')->count() > 0 ? 'bg-secondary' : 'bg-success' }}"
                                                                    style="font-size: 0.6rem; font-family: 'Poppins', sans-serif;"
                                                                    title="{{ $followupCount }} Follow-up(s)">
                                                                    {{ $followupCount }}🔄
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Events Items -->
                                                <div class="follow-up-items" style="max-height: 80px; overflow-y: auto;">
                                                    <!-- Past Events (Disabled) -->
                                                    @foreach($pastDayEvents as $event)
                                                        @php
                                                            $bgColor = $event['type'] == 'inquiry' ? '#f0f8ff' : '#f8f9fa';
                                                            $borderColor = $event['type'] == 'inquiry' ? '#95a5a6' : '#6c757d';
                                                        @endphp
                                                        <div class="follow-up-item mb-1 p-1 rounded"
                                                            style="background-color: {{ $bgColor }}; border-left: 3px solid {{ $borderColor }};
                                                            font-size: 0.75rem; font-family: 'Poppins', sans-serif; opacity: 0.6; cursor: not-allowed;"
                                                            title="Past {{ ucfirst($event['type']) }} - Date has passed">
                                                            <div class="fw-bold text-truncate text-muted" style="font-size: 0.7rem;">
                                                                {{ $event['type'] == 'inquiry' ? '' : '' }} {{ $event['patient_name'] ?? 'Unknown' }}
                                                            </div>
                                                            <div class="text-muted text-truncate" style="font-size: 0.65rem;">
                                                                Past {{ ucfirst($event['type']) }}
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                    <!-- Active Events (With Hover) -->
                                                    @foreach($upcomingDayEvents as $event)
                                                        @php
                                                            $bgColor = $event['type'] == 'inquiry' ? '#e6f2ff' : '#e8f5e8';
                                                            $borderColor = $event['type'] == 'inquiry' ? '#3498db' : 'rgb(25, 112, 64)';
                                                            $icon = $event['type'] == 'inquiry' ? '' : '🔄';
                                                        @endphp
                                                        <div class="follow-up-item mb-1 p-1 rounded inquiry-hover-trigger"
                                                            data-inquiry-id="{{ $event['id'] }}"
                                                            style="background-color: {{ $bgColor }}; border-left: 3px solid {{ $borderColor }};
                                                            font-size: 0.75rem; cursor: pointer; font-family: 'Poppins', sans-serif;">
                                                            <div class="fw-bold text-truncate" style="font-size: 0.7rem;">
                                                                {{ $icon }} {{ $event['patient_name'] ?? 'Unknown' }}
                                                            </div>
                                                            <div class="text-muted text-truncate" style="font-size: 0.65rem;">
                                                                {{ ucfirst($event['type']) }}
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            @php
                                                $currentDateLoop->addDay();
                                            @endphp
                                        @endfor
                                    </tr>
                                @endwhile
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Simple Hover Dialog -->
<div id="inquiryHoverDialog" class="inquiry-dialog" style="display: none;">
    <div class="inquiry-dialog-content">
        <div class="inquiry-dialog-body">
            <div id="inquiryDetailsContent">
                <!-- Patient details will be loaded here -->
            </div>
            <div class="inquiry-dialog-footer">
                <button class="btn btn-success btn-sm view-profile-btn"
                    style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                    <i class="fas fa-eye"></i> View Profile
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Import Poppins font */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    /* Apply Poppins to entire page */
    body {
        font-family: 'Poppins', sans-serif !important;
    }

    .follow-up-calendar {
        background: white;
        min-height: 100vh;
        font-family: 'Poppins', sans-serif !important;
    }

    .row>* {
        padding-right: 0px !important;
        padding-left: 0px !important;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6 !important;
    }

    .follow-up-item:hover {
        transform: translateX(2px);
        transition: all 0.2s ease;
    }

    .badge {
        font-weight: 500;
        font-family: 'Poppins', sans-serif !important;
    }

    .table {
        margin-bottom: 0;
    }

    .table-bordered thead th {
        border: none !important;
    }

    .card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        font-family: 'Poppins', sans-serif !important;
    }

    .btn-outline-success {
        border-color: rgb(25, 112, 64);
        color: rgb(25, 112, 64);
        font-family: 'Poppins', sans-serif !important;
    }

    .btn-outline-success:hover {
        background-color: rgb(25, 112, 64);
        border-color: rgb(25, 112, 64);
        color: white;
    }

    .follow-up-items::-webkit-scrollbar {
        width: 4px;
    }

    .follow-up-items::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 2px;
    }

    .follow-up-items::-webkit-scrollbar-thumb {
        background: rgb(25, 112, 64);
        border-radius: 2px;
    }

    .time-slot {
        border-right: 1px solid #dee2e6;
        font-family: 'Poppins', sans-serif !important;
    }

    .btn:focus,
    .btn:active,
    .btn:focus-visible {
        outline: none !important;
        box-shadow: none !important;
        border-color: inherit !important;
    }

    /* Enhanced Hover Dialog Styles */
    .inquiry-dialog {
        position: fixed;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        z-index: 10000;
        min-width: 320px;
        max-width: 380px;
        border: 2px solid rgb(25, 112, 64);
        font-family: 'Poppins', sans-serif !important;
        animation: fadeIn 0.15s ease-out;
    }

    /* Mobile adjustments for the dialog */
    @media (max-width: 576px) {
        .inquiry-dialog {
            position: fixed !important;
            width: calc(100vw - 30px) !important;
            min-width: auto !important;
            max-width: none !important;
            left: 15px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            margin: 0 !important;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-40%) !important;
            }
            to {
                opacity: 1;
                transform: translateY(-50%) !important;
            }
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .inquiry-dialog-content {
        position: relative;
    }

    .inquiry-dialog-body {
        padding: 20px;
        font-family: 'Poppins', sans-serif !important;
    }

    .inquiry-detail-item {
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0f0f0;
        font-family: 'Poppins', sans-serif !important;
    }

    .inquiry-detail-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .inquiry-detail-label {
        font-weight: 600;
        color: #2c3e50;
        font-size: 13px;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-family: 'Poppins', sans-serif !important;
    }

    .inquiry-detail-value {
        color: #555;
        font-size: 14px;
        font-weight: 500;
        word-break: break-word;
        font-family: 'Poppins', sans-serif !important;
    }

    .patient-name {
        font-size: 18px !important;
        font-weight: 700 !important;
        color: rgb(25, 112, 64) !important;
        margin-bottom: 15px !important;
        text-align: center;
        padding: 10px;
        background-color: #f8fff8;
        border-radius: 8px;
        border: 1px solid #e1f5e1;
        font-family: 'Poppins', sans-serif !important;
    }

    .inquiry-dialog-footer {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 2px solid rgb(25, 112, 64);
        text-align: center;
        font-family: 'Poppins', sans-serif !important;
    }

    .view-profile-btn {
        background-color: rgb(25, 112, 64);
        border: none;
        padding: 10px 24px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        width: 100%;
        transition: all 0.2s ease;
        color: white;
        font-family: 'Poppins', sans-serif !important;
    }

    .view-profile-btn:hover {
        background-color: #3d8b2a;
        transform: translateY(-1px);
    }

    .inquiry-dialog-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.1);
        z-index: 9999;
        display: none;
    }

    .follow-up-item {
        font-family: 'Poppins', sans-serif !important;
    }

    .loading-spinner {
        text-align: center;
        padding: 20px;
        font-family: 'Poppins', sans-serif !important;
    }

    .loading-spinner .spinner-border {
        color: rgb(25, 112, 64);
        width: 2rem;
        height: 2rem;
    }

    .loading-spinner p {
        margin-top: 10px;
        color: #6c757d;
        font-family: 'Poppins', sans-serif !important;
    }

    /* Ensure hover items have proper z-index */
    .inquiry-hover-trigger {
        position: relative;
        z-index: 1;
    }

    /* Error message styling */
    .error-message {
        color: #dc3545;
        text-align: center;
        padding: 10px;
        background-color: #f8d7da;
        border-radius: 4px;
        border: 1px solid #f5c6cb;
        font-family: 'Poppins', sans-serif !important;
    }

    /* Status styling for past events */
    .past-event {
        opacity: 0.6;
        cursor: not-allowed !important;
        background-color: #f8f9fa !important;
        border-left: 3px solid #6c757d !important;
    }

    .past-event:hover {
        transform: none !important;
        color: #6c757d;
    }

    /* Responsive Calendar Fixes */
    @media (max-width: 991px) {
        .btn-xl { font-size: 0.8rem; padding: 0.5rem 0.8rem; }
        .follow-up-calendar h1 { font-size: 1.5rem; }
    }

    @media (max-width: 767px) {
        .time-slot { font-size: 0.75rem; min-height: 50px !important; }
        .follow-up-item { font-size: 0.7rem !important; padding: 0.25rem !important; }
        
        /* Enable horizontal scroll for Day and Week views */
        .card-body.p-0 > .row {
            overflow-x: auto;
            flex-wrap: nowrap;
            margin: 0 !important;
            -webkit-overflow-scrolling: touch;
        }

        .month-view-table {
            min-width: 650px; /* Allow month table to scroll instead of crushing */
        }

        .month-view-table thead th { font-size: 0.75rem; padding: 0.5rem 0.2rem !important; }
        .month-view-table td { height: 100px !important; padding: 0.25rem !important; }
        .month-view-table .badge { font-size: 0.55rem !important; padding: 2px 4px; }
    }

    @media (max-width: 576px) {
        .follow-up-calendar .d-flex.gap-2 { 
            flex-wrap: wrap; 
            justify-content: center;
        }
        .follow-up-calendar .d-flex.gap-2 a { 
            flex: 1 1 auto;
            font-size: 0.7rem; 
        }
        .month-view-table {
            min-width: 700px;
        }
        
        .follow-up-calendar h1 {
            font-size: 1.25rem;
            text-align: center;
        }

        .card-body h5 {
            font-size: 1rem;
            margin-bottom: 0.5rem !important;
            text-align: center;
            width: 100%;
        }
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Follow-up calendar loaded');

        // State variables
        let isDialogVisible = false;
        let hoverTimer = null;
        let currentInquiryId = null;
        let currentPatientProfileId = null;

        // Create overlay
        const overlay = document.createElement('div');
        overlay.className = 'inquiry-dialog-overlay';
        document.body.appendChild(overlay);

        const dialog = document.getElementById('inquiryHoverDialog');
        if (!dialog) {
            console.error('Inquiry hover dialog element not found');
            return;
        }

        // Add hover functionality only to upcoming events (not past ones)
        document.querySelectorAll('.inquiry-hover-trigger').forEach(item => {
            console.log('Adding hover to item:', item);

            item.addEventListener('mouseenter', function(e) {
                console.log('Mouse enter on item');
                const inquiryId = this.getAttribute('data-inquiry-id');
                console.log('Inquiry ID:', inquiryId);
                currentInquiryId = inquiryId;

                clearTimeout(hoverTimer);
                hoverTimer = setTimeout(() => {
                    showInquiryDetails(inquiryId, e);
                }, 400);
            });

            item.addEventListener('mouseleave', function(e) {
                console.log('Mouse leave from item');
                clearTimeout(hoverTimer);

                const relatedTarget = e.relatedTarget;
                if (!relatedTarget || !isElementInDialog(relatedTarget)) {
                    startHideTimer();
                }
            });
        });

        // Dialog hover events
        dialog.addEventListener('mouseenter', function() {
            console.log('Mouse enter dialog');
            clearTimeout(hoverTimer);
        });

        dialog.addEventListener('mouseleave', function(e) {
            console.log('Mouse leave dialog');
            const relatedTarget = e.relatedTarget;
            if (!relatedTarget || !isInquiryTrigger(relatedTarget)) {
                startHideTimer();
            }
        });

        // View Profile button event
        document.querySelector('.view-profile-btn').addEventListener('click', function() {
            console.log('View Profile clicked');
            if (currentPatientProfileId) {
                window.location.href = '/svc-profile/' + currentPatientProfileId;
            }
        });

        // Close dialog when clicking on overlay
        overlay.addEventListener('click', function() {
            hideInquiryDialog();
        });

        // Helper functions
        function isElementInDialog(element) {
            return dialog.contains(element) || element === dialog || overlay.contains(element);
        }

        function isInquiryTrigger(element) {
            return element.classList.contains('inquiry-hover-trigger') ||
                   element.closest('.inquiry-hover-trigger');
        }

        function isMouseOverDialog() {
            return dialog.matches(':hover') || overlay.matches(':hover');
        }

        function isMouseOverTrigger() {
            return document.querySelector('.inquiry-hover-trigger:hover') !== null;
        }

        function startHideTimer() {
            clearTimeout(hoverTimer);
            hoverTimer = setTimeout(() => {
                if (!isMouseOverDialog() && !isMouseOverTrigger()) {
                    hideInquiryDialog();
                }
            }, 200);
        }

        function showInquiryDetails(inquiryId, event) {
            console.log('Showing details for:', inquiryId);

            if (isDialogVisible && currentInquiryId === inquiryId) {
                return; // Already showing this inquiry
            }

            isDialogVisible = true;

            // Show loading with better styling
            document.getElementById('inquiryDetailsContent').innerHTML =
                '<div class="loading-spinner">' +
                '<div class="spinner-border" role="status"></div>' +
                '<p class="mt-2">Loading patient details...</p>' +
                '</div>';

            // Position dialog near cursor
            positionDialog(event);

            // Show overlay and dialog
            overlay.style.display = 'block';
            dialog.style.display = 'block';

            // Fetch inquiry details via AJAX with better error handling
            $.ajax({
                url: '/inquiry-details/' + inquiryId,
                type: 'GET',
                timeout: 10000, // 10 second timeout
                success: function(response) {
                    console.log('AJAX response:', response);
                    if (response.success && response.patient) {
                        const patient = response.patient;
                        currentPatientProfileId = patient.id || null;
                        const detailsHtml = `
                            <div class="inquiry-detail-item">
                                <div class="patient-name">${escapeHtml(patient.patient_name || 'N/A')}</div>
                            </div>
                        
                            <div class="inquiry-detail-item">
                                <div class="inquiry-detail-label">Patient ID</div>
                                <div class="inquiry-detail-value">${escapeHtml(patient.patient_id || 'N/A')}</div>
                            </div>
                            <div class="inquiry-detail-item">
                                <div class="inquiry-detail-label">Branch</div>
                                <div class="inquiry-detail-value">${escapeHtml(patient.branch || 'SVC')}</div>
                            </div>
                            <div class="inquiry-detail-item">
                                <div class="inquiry-detail-label">Event Date</div>
                                <div class="inquiry-detail-value">${escapeHtml(patient.event_date || 'N/A')}</div>
                            </div>
                            ${patient.inquiry_date && patient.inquiry_date !== 'N/A' ? `
                            <div class="inquiry-detail-item">
                                <div class="inquiry-detail-label">Inquiry Date</div>
                                <div class="inquiry-detail-value">${escapeHtml(patient.inquiry_date)}</div>
                            </div>
                            ` : ''}
                            ${patient.next_follow_date && patient.next_follow_date !== 'N/A' ? `
                            <div class="inquiry-detail-item">
                                <div class="inquiry-detail-label">Next Follow-up</div>
                                <div class="inquiry-detail-value">${escapeHtml(patient.next_follow_date)}</div>
                            </div>
                            ` : ''}
                            ${patient.mobile && patient.mobile !== 'N/A' ? `
                            <div class="inquiry-detail-item">
                                <div class="inquiry-detail-label">Mobile</div>
                                <div class="inquiry-detail-value">${escapeHtml(patient.mobile)}</div>
                            </div>
                            ` : ''}
                            ${patient.email && patient.email !== 'N/A' ? `
                            <div class="inquiry-detail-item">
                                <div class="inquiry-detail-label">Email</div>
                                <div class="inquiry-detail-value">${escapeHtml(patient.email)}</div>
                            </div>
                            ` : ''}
                        `;
                        document.getElementById('inquiryDetailsContent').innerHTML = detailsHtml;
                    } else {
                        const errorMessage = response.error || 'Patient details not found';
                        document.getElementById('inquiryDetailsContent').innerHTML =
                            '<div class="error-message">Error: ' + escapeHtml(errorMessage) + '</div>';
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    console.log('Status:', status);
                    console.log('XHR:', xhr);

                    let errorMessage = 'Error loading patient details. ';
                    if (status === 'timeout') {
                        errorMessage += 'Request timed out.';
                    } else if (xhr.status === 404) {
                        errorMessage += 'Patient not found.';
                    } else if (xhr.status === 500) {
                        errorMessage += 'Server error.';
                    } else {
                        errorMessage += 'Please try again.';
                    }

                    document.getElementById('inquiryDetailsContent').innerHTML =
                        '<div class="error-message">' + errorMessage + '</div>';
                }
            });
        }

        function positionDialog(event) {
            const windowWidth = window.innerWidth;
            const windowHeight = window.innerHeight;

            // On mobile, use centered fixed positioning (handled mainly by CSS now)
            if (windowWidth <= 576) {
                // The CSS position: fixed with top: 50%, left: 15px handles this
                // We just need to reset any inline styles potentially set previously
                dialog.style.left = '';
                dialog.style.top = '';
                return;
            }

            const x = event.clientX;
            const y = event.clientY;
            
            // Set temporary display to get dimensions
            const wasHidden = dialog.style.display === 'none';
            if (wasHidden) {
                dialog.style.visibility = 'hidden';
                dialog.style.display = 'block';
            }
            
            const dialogWidth = dialog.offsetWidth;
            const dialogHeight = dialog.offsetHeight;
            
            if (wasHidden) {
                dialog.style.display = 'none';
                dialog.style.visibility = 'visible';
            }

            let left = x + 15;
            let top = y + 15;

            // Adjust position if dialog goes beyond window boundaries
            if (left + dialogWidth > windowWidth - 20) {
                left = x - dialogWidth - 15;
            }
            if (top + dialogHeight > windowHeight - 20) {
                top = y - dialogHeight - 15;
            }

            // Ensure dialog stays within viewport with proper margins
            left = Math.max(10, Math.min(left, windowWidth - dialogWidth - 10));
            top = Math.max(10, Math.min(top, windowHeight - dialogHeight - 10));

            dialog.style.position = 'absolute';
            dialog.style.left = left + 'px';
            dialog.style.top = top + 'px';
        }

        function hideInquiryDialog() {
            console.log('Hiding dialog');
            overlay.style.display = 'none';
            dialog.style.display = 'none';
            isDialogVisible = false;
            currentInquiryId = null;
        }

        // Escape HTML to prevent XSS
        function escapeHtml(unsafe) {
            if (unsafe === null || unsafe === undefined) return 'N/A';
            return unsafe
                .toString()
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isDialogVisible) {
                hideInquiryDialog();
            }
        });

        // Prevent dialog from closing when clicking inside it
        dialog.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Close dialog when clicking outside
        document.addEventListener('click', function(e) {
            if (isDialogVisible && !dialog.contains(e.target) && !e.target.closest('.inquiry-hover-trigger')) {
                hideInquiryDialog();
            }
        });
    });
</script>
@endsection