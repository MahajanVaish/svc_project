<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $invoice->invoice_no }}</title>
    <style>
        @page {
            margin: 0.5cm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Segoe UI', Arial, sans-serif;
            background-color: #ffffff;
            color: #1e293b;
            line-height: 1.5;
            font-size: 13px;
        }

        /* ── HEADER ── */
        .invoice-header {
            background-color: #086838;
            padding: 30px;
            color: #ffffff;
            width: 100%;
            clear: both;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-title h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .invoice-title h3 {
            font-size: 13px;
            color: #d1d5db;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .company-address {
            text-align: right;
            font-size: 11px;
            color: #e5e7eb;
            line-height: 1.6;
        }

        /* ── BODY ── */
        .invoice-body {
            padding: 30px;
        }

        /* ── INFO SECTION ── */
        .info-table {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: separate;
            border-spacing: 10px 0;
        }

        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 15px;
            width: 50%;
            vertical-align: top;
            border-radius: 8px;
        }

        .info-box h4 {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #086838;
            margin-bottom: 12px;
            border-bottom: 1px solid #dcfce7;
            padding-bottom: 8px;
        }

        .info-row {
            margin-bottom: 8px;
            font-size: 12px;
        }

        .info-label {
            color: #64748b;
            font-weight: bold;
            display: inline-block;
            width: 90px;
        }

        .info-value {
            color: #1e293b;
        }

        /* ── TABLE ── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            border: 1px solid #e2e8f0;
        }

        .items-table th {
            background: #f0fdf4;
            border-bottom: 2px solid #086838;
            color: #065c2e;
            font-weight: bold;
            font-size: 12px;
            padding: 12px 15px;
            text-align: left;
        }

        .items-table td {
            padding: 12px 15px;
            font-size: 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .summary-row {
            background: #f8fafc;
        }

        .summary-row td {
            font-weight: bold;
        }

        .grand-total {
            background: #f0fdf4;
            color: #086838;
            font-size: 14px;
        }

        /* ── TERMS ── */
        .terms-section {
            background: #f0fdf4;
            border: 1px solid #dcfce7;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .terms-heading {
            font-weight: bold;
            font-size: 13px;
            color: #086838;
            margin-bottom: 10px;
            border-bottom: 1px solid #bbfcce;
            padding-bottom: 8px;
        }

        .terms-list {
            margin-left: 20px;
        }

        .terms-list li {
            font-size: 11px;
            color: #166534;
            margin-bottom: 5px;
        }

        /* ── SIGNATURE ── */
        .signature-table {
            width: 100%;
            margin-top: 30px;
        }

        .signature-box {
            text-align: center;
            width: 50%;
        }

        .signature-line {
            border-top: 1px solid #94a3b8;
            width: 180px;
            margin: 40px auto 10px;
        }

        .signature-label {
            font-size: 12px;
            font-weight: bold;
            color: #475569;
        }

        .signature-sub {
            font-size: 10px;
            color: #94a3b8;
        }
    </style>
</head>

<body>

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
            $branchAddress = "Priyanka Intercity, Puna Kumbhariya Road, Magob, Surat. Phone: 8758875020";
        } elseif ($isBardoli) {
            $branchAddress = "Sarthak Villa, Tribhovan Nagar, B/H V K Tower, Mahatma Gandhi Rd, Bardoli, Gujarat 394601";
        } elseif ($isSuratMain) {
            $branchAddress = "211/212, Millenium Point, Block-A, Laldarwaja Road, Station, Surat. Phone: +91-99133 48004";
        }
    @endphp

    <div class="invoice-header">
        <table class="header-table">
            <tr>
                <td class="invoice-title">
                    <h2>{{ $branchDisplayName }}</h2>
                    <h3>Invoice</h3>
                    @if($isSVC)
                        <div style="font-size:10px; color:#d1d5db; margin-top:5px;">
                            Consultant: Dr. Manish Akbari (BHMS)<br>
                            Reg. No.: G-9088 (Gujarat Homoeopathic Council)
                        </div>
                    @endif
                </td>
                <td class="company-address">
                    {!! nl2br(e($branchAddress)) !!}
                    @if(!empty($invoice->branch->phone_no) && !$isSVC && !$isSuratMain && !$isBardoli)
                        <br>📞 {{ $invoice->branch->phone_no }}
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="invoice-body">
        <table class="info-table">
            <tr>
                <td class="info-box">
                    <h4>Patient Details</h4>
                    <div class="info-row">
                        <span class="info-label">Name:</span>
                        <span class="info-value">{{ $invoice->resolved_patient->patient_name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Address:</span>
                        <span class="info-value">{{ $invoice->address ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Mobile No.:</span>
                        <span class="info-value">{{ $invoice->phone ?: '—' }}</span>
                    </div>
                </td>
                <td class="info-box">
                    <h4>Invoice Details</h4>
                    <div class="info-row">
                        <span class="info-label">Invoice No.:</span>
                        <span class="info-value">{{ $invoice->invoice_no }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Invoice Date:</span>
                        <span
                            class="info-value">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</span>
                    </div>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 8%">No.</th>
                    <th style="width: 68%">Description</th>
                    <th style="width: 24%">Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $counter = 1; @endphp
                @php $isIPDInvoice = str_starts_with($invoice->invoice_no, 'IPD-'); @endphp

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
                        if (is_string($programsData))
                            $programsData = json_decode($programsData, true);

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
                                        <br><span style="color: #6c757d; font-size: 10px;">
                                            @if(!empty($program['session']))
                                                Session: {{ $program['session'] }}
                                            @endif
                                            @if(!empty($program['session']) && !empty($program['months'])) | @endif
                                            @if(!empty($program['months'])) Months: {{ $program['months'] }} @endif
                                        </span>
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
                                        <br><span style="color: #6c757d; font-size: 10px;">
                                            @if(!empty($program['session']))
                                                Session: {{ $program['session'] }}
                                            @endif
                                            @if(!empty($program['session']) && !empty($program['months'])) | @endif
                                            @if(!empty($program['months'])) Months: {{ $program['months'] }} @endif
                                        </span>
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
                        if (is_string($chargesData))
                            $chargesData = json_decode($chargesData, true);
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

                @for ($i = 0; $i < max(0, 3 - $counter); $i++)
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor

                <tr class="summary-row">
                    <td></td>
                    <td style="text-align: right; padding-right: 25px; font-weight: bold; color: #444;">Total Amount</td>
                    <td style="font-weight: bold;">₹{{ number_format($invoice->total_payment, 2) }}</td>
                </tr>
                @if ($invoice->discount > 0)
                <tr class="summary-row">
                    <td></td>
                    <td style="text-align: right; padding-right: 25px; font-weight: bold; color: #444;">Discount</td>
                    <td style="font-weight: bold; color: #e63946;">−₹{{ number_format($invoice->discount, 2) }}</td>
                </tr>
                @endif
                <tr class="summary-row">
                    <td></td>
                    <td style="text-align: right; padding-right: 25px; font-weight: bold; color: #444;">Paid Amount</td>
                    <td style="font-weight: bold;">₹{{ number_format($invoice->given_payment, 2) }}</td>
                </tr>
                <tr class="summary-row grand-total">
                    <td></td>
                    <td style="text-align: right; padding-right: 25px; text-transform: uppercase; font-weight: bold;">Due Payment</td>
                    <td>₹{{ number_format($invoice->due_payment, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="terms-section">
            <div class="terms-heading">
                Terms & Conditions {{ $isSVC ? 'of Treatment' : 'of Service' }}
            </div>
            <ul class="terms-list">
                @if($isSVC)
                    <li>Consultation provided by Registered Homoeopathic Practitioner.</li>
                    <li>Patient advised to seek specialist / hospital care in emergency.</li>
                    <li>Clinic not responsible for non-compliance or incomplete history.</li>
                    <li>Fees once paid are non-refundable.</li>
                    <li>Subject to Surat jurisdiction only.</li>
                @else
                    <li>Nutrition programs and services based on individual body composition.</li>
                    <li>Fees once paid are strictly non-refundable and non-transferable.</li>
                    <li>Disclosure of medical conditions is mandatory before starting any program.</li>
                    <li>Missed appointments are non-compensable.</li>
                @endif
            </ul>
        </div>

        <table class="signature-table">
            <tr>
                <td class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-label">Receiver's Name</div>
                </td>
                <td class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-label">Authorised Signature</div>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>