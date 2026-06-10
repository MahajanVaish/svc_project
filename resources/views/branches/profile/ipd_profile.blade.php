@extends('admin.layouts.layouts')

@section('title', 'IPD Patient Profile')

@section('content')
<style>
    .profile-container {
        max-width: 1742px;
        margin: 0 auto;
        padding: 40px;
        background: var(--bg-main);
        min-height: 100vh;
    }

    .profile-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border-subtle);
    }

    .profile-title {
        font-size: 20px;
        font-weight: bold;
        color: var(--text-primary);
    }

    .profile-content {
        display: flex;
        gap: 30px;
        margin-bottom: 30px;
    }

    .profile-sidebar {
        flex: 1;
        background: var(--bg-card);
        padding: 25px;
        border-radius: 10px;
        box-shadow: var(--shadow-md);
        text-align: center;
        color: var(--text-primary);
    }

    .profile-main {
        flex: 2;
        background: var(--bg-card);
        padding: 25px;
        border-radius: 10px;
        box-shadow: var(--shadow-md);
        color: var(--text-primary);
        max-height: 800px;
        overflow-y: auto;
    }

    .profile-image {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-image svg {
        width: 200px;
        height: 200px;
        fill: #6c757d;
    }

    .patient-info {
        text-align: left;
        margin-bottom: 25px;
    }

    .info-group {
        margin-bottom: 15px;
    }

    .info-label {
        font-size: 12px;
        color: var(--text-muted);
        font-weight: bold;
        margin-bottom: 5px;
        text-transform: uppercase;
    }

    .info-value {
        font-size: 16px;
        color: var(--text-primary);
        font-weight: 500;
    }

    .edit-profile-btn {
        background: #28a745;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        margin-top: 10px;
    }
    .edit-profile-btn:hover {
        background: #006637;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        text-decoration: none;
        color: white;
    }
    .edit-profile-align-btn {
        display: flex;
        justify-content: end;
        align-items: end;
    }
    .section-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0px !important;
    }

    .data-table {
        width: 100%;
        margin-bottom: 15px;
        font-size: 14px;
        white-space: nowrap;
    }

    .data-table th {
        padding: 12px 8px;
        text-align: left;
        font-weight: bold;
        color: white;
        background: #006637;
        font-size: 16px;
    }

    .data-table td {
        border-bottom: 1px solid var(--border-subtle);
        padding: 12px 8px;
        text-align: left;
        font-size: 14px;
        color: var(--text-primary);
    }

    .data-table tr:nth-child(even) {
        background: var(--bg-main);
    }

    .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
    }

    .pagination-info {
        color: #6c757d;
        font-size: 13px;
    }

    .pagination-buttons {
        display: flex;
        gap: 8px;
    }

    .pagination-btn {
        background: #6c757d;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 11px;
        text-decoration: none;
        display: inline-block;
    }

    .pagination-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
    }

    .pagination-btn:hover:not(:disabled) {
        background: #5a6268;
        color: white;
    }

    .patient-type {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .type-badge {
        background: #e9ecef;
        color: #495057;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
    }

    .back-button {
        background: #6c757d;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 20px;
    }

    .back-button:hover {
        background: #5a6268;
        color: white;
    }

    .empty-data {
        text-align: center;
        color: #6c757d;
        font-style: italic;
        padding: 20px;
        font-size: 14px;
    }

    .section-divider {
        border-top: 1px solid #dee2e6;
        margin: 20px 0;
    }

    .medicine-prescription {
        font-size: 13px;
        line-height: 1.6;
        white-space: normal !important;
    }

    .prescription-item {
        margin-bottom: 12px;
        padding: 12px;
        border: 1px solid var(--border-subtle);
        border-radius: 8px;
        background: var(--bg-main);
    }

    .prescription-item:last-child {
        margin-bottom: 0;
    }

    .medicine-name {
        font-weight: bold;
        color: var(--accent-solid);
        margin-bottom: 8px;
        font-size: 14px;
        padding-bottom: 5px;
        border-bottom: 1px dashed var(--border-subtle);
    }

    .medicine-detail {
        display: flex;
        align-items: center;
        margin-bottom: 4px;
        color: #495057;
    }

    .medicine-label {
        font-weight: 600;
        min-width: 60px;
        color: #6c757d;
    }

    .medicine-value {
        margin-left: 8px;
    }

    .medicine-note {
        color: #ff4d4d;
        font-size: 12px;
        margin-top: 6px;
        padding: 8px;
        background: rgba(220, 53, 69, 0.1);
        border-radius: 4px;
        border-left: 3px solid #dc3545;
    }

    .lab-table-container {
        overflow-x: auto;
        margin-bottom: 15px;
    }

    .follow-up-btn {
        background: #28a745;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .follow-up-btn:hover {
        background: #006637;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        text-decoration: none;
        color: white;
    }

    .treatment-section {
        margin-bottom: 25px;
    }

    .treatment-icon {
        margin-right: 8px;
        font-size: 16px;
    }

    .no-treatment {
        text-align: center;
        color: var(--text-muted);
        font-style: italic;
        padding: 30px;
        background: var(--bg-main);
        border-radius: 8px;
        border: 2px dashed var(--border-subtle);
    }

    .compact-table {
        font-size: 12px;
    }

    .compact-table th,
    .compact-table td {
        padding: 12px 15px;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .action-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 16px;
        padding: 5px;
        border-radius: 4px;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
    }

    .edit-btn {
        color: var(--color-primary);
    }

    .edit-btn:hover {
        color: var(--color-primary);
        background: rgba(37, 99, 235, 0.15);
    }

    .delete-btn {
        color: var(--color-danger);
    }

    .delete-btn:hover {
        color: var(--color-danger);
        background: rgba(239, 68, 68, 0.15);
    }

    .btn-zoom-start,
    .btn-zoom-join,
    .btn-copy,
    .btn-zoom-create {
        color: var(--icon-on-color);
    }
    .btn-zoom-start { background: var(--color-success); }
    .btn-zoom-join { background: var(--color-primary); }
    .btn-copy { background: var(--color-info); }
    .btn-zoom-create { background: var(--text-secondary); color: var(--icon-on-color); }
    .action-btn:hover { filter: brightness(1.05); transform: translateY(-1px); }

    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: background 0.3s;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background: #c82333;
    }

    .empty-field {
        color: #6c757d;
        font-style: italic;
    }

    .delete-form {
        display: inline;
        margin: 0;
        padding: 0;
    }

    .profile_txt_color {
        color: var(--accent-solid);
    }

    .fnf-title {
        font-weight: 600;
        color: var(--accent-solid);
        padding-bottom: 0;
        line-height: 1.3em;
        margin-bottom: 0px !important;
        font-size: 20px !important;
    }

    .data-table thead th {
        background-color: #006637 !important;
        color: white;
    }

    .profile-image-wrapper {
        position: relative;
        display: inline-block;
        margin-bottom: 15px;
    }

    .profile-image-container {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid var(--border-subtle);
        background: var(--bg-main);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .profile-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-image-container i {
        font-size: 60px;
        color: #adb5bd;
    }

    .upload-label {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: #007bff;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 2px solid white;
        transition: all 0.2s;
    }

    .upload-label:hover {
        background: #0056b3;
        transform: scale(1.1);
    }

    .save-profile-btn {
        background: #007bff;
        color: white;
        border: none;
        padding: 5px 15px;
        border-radius: 4px;
        font-size: 13px;
        margin-top: 10px;
        display: none;
    }

    /* ===== INDOOR TREATMENT MODAL REDESIGN ===== */
    #indoorTreatmentModal .modal-dialog {
        max-width: 860px !important;
    }

    #indoorTreatmentModal .modal-content {
        border-radius: 16px;
        overflow: hidden;
        border: none;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }

    #indoorTreatmentModal .modal-header {
        background: #fff;
        border-bottom: 1px solid #e9ecef;
        padding: 20px 28px;
    }

    #indoorTreatmentModal .modal-title {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    #indoorTreatmentModal .modal-title i {
        color: #006637;
        font-size: 20px;
    }

    #indoorTreatmentModal .modal-body {
        background: #f8f9fa;
        padding: 24px 28px;
        overflow-y: auto !important;
        max-height: 68vh;
    }

    #indoorTreatmentModal .modal-footer {
        background: #fff;
        border-top: 1px solid #e9ecef;
        padding: 16px 28px;
    }

    /* Patient Info Card inside modal */
    .indoor-patient-info {
        background: #f0f7f2;
        border: 1px solid #c8e6d4;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
        display: grid;
        grid-template-columns: 1fr 1fr;
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
        background: transparent;
        border: 1px solid #006637;
        color: #006637;
        font-weight: 600;
        font-size: 13px;
        padding: 8px 18px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 18px;
    }

    .add-slot-btn:hover {
        background: #f0f7f2;
    }

    /* Date Slot Card */
    .date-slot-card {
        background: #fff;
        border-radius: 12px;
        overflow: visible;
        margin-bottom: 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .date-slot-header {
        background: #006637;
        color: white;
        padding: 11px 18px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-radius: 12px 12px 0 0;
        flex-wrap: wrap;
    }

    .date-slot-header label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin: 0;
        white-space: nowrap;
    }

    .date-slot-header input[type="date"],
    .date-slot-header input[type="time"] {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.4);
        color: white;
        border-radius: 6px;
        padding: 5px 10px;
        font-size: 13px;
        outline: none;
        max-width: 155px;
    }

    .date-slot-header input[type="date"]:focus,
    .date-slot-header input[type="time"]:focus {
        border-color: rgba(255,255,255,0.9);
        background: rgba(255,255,255,0.25);
    }

    .date-slot-header input[type="date"]::-webkit-calendar-picker-indicator,
    .date-slot-header input[type="time"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
        cursor: pointer;
    }

    .date-slot-header input::placeholder {
        color: rgba(255,255,255,0.65);
    }

    .slot-at-separator {
        color: rgba(255,255,255,0.75);
        font-size: 13px;
        font-weight: 500;
    }

    .medicine-count-badge {
        background: rgba(255,255,255,0.22);
        color: white;
        border-radius: 20px;
        padding: 3px 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .remove-slot-btn {
        margin-left: auto;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.45);
        color: white;
        border-radius: 6px;
        padding: 5px 13px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .remove-slot-btn:hover {
        background: rgba(220,53,69,0.65);
        border-color: rgba(220,53,69,0.8);
    }

    /* Medicine rows */
    .date-slot-body {
        padding: 14px 18px 16px;
    }

    .medicines-header {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        padding-bottom: 6px;
        margin-bottom: 6px;
        border-bottom: 1px solid #f0f0f0;
    }

    .medicines-header span {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
    }

    .medicine-row {
        display: grid;
        grid-template-columns: 1fr 1fr 36px;
        gap: 10px;
        margin-bottom: 8px;
        align-items: center;
    }

    .medicine-row input {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 7px 10px;
        font-size: 13px;
        width: 100%;
        color: #333;
        background: #fff;
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
        width: 32px;
        height: 32px;
        background: #fff0f0;
        border: 1px solid #ffcccc;
        color: #dc3545;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .delete-medicine-btn:hover {
        background: #dc3545;
        color: white;
        border-color: #dc3545;
    }

    /* Add Medicine Button */
    .add-medicine-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: transparent;
        border: 1.5px solid #006637;
        color: #006637;
        font-size: 12px;
        font-weight: 600;
        padding: 5px 14px;
        border-radius: 6px;
        cursor: pointer;
        margin-top: 6px;
        transition: all 0.2s;
    }

    .add-medicine-btn:hover {
        background: #f0f7f2;
    }

    /* Modal footer buttons */
    .btn-cancel-indoor {
        background: #f8f9fa;
        color: #6c757d;
        border: 1px solid #dee2e6;
        padding: 9px 22px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-cancel-indoor:hover {
        background: #e9ecef;
    }

    .btn-save-indoor {
        background: #006637;
        color: white;
        border: none;
        padding: 9px 26px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-save-indoor:hover {
        background: #004d29;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,102,55,0.3);
    }
</style>

@php
function formatValue($value) {
    return ($value === null || $value === 'null' || $value === '' || $value === '0.00' || $value === '0') ? '' : $value;
}
@endphp

<div class="profile-container">
    <a href="{{ route('indoor.patients') }}" class="back-button">
        <i class="bi bi-arrow-left"></i> Back to Indoor Patients
    </a>

    <div class="profile-header">
        <div class="header-left">
            <div class="profile-title">IPD Patient Profile</div>
        </div>
        <div class="header-right">
            <button type="button" class="follow-up-btn" data-bs-toggle="modal" data-bs-target="#indoorTreatmentModal" style="background-color: #007bff; border-color: #007bff; margin-right: 10px;">
                <i class="bi bi-hospital"></i> Indoor Treatment
            </button>
            <a href="{{ route('add.follow.up', ['patient_id' => $patient->patient_id]) }}" class="follow-up-btn">Add Follow Up</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 p-0">
            <div class="card mb-4">
                <div class="card-body py-4 text-center">
                    <form action="{{ route('svc.profile.update-image', $patient->id) }}" method="POST" enctype="multipart/form-data" id="profileImageForm">
                        @csrf
                        <div class="profile-image-wrapper">
                            <div class="profile-image-container" id="profileImagePreview">
                                @php $profileImage = $patient->getMeta('profile_image'); @endphp
                                @if($profileImage)
                                    <img src="{{ url($profileImage) }}" alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                @else
                                    <i class="bi bi-person-fill"></i>
                                @endif
                            </div>
                            <label for="profile_image_input" class="upload-label" title="Change Profile Image">
                                <i class="bi bi-camera-fill"></i>
                            </label>
                            <input type="file" name="profile_image" id="profile_image_input" class="d-none" accept="image/*" onchange="
                                if (this.files && this.files[0]) {
                                    var reader = new FileReader();
                                    reader.onload = function(e) {
                                        var container = document.getElementById('profileImagePreview');
                                        if (container) {
                                            container.innerHTML = '<img src=\'' + e.target.result + '\' alt=\'Profile\' style=\'width:100%; height:100%; object-fit:cover; border-radius:50%;\'>';
                                        }
                                        var saveBtn = document.getElementById('saveImageBtn');
                                        if(saveBtn) saveBtn.style.display = 'inline-block';
                                    };
                                    reader.readAsDataURL(this.files[0]);
                                }
                            ">
                        </div>
                        <div id="imageSaveContainer" class="text-center">
                            <button type="submit" class="save-profile-btn" id="saveImageBtn">Save Image</button>
                        </div>
                    </form>
                    <p class="my-3 profile_txt_color mb-2 pb-0" style="font-weight: 600;">{{ $patient->patient_name ?? 'N/A' }}</p>
                    <p class="text-muted small">Hospital ID: {{ $patient->patient_id ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-8 pr-0">
            <div class="card mb-4">
                <div class="card-body py-2">
                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0 profile_txt_color">Full Name</p></div>
                        <div class="col-sm-9"><p class="text-muted mb-0">{{ $patient->patient_name ?? '' }}</p></div>
                    </div>
                    <hr class="my-2">
                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0 profile_txt_color">Address</p></div>
                        <div class="col-sm-9"><p class="text-muted mb-0">{{ $patient->address ?? '' }}</p></div>
                    </div>
                    <hr class="my-2">
                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0 profile_txt_color">Assigned Doctor</p></div>
                        <div class="col-sm-9">
                            @php
                                $doctorId = $meta['doctor_id'] ?? null;
                                $doctor = null;
                                if ($doctorId) { $doctor = \App\Models\User::find($doctorId); }
                            @endphp
                            <p class="text-muted mb-0">{{ $doctor ? $doctor->name : 'Not Assigned' }}</p>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0 profile_txt_color">Age</p></div>
                        <div class="col-sm-9"><p class="text-muted mb-0">{{ $patient->age ?? '-' }}</p></div>
                    </div>
                    <hr class="my-2">
                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0 profile_txt_color">Gender</p></div>
                        <div class="col-sm-9"><p class="text-muted mb-0">{{ $patient->getMeta('gender') ?? '-' }}</p></div>
                    </div>
                    <hr class="my-2">
                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0 profile_txt_color">Reference By</p></div>
                        <div class="col-sm-9"><p class="text-muted mb-0">{{ $patient->getMeta('reference_by') ?? '-' }}</p></div>
                    </div>
                    <hr class="my-2">
                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0 profile_txt_color">Patient Status</p></div>
                        <div class="col-sm-9"><p class="text-muted mb-0">{{ $patient->getMeta('pt_status') ?? '-' }}</p></div>
                    </div>
                    <hr class="my-2">
                    <div class="edit-profile-align-btn">
                        <button class="edit-profile-btn" onclick="window.location.href='{{ route('edit.svc.inquiry', $patient->id) }}'">Edit Profile</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-content">
            <div class="profile-main">



                <!-- Charges Management Section -->
                <div class="row">
                    <div class="col-lg-12 p-0">
                        <div class="card-header mb-2">
                            <div class="section-title"><h3 class="bold font-up fnf-title">Charges Management</h3></div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-body py-2">
                                <div class="row">
                                    <div class="col-sm-3"><p class="mb-0 profile_txt_color">Consultation Charge</p></div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            @if($invoice)
                                                {{ number_format($invoice->total_payment, 2) }}
                                            @else
                                                Not Set
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <div class="row">
                                    <div class="col-sm-3"><p class="mb-0 profile_txt_color">Paid Amount</p></div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            @if($invoice)
                                                {{ number_format($invoice->given_payment, 2) }}
                                            @else
                                                0
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <div class="row">
                                    <div class="col-sm-3"><p class="mb-0 profile_txt_color">Payment Method</p></div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            @if($invoice && $invoice->given_payment > 0)
                                                @php
                                                    // Get payment method from patient transactions
                                                    $transaction = \App\Models\PatientTransaction::where('patient_id', $patient->id)
                                                        ->where('invoice_id', $invoice->id)
                                                        ->where('type', 'credit')
                                                        ->orderBy('created_at', 'desc')
                                                        ->first();
                                                    
                                                    $paymentMethod = 'Not Set';
                                                    if ($transaction && str_contains($transaction->description, 'Cash')) {
                                                        $paymentMethod = 'Cash';
                                                    } elseif ($transaction && str_contains($transaction->description, 'Online')) {
                                                        $paymentMethod = 'Online';
                                                    } elseif ($transaction && str_contains($transaction->description, 'Cheque')) {
                                                        $paymentMethod = 'Cheque';
                                                    }
                                                @endphp
                                                {{ $paymentMethod }}
                                            @else
                                                Not Set
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <div class="row">
                                    <div class="col-sm-3"><p class="mb-0 profile_txt_color">Due Amount</p></div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            @if($invoice)
                                                <span class="{{ $invoice->due_payment > 0 ? 'text-danger' : 'text-success' }}">
                                                    {{ number_format($invoice->due_payment, 2) }}
                                                </span>
                                            @else
                                                <span class="text-success">0</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <div class="row">
                                    <div class="col-sm-3"><p class="mb-0 profile_txt_color">Discount</p></div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            @if($invoice)
                                                {{ number_format($invoice->discount, 2) }}
                                            @else
                                                0
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <div class="text-end mt-3">
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateChargesModal">
                                        <i class="bi bi-pencil-square"></i> Update Charges
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Indoor Treatment Display Section -->
                <div class="row pt-5">
                    <div class="col-lg-12 p-0">
                        <div class="card-header mb-2">
                            <div class="section-title"><h3 class="bold font-up fnf-title">Indoor Treatment</h3></div>
                        </div>
                        @php
                            $allIndoorTreatments = collect();
                            $initialIndoorTreatments = $treatments['indoor'] ?? [];
                            foreach ($initialIndoorTreatments as $treatment) {
                                $allIndoorTreatments->push(array_merge($treatment, [
                                    'display_date' => !empty($treatment['date']) ? \Carbon\Carbon::parse($treatment['date'])->format('d/m/Y') : ($patient->inquiry_date ? \Carbon\Carbon::parse($patient->inquiry_date)->format('d/m/Y') : ''),
                                    'display_time' => !empty($treatment['time']) ? \Carbon\Carbon::parse($treatment['time'])->format('h:i A') : '',
                                    'type' => 'initial'
                                ]));
                            }
                            $followUps = $patient->followups()->with('metas')->orderBy('followup_date', 'desc')->get();
                            foreach ($followUps as $followUp) {
                                $followUpIndoorTreatments = \App\Models\PatientTreatment::where('followup_id', $followUp->id)->where('type', 'indoor')->get();
                                foreach ($followUpIndoorTreatments as $treatment) {
                                    $allIndoorTreatments->push(array_merge($treatment->toArray(), [
                                        'display_date' => !empty($treatment->date) ? \Carbon\Carbon::parse($treatment->date)->format('d/m/Y') : ($followUp->followup_date ? \Carbon\Carbon::parse($followUp->followup_date)->format('d/m/Y') : ''),
                                        'display_time' => !empty($treatment->time) ? \Carbon\Carbon::parse($treatment->time)->format('h:i A') : '',
                                        'type' => 'followup'
                                    ]));
                                }
                            }
                            $currentIndoorPage = request()->get('indoor_page', 1);
                            $indoorPerPage = 3;
                            $indoorChunks = $allIndoorTreatments->chunk($indoorPerPage);
                            $currentIndoorChunk = $indoorChunks[$currentIndoorPage - 1] ?? collect();
                            $totalIndoorPages = count($indoorChunks);
                        @endphp

                        @if($currentIndoorChunk->count() > 0)
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th><th width="15%">Date</th><th width="10%">Time</th>
                                        <th width="35%">Medicine</th><th width="15%">Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($currentIndoorChunk as $index => $medicine)
                                    @php $serialNumber = ($currentIndoorPage - 1) * $indoorPerPage + $index + 1; @endphp
                                    <tr>
                                        <td>{{ $serialNumber }}</td>
                                        <td>{{ formatValue($medicine['display_date']) }}</td>
                                        <td>{{ formatValue($medicine['display_time']) }}</td>
                                        <td class="medicine-name">{{ formatValue($medicine['medicine']) }}</td>
                                        <td>{{ formatValue($medicine['note']) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination">
                                <div class="pagination-info">Showing {{ ($currentIndoorPage - 1) * $indoorPerPage + 1 }} to {{ min($currentIndoorPage * $indoorPerPage, $allIndoorTreatments->count()) }} of {{ $allIndoorTreatments->count() }} entries</div>
                                <div class="pagination-buttons">
                                    @if($currentIndoorPage <= 1)
                                        <button class="pagination-btn" disabled>Previous</button>
                                    @else
                                        <a href="?indoor_page={{ $currentIndoorPage - 1 }}" class="pagination-btn">Previous</a>
                                    @endif
                                    @if($currentIndoorPage >= $totalIndoorPages)
                                        <button class="pagination-btn" disabled>Next</button>
                                    @else
                                        <a href="?indoor_page={{ $currentIndoorPage + 1 }}" class="pagination-btn">Next</a>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="no-treatment">No indoor treatment prescribed</div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagePreviewModalLabel">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="image-preview-container">
                    <div class="default-image-preview">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="150" height="150">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" fill="currentColor"/>
                        </svg>
                        <p class="text-muted mt-3">Profile Image</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     INDOOR TREATMENT MODAL — NEW REDESIGNED VERSION
     ============================================================ --}}
<div class="modal fade" id="indoorTreatmentModal" tabindex="-1" aria-labelledby="indoorTreatmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('svc.profile.indoor-treatment', $patient->id) }}" method="POST" id="indoorTreatmentForm">
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
                        <div class="info-item"><strong>Name:</strong> {{ $patient->patient_name }}</div>
                        <div class="info-item"><strong>Age:</strong> {{ $patient->age }}</div>
                        <div class="info-item"><strong>Diagnosis:</strong> {{ $patient->diagnosis ?? 'N/A' }}</div>
                        <div class="info-item"><strong>Complaints:</strong> {{ $patient->getMeta('complain') ?? 'N/A' }}</div>
                    </div>

                    {{-- Add New Date Slot Button --}}
                    <button type="button" class="add-slot-btn" onclick="addIndoorDateSlot()">
                        <i class="bi bi-plus-lg"></i> Add New Date Slot
                    </button>

                    {{-- Slots Container --}}
                    <div id="indoorSlotsContainer">
                        @php
                            // Group existing indoor treatments by date+time
                            $indoorGroups = [];
                            if(isset($treatments['indoor']) && count($treatments['indoor']) > 0) {
                                foreach($treatments['indoor'] as $t) {
                                    $key = ($t['date'] ?? '') . '||' . ($t['time'] ?? '');
                                    $indoorGroups[$key][] = $t;
                                }
                            }
                        @endphp

                        @if(!empty($indoorGroups))
                            @foreach($indoorGroups as $groupKey => $medicines)
                                @php
                                    [$gDate, $gTime] = explode('||', $groupKey);
                                    $slotIndex = $loop->index;
                                @endphp
                                <div class="date-slot-card" data-slot="{{ $slotIndex }}">
                                    <div class="date-slot-header">
                                        <label>Date &amp; Time</label>
                                        <input type="date" name="slot_date[{{ $slotIndex }}]" value="{{ $gDate }}" required>
                                        <span class="slot-at-separator">@</span>
                                        <input type="time" name="slot_time[{{ $slotIndex }}]" value="{{ $gTime }}">
                                        <span class="medicine-count-badge">
                                            {{ count($medicines) }} {{ count($medicines) === 1 ? 'medicine' : 'medicines' }}
                                        </span>
                                        <button type="button" class="remove-slot-btn" onclick="removeIndoorSlot(this)">
                                            <i class="bi bi-x-lg"></i> Remove Slot
                                        </button>
                                    </div>
                                    <div class="date-slot-body">
                                        <div class="medicines-header">
                                            <span>Medicine</span>
                                            <span>Note</span>
                                        </div>
                                        <div class="medicine-rows-container">
                                            @foreach($medicines as $med)
                                            <div class="medicine-row">
                                                <input type="text"
                                                       name="slot_medicine[{{ $slotIndex }}][]"
                                                       value="{{ $med['medicine'] ?? '' }}"
                                                       placeholder="Medicine name"
                                                       autocomplete="off">
                                                <input type="text"
                                                       name="slot_note[{{ $slotIndex }}][]"
                                                       value="{{ $med['note'] ?? '' }}"
                                                       placeholder="Note">
                                                <button type="button" class="delete-medicine-btn" onclick="deleteMedicineRow(this)" title="Remove">
                                                    <i class="bi bi-trash3-fill" style="font-size:12px;"></i>
                                                </button>
                                            </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="add-medicine-btn" onclick="addMedicineRow(this, {{ $slotIndex }})">
                                            <i class="bi bi-plus-lg"></i> Add Medicine
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
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

{{-- Zoom Actions Modal --}}
<div class="modal fade" id="zoomActionsModal" tabindex="-1" aria-labelledby="zoomActionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="max-width: 500px !important;">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomActionsModalLabel">Zoom Meeting Options</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <label class="form-label fw-bold mb-2 text-primary">Internal Access</label>
                    <a href="" id="modalZoomJoinBtn" target="_blank" class="btn btn-primary w-100 d-flex align-items-center justify-content-center py-2" style="background: var(--color-primary); border: none;">
                        <i class="fas fa-video me-2"></i> Join Meeting Now
                    </a>
                </div>
                <hr class="my-4">
                <div>
                    <label class="form-label fw-bold mb-2 text-primary">Share With Patient</label>
                    <div class="input-group mb-3">
                        <input type="text" id="modalZoomLinkInput" class="form-control" readonly style="font-size: 13px; background: var(--bg-main); color: var(--text-primary);">
                        <button class="btn btn-outline-secondary" type="button" onclick="copyModalZoomLink()" title="Copy Link">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    <a href="" id="modalZoomWaBtn" target="_blank" class="btn btn-success w-100 d-flex align-items-center justify-content-center py-2" style="background: #25D366; border: none; color: white;">
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ===== INDOOR TREATMENT MODAL — SLOT-BASED LOGIC =====
let indoorSlotCounter = {{ isset($indoorGroups) && !empty($indoorGroups) ? count($indoorGroups) : 0 }};

/**
 * Add a new date slot card
 */
function addIndoorDateSlot() {
    const container = document.getElementById('indoorSlotsContainer');
    const slotIndex = indoorSlotCounter++;

    const card = document.createElement('div');
    card.className = 'date-slot-card';
    card.dataset.slot = slotIndex;

    card.innerHTML = `
        <div class="date-slot-header">
            <label>Date &amp; Time</label>
            <input type="date" name="slot_date[${slotIndex}]" required>
            <span class="slot-at-separator">@</span>
            <input type="time" name="slot_time[${slotIndex}]">
            <span class="medicine-count-badge">1 medicine</span>
            <button type="button" class="remove-slot-btn" onclick="removeIndoorSlot(this)">
                <i class="bi bi-x-lg"></i> Remove Slot
            </button>
        </div>
        <div class="date-slot-body">
            <div class="medicines-header">
                <span>Medicine</span>
                <span>Note</span>
            </div>
            <div class="medicine-rows-container">
                <div class="medicine-row">
                    <input type="text" name="slot_medicine[${slotIndex}][]" placeholder="Medicine name" autocomplete="off">
                    <input type="text" name="slot_note[${slotIndex}][]" placeholder="Note">
                    <button type="button" class="delete-medicine-btn" onclick="deleteMedicineRow(this)" title="Remove">
                        <i class="bi bi-trash3-fill" style="font-size:12px;"></i>
                    </button>
                </div>
            </div>
            <button type="button" class="add-medicine-btn" onclick="addMedicineRow(this, ${slotIndex})">
                <i class="bi bi-plus-lg"></i> Add Medicine
            </button>
        </div>
    `;

    container.appendChild(card);
    card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

/**
 * Add a medicine row inside a slot
 */
function addMedicineRow(btn, slotIndex) {
    const card = btn.closest('.date-slot-card');
    const rowsContainer = card.querySelector('.medicine-rows-container');

    const row = document.createElement('div');
    row.className = 'medicine-row';
    row.innerHTML = `
        <input type="text" name="slot_medicine[${slotIndex}][]" placeholder="Medicine name" autocomplete="off">
        <input type="text" name="slot_note[${slotIndex}][]" placeholder="Note">
        <button type="button" class="delete-medicine-btn" onclick="deleteMedicineRow(this)" title="Remove">
            <i class="bi bi-trash3-fill" style="font-size:12px;"></i>
        </button>
    `;
    rowsContainer.appendChild(row);
    updateBadge(card);
    row.querySelector('input').focus();
}

/**
 * Delete a single medicine row
 */
function deleteMedicineRow(btn) {
    const card = btn.closest('.date-slot-card');
    const rowsContainer = card.querySelector('.medicine-rows-container');
    const rows = rowsContainer.querySelectorAll('.medicine-row');

    if (rows.length > 1) {
        btn.closest('.medicine-row').remove();
        updateBadge(card);
    } else {
        // Last row: clear inputs instead of removing
        btn.closest('.medicine-row').querySelectorAll('input').forEach(i => i.value = '');
    }
}

/**
 * Remove an entire date slot card
 */
function removeIndoorSlot(btn) {
    const container = document.getElementById('indoorSlotsContainer');
    const card = btn.closest('.date-slot-card');
    const slots = container.querySelectorAll('.date-slot-card');

    if (slots.length > 1) {
        if (confirm('Remove this date slot and all its medicines?')) {
            card.remove();
        }
    } else {
        // Last slot: clear all inputs
        card.querySelectorAll('input').forEach(i => i.value = '');
        updateBadge(card);
    }
}

/**
 * Update medicine count badge on a slot card
 */
function updateBadge(card) {
    const rows = card.querySelectorAll('.medicine-rows-container .medicine-row');
    const badge = card.querySelector('.medicine-count-badge');
    if (!badge) return;
    const count = rows.length;
    badge.textContent = count + (count === 1 ? ' medicine' : ' medicines');
}

// ===== OTHER FUNCTIONS =====

function openZoomModal(joinUrl, waUrl) {
    const joinBtn = document.getElementById('modalZoomJoinBtn');
    const waBtn = document.getElementById('modalZoomWaBtn');
    const linkInput = document.getElementById('modalZoomLinkInput');
    if (joinBtn) joinBtn.href = joinUrl;
    if (waBtn) waBtn.href = waUrl;
    if (linkInput) linkInput.value = joinUrl;
    const modalEl = document.getElementById('zoomActionsModal');
    if (modalEl) { const modal = new bootstrap.Modal(modalEl); modal.show(); }
}

function copyModalZoomLink() {
    const linkInput = document.getElementById('modalZoomLinkInput');
    if (!linkInput) return;
    linkInput.select();
    linkInput.setSelectionRange(0, 99999);
    document.execCommand('copy');
}

function previewPatientImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var previewContainer = document.getElementById('profileImagePreview');
            previewContainer.innerHTML = '<img src="' + e.target.result + '" alt="Profile Image">';
            document.getElementById('saveImageBtn').style.display = 'inline-block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function confirmDeletePatient() {
    return confirm('Are you sure you want to delete this patient record? This action cannot be undone and will remove all associated data including treatments, follow-ups, and payments.');
}

document.addEventListener('DOMContentLoaded', function () {
    const editProfileBtn = document.querySelector('.edit-profile-btn');
    if (editProfileBtn) {
        editProfileBtn.addEventListener('click', function() {
            window.location.href = "{{ route('edit.svc.inquiry', $patient->id) }}";
        });
    }
});
</script>

<!-- Update Charges Modal -->
<div class="modal fade" id="updateChargesModal" tabindex="-1" aria-labelledby="updateChargesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateChargesModalLabel">Update Patient Charges</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('svc.profile.update-charges', $patient->id) }}" method="POST" id="updateChargesForm">
                @csrf
                <div class="modal-body">                    
                    <div class="mb-3">
                        <label for="modal_total_payment" class="form-label">Total Amount (₹)</label>
                        <input type="number" class="form-control" id="modal_total_payment" name="total_payment" 
                               value="{{ $invoice ? $invoice->total_payment : 0 }}" step="0.01" placeholder="Enter total charge" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_discount_payment" class="form-label">Discount (₹)</label>
                        <input type="number" class="form-control" id="modal_discount_payment" name="discount_payment" 
                               value="{{ $invoice ? $invoice->discount : 0 }}" step="0.01" placeholder="Enter discount" min="0">
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_given_payment" class="form-label">Paid Amount (₹)</label>
                        <input type="number" class="form-control" id="modal_given_payment" name="given_payment" 
                               value="{{ $invoice ? $invoice->given_payment : 0 }}" step="0.01" placeholder="Enter paid amount" min="0">
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_payment_method" class="form-label">Payment Method</label>
                        <select class="form-select" id="modal_payment_method" name="payment_method" required>
                            <option value="">Select Payment Method</option>
                            <option value="Cash" @if($invoice && $invoice->given_payment > 0)
                                @php
                                    $transaction = \App\Models\PatientTransaction::where('patient_id', $patient->id)
                                        ->where('invoice_id', $invoice->id)
                                        ->where('type', 'credit')
                                        ->orderBy('created_at', 'desc')
                                        ->first();
                                    if ($transaction && str_contains($transaction->description, 'Cash')) echo 'selected';
                                @endphp
                            @endif>Cash</option>
                            <option value="Online" @if($invoice && $invoice->given_payment > 0)
                                @php
                                    $transaction = \App\Models\PatientTransaction::where('patient_id', $patient->id)
                                        ->where('invoice_id', $invoice->id)
                                        ->where('type', 'credit')
                                        ->orderBy('created_at', 'desc')
                                        ->first();
                                    if ($transaction && str_contains($transaction->description, 'Online')) echo 'selected';
                                @endphp
                            @endif>Online (GPay/PhonePe/Paytm)</option>
                            <option value="Cheque" @if($invoice && $invoice->given_payment > 0)
                                @php
                                    $transaction = \App\Models\PatientTransaction::where('patient_id', $patient->id)
                                        ->where('invoice_id', $invoice->id)
                                        ->where('type', 'credit')
                                        ->orderBy('created_at', 'desc')
                                        ->first();
                                    if ($transaction && str_contains($transaction->description, 'Cheque')) echo 'selected';
                                @endphp
                            @endif>Cheque</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_due_payment" class="form-label">Due Amount (₹)</label>
                        <input type="number" class="form-control" id="modal_due_payment" name="due_payment" 
                               value="{{ $invoice ? $invoice->due_payment : 0 }}" step="0.01" readonly>
                        <small class="text-muted">Auto-calculated: Total - Discount - Paid</small>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span><strong>Summary:</strong></span>
                            <span id="chargeSummary">
                                Total: ₹<span id="summaryTotal">{{ $invoice ? $invoice->total_payment : 0 }}</span> | 
                                Discount: ₹<span id="summaryDiscount">{{ $invoice ? $invoice->discount : 0 }}</span> | 
                                Paid: ₹<span id="summaryPaid">{{ $invoice ? $invoice->given_payment : 0 }}</span> | 
                                Due: ₹<span id="summaryDue">{{ $invoice ? $invoice->due_payment : 0 }}</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Charges & Sync Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-calculate due amount
document.addEventListener('DOMContentLoaded', function() {
    const totalInput = document.getElementById('modal_total_payment');
    const discountInput = document.getElementById('modal_discount_payment');
    const paidInput = document.getElementById('modal_given_payment');
    const dueInput = document.getElementById('modal_due_payment');
    
    const summaryTotal = document.getElementById('summaryTotal');
    const summaryDiscount = document.getElementById('summaryDiscount');
    const summaryPaid = document.getElementById('summaryPaid');
    const summaryDue = document.getElementById('summaryDue');
    
    function calculateDue() {
        const total = parseFloat(totalInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        const paid = parseFloat(paidInput.value) || 0;
        
        const due = Math.max(0, total - discount - paid);
        
        dueInput.value = due.toFixed(2);
        
        // Update summary
        summaryTotal.textContent = total.toFixed(2);
        summaryDiscount.textContent = discount.toFixed(2);
        summaryPaid.textContent = paid.toFixed(2);
        summaryDue.textContent = due.toFixed(2);
    }
    
    // Add event listeners
    totalInput.addEventListener('input', calculateDue);
    discountInput.addEventListener('input', calculateDue);
    paidInput.addEventListener('input', calculateDue);
    
    // Initial calculation
    calculateDue();
});

// Auto-calculate due amount when total, given, or discount changes
document.addEventListener('DOMContentLoaded', function() {
    const totalInput = document.getElementById('modal_total_payment');
    const givenInput = document.getElementById('modal_given_payment');
    const discountInput = document.getElementById('modal_discount_payment');
    const dueInput = document.getElementById('modal_due_payment');

    function calculateDue() {
        const total = parseFloat(totalInput.value) || 0;
        const given = parseFloat(givenInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        const due = total - given - discount;
        dueInput.value = due >= 0 ? due.toFixed(2) : '0.00';
    }

    [totalInput, givenInput, discountInput].forEach(input => {
        if (input) {
            input.addEventListener('input', calculateDue);
        }
    });
});
</script>
@endsection