@extends('admin.layouts.layouts')

@section('title', 'Indoor Patients (IPD)')

@section('content')

    <style>
        /* Import Poppins font */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        /* Apply Poppins to entire page */
        body {
            font-family: 'Poppins', sans-serif !important;
        }

        .search-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
        }

        .search-row {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .search-field {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-field label {
            font-weight: bold;
            color: #2c3e50;
            white-space: nowrap;
        }

        .search-field input,
        .search-field select {
            padding: 8px 12px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            font-size: 14px;
        }

        .search-btn {
            background: rgb(8, 104, 56);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .patient-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 14px;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            min-width: 1000px;
        }

        .patient-table th {
            background: #006637;
            color: white;
            font-weight: bold;
            padding: 15px 10px;
            text-align: left;
            border: none;
            font-size: 14px;
        }

        .patient-table td {
            padding: 12px 10px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
            font-size: 13px;
        }

        .patient-table tr:nth-child(even) {
            background: #f8f9fa;
        }

        .patient-table tr:hover {
            background: #e9f7ef;
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 24px;
            font-weight: bold;
            color: #006637;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            background: transparent;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-profile-square {
            border: 1px solid #28a745;
            color: #28a745 !important;
        }

        .btn-profile-square:hover {
            background-color: #28a745;
            color: white !important;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: #6c757d;
        }

        .pagination-buttons {
            display: flex;
            gap: 8px;
        }

        .pagination-buttons .btn {
            padding: 6px 12px;
            background: #006637;
            color: white;
            border-radius: 4px;
            text-decoration: none;
        }

        .pagination-buttons .btn[disabled] {
            background: #ccc;
            cursor: not-allowed;
        }

        .dual-search-container {
            display: flex;
            align-items: center;
            gap: 15px;
            width: 100%;
        }

        .search-input {
            padding: 8px 12px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            font-size: 14px;
            width: 100%;
        }

        .global-search-wrapper {
            flex: 1;
            min-width: 250px;
        }

        .per-page-wrapper {
            width: 150px;
        }

        .search-btn-wrapper {
            display: flex;
            align-items: flex-end;
            padding-bottom: 2px;
        }

        .pagination .page-item.active .page-link {
            background-color: #006637 !important;
            border-color: #006637 !important;
            color: white !important;
        }
        .pagination .page-link {
            color: #006637;
            border-radius: 6px;
            margin: 0 2px;
            font-size: 13px;
        }
        .pagination .page-link:hover {
            background-color: #e9f7ef;
            color: #004d2a;
        }

        @media (max-width: 768px) {
            .header-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .dual-search-container {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
            }

            .per-page-wrapper,
            .global-search-wrapper,
            .search-btn-wrapper {
                width: 100%;
                min-width: 100%;
            }

            .search-btn-wrapper {
                margin-top: 5px;
            }

            .search-btn-wrapper .btn {
                width: 100%;
            }

            .search-label {
                margin-top: 5px;
            }
        }

        .search-label {
            font-weight: 600;
            color: #006637;
            margin-bottom: 5px;
            display: block;
        }

        .badge-ipd {
            background-color: rgba(0, 102, 55, 0.1);
            color: #006637;
            padding: 4px 12px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
        }

        /* Apply Poppins to entire page but exclude icons */
        body,
        .main-content,
        .card,
        .table,
        .modal-content,
        input,
        select,
        textarea,
        button {
            font-family: 'Poppins', sans-serif !important;
        }

        /* Ensure FontAwesome and Bootstrap Icons preserve their font families */
        .fas,
        .far,
        .fal,
        .fab,
        .fa,
        .fa-solid,
        .fa-regular,
        .fa-brands,
        .bi,
        [class^="fa-"],
        [class*=" fa-"],
        [class^="bi-"],
        [class*=" bi-"] {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands", "bootstrap-icons", "FontAwesome" !important;
        }

        /* Indoor Treatment Modal Styles */
        .indoor-patient-info {
            background: #f0f7f2;
            border: 1px solid #c8e6d4;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 8px 24px;
        }

        .indoor-patient-info .info-item {
            font-size: 14px;
            color: #333;
        }

        .indoor-patient-info .info-item strong {
            color: #006637;
            font-weight: 600;
            margin-right: 6px;
        }

        /* Add New Date Slot Button */
        .add-slot-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: white !important;
            color: #006637 !important;
            border: 1.5px solid #006637 !important;
            border-radius: 8px !important;
            padding: 10px 16px !important;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 18px;
        }

        .add-slot-btn:hover {
            background: #f0f7f2 !important;
        }

        /* Date Slot Card */
        .date-slot-card {
            background: #fff;
            border-radius: 12px;
            overflow: visible;
            margin-bottom: 16px;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .date-slot-header {
            background: linear-gradient(135deg, #006637 0%, #004d2a 100%);
            color: white;
            padding: 12px 16px;
            border-radius: 12px 12px 0 0;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .date-slot-header label {
            font-weight: 500;
            margin: 0;
            font-size: 14px;
        }

        .date-slot-header input {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 13px;
        }

        .date-slot-header input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .date-slot-vitals-input {
            background: #ffffff !important;
            border: 1px solid #ced4da !important;
            color: #212529 !important;
            border-radius: 6px !important;
            padding: 4px 8px !important;
            font-size: 12px !important;
            font-weight: 500 !important;
        }

        .date-slot-vitals-input::placeholder {
            color: #6c757d !important;
            opacity: 1 !important;
            font-size: 11px !important;
        }

        .date-slot-vitals-input:focus {
            background: #ffffff !important;
            border-color: #006637 !important;
            color: #212529 !important;
            outline: none !important;
            box-shadow: 0 0 0 2px rgba(0, 102, 55, 0.25) !important;
        }

        .slot-at-separator {
            color: rgba(255, 255, 255, 0.8);
            font-weight: bold;
        }

        .medicine-count-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            margin-left: auto;
        }

        .remove-slot-btn {
            background: rgba(220, 53, 69, 0.9);
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .remove-slot-btn:hover {
            background: rgba(220, 53, 69, 1);
        }

        .date-slot-body {
            padding: 16px;
        }

        .medicines-header {
            display: grid;
            grid-template-columns: 1fr 1fr 36px;
            gap: 10px;
            margin-bottom: 12px;
            font-weight: 600;
            color: #495057;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .medicine-rows-container {
            margin-bottom: 12px;
        }

        .medicine-row {
            display: grid;
            grid-template-columns: 1fr 1fr 36px;
            gap: 10px;
            align-items: center;
            margin-bottom: 8px;
        }

        .medicine-row input {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 7px 10px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .medicine-row input:focus {
            outline: none;
            border-color: #006637;
            box-shadow: 0 0 0 3px rgba(0, 102, 55, 0.1);
        }

        .medicine-row input::placeholder {
            color: #adb5bd;
        }

        .delete-medicine-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 6px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .delete-medicine-btn:hover {
            background: #c82333;
        }

        .add-medicine-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .add-medicine-btn:hover {
            background: #218838;
        }

        /* Modal Footer Buttons */
        .btn-cancel-indoor {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-cancel-indoor:hover {
            background: #5a6268;
        }

        .btn-save-indoor {
            background: #006637;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-save-indoor:hover {
            background: #004d2a;
        }

        @media (max-width: 576px) {

            .medicine-row,
            .medicines-header {
                grid-template-columns: 1fr;
                gap: 5px;
            }

            .medicines-header {
                display: none;
            }

            .medicine-row {
                background: #f8f9fa;
                padding: 10px;
                border-radius: 8px;
                border: 1px solid #eee;
                position: relative;
                padding-bottom: 45px;
            }

            .medicine-row input {
                width: 100%;
            }

            .delete-medicine-btn {
                position: absolute;
                bottom: 10px;
                right: 10px;
                width: calc(100% - 20px);
            }

            .pagination {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .date-slot-header {
                flex-direction: column;
                align-items: stretch;
            }

            .date-slot-header input {
                width: 100%;
            }

            .slot-at-separator {
                text-align: center;
                margin: 5px 0;
            }

            .medicine-count-badge {
                margin: 5px 0;
                text-align: center;
            }
        }
    </style>

    <div class="header-row">
        <div class="section-title">
            <i class="bi bi-hospital"></i> Indoor Patients (IPD)
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="search-section">
        <form method="GET" action="{{ route('indoor.patients') }}" id="searchForm">
            <div class="dual-search-container d-flex justify-content-between align-items-center flex-wrap">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="text" name="global_search" class="search-input"
                            placeholder="Search by name, ID, diagnosis, address, age..."
                            value="{{ request('global_search') }}" id="globalSearchInput"
                            autocomplete="off" style="width: 350px;">
                        <button type="button" id="clearSearchBtn" title="Clear search"
                            style="background:#e9ecef; border:1px solid #ced4da; border-radius:4px; cursor:pointer; color:#555; font-size:14px; padding:6px 10px; display:{{ request('global_search') ? 'flex' : 'none' }}; align-items:center; gap:4px; flex-shrink:0;">
                            <i class="bi bi-x-lg"></i> Clear
                        </button>
                    </div>
                    <div style="width: 150px;">
                        <label class="search-label">Show</label>
                        <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 entries</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 entries</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 entries</option>
                        </select>
                    </div>
                    <div style="margin-top: 25px;">
                        <button type="submit" class="btn btn-primary" style="background-color: #006637; border: none;">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </div>

                <!-- Right Side Action Button (Red Box Location in Screenshot) -->
                <div style="margin-top: 25px;">
                    <button type="button" class="btn text-white fw-bold px-3 py-2" style="background-color: #006637; border-radius: 6px; font-size: 14px;" onclick="openSelectPatientModal()">
                        <i class="bi bi-plus-circle me-1"></i> Indoor Patient
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Select Indoor Patient Modal -->
    <div class="modal fade" id="selectPatientModal" tabindex="-1" aria-labelledby="selectPatientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header text-white" style="background-color: #006637;">
                    <h5 class="modal-title fw-bold" id="selectPatientModalLabel">
                        <i class="bi bi-hospital me-2"></i> Select Indoor Patient
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <label class="form-label fw-bold text-dark mb-2">Select Patient to Open Indoor Treatment Page:</label>
                    <select id="select_indoor_patient_id" class="form-select form-select-lg border-success" style="font-size: 15px;">
                        <option value="">-- Choose Indoor Patient --</option>
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}">{{ $p->patient_id }} - {{ $p->patient_name }} @if($p->diagnosis) ({{ $p->diagnosis }}) @endif</option>
                        @endforeach
                    </select>
                    <div class="text-danger small mt-2" id="select-patient-error" style="display: none;">Please select a patient from the list.</div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn text-white fw-bold px-4" style="background-color: #006637;" onclick="goToIndoorTreatmentPage()">
                        Open Treatment Page <i class="bi bi-arrow-right me-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Live search results container --}}
    <div id="liveTableWrapper">
        {{-- Will be replaced by AJAX results --}}
    </div>

    <script>
    (function() {
        const searchInput   = document.getElementById('globalSearchInput');
        const clearBtn      = document.getElementById('clearSearchBtn');
        const tableWrapper  = document.querySelector('.table-responsive');
        const paginationDiv = document.querySelector('.pagination');
        const liveWrapper   = document.getElementById('liveTableWrapper');
        let debounceTimer   = null;

        if (!searchInput) return;

        function toggleClearBtn() {
            if (clearBtn) {
                clearBtn.style.display = searchInput.value.trim() ? 'flex' : 'none';
            }
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                toggleClearBtn();
                searchInput.focus();
                window.location.href = '{{ route('indoor.patients') }}' +
                    ({{ request('per_page') ? 'true' : 'false' }} ? '?per_page={{ request('per_page', 10) }}' : '');
            });
        }

        searchInput.addEventListener('input', function() {
            toggleClearBtn();
            const query = this.value.trim();
            clearTimeout(debounceTimer);

            if (query.length === 0) {
                if (tableWrapper)  tableWrapper.style.display  = '';
                if (paginationDiv) paginationDiv.style.display = '';
                liveWrapper.innerHTML = '';
                return;
            }

            if (query.length < 2) return;

            debounceTimer = setTimeout(() => {
                const tbody = tableWrapper ? tableWrapper.querySelector('tbody') : null;
                if (tbody) {
                    tbody.innerHTML = `<tr><td colspan="10" class="text-center py-4">
                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>Searching...
                    </td></tr>`;
                }
                if (paginationDiv) paginationDiv.style.display = 'none';

                fetch(`{{ route('indoor.patients') }}?global_search=${encodeURIComponent(query)}&per_page={{ request('per_page', 10) }}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
                })
                .then(r => r.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc    = parser.parseFromString(html, 'text/html');

                    const newTbody = doc.querySelector('.patient-table tbody');
                    if (tbody && newTbody) {
                        tbody.innerHTML = newTbody.innerHTML;
                    }

                    const newPagination = doc.querySelector('.pagination');
                    if (paginationDiv) {
                        if (newPagination) {
                            paginationDiv.outerHTML = newPagination.outerHTML;
                        } else {
                            paginationDiv.style.display = 'none';
                        }
                    }
                })
                .catch(() => {
                    if (tbody) tbody.innerHTML = '<tr><td colspan="10" class="text-center text-danger py-3">Search failed. Use the Search button.</td></tr>';
                });
            }, 400);
        });

        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('searchForm').submit();
            }
        });
    })();
    </script>

    <div id="tableContainer">
        <div class="table-responsive">
            <table class="patient-table">
                <thead>
                    <tr>
                        <th>Profile</th>
                        <th>Patient Id</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Age</th>
                        <th>Diagnosis</th>
                        <th>Inquiry Date</th>
                        <th>Added On</th>
                        <th>Status</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($patients as $patient)
                        <tr>
                            <td class="profile-icon">
                                @php
                                    $profileImage = $patient->getMeta('profile_image');
                                @endphp
                                <a href="{{ route('ipd.profile', $patient->id) }}" title="View Profile">
                                    @if ($profileImage && file_exists(public_path($profileImage)))
                                        <img src="{{ asset($profileImage) }}" alt="Profile"
                                            style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                                    @else
                                        <i class="far fa-address-card"></i>
                                    @endif
                                </a>
                            </td>
                            <td class="patient_id">
                                <a href="{{ route('ipd.profile', $patient->id) }}" style="color: #28a745; text-decoration: none;"
                                    title="View Profile">
                                    {{ $patient->patient_id }}
                                </a>
                            </td>
                            <td class="fw-bold">{{ $patient->patient_name }}</td>
                            <td>{{ $patient->address }}</td>
                            <td>{{ $patient->age }}</td>
                            <td>
                                @if($patient->diagnosis)
                                    @php
                                        $diagnoses = explode(', ', $patient->diagnosis);
                                        $diagnoses = array_filter($diagnoses);
                                        if (!empty($diagnoses)) {
                                            echo '<span class="badge bg-info me-1">' . implode('</span><span class="badge bg-info me-1">', array_slice($diagnoses, 0, 3)) . '</span>';
                                            if (count($diagnoses) > 3) {
                                                echo '<span class="badge bg-secondary">+' . (count($diagnoses) - 3) . ' more</span>';
                                            }
                                        }
                                    @endphp
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $patient->inquiry_date ? \Carbon\Carbon::parse($patient->inquiry_date)->format('d/m/Y') : '-' }}</td>
                            <td>{{ $patient->created_at->format('d/m/Y') }}</td>
                            <td><span class="badge-ipd">Indoor (IPD)</span></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('svc.profile.add-indoor-treatment', $patient->id) }}" class="action-btn btn-profile-square" title="Indoor Treatment" style="border-color: #006637; color: #006637 !important;">
                                        <i class="bi bi-hospital"></i>
                                    </a>
                                    <button type="button" class="action-btn btn-profile-square" onclick="openPaymentModal({{ json_encode([
                                        'id' => $patient->id,
                                        'name' => $patient->patient_name,
                                        'patient_id' => $patient->patient_id,
                                        'invoice' => $patient->invoice
                                    ]) }})" title="Payment Details" style="border-color: #28a745; color: #28a745 !important;">
                                        <i class="fas fa-rupee-sign"></i>
                                    </button>
                                    <a href="{{ route('edit.svc.inquiry', $patient->id) }}" class="action-btn btn-profile-square"
                                        title="Edit Inquiry" style="border-color: #007bff; color: #007bff !important;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="empty-state">
                                <i class="bi bi-info-circle" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
                                No indoor patients found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($patients->hasPages() || $patients->total() > 0)
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4 px-1">
                <div class="text-muted small fw-medium">
                    Showing {{ $patients->firstItem() ?? 0 }} to {{ $patients->lastItem() ?? 0 }} of {{ $patients->total() }} indoor patients
                </div>
                <div>
                    {{ $patients->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>

    <script>
    (function() {
        const searchInput = document.getElementById('globalSearchInput');
        const clearBtn = document.getElementById('clearSearchBtn');
        const perPageSelect = document.querySelector('select[name="per_page"]');
        const searchForm = document.getElementById('searchForm');
        let debounceTimer = null;

        function toggleClearBtn() {
            if (clearBtn) {
                clearBtn.style.display = searchInput && searchInput.value.trim() ? 'flex' : 'none';
            }
        }

        function fetchResults(url = null) {
            const query = searchInput ? searchInput.value.trim() : '';
            const perPage = perPageSelect ? perPageSelect.value : 10;
            
            let targetUrl = url;
            if (!targetUrl) {
                const baseUrl = searchForm ? searchForm.action : window.location.pathname;
                const params = new URLSearchParams();
                if (query) params.set('global_search', query);
                if (perPage) params.set('per_page', perPage);
                targetUrl = baseUrl + (params.toString() ? '?' + params.toString() : '');
            }

            const tableContainer = document.getElementById('tableContainer');
            if (tableContainer) {
                tableContainer.style.opacity = '0.5';
            }

            fetch(targetUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContainer = doc.getElementById('tableContainer');
                if (newContainer && tableContainer) {
                    tableContainer.innerHTML = newContainer.innerHTML;
                    tableContainer.style.opacity = '1';
                    attachPaginationListeners();
                } else if (tableContainer) {
                    tableContainer.style.opacity = '1';
                }
                window.history.replaceState(null, '', targetUrl);
            })
            .catch(err => {
                if (tableContainer) tableContainer.style.opacity = '1';
                console.error('Live search error:', err);
            });
        }

        function attachPaginationListeners() {
            const container = document.getElementById('tableContainer');
            if (!container) return;
            const links = container.querySelectorAll('.pagination a');
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    fetchResults(this.href);
                });
            });
        }

        if (searchInput) {
            toggleClearBtn();
            searchInput.addEventListener('input', function() {
                toggleClearBtn();
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    fetchResults();
                }, 200);
            });
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                if (searchInput) {
                    searchInput.value = '';
                    toggleClearBtn();
                    searchInput.focus();
                }
                fetchResults();
            });
        }

        if (perPageSelect) {
            perPageSelect.addEventListener('change', function(e) {
                e.preventDefault();
                fetchResults();
            });
        }

        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                fetchResults();
            });
        }

        attachPaginationListeners();
    })();
    </script>

    {{-- Payment Details Modal --}}
    <div class="modal fade" id="paymentDetailsModal" tabindex="-1" aria-labelledby="paymentDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <div class="modal-header" style="background: linear-gradient(135deg, #006637, #28a745); color: white;">
                    <h5 class="modal-title" id="paymentDetailsModalLabel" style="color: white; font-weight: 700;">
                        <i class="fas fa-file-invoice-dollar me-2"></i> Payment & Charges Status
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    {{-- Financial Summary Cards --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="p-3 text-center" style="background: rgba(40, 167, 69, 0.1); border-radius: 10px; border: 1px solid rgba(40, 167, 69, 0.2);">
                                <span class="d-block text-muted" style="font-size: 11px; text-transform: uppercase; font-weight: 600;">Total Charges</span>
                                <h4 class="m-0 mt-1" style="color: #28a745; font-weight: 700;">₹<span id="modal-pay-total">0</span></h4>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 text-center" style="background: rgba(23, 162, 184, 0.1); border-radius: 10px; border: 1px solid rgba(23, 162, 184, 0.2);">
                                <span class="d-block text-muted" style="font-size: 11px; text-transform: uppercase; font-weight: 600;">Paid Amount</span>
                                <h4 class="m-0 mt-1" style="color: #17a2b8; font-weight: 700;">₹<span id="modal-pay-paid">0</span></h4>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 text-center" style="background: rgba(220, 53, 69, 0.1); border-radius: 10px; border: 1px solid rgba(220, 53, 69, 0.2);">
                                <span class="d-block text-muted" style="font-size: 11px; text-transform: uppercase; font-weight: 600;">Due Balance</span>
                                <h4 class="m-0 mt-1" style="color: #dc3545; font-weight: 700;">₹<span id="modal-pay-due">0</span></h4>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 text-center" style="background: rgba(255, 193, 7, 0.1); border-radius: 10px; border: 1px solid rgba(255, 193, 7, 0.2);">
                                <span class="d-block text-muted" style="font-size: 11px; text-transform: uppercase; font-weight: 600;">Discount</span>
                                <h4 class="m-0 mt-1" style="color: #ffc107; font-weight: 700;">₹<span id="modal-pay-discount">0</span></h4>
                            </div>
                        </div>
                    </div>

                    {{-- Form: Update Charges --}}
                    <div style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 10px; padding: 20px;">
                        <h6 class="mb-3" style="color: #006637; font-weight: 700;"><i class="fas fa-edit me-1"></i> Update Patient Charges</h6>
                        <form action="" method="POST" id="paymentUpdateForm">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 12px; font-weight: 600;">Total Charges (₹)</label>
                                    <input type="number" step="0.01" class="form-control" name="total_payment" id="input-pay-total" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 12px; font-weight: 600;">Given Payment (₹)</label>
                                    <input type="number" step="0.01" class="form-control" name="given_payment" id="input-pay-given" readonly style="background-color: #f8f9fa;">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 12px; font-weight: 600;">Discount (₹)</label>
                                    <input type="number" step="0.01" class="form-control" name="discount_payment" id="input-pay-discount" value="0">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 12px; font-weight: 600;">Cash Payment (₹)</label>
                                    <input type="number" step="0.01" class="form-control" name="cash_payment" id="input-pay-cash" value="0">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 12px; font-weight: 600;">G-Pay Payment (₹)</label>
                                    <input type="number" step="0.01" class="form-control" name="gp_payment" id="input-pay-gpay" value="0">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 12px; font-weight: 600;">Cheque Payment (₹)</label>
                                    <input type="number" step="0.01" class="form-control" name="cheque_payment" id="input-pay-cheque" value="0">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 12px; font-weight: 600;">Charge Date</label>
                                    <input type="date" class="form-control" name="charge_date" value="{{ now()->format('Y-m-d') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 12px; font-weight: 600;">Charge Time</label>
                                    <input type="time" class="form-control" name="charge_time" value="{{ now()->format('H:i') }}">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label" style="font-size: 12px; font-weight: 600;">Shift / Reference Note</label>
                                    <input type="text" class="form-control" name="charge_shift" placeholder="e.g. Morning Shift / IPD Routine">
                                </div>
                                <div class="col-12 text-end mt-3">
                                    <button type="submit" class="btn btn-success px-4" style="background-color: #28a745; border-color: #28a745;">
                                        <i class="fas fa-save me-1"></i> Save Updates
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let indoorSlotCounter = 0;

        function openIndoorModal(data) {
            document.getElementById('modal-patient-name').textContent = data.name || '-';
            document.getElementById('modal-patient-age').textContent = data.age || '-';
            document.getElementById('modal-patient-diagnosis').textContent = data.diagnosis || 'N/A';
            document.getElementById('modal-patient-complaints').textContent = data.complaints || 'N/A';

            const form = document.getElementById('indoorTreatmentForm');
            form.action = `/svc-profile/${data.id}/indoor-treatment`;

            // Display existing treatments in History container
            const historyContainer = document.getElementById('indoorHistoryContainer');
            historyContainer.innerHTML = '';

            if (data.treatments && data.treatments.length > 0) {
                // Group treatments by date+time
                const treatmentGroups = {};
                data.treatments.forEach(t => {
                    const key = (t.date || 'No Date') + '||' + (t.time || 'No Time');
                    if (!treatmentGroups[key]) {
                        treatmentGroups[key] = [];
                    }
                    treatmentGroups[key].push(t);
                });

                Object.keys(treatmentGroups).forEach(groupKey => {
                    const [date, time] = groupKey.split('||');
                    const medicines = treatmentGroups[groupKey];
                    
                    // Format Date/Time nicely
                    let displayDate = date;
                    if (date !== 'No Date') {
                        const parts = date.split('-');
                        if (parts.length === 3) displayDate = `${parts[2]}/${parts[1]}/${parts[0]}`;
                    }
                    
                    let displayTime = time;
                    if (time !== 'No Time' && time.includes(':')) {
                        const tparts = time.split(':');
                        let h = parseInt(tparts[0], 10);
                        const ampm = h >= 12 ? 'PM' : 'AM';
                        h = h % 12;
                        h = h ? h : 12;
                        displayTime = `${h}:${tparts[1]} ${ampm}`;
                    }

                    const historyCard = document.createElement('div');
                    historyCard.className = 'card mb-2 border-0 shadow-sm';
                    historyCard.style.background = '#fff';
                    historyCard.style.borderLeft = '3px solid #17a2b8';
                    
                    let medsListHtml = medicines.map(m => `
                        <div class="d-flex justify-content-between align-items-center py-1" style="font-size: 13px; border-bottom: 1px solid #f0f0f0;">
                            <span style="font-weight: 500; color: #333;"><i class="bi bi-capsule me-2" style="color: #006637;"></i>${m.medicine || '-'}</span>
                            <span class="text-muted" style="font-size: 12px; font-style: italic;">${m.note || ''}</span>
                        </div>
                    `).join('');

                    const firstMed = medicines[0] || {};
                    let vitalsHtml = '';
                    if (firstMed.temp || firstMed.pulse || firstMed.bp || firstMed.spo2) {
                        vitalsHtml = `<div class="d-flex flex-wrap gap-1 ms-2 me-2" style="font-size: 11px;">
                            ${firstMed.temp ? `<span class="badge bg-white text-dark border">Temp: ${firstMed.temp}</span>` : ''}
                            ${firstMed.pulse ? `<span class="badge bg-white text-dark border">Pulse: ${firstMed.pulse}</span>` : ''}
                            ${firstMed.bp ? `<span class="badge bg-white text-dark border">BP: ${firstMed.bp}</span>` : ''}
                            ${firstMed.spo2 ? `<span class="badge bg-white text-dark border">SpO2: ${firstMed.spo2}</span>` : ''}
                        </div>`;
                    }

                    historyCard.innerHTML = `
                        <div class="card-header py-1 px-3 d-flex flex-wrap justify-content-between align-items-center" style="background: #f8f9fa; border-bottom: none;">
                            <div class="d-flex align-items-center gap-2">
                                <span style="font-size: 12px; font-weight: 600; color: #006637;">
                                    <i class="bi bi-calendar-event me-1"></i> ${displayDate} &nbsp;|&nbsp; <i class="bi bi-clock me-1"></i> ${displayTime}
                                </span>
                                ${vitalsHtml}
                            </div>
                            <span class="badge bg-light text-dark border">${medicines.length} ${medicines.length === 1 ? 'item' : 'items'}</span>
                        </div>
                        <div class="card-body py-2 px-3">
                            ${medsListHtml}
                        </div>
                    `;
                    historyContainer.appendChild(historyCard);
                });
            } else {
                historyContainer.innerHTML = '<div class="text-muted text-center py-2" style="font-size: 13px;">No past treatment logs recorded yet.</div>';
            }

            // Clear inputs container and add one new empty slot for current logging
            const container = document.getElementById('indoorSlotsContainer');
            container.innerHTML = '';
            indoorSlotCounter = 0;
            
            // Set current date/time as default for new entry
            const now = new Date();
            const yyyy = now.getFullYear();
            const mm = String(now.getMonth() + 1).padStart(2, '0');
            const dd = String(now.getDate()).padStart(2, '0');
            const hh = String(now.getHours()).padStart(2, '0');
            const min = String(now.getMinutes()).padStart(2, '0');
            
            createIndoorSlot(`${yyyy}-${mm}-${dd}`, `${hh}:${min}`, [], 0);

            const modal = new bootstrap.Modal(document.getElementById('indoorTreatmentModal'));
            modal.show();
        }

        function createIndoorSlot(date = '', time = '', medicines = [], slotIndex) {
            const container = document.getElementById('indoorSlotsContainer');

            const slot = document.createElement('div');
            slot.className = 'date-slot-card mb-3 p-3';
            slot.style.border = '1px solid #ced4da';
            slot.style.borderRadius = '8px';
            slot.style.background = '#fff';
            
            const indexToUse = slotIndex !== undefined ? slotIndex : indoorSlotCounter++;
            slot.setAttribute('data-slot', indexToUse);

            // Default at least one empty medicine row
            const medsToRender = medicines.length > 0 ? medicines : [{medicine: '', note: ''}];

            slot.innerHTML = `
                    <div class="date-slot-header d-flex flex-wrap align-items-center gap-2 mb-3 pb-2" style="border-bottom: 1px solid #dee2e6;">
                        <label style="font-weight: 600; font-size: 13px; color: #495057;">Date &amp; Time:</label>
                        <input type="date" class="form-control form-control-sm w-auto" name="slot_date[${indexToUse}]" value="${date}" required>
                        <span class="slot-at-separator">@</span>
                        <input type="time" class="form-control form-control-sm w-auto" name="slot_time[${indexToUse}]" value="${time}">
                        <div class="d-flex align-items-center gap-1 ms-2">
                            <input type="text" class="date-slot-vitals-input" name="slot_temp[${indexToUse}]" placeholder="Temp (°F)" title="Temperature (°F)" style="width: 90px;">
                            <input type="text" class="date-slot-vitals-input" name="slot_pulse[${indexToUse}]" placeholder="Pulse (bpm)" title="Pulse (bpm)" style="width: 90px;">
                            <input type="text" class="date-slot-vitals-input" name="slot_bp[${indexToUse}]" placeholder="BP (mmHg)" title="Blood Pressure" style="width: 90px;">
                            <input type="text" class="date-slot-vitals-input" name="slot_spo2[${indexToUse}]" placeholder="SpO2 (%)" title="SpO2 (%)" style="width: 90px;">
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger ms-auto d-flex align-items-center gap-1" onclick="removeIndoorSlot(this)" style="padding: 2px 8px; font-size: 12px;">
                            <i class="bi bi-trash"></i> Drop Slot
                        </button>
                    </div>
                    <div class="date-slot-body">
                        <div class="medicine-rows-container">
                            ${medsToRender.map(med => `
                                <div class="medicine-row d-flex gap-2 mb-2">
                                    <input type="text" class="form-control form-control-sm flex-grow-1" name="slot_medicine[${indexToUse}][]" value="${med.medicine || ''}" placeholder="Enter medicine name / action" autocomplete="off" required>
                                    <input type="text" class="form-control form-control-sm w-25" name="slot_note[${indexToUse}][]" value="${med.note || ''}" placeholder="Dosage / Note">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deleteMedicineRow(this)" title="Remove Row">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1" onclick="addMedicineRow(this, ${indexToUse})" style="font-size: 12px;">
                            <i class="bi bi-plus"></i> Add Item Row
                        </button>
                    </div>
                `;

            container.appendChild(slot);
        setTimeout(() => {
                const modalEl = container.closest('.modal');
                if (modalEl) {
                    modalEl.scrollTo({ top: modalEl.scrollHeight, behavior: 'smooth' });
                }
                slot.scrollIntoView({ behavior: 'smooth', block: 'end' });
            }, 100);
        }

        function addIndoorDateSlot() {
            const now = new Date();
            const yyyy = now.getFullYear();
            const mm = String(now.getMonth() + 1).padStart(2, '0');
            const dd = String(now.getDate()).padStart(2, '0');
            const hh = String(now.getHours()).padStart(2, '0');
            const min = String(now.getMinutes()).padStart(2, '0');
            
            createIndoorSlot(`${yyyy}-${mm}-${dd}`, `${hh}:${min}`, [], indoorSlotCounter++);
        }

        function addMedicineRow(btn, slotIndex) {
            const card = btn.closest('.date-slot-card');
            const rowsContainer = card.querySelector('.medicine-rows-container');

            const row = document.createElement('div');
            row.className = 'medicine-row d-flex gap-2 mb-2';
            row.innerHTML = `
                    <input type="text" class="form-control form-control-sm flex-grow-1" name="slot_medicine[${slotIndex}][]" placeholder="Enter medicine name / action" autocomplete="off" required>
                    <input type="text" class="form-control form-control-sm w-25" name="slot_note[${slotIndex}][]" placeholder="Dosage / Note">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deleteMedicineRow(this)" title="Remove Row">
                        <i class="bi bi-x-lg"></i>
                    </button>
                `;

            rowsContainer.appendChild(row);
            row.querySelector('input').focus();
        }

        function deleteMedicineRow(btn) {
            const card = btn.closest('.date-slot-card');
            const rowsContainer = card.querySelector('.medicine-rows-container');
            const rows = rowsContainer.querySelectorAll('.medicine-row');

            if (rows.length > 1) {
                btn.closest('.medicine-row').remove();
            } else {
                // Clear values if last row
                btn.closest('.medicine-row').querySelectorAll('input').forEach(i => i.value = '');
            }
        }

        function removeIndoorSlot(btn) {
            const container = document.getElementById('indoorSlotsContainer');
            const slots = container.querySelectorAll('.date-slot-card');

            if (slots.length > 1) {
                btn.closest('.date-slot-card').remove();
            } else {
                // Clear values
                btn.closest('.date-slot-card').querySelectorAll('input[type="text"]').forEach(i => i.value = '');
            }
        }

        function openPaymentModal(data) {
            const inv = data.invoice || {};
            
            // Format numbers nicely
            const total = parseFloat(inv.total_payment || inv.price || 0).toFixed(2);
            const paid = parseFloat(inv.given_payment || 0).toFixed(2);
            const due = parseFloat(inv.due_payment || 0).toFixed(2);
            const disc = parseFloat(inv.discount || 0).toFixed(2);

            document.getElementById('modal-pay-total').textContent = total;
            document.getElementById('modal-pay-paid').textContent = paid;
            document.getElementById('modal-pay-due').textContent = due;
            document.getElementById('modal-pay-discount').textContent = disc;

            document.getElementById('input-pay-total').value = parseFloat(total);
            document.getElementById('input-pay-given').value = parseFloat(paid);
            document.getElementById('input-pay-discount').value = parseFloat(disc);
            
            document.getElementById('input-pay-cash').value = parseFloat(inv.cash_payment || 0);
            document.getElementById('input-pay-gpay').value = parseFloat(inv.gpay_payment || inv.google_pay || 0);
            document.getElementById('input-pay-cheque').value = parseFloat(inv.cheque_payment || 0);

            const form = document.getElementById('paymentUpdateForm');
            form.action = `/svc-profile/${data.id}/update-charges`;

            const modal = new bootstrap.Modal(document.getElementById('paymentDetailsModal'));
            modal.show();
        }

        // Calculate given payment
        document.addEventListener('DOMContentLoaded', function() {
            const cashInput = document.getElementById('input-pay-cash');
            const gpayInput = document.getElementById('input-pay-gpay');
            const chequeInput = document.getElementById('input-pay-cheque');
            const givenInput = document.getElementById('input-pay-given');

            function calculateGiven() {
                const cash = parseFloat(cashInput?.value) || 0;
                const gpay = parseFloat(gpayInput?.value) || 0;
                const cheque = parseFloat(chequeInput?.value) || 0;
                if (givenInput) givenInput.value = (cash + gpay + cheque).toFixed(2);
            }

            [cashInput, gpayInput, chequeInput].forEach(input => {
                if (input) input.addEventListener('input', calculateGiven);
            });
        });

        function openSelectPatientModal() {
            const modalEl = document.getElementById('selectPatientModal');
            if (modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        }

        function goToIndoorTreatmentPage() {
            const select = document.getElementById('select_indoor_patient_id');
            const patientId = select ? select.value : '';
            const errorEl = document.getElementById('select-patient-error');
            if (!patientId) {
                if (errorEl) errorEl.style.display = 'block';
                return;
            }
            if (errorEl) errorEl.style.display = 'none';
            window.location.href = '/svc-profile/' + patientId + '/add-indoor-treatment';
        }
    </script>
@endsection