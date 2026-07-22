@extends('admin.layouts.layouts')
@section('title', 'Online/Abroad Patients')
@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div
                    class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3">
                    <h2 class="mb-0" style="color: var(--accent-solid);">
                        <i class="fas fa-globe"></i> Online/Abroad Patients
                    </h2>
                    <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                        <form method="GET" action="{{ route('export.online.abroad.inquiries') }}" class="d-inline"
                            id="exportForm">
                            <input type="hidden" name="search" id="exportSearch" value="{{ request('search') }}">
                            <button type="submit" class="btn btn-success shadow-sm" style="border-radius: 8px;">
                                <i class="fas fa-download me-1"></i> Export All
                            </button>
                        </form>
                        <a href="{{ route('add.inquiry') }}?is_online_abroad=1" class="btn btn-primary w-auto shadow-sm" style="border-radius: 8px;">
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
                    <input type="text" class="form-control border-start-0" id="liveSearch" placeholder="Search Patients..."
                        value="{{ request('search') }}" autocomplete="off">
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
                                <th>Diagnosis</th>
                                <th>Diet H/O</th>
                                <th class="text-center">Zoom</th>
                                <th class="text-center">Reverse</th>
                                <th class="text-center">Call</th>
                                <th class="text-center">Edit</th>
                                <th class="text-center">Delete</th>
                            </tr>
                        </thead>
                        <tbody id="inquiryTableBody">
                            @include('admin.inquiry.online_abroad_inquiry_table')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Unified Zoom Actions Modal --}}
    <div class="modal fade" id="zoomActionsModal" tabindex="-1" aria-labelledby="zoomActionsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="max-width: 500px !important;">
                <div class="modal-header">
                    <h5 class="modal-title" id="zoomActionsModalLabel">Zoom Meeting Options</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold mb-2 text-primary">Internal Access</label>
                        <a href="" id="modalZoomJoinBtn" target="_blank"
                            class="btn btn-primary w-100 d-flex align-items-center justify-content-center py-2"
                            style="background: #086838; border: none;">
                            <i class="fas fa-video me-2"></i> Join Meeting Now
                        </a>
                    </div>

                    <hr class="my-4">

                    <div>
                        <label class="form-label fw-bold mb-2 text-primary">Share With Patient</label>
                        <div class="input-group mb-3">
                            <input type="text" id="modalZoomLinkInput" class="form-control" readonly
                                style="font-size: 13px; background: var(--bg-main); color: var(--text-primary);">
                            <button class="btn btn-outline-secondary" type="button"
                                onclick="copyModalZoomLink()" title="Copy Link">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>

                        <a href="" id="modalZoomWaBtn" target="_blank"
                            class="btn btn-success w-100 d-flex align-items-center justify-content-center py-2"
                            style="background: #25D366; border: none; color: white;">
                            <i class="fab fa-whatsapp me-2"></i> Share via WhatsApp
                        </a>
                        <p class="text-muted small mt-2 text-center">
                            <i class="fas fa-info-circle me-1"></i> This will open WhatsApp with a pre-filled message.
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function reverseToFollowup(id, source, patientName) {
            const today = new Date().toISOString().split('T')[0];
            
            Swal.fire({
                title: 'Move to Follow-up',
                html: `
                    <div style="text-align: left;">
                        <p>Are you sure you want to move <strong>${patientName}</strong> to the Follow-up list?</p>
                        <label for="swalFollowupDate" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Select Next Follow-up Date:</label>
                        <input type="date" id="swalFollowupDate" class="swal2-input" min="${today}" value="${today}" style="width: 100%; margin: 0; box-sizing: border-box; background: var(--bg-card); color: var(--text-primary); border: 1px solid var(--border-subtle);">
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#086838',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Move to Follow-up',
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                    const nextDate = document.getElementById('swalFollowupDate').value;
                    if (!nextDate) {
                        Swal.showValidationMessage('Please select a valid date');
                        return false;
                    }
                    return nextDate;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const date = result.value;
                    
                    $.ajax({
                        url: `/admin/patient/reverse-to-followup/${id}`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            next_followup_date: date,
                            source: source
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Moved Successfully!',
                                    text: response.message,
                                    confirmButtonColor: '#086838',
                                    timer: 2000,
                                    timerProgressBar: true
                                }).then(() => {
                                    if (typeof performSearch === 'function') {
                                        performSearch($('#liveSearch').val());
                                    } else {
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Failed',
                                    text: response.message || 'Error occurred while moving patient.',
                                    confirmButtonColor: '#dc3545'
                                });
                            }
                        },
                        error: function(xhr) {
                            const errorMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Something went wrong';
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMsg,
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    });
                }
            });
        }

        function openZoomModal(startUrl, joinUrl, waUrl) {
            const joinBtn = document.getElementById('modalZoomJoinBtn');
            const waBtn = document.getElementById('modalZoomWaBtn');
            const linkInput = document.getElementById('modalZoomLinkInput');

            if (joinBtn) joinBtn.href = startUrl;
            if (waBtn) waBtn.href = waUrl;
            if (linkInput) linkInput.value = joinUrl;

            const modalEl = document.getElementById('zoomActionsModal');
            if (modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        }

        function copyModalZoomLink() {
            const linkInput = document.getElementById('modalZoomLinkInput');
            if (!linkInput) return;
            linkInput.select();
            linkInput.setSelectionRange(0, 99999);
            document.execCommand('copy');
        }

        function editInquiry(id) {
            window.location.href = "/admin/inquiry/" + id + "/edit";
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
                // Show clear button if query is not empty
                if (query.length > 0) {
                    clearBtn.removeClass('d-none');
                } else {
                    clearBtn.addClass('d-none');
                }

                // Update export field
                exportSearch.val(query);

                // Add loading opacity
                tableBody.css('opacity', '0.5');

                $.ajax({
                    url: "{{ route('online.abroad.inquiry') }}",
                    method: 'GET',
                    data: { search: query },
                    success: function (response) {
                        tableBody.html(response);
                        tableBody.css('opacity', '1');
                    },
                    error: function () {
                        tableBody.css('opacity', '1');
                        // Optional: show error message
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

            // Initial clear button state
            if (searchInput.val().length > 0) {
                clearBtn.removeClass('d-none');
            }
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
    </style>
@endsection