@extends('admin.layouts.layouts')
@section('title', 'Pending Inquiries')
@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div
                    class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3">
                    <h2 class="mb-0" style="color: var(--accent-solid);">
                        <i class="fas fa-clock"></i> Pending Inquiries
                    </h2>
                    <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                        <form method="GET" action="{{ route('export.pending.inquiries') }}" class="d-inline"
                            id="exportForm">
                            <input type="hidden" name="search" id="exportSearch" value="{{ request('search') }}">
                            <button type="submit" class="btn btn-success shadow-sm" style="border-radius: 8px;">
                                <i class="fas fa-download me-1"></i> Export All
                            </button>
                        </form>
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
                        placeholder="Search pending inquiries..." value="{{ request('search') }}" autocomplete="off">
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
                                <th>Profile</th>
                                <th>Patient Id</th>
                                <th>Date</th>
                                <th>Patient Name</th>
                                <th>Phone no.</th>
                                {{-- <th>Address</th> --}}
                                <th>Diagnosis</th>
                                <th>Status</th>
                                <th class="text-center">Call</th>
                                <th class="text-center">Edit</th>
                                <th class="text-center">Delete</th>
                            </tr>
                        </thead>
                        <tbody id="inquiryTableBody">
                            @include('admin.inquiry.pending_inquiry_table')
                        </tbody>
                    </table>
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
                    url: "{{ route('pending.inquiry') }}",
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
        });
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
            color: white !important;
        }

        .table td {
            color: var(--text-primary);
            vertical-align: middle;
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

        .table th {
            font-weight: 600;
            white-space: nowrap;
            background-color: #086838 !important;
            color: white !important;
            vertical-align: middle;
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

        /* Theme colors override */
        .btn-success {
            background-color: #086838;
            border-color: #086838;
        }

        .btn-success:hover {
            background-color: #06502b;
            border-color: #06502b;
        }

        .alert-success {
            background-color: rgba(22, 163, 74, 0.1);
            border-color: rgba(22, 163, 74, 0.2);
            color: #4ade80;
        }

        .table td {
            vertical-align: middle;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .badge {
            font-size: 0.85em;
            padding: 0.4em 0.8em;
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
    </style>
@endsection