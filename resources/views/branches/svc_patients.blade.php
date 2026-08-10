@extends('admin.layouts.layouts')

@section('title', 'SVC Patients')

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
            max-width: 400px;
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

        /* Apply Poppins to entire page but exclude icons */
        body, .main-content, .card, .table, .modal-content, input, select, textarea, button {
            font-family: 'Poppins', sans-serif !important;
        }
        
        /* Ensure FontAwesome and Bootstrap Icons preserve their font families */
        .fas, .far, .fal, .fab, .fa, .fa-solid, .fa-regular, .fa-brands, .bi, 
        [class^="fa-"], [class*=" fa-"], [class^="bi-"], [class*=" bi-"] {
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
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
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 13px;
        }

        .date-slot-header input::placeholder {
            color: rgba(255,255,255,0.7);
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
            color: rgba(255,255,255,0.8);
            font-weight: bold;
        }

        .medicine-count-badge {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            margin-left: auto;
        }

        .remove-slot-btn {
            background: rgba(220,53,69,0.9);
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .remove-slot-btn:hover {
            background: rgba(220,53,69,1);
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
            box-shadow: 0 0 0 3px rgba(0,102,55,0.1);
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
    </style>

    <div class="header-row">
        <div class="section-title">
            <i class="bi bi-hospital"></i> SVC Patients
        </div>
        <div>
            <a href="{{ route('svc.add.inquiry') }}" class="btn btn-success" style="background-color: #006637; border-radius: 6px; font-weight: 500;">
                <i class="bi bi-plus-circle me-1"></i> Add Inquiry
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="search-section">
        <form method="GET" action="{{ route('svc-patient') }}" id="searchForm">
            <div class="dual-search-container">
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
        </form>
    </div>

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
                                <a href="{{ route('svc.profile', $patient->id) }}" title="View Profile">
                                    @if ($profileImage && file_exists(public_path($profileImage)))
                                        <img src="{{ asset($profileImage) }}" alt="Profile"
                                            style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                                    @else
                                        <i class="far fa-address-card"></i>
                                    @endif
                                </a>
                            </td>
                            <td class="patient_id">
                                <a href="{{ route('svc.profile', $patient->id) }}"
                                    style="color: #28a745; text-decoration: none;" title="View Profile">
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
                            <td><span class="badge-ipd">SVC Patient</span></td>
                            <td>
                                <div class="action-buttons">
                                    @if($patient->getMeta('pt_status') === 'IPD')
                                    <button type="button" class="action-btn btn-profile-square" 
                                        onclick="openIndoorModal({{ json_encode([
                                            'id' => $patient->id,
                                            'name' => $patient->patient_name,
                                            'age' => $patient->age,
                                            'diagnosis' => $patient->diagnosis ?? 'N/A',
                                            'complaints' => $patient->getMeta('complain') ?? 'N/A',
                                            'treatments' => $patient->treatments
                                        ]) }})" 
                                        title="Manage Treatment">
                                        <i class="bi bi-hospital"></i>
                                    </button>
                                    @endif
                                    <a href="{{ route('edit.svc.inquiry', $patient->id) }}" class="action-btn btn-profile-square" title="Edit Inquiry" style="border-color: #007bff; color: #007bff !important;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('delete-inquiry', $patient->id) }}" method="POST" class="d-inline delete-svc-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="action-btn btn-delete-svc" title="Delete Patient"
                                            style="border-color:#dc3545;color:#dc3545;"
                                            onclick="confirmDeleteSvc(this, '{{ addslashes($patient->patient_name) }}', '{{ $patient->patient_id }}')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="empty-state">
                                <i class="bi bi-info-circle" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
                                No SVC patients found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($patients->hasPages() || $patients->total() > 0)
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4 px-1">
                <div class="text-muted small fw-medium">
                    Showing {{ $patients->firstItem() ?? 0 }} to {{ $patients->lastItem() ?? 0 }} of {{ $patients->total() }} patients
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

    {{-- Indoor Treatment Modal --}}
    <div class="modal fade" id="indoorTreatmentModal" tabindex="-1" aria-labelledby="indoorTreatmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <form action="" method="POST" id="indoorTreatmentForm">
                    @csrf

                    {{-- Modal Header --}}
                    <div class="modal-header">
                        <h5 class="modal-title" id="indoorTreatmentModalLabel">
                            <i class="bi bi-hospital-fill"></i> Add Indoor Treatment Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="modal-body">

                        {{-- Patient Info --}}
                        <div class="indoor-patient-info">
                            <div class="info-item"><strong>Name:</strong> <span id="modal-patient-name"></span></div>
                            <div class="info-item"><strong>Age:</strong> <span id="modal-patient-age"></span></div>
                            <div class="info-item"><strong>Diagnosis:</strong> <span id="modal-patient-diagnosis"></span></div>
                            <div class="info-item"><strong>Complaints:</strong> <span id="modal-patient-complaints"></span></div>
                        </div>

                        {{-- Add New Date Slot Button --}}
                        <button type="button" class="add-slot-btn" id="addSlotBtn">
                            <i class="bi bi-plus-lg"></i> Add New Date Slot
                        </button>

                        {{-- Slots Container --}}
                        <div id="indoorSlotsContainer">
                            <!-- Slots are added dynamically via JS -->
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="modal-footer justify-content-end gap-2">
                        <button type="button" class="btn-cancel-indoor" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-save-indoor">
                            <i class="bi bi-check-lg me-1"></i> Save Treatment
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        let indoorSlotCounter = 1;

        // Wire up the "Add New Date Slot" button after DOM is ready
        document.addEventListener('DOMContentLoaded', function () {
            const addSlotBtn = document.getElementById('addSlotBtn');
            if (addSlotBtn) {
                addSlotBtn.addEventListener('click', addIndoorDateSlot);
            }
        });

        function openIndoorModal(data) {
            document.getElementById('modal-patient-name').textContent = data.name;
            document.getElementById('modal-patient-age').textContent = data.age;
            document.getElementById('modal-patient-diagnosis').textContent = data.diagnosis;
            document.getElementById('modal-patient-complaints').textContent = data.complaints;

            const form = document.getElementById('indoorTreatmentForm');
            form.action = `/svc-profile/${data.id}/indoor-treatment`;

            // Reset counter and clear container
            indoorSlotCounter = 0;
            const container = document.getElementById('indoorSlotsContainer');
            container.innerHTML = '';

            // Load existing treatments grouped by date+time
            if (data.treatments && data.treatments.length > 0) {
                const treatmentGroups = {};
                data.treatments.forEach(t => {
                    const key = (t.date || '') + '||' + (t.time || '');
                    if (!treatmentGroups[key]) treatmentGroups[key] = [];
                    treatmentGroups[key].push(t);
                });

                Object.keys(treatmentGroups).forEach((groupKey) => {
                    const [date, time] = groupKey.split('||');
                    createIndoorSlot(date, time, treatmentGroups[groupKey]);
                });
            } else {
                // Always show at least one empty slot
                createIndoorSlot('', '', [{ medicine: '', note: '' }]);
            }

            const modal = new bootstrap.Modal(document.getElementById('indoorTreatmentModal'));
            modal.show();
        }

        function createIndoorSlot(date, time, medicines) {
            const container = document.getElementById('indoorSlotsContainer');
            const slotIndex = indoorSlotCounter++;

            const slot = document.createElement('div');
            slot.className = 'date-slot-card';
            slot.dataset.slot = slotIndex;

            const medicineRows = medicines.map(med => `
                <div class="medicine-row">
                    <input type="text" name="slot_medicine[${slotIndex}][]" value="${med.medicine || ''}" placeholder="Medicine name" autocomplete="off">
                    <input type="text" name="slot_note[${slotIndex}][]" value="${med.note || ''}" placeholder="Note">
                    <button type="button" class="delete-medicine-btn" title="Remove">
                        <i class="bi bi-trash3-fill" style="font-size:12px;"></i>
                    </button>
                </div>
            `).join('');

            const firstMed = medicines[0] || {};
            slot.innerHTML = `
                <div class="date-slot-header d-flex flex-wrap align-items-center gap-2">
                    <label>Date &amp; Time</label>
                    <input type="date" name="slot_date[${slotIndex}]" value="${date || ''}" required>
                    <span class="slot-at-separator">@</span>
                    <input type="time" name="slot_time[${slotIndex}]" value="${time || ''}">
                    <div class="d-flex align-items-center gap-1 ms-2">
                        <input type="text" class="date-slot-vitals-input" name="slot_temp[${slotIndex}]" value="${firstMed.temp || ''}" placeholder="Temp (°F)" title="Temperature (°F)" style="width: 90px;">
                        <input type="text" class="date-slot-vitals-input" name="slot_pulse[${slotIndex}]" value="${firstMed.pulse || ''}" placeholder="Pulse (bpm)" title="Pulse (bpm)" style="width: 90px;">
                        <input type="text" class="date-slot-vitals-input" name="slot_bp[${slotIndex}]" value="${firstMed.bp || ''}" placeholder="BP (mmHg)" title="Blood Pressure" style="width: 90px;">
                        <input type="text" class="date-slot-vitals-input" name="slot_spo2[${slotIndex}]" value="${firstMed.spo2 || ''}" placeholder="SpO2 (%)" title="SpO2 (%)" style="width: 90px;">
                    </div>
                    <span class="medicine-count-badge">${medicines.length} ${medicines.length === 1 ? 'medicine' : 'medicines'}</span>
                    <button type="button" class="remove-slot-btn">
                        <i class="bi bi-x-lg"></i> Remove Slot
                    </button>
                </div>
                <div class="date-slot-body">
                    <div class="medicines-header">
                        <span>Medicine</span>
                        <span>Note</span>
                    </div>
                    <div class="medicine-rows-container">
                        ${medicineRows}
                    </div>
                </div>
                <button type="button" class="add-medicine-btn">
                    <i class="bi bi-plus-lg"></i> Add Medicine
                </button>
            `;

            // Wire up buttons via addEventListener (avoids onclick-before-script issues)
            slot.querySelector('.remove-slot-btn').addEventListener('click', function () {
                removeIndoorSlot(this);
            });
            slot.querySelector('.add-medicine-btn').addEventListener('click', function () {
                addMedicineRow(this, slotIndex);
            });
            slot.querySelectorAll('.delete-medicine-btn').forEach(btn => {
                btn.addEventListener('click', function () { deleteMedicineRow(this); });
            });

            container.appendChild(slot);
        }

        function addIndoorDateSlot() {
            createIndoorSlot('', '', [{ medicine: '', note: '' }]);
        }

        function addMedicineRow(btn, slotIndex) {
            const card = btn.closest('.date-slot-card');
            const rowsContainer = card.querySelector('.medicine-rows-container');

            const row = document.createElement('div');
            row.className = 'medicine-row';
            row.innerHTML = `
                <input type="text" name="slot_medicine[${slotIndex}][]" placeholder="Medicine name" autocomplete="off">
                <input type="text" name="slot_note[${slotIndex}][]" placeholder="Note">
                <button type="button" class="delete-medicine-btn" title="Remove">
                    <i class="bi bi-trash3-fill" style="font-size:12px;"></i>
                </button>
            `;
            row.querySelector('.delete-medicine-btn').addEventListener('click', function () {
                deleteMedicineRow(this);
            });

            rowsContainer.appendChild(row);
            updateMedicineCount(card);
            row.querySelector('input').focus();
        }

        function deleteMedicineRow(btn) {
            const card = btn.closest('.date-slot-card');
            const rowsContainer = card.querySelector('.medicine-rows-container');
            const rows = rowsContainer.querySelectorAll('.medicine-row');

            if (rows.length > 1) {
                btn.closest('.medicine-row').remove();
                updateMedicineCount(card);
            } else {
                btn.closest('.medicine-row').querySelectorAll('input').forEach(i => i.value = '');
            }
        }

        function removeIndoorSlot(btn) {
            const container = document.getElementById('indoorSlotsContainer');
            const slots = container.querySelectorAll('.date-slot-card');

            if (slots.length > 1) {
                if (confirm('Remove this date slot and all its medicines?')) {
                    btn.closest('.date-slot-card').remove();
                }
            } else {
                const card = btn.closest('.date-slot-card');
                card.querySelectorAll('input').forEach(i => i.value = '');
                updateMedicineCount(card);
            }
        }

        function updateMedicineCount(card) {
            const rows = card.querySelectorAll('.medicine-rows-container .medicine-row');
            const badge = card.querySelector('.medicine-count-badge');
            if (!badge) return;
            const count = rows.length;
            badge.textContent = count + (count === 1 ? ' medicine' : ' medicines');
        }

        function confirmDeleteSvc(btn, name, patientId) {
            Swal.fire({
                title: 'Delete Patient?',
                html: `Are you sure you want to delete <strong>${name}</strong> (${patientId})?<br><small class="text-danger">This will permanently delete the patient and all related data.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.closest('form').submit();
                }
            });
        }
    </script>
@endsection