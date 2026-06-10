@extends('admin.layouts.layouts')

@section('title', 'LHR Pending Patients')

@section('content')
    <style>
        :root {
            --hospital-green: #006637;
            --hospital-green-light: #e6f0eb;
            --hospital-blue: #0ea5e9;
            --hospital-gray: #f8fafc;
            --hospital-border: #e2e8f0;
            --hospital-teal: #0d9488;
            --card-bg: #ffffff;
            --card-header-bg: #f8fafc;
            --text-main: #1e293b;
            --text-sub: #475569;
            --text-muted: #64748b;
            --table-row-hover: #e6f0eb;
            --input-bg: #ffffff;
        }

        /* Dark Mode Overrides */
        .dark {
            --hospital-green-light: #064e3b;
            --hospital-gray: #1e293b;
            --hospital-border: #334155;
            --card-bg: #1e293b;
            --card-header-bg: #0f172a;
            --text-main: #f8fafc;
            --text-sub: #cbd5e1;
            --text-muted: #94a3b8;
            --table-row-hover: #0f172a;
            --input-bg: #0f172a;
        }

        .hospital-card {
            background: var(--card-bg);
            border: 1px solid var(--hospital-border);
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
            transition: background 0.3s, border 0.3s;
        }

        .hospital-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--hospital-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--card-header-bg);
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            transition: background 0.3s, border 0.3s;
        }

        .hospital-title {
            color: var(--text-main);
            font-weight: 700;
            font-size: 1.25rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .hospital-title i {
            color: var(--hospital-green);
        }

        .hospital-table thead th {
            background-color: var(--hospital-green);
            color: white !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.025em;
            padding: 12px 16px;
            border: none;
            text-align: center;
        }

        .hospital-table tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            color: var(--text-sub);
            border-bottom: 1px solid var(--hospital-border);
            text-align: center;
            transition: color 0.3s, border 0.3s;
        }

        .hospital-table tbody tr:hover {
            background-color: var(--table-row-hover);
        }

        /* Professional Badges */
        .hosp-badge {
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .hosp-badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .hosp-badge-joined {
            background: #dcfce7;
            color: #166534;
        }

        .hosp-badge-male {
            background: #e0f2fe;
            color: #075985;
        }

        .hosp-badge-female {
            background: #fae8ff;
            color: #86198f;
        }

        .dark .hosp-badge-pending {
            background: rgba(251, 191, 36, 0.1);
            color: #fbbf24;
        }

        .dark .hosp-badge-joined {
            background: rgba(52, 211, 153, 0.1);
            color: #34d399;
        }

        .dark .hosp-badge-male {
            background: rgba(56, 189, 248, 0.1);
            color: #38bdf8;
        }

        .dark .hosp-badge-female {
            background: rgba(232, 121, 249, 0.1);
            color: #e879f9;
        }

        /* Action Buttons */
        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            border: 1px solid transparent;
            text-decoration: none;
        }

        .btn-edit {
            background: var(--hospital-green-light);
            color: var(--hospital-green);
        }

        .btn-edit:hover {
            background: var(--hospital-green);
            color: white;
        }

        .btn-delete {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-delete:hover {
            background: #ef4444;
            color: white;
        }

        .btn-profile {
            background: #e0f2fe;
            color: #0369a1;
        }

        .btn-profile:hover {
            background: #0ea5e9;
            color: white;
        }

        .dark .btn-edit {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .dark .btn-delete {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .dark .btn-profile {
            background: rgba(14, 165, 233, 0.1);
            color: #0ea5e9;
        }

        /* Custom Input Styling */
        .hospital-input {
            background-color: var(--input-bg);
            color: var(--text-main);
            border-radius: 8px;
            border: 1px solid var(--hospital-border);
            padding: 8px 12px;
            font-size: 0.875rem;
            transition: border-color 0.2s, background 0.3s, color 0.3s;
        }

        .hospital-input:focus {
            border-color: var(--hospital-green);
            box-shadow: 0 0 0 3px rgba(0, 102, 55, 0.1);
            outline: none;
            background-color: var(--input-bg);
            color: var(--text-main);
        }

        .input-group-text {
            background-color: var(--input-bg);
            border-color: var(--hospital-border);
            color: var(--text-muted);
        }

        .btn-primary-hosp {
            background-color: var(--hospital-green);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 600;
            transition: opacity 0.2s;
        }

        .btn-secondary-hosp {
            background-color: var(--card-bg);
            color: var(--text-sub);
            border: 1px solid var(--hospital-border);
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 600;
            transition: background 0.3s, color 0.3s;
        }

        .profile-avatar {
            width: 36px;
            height: 36px;
            background: var(--hospital-green-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--hospital-teal);
            margin: 0 auto;
            transition: background 0.3s, color 0.3s;
        }

        .text-teal {
            color: var(--hospital-teal) !important;
        }

        .text-dark {
            color: var(--text-main) !important;
        }

        .text-muted {
            color: var(--text-muted) !important;
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Header with Actions -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="hospital-title" style="font-size: 1.5rem;">
                    <i class="fas fa-user-clock"></i> LHR Pending Patients
                </h1>
                <p class="text-muted small mb-0">Management of patient inquiries awaiting admission</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button class="btn btn-secondary-hosp" id="exportDataBtn">
                    <i class="fas fa-file-download me-1"></i> Export Filtered
                </button>
                <button class="btn btn-secondary-hosp" id="exportAllBtn">
                    <i class="fas fa-file-export me-1"></i> Export All
                </button>
                <a href="{{ route('lhr.add.inquiry') }}" class="btn btn-primary-hosp">
                    <i class="fas fa-plus-circle me-1"></i> New Inquiry
                </a>
            </div>
        </div>

        <!-- Compact Search Section -->
        <div class="hospital-card p-2 mb-4">
            <form method="GET" action="{{ route('lhr.pending') }}" class="d-flex align-items-center gap-2">
                <div class="input-group" style="max-width: 400px;">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control hospital-input border-start-0"
                        placeholder="Search name, ID or phone..." value="{{ request('search') }}" id="searchName"
                        style="height: 38px;">
                    @if(request('search'))
                        <a href="{{ route('lhr.pending') }}" class="btn btn-outline-secondary border-start-0"
                            title="Clear Search" style="height: 38px; display: flex; align-items: center;">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary-hosp px-4" style="height: 38px;">Search</button>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="hospital-card">
            <div class="hospital-card-header">
                <div class="small text-muted">
                    Showing {{ $inquiries->firstItem() ?? 0 }} to {{ $inquiries->lastItem() ?? 0 }} of
                    {{ $inquiries->total() }} records
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table hospital-table mb-0" id="patientsTable">
                        <thead>
                            <tr>
                                <th width="80">Profile</th>
                                <th width="150">Patient ID</th>
                                <th>Full Name</th>
                                <th>Gender</th>
                                <th>Age</th>
                                <th>Next Follow Up</th>
                                <th>Status</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inquiries as $inquiry)
                                <tr data-id="{{ $inquiry->id }}">
                                    <td>
                                        <a href="{{ route('lhr.patient.profile', $inquiry->id) }}" class="text-decoration-none">
                                            <div
                                                class="profile-avatar d-flex align-items-center justify-content-center position-relative overflow-hidden">
                                                @php
                                                    $initial = strtoupper(substr($inquiry->patient_name ?? 'P', 0, 1));
                                                    $profileImage = $inquiry->profile_image;
                                                @endphp
                                                <span class="avatar-initial">{{ $initial }}</span>
                                                @if($profileImage && Storage::disk('public')->exists($profileImage))
                                                    <img src="{{ asset('storage/' . $profileImage) }}" alt="Profile Image"
                                                        style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:50%;"
                                                        onload="this.closest('.profile-avatar').querySelector('.avatar-initial').style.display='none';"
                                                        onerror="this.remove();">
                                                @endif
                                            </div>
                                        </a>
                                    </td>
                                    <td><span class="text-teal fw-500">{{ $inquiry->patient_id }}</span></td>
                                    <td>
                                        <div class="text-dark">{{ $inquiry->patient_name }}</div>
                                    </td>
                                    <td>
                                        @if($inquiry->gender == 'male')
                                            <span class="hosp-badge hosp-badge-male">Male</span>
                                        @elseif($inquiry->gender == 'female')
                                            <span class="hosp-badge hosp-badge-female">Female</span>
                                        @else
                                            <span class="hosp-badge bg-light text-dark">Other</span>
                                        @endif
                                    </td>
                                    <td><span class="">{{ $inquiry->age }}</span></td>
                                    <td>
                                        @if($inquiry->next_follow_up)
                                            <div class="d-flex flex-column align-items-center">
                                                <span
                                                    class="text-dark">{{ \Carbon\Carbon::parse($inquiry->next_follow_up)->format('d M, Y') }}</span>
                                                <small
                                                    class="text-muted">{{ \Carbon\Carbon::parse($inquiry->next_follow_up)->diffForHumans() }}</small>
                                            </div>
                                        @else
                                            <span class="text-muted italic small">No date set</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="hosp-badge hosp-badge-pending">
                                            <i class="fas fa-clock small"></i> {{ ucfirst($inquiry->status_name) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('lhr.patient.profile', $inquiry->id) }}"
                                                class="action-btn btn-profile" title="Patient Profile">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                            <a href="{{ route('lhr.edit', $inquiry->id) }}" class="action-btn btn-edit"
                                                title="Edit Record">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="action-btn btn-delete delete-btn" data-id="{{ $inquiry->id }}"
                                                data-name="{{ $inquiry->patient_name }}" title="Delete Inquiry">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                            <h5 class="text-secondary">No Pending Records</h5>
                                            <p class="text-muted">Currently there are no patients in the pending inquiry list.
                                            </p>
                                            <a href="{{ route('lhr.add.inquiry') }}" class="btn btn-primary-hosp mt-2">
                                                Start New Inquiry
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($inquiries->hasPages())
                <div class="px-4 py-3 border-top bg-light border-bottom-left-radius border-bottom-right-radius">
                    {{ $inquiries->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Delete Confirmation with SweetAlert
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const name = this.dataset.name;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: `Do you want to permanently delete inquiries for ${name}?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, keep it'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const formData = new FormData();
                            formData.append('_token', '{{ csrf_token() }}');

                            // Show loading state
                            this.disabled = true;
                            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

                            fetch('/lhr/delete/' + id, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire('Deleted!', 'The record has been deleted.', 'success')
                                            .then(() => window.location.reload());
                                    } else {
                                        Swal.fire('Error!', data.message || 'Something went wrong', 'error');
                                        this.disabled = false;
                                        this.innerHTML = '<i class="fas fa-trash-alt"></i>';
                                    }
                                })
                                .catch(error => {
                                    Swal.fire('Error!', 'Network error occurred', 'error');
                                    this.disabled = false;
                                    this.innerHTML = '<i class="fas fa-trash-alt"></i>';
                                });
                        }
                    });
                });
            });

            // Export Buttons
            document.getElementById('exportDataBtn').addEventListener('click', function () {
                const search = document.getElementById('searchName').value;
                window.location.href = '/lhr/export/pending' + (search ? '?search=' + encodeURIComponent(search) : '');
            });

            document.getElementById('exportAllBtn').addEventListener('click', function () {
                window.location.href = '/lhr/export/all-pending';
            });
        });
    </script>
@endsection