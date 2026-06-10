@extends('admin.layouts.layouts')
@section('title', 'Joined Patients')
@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div
                    class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3">
                    <h2 class="mb-0" style="color: var(--accent-solid);">
                        <i class="fas fa-users"></i> Joined Patients
                    </h2>
                    <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                        <form method="GET" action="{{ route('export.joined.inquiries') }}" class="d-inline" id="exportForm">
                            <input type="hidden" name="search" id="exportSearch" value="{{ request('search') }}">
                            <button type="submit" class="btn btn-success shadow-sm" style="border-radius: 8px;">
                                <i class="fas fa-download me-1"></i> Export All
                            </button>
                        </form>
                        <a href="{{ route('add.inquiry') }}?default_status=Joined" class="btn btn-primary w-auto shadow-sm" style="border-radius: 8px;">
                            <i class="fas fa-plus me-1"></i> Add Patient
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
                        placeholder="Search joined patients..." value="{{ request('search') }}" autocomplete="off">
                    <button class="btn btn-outline-secondary d-none" id="clearSearch" type="button">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">


                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead style="background-color: #086838; color: white;">
                            <tr>
                                <th>#</th>
                                <th>Patient ID</th>
                                <th>Date</th>
                                <th>Patient Name</th>
                                <th>Phone no.</th>
                                {{-- <th>Address</th> --}}
                                <th>Program</th>
                                <th>Diagnosis</th>
                                <th>Diet H/O</th>
                                {{-- <th>Status</th> --}}
                                <th class="text-center">Call</th>
                                <th class="text-center">Edit</th>
                                <th class="text-center">Delete</th>
                            </tr>
                        </thead>
                        <tbody id="inquiryTableBody">
                            @include('admin.inquiry.joined_inquiry_table')
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
        function editInquiry(id) {
            window.location.href = "{{ route('add.inquiry') }}" + "?id=" + id;
        }

        function confirmDelete(form, patientName) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to delete inquiry for " + patientName + "?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        $(document).ready(function () {
            let searchTimer;
            const searchInput = $('#liveSearch');
            const clearBtn = $('#clearSearch');
            const tableBody = $('#inquiryTableBody');
            const exportSearch = $('#exportSearch');

            function performSearch(query) {
                if (query.length > 0) {
                    clearBtn.removeClass('d-none');
                } else {
                    clearBtn.addClass('d-none');
                }

                exportSearch.val(query);
                tableBody.css('opacity', '0.5');

                $.ajax({
                    url: "{{ route('joined.inquiry') }}",
                    method: 'GET',
                    data: { search: query },
                    success: function (response) {
                        tableBody.html(response);
                        tableBody.css('opacity', '1');
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

            // Auto-dismiss alerts after 5 seconds
            setTimeout(function () {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function (alert) {
                    const closeButton = alert.querySelector('.btn-close');
                    if (closeButton) {
                        closeButton.click();
                    }
                });
            }, 5000);
        });
    </script>

    <style>
        .card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-subtle);
            box-shadow: var(--shadow-md);
            color: var(--text-primary);
            border-radius: 15px;
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
            color: #ffffff !important;
            border: none;
        }

        .table td {
            vertical-align: middle;
            color: var(--text-primary);
        }

        .table tbody tr:hover {
            background-color: var(--bg-hover) !important;
        }

        .pagination {
            margin-bottom: 0;
        }

        .badge-active {
            background-color: rgba(124, 58, 237, 0.1);
            color: #a78bfa;
            border-color: rgba(124, 58, 237, 0.2);
        }

        .status-badge {
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            border: 1px solid transparent;
        }

        .badge-diagnosis {
            background-color: #e8f5e9;
            color: #086838;
            border: 1px solid #c8e6c9;
        }

        .dark .badge-diagnosis {
            background-color: rgba(52, 211, 153, 0.15) !important;
            color: #34d399 !important;
        }

        /* Program Badge Styles */
        .status-badge {
            display: block;
            width: fit-content;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 700;
            border: 1px solid;
            white-space: nowrap;
            margin-bottom: 6px;
            line-height: 1.2;
        }

        /* Program Badge Colors */
        .badge-pg-1 {
            background-color: #e3f2fd;
            color: #1e88e5;
            border-color: #bbdefb;
        }

        .badge-pg-2 {
            background-color: #f3e5f5;
            color: #8e24aa;
            border-color: #e1bee7;
        }

        .badge-pg-3 {
            background-color: #fff3e0;
            color: #fb8c00;
            border-color: #ffe0b2;
        }

        .badge-pg-4 {
            background-color: #fce4ec;
            color: #d81b60;
            border-color: #f8bbd0;
        }

        .badge-pg-5 {
            background-color: #e0f2f1;
            color: #00897b;
            border-color: #b2dfdb;
        }

        .badge-pg-6 {
            background-color: #e8f5e9;
            color: #43a047;
            border-color: #c8e6c9;
        }

        .badge-pg-7 {
            background-color: #e8eaf6;
            color: #3949ab;
            border-color: #c5cae9;
        }

        .badge-pg-8 {
            background-color: #fbe9e7;
            color: #f4511e;
            border-color: #ffccbc;
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
            background-color: rgba(52, 211, 153, 0.1) !important;
            color: #6ee7b7 !important;
            border-color: rgba(52, 211, 153, 0.2) !important;
        }

        .alert-success {
            background-color: rgba(22, 163, 74, 0.1);
            border-color: rgba(22, 163, 74, 0.2);
            color: #4ade80;
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            border-color: rgba(220, 53, 69, 0.2);
            color: #f87171;
        }

        .profile-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--bg-hover);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--text-primary);
            cursor: pointer;
            border: 1px solid var(--border-subtle);
            margin: 0 auto;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .profile-circle:hover {
            background-color: var(--accent-solid);
            color: white;
            transform: scale(1.1);
        }

        .profile-circle .profile-initial {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }

        .profile-circle img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            z-index: 2;
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

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .badge {
            font-size: 0.85em;
            padding: 0.4em 0.8em;
        }

        .badge.bg-success {
            background-color: #28a745 !important;
        }

        .dark .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.02);
        }
    </style>
@endsection