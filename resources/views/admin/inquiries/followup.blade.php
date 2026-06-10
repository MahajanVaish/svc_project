@extends('admin.layouts.layouts')
@section('title', 'Follow Up Patients')
@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div
                    class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3">
                    <h2 class="mb-0" style="color: var(--accent-solid);">
                        <i class="fas fa-calendar-check"></i> Follow Up Patients
                    </h2>
                    <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                        <span class="badge bg-primary px-3 py-2 shadow-sm"
                            style="font-size: 14px; background-color: var(--accent-solid) !important;">
                            {{ $followupPatients->total() }} Patients
                        </span>
                        <a href="{{ route('add.inquiry') }}" class="btn btn-primary w-auto shadow-sm" style="border-radius: 8px;">
                            <i class="fas fa-plus me-1"></i> Add Inquiry
                        </a>
                        <a href="javascript:history.back()" class="btn btn-outline-secondary shadow-sm"
                            style="border-radius: 8px;">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="input-group search-container">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" id="liveSearch"
                        placeholder="Search follow up patients..." value="{{ request('search_name') }}" autocomplete="off">
                    <button class="btn btn-outline-secondary d-none" id="clearSearch" type="button">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead style="background-color: #086838; color: white;">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Profile</th>
                                <th>Patient ID</th>
                                <th>Name</th>
                                <th>Follow-up Date</th>
                                <th>Phone</th>
                                <th>Diagnosis</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="followupTableBody">
                            @include('admin.inquiries.followup_table')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            let searchTimer;
            const searchInput = $('#liveSearch');
            const clearBtn = $('#clearSearch');
            const tableBody = $('#followupTableBody');
            const countBadge = $('#patientCountBadge');

            function performSearch(query) {
                if (query.length > 0) {
                    clearBtn.removeClass('d-none');
                } else {
                    clearBtn.addClass('d-none');
                }

                tableBody.css('opacity', '0.5');

                $.ajax({
                    url: "{{ route('followup.patients.appointment') }}",
                    method: 'GET',
                    data: { search_name: query },
                    success: function (response) {
                        tableBody.html(response);
                        tableBody.css('opacity', '1');

                        // Update patient count from the table rows if possible or just use a placeholder
                        // For now, let's keep the count static or update it via a header in response if we wanted to be fancy
                    },
                    error: function () {
                        tableBody.css('opacity', '1');
                    }
                });
            }

            searchInput.on('keyup', function () {
                clearTimeout(searchTimer);
                const query = $(this).val();
                searchTimer = setTimeout(function () {
                    performSearch(query);
                }, 300);
            });

            clearBtn.on('click', function () {
                searchInput.val('');
                performSearch('');
            });

            if (searchInput.val().length > 0) {
                clearBtn.removeClass('d-none');
            }
        });

        // Edit follow-up date function
        function editFollowupDate(patientId, currentDate) {
            Swal.fire({
                title: 'Edit Follow-up Date',
                html: `
                            <div style="text-align: left;">
                                <label for="followupDate" style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--text-primary);">Select New Follow-up Date:</label>
                                <input type="date" id="followupDate" class="swal2-input" value="${currentDate}" style="width: 100%; margin: 0;">
                            </div>
                        `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#086838',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Update Date',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'premium-swal-popup'
                },
                preConfirm: () => {
                    const newDate = document.getElementById('followupDate').value;
                    if (!newDate) {
                        Swal.showValidationMessage('Please select a date');
                        return false;
                    }
                    return newDate;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    updateFollowupDate(patientId, result.value);
                }
            });
        }

        // Delete follow-up date function
        function deleteFollowupDate(patientId) {
            Swal.fire({
                title: 'Remove Follow-up Date?',
                text: "This patient will be removed from the follow-up list.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'premium-swal-popup'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    updateFollowupDate(patientId, null);
                }
            });
        }

        // Update follow-up date in database
        function updateFollowupDate(patientId, newDate) {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('patient_id', patientId);
            formData.append('next_followup_date', newDate || '');

            fetch('{{ route("update.followup.date") }}', {
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
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            confirmButtonColor: '#086838',
                            timer: 2000,
                            timerProgressBar: true,
                            customClass: {
                                popup: 'premium-swal-popup'
                            }
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Failed to update follow-up date',
                            confirmButtonColor: '#dc3545',
                            customClass: {
                                popup: 'premium-swal-popup'
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while updating the follow-up date',
                        confirmButtonColor: '#dc3545',
                        customClass: {
                            popup: 'premium-swal-popup'
                        }
                    });
                });
        }
    </script>

    <style>
        .card {
            background-color: var(--bg-card);
            border: none;
            box-shadow: var(--shadow-md);
        }

        .card-body {
            color: var(--text-primary);
        }

        .btn-primary {
            background-color: #086838;
            border-color: #086838;
            color: white;
        }

        .btn-primary:hover {
            background-color: #06502b;
            border-color: #06502b;
            color: white;
        }

        .table th {
            font-weight: 600;
            white-space: nowrap;
            background-color: #086838 !important;
            color: white !important;
            vertical-align: middle;
        }

        .table td {
            color: var(--text-primary);
            vertical-align: middle;
            font-size: 13px;
        }

        .dark .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.02);
        }

        .table tbody tr:hover {
            background-color: var(--bg-hover) !important;
        }

        /* Action Buttons Styling */
        .action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all 0.3s ease;
            background: transparent;
            border: 1px solid transparent;
            cursor: pointer;
        }

        .btn-edit-square {
            border-color: #16a34a;
            color: #16a34a;
        }

        .btn-edit-square:hover {
            background-color: #16a34a;
            color: white;
        }

        .btn-delete-square {
            border-color: #dc3545;
            color: #dc3545;
        }

        .btn-delete-square:hover {
            background-color: #dc3545;
            color: white;
        }

        .pagination {
            margin-bottom: 0;
        }

        /* Custom Status Badges */
        .status-badge {
            padding: 5px 12px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.75rem;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid transparent;
        }

        .badge-pending {
            background-color: rgba(217, 119, 6, 0.1);
            color: #f59e0b;
            border-color: rgba(217, 119, 6, 0.2);
        }

        .badge-diet {
            background-color: rgba(8, 145, 178, 0.1);
            color: #22d3ee;
            border-color: rgba(8, 145, 178, 0.2);
        }

        .badge-joined {
            background-color: rgba(22, 163, 74, 0.1);
            color: #4ade80;
            border-color: rgba(22, 163, 74, 0.2);
        }

        .badge-active {
            background-color: rgba(124, 58, 237, 0.1);
            color: #a78bfa;
            border-color: rgba(124, 58, 237, 0.2);
        }

        .badge-diagnosis {
            background-color: rgba(8, 104, 56, 0.1);
            color: #086838;
            border-color: rgba(8, 104, 56, 0.3);
            font-weight: 700;
            text-transform: none;
            letter-spacing: normal;
        }

        /* Dark Mode Specific Badge Overrides */
        .dark .badge-pending {
            background-color: rgba(245, 158, 11, 0.15) !important;
            color: #fbbf24 !important;
        }

        .dark .badge-diet {
            background-color: rgba(34, 211, 238, 0.15) !important;
            color: #67e8f9 !important;
        }

        .dark .badge-joined {
            background-color: rgba(74, 222, 128, 0.15) !important;
            color: #86efac !important;
        }

        .dark .badge-active {
            background-color: rgba(167, 139, 250, 0.15) !important;
            color: #c4b5fd !important;
        }

        .dark .badge-diagnosis {
            background-color: rgba(52, 211, 153, 0.15) !important;
            color: #34d399 !important;
        }

        /* Profile avatar animation */
        .profile-avatar {
            transition: all 0.2s ease;
        }

        .profile-avatar:hover {
            transform: scale(1.08);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
        }

        /* Badge pulse animation for Today */
        .badge-pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4);
            }

            70% {
                box-shadow: 0 0 0 8px rgba(220, 53, 69, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        .alert-success {
            background-color: rgba(22, 163, 74, 0.1);
            border-color: rgba(22, 163, 74, 0.2);
            color: #4ade80;
        }
    </style>
@endsection