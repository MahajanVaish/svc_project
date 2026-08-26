@extends('admin.layouts.layouts')

@section('title', 'Add Indoor Patient Treatment')

@section('content')
<style>
    .section-divider {
        display: flex;
        align-items: center;
        width: 100%;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }
    .section-divider .title {
        white-space: nowrap;
        font-size: 16px;
        font-weight: 600;
        color: #28a745;
        margin-right: 10px;
    }
    .section-divider .line {
        flex-grow: 1;
        height: 1px;
        background: #dcdcdc;
    }
    .section-divider .icon-box {
        display: flex;
        padding-left: 10px;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .section-divider .icon-box i {
        color: #067945;
        font-size: 23px;
    }
    .date-slot-card {
        background: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 20px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }
    .date-slot-header {
        background-color: #006637;
        color: #ffffff;
        padding: 12px 16px;
        font-weight: 600;
    }
    .date-slot-vitals-input {
        background-color: #ffffff !important;
        color: #212529 !important;
        border: 1px solid #ced4da !important;
        opacity: 1 !important;
        font-weight: 500;
    }
    .date-slot-vitals-input::placeholder {
        color: #6c757d !important;
        opacity: 0.8 !important;
    }
    .date-slot-body {
        padding: 16px;
    }
    .multi-select-container {
        position: relative;
    }
    .selected-items {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 6px;
    }
    .selected-item {
        background-color: #e8f5e9;
        color: #006637;
        border: 1px solid #a5d6a7;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .selected-item .remove-item {
        cursor: pointer;
        color: #c62828;
    }
    .autocomplete-container {
        position: relative;
    }
    .autocomplete-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #ddd;
        border-top: none;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border-radius: 0 0 4px 4px;
    }
    .autocomplete-dropdown.show {
        display: block;
    }
    .autocomplete-item {
        padding: 8px 12px;
        cursor: pointer;
    }
    .autocomplete-item:hover {
        background-color: #f1f8e9;
    }
    .pro_filed {
        display: flex;
        gap: 15px;
        align-items: flex-end;
    }
    .pro_filed .form {
        flex: 1;
        min-width: 140px;
    }
    .pro_filed label {
        font-size: 13px;
        font-weight: 600;
        color: #333;
        margin-bottom: 4px;
    }
    .pro_filed input, .pro_filed select {
        width: 100%;
        padding: 7px 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 14px;
    }
</style>

<div class="container-fluid py-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card rounded shadow mb-5">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="bold font-up fnf-title text-success mb-0">Add Indoor Patient Treatment</h3>
            <a href="{{ route('indoor.patients') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Indoor Patients List
            </a>
        </div>
        <div class="row">
            <div class="col-md-12 m-auto">
                <div class="bg-light rounded-5">
                    <section class="w-100 p-4 pb-4">

                        <!-- Patient Header Summary Box -->
                        <div class="card border-0 shadow-sm mb-4" style="border-left: 5px solid #006637 !important; background: #ffffff;">
                            <div class="card-body py-3">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <h5 class="fw-bold mb-1 text-dark">{{ $patient->patient_name }}</h5>
                                        <span class="badge bg-success me-1">{{ $patient->patient_id }}</span>
                                        <span class="badge bg-primary">IPD Patient</span>
                                    </div>
                                    <div class="col-md-2">
                                        <small class="text-muted d-block fw-semibold">Age / Gender</small>
                                        <span class="fw-bold text-dark">{{ $patient->age ?: 'N/A' }} | {{ ucfirst($patient->getMeta('gender') ?? $patient->gender ?? 'N/A') }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted d-block fw-semibold">Phone / Address</small>
                                        <span class="fw-bold text-dark">{{ $patient->getMeta('phone') ?: '-' }}</span>
                                        <small class="d-block text-truncate text-muted" title="{{ $patient->address }}">{{ $patient->address ?: '-' }}</small>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted d-block fw-semibold">Diagnosis / Complaints</small>
                                        <span class="fw-bold text-danger">{{ $patient->diagnosis ?: 'None listed' }}</span>
                                        <small class="d-block text-muted text-truncate">{{ $patient->getMeta('complain') ?: '' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Past Indoor Treatment History Section -->
                        @if(isset($groupedTreatments) && count($groupedTreatments) > 0)
                        <div class="card shadow-sm border-0 mb-4 bg-white">
                            <div class="card-header bg-white border-bottom py-2 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold text-success"><i class="fas fa-history me-2"></i>Past Treatment Records</h6>
                                <span class="badge bg-secondary">{{ count($groupedTreatments) }} Record Slots</span>
                            </div>
                            <div class="card-body p-3" style="max-height: 250px; overflow-y: auto;">
                                <div class="row g-3">
                                    @foreach($groupedTreatments as $groupKey => $groupItems)
                                        @php
                                            list($gDate, $gTime) = explode('||', $groupKey);
                                            $firstItem = $groupItems->first();
                                        @endphp
                                        <div class="col-md-6 col-lg-4">
                                            <div class="border rounded p-2 bg-light">
                                                <div class="d-flex justify-content-between align-items-center mb-1 pb-1 border-bottom">
                                                    <span class="fw-bold text-dark"><i class="far fa-calendar-alt me-1"></i> {{ $gDate !== 'No Date' ? \Carbon\Carbon::parse($gDate)->format('d/m/Y') : 'No Date' }}</span>
                                                    <span class="badge bg-dark"><i class="far fa-clock me-1"></i> {{ $gTime !== 'No Time' ? $gTime : 'No Time' }}</span>
                                                </div>
                                                @if($firstItem->temp || $firstItem->pulse || $firstItem->bp || $firstItem->spo2)
                                                <div class="d-flex flex-wrap gap-1 mb-2">
                                                    @if($firstItem->temp)<span class="badge bg-warning text-dark" style="font-size: 11px;">Temp: {{ $firstItem->temp }}°F</span>@endif
                                                    @if($firstItem->pulse)<span class="badge bg-info text-dark" style="font-size: 11px;">Pulse: {{ $firstItem->pulse }}</span>@endif
                                                    @if($firstItem->bp)<span class="badge bg-danger" style="font-size: 11px;">BP: {{ $firstItem->bp }}</span>@endif
                                                    @if($firstItem->spo2)<span class="badge bg-success" style="font-size: 11px;">SpO2: {{ $firstItem->spo2 }}%</span>@endif
                                                </div>
                                                @endif
                                                <ul class="list-unstyled mb-0 small">
                                                    @foreach($groupItems as $tItem)
                                                        <li class="py-1 border-bottom-light">
                                                            <i class="fas fa-pills text-success me-1"></i> <strong>{{ $tItem->medicine }}</strong>
                                                            @if($tItem->note) <span class="text-muted">({{ $tItem->note }})</span> @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Main Form -->
                        <form action="{{ route('svc.profile.indoor-treatment', $patient->id) }}" method="POST" id="indoorTreatmentForm">
                            @csrf

                            <!-- Section 1: Treatment Log Slots -->
                            <div class="section-divider">
                                <div class="title">Add Treatment Log Slots</div>
                                <div class="line"></div>
                                <div class="icon-box" onclick="toggleGenericSection(this)">
                                    <i class="bi bi-dash-lg"></i>
                                </div>
                            </div>

                            <div class="pt-2">
                                <div id="indoorSlotContainer">
                                    <!-- Slot 0 (Default Initial Slot) -->
                                    <div class="date-slot-card" data-slot-index="0">
                                        <div class="date-slot-header">
                                            <div class="row g-2 align-items-center">
                                                <div class="col-md-2">
                                                    <label class="form-label text-white mb-1 small fw-bold">Date *</label>
                                                    <input type="date" name="slot_date[0]" class="form-control form-control-sm date-slot-vitals-input" value="{{ date('Y-m-d') }}" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label text-white mb-1 small fw-bold">Time *</label>
                                                    <input type="time" name="slot_time[0]" class="form-control form-control-sm date-slot-vitals-input" value="{{ date('H:i') }}" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label text-white mb-1 small fw-bold">Temp (°F / °C)</label>
                                                    <input type="text" name="slot_temp[0]" class="form-control form-control-sm date-slot-vitals-input" placeholder="Temp (°F)">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label text-white mb-1 small fw-bold">Pulse (bpm)</label>
                                                    <input type="text" name="slot_pulse[0]" class="form-control form-control-sm date-slot-vitals-input" placeholder="Pulse (bpm)">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label text-white mb-1 small fw-bold">BP (mmHg)</label>
                                                    <input type="text" name="slot_bp[0]" class="form-control form-control-sm date-slot-vitals-input" placeholder="BP (mmHg)">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label text-white mb-1 small fw-bold">SpO2 (%)</label>
                                                    <input type="text" name="slot_spo2[0]" class="form-control form-control-sm date-slot-vitals-input" placeholder="SpO2 (%)">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="date-slot-body">
                                            <table class="table table-sm table-borderless mb-2">
                                                <thead>
                                                    <tr class="text-muted small">
                                                        <th style="width: 45%;">Medicine Name / Action</th>
                                                        <th style="width: 45%;">Dosage / Action Note</th>
                                                        <th style="width: 10%; text-align: center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="slot-medicine-tbody">
                                                    <tr>
                                                        <td>
                                                            <input type="text" name="slot_medicine[0][]" class="form-control form-control-sm" placeholder="Enter medicine name / procedure" required autocomplete="off">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="slot_note[0][]" class="form-control form-control-sm" placeholder="Enter dosage / instructions">
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" onclick="removeMedicineRow(this)"><i class="fas fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                                <button type="button" class="btn btn-sm btn-outline-success" onclick="addMedicineRow(this, 0)">
                                                    <i class="fas fa-plus me-1"></i> Add Another Medicine
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-slot" onclick="removeSlotCard(this)" style="display: none;">
                                                    <i class="fas fa-trash-alt me-1"></i> Drop Slot
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <button type="button" class="btn btn-outline-primary" onclick="addNewSlot()">
                                        <i class="fas fa-calendar-plus me-1"></i> Add Another Date & Time Slot
                                    </button>
                                </div>
                            </div>

                            <!-- Section 2: Payment Information (Same layout as add_follow_up.blade.php) -->
                            <div class="section-divider mt-4">
                                <div class="title">Payment Information</div>
                                <div class="line"></div>
                                <div class="icon-box" onclick="toggleGenericSection(this)">
                                    <i class="bi bi-dash-lg"></i>
                                </div>
                            </div>

                            <div class="pt-2">
                                <div class="d-flex align-items-center bg-light p-3 mb-3 rounded border">
                                    <input type="checkbox" name="foc" id="foc" class="form-check-input me-3" style="width: 20px; height: 20px;">
                                    <label for="foc" class="mb-0 fw-semibold text-dark">
                                        FOC (Free of Charge Inquiry)
                                    </label>
                                </div>

                                <div id="payment_section" class="bg-white p-3 rounded border mb-4">
                                    <div class="pro_filed" style="flex-wrap: wrap;">
                                        <div class="form">
                                            <div class="form-col">
                                                <label for="followup_charges" class="fw-bold">Followup Charges</label>
                                                <input type="number" id="followup_charges" name="followup_charges" step="0.01" min="0" placeholder="Enter Followup Charges" value="">
                                            </div>
                                        </div>

                                        <div class="form">
                                            <div class="form-col">
                                                <label for="total_payment" class="fw-bold">Total Amount</label>
                                                <input type="number" id="total_payment" name="total_payment" step="0.01" readonly placeholder="Total" value="0">
                                            </div>
                                        </div>

                                        <div class="form">
                                            <div class="form-col">
                                                <label for="discount_payment" class="fw-bold">Discount Amount</label>
                                                <input type="number" id="discount_payment" name="discount_payment" step="0.01" placeholder="Discount" value="0">
                                            </div>
                                        </div>

                                        <div class="form">
                                            <div class="form-col">
                                                <label for="given_payment" class="fw-bold">Paid Amount</label>
                                                <input type="number" id="given_payment" name="given_payment" placeholder="Enter amount paid" step="0.01" value="">
                                            </div>
                                        </div>

                                        <div class="form">
                                            <div class="form-col">
                                                <label for="payment_method" class="fw-bold">Payment Method</label>
                                                <select id="payment_method" name="payment_method">
                                                    <option value="Cash" selected>Cash</option>
                                                    <option value="Online">Online</option>
                                                    <option value="Cheque">Cheque</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form">
                                            <div class="form-col">
                                                <label for="due_payment" class="fw-bold">Due Amount</label>
                                                <input type="number" id="due_payment" name="due_payment" value="0" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit & Cancel Buttons -->
                            <div class="d-flex justify-content-end gap-2 pt-3">
                                <a href="{{ route('indoor.patients') }}" class="btn btn-secondary px-4">Cancel</a>
                                <button type="submit" class="btn text-white px-4 fw-bold" style="background-color: #006637;">
                                    <i class="fas fa-save me-2"></i> Save Indoor Treatment & Payment
                                </button>
                            </div>
                        </form>

                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Logic -->
<script>
let slotCounter = 1;

document.addEventListener('DOMContentLoaded', function() {
    initPaymentLogic();
});

function toggleGenericSection(iconBox) {
    const sectionDivider = iconBox.closest('.section-divider');
    const targetContent = sectionDivider ? sectionDivider.nextElementSibling : null;
    const icon = iconBox.querySelector('i');

    if (targetContent) {
        if (targetContent.style.display === 'none') {
            targetContent.style.display = 'block';
            if (icon) icon.className = 'bi bi-dash-lg';
        } else {
            targetContent.style.display = 'none';
            if (icon) icon.className = 'bi bi-plus-lg';
        }
    }
}

function addNewSlot() {
    const container = document.getElementById('indoorSlotContainer');
    const slotIdx = slotCounter++;
    const today = new Date().toISOString().split('T')[0];
    const nowTime = new Date().toTimeString().split(' ')[0].substring(0, 5);

    const slotHtml = `
        <div class="date-slot-card" data-slot-index="${slotIdx}">
            <div class="date-slot-header">
                <div class="row g-2 align-items-center">
                    <div class="col-md-2">
                        <label class="form-label text-white mb-1 small fw-bold">Date *</label>
                        <input type="date" name="slot_date[${slotIdx}]" class="form-control form-control-sm date-slot-vitals-input" value="${today}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-white mb-1 small fw-bold">Time *</label>
                        <input type="time" name="slot_time[${slotIdx}]" class="form-control form-control-sm date-slot-vitals-input" value="${nowTime}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-white mb-1 small fw-bold">Temp (°F / °C)</label>
                        <input type="text" name="slot_temp[${slotIdx}]" class="form-control form-control-sm date-slot-vitals-input" placeholder="Temp (°F)">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-white mb-1 small fw-bold">Pulse (bpm)</label>
                        <input type="text" name="slot_pulse[${slotIdx}]" class="form-control form-control-sm date-slot-vitals-input" placeholder="Pulse (bpm)">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-white mb-1 small fw-bold">BP (mmHg)</label>
                        <input type="text" name="slot_bp[${slotIdx}]" class="form-control form-control-sm date-slot-vitals-input" placeholder="BP (mmHg)">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-white mb-1 small fw-bold">SpO2 (%)</label>
                        <input type="text" name="slot_spo2[${slotIdx}]" class="form-control form-control-sm date-slot-vitals-input" placeholder="SpO2 (%)">
                    </div>
                </div>
            </div>
            <div class="date-slot-body">
                <table class="table table-sm table-borderless mb-2">
                    <thead>
                        <tr class="text-muted small">
                            <th style="width: 45%;">Medicine Name / Action</th>
                            <th style="width: 45%;">Dosage / Action Note</th>
                            <th style="width: 10%; text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody class="slot-medicine-tbody">
                        <tr>
                            <td>
                                <input type="text" name="slot_medicine[${slotIdx}][]" class="form-control form-control-sm" placeholder="Enter medicine name / procedure" required autocomplete="off">
                            </td>
                            <td>
                                <input type="text" name="slot_note[${slotIdx}][]" class="form-control form-control-sm" placeholder="Enter dosage / instructions">
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" onclick="removeMedicineRow(this)"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="addMedicineRow(this, ${slotIdx})">
                        <i class="fas fa-plus me-1"></i> Add Another Medicine
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-slot" onclick="removeSlotCard(this)">
                        <i class="fas fa-trash-alt me-1"></i> Drop Slot
                    </button>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', slotHtml);
    updateSlotButtons();
}

function addMedicineRow(btn, slotIdx) {
    const card = btn.closest('.date-slot-card');
    const index = card ? card.getAttribute('data-slot-index') : slotIdx;
    const tbody = card.querySelector('.slot-medicine-tbody');
    const trHtml = `
        <tr>
            <td>
                <input type="text" name="slot_medicine[${index}][]" class="form-control form-control-sm" placeholder="Enter medicine name / procedure" required autocomplete="off">
            </td>
            <td>
                <input type="text" name="slot_note[${index}][]" class="form-control form-control-sm" placeholder="Enter dosage / instructions">
            </td>
            <td style="text-align: center;">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" onclick="removeMedicineRow(this)"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
    `;
    tbody.insertAdjacentHTML('beforeend', trHtml);
}

function removeMedicineRow(btn) {
    const tbody = btn.closest('tbody');
    if (tbody.querySelectorAll('tr').length > 1) {
        btn.closest('tr').remove();
    } else {
        alert('Each date slot must have at least one medicine row.');
    }
}

function removeSlotCard(btn) {
    const card = btn.closest('.date-slot-card');
    if (document.querySelectorAll('.date-slot-card').length > 1) {
        card.remove();
        updateSlotButtons();
    }
}

function updateSlotButtons() {
    const cards = document.querySelectorAll('.date-slot-card');
    cards.forEach((card, idx) => {
        const dropBtn = card.querySelector('.btn-remove-slot');
        if (dropBtn) {
            dropBtn.style.display = (cards.length > 1) ? 'inline-block' : 'none';
        }
    });
}

function initPaymentLogic() {
    const followupChargesInput = document.getElementById('followup_charges');
    const totalInput = document.getElementById('total_payment');
    const discountInput = document.getElementById('discount_payment');
    const givenInput = document.getElementById('given_payment');
    const dueInput = document.getElementById('due_payment');
    const focCheckbox = document.getElementById('foc');
    const paymentSection = document.getElementById('payment_section');

    // FOC Checkbox Handler
    if (focCheckbox) {
        focCheckbox.addEventListener('change', function() {
            if (this.checked) {
                paymentSection.style.display = 'none';
                if (followupChargesInput) followupChargesInput.value = 0;
                totalInput.value = (0).toFixed(2);
                discountInput.value = (0).toFixed(2);
                givenInput.value = (0).toFixed(2);
                dueInput.value = (0).toFixed(2);
            } else {
                paymentSection.style.display = 'block';
                calculateTotals();
            }
        });
    }

    function calculateTotals() {
        const followupCharges = parseFloat(followupChargesInput ? followupChargesInput.value : 0) || 0;
        totalInput.value = followupCharges.toFixed(2);

        const discount = parseFloat(discountInput.value || 0);
        const given = parseFloat(givenInput.value || 0);
        const due = Math.max(0, followupCharges - discount - given);
        dueInput.value = due.toFixed(2);
    }

    if (followupChargesInput) {
        followupChargesInput.addEventListener('input', calculateTotals);
    }
    if (discountInput) {
        discountInput.addEventListener('input', calculateTotals);
    }
    if (givenInput) {
        givenInput.addEventListener('input', calculateTotals);
    }

    calculateTotals();
}
</script>
@endsection
