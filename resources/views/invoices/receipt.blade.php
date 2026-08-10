<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $invoice->invoice_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            overflow-x: hidden;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f1f5f9;
            padding: 20px;
            font-size: 14px;
            color: #1e293b;
            line-height: 1.6;
        }

        /* ── PRINT BUTTON (hidden on print) ── */
        .print-bar {
            max-width: 860px;
            margin: 0 auto 16px auto;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .print-btn {
            background: #086838;
            color: white;
            border: none;
            padding: 10px 24px;
            cursor: pointer;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 4px 6px -1px rgba(8, 104, 56, 0.3);
        }

        .print-btn:hover {
            background: #065c2e;
            transform: translateY(-1px);
            box-shadow: 0 8px 15px -3px rgba(8, 104, 56, 0.4);
        }

        /* ── MAIN CARD ── */
        .invoice-card {
            max-width: 860px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.12);
            overflow: hidden;
        }

        /* ── HEADER ── */
        .invoice-header {
            background: linear-gradient(135deg, #065c2e 0%, #086838 60%, #0d8c47 100%);
            padding: 30px 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }

        .invoice-title h2 {
            font-size: 24px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 4px;
            letter-spacing: -0.3px;
        }

        .invoice-title h3 {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 400;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .company-address {
            text-align: right;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.8;
        }

        /* ── BODY ── */
        .invoice-body {
            padding: 20px 40px;
        }

        /* ── INFO SECTION ── */
        .info-section {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-block {
            flex: 1;
            background: #f8fafc;
            border-radius: 10px;
            padding: 12px 18px;
            border: 1px solid #e2e8f0;
        }

        .info-block h4 {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #086838;
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dcfce7;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
            gap: 12px;
        }

        .info-label {
            font-weight: 600;
            font-size: 12px;
            color: #64748b;
            min-width: 100px;
            flex-shrink: 0;
        }

        .info-value {
            font-size: 13px;
            color: #1e293b;
            text-align: right;
            word-break: break-word;
        }

        /* ── TABLE ── */
        .table-wrapper {
            overflow-x: auto;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            margin-bottom: 18px;
            -webkit-overflow-scrolling: touch;
            max-width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 460px;
        }

        thead th {
            background: #f0fdf4;
            border-bottom: 2px solid #086838;
            color: #065c2e;
            font-weight: 700;
            font-size: 12px;
            padding: 14px 16px;
            text-align: left;
            white-space: nowrap;
        }

        tbody td {
            padding: 13px 16px;
            font-size: 13px;
            vertical-align: top;
            border-bottom: 1px solid #f1f5f9;
        }

        tbody tr:hover {
            background-color: #fafafa;
        }

        .summary-row {
            background: #f8fafc !important;
        }

        .summary-row td {
            font-weight: 600;
            border-top: 1px solid #e2e8f0;
            font-size: 13px;
        }

        .summary-row.grand-total td {
            color: #086838;
            font-weight: 800;
            font-size: 14px;
            background: #f0fdf4;
        }

        /* ── TERMS ── */
        .terms-section {
            background: #f0fdf4;
            border: 1px solid #dcfce7;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 18px;
        }

        .terms-heading {
            font-weight: 700;
            font-size: 13px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid #bbfcce;
            color: #086838;
            gap: 10px;
        }

        .terms-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .terms-list li {
            font-size: 12px;
            margin-bottom: 7px;
            padding-left: 20px;
            position: relative;
            color: #166534;
            line-height: 1.6;
        }

        .terms-list li:before {
            content: "✓";
            color: #22c55e;
            font-weight: bold;
            position: absolute;
            left: 0;
        }

        /* ── SIGNATURE ── */
        .signature-section {
            display: flex;
            justify-content: space-between;
            gap: 30px;
            padding-top: 16px;
        }

        .signature-box {
            flex: 1;
            text-align: center;
        }

        .signature-label {
            font-size: 13px;
            color: #475569;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .signature-line {
            border-top: 1.5px solid #94a3b8;
            margin: 30px auto 10px;
            max-width: 200px;
        }

        .signature-sub {
            font-size: 11px;
            color: #94a3b8;
        }

        /* ── RESPONSIVE: Tablet (≤768px) ── */
        @media (max-width: 768px) {
            body {
                padding: 12px;
            }

            .invoice-header {
                padding: 24px 28px;
            }

            .invoice-body {
                padding: 24px 28px;
            }

            .invoice-title h2 {
                font-size: 20px;
            }

            .info-label {
                min-width: 85px;
            }
        }

        /* ── RESPONSIVE: Mobile (≤576px) ── */
        @media (max-width: 576px) {
            body {
                padding: 8px;
            }

            .print-bar {
                justify-content: center;
                margin-bottom: 12px;
                padding: 0;
            }

            .print-btn {
                width: 100%;
                justify-content: center;
                padding: 12px;
            }

            .invoice-card {
                border-radius: 10px;
                width: 100%;
            }

            .invoice-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 20px 16px;
                gap: 14px;
            }

            .company-address {
                text-align: center;
            }

            .invoice-title h2 {
                font-size: 18px;
            }

            .invoice-body {
                padding: 16px 14px;
            }

            .info-section {
                flex-direction: column;
                gap: 14px;
            }

            .info-row {
                flex-direction: column;
                gap: 2px;
                margin-bottom: 8px;
            }

            .info-value {
                text-align: left;
                font-weight: 600;
            }

            .terms-heading {
                flex-direction: column;
                text-align: center;
                gap: 6px;
            }

            .signature-section {
                flex-direction: column;
                gap: 20px;
            }

            .signature-line {
                margin: 30px auto 10px;
            }

            table {
                min-width: 0;
                font-size: 11px;
            }

            thead th,
            tbody td {
                padding: 8px 10px;
            }
        }

        /* ── PRINT ── */
        @media print {
            @page {
                margin: 0;
            }

            body {
                background: white;
                padding: 1.5cm;
            }

            .print-bar {
                display: none;
            }

            .invoice-card {
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
            }

            .invoice-header {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .info-block,
            .terms-section {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .summary-row.grand-total td {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>

    {{-- Print Button Bar --}}
    <div class="print-bar">
        <button class="print-btn" onclick="window.print()">
            🖨️ Print Invoice
        </button>
    </div>

    @php
        $branchId = $invoice->branch_id;
        $isSVC = ($branchId === 'SVC-0005');
        $isLHR = ($branchId === 'LB-0007');
        $isHydra = ($branchId === 'BH-00023');
        $isBardoli = in_array($branchId, ['LB-0007', 'BH-00023', 'BD-0004']);
        $isSuratMain = ($branchId === 'ST-0001');

        $branchDisplayName = $isSVC ? 'Shree Vallabh Clinic' : 'FIGURE ‘n FIT';
        $branchAddress = $invoice->branch->address ?? 'Surat, Gujarat';

        if ($isSVC) {
            $branchAddress = "Priyanka Intercity,<br>Puna Kumbhariya Road,<br>Magob, Surat<br>📞 8758875020";
        } elseif ($isBardoli) {
            $branchAddress = "Sarthak Villa, Tribhovan Nagar,<br>B/H V K Tower, B/S Green Apple Hospital,<br>Mahatma Gandhi Rd, Bardoli, Gujarat 394601";
        } elseif ($isSuratMain) {
            $branchAddress = "211/212, Millenium Point, Block-A,<br>Laldarwaja Road, Station, Surat<br>📞 +91-99133 48004";
        }
    @endphp

    <div class="invoice-card">

        {{-- HEADER --}}
        <div class="invoice-header">
            <div class="invoice-title">
                <h2>{{ $branchDisplayName }}</h2>
                <h3>Invoice</h3>
                @if($isSVC)
                    <div style="margin-top:8px; font-size:11px; color:rgba(255,255,255,0.75); line-height:1.7;">
                        <div><strong style="color:#fff;">Consultant:</strong> Dr.Manish Akbari (BHMS)</div>
                        <div><strong style="color:#fff;">Reg. No.:</strong> G-9088 (Gujarat Homoeopathic Council)</div>
                    </div>
                @endif
            </div>
            <div class="company-address">
                @if($isSVC || $isBardoli || $isSuratMain)
                    {!! $branchAddress !!}
                @else
                    {!! nl2br(e($branchAddress)) !!}
                    @if(!empty($invoice->branch->phone_no))
                        <br>📞 {{ $invoice->branch->phone_no }}
                    @endif
                @endif
            </div>
        </div>

        <div class="invoice-body">

            {{-- INFO SECTION --}}
            <div class="info-section">

                {{-- Patient Info --}}
                <div class="info-block">
                    <h4>Patient Details</h4>
                    <div class="info-row">
                        <span class="info-label">Name</span>
                        <span class="info-value">{{ $invoice->resolved_patient->patient_name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Address</span>
                        <span class="info-value">{{ $invoice->address ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Mobile No.</span>
                        <span class="info-value">{{ $invoice->phone ?: '—' }}</span>
                    </div>
                </div>

                {{-- Invoice Info --}}
                <div class="info-block">
                    <h4>Invoice Details</h4>
                    <div class="info-row">
                        <span class="info-label">Invoice No.</span>
                        <span class="info-value">{{ $invoice->invoice_no }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Invoice Date</span>
                        <span
                            class="info-value">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</span>
                    </div>
                </div>

            </div>

            {{-- TABLE --}}
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 8%">No.</th>
                            <th style="width: 68%">Description</th>
                            <th style="width: 24%">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = 1; @endphp

                        @php
                            $isIPDInvoice = str_starts_with($invoice->invoice_no, 'IPD-');
                        @endphp

                        @if($isIPDInvoice)
                            <tr>
                                <td>{{ $counter++ }}</td>
                                <td>IPD Patient Charges</td>
                                <td>₹{{ number_format($invoice->total_payment, 2) }}</td>
                            </tr>

                            @if ($invoice->discount > 0)
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>IPD Discount</td>
                                    <td>−₹{{ number_format($invoice->discount, 2) }}</td>
                                </tr>
                            @endif

                        @else
                            @php
                            $programsData = $invoice->programs_data;
                            if (is_string($programsData)) {
                                $programsData = json_decode($programsData, true);
                            }

                            // Fetch latest program from OptMeta only if programs_data is empty
                            $optMetaPrograms = [];
                            if (empty($programsData)) {
                                try {
                                    $patientIdStr = $invoice->resolved_patient->patient_id ?? null;
                                    if ($patientIdStr) {
                                        $opt = \App\Models\Opt::where('patient_id', $patientIdStr)
                                            ->where(function ($q) {
                                                $q->whereNull('delete_status')
                                                  ->orWhere('delete_status', '0');
                                            })
                                            ->latest()
                                            ->first();
                                        if ($opt) {
                                            $meta = \App\Models\OptMeta::where('opt_id', $opt->id)->where('meta_key', 'programs_array')->first();
                                            if ($meta && $meta->meta_value) {
                                                $decoded = json_decode($meta->meta_value, true);
                                                if (is_array($decoded) && count($decoded) > 0) {
                                                    $optMetaPrograms = [end($decoded)];
                                                }
                                            }
                                        }
                                    }
                                } catch (\Exception $e) {}
                            }

                            // Fallback for online/abroad patients whose old invoices have no programs_data
                            $onlineProgramLabel = (!empty($optMetaPrograms) || !empty($programsData)) ? null : $invoice->online_program_label;
                            @endphp

                            @if(!empty($programsData) && is_array($programsData))
                                @foreach($programsData as $program)
                                    <tr>
                                        <td>{{ $counter++ }}</td>
                                        <td>
                                            {{ $program['program_name'] ?? 'Service' }}
                                            @if(!empty($program['session']) || !empty($program['months']))
                                                <br><small class="text-muted">
                                                    @if(!empty($program['session']))
                                                        Session: {{ $program['session'] }}
                                                    @endif
                                                    @if(!empty($program['session']) && !empty($program['months'])) | @endif
                                                    @if(!empty($program['months'])) Months: {{ $program['months'] }} @endif
                                                </small>
                                            @endif
                                        </td>
                                        @php
                                            $sessionStr = $program['session'] ?? '';
                                            $monthsStr = $program['months'] ?? '';
                                            $sCount = 0; $mCount = 0;
                                            if (preg_match('/\d+/', $sessionStr, $matches)) $sCount = (int)$matches[0];
                                            if (preg_match('/\d+/', $monthsStr, $matches)) $mCount = (int)$matches[0];
                                            $multiplier = max(1, $sCount, $mCount);
                                            
                                            $rowPrice = (float)($program['price'] ?? 0);
                                            // Fix for older invoices where unit price was stored instead of total price
                                            if ($multiplier > 1 && ($rowPrice * $multiplier) <= ((float)$invoice->price + 1)) {
                                                $rowPrice = $rowPrice * $multiplier;
                                            }
                                        @endphp
                                        <td>₹{{ number_format($rowPrice, 2) }}</td>
                                    </tr>
                                @endforeach
                            @elseif(!empty($optMetaPrograms) && is_array($optMetaPrograms) && count($optMetaPrograms) > 0)
                                @foreach($optMetaPrograms as $program)
                                    <tr>
                                        <td>{{ $counter++ }}</td>
                                        <td>
                                            {{ $program['program'] ?? 'Program' }}
                                            @if(!empty($program['session']) || !empty($program['months']))
                                                <br><small class="text-muted">
                                                    @if(!empty($program['session']))
                                                        Session: {{ $program['session'] }}
                                                    @endif
                                                    @if(!empty($program['session']) && !empty($program['months'])) | @endif
                                                    @if(!empty($program['months'])) Months: {{ $program['months'] }} @endif
                                                </small>
                                            @endif
                                        </td>
                                        @php
                                            $sessionStr = $program['session'] ?? '';
                                            $monthsStr = $program['months'] ?? '';
                                            $sCount = 0; $mCount = 0;
                                            if (preg_match('/\d+/', $sessionStr, $matches)) $sCount = (int)$matches[0];
                                            if (preg_match('/\d+/', $monthsStr, $matches)) $mCount = (int)$matches[0];
                                            $multiplier = max(1, $sCount, $mCount);
                                            
                                            $rowPrice = (float)($program['total'] ?? 0);
                                            // Fix for older invoices where unit price was stored instead of total price
                                            if ($multiplier > 1 && ($rowPrice * $multiplier) <= ((float)$invoice->price + 1)) {
                                                $rowPrice = $rowPrice * $multiplier;
                                            }
                                        @endphp
                                        <td>₹{{ number_format($rowPrice, 2) }}</td>
                                    </tr>
                                @endforeach
                            @elseif($onlineProgramLabel)
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $onlineProgramLabel }} (Online/Abroad Program)</td>
                                    <td>₹{{ number_format($invoice->total_payment, 2) }}</td>
                                </tr>
                            @elseif($invoice->program)
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $invoice->program->program_name }} (Program)</td>
                                    <td>₹{{ number_format($invoice->program->program_price, 2) }}</td>
                                </tr>
                            @endif

                            @php
                                $chargesData = $invoice->charges_data;
                                if (is_string($chargesData)) {
                                    $chargesData = json_decode($chargesData, true);
                                }
                            @endphp

                            @if(!empty($chargesData) && is_array($chargesData))
                                @php
                                    $consolidatedCharges = [];
                                    foreach ($chargesData as $charge) {
                                        $chargeModel = !empty($charge['charge_id']) ? \App\Models\Charges::find($charge['charge_id']) : null;
                                        $displayChargeName = $charge['charge_name'] ?? ($chargeModel ? $chargeModel->charges_name : 'Charge');

                                        if ($displayChargeName === 'FNF Service' || $displayChargeName === 'LHR Service' || $displayChargeName === 'Hydra Service' || $displayChargeName === 'SVC Service' || in_array($displayChargeName, ['Registration Charges', 'Registration', 'SVC-Charge', 'Followup Charges', 'Follow up charges', 'Consulting charges', 'Registration & Consultation Charges'])) {
                                            $branchName = $invoice->branch->branch_name ?? 'FNF';
                                            $branchPrefix = str_replace([' Branch', ' Inquiry', ' Diet Chart'], '', $branchName);
                                            $displayChargeName = $branchPrefix . ' Service';
                                        }

                                        if (isset($consolidatedCharges[$displayChargeName])) {
                                            $consolidatedCharges[$displayChargeName] += (float) $charge['price'];
                                        } else {
                                            $consolidatedCharges[$displayChargeName] = (float) $charge['price'];
                                        }
                                    }
                                @endphp

                                @foreach($consolidatedCharges as $name => $price)
                                    <tr>
                                        <td>{{ $counter++ }}</td>
                                        <td>{{ $name }}</td>
                                        <td>₹{{ number_format($price, 2) }}</td>
                                    </tr>
                                @endforeach

                            @elseif(!$invoice->program && empty($programsData) && empty($optMetaPrograms) && empty($onlineProgramLabel))
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>
                                        @php
                                            $fLabel = $invoice->charge->charges_name ?? 'Consulting charges';
                                            
                                            // Only override if it's a generic registration/consultation charge
                                            if (in_array($fLabel, ['Registration Charges', 'Registration', 'SVC-Charge', 'Followup Charges', 'Follow up charges', 'Consulting charges', 'Registration & Consultation Charges', 'FNF Service', 'LHR Service', 'Hydra Service', 'SVC Service'])) {
                                                $branchName = $invoice->branch->branch_name ?? 'FNF';
                                                $branchPrefix = str_replace([' Branch', ' Inquiry', ' Diet Chart'], '', $branchName);
                                                $fLabel = $branchPrefix . ' Service';
                                            }
                                        @endphp
                                        {{ $fLabel }}
                                    </td>
                                    <td>₹{{ number_format($invoice->price, 2) }}</td>
                                </tr>
                            @endif

                            @if ($invoice->discount > 0)
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>Discount</td>
                                    <td>−₹{{ number_format($invoice->discount, 2) }}</td>
                                </tr>
                            @endif
                        @endif

                        {{-- Spacer rows reduced for single page fit --}}
                        @for ($i = 0; $i < max(0, 3 - $counter); $i++)
                            <tr>
                                <td>&nbsp;</td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endfor

                        <tr class="summary-row">
                            <td></td>
                            <td style="text-align: right; padding-right: 20px; font-weight: 700; color: #475569;">Total Amount</td>
                            <td style="font-weight: 700; font-size: 14px;">₹{{ number_format($invoice->total_payment, 2) }}</td>
                        </tr>
                        @if ($invoice->discount > 0)
                        <tr class="summary-row">
                            <td></td>
                            <td style="text-align: right; padding-right: 20px; font-weight: 700; color: #475569;">Discount</td>
                            <td style="font-weight: 700; font-size: 14px; color: #e63946;">−₹{{ number_format($invoice->discount, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="summary-row">
                            <td></td>
                            <td style="text-align: right; padding-right: 20px; font-weight: 700; color: #475569;">Paid Amount</td>
                            <td style="font-weight: 700; font-size: 14px;">₹{{ number_format($invoice->given_payment, 2) }}</td>
                        </tr>
                        <tr class="summary-row grand-total">
                            <td style="border-bottom-left-radius: 8px;"></td>
                            <td style="text-align: right; padding-right: 20px; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px;">Due Payment</td>
                            <td style="border-bottom-right-radius: 8px;">₹{{ number_format($invoice->due_payment, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- PAYMENT TRANSACTIONS / PARTIAL RECEIPTS BREAKDOWN --}}
            @php
                $creditTransactions = $invoice->transactions ? $invoice->transactions->where('type', 'credit') : collect();
            @endphp
            @if($creditTransactions->isNotEmpty())
                <div style="margin-bottom: 22px;">
                    <div style="font-weight: 700; font-size: 13px; color: #086838; margin-bottom: 10px; border-bottom: 2px solid #dcfce7; padding-bottom: 6px; display: flex; justify-content: space-between; align-items: center;">
                        <span><i class="fas fa-receipt me-1"></i> Payment Receipts Breakdown</span>
                        <span style="font-size: 11px; font-weight: 600; color: #64748b;">{{ $creditTransactions->count() }} Payments Recorded</span>
                    </div>
                    <div class="table-wrapper" style="margin-bottom: 0;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                            <thead>
                                <tr style="background: #f0fdf4; color: #065c2e;">
                                    <th style="padding: 10px 14px; text-align: left;">Receipt #</th>
                                    <th style="padding: 10px 14px; text-align: left;">Payment Date &amp; Time</th>
                                    <th style="padding: 10px 14px; text-align: left;">Description</th>
                                    <th style="padding: 10px 14px; text-align: right;">Amount Received</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($creditTransactions as $t)
                                    <tr style="border-bottom: 1px solid #f1f5f9; {{ (isset($selectedTransaction) && $selectedTransaction->id == $t->id) ? 'background-color: #dcfce7; font-weight: 700;' : '' }}">
                                        <td style="padding: 10px 14px;">
                                            <span style="font-weight: 700; color: #086838;">#TRX-{{ $t->id }}</span>
                                            @if(isset($selectedTransaction) && $selectedTransaction->id == $t->id)
                                                <span class="badge bg-success ms-1" style="font-size: 10px; background-color: #086838 !important; color: white; padding: 2px 6px; border-radius: 4px;">Selected Receipt</span>
                                            @endif
                                        </td>
                                        <td style="padding: 10px 14px; color: #334155;">{{ $t->created_at ? $t->created_at->format('d M, Y h:i A') : 'N/A' }}</td>
                                        <td style="padding: 10px 14px; color: #475569;">{{ $t->description }}</td>
                                        <td style="padding: 10px 14px; text-align: right; color: #166534; font-weight: 700;">+₹{{ number_format($t->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- TERMS --}}
            <div class="terms-section">
                <div class="terms-heading">
                    <span>Terms &amp; Conditions {{ $isSVC ? 'of Treatment' : 'of Service' }}</span>
                    <span>{{ $branchDisplayName }}</span>
                </div>
                @if($isSVC)
                    <ul class="terms-list">
                        <li>Consultation provided by Registered Homoeopathic Practitioner.</li>
                        <li>Patient advised to seek specialist / hospital care in emergency.</li>
                        <li>Clinic not responsible for non-compliance or incomplete history.</li>
                        <li>Fees once paid are non-refundable.</li>
                        <li>Subject to Surat jurisdiction only.</li>
                        <li>Not valid for medico-legal / insurance claim purposes.</li>
                    </ul>
                @else
                    {{-- FNF (FIGURE 'n FIT) Terms & Conditions --}}
                    <ul class="terms-list">
                        <li>Nutrition programs and services based on individual body composition.</li>
                        <li>Fees once paid are strictly non-refundable and non-transferable.</li>
                        <li>Disclosure of medical conditions is mandatory before starting any program.</li>
                        <li>Missed appointments are non-compensable.</li>
                    </ul>
                @endif
            </div>

            {{-- SIGNATURE --}}
            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-label">Receiver's Name</div>
                    <div class="signature-line"></div>
                    <div class="signature-sub">Sign here</div>
                </div>
                <div class="signature-box">
                    <div class="signature-label">Authorised Signature</div>
                    <div class="signature-line"></div>
                    <div class="signature-sub">Sign here</div>
                </div>
            </div>

        </div>{{-- end invoice-body --}}
    </div>{{-- end invoice-card --}}

</body>

</html>