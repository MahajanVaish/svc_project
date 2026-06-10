@extends('admin.layouts.layouts')

@section('title', 'Appointments')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0" style="color: var(--accent-solid);"><i class="fas fa-calendar-alt me-2"></i>Appointments</h2>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addAppointmentModal">
                <i class="fas fa-plus me-1"></i> New Appointment
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Patient Name</th>
                            <th>Date & Time</th>
                            <th>Branch</th>
                            <th>Notes</th>
                            <th>Status</th>
                            <th class="pe-4 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $index => $apt)
                        <tr>
                            <td class="ps-4">{{ $appointments->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $apt->patient_name }}</div>
                                @if($apt->phone)
                                <div class="text-muted small"><i class="fas fa-phone-alt me-1" style="font-size: 10px;"></i>{{ $apt->phone }}</div>
                                @endif
                                <div class="text-muted small text-xs opacity-75">{{ $apt->patient_id ?? 'No ID' }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center mb-1">
                                    <span class="badge bg-light text-primary border border-primary-subtle me-2">
                                        <i class="fas fa-calendar-day me-1"></i>{{ $apt->appointment_date->format('d M, Y') }}
                                    </span>
                                </div>
                                <div class="text-muted small ps-1">
                                    <i class="fas fa-clock me-1"></i>{{ $apt->appointment_time }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border">
                                    {{ $apt->branch_id ?? 'SVC' }}
                                </span>
                            </td>
                            <td>
                                <div class="text-muted small" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $apt->content }}">
                                    {{ $apt->content ?: 'No notes' }}
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $apt->status == 'Pending' ? 'bg-warning-subtle text-warning border border-warning-subtle' : 'bg-success-subtle text-success border border-success-subtle' }}" style="font-size: 11px; padding: 4px 10px; border-radius: 20px;">
                                    {{ $apt->status }}
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-light border" onclick="editAppointment({{ $apt->toJson() }})" title="Edit">
                                        <i class="fas fa-edit text-primary"></i>
                                    </button>
                                    <form action="{{ route('appointment.destroy', $apt->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this appointment?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border" title="Delete">
                                            <i class="fas fa-trash text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-calendar-times fa-3x mb-3 opacity-25"></i>
                                    <p>No appointments found.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($appointments->hasPages())
        <div class="card-footer bg-white border-top-0 pt-0 pb-3">
            {{ $appointments->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="addAppointmentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="appointmentForm" action="{{ route('appointment.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="modal-content border-0 shadow" style="border-radius: 15px;">
                <div class="modal-header border-bottom-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" id="modalTitle">New Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <div class="mb-3">
                        <label class="form-label">Patient Name <span class="text-danger">*</span></label>
                        <input type="text" name="patient_name" id="modalPatientName" class="form-control form-control-lg" list="patientList" required placeholder="Type or select patient...">
                        <datalist id="patientList">
                            @foreach($patients as $p)
                            <option value="{{ $p->patient_f_name }} {{ $p->patient_l_name }}" data-id="{{ $p->patient_id }}" data-phone="{{ $p->phone_no }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" id="modalPhone" class="form-control" placeholder="Enter contact number">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Patient ID (Optional) <small class="text-muted">(Leave blank to auto-generate)</small></label>
                        <input type="text" name="patient_id" id="modalPatientId" class="form-control" placeholder="e.g. SVC-00001">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="appointment_date" id="modalDate" class="form-control" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Time <span class="text-danger">*</span></label>
                            <input type="time" name="appointment_time" id="modalTime" class="form-control" required value="{{ date('H:i') }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        @if(auth()->user()->hasRole('Superadmin'))
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Branch <span class="text-danger">*</span></label>
                            <select name="branch_id" id="modalBranch" class="form-select" required>
                                <option value="">Select Branch</option>
                                @foreach($branches as $b)
                                <option value="{{ $b->branch_id }}">{{ $b->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Branch</label>
                            <input type="text" class="form-control bg-light" value="{{ auth()->user()->branch->branch_name ?? auth()->user()->user_branch }}" readonly>
                            <input type="hidden" name="branch_id" value="{{ auth()->user()->user_branch }}">
                        </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="modalStatus" class="form-select">
                                <option value="Pending">Pending</option>
                                <option value="Completed">Completed</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Notes / Content</label>
                        <textarea name="content" id="modalContent" class="form-control" rows="3" placeholder="Add any specific instructions or notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" id="submitBtn">Save Appointment</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function editAppointment(apt) {
        const modal = new bootstrap.Modal(document.getElementById('addAppointmentModal'));
        const form = document.getElementById('appointmentForm');
        
        document.getElementById('modalTitle').innerText = 'Edit Appointment';
        document.getElementById('submitBtn').innerText = 'Update Appointment';
        document.getElementById('formMethod').value = 'PUT';
        form.action = `/admin/appointment/${apt.id}`;
        
        document.getElementById('modalPatientName').value = apt.patient_name;
        document.getElementById('modalPatientId').value = apt.patient_id;
        document.getElementById('modalPhone').value = apt.phone || '';
        document.getElementById('modalDate').value = apt.appointment_date.split('T')[0];
        document.getElementById('modalTime').value = apt.appointment_time;
        if(document.getElementById('modalBranch')) document.getElementById('modalBranch').value = apt.branch_id;
        document.getElementById('modalStatus').value = apt.status;
        document.getElementById('modalContent').value = apt.content;
        
        modal.show();
    }

    // Reset modal on close
    document.getElementById('addAppointmentModal').addEventListener('hidden.bs.modal', function () {
        const form = document.getElementById('appointmentForm');
        document.getElementById('modalTitle').innerText = 'New Appointment';
        document.getElementById('submitBtn').innerText = 'Save Appointment';
        document.getElementById('formMethod').value = 'POST';
        form.action = "{{ route('appointment.store') }}";
        form.reset();
    });

    // Auto-fill ID and Phone from datalist
    document.getElementById('modalPatientName').addEventListener('input', function(e) {
        const val = this.value;
        const options = document.getElementById('patientList').options;
        for (let i = 0; i < options.length; i++) {
            if (options[i].value === val) {
                document.getElementById('modalPatientId').value = options[i].getAttribute('data-id');
                document.getElementById('modalPhone').value = options[i].getAttribute('data-phone');
                break;
            }
        }
    });
</script>

<style>
    :root {
        --accent-solid: #086838;
    }
    .btn-primary {
        background-color: var(--accent-solid);
        border-color: var(--accent-solid);
    }
    .btn-primary:hover {
        background-color: #06502b;
        border-color: #06502b;
    }
    .text-primary {
        color: var(--accent-solid) !important;
    }
    .bg-primary-subtle {
        background-color: rgba(8, 104, 56, 0.1) !important;
    }
    .border-primary-subtle {
        border-color: rgba(8, 104, 56, 0.2) !important;
    }
</style>
@endsection
