@extends('admin.layouts.layouts')
@section('title', 'Diet Plan')
@section('content')

    <div class="container px-0">
        <div class="card shadow-sm mt-3 border-0 rounded-3">
            <div class="card-header bg-light p-3 border-bottom-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold" style="color: #197040">
                        <i class="fas fa-leaf me-2"></i> Create Diet Plan
                    </h5>
                    <div
                        class="badge rounded-pill bg-success-subtle text-success px-3 py-2 border border-success-subtle d-none d-md-block">
                        <i class="fas fa-magic me-1"></i> Nutritional Engine
                    </div>
                </div>
            </div>
            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <style>
                    label {
                        font-weight: 600;
                        color: #5a6268;
                        display: block;
                        margin-bottom: 4px;
                        font-size: 13px;
                    }

                    .form-control,
                    .form-select {
                        padding: 8px 12px;
                        font-size: 14px;
                        border-radius: 8px;
                        border: 1px solid #ced4da;
                    }

                    .search-menu-with-dropdown .form-control {
                        padding: 8px 12px;
                        font-size: 14px;
                    }

                    .meal-entry-card {
                        background: #fdfdfd;
                        border: 1px solid #e9ecef;
                        border-radius: 12px;
                        padding: 20px;
                        margin-bottom: 20px;
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
                    }

                    .btn-circle {
                        width: 36px;
                        height: 36px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border: none;
                        color: white;
                        font-size: 18px;
                        transition: all 0.2s;
                    }

                    .btn-circle-success {
                        background-color: #198754;
                    }

                    .btn-circle-danger {
                        background-color: #dc3545;
                    }

                    .btn-circle:hover {
                        transform: scale(1.1);
                        opacity: 0.9;
                    }

                    .recipe-chip {
                        display: inline-flex;
                        align-items: center;
                        background: white;
                        border: 1px solid #198754;
                        border-radius: 25px;
                        padding: 3px;
                        margin-right: 12px;
                        margin-bottom: 12px;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                    }

                    .recipe-name-tag {
                        background-color: #d1e7dd;
                        color: #0f5132;
                        padding: 5px 15px;
                        font-weight: 600;
                        font-size: 14px;
                        border-radius: 20px;
                        margin-right: 8px;
                    }

                    .recipe-qty-input {
                        border: 1px solid #ced4da !important;
                        border-radius: 6px !important;
                        padding: 4px 10px !important;
                        width: 90px !important;
                        font-size: 13px !important;
                        margin-right: 8px !important;
                        text-align: center;
                        outline: none;
                    }

                    .recipe-remove-btn {
                        background-color: #dc3545;
                        color: white !important;
                        border: none;
                        width: 24px;
                        height: 24px;
                        border-radius: 50%;
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 12px;
                        margin-right: 4px;
                        transition: background 0.2s;
                    }

                    .recipe-remove-btn:hover {
                        background-color: #a52834;
                    }

                    .add-item-btn {
                        background-color: #198754;
                        color: white;
                        border: none;
                        border-radius: 6px;
                        padding: 6px 15px;
                        font-size: 13px;
                        font-weight: 600;
                        margin-bottom: 12px;
                        transition: background 0.2s;
                    }

                    .add-item-btn:hover {
                        background-color: #146c43;
                    }

                    .add-item-btn i {
                        margin-right: 6px;
                    }

                    .selected-recipes-container {
                        margin-top: 15px;
                        display: flex;
                        flex-direction: column;
                        gap: 10px;
                        min-height: 45px;
                    }

                    .bg-success-subtle {
                        background-color: rgba(25, 135, 84, 0.1) !important;
                    }

                    .text-success {
                        color: #198754 !important;
                    }
                </style>


                <form id="dietForm">
                    <div class="row g-3 mb-3">
                        <!-- Select Branch -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Operational Branch</label>
                            <select class="form-control" id="branch_id" name="branch_id" required>
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->branch_id }}">
                                        {{ $branch->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($branches->isEmpty())
                                <div class="text-danger mt-2">
                                    No branches found. Please create branches first.
                                </div>
                            @endif
                        </div>

                        <!-- Search Patient -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Search Patient</label>
                            <input type="text" id="patientSearch" class="form-control" placeholder="Type name or ID..." disabled>
                        </div>

                        <!-- Select Patient -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Select Patient</label>
                            <select class="form-control" id="patient_id" name="patient_id" required disabled>
                                <option value="">Select Patient</option>
                            </select>
                            <div id="patient_loading" class="mt-2" style="display: none;">
                                <small class="text-muted">Loading patients...</small>
                            </div>
                            <div id="patient_info" class="mt-2" style="display: none;">
                                <small class="text-success">Patients loaded successfully</small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <!-- Date -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Activation Date</label>
                            <input type="date" class="form-control" id="date" name="date" value="{{ date('Y-m-d') }}"
                                required>
                        </div>

                        <!-- Diet Name -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Diet Name</label>
                            <div class="diet-name-autocomplete-wrapper">
                                <input type="text" class="form-control" id="diet_name" name="diet_name"
                                    placeholder="Enter diet name" required autocomplete="off">
                                <div class="diet-name-suggestions" id="diet-name-suggestions"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Time & Search Menu Section -->
                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <div class="search-menu-container">
                                <!-- Initial row -->
                                <div class="meal-entry-card search-menu-row" data-row="1">
                                    <div class="row g-3">
                                        <div class="col-md-2">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Time</label>
                                            <input type="time" class="form-control time-search"
                                                name="time_search_menus[0][time]">
                                        </div>

                                        <div class="col-md-9 search-menu-with-dropdown">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Search
                                                Menu</label>
                                            <input type="text" class="form-control search-menu"
                                                name="time_search_menus[0][search_menu]"
                                                placeholder="Search or type & press Enter" data-row="0" autocomplete="off"
                                                id="search-input-0">
                                            <div class="recipe-loader" id="recipe-loader-0">
                                                <i class="fas fa-spinner"></i>
                                            </div>
                                            <div class="recipe-dropdown" id="recipe-dropdown-0">
                                                <!-- Recipes will be loaded here -->
                                            </div>
                                            <div class="selected-recipes-container" id="selected-recipes-0">
                                                <div class="no-recipes-message">No recipes selected. Click above to select.
                                                </div>
                                            </div>
                                            <input type="hidden" class="selected-recipes-input"
                                                name="time_search_menus[0][selected_recipes]" id="selected-recipes-input-0"
                                                value="">
                                        </div>

                                        <div class="col-md-1 d-flex align-items-center justify-content-center gap-2"
                                            style="height: 100px;">
                                            <button type="button" class="btn-circle btn-circle-success"
                                                onclick="addTimeSearchRow()" title="Add meal">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <button type="button" class="btn-circle btn-circle-danger"
                                                onclick="removeTimeSearchRow(this)" title="Remove meal"
                                                style="display: none;">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>

        <div class="row g-3 mb-3 pt-3">
            <!-- General Notes -->
            <div class="col-md-6">
                <label class="form-label fw-bold small text-muted text-uppercase">General Observations</label>
                <textarea class="form-control" id="general_notes" name="general_notes" placeholder="Notes"
                    rows="3"></textarea>
            </div>

            <!-- Next Follow up Date -->
            <div class="col-md-6">
                <label class="form-label fw-bold small text-muted text-uppercase">Next Clinical Review</label>
                <input type="date" class="form-control" id="next_follow_up_date" name="next_follow_up_date">
            </div>
        </div>


        <div class="border-bottom mb-3"></div>
        <div class="text-end">
            <button type="button" class="btn btn-success px-5 fw-bold" id="submit_btn"
                style="background-color: #198754; border: none; border-radius: 8px; padding: 10px 30px;">
                Submit
            </button>
        </div>
        </form>
    </div>
    </div>
    </div>

    <style>
        /* Meal Entry Card Styling */
        .meal-entry-card {
            background: white;
            border: 1px solid #e3e6ea;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            transition: all 0.2s ease;
        }

        .meal-entry-card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
            border-color: #d1d7dd;
        }

        /* Button Action Group */
        .btn-action-group {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        /* Circular Buttons */
        .btn-circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 14px;
            padding: 0;
        }

        .btn-circle-success {
            background-color: #28a745;
            color: white;
        }

        .btn-circle-success:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .btn-circle-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-circle-danger:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }

        .btn-circle:disabled {
            background-color: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
            opacity: 0.5;
        }

        .btn-circle:disabled:hover {
            transform: none;
        }

        /* Recipe Dropdown */
        .recipe-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            max-height: 200px;
            overflow-y: auto;
            background: white;
            border: 1px solid #d1d7dd;
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
            margin-top: 2px;
        }

        .recipe-dropdown-item {
            padding: 10px 15px;
            cursor: pointer;
            transition: background 0.2s ease;
            border-bottom: 1px solid #f1f1f1;
            font-size: 14px;
        }

        .recipe-dropdown-item:last-child {
            border-bottom: none;
        }

        .recipe-dropdown-item:hover {
            background: #f8f9fa;
            color: #197040;
        }

        .search-menu-with-dropdown {
            position: relative;
        }

        .search-menu-with-dropdown input {
            cursor: pointer;
        }

        .recipe-loader {
            display: none;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        .recipe-loader i {
            color: #197040;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Selected Recipes */
        .selected-recipes-container {
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            min-height: 40px;
        }

        .recipe-chip {
            background: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            font-size: 14px;
            color: #333;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .recipe-chip-text {
            flex-grow: 1;
            font-weight: 500;
        }

        .recipe-chip-remove {
            background: #dc3545;
            color: white;
            border: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 10px;
            padding: 0;
            line-height: 1;
        }

        .recipe-chip-remove:hover {
            background: #c82333;
        }

        .no-recipes-message {
            color: #6c757d;
            font-style: italic;
            font-size: 13px;
            padding: 5px 0;
        }

        /* Form Controls */
        .form-control {
            border-radius: 6px;
            border: 1px solid #d1d7dd;
        }

        .form-control:focus {
            border-color: #197040;
            box-shadow: 0 0 0 0.2rem rgba(25, 112, 64, 0.15);
        }

        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 6px;
        }

        /* Quantity History Dropdown Styling */
        .quantity-dropdown-container {
            position: relative;
        }

        .quantity-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            max-height: 180px;
            overflow-y: auto;
            background: #ffffff;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 10000;
            display: none;
            margin-top: 4px;
        }

        .quantity-dropdown-item {
            padding: 10px 15px;
            cursor: pointer;
            transition: all 0.15s ease;
            border-bottom: 1px solid #f0f0f0;
            font-size: 13px;
            color: #333333;
        }

        .quantity-dropdown-item:last-child {
            border-bottom: none;
        }

        .quantity-dropdown-item:hover {
            background: #f8f9fa;
            color: #197040;
        }

        /* Diet Name Autocomplete Dropdown */
        .diet-name-autocomplete-wrapper {
            position: relative;
        }

        .diet-name-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 9999;
            background: #ffffff;
            border: 1px solid #ced4da;
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
            max-height: 200px;
            overflow-y: auto;
            display: none;
            margin-top: 0;
        }

        .diet-name-suggestion-item {
            padding: 10px 14px;
            cursor: pointer;
            font-size: 13.5px;
            color: #333;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.15s ease;
        }

        .diet-name-suggestion-item:last-child {
            border-bottom: none;
        }

        .diet-name-suggestion-item:hover {
            background: #f0faf4;
            color: #197040;
        }

        .diet-name-suggestion-item i {
            color: #aaa;
            font-size: 12px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Set today's date as default
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date').value = today;

            // Initialize recipe dropdown for first row
            initializeRecipeDropdown(0);

            // ===== Diet Name Autocomplete =====
            (function initDietNameAutocomplete() {
                const dietNameInput = document.getElementById('diet_name');
                const suggestionsBox = document.getElementById('diet-name-suggestions');
                let acTimeout = null;

                dietNameInput.addEventListener('input', function () {
                    clearTimeout(acTimeout);
                    const q = this.value.trim();
                    if (q.length === 0) {
                        suggestionsBox.style.display = 'none';
                        suggestionsBox.innerHTML = '';
                        return;
                    }
                    acTimeout = setTimeout(function () {
                        $.get('{{ route("diet.getDietNameSuggestions") }}', { q: q }, function (res) {
                            suggestionsBox.innerHTML = '';
                            if (res.success && res.suggestions.length > 0) {
                                res.suggestions.forEach(function (name) {
                                    const item = document.createElement('div');
                                    item.className = 'diet-name-suggestion-item';
                                    item.innerHTML = '<i class="fas fa-history"></i> ' + name;
                                    item.addEventListener('mousedown', function (e) {
                                        e.preventDefault();
                                        dietNameInput.value = name;
                                        suggestionsBox.style.display = 'none';
                                        suggestionsBox.innerHTML = '';
                                    });
                                    suggestionsBox.appendChild(item);
                                });
                                suggestionsBox.style.display = 'block';
                            } else {
                                suggestionsBox.style.display = 'none';
                            }
                        });
                    }, 300);
                });

                dietNameInput.addEventListener('blur', function () {
                    setTimeout(function () {
                        suggestionsBox.style.display = 'none';
                    }, 200);
                });

                document.addEventListener('click', function (e) {
                    if (!dietNameInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                        suggestionsBox.style.display = 'none';
                    }
                });
            })();
            // ===== End Diet Name Autocomplete =====

            let searchTimeout;

            function fetchPatients() {
                const branchId = $('#branch_id').val();
                const searchQuery = $('#patientSearch').val();
                const patientSelect = $('#patient_id');
                const loadingDiv = $('#patient_loading');
                const infoDiv = $('#patient_info');

                if (!branchId) {
                    patientSelect.prop('disabled', true);
                    patientSelect.html('<option value="">Select Branch First</option>');
                    $('#patientSearch').prop('disabled', true);
                    loadingDiv.hide();
                    infoDiv.hide();
                    return;
                }

                // Enable fields and show loading
                patientSelect.prop('disabled', false);
                $('#patientSearch').prop('disabled', false);
                patientSelect.html('<option value="">Searching...</option>');
                loadingDiv.show();
                infoDiv.hide();

                $.ajax({
                    url: '{{ route("diet.getPatientsByBranch") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        branch_id: branchId,
                        search: searchQuery,
                        force_patient_id: new URLSearchParams(window.location.search).get('patient_id')
                    },
                    success: function (response) {
                        if (response.success) {
                            if (response.patients && response.patients.length > 0) {
                                patientSelect.html('<option value="">Select Patient</option>');
                                $.each(response.patients, function (index, patient) {
                                    const rawName = (patient.patient_name || '').toString().trim();
                                    const rawId = (patient.patient_id || '').toString().trim();
                                    let displayText = rawName !== '' ? rawName : (rawId !== '' ? ('Patient (ID: ' + rawId + ')') : 'Patient');
                                    if (rawName !== '' && rawId !== '') {
                                        displayText += ' (ID: ' + rawId + ')';
                                    }

                                    patientSelect.append(
                                        $('<option></option>')
                                            .val(patient.patient_id)
                                            .text(displayText)
                                            .attr('title', rawName !== '' ? rawName : (rawId !== '' ? rawId : 'Patient'))
                                            .data('patient-name', rawName !== '' ? rawName : displayText)
                                    );
                                });
                                infoDiv.text('Found ' + response.patients.length + ' patients').show();
                                
                                const urlParams = new URLSearchParams(window.location.search);
                                const patientIdParam = urlParams.get('patient_id');
                                if (patientIdParam) {
                                    patientSelect.val(patientIdParam).trigger('change');
                                }
                            } else {
                                patientSelect.html('<option value="">No patients found</option>');
                                infoDiv.text('No patients found').addClass('text-warning').show();
                            }
                        } else {
                            patientSelect.html('<option value="">Error loading patients</option>');
                            infoDiv.text('Error: ' + response.message).addClass('text-danger').show();
                        }
                        loadingDiv.hide();
                    },
                    error: function () {
                        patientSelect.html('<option value="">Error loading patients</option>');
                        infoDiv.text('Server error.').addClass('text-danger').show();
                        loadingDiv.hide();
                    }
                });
            }

            // Branch selection event
            $('#branch_id').change(function () {
                $('#patientSearch').val(''); // Reset search
                fetchPatients();
            });

            // Search input event
            $('#patientSearch').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(fetchPatients, 500);
            });



            // Submit form functionality
            $('#submit_btn').click(function () {
                const branchId = $('#branch_id').val();
                const patientId = $('#patient_id').val();
                const date = $('#date').val();
                const dietName = $('#diet_name').val();

                if (!branchId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validation Error',
                        text: 'Please select a branch'
                    });
                    return;
                }

                if (!patientId || patientId === '' || patientId.includes('Select')) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validation Error',
                        text: 'Please select a patient'
                    });
                    return;
                }

                if (!date) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validation Error',
                        text: 'Please select a date'
                    });
                    return;
                }

                if (!dietName.trim()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validation Error',
                        text: 'Please enter diet name'
                    });
                    return;
                }

                const timeSearchMenus = [];
                $('.search-menu-row').each(function (index) {
                    const time = $(this).find('.time-search').val();
                    const hiddenInput = $(this).find('.selected-recipes-input');
                    const selectedRecipesRaw = hiddenInput.val() || '';
                    const searchMenuInput = $(this).find('.search-menu').val();
                    const quantity = $(this).find('.quantity-input').val();
                    const notes = $(this).find('textarea').val();

                    if (time || selectedRecipesRaw || searchMenuInput || quantity || notes) {
                        // Parse the JSON recipes array stored in the hidden input
                        let recipesArray = [];
                        try {
                            const parsed = JSON.parse(selectedRecipesRaw);
                            if (Array.isArray(parsed)) recipesArray = parsed;
                        } catch(e) {
                            // Not JSON — treat as plain text
                        }

                        timeSearchMenus.push({
                            time: time || '',
                            search_menu: searchMenuInput || '',
                            selected_recipes: selectedRecipesRaw || '',
                            recipes: recipesArray,
                            quantity: quantity || '',
                            notes: notes || ''
                        });
                    }
                });

                const formData = {
                    _token: '{{ csrf_token() }}',
                    branch_id: branchId,
                    patient_id: patientId,
                    date: date,
                    diet_name: dietName,
                    general_notes: $('#general_notes').val(),
                    next_follow_up_date: $('#next_follow_up_date').val(),
                    diet: $('#diet').val() || '',
                    exercise: $('#exercise').val() || '',
                    sleep: $('#sleep').val() || '',
                    water: $('#water').val() || '',
                    time_search_menus: timeSearchMenus
                };

                console.log('Form Data for submission:', formData);

                const submitBtn = $(this);
                const originalText = submitBtn.text();
                submitBtn.prop('disabled', true).text('Saving...');

                $.ajax({
                    url: '{{ route("diet.plan.store") }}',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(formData),
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Diet plan created successfully!',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = response.redirect;
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Error: ' + response.message
                            });
                            submitBtn.prop('disabled', false).text(originalText);
                        }
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr);
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage
                        });
                        submitBtn.prop('disabled', false).text(originalText);
                    }
                });
            });

            updateRemoveButtons();

            // Initialize dropdowns for first row
            initializeRecipeDropdown(0);
            initializeQuantityDropdown(0);
        });

        let rowCount = 1;
        $(document).ready(function () {
            const urlParams = new URLSearchParams(window.location.search);
            const branchIdParam = urlParams.get('branch_id');
            const patientIdParam = urlParams.get('patient_id');

            console.log('Checking URL params:', { branchIdParam, patientIdParam });

            if (branchIdParam) {
                const branchSelect = $('#branch_id');
                if (branchSelect.find(`option[value="${branchIdParam}"]`).length) {
                    branchSelect.val(branchIdParam).trigger('change');
                } else {
                    console.warn('Branch ID from URL not found in dropdown:', branchIdParam);
                }
            } else if (patientIdParam) {
                // If only patient ID is provided, we can't easily select the branch
                // but we could try to look it up or select first branch
                const branchSelect = $('#branch_id');
                const options = branchSelect.find('option');
                if (options.length > 1) {
                    branchSelect.val(options.eq(1).val()).trigger('change');
                }
            }
        });
        function addTimeSearchRow() {
            rowCount++;
            const container = document.querySelector('.search-menu-container');
            const newRow = document.createElement('div');
            newRow.className = 'meal-entry-card search-menu-row';
            newRow.setAttribute('data-row', rowCount);

            newRow.innerHTML = `
                                <div class="row g-3 align-items-start">
                                    <div class="col-md-2">
                                        <label class="form-label">Time</label>
                                        <input type="time" class="form-control time-search" name="time_search_menus[${rowCount - 1}][time]">
                                    </div>
                                    <div class="col-md-9 search-menu-with-dropdown">
                                        <label class="form-label">Search Menu</label>
                                        <input type="text" class="form-control search-menu"
                                               name="time_search_menus[${rowCount - 1}][search_menu]"
                                               placeholder="Search or type & press Enter"
                                               data-row="${rowCount - 1}"
                                               autocomplete="off"
                                               id="search-input-${rowCount - 1}">
                                        <div class="recipe-loader" id="recipe-loader-${rowCount - 1}">
                                            <i class="fas fa-spinner"></i>
                                        </div>
                                        <div class="recipe-dropdown" id="recipe-dropdown-${rowCount - 1}">
                                            <!-- Recipes will be loaded here -->
                                        </div>
                                        <div class="selected-recipes-container" id="selected-recipes-${rowCount - 1}">
                                            <div class="no-recipes-message">No recipes selected. Click above to select.</div>
                                        </div>
                                        <input type="hidden" class="selected-recipes-input" name="time_search_menus[${rowCount - 1}][selected_recipes]" id="selected-recipes-input-${rowCount - 1}" value="">
                                    </div>
                                    <div class="col-md-1 d-flex align-items-center justify-content-center gap-2" style="height: 100px;">
                                        <button type="button" class="btn-circle btn-circle-success" onclick="addTimeSearchRow()" title="Add meal">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button type="button" class="btn-circle btn-circle-danger" onclick="removeTimeSearchRow(this)" title="Remove meal">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                            `;

            container.appendChild(newRow);
            updateRemoveButtons();
            initializeRecipeDropdown(rowCount - 1);
        }

        function removeTimeSearchRow(button) {
            const row = button.closest('.search-menu-row');
            if (document.querySelectorAll('.search-menu-row').length > 1) {
                row.remove();
                updateRemoveButtons();
                $('.search-menu-row').each(function (index) {
                    $(this).find('.time-search').attr('name', `time_search_menus[${index}][time]`);
                    $(this).find('.search-menu').attr('name', `time_search_menus[${index}][search_menu]`);
                    $(this).find('.quantity-input').attr('name', `time_search_menus[${index}][quantity]`);
                    $(this).find('textarea').attr('name', `time_search_menus[${index}][notes]`);
                });
            }
        }

        function updateRemoveButtons() {
            const rows = document.querySelectorAll('.search-menu-row');

            rows.forEach((row, index) => {
                const removeButton = row.querySelector('.btn-circle-danger');

                if (rows.length === 1) {
                    // Hide remove button on the only row
                    if (removeButton) {
                        removeButton.style.display = 'none';
                    }
                } else {
                    // Show remove button on all rows when there's more than one
                    if (removeButton) {
                        removeButton.style.display = 'flex';
                    }
                }
            });
        }

        // --- Quantity History Logic ---
        let quantityHistory = JSON.parse(localStorage.getItem('diet_quantity_history')) || ['1 bowl', '1 plate', '1 glass', '100g', '200g', '1 cup'];

        function initializeQuantityDropdown(rowIndex) {
            const input = $(`#quantity-input-${rowIndex}`);
            const dropdown = $(`#quantity-dropdown-${rowIndex}`);

            function showSuggestions() {
                const val = input.val().trim().toLowerCase();
                dropdown.empty();

                // Filter history based on what user is typing
                let matches = quantityHistory.filter(item =>
                    item.toLowerCase().includes(val)
                );

                if (matches.length > 0) {
                    matches.forEach(match => {
                        const item = $('<div class="quantity-dropdown-item"></div>')
                            .text(match)
                            .on('mousedown', function (e) { // Use mousedown to trigger before blur
                                e.preventDefault();
                                input.val(match);
                                dropdown.hide();
                            });
                        dropdown.append(item);
                    });
                    dropdown.show();
                } else {
                    dropdown.hide();
                }
            }

            input.on('focus input', showSuggestions);

            input.on('blur', function () {
                const val = $(this).val().trim();
                // Save to history if it's a new non-empty value
                if (val && !quantityHistory.some(h => h.toLowerCase() === val.toLowerCase())) {
                    quantityHistory.unshift(val);
                    // Keep history manageable (last 20 items)
                    if (quantityHistory.length > 20) quantityHistory.pop();
                    localStorage.setItem('diet_quantity_history', JSON.stringify(quantityHistory));
                }
                dropdown.hide();
            });

            // Close dropdown when clicking outside
            $(document).on('click', function (e) {
                if (!$(e.target).closest(`#quantity-input-${rowIndex}`).length &&
                    !$(e.target).closest(`#quantity-dropdown-${rowIndex}`).length) {
                    dropdown.hide();
                }
            });
        }

        function initializeRecipeDropdown(rowIndex) {
            const searchInput = $(`#search-input-${rowIndex}`);
            const dropdown = $(`#recipe-dropdown-${rowIndex}`);
            const loader = $(`#recipe-loader-${rowIndex}`);
            const selectedRecipesContainer = $(`#selected-recipes-${rowIndex}`);
            const hiddenInput = $(`#selected-recipes-input-${rowIndex}`);
            let allRecipes = [];
            let selectedRecipes = []; // Array of {name: string, qty: any}

            function updateDropdownDisplay() {
                const items = dropdown.find('.recipe-dropdown-item');
                items.each(function () {
                    const recipeName = $(this).data('value');
                    const isSelected = selectedRecipes.some(r => r.name === recipeName);
                    if (isSelected) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
            }

            function updateSelectedRecipesDisplay() {
                selectedRecipesContainer.empty();

                if (selectedRecipes.length === 0) {
                    selectedRecipesContainer.append('<div class="no-recipes-message">No recipes selected. Click above to select.</div>');
                } else {
                    selectedRecipes.forEach(function (recipe, index) {
                        const chip = $(`
                            <div class="recipe-chip p-2 mb-2 bg-light border rounded w-100" style="display: block;">
                                <div class="row align-items-center g-2 m-0">
                                    <div class="col-md-4">
                                        <span class="recipe-chip-text fw-bold text-success" title="${recipe.name}">${recipe.name}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control recipe-qty-input" 
                                            data-index="${index}" value="${recipe.qty || ''}" 
                                            placeholder="Quantity (e.g. 1 cup)" style="font-size: 13px;">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control recipe-notes-input" 
                                            data-index="${index}" value="${recipe.notes || ''}" 
                                            placeholder="Notes" style="font-size: 13px;">
                                    </div>
                                    <div class="col-md-1 text-end">
                                        <button type="button" class="btn btn-sm btn-danger recipe-chip-remove d-inline-flex align-items-center justify-content-center" data-index="${index}" title="Remove item" style="width: 28px; height: 28px; border-radius: 50%; padding: 0;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `);
                        selectedRecipesContainer.append(chip);
                    });

                    // Add "Add Item" button style element as a cue
                    const addItemBtn = $(`
                                        <button type="button" class="add-item-btn" onclick="$('#search-input-${rowIndex}').focus()">
                                            <i class="fas fa-plus"></i> Add item
                                        </button>
                                    `);
                    selectedRecipesContainer.append(addItemBtn);
                }

                hiddenInput.val(JSON.stringify(selectedRecipes));
                updateDropdownDisplay();
            }

            function addRecipeToSelected(recipeName, isCustom) {
                // Add if not already there
                if (!selectedRecipes.some(r => r.name.toLowerCase() === recipeName.toLowerCase())) {
                    selectedRecipes.push({ name: recipeName, qty: '', unit: '', custom: isCustom ? true : false });
                    updateSelectedRecipesDisplay();
                }

                // ✅ Sync Input Box: Sabhi selected items ko space ke saath dikhao
                const currentItemsText = selectedRecipes.map(r => r.name).join(' ') + ' ';
                searchInput.val(currentItemsText);
                searchInput.focus();

                dropdown.hide();
            }

            function removeRecipeFromSelected(index) {
                const removedRecipe = selectedRecipes[index];
                selectedRecipes.splice(index, 1);
                updateSelectedRecipesDisplay();
                dropdown.show();
            }

            selectedRecipesContainer.on('click', '.recipe-chip-remove', function () {
                const index = $(this).data('index');
                removeRecipeFromSelected(index);
            });

            selectedRecipesContainer.on('input', '.recipe-qty-input', function () {
                const index = $(this).data('index');
                const qty = $(this).val();
                if (index !== undefined && selectedRecipes[index]) {
                    selectedRecipes[index].qty = qty;
                    hiddenInput.val(JSON.stringify(selectedRecipes));
                }
            });

            selectedRecipesContainer.on('input', '.recipe-notes-input', function () {
                const index = $(this).data('index');
                const notes = $(this).val();
                if (index !== undefined && selectedRecipes[index]) {
                    selectedRecipes[index].notes = notes;
                    hiddenInput.val(JSON.stringify(selectedRecipes));
                }
            });

            searchInput.on('focus', function () {
                if (allRecipes.length === 0) {
                    loader.show();

                    $.ajax({
                        url: '{{ route("diet.getRecipes") }}',
                        type: 'GET',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            loader.hide();

                            if (response.success && response.recipes && response.recipes.length > 0) {
                                dropdown.empty();
                                allRecipes = response.recipes.map(recipe => recipe.name);

                                response.recipes.forEach(function (recipe) {
                                    const item = $('<div class="recipe-dropdown-item"></div>')
                                        .text(recipe.name)
                                        .attr('data-value', recipe.name)
                                        .click(function () {
                                            addRecipeToSelected(recipe.name);
                                        });
                                    dropdown.append(item);
                                });

                                updateDropdownDisplay();
                                dropdown.show();
                            } else {
                                const noResults = $('<div class="recipe-dropdown-item">No recipes found</div>');
                                dropdown.empty().append(noResults).show();
                            }
                        },
                        error: function () {
                            loader.hide();
                            const errorItem = $('<div class="recipe-dropdown-item text-danger">Error loading recipes</div>');
                            dropdown.empty().append(errorItem).show();
                        }
                    });
                } else {
                    dropdown.show();
                }
            });

            $(document).on('click', function (e) {
                if (!$(e.target).closest(`#search-input-${rowIndex}`).length &&
                    !$(e.target).closest(`#recipe-dropdown-${rowIndex}`).length &&
                    !$(e.target).closest(`#selected-recipes-${rowIndex}`).length &&
                    !$(e.target).hasClass('recipe-chip-remove') &&
                    !$(e.target).closest('.recipe-chip-remove').length) {
                    dropdown.hide();
                }
            });

            searchInput.on('input', function () {
                const fullVal = $(this).val();
                // ✅ Search logic: Sirf aakhri space ke baad wala word search karo
                const parts = fullVal.split(' ');
                const searchTerm = parts[parts.length - 1].trim();
                const termLower = searchTerm.toLowerCase();

                // Clear previous add-option
                dropdown.find('.recipe-custom-add').remove();

                // Agar aakhri word khali hai (matlab space press hua hai)
                if (!searchTerm) {
                    if (allRecipes.length > 0) {
                        updateDropdownDisplay();
                        dropdown.show();
                    }
                    return;
                }

                if (allRecipes.length > 0) {
                    const items = dropdown.find('.recipe-dropdown-item:not(.recipe-custom-add)');
                    let foundAny = false;

                    items.each(function () {
                        const itemText = $(this).text().toLowerCase();
                        // Dropdown mein wo item na dikhao jo already select ho chuka hai
                        const isAlready = selectedRecipes.some(r => r.name.toLowerCase() === $(this).data('value').toLowerCase());

                        if (itemText.includes(termLower) && !isAlready) {
                            $(this).show();
                            foundAny = true;
                        } else {
                            $(this).hide();
                        }
                    });

                    // Agar dropdown mein nahi hai, toh "Add new" option dikhao
                    const existsInDB = allRecipes.some(r => r.toLowerCase() === termLower);
                    if (!existsInDB) {
                        const btn = $(`<div class="recipe-dropdown-item recipe-custom-add" 
                                            style="color:#197040;font-weight:600;border-top:1px solid #dee2e6;background:#f0fff4;">
                                            <i class="fas fa-plus-circle me-2"></i>Add "${searchTerm}" as new item
                                        </div>`);
                        btn.on('click', function () {
                            addRecipeToSelected(searchTerm, true);
                        });
                        dropdown.append(btn);
                    }

                    dropdown.show();
                } else {
                    // Loader check or fallback
                    const btn = $(`<div class="recipe-dropdown-item recipe-custom-add" style="color:#197040;font-weight:600;background:#f0fff4;">
                                        <i class="fas fa-plus-circle me-2"></i>Add "${searchTerm}" as new item
                                    </div>`);
                    btn.on('click', function () {
                        addRecipeToSelected(searchTerm, true);
                    });
                    dropdown.empty().append(btn).show();
                }
            });

            searchInput.on('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const fullVal = $(this).val();
                    const parts = fullVal.split(' ');
                    const value = parts[parts.length - 1].trim();

                    if (!value) return;

                    // Existing recipe?
                    const exactMatch = allRecipes.find(r => r.toLowerCase() === value.toLowerCase());
                    if (exactMatch) {
                        addRecipeToSelected(exactMatch, false);
                    } else {
                        addRecipeToSelected(value, true);
                    }
                }

                // ✅ Space press logic
                if (e.key === ' ') {
                    // Agar input empty ya sirf space hai, dropdown kholo
                    if (!$(this).val().trim()) {
                        e.preventDefault();
                        if (allRecipes.length > 0) {
                            updateDropdownDisplay();
                            dropdown.show();
                        }
                    }
                }
            });

            searchInput.on('click', function () {
                if ($(this).attr('readonly')) {
                    $(this).removeAttr('readonly');
                }
            });

            updateSelectedRecipesDisplay();
        }
    </script>

@endsection