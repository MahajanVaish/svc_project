@extends('admin.layouts.layouts')
@section('title', 'Record Call Log')
@section('content')
<div class="container px-0">
    <div class="card shadow-sm mt-3">
        <div class="card-header bg-light p-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold" style="color: #197040">
                    <i class="fas fa-phone-alt me-2"></i> Record Call Log
                </h5>
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <div class="row g-3 mb-4">
                <div class="col-md-12">
                    <div class="p-3 border rounded-3 bg-light">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <i class="fas fa-user-circle fa-3x text-secondary"></i>
                            </div>
                            <div class="col">
                                <h6 class="mb-1 fw-bold">{{ $inquiry->patient_name ?? trim(($inquiry->patient_f_name ?? '') . ' ' . ($inquiry->patient_l_name ?? '')) }}</h6>
                                <p class="mb-0 text-muted small">
                                    <span class="me-3"><strong>ID:</strong> {{ $inquiry->patient_id }}</span>
                                    <span class="me-3"><strong>Phone:</strong> {{ $inquiry->phone_no ?? 'N/A' }}</span>
                                    <span><strong>Branch:</strong> {{ $inquiry->branch->branch_name ?? $inquiry->branch_id }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('call.log.store') }}" method="POST">
                @csrf
                <input type="hidden" name="inquiry_id" value="{{ $inquiry->id }}">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted text-uppercase">Branch</label>
                        <select name="branch" class="form-select" required>
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->branch_name }}" 
                                    {{ (isset($inquiry->branch) && $inquiry->branch == $branch->branch_name) || ($inquiry->branch_id == $branch->branch_id) || (auth()->user()->user_branch == $branch->id) ? 'selected' : '' }}>
                                    {{ $branch->branch_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted text-uppercase">Call Date</label>
                        <input type="date" name="call_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted text-uppercase">Call Time</label>
                        <input type="time" name="call_time" class="form-control" value="{{ date('H:i') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted text-uppercase">Time Slot</label>
                        <select name="time_slot" class="form-select" required>
                            <option value="" selected disabled>Select Slot</option>
                            @foreach($timeSlots as $slot)
                                <option value="{{ $slot }}">{{ $slot }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted text-uppercase">Remarks / Discussion Summary</label>
                    <textarea name="remarks" class="form-control" rows="4" placeholder="Enter call details here..."></textarea>
                </div>



                <div class="text-end">
                    <button type="submit" class="btn btn-success px-4" style="background-color: #198754;">
                        <i class="fas fa-save me-1"></i> Save Call Log
                    </button>
                </div>
            </form>
        </div>
        
        <!-- @php
            $recentLogs = \App\Models\CallLog::where('inquiry_id', $inquiry->id)->orderBy('created_at', 'desc')->take(3)->get();
        @endphp
        @if($recentLogs->count() > 0)
        <div class="card-footer bg-light p-3">
            <h6 class="fw-bold small text-muted text-uppercase mb-3">Recent Call History</h6>
            @foreach($recentLogs as $log)
            <div class="mb-2 p-2 bg-white border rounded">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="badge bg-light text-dark border">{{ $log->time_slot }}</span>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($log->call_date)->format('d M, Y') }}</small>
                </div>
                <p class="mb-0 small text-dark">{{ Str::limit($log->remarks, 150) }}</p>
            </div>
            @endforeach
        </div> -->
        @endif
    </div>
</div>

<style>
    .form-label {
        font-size: 13px;
        letter-spacing: 0.5px;
    }
    .form-control:focus, .form-select:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
    }
</style>
@endsection
