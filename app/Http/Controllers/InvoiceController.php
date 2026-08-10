<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Charges;
use App\Models\Invoice;
use App\Models\PatientInquiry;
use App\Models\ManageProgram;
use App\Models\PatientTransaction;
use App\Models\AccInquiry;
use App\Models\LHRInquiry;
use App\Models\HydraInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function addInvoice(Request $request)
    {
        $user = auth()->user();
        $userBranch = $user->user_branch;
        $isSuperadmin = $user->hasRole('Superadmin');
        $branchName = null;

        // 🔹 Branches
        $branches = Branch::where(function ($q) {
            $q->where('delete_status', '0')
                ->orWhere('delete_status', '');
        })
            ->when(!$isSuperadmin, function ($q) use ($userBranch) {
                $q->where('branch_id', $userBranch);
            })
            ->orderBy('branch_name', 'asc')
            ->get();

        $results = collect();
        if ($userBranch) {
            // Check for branch name to allow fallback search in 'branch' column
            $branch = Branch::where('branch_id', $userBranch)->first();
            $branchName = $branch ? $branch->branch_name : null;

            $accInquiries = AccInquiry::withoutGlobalScope('branch_restriction')->where(function ($q) {
                $q->where('delete_status', '0')->orWhere('delete_status', '');
            })
                ->where(function ($q) use ($userBranch, $branchName) {
                    $q->where('branch_id', $userBranch);
                    if ($branchName)
                        $q->orWhere('branch', $branchName);
                })
                ->get();

            foreach ($accInquiries as $p) {
                $results->push((object) [
                    'id' => $p->id,
                    'patient_id' => $p->patient_id,
                    'branch_id' => $p->branch_id,
                    'age' => $p->age,
                    'patient_name' => trim($p->patient_f_name . ' ' . $p->patient_m_name . ' ' . $p->patient_l_name),
                    'address' => $p->address,
                    'phone' => $p->phone_no,
                    'diagnosis' => $p->diagnosis ?? '',
                    'inquiry_date' => $p->inquiry_date
                ]);
            }

            // Fetch from PatientInquiry (LHR/SVC/Hydra/PP unified)
            if ($userBranch === 'LB-0007' || $userBranch === 'BH-00023' || $userBranch === 'SVC-0005' || $userBranch === 'PP-0002') {
                $pInquiriesQuery = PatientInquiry::where(function ($q) use ($userBranch, $branchName) {
                    $q->where('branch_id', $userBranch);
                    if ($branchName)
                        $q->orWhere('branch', $branchName);
                });

                if ($userBranch === 'LB-0007') {
                    // LHR might have records in LHRInquiry too
                    $lhrPatients = LHRInquiry::whereNull('deleted_at')
                        ->where('branch_id', $userBranch)->get();
                    foreach ($lhrPatients as $p) {
                        $results->push((object) [
                            'id' => $p->id,
                            'patient_id' => $p->patient_id,
                            'branch_id' => $p->branch_id,
                            'age' => $p->age,
                            'patient_name' => $p->patient_name,
                            'address' => $p->address,
                            'diagnosis' => $p->diagnosis ?? '',
                            'inquiry_date' => $p->inquiry_date,
                            'phone' => ''
                        ]);
                    }
                }

                $patientInquiries = $pInquiriesQuery->get();
                foreach ($patientInquiries as $p) {
                    $results->push((object) [
                        'id' => $p->id,
                        'patient_id' => $p->patient_id,
                        'branch_id' => $p->branch_id,
                        'age' => $p->age,
                        'patient_name' => $p->patient_name,
                        'address' => $p->address,
                        'diagnosis' => $p->diagnosis ?? '',
                        'inquiry_date' => $p->inquiry_date,
                        'phone' => method_exists($p, 'getMeta') ? $p->getMeta('phone') : null
                    ]);
                }
            }
        }

        $patients = $results->map(function ($p) {
            $name = isset($p->patient_name) ? trim((string) $p->patient_name) : '';
            if ($name === '') {
                $name = 'Patient (ID: ' . ($p->patient_id ?? 'Unknown') . ')';
            }
            $p->patient_name = $name;
            return $p;
        })->unique('id')->sortBy('patient_name')->values();

        $chargesQuery = Charges::where(function ($q) {
            $q->where('delete_status', 0)
                ->orWhere('delete_status', null)
                ->orWhere('delete_status', '');
        });

        if ($isSuperadmin) {
            $charges = $chargesQuery->get();
            $programs = ManageProgram::where(function ($q) {
                $q->where('delete_status', 0)
                    ->orWhere('delete_status', null)
                    ->orWhere('delete_status', '');
            })->get();
        } else {
            if ($userBranch === 'SVC-0005') {
                $charges = $chargesQuery->get();
                $programs = collect();
            } else {
                $charges = $chargesQuery->get();

                $programsQuery = ManageProgram::where(function ($q) {
                    $q->where('delete_status', 0)
                        ->orWhere('delete_status', null)
                        ->orWhere('delete_status', '');
                });

                if ($userBranch === 'LB-0007') {
                    $programsQuery->where(function ($q) {
                        $q->where('branch', 'LHR')
                            ->orWhere('branch', 'ALL');
                    });
                } else {
                    $programsQuery->where(function ($q) {
                        $q->where('branch', '!=', 'LHR')
                            ->orWhereNull('branch');
                    });
                }

                $programs = $programsQuery->get();
            }
        }


        $query = Invoice::with(['patient', 'branch']);

        if (!$isSuperadmin) {
            $query->where('branch_id', $userBranch);
        }


        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                    ->orWhereHas('patient', function ($pq) use ($search) {
                        $pq->where('patient_name', 'like', "%{$search}%");
                    });
                // Note: resolved_patient might involve multiple tables, 
                // but usually patient_name in basic patient_inquiry is the primary search target.
            });
        }

        // Date Filtering
        if ($request->filled('date_filter')) {
            $startDate = null;
            $endDate = null;

            switch ($request->date_filter) {
                case 'today':
                    $startDate = now()->startOfDay();
                    $endDate = now()->endOfDay();
                    break;
                case 'week':
                    $startDate = now()->startOfWeek();
                    $endDate = now()->endOfWeek();
                    break;
                case 'month':
                    $startDate = now()->startOfMonth();
                    $endDate = now()->endOfMonth();
                    break;
                case 'custom':
                    if ($request->filled('start_date') && $request->filled('end_date')) {
                        $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
                        $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay();
                    }
                    break;
            }

            if ($startDate && $endDate) {
                $query->whereBetween('invoice_date', [$startDate, $endDate]);
            }
        }

        $perPage = $request->input('per_page', 10);
        $invoices = $query->orderBy('invoice_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->appends($request->all());

        return view(
            'invoices.add_invoice',
            compact('branches', 'patients', 'charges', 'programs', 'invoices')
        );
    }


    public function storeInvoice(Request $request)
    {
        // 1. First, validate the branch_id
        $request->validate([
            'branch_id' => 'required|exists:branches,branch_id',
        ]);

        // 2. Determine the correct table for patient validation
        $branchId = $request->branch_id;
        $patientTable = 'patient_inquiry'; // Default fallback

        if ($branchId === 'LB-0007') {
            $patientTable = 'lhr_inquiries';
        } elseif ($branchId === 'BH-00023') {
            $patientTable = 'hydra_inquiries';
        } elseif ($branchId === 'SVC-0005') {
            // SVC branch uses dual tables (acc_inquirys & patient_inquiry)
            $patientTable = null;
        } elseif ($branchId === 'PP-0002') {
            // FNF PP branch — patients stored in acc_inquirys
            $patientTable = 'acc_inquirys';
        } else {
            // Default for other branches
            $patientTable = 'acc_inquirys';
        }

        // Guard: reject patient_id = 0 (unselected patient)
        if ((int) $request->patient_id === 0) {
            return redirect()->back()->withInput()->withErrors(['patient_id' => 'Please select a valid patient.']);
        }

        // Build dynamic validation rules
        $rules = [
            'patient_id' => $patientTable ? "required|exists:$patientTable,id" : "required",
            'invoice_no' => 'required',
            'invoice_date' => 'required|date',
            'address' => 'nullable',
            'discount' => 'nullable|numeric|min:0',
            'given_payment' => 'nullable|numeric|min:0',
            'pending_due' => 'nullable|numeric|min:0',
        ];

        // For SVC branch, charges are required instead of program
        if ($branchId === 'SVC-0005') {
            $rules['charges'] = 'required|array';
            $rules['charges.*'] = 'exists:charges,id';

            // Debug: Log incoming charges data
            \Log::info('SVC Invoice - Charges data:', [
                'charges' => $request->charges,
                'branch_id' => $branchId
            ]);
        } else {
            $rules['program_ids'] = 'nullable|array';
            $rules['program_ids.*'] = 'exists:manage_programs,id';
            $rules['charges'] = 'nullable|array';
            $rules['charges.*'] = 'exists:charges,id';
        }

        $request->validate($rules);

        // 3. Manual check for SVC-0005 if it's the dual-table branch
        if ($branchId === 'SVC-0005') {
            $existsInAcc = \DB::table('acc_inquirys')->where('id', $request->patient_id)->exists();
            $existsInPatient = \DB::table('patient_inquiry')->where('id', $request->patient_id)->exists();
            if (!$existsInAcc && !$existsInPatient) {
                return redirect()->back()->withInput()->withErrors(['patient_id' => 'The selected patient id is invalid.']);
            }

            // Additional manual validation for charges
            $validCharges = [];
            if ($request->charges) {
                foreach ($request->charges as $chargeId) {
                    if (!empty($chargeId) && is_numeric($chargeId)) {
                        $validCharges[] = $chargeId;
                    }
                }
            }

            if (empty($validCharges)) {
                return redirect()->back()->withInput()->with('error', 'Please select at least one charge.');
            }

            \Log::info('SVC Invoice - Valid charges found:', ['valid_charges' => $validCharges]);
        }

        // Process programs for non-SVC branches
        $validPrograms = [];
        if ($request->selected_assigned_programs) {
            foreach ($request->selected_assigned_programs as $jsonStr) {
                $decoded = json_decode($jsonStr, true);
                if ($decoded && !empty($decoded['program_name'])) {
                    $validPrograms[] = [
                        'program_name' => $decoded['program_name'],
                        'session' => $decoded['session'],
                        'months' => $decoded['months'],
                        'price' => $decoded['price']
                    ];
                }
            }
        }

        if (empty($validPrograms) && $request->program_ids) {
            foreach ($request->program_ids as $programId) {
                if (!empty($programId) && is_numeric($programId)) {
                    $program = ManageProgram::find($programId);
                    if ($program) {
                        $validPrograms[] = [
                            'program_id' => $programId,
                            'program_name' => $program->program_name,
                            'price' => $program->program_price
                        ];
                    }
                }
            }
        }

        try {
            DB::beginTransaction();

            // Get patient and branch details
            $patient = null;
            if ($branchId === 'LB-0007') {
                $patient = LHRInquiry::find($request->patient_id);
            } elseif ($branchId === 'BH-00023') {
                $patient = HydraInquiry::find($request->patient_id);
            } elseif ($branchId === 'SVC-0005') {
                $patient = AccInquiry::find($request->patient_id) ?: PatientInquiry::find($request->patient_id);
            } else {
                $patient = AccInquiry::find($request->patient_id) ?: PatientInquiry::find($request->patient_id);
            }

            $branch = Branch::where('branch_id', $request->branch_id)->first();

            // Generate UNIQUE filename with timestamp to avoid conflicts
            $timestamp = now()->format('His');
            $invoiceFile = $this->generateInvoiceFilename($patient, $branch, $request->invoice_no, $timestamp);

            // Generate UNIQUE invoice number if same patient creates multiple invoices
            $baseInvoiceNo = $request->invoice_no;
            $finalInvoiceNo = $baseInvoiceNo;

            // Check if invoice_no already exists and append counter if needed
            $counter = 1;
            while (Invoice::where('invoice_no', $finalInvoiceNo)->exists()) {
                $finalInvoiceNo = $baseInvoiceNo . $counter;
                $counter++;
            }

            // Filter and process charges
            $validCharges = [];
            if ($request->charges) {
                foreach ($request->charges as $chargeId) {
                    if (!empty($chargeId) && is_numeric($chargeId)) {
                        $charge = Charges::find($chargeId);
                        if ($charge) {
                            $validCharges[] = [
                                'charge_id' => $chargeId,
                                'charge_name' => $charge->charges_name,
                                'price' => $charge->charges_price
                            ];
                        }
                    }
                }
            }

            // ALWAYS create NEW invoice record (never update existing)
            $invoice = Invoice::create([
                'branch_id' => $request->branch_id,
                'patient_id' => $request->patient_id,
                'program_id' => is_array($request->program_ids) ? ($request->program_ids[0] ?? null) : null, // For backward compatibility if needed
                'invoice_no' => $finalInvoiceNo, // Use unique invoice number
                'invoice_date' => $request->invoice_date,
                'address' => $request->address,
                'phone' => $request->phone,
                'price' => $request->total_payment,
                'pending_due' => $request->pending_due ?? 0,
                'total_payment' => $request->total_payment,
                'discount' => $request->discount ?? 0,
                'given_payment' => $request->given_payment ?? 0,
                'due_payment' => $request->due_payment ?? 0,
                'invoice_file' => $invoiceFile,
                'charges_data' => !empty($validCharges) ? $validCharges : null,
                'programs_data' => !empty($validPrograms) ? $validPrograms : null,
            ]);

            // 0. Consolidation: Reset previous dues if they are carried forward to prevent double-counting
            if ($request->pending_due > 0) {
                Invoice::where('patient_id', $request->patient_id)
                    ->where('id', '!=', $invoice->id)
                    ->update(['due_payment' => 0, 'pending_due' => 0]);
            }

            // 1. Create Debit Transaction (Invoice Amount)
            if ($request->branch_id === 'LB-0007') {
                $descPrefix = 'LHR Service - Invoice Generated: ';
            } elseif ($request->branch_id === 'BH-00023') {
                $descPrefix = 'Hydra Service - Invoice Generated: ';
            } elseif ($request->branch_id === 'SVC-0005') {
                $descPrefix = 'SVC Service - Invoice Generated: ';
            } elseif ($request->branch_id === 'PP-0002') {
                $descPrefix = 'FNF PP Service - Invoice Generated: ';
            } else {
                $descPrefix = 'FNF Service - Invoice Generated: ';
            }

            // Construct detailed description with item names
            $itemNames = [];
            foreach ($validPrograms as $p) {
                $itemNames[] = $p['program_name'];
            }
            foreach ($validCharges as $c) {
                $itemNames[] = $c['charge_name'];
            }
            $itemsDetail = !empty($itemNames) ? ' (' . implode(', ', $itemNames) . ')' : '';

            $txDate = !empty($request->invoice_date) ? \Carbon\Carbon::parse($request->invoice_date . ' ' . now()->format('H:i:s'))->format('Y-m-d H:i:s') : now();

            PatientTransaction::create([
                'branch_id' => $request->branch_id,
                'patient_id' => $request->patient_id,
                'invoice_id' => $invoice->id,
                'program_id' => is_array($request->program_ids) ? ($request->program_ids[0] ?? null) : null,
                'type' => 'debit',
                'amount' => $request->total_payment - ($request->discount ?? 0),
                'description' => $descPrefix . $invoice->invoice_no . $itemsDetail,
                'created_at' => $txDate,
                'updated_at' => $txDate,
            ]);

            // 2. Create Credit Transaction (Given Payment) if any payment is made
            if ($request->given_payment > 0) {
                if ($request->branch_id === 'LB-0007') {
                    $payDesc = 'LHR Service Payment Received for Invoice: ';
                } elseif ($request->branch_id === 'BH-00023') {
                    $payDesc = 'Hydra Service Payment Received for Invoice: ';
                } elseif ($request->branch_id === 'SVC-0005') {
                    $payDesc = 'SVC Service Payment Received for Invoice: ';
                } elseif ($request->branch_id === 'PP-0002') {
                    $payDesc = 'FNF PP Service Payment Received for Invoice: ';
                } else {
                    $payDesc = 'FNF Service Payment Received for Invoice: ';
                }
                PatientTransaction::create([
                    'branch_id' => $request->branch_id,
                    'patient_id' => $request->patient_id,
                    'invoice_id' => $invoice->id,
                    'program_id' => is_array($request->program_ids) ? ($request->program_ids[0] ?? null) : null,
                    'type' => 'credit',
                    'amount' => $request->given_payment,
                    'description' => $payDesc . $invoice->invoice_no . $itemsDetail,
                    'created_at' => $txDate,
                    'updated_at' => $txDate,
                ]);
            }

            DB::commit();

            Session::flash('success', 'Invoice generated successfully! New entry created in database.');
            return redirect()->route('add.invoice');

        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'Error generating invoice: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    private function generateInvoiceFilename($patient, $branch, $invoiceNo, $timestamp = '')
    {
        $patientName = preg_replace('/[^A-Za-z0-9]/', '', $patient->patient_name ?? 'Patient');
        $branchName = preg_replace('/[^A-Za-z0-9]/', '', $branch->branch_name ?? 'Branch');
        $currentDate = now()->format('d-m-Y');

        // Add timestamp to make filename unique
        $uniquePart = $timestamp ? '-' . $timestamp : '';

        return $patientName . $branchName . '-' . $invoiceNo . $uniquePart . '-' . $currentDate . '.pdf';
    }

    public function viewInvoice(Request $request, $id)
    {
        $invoice = Invoice::with(['transactions'])->findOrFail($id);
        $transactionId = $request->query('transaction_id');
        $selectedTransaction = null;
        if ($transactionId) {
            $selectedTransaction = PatientTransaction::find($transactionId);
        }
        return view('invoices.receipt', compact('invoice', 'selectedTransaction'));
    }

    public function downloadInvoice(Request $request, $id)
    {
        $invoice = Invoice::with(['transactions'])->findOrFail($id);
        $transactionId = $request->query('transaction_id');
        $selectedTransaction = null;
        if ($transactionId) {
            $selectedTransaction = PatientTransaction::find($transactionId);
        }

        // Generate filename if invoice_file is null
        if (empty($invoice->invoice_file)) {
            $patient = null;
            
            // Get patient based on patient_id
            if ($invoice->patient_id) {
                // Try to find patient from different tables
                $patient = AccInquiry::find($invoice->patient_id);
                if (!$patient) $patient = PatientInquiry::find($invoice->patient_id);
                if (!$patient) $patient = LHRInquiry::find($invoice->patient_id);
                if (!$patient) $patient = HydraInquiry::find($invoice->patient_id);
            }
            
            // Get branch info
            $branch = Branch::where('branch_id', $invoice->branch_id)->first();
            
            // Generate filename
            $filename = $this->generateInvoiceFilename($patient, $branch, $invoice->invoice_no);
        } else {
            $filename = $invoice->invoice_file;
        }
        if ($selectedTransaction) {
            $filename = 'Receipt-TRX' . $selectedTransaction->id . '-' . $filename;
        }
        $pdf = Pdf::loadView('invoices.receipt_pdf', compact('invoice', 'selectedTransaction'));
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Arial'
        ]);

        return $pdf->download($filename);
    }

    public function getPatientPrograms(Request $request, $patientId)
    {
        // Fetch the patient record to get their patient_id literal (e.g., SVC-00001)
        $patientLiteralId = $request->query('literal_id');

        if (!$patientLiteralId) {
            $acc = AccInquiry::find($patientId);
            if ($acc) {
                $patientLiteralId = $acc->patient_id;
            } else {
                $pi = PatientInquiry::find($patientId);
                if ($pi)
                    $patientLiteralId = $pi->patient_id;
                else {
                    $lhr = LHRInquiry::find($patientId);
                    if ($lhr)
                        $patientLiteralId = $lhr->patient_id;
                    else {
                        $hydra = HydraInquiry::find($patientId);
                        if ($hydra)
                            $patientLiteralId = $hydra->patient_id;
                    }
                }
            }
        }

        // Fetch all invoices for this patient to calculate total outstanding due
        $allInvoices = Invoice::where('patient_id', $patientId)->get();
        $totalDue = $allInvoices->sum('due_payment');

        // Fetch invoices for this patient that have a program linked for history display
        $invoicesWithPrograms = $allInvoices->whereNotNull('program_id');

        // Also consider SVC style charges_data
        $invoicesWithCharges = $allInvoices->whereNotNull('charges_data');

        // Group and count programs
        $programCounts = [];
        foreach ($invoicesWithPrograms as $invoice) {
            if ($invoice->program) {
                $programName = $invoice->program->program_name;
                $key = $programName . ' (' . $invoice->program->program_price . ')';

                if (!isset($programCounts[$key])) {
                    $programCounts[$key] = 0;
                }
                $programCounts[$key]++;
            }
        }

        // Group and count charges (for SVC)
        $chargeCounts = [];
        foreach ($invoicesWithCharges as $invoice) {
            if (is_array($invoice->charges_data)) {
                foreach ($invoice->charges_data as $charge) {
                    $chargeName = $charge['charge_name'] ?? 'Unknown Charge';
                    $price = $charge['price'] ?? '0';
                    $key = $chargeName . ' (' . $price . ')';

                    if (!isset($chargeCounts[$key])) {
                        $chargeCounts[$key] = 0;
                    }
                    $chargeCounts[$key]++;
                }
            }
        }

        // Format results for response
        $history = [];
        foreach ($programCounts as $name => $count) {
            $history[] = $name . ' x ' . $count;
        }

        foreach ($chargeCounts as $name => $count) {
            $history[] = $name . ' x ' . $count;
        }

        // --- FETCH ASSIGNED PROGRAMS FROM DIET H/O (Opt table) ---
        $assignedPrograms = [];
        $lhrArea = null;
        $lhrSession = null;

        if ($patientLiteralId) {
            $opt = \App\Models\Opt::where('patient_id', $patientLiteralId)
                ->where(function ($q) {
                    $q->where('delete_status', '0')
                        ->orWhereNull('delete_status')
                        ->orWhere('delete_status', '');
                })
                ->orderBy('created_at', 'desc')
                ->first();

            if ($opt) {
                $programsJson = $opt->getMetaValue('programs_array');
                if ($programsJson) {
                    $assignedPrograms = json_decode($programsJson, true);
                }
            }

            // Fetch LHR specific data if applicable
            $lhr = LHRInquiry::where('patient_id', $patientLiteralId)->first();
            if ($lhr) {
                $lhrArea = $lhr->area;
                $lhrSession = $lhr->session;
            }
        }

        // --- FIRST CONSULTATION LOGIC ---
        // If no invoices exist for this patient, it's their first consultation.
        $isFirstConsultation = $allInvoices->count() == 0;

        return response()->json([
            'program_history' => $history,
            'assigned_programs' => $assignedPrograms,
            'total_due' => $totalDue,
            'is_first_consultation' => $isFirstConsultation,
            'lhr_area' => $lhrArea,
            'lhr_session' => $lhrSession
        ]);
    }

    // public function getPatientsByBranch(Request $request)
    // {
    //     $branchId = $request->branch_id;
    //     $user = auth()->user();
    //     $isSuperadmin = $user->hasRole('Superadmin');

    //     if (!$branchId && !$isSuperadmin) {
    //          return response()->json(['success' => false, 'message' => 'Branch ID is required'], 400);
    //     }

    //     $patients = collect();

    //     // 1. AccInquiry
    //     $patients = $patients->merge(
    //         AccInquiry::where(function ($q) {
    //                 $q->where('delete_status', '0')
    //                   ->orWhere('delete_status', '');
    //             })
    //             ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
    //             ->get([
    //                 'id',
    //                 'patient_id',
    //                 'branch_id',
    //                 'age',
    //                 'address',
    //                 'phone_no as phone', // Standardize to phone
    //                 'diagnosis',
    //                 'inquiry_date',
    //                 DB::raw("CONCAT(patient_f_name,' ',patient_l_name) as patient_name")
    //             ])
    //     );

    //     // 2. PatientInquiry
    //     $patients = $patients->merge(
    //          PatientInquiry::when($branchId, fn ($q) => $q->where('branch_id', $branchId))
    //             ->get([
    //                 'id',
    //                 'patient_id',
    //                 'branch_id',
    //                 'age',
    //                 'address',
    //                 'diagnosis',
    //                 'inquiry_date',
    //                 'patient_name'
    //             ])
    //     );

    //     // 3. LHRInquiry
    //     $patients = $patients->merge(
    //          LHRInquiry::whereNull('deleted_at')
    //             ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
    //             ->get([
    //                 'id',
    //                 'patient_id',
    //                 'branch_id',
    //                 'age',
    //                 'address',
    //                 'inquiry_date',
    //                 'patient_name'
    //             ])
    //     );

    //     // 4. HydraInquiry
    //     $patients = $patients->merge(
    //         HydraInquiry::when($branchId, fn ($q) => $q->where('branch_id', $branchId))
    //             ->get([
    //                 'id',
    //                 'patient_id',
    //                 'branch_id',
    //                 'age',
    //                 'address',
    //                 'phone_number as phone', // Standardize
    //                 'inquiry_date',
    //                 'patient_name'
    //             ])
    //     );

    //     if ($patients->isEmpty()) {
    //         return response()->json([
    //             'success' => true,
    //             'patients' => []
    //         ]);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'patients' => $patients->sortBy('patient_name')->values()
    //     ]);
    // }

    public function getPatientsByBranch(Request $request)
    {
        $branchId = $request->branch_id;
        $user = auth()->user();
        $isSuperadmin = $user->hasRole('Superadmin');

        if (!$branchId && !$isSuperadmin) {
            return response()->json(['success' => false], 400);
        }

        // Check for branch name to allow fallback search in 'branch' column
        $branch = \App\Models\Branch::where('branch_id', $branchId)->first();
        $branchName = $branch ? $branch->branch_name : null;

        $results = collect();

        // 1️⃣ LHR Branch (LB-0007)
        if ($branchId === 'LB-0007') {
            $lhrPatients = LHRInquiry::whereNull('deleted_at')
                ->where(function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId)
                        ->orWhere('branch', 'LHR')
                        ->orWhere('branch', 'LB-0007');
                })
                ->get();

            foreach ($lhrPatients as $p) {
                $results->push((object) [
                    'id' => $p->id,
                    'patient_id' => $p->patient_id,
                    'branch_id' => $p->branch_id,
                    'age' => $p->age,
                    'patient_name' => $p->patient_name,
                    'address' => $p->address,
                    'diagnosis' => $p->diagnosis ?? '',
                    'inquiry_date' => $p->inquiry_date,
                    'phone' => '' // LHR usually doesn't have phone in main table
                ]);
            }
        }
        // 2️⃣ Hydra Branch (BH-00023)
        elseif ($branchId === 'BH-00023') {
            $hydraPatients = HydraInquiry::where(function ($q) use ($branchId) {
                $q->where('branch_id', $branchId)
                    ->orWhere('branch', 'Hydra')
                    ->orWhere('branch', 'BH-00023');
            })
                ->get();

            foreach ($hydraPatients as $p) {
                $results->push((object) [
                    'id' => $p->id,
                    'patient_id' => $p->patient_id,
                    'branch_id' => $p->branch_id,
                    'age' => $p->age,
                    'patient_name' => $p->patient_name,
                    'address' => $p->address,
                    'diagnosis' => $p->diagnosis ?? '',
                    'inquiry_date' => $p->inquiry_date,
                    'phone' => $p->phone_number
                ]);
            }
        }
        // 3️⃣ SVC Branch (SVC-0005) or Fallback
        else {
            // Fetch from AccInquiry (without scope so PP-0002 branch users see their own data)
            $accPatients = AccInquiry::withoutGlobalScope('branch_restriction')->where(function ($q) {
                $q->where('delete_status', '0')
                    ->orWhere('delete_status', '');
            })
                ->where(function ($q) use ($branchId, $branchName) {
                    $q->where('branch_id', $branchId);
                    if ($branchName)
                        $q->orWhere('branch', $branchName);
                })
                ->get();

            foreach ($accPatients as $p) {
                $results->push((object) [
                    'id' => $p->id,
                    'patient_id' => $p->patient_id,
                    'branch_id' => $p->branch_id,
                    'age' => $p->age,
                    'patient_name' => trim($p->patient_f_name . ' ' . $p->patient_m_name . ' ' . $p->patient_l_name),
                    'address' => $p->address,
                    'phone' => $p->phone_no,
                    'diagnosis' => $p->diagnosis ?? '',
                    'inquiry_date' => $p->inquiry_date
                ]);
            }

            // Fetch from PatientInquiry
            $patientInquiries = PatientInquiry::where(function ($q) use ($branchId, $branchName) {
                $q->where('branch_id', $branchId);
                if ($branchName)
                    $q->orWhere('branch', $branchName);
            })
                ->get();

            foreach ($patientInquiries as $p) {
                $results->push((object) [
                    'id' => $p->id,
                    'patient_id' => $p->patient_id,
                    'branch_id' => $p->branch_id,
                    'age' => $p->age,
                    'patient_name' => $p->patient_name,
                    'address' => $p->address,
                    'diagnosis' => $p->diagnosis ?? '',
                    'inquiry_date' => $p->inquiry_date,
                    'phone' => method_exists($p, 'getMeta') ? $p->getMeta('phone') : null
                ]);
            }
        }

        // Clean names and ensure unique
        $patients = $results->map(function ($p) {
            $name = isset($p->patient_name) ? trim((string) $p->patient_name) : '';
            if ($name === '') {
                $name = 'Patient (ID: ' . ($p->patient_id ?? 'Unknown') . ')';
            }
            $p->patient_name = $name;
            return $p;
        })->unique('id')->sortBy('patient_name')->values();

        return response()->json([
            'success' => true,
            'patients' => $patients
        ]);
    }


    public function addPayment(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $invoice = Invoice::findOrFail($request->invoice_id);
            $paymentAmount = $request->amount;

            // Validation: Cannot pay more than due
            if ($paymentAmount > $invoice->due_payment) {
                return redirect()->back()->with('error', 'Payment amount cannot exceed the pending due amount (' . $invoice->due_payment . ').');
            }

            // 1. Update Invoice
            $invoice->given_payment += $paymentAmount;
            $invoice->due_payment -= $paymentAmount;

            // Also sync pending_due if it's meant to be the same (optional but safer for legacy)
            if ($invoice->pending_due > 0) {
                $invoice->pending_due = max(0, $invoice->pending_due - $paymentAmount);
            }

            $invoice->save();

            // 2. Create Transaction (Credit)
            $transaction = PatientTransaction::create([
                'branch_id' => $invoice->branch_id,
                'patient_id' => $invoice->patient_id,
                'invoice_id' => $invoice->id,
                'program_id' => $invoice->program_id,
                'type' => 'credit',
                'amount' => $paymentAmount,
                'description' => 'Due Payment Received for Invoice: ' . $invoice->invoice_no,
                'created_at' => $request->payment_date . ' ' . now()->format('H:i:s'),
            ]);

            DB::commit();

            $receiptUrl = route('view.invoice', ['id' => $invoice->id, 'transaction_id' => $transaction->id]);
            Session::flash('success', 'Payment of ₹' . number_format($paymentAmount, 2) . ' received! Payment Receipt #' . $transaction->id . ' generated. <a href="' . $receiptUrl . '" target="_blank" class="btn btn-sm btn-success ms-2"><i class="fas fa-print me-1"></i> Print Receipt</a>');
            Session::flash('receipt_url', $receiptUrl);
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'Error adding payment: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function deleteInvoice($id)
    {
        try {
            DB::beginTransaction();

            $invoice = Invoice::findOrFail($id);

            // Delete associated transactions
            PatientTransaction::where('invoice_id', $invoice->id)->delete();

            // Delete the invoice record
            $invoice->delete();

            DB::commit();

            Session::flash('success', 'Invoice and associated transactions deleted successfully.');
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'Error deleting invoice: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
