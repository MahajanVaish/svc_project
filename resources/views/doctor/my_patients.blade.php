@extends('admin.layouts.layouts')

@section('title', 'My Patients')

@section('content')
<div style="font-size: 20px; font-weight: bold; color: #006637; margin-bottom: 15px;">My Patients</div>
<div class="card" style="border: 1px solid var(--border-subtle)">
    <div class="card-header d-flex justify-content-between align-items-center">
        <!-- <h5 class="mb-0"><i class="fas fa-users me-2"></i>My Patients</h5> -->
        <form method="get" action="{{ route('doctor.my-patients') }}" class="d-flex gap-2">
            <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Search by name or ID" class="form-control" style="width:240px">
            <button type="submit" class="btn btn-success"><i class="fas fa-search"></i></button>
        </form>
    </div>
    <div class="card-body p-0">
        @if($patients->count())
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $p)
                            <tr>
                                <td><span class="badge bg-success">{{ $p->patient_id }}</span></td>
                                <td>{{ $p->patient_name }}</td>
                                <td>{{ $p->address ?: 'Not provided' }}</td>
                                <td>
                                    <a href="{{ route('svc.profile', $p->id) }}" class="btn btn-outline-success btn-sm" title="View Profile">
                                        <i class="fas fa-user"></i>
                                    </a>
                                    <a href="{{ route('add.follow.up', $p->patient_id) }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-plus"></i> Add Follow-Up
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-3 text-center text-muted">No patients assigned yet.</div>
        @endif
    </div>
    @if(method_exists($patients, 'links'))
        <div class="card-footer">
            {{ $patients->links() }}
        </div>
    @endif
</div>
@endsection
