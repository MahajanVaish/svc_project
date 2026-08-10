<?php

namespace App\Http\Controllers\patients;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Charges;
use App\Models\FollowupMeta;
use App\Models\Followups;
use App\Models\PatientInquiry;
use App\Models\PatientTreatment;
use App\Models\User;
use App\Models\Invoice;
use App\Models\PatientTransaction;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class SVCController extends Controller
{
    //  public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function addInquiry()
    {
        $accUser = User::where('email', auth()->user()->email)->first();
        // dd($accUser);
        if (!$accUser) {
            dd("ACC user not found");
        }
        $branches = Branch::all();   // <-- missing

        $branchName = optional($accUser->branch)->branch_name;

        $branchId = auth()->user()->user_branch;

        // Get doctors (users with doctor role)
        $doctors = User::where('user_role', 6)
            ->orWhereHas('roles', function ($query) {
                $query->where('name', 'Doctor');
            })
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        // Get active charges
        $charges = Charges::where(function ($query) {
            $query->whereIn('delete_status', ['0', ''])->orWhereNull('delete_status');
        })
            ->orderBy('charges_name')
            ->get();

        return view('branches.add_inquiry', compact('branchId', 'branchName', 'branches', 'doctors', 'charges'));
    }




    public function searchSvcPatient(Request $request)
    {
        $user = auth()->user();
        $query = PatientInquiry::query();

        if ($user->user_role == 2) {
            $query->where('branch', 'SVC');
        } elseif (!empty($user->user_branch)) {
            $query->where('branch_id', $user->user_branch);
        }

        if (!empty($request->name_search)) {
            $query->where('patient_name', 'like', '%' . $request->name_search . '%');
        }

        if (!empty($request->global_search)) {
            $search = trim($request->global_search);

            $query->where(function ($q) use ($search) {
                $q->where('patient_id', 'like', "%$search%")
                    ->orWhere('patient_name', 'like', "%$search%")
                    ->orWhere('address', 'like', "%$search%")
                    ->orWhere('diagnosis', 'like', "%$search%")
                    ->orWhere('age', 'like', "%$search%");
            });
        }

        $perPage = (int) $request->get('per_page', 10);
        $patients = $query
            ->with([
                'metas',
                'treatments' => fn($q) => $q->where('type', 'indoor')->whereNull('followup_id'),
            ])
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->appends($request->all());

        if ($request->ajax()) {
            return view('branches.svc_patients', compact('patients'))->render();
        }

        return view('branches.svc_patients', compact('patients'));
    }

    public function indoorPatients(Request $request)
    {
        $user = auth()->user();
        $query = PatientInquiry::with([
            'metas',
            'invoice',
            'treatments' => function ($q) {
                $q->where('type', 'indoor')->whereNull('followup_id');
            }
        ]);

        // Filter by IPD status in metas
        $query->whereHas('metas', function ($q) {
            $q->where('meta_key', 'pt_status')->where('meta_value', 'IPD');
        });

        if ($user->user_role == 2) {
            $query->where('branch', 'SVC');
        } elseif (!empty($user->user_branch)) {
            $query->where('branch_id', $user->user_branch);
        }

        if (!empty($request->global_search)) {
            $search = trim($request->global_search);
            $query->where(function ($q) use ($search) {
                $q->where('patient_id', 'like', "%$search%")
                    ->orWhere('patient_name', 'like', "%$search%")
                    ->orWhere('address', 'like', "%$search%")
                    ->orWhere('diagnosis', 'like', "%$search%")
                    ->orWhere('age', 'like', "%$search%");
            });
        }

        $perPage = (int) $request->get('per_page', 10);
        $patients = $query->orderBy('id', 'desc')
            ->paginate($perPage)
            ->appends($request->all());

        if ($request->ajax()) {
            return view('branches.indoor_patients', compact('patients'))->render();
        }

        return view('branches.indoor_patients', compact('patients'));
    }

    // This is for /dashboard route (branch dashboard)
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('Superadmin')) {
            // Superadmin sees all branches - redirect to admin dashboard

            return redirect()->route('admin.dashboard');
        } else {
            // Non-superadmin users see only their branch
            $branch = Branch::where('branch_id', $user->user_branch)->first();

            if (!$branch) {
                // If no branch assigned, show error or redirect
                $branches = collect();
                return view('dashboard', compact('branches'))->with('error', 'No branch assigned to your account.');
            }

            // Pass single branch as collection
            $branches = collect([$branch]);

            return view('dashboard', compact('branches'));
        }
    }

    public function getSuggestions()
    {
        try {
            // Get complaints from medical_conditions table
            $complaints = \App\Models\MedicalCondition::getComplaints()
                ->pluck('name')
                ->toArray();

            // Get diagnoses from medical_conditions table
            $diagnoses = \App\Models\MedicalCondition::getDiagnoses()
                ->pluck('name')
                ->toArray();

            return response()->json([
                'complaints' => $complaints,
                'diagnoses' => $diagnoses
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching suggestions: ' . $e->getMessage());
            return response()->json([
                'complaints' => [],
                'diagnoses' => []
            ]);
        }
    }

    public function getMedicineSuggestions()
    {
        try {
            // Step 1: Get most frequent dose per medicine (regardless of timing)
            $doseResults = \App\Models\PatientTreatment::select(
                    'medicine',
                    'dose',
                    \DB::raw('COUNT(*) as frequency')
                )
                ->whereNotNull('medicine')
                ->where('medicine', '!=', '')
                ->groupBy('medicine', 'dose')
                ->orderBy('frequency', 'desc')
                ->get();

            // Step 2: Get most frequent NON-EMPTY timing per medicine
            $timingResults = \App\Models\PatientTreatment::select(
                    'medicine',
                    'timing',
                    \DB::raw('COUNT(*) as frequency')
                )
                ->whereNotNull('medicine')
                ->where('medicine', '!=', '')
                ->whereNotNull('timing')
                ->where('timing', '!=', '')
                ->groupBy('medicine', 'timing')
                ->orderBy('frequency', 'desc')
                ->get()
                ->groupBy('medicine')
                ->map(fn($rows) => $rows->first()->timing); // most frequent timing per medicine

            $medicines       = [];
            $medicineDoses   = (object) [];
            $medicineTimings = (object) [];

            foreach ($doseResults as $row) {
                $medName  = trim($row->medicine);
                $doseVal  = trim($row->dose ?? '');

                if (!in_array($medName, $medicines)) {
                    $medicines[]             = $medName;
                    $medicineDoses->$medName = $doseVal;
                    // Attach timing only if available
                    $medicineTimings->$medName = $timingResults->get($medName, '');
                }
            }

            // All unique doses
            $doses = \App\Models\PatientTreatment::whereNotNull('dose')
                ->where('dose', '!=', '')
                ->groupBy('dose')
                ->pluck('dose')
                ->toArray();

            return response()->json([
                'success'          => true,
                'medicines'        => $medicines,
                'medicine_doses'   => $medicineDoses,
                'medicine_timings' => $medicineTimings,
                'doses'            => $doses
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching medicine suggestions: ' . $e->getMessage());
            return response()->json([
                'success'          => false,
                'medicines'        => [],
                'medicine_doses'   => (object) [],
                'medicine_timings' => (object) [],
                'doses'            => []
            ]);
        }
    }

    public function saveMedicalCondition(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|in:complaint,diagnosis'
            ]);

            $name = trim($request->name);
            $type = $request->type;

            // Check if condition already exists
            if (\App\Models\MedicalCondition::exists($name, $type)) {
                return response()->json([
                    'success' => false,
                    'message' => 'This ' . $type . ' already exists'
                ]);
            }

            // Add new condition
            $condition = \App\Models\MedicalCondition::addIfNotExists($name, $type);

            return response()->json([
                'success' => true,
                'message' => $type . ' added successfully',
                'condition' => [
                    'id' => $condition->id,
                    'name' => $condition->name,
                    'type' => $condition->type
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error saving medical condition: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving ' . $request->type . ': ' . $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {

        // dd($request->all());
        return DB::transaction(function () use ($request) {
            try {
                $user = auth()->user();
                if ($user->hasRole('Superadmin')) {

                    if (!$request->branch_id) {
                        throw new \Exception("Please select a branch.");
                    }

                    $branchId = $request->branch_id;
                } else {

                    if (!$user->user_branch) {
                        throw new \Exception("User has no branch assigned.");
                    }

                    $branchId = $user->user_branch;
                }


                $branch = Branch::where('branch_id', $branchId)->first();

                if (!$branch) {
                    throw new \Exception("Branch not found.");
                }

                $prefix = $branch->branch_name;

                $maxNumber = PatientInquiry::withTrashed()
                    ->where('branch_id', $branch->branch_id)
                    ->where('patient_id', 'LIKE', $prefix . '-%')
                    ->lockForUpdate()
                    ->max(DB::raw('CAST(SUBSTRING(patient_id, LOCATE("-", patient_id) + 1) AS UNSIGNED)'));

                $nextNumber = $maxNumber ? (int) $maxNumber + 1 : 1;
                $patientId = $prefix . '-' . str_pad($nextNumber, strlen((string) $nextNumber) + 4, '0', STR_PAD_LEFT);
                // Debug: Log all request data before validation
                \Log::info('SVC Inquiry Request Data:', [
                    'all_data' => $request->all(),
                    'diagnosis_value' => $request->input('diagnosis'),
                    'complain_value' => $request->input('complain'),
                ]);

                $validated = $request->validate([
                    'branch_id' => 'required|string',
                    'patient_name' => 'required|string|max:255',
                    'address' => 'required|string',
                    'age' => 'required|string|max:255',
                    'gender' => 'required|in:male,female,other',
                    'diagnosis' => 'required|string',
                    'inquiry_date' => 'nullable|date',
                    'next_follow_date' => 'nullable|date',
                    'total_payment' => 'nullable|numeric',
                    'given_payment' => 'nullable|numeric',
                    'payment_method' => 'nullable|string',
                ]);
                // dd($validated);
                if (!empty($validated['inquiry_date']) && $request->filled('inquiry_time')) {
                    try {
                        $validated['inquiry_date'] = \Carbon\Carbon::createFromFormat(
                            'Y-m-d H:i',
                            $validated['inquiry_date'] . ' ' . $request->input('inquiry_time'),
                            'Asia/Kolkata'
                        )->format('Y-m-d H:i:s');
                    } catch (\Exception $e) {
                        // Keep original inquiry_date if parsing fails
                    }
                }

                $validated['patient_id'] = $patientId;
                $validated['branch_id'] = $branch->branch_id;
                $validated['branch'] = $branch->branch_name;

                $patient = PatientInquiry::create($validated);

                // Debug: Log the created patient data
                \Log::info('Patient Created Successfully:', [
                    'patient_id' => $patient->id,
                    'patient_name' => $patient->patient_name,
                    'diagnosis' => $patient->diagnosis,
                    'all_patient_data' => $patient->toArray()
                ]);


                $totalPayment = (float) $request->input('total_payment', 0);
                $discountPayment = (float) $request->input('discount_payment', 0);
                $cashPayment = (float) $request->input('cash_payment', 0);
                $gpayPayment = (float) $request->input('gp_payment', 0);
                $chequePayment = (float) $request->input('cheque_payment', 0);
                $givenInput = $request->input('given_payment');
                $givenPayment = ($cashPayment + $gpayPayment + $chequePayment) > 0
                    ? ($cashPayment + $gpayPayment + $chequePayment)
                    : (float) ($givenInput ?? 0);

                $paymentMethod = $request->input('payment_method', 'Cash');
                if (($cashPayment + $gpayPayment + $chequePayment) <= 0 && $givenPayment > 0) {
                    if ($paymentMethod === 'Online') {
                        $gpayPayment = $givenPayment;
                    } elseif ($paymentMethod === 'Cheque') {
                        $chequePayment = $givenPayment;
                    } else {
                        $cashPayment = $givenPayment;
                    }
                }

                if ($totalPayment <= 0 && $givenPayment > 0) {
                    $totalPayment = $givenPayment;
                }

                $duePayment = (float) $request->input('due_payment', $totalPayment - $discountPayment - $givenPayment);
                if ($duePayment < 0) {
                    $duePayment = 0.0;
                }

                // Fields that must be saved as meta because they aren't in the main table
                $explicitMetaFields = [
                    'gender',
                    'total_payment',
                    'given_payment',
                    'due_payment',
                    'discount_payment',
                    'cash_payment',
                    'gp_payment',
                    'cheque_payment',
                    'payment_method',
                    'inquiry_time',
                    'doctor_id',
                    'foc',
                ];

                foreach ($explicitMetaFields as $field) {
                    if ($field === 'foc') {
                        $patient->setMeta('foc', $request->has('foc') ? 'on' : null);
                    } else if ($request->has($field) || in_array($field, ['cash_payment', 'gp_payment', 'cheque_payment'])) {
                        $val = $request->input($field);
                        if ($field === 'total_payment') {
                            $val = $totalPayment;
                        } elseif ($field === 'due_payment') {
                            $val = $duePayment;
                        } elseif ($field === 'given_payment') {
                            $val = $givenPayment;
                        } elseif ($field === 'cash_payment') {
                            $val = $cashPayment;
                        } elseif ($field === 'gp_payment') {
                            $val = $gpayPayment;
                        } elseif ($field === 'cheque_payment') {
                            $val = $chequePayment;
                        }
                        $patient->setMeta($field, $val);
                    }
                }

                $metaFields = $request->except(array_merge(array_keys($validated), ['_token'], $explicitMetaFields));

                // Debug: Log what meta fields are being processed
                \Log::info('Meta fields being processed:', [
                    'meta_fields' => $metaFields,
                    'validated_keys' => array_keys($validated),
                    'all_request_data' => $request->all()
                ]);

                foreach ($metaFields as $key => $value) {
                    if (is_array($value) && !in_array($key, ['charge_id', 'custom_charge_name', 'custom_charge_price', 'custom_charge_id'])) {
                        foreach ($value as $index => $item) {
                            $patient->setMeta("{$key}_{$index}", $item);
                        }
                    } else {
                        // Store it as a single meta field (it will be JSON encoded by setMeta if it's an array like charge_id)
                        $patient->setMeta($key, $value);
                        \Log::info("Setting meta: {$key} = " . (is_array($value) ? json_encode($value) : $value));
                    }
                }

                $groups = [
                    'inside' => ['dose', 'timing', 'days'],
                    'homeo' => ['dose', 'timing', 'days'],
                    'prescription' => ['dose', 'timing', 'days'],
                    'indoor' => ['dose', 'note', 'days', 'date', 'time'],
                    'other' => ['note'],
                ];

                // Check if any indoor treatment is provided to set IPD status
                if ($request->has('indoor_medicine')) {
                    $indoorMedicines = $request->input('indoor_medicine', []);
                    $hasIndoor = false;
                    foreach ($indoorMedicines as $med) {
                        if (!empty(trim($med))) {
                            $hasIndoor = true;
                            break;
                        }
                    }
                    if ($hasIndoor) {
                        $patient->setMeta('pt_status', 'IPD');
                    }
                }

                foreach ($groups as $type => $fields) {
                    $medicineKey = $type . '_medicine';

                    if ($request->has($medicineKey)) {
                        foreach ($request->$medicineKey as $i => $medicine) {
                            if (!empty($medicine)) {
                                $data = [
                                    'patient_id' => $patient->patient_id,
                                    'inquiry_id' => $patient->id,
                                    'followup_id' => null,
                                    'type' => $type,
                                    'medicine' => $medicine,
                                ];

                                foreach ($fields as $f) {
                                    // Handle dose fields with dual input (textbox + dropdown)
                                    if ($f === 'dose') {
                                        $textboxField = $type . '_dose';
                                        $dropdownField = $type . '_dose_dropdown';

                                        // Get textbox value (manual entry)
                                        $textboxValues = $request->input($textboxField, []);
                                        $textboxValue = $textboxValues[$i] ?? null;

                                        // Get dropdown value
                                        $dropdownValues = $request->input($dropdownField, []);
                                        $dropdownValue = $dropdownValues[$i] ?? null;

                                        // Use textbox value if available, otherwise use dropdown value
                                        $data[$f] = !empty($textboxValue) ? $textboxValue : $dropdownValue;

                                        \Log::info("Dose processing for {$type}[{$i}]: textbox={$textboxValue}, dropdown={$dropdownValue}, final={$data[$f]}");
                                    } else {
                                        // Handle other fields normally
                                        $fieldName = $type . '_' . $f;
                                        $fieldValues = $request->input($fieldName, []);
                                        if (isset($fieldValues[$i])) {
                                            $data[$f] = $fieldValues[$i];
                                            \Log::info("Field processing for {$type}[{$i}]: {$fieldName} = " . $fieldValues[$i]);
                                        }
                                    }
                                }

                                PatientTreatment::create($data);
                            }
                        }
                    }
                }

                // Create Invoice and Transactions for Registration Charges
                if ($totalPayment > 0) {
                    $invoiceNo = 'INV-' . $patientId;

                    // Check if invoice number already exists (unlikely for new patient but safe)
                    $counter = 1;
                    $finalInvoiceNo = $invoiceNo;
                    while (Invoice::where('invoice_no', $finalInvoiceNo)->exists()) {
                        $finalInvoiceNo = $invoiceNo . '-' . $counter;
                        $counter++;
                    }

                    // Generate Filename
                    $pNameClean = preg_replace('/[^A-Za-z0-9]/', '', $patient->patient_name ?? 'Patient');
                    $bNameClean = preg_replace('/[^A-Za-z0-9]/', '', $branch->branch_name ?? 'Branch');
                    $invoiceFile = $pNameClean . $bNameClean . '-' . $finalInvoiceNo . '-' . now()->format('d-m-Y') . '.pdf';

                    $selectedChargeIds = $request->input('charge_id', []);
                    if (!is_array($selectedChargeIds))
                        $selectedChargeIds = [$selectedChargeIds];

                    $chargesData = [];
                    $selectedCharges = Charges::whereIn('id', $selectedChargeIds)->get();
                    if ($selectedCharges->count() > 0) {
                        foreach ($selectedCharges as $c) {
                            $chargesData[] = [
                                'charge_id' => $c->id,
                                'charge_name' => $c->charges_name,
                                'price' => $c->charges_price
                            ];
                        }
                    }

                    // Add Custom Charges
                    $customNames = $request->input('custom_charge_name', []);
                    $customPrices = $request->input('custom_charge_price', []);
                    if (is_array($customNames)) {
                        foreach ($customNames as $index => $name) {
                            if (!empty($name) && isset($customPrices[$index])) {
                                $chargesData[] = [
                                    'charge_id' => null,
                                    'charge_name' => $name,
                                    'price' => $customPrices[$index]
                                ];
                            }
                        }
                    }

                    if (empty($chargesData)) {
                        $chargesData = [
                            [
                                'charge_id' => null,
                                'charge_name' => 'Registration & Consultation Charges',
                                'price' => $totalPayment
                            ]
                        ];
                    }



                    $inquiryDateRaw = $patient->inquiry_date ?? $request->input('inquiry_date');
                    $invoiceDate = !empty($inquiryDateRaw) ? \Carbon\Carbon::parse($inquiryDateRaw)->format('Y-m-d') : now()->format('Y-m-d');
                    $transactionDate = !empty($inquiryDateRaw) ? \Carbon\Carbon::parse($inquiryDateRaw)->format('Y-m-d H:i:s') : now();

                    $invoice = Invoice::create([
                        'branch_id' => $branch->branch_id,
                        'patient_id' => $patient->id,
                        'invoice_no' => $finalInvoiceNo,
                        'invoice_date' => $invoiceDate,
                        'address' => $patient->address,
                        'phone' => $patient->getMeta('phone'),
                        'price' => $totalPayment,
                        'total_payment' => $totalPayment,
                        'discount' => $discountPayment,
                        'given_payment' => $givenPayment,
                        'due_payment' => $duePayment,
                        'invoice_file' => $invoiceFile,
                        'charges_data' => $chargesData,
                        'cash_payment' => $cashPayment,
                        'gpay_payment' => $gpayPayment,
                        'cheque_payment' => $chequePayment,
                        'created_at' => $transactionDate,
                        'updated_at' => $transactionDate,
                    ]);

                    // Determine branch prefix
                    if ($branch->branch_id === 'LB-0007') {
                        $descPrefix = 'LHR Service';
                    } elseif ($branch->branch_id === 'BH-00023') {
                        $descPrefix = 'Hydra Service';
                    } elseif ($branch->branch_id === 'SVC-0005') {
                        $descPrefix = 'SVC Service';
                    } else {
                        $descPrefix = 'FNF Service';
                    }

                    // Debit Transaction
                    PatientTransaction::create([
                        'branch_id' => $branch->branch_id,
                        'patient_id' => $patient->id,
                        'invoice_id' => $invoice->id,
                        'type' => 'debit',
                        'amount' => $totalPayment,
                        'description' => $descPrefix . ' (Registration & Consultation) - Invoice Generated: ' . $invoice->invoice_no,
                        'created_at' => $transactionDate,
                        'updated_at' => $transactionDate,
                    ]);

                    // Credit Transaction
                    if ($givenPayment > 0) {
                        $methods = [];
                        if ($cashPayment > 0) $methods[] = 'Cash';
                        if ($gpayPayment > 0) $methods[] = 'G-Pay';
                        if ($chequePayment > 0) $methods[] = 'Cheque';
                        $paymentMethod = !empty($methods) ? implode('+', $methods) : 'Cash';

                        PatientTransaction::create([
                            'branch_id' => $branch->branch_id,
                            'patient_id' => $patient->id,
                            'invoice_id' => $invoice->id,
                            'type' => 'credit',
                            'amount' => $givenPayment,
                            'description' => $descPrefix . ' (Registration & Consultation) Payment Received (' . $paymentMethod . ') for Invoice: ' . $invoice->invoice_no,
                            'created_at' => $transactionDate,
                            'updated_at' => $transactionDate,
                        ]);
                    }
                }
                // dd($request->branch_id, Branch::pluck('branch_id'));

                return redirect()
                    ->route('svc-patient')
                    ->with('success', 'Patient inquiry and treatments saved successfully. Patient ID: ' . $patientId);
            } catch (\Exception $e) {
                \Log::error('Error saving SVC inquiry: ' . $e->getMessage());
                \Log::error('Trace: ' . $e->getTraceAsString());

                return back()
                    ->with('error', 'Error saving inquiry: ' . $e->getMessage())
                    ->withInput();
            }
        });
    }




    public function editSvcInquiry($id)
    {
        $patient = PatientInquiry::with(['metas'])->findOrFail($id);

        $meta = [];
        foreach ($patient->metas as $m) {
            $decoded = json_decode($m->meta_value, true);
            $meta[$m->meta_key] = json_last_error() === JSON_ERROR_NONE ? $decoded : $m->meta_value;
        }

        $treatments = [];
        $groups = ['inside', 'homeo', 'prescription', 'indoor', 'other'];

        foreach ($groups as $group) {
            $treatments[$group] = PatientTreatment::where('patient_id', $patient->patient_id)
                ->where('inquiry_id', $patient->id)
                ->where('type', $group)
                ->whereNull('followup_id')
                ->get()
                ->toArray();
        }

        $doctors = User::where('user_role', 6)
            ->orWhereHas('roles', function ($query) {
                $query->where('name', 'Doctor');
            })
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        $charges = Charges::where(function ($query) {
            $query->whereIn('delete_status', ['0', ''])->orWhereNull('delete_status');
        })
            ->orderBy('charges_name')
            ->get();

        return view('branches.edit_svc_inquiry', [
            'patient' => $patient,
            'meta' => $meta,
            'treatments' => $treatments,
            'doctors' => $doctors,
            'charges' => $charges,
        ]);
    }

    public function updateSvcInquiry(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'patient_id' => 'required|string',
                'branch' => 'nullable|string',
                'branch_id' => 'nullable|string',
                'patient_name' => 'required|string|max:255',
                'address' => 'nullable|string',
                'age' => 'nullable|string|max:255',
                'diagnosis' => 'nullable|string',
                'inquiry_date' => 'nullable|date',
                'next_follow_date' => 'nullable|date',
            ]);

            $patient = PatientInquiry::findOrFail($id);
            $patient->update($validated);

            $metaFields = [
                'complain',
                'investigation',
                'past_history',
                'family_history',
                'gender',
                'weight',
                'phone',
                'pt_status',
                'temperature',
                'pulse',
                'blood_pressure',
                'spo2',
                'rbs',
                'hb',
                'tc',
                'pc',
                'MP',
                'HB1AC',
                'fbs',
                'pp2bs',
                'S_widal',
                'USG',
                'X_ray',
                'SGPT',
                's_creatinine',
                'NS1Ag',
                'DengueIGM',
                's_cholesterol',
                'STriglyceride',
                'HDL',
                'LDL',
                'VLDL',
                'non_hdl_c',
                'chol_hdl_ratio',
                'SB12',
                'SD3',
                'Urine',
                'CRP',
                'St3',
                'St4',
                'STSH',
                'ESR',
                'specific_test',
                'reference_by',
                'referto',
                'doctor_id',
                'notes',
                'charge_id',
                'custom_charge_name',
                'custom_charge_price',
                'total_payment',
                'discount_payment',
                'given_payment',
                'due_payment',
                'cash_payment',
                'gp_payment',
                'cheque_payment',
                'payment_method',
                'inquiry_time'
            ];

            $totalPayment = (float) $request->input('total_payment', 0);
            $discountPayment = (float) $request->input('discount_payment', 0);
            $cashPayment = (float) $request->input('cash_payment', 0);
            $gpayPayment = (float) $request->input('gp_payment', 0);
            $chequePayment = (float) $request->input('cheque_payment', 0);
            $givenInput = $request->input('given_payment');
            $givenPayment = ($cashPayment + $gpayPayment + $chequePayment) > 0
                ? ($cashPayment + $gpayPayment + $chequePayment)
                : (float) ($givenInput ?? 0);

            $paymentMethod = $request->input('payment_method', 'Cash');
            if (($cashPayment + $gpayPayment + $chequePayment) <= 0 && $givenPayment > 0) {
                if ($paymentMethod === 'Online') {
                    $gpayPayment = $givenPayment;
                } elseif ($paymentMethod === 'Cheque') {
                    $chequePayment = $givenPayment;
                } else {
                    $cashPayment = $givenPayment;
                }
            }

            // If total payment is not set but payment is given, default total payment to given payment
            if ($totalPayment <= 0 && $givenPayment > 0) {
                $totalPayment = $givenPayment;
            }

            $duePayment = (float) $request->input('due_payment', $totalPayment - $discountPayment - $givenPayment);
            if ($duePayment < 0) {
                $duePayment = 0.0;
            }

            foreach ($metaFields as $field) {
                if ($request->has($field) || in_array($field, ['cash_payment', 'gp_payment', 'cheque_payment'])) {
                    $val = $request->input($field);
                    if ($field === 'total_payment') {
                        $val = $totalPayment;
                    } elseif ($field === 'due_payment') {
                        $val = $duePayment;
                    } elseif ($field === 'given_payment') {
                        $val = $givenPayment;
                    } elseif ($field === 'cash_payment') {
                        $val = $cashPayment;
                    } elseif ($field === 'gp_payment') {
                        $val = $gpayPayment;
                    } elseif ($field === 'cheque_payment') {
                        $val = $chequePayment;
                    }
                    $patient->setMeta($field, $val);
                }
            }

            // Handle foc explicitly for checkbox state
            $patient->setMeta('foc', $request->has('foc') ? 'on' : null);

            // Update or Create Invoice
            $invoice = Invoice::where('patient_id', $patient->id)->first();

            // Determine branch prefix
            if ($patient->branch_id === 'LB-0007') {
                $descPrefix = 'LHR Service';
            } elseif ($patient->branch_id === 'BH-00023') {
                $descPrefix = 'Hydra Service';
            } elseif ($patient->branch_id === 'SVC-0005') {
                $descPrefix = 'SVC Service';
            } else {
                $descPrefix = 'FNF Service';
            }

            $inquiryDateRaw = $patient->inquiry_date ?? $request->input('inquiry_date');
            $invoiceDate = !empty($inquiryDateRaw) ? \Carbon\Carbon::parse($inquiryDateRaw)->format('Y-m-d') : now()->format('Y-m-d');
            $transactionDate = !empty($inquiryDateRaw) ? \Carbon\Carbon::parse($inquiryDateRaw)->format('Y-m-d H:i:s') : now();

            if ($invoice) {
                $selectedChargeIds = $request->input('charge_id', []);
                if (!is_array($selectedChargeIds))
                    $selectedChargeIds = [$selectedChargeIds];

                $chargesData = [];
                $selectedCharges = Charges::whereIn('id', $selectedChargeIds)->get();
                foreach ($selectedCharges as $c) {
                    $chargesData[] = [
                        'charge_id' => $c->id,
                        'charge_name' => $c->charges_name,
                        'price' => $c->charges_price
                    ];
                }

                // Add Custom Charges in Update
                $customNames = $request->input('custom_charge_name', []);
                $customPrices = $request->input('custom_charge_price', []);
                if (is_array($customNames)) {
                    foreach ($customNames as $index => $name) {
                        if (!empty($name) && isset($customPrices[$index])) {
                            $chargesData[] = [
                                'charge_id' => null,
                                'charge_name' => $name,
                                'price' => $customPrices[$index]
                            ];
                        }
                    }
                }

                if (empty($chargesData)) {
                    $chargesData = [['charge_id' => null, 'charge_name' => 'Registration & Consultation Charges', 'price' => $totalPayment]];
                }

                $invoice->update([
                    'invoice_date' => $invoiceDate,
                    'total_payment' => $totalPayment,
                    'discount' => $discountPayment,
                    'given_payment' => $givenPayment,
                    'due_payment' => $duePayment,
                    'price' => $totalPayment,
                    'charges_data' => $chargesData,
                    'cash_payment' => $cashPayment,
                    'gpay_payment' => $gpayPayment,
                    'cheque_payment' => $chequePayment
                ]);

                // Update Transactions as well
                $debitTx = PatientTransaction::where('invoice_id', $invoice->id)->where('type', 'debit')->first();
                if ($debitTx) {
                    $debitTx->update([
                        'amount' => $totalPayment,
                        'created_at' => $transactionDate,
                        'updated_at' => $transactionDate,
                    ]);
                } else {
                    PatientTransaction::create([
                        'branch_id' => $patient->branch_id,
                        'patient_id' => $patient->id,
                        'invoice_id' => $invoice->id,
                        'type' => 'debit',
                        'amount' => $totalPayment,
                        'description' => $descPrefix . ' (Registration & Consultation) - Invoice Generated: ' . $invoice->invoice_no,
                        'created_at' => $transactionDate,
                        'updated_at' => $transactionDate,
                    ]);
                }

                $creditTx = PatientTransaction::where('invoice_id', $invoice->id)->where('type', 'credit')->first();
                if ($creditTx) {
                    if ($givenPayment > 0) {
                        $methods = [];
                        if ($cashPayment > 0) $methods[] = 'Cash';
                        if ($gpayPayment > 0) $methods[] = 'G-Pay';
                        if ($chequePayment > 0) $methods[] = 'Cheque';
                        $paymentMethod = !empty($methods) ? implode('+', $methods) : 'Cash';

                        $creditTx->update([
                            'amount' => $givenPayment,
                            'description' => $descPrefix . ' (Registration & Consultation) Payment Received (' . $paymentMethod . ') for Invoice: ' . $invoice->invoice_no,
                            'created_at' => $transactionDate,
                            'updated_at' => $transactionDate,
                        ]);
                    } else {
                        $creditTx->delete();
                    }
                } elseif ($givenPayment > 0) {
                    $methods = [];
                    if ($cashPayment > 0) $methods[] = 'Cash';
                    if ($gpayPayment > 0) $methods[] = 'G-Pay';
                    if ($chequePayment > 0) $methods[] = 'Cheque';
                    $paymentMethod = !empty($methods) ? implode('+', $methods) : 'Cash';

                    PatientTransaction::create([
                        'branch_id' => $patient->branch_id,
                        'patient_id' => $patient->id,
                        'invoice_id' => $invoice->id,
                        'type' => 'credit',
                        'amount' => $givenPayment,
                        'description' => $descPrefix . ' (Registration & Consultation) Payment Received (' . $paymentMethod . ') for Invoice: ' . $invoice->invoice_no,
                        'created_at' => $transactionDate,
                        'updated_at' => $transactionDate,
                    ]);
                }
            } elseif ($totalPayment > 0) {
                // Create Invoice and Transactions for Registration Charges if they didn't exist before
                $invoiceNo = 'INV-' . $patient->patient_id;

                // Check if invoice number already exists
                $counter = 1;
                $finalInvoiceNo = $invoiceNo;
                while (Invoice::where('invoice_no', $finalInvoiceNo)->exists()) {
                    $finalInvoiceNo = $invoiceNo . '-' . $counter;
                    $counter++;
                }

                // Generate Filename
                $pNameClean = preg_replace('/[^A-Za-z0-9]/', '', $patient->patient_name ?? 'Patient');
                $bNameClean = preg_replace('/[^A-Za-z0-9]/', '', $patient->branch ?? 'Branch');
                $invoiceFile = $pNameClean . $bNameClean . '-' . $finalInvoiceNo . '-' . now()->format('d-m-Y') . '.pdf';

                $selectedChargeIds = $request->input('charge_id', []);
                if (!is_array($selectedChargeIds))
                    $selectedChargeIds = [$selectedChargeIds];

                $chargesData = [];
                $selectedCharges = Charges::whereIn('id', $selectedChargeIds)->get();
                if ($selectedCharges->count() > 0) {
                    foreach ($selectedCharges as $c) {
                        $chargesData[] = [
                            'charge_id' => $c->id,
                            'charge_name' => $c->charges_name,
                            'price' => $c->charges_price
                        ];
                    }
                }

                // Add Custom Charges
                $customNames = $request->input('custom_charge_name', []);
                $customPrices = $request->input('custom_charge_price', []);
                if (is_array($customNames)) {
                    foreach ($customNames as $index => $name) {
                        if (!empty($name) && isset($customPrices[$index])) {
                            $chargesData[] = [
                                'charge_id' => null,
                                'charge_name' => $name,
                                'price' => $customPrices[$index]
                            ];
                        }
                    }
                }

                if (empty($chargesData)) {
                    $chargesData = [
                        [
                            'charge_id' => null,
                            'charge_name' => 'Registration & Consultation Charges',
                            'price' => $totalPayment
                        ]
                    ];
                }

                $invoice = Invoice::create([
                    'branch_id' => $patient->branch_id,
                    'patient_id' => $patient->id,
                    'invoice_no' => $finalInvoiceNo,
                    'invoice_date' => $invoiceDate,
                    'address' => $patient->address,
                    'phone' => $patient->getMeta('phone'),
                    'price' => $totalPayment,
                    'total_payment' => $totalPayment,
                    'discount' => $discountPayment,
                    'given_payment' => $givenPayment,
                    'due_payment' => $duePayment,
                    'invoice_file' => $invoiceFile,
                    'charges_data' => $chargesData,
                    'cash_payment' => $cashPayment,
                    'gpay_payment' => $gpayPayment,
                    'cheque_payment' => $chequePayment,
                    'created_at' => $transactionDate,
                    'updated_at' => $transactionDate,
                ]);

                // Debit Transaction
                PatientTransaction::create([
                    'branch_id' => $patient->branch_id,
                    'patient_id' => $patient->id,
                    'invoice_id' => $invoice->id,
                    'type' => 'debit',
                    'amount' => $totalPayment,
                    'description' => $descPrefix . ' (Registration & Consultation) - Invoice Generated: ' . $invoice->invoice_no,
                    'created_at' => $transactionDate,
                    'updated_at' => $transactionDate,
                ]);

                // Credit Transaction
                if ($givenPayment > 0) {
                    $methods = [];
                    if ($cashPayment > 0) $methods[] = 'Cash';
                    if ($gpayPayment > 0) $methods[] = 'G-Pay';
                    if ($chequePayment > 0) $methods[] = 'Cheque';
                    $paymentMethod = !empty($methods) ? implode('+', $methods) : 'Cash';

                    PatientTransaction::create([
                        'branch_id' => $patient->branch_id,
                        'patient_id' => $patient->id,
                        'invoice_id' => $invoice->id,
                        'type' => 'credit',
                        'amount' => $givenPayment,
                        'description' => $descPrefix . ' (Registration & Consultation) Payment Received (' . $paymentMethod . ') for Invoice: ' . $invoice->invoice_no,
                        'created_at' => $transactionDate,
                        'updated_at' => $transactionDate,
                    ]);
                }
            }

            $treatmentGroups = [
                'inside' => ['dose', 'timing', 'days'],
                'homeo' => ['dose', 'timing', 'days'],
                'prescription' => ['dose', 'timing', 'days'],
                'indoor' => ['dose', 'note', 'days', 'date', 'time'],
                'other' => ['note'],
            ];

            foreach ($treatmentGroups as $key => $fields) {
                $medicineKey = "{$key}_medicine";

                PatientTreatment::where('patient_id', $patient->patient_id)
                    ->where('inquiry_id', $patient->id)
                    ->where('type', $key)
                    ->delete();

                $medicines = $request->input($medicineKey, []);

                foreach ($medicines as $index => $medicine) {
                    if (!empty(trim($medicine))) {
                        $treatmentData = [
                            'patient_id' => $patient->patient_id,
                            'inquiry_id' => $patient->id,
                            'type' => $key,
                            'medicine' => trim($medicine),
                        ];

                        foreach ($fields as $field) {
                            $fieldName = "{$key}_{$field}";
                            $fieldValues = $request->input($fieldName, []);
                            if (isset($fieldValues[$index])) {
                                $treatmentData[$field] = $fieldValues[$index];
                                \Log::info("Update field processing for {$key}[{$index}]: {$fieldName} = " . $fieldValues[$index]);
                            }
                        }

                        PatientTreatment::create($treatmentData);
                    }
                }
            }

            return redirect()
                ->route('svc-patient')
                ->with('success', 'Patient inquiry updated successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'Error updating inquiry: ' . $e->getMessage());
        }
    }
    public function deleteSvcInquiry($id)
    {
        try {
            $patient = PatientInquiry::findOrFail($id);


            $patient->delete_by = auth()->check() ? auth()->user()->name : 'system';
            $patient->delete_status = 'deleted';
            $patient->save();


            $patient->metas()->delete();


            $patient->delete();

            return redirect()
                ->route('svc-patient')
                ->with('success', 'Patient inquiry and related meta deleted successfully.');
        } catch (ModelNotFoundException $e) {
            Log::error("Patient not found for deleteSvcInquiry: ID {$id}");
            return redirect()->route('svc-patient')->with('error', 'Patient not found.');
        } catch (Exception $e) {
            Log::error('Error in deleteSvcInquiry', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Unexpected error while deleting inquiry.');
        }
    }
    public function viewSvcProfile($id)
    {

        try {
            $patient = PatientInquiry::with(['metas'])->findOrFail($id);

            // Build meta array like in editSvcInquiry
            $meta = [];
            foreach ($patient->metas as $m) {
                $decoded = json_decode($m->meta_value, true);
                $meta[$m->meta_key] = json_last_error() === JSON_ERROR_NONE ? $decoded : $m->meta_value;
            }

            $treatments = [];
            $groups = ['inside', 'homeo', 'prescription', 'indoor', 'other'];

            foreach ($groups as $group) {
                $treatments[$group] = PatientTreatment::where('patient_id', $patient->patient_id)
                    ->where('inquiry_id', $patient->id)
                    ->where('type', $group)
                    ->whereNull('followup_id')
                    ->get()
                    ->toArray();
            }

            // Get doctors for display
            $doctors = User::where('user_role', 6)
                ->orWhereHas('roles', function ($query) {
                    $query->where('name', 'Doctor');
                })
                ->select('id', 'name', 'email')
                ->orderBy('name')
                ->get();

            // Get invoice data
            $invoice = \App\Models\Invoice::where('patient_id', $patient->id)->first();

            return view('branches.profile.svc_profile', compact('patient', 'meta', 'treatments', 'invoice'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('svc-patient')->with('error', 'Patient not found.');
        } catch (Exception $e) {
            return redirect()->route('svc-patient')->with('error', 'Error loading patient profile.');
        }
    }

    public function viewIpdProfile($id)
    {
        try {
            $patient = PatientInquiry::with([
                'metas',
                'followups' => function ($q) {
                    $q->orderBy('followup_date', 'desc');
                }
            ])->findOrFail($id);

            // Build meta array like in editSvcInquiry
            $meta = [];
            foreach ($patient->metas as $m) {
                $decoded = json_decode($m->meta_value, true);
                $meta[$m->meta_key] = json_last_error() === JSON_ERROR_NONE ? $decoded : $m->meta_value;
            }

            $treatments = [];
            $groups = ['inside', 'homeo', 'prescription', 'indoor', 'other'];

            foreach ($groups as $group) {
                $treatments[$group] = PatientTreatment::where('patient_id', $patient->patient_id)
                    ->where('inquiry_id', $patient->id)
                    ->where('type', $group)
                    ->whereNull('followup_id')
                    ->get()
                    ->toArray();
            }

            // Get invoice data
            $invoice = Invoice::where('patient_id', $patient->id)->first();

            return view('branches.profile.svc_profile', compact('patient', 'meta', 'treatments', 'invoice'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('svc-patient')->with('error', 'Patient not found.');
        } catch (Exception $e) {
            Log::error("Error in viewIpdProfile for patient ID {$id}: " . $e->getMessage());
            return redirect()->route('svc-patient')->with('error', 'Error loading IPD patient profile.');
        }
    }

    public function updateCharges(Request $request, $id)
    {
        try {
            $patient = PatientInquiry::findOrFail($id);
            $branchId = $patient->branch_id ?? 'SVC-0005';
            $invoice = Invoice::where('patient_id', $patient->id)->first();

            $totalPayment = (float) $request->input('total_payment', 0);
            $discountPayment = (float) $request->input('discount_payment', 0);
            $cashPayment = (float) $request->input('cash_payment', 0);
            $gpayPayment = (float) $request->input('gp_payment', 0);
            $chequePayment = (float) $request->input('cheque_payment', 0);
            $givenPayment = $cashPayment + $gpayPayment + $chequePayment;
            $paymentMethod = $givenPayment > 0 ? 'Split' : 'None';
            $duePayment = $totalPayment - $discountPayment - $givenPayment;

            // New fields
            $chargeDate = $request->input('charge_date');
            $chargeTime = $request->input('charge_time');
            $chargeShift = $request->input('charge_shift');

            if (!$invoice) {
                $invoiceNo = 'INV-' . ($patient->patient_id ?? 'SVC-' . time());
                $counter = 1;
                $finalInvoiceNo = $invoiceNo;
                while (Invoice::where('invoice_no', $finalInvoiceNo)->exists()) {
                    $finalInvoiceNo = $invoiceNo . '-' . $counter;
                    $counter++;
                }
                $invoice = Invoice::create([
                    'branch_id' => $branchId,
                    'patient_id' => $patient->id,
                    'invoice_no' => $finalInvoiceNo,
                    'invoice_date' => now()->format('Y-m-d'),
                    'address' => $patient->address ?? '',
                    'phone' => $patient->getMeta('phone') ?? '',
                    'price' => $totalPayment,
                    'total_payment' => $totalPayment,
                    'discount' => $discountPayment,
                    'given_payment' => $givenPayment,
                    'due_payment' => $duePayment,
                    'cash_payment' => $cashPayment,
                    'gpay_payment' => $gpayPayment,
                    'cheque_payment' => $chequePayment,
                    'charges_data' => []
                ]);
            }

            if ($invoice) {
                $chargesData = $invoice->charges_data ?? [];
                $chargesData['last_update'] = [
                    'date' => $chargeDate,
                    'time' => $chargeTime,
                    'shift' => $chargeShift
                ];

                $invoice->update([
                    'total_payment' => $totalPayment,
                    'given_payment' => $givenPayment,
                    'due_payment' => $duePayment,
                    'discount' => $discountPayment,
                    'price' => $totalPayment,
                    'cash_payment' => $cashPayment,
                    'gpay_payment' => $gpayPayment,
                    'cheque_payment' => $chequePayment,
                    'charges_data' => $chargesData
                ]);

                // Update Debit Transaction (for total price increase)
                $debitTx = PatientTransaction::where('invoice_id', $invoice->id)->where('type', 'debit')->first();
                $description = "SVC Service (Update Charges) - Invoice: " . $invoice->invoice_no . " | Visit: " . $chargeDate . " " . $chargeTime . " (" . $chargeShift . ")";

                if ($debitTx) {
                    $debitTx->update([
                        'amount' => $totalPayment,
                        'description' => $description
                    ]);
                } else {
                    PatientTransaction::create([
                        'branch_id' => $branchId,
                        'patient_id' => $patient->id,
                        'invoice_id' => $invoice->id,
                        'type' => 'debit',
                        'amount' => $totalPayment,
                        'description' => $description,
                    ]);
                }

                // Update Credit Transaction (for payments)
                $creditTx = PatientTransaction::where('invoice_id', $invoice->id)->where('type', 'credit')->first();
                if ($creditTx) {
                    if ($givenPayment > 0) {
                        $creditTx->update([
                            'amount' => $givenPayment,
                            'description' => 'SVC Service (Update Charges) Payment Received (' . $paymentMethod . ') for Invoice: ' . $invoice->invoice_no . " | Visit: " . $chargeDate . " " . $chargeTime . " (" . $chargeShift . ")",
                        ]);
                    } else {
                        $creditTx->delete();
                    }
                } elseif ($givenPayment > 0) {
                    PatientTransaction::create([
                        'branch_id' => $branchId,
                        'patient_id' => $patient->id,
                        'invoice_id' => $invoice->id,
                        'type' => 'credit',
                        'amount' => $givenPayment,
                        'description' => 'SVC Service (Update Charges) Payment Received (' . $paymentMethod . ') for Invoice: ' . $invoice->invoice_no . " | Visit: " . $chargeDate . " " . $chargeTime . " (" . $chargeShift . ")",
                    ]);
                }
            }

            return back()->with('success', 'Charges updated successfully.');
        } catch (Exception $e) {
            Log::error("Error in updateCharges for patient ID {$id}: " . $e->getMessage());
            return back()->with('error', 'Error updating charges: ' . $e->getMessage());
        }
    }

    public function addIpdPayment(Request $request, $id)
    {
        try {
            $patient = PatientInquiry::findOrFail($id);
            $cashPayment = (float) $request->input('cash_payment', 0);
            $gpayPayment = (float) $request->input('gp_payment', 0);
            $chequePayment = (float) $request->input('cheque_payment', 0);
            $amount = $cashPayment + $gpayPayment + $chequePayment;
            $method = 'Split';

            if ($amount <= 0 && $request->filled('amount')) {
                $amount = (float) $request->input('amount', 0);
                $method = $request->input('payment_method', 'Cash');
                if (strtolower($method) === 'cash') {
                    $cashPayment = $amount;
                } elseif (in_array(strtolower($method), ['online', 'g-pay', 'gpay', 'gp-pay'])) {
                    $gpayPayment = $amount;
                } elseif (strtolower($method) === 'cheque') {
                    $chequePayment = $amount;
                }
            } else {
                $methods = [];
                if ($cashPayment > 0) $methods[] = 'Cash';
                if ($gpayPayment > 0) $methods[] = 'G-Pay';
                if ($chequePayment > 0) $methods[] = 'Cheque';
                $method = !empty($methods) ? implode('+', $methods) : 'Cash';
            }

            $discount = (float) $request->input('discount', 0);
            $note = $request->input('note', 'Quick Payment');

            if ($amount <= 0 && $discount <= 0) {
                return back()->with('error', 'Please enter a valid amount or discount.');
            }

            $invoice = Invoice::where('patient_id', $patient->id)->first();
            $branchId = $patient->branch_id ?: 'SVC-0005';

            if (!$invoice) {
                // Create a basic invoice if none exists
                $invoice = Invoice::create([
                    'branch_id' => $branchId,
                    'patient_id' => $patient->id,
                    'invoice_no' => 'INV-IPD-' . time(),
                    'invoice_date' => now()->format('Y-m-d'),
                    'total_payment' => $amount + $discount,
                    'given_payment' => $amount,
                    'discount' => $discount,
                    'due_payment' => 0,
                    'price' => $amount + $discount,
                    'cash_payment' => $cashPayment,
                    'gpay_payment' => $gpayPayment,
                    'cheque_payment' => $chequePayment,
                    'charges_data' => []
                ]);

                // Create Debit Transaction for the initial amount
                PatientTransaction::create([
                    'branch_id' => $branchId,
                    'patient_id' => $patient->id,
                    'invoice_id' => $invoice->id,
                    'type' => 'debit',
                    'amount' => $amount + $discount,
                    'description' => "SVC Service (Quick Invoice) - IPD Initial Charges",
                ]);
            } else {
                // Update existing invoice
                $invoice->given_payment = (float) $invoice->given_payment + $amount;
                $invoice->cash_payment = (float) ($invoice->cash_payment ?? 0) + $cashPayment;
                $invoice->gpay_payment = (float) ($invoice->gpay_payment ?? 0) + $gpayPayment;
                $invoice->cheque_payment = (float) ($invoice->cheque_payment ?? 0) + $chequePayment;
                $invoice->discount = (float) $invoice->discount + $discount;
                $invoice->due_payment = max(0, (float) $invoice->total_payment - (float) $invoice->given_payment - (float) $invoice->discount);
                $invoice->save();
            }

            // Create Transaction Record for Payment
            if ($amount > 0) {
                PatientTransaction::create([
                    'branch_id' => $branchId,
                    'patient_id' => $patient->id,
                    'invoice_id' => $invoice->id,
                    'type' => 'credit',
                    'amount' => $amount,
                    'description' => "IPD Payment Received ($method) - $note",
                ]);
            }

            // Create Transaction Record for Discount
            if ($discount > 0) {
                PatientTransaction::create([
                    'branch_id' => $branchId,
                    'patient_id' => $patient->id,
                    'invoice_id' => $invoice->id,
                    'type' => 'discount',
                    'amount' => $discount,
                    'description' => "IPD Discount Applied - $note",
                ]);
            }

            return back()->with('success', 'Payment recorded successfully.');

        } catch (\Exception $e) {
            Log::error("Error in addIpdPayment for patient ID {$id}: " . $e->getMessage());
            return back()->with('error', 'Error recording payment: ' . $e->getMessage());
        }
    }

    /**
     * Update the patient's profile image.
     */
    public function updateProfileImage(Request $request, $id)
    {
        try {
            $patient = PatientInquiry::findOrFail($id);

            $request->validate([
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('profile_image')) {
                // Delete old image if exists
                $oldImage = $patient->getMeta('profile_image');
                if ($oldImage && file_exists(public_path($oldImage))) {
                    unlink(public_path($oldImage));
                }

                $image = $request->file('profile_image');
                $filename = 'patient_' . $id . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/patients'), $filename);
                $path = 'uploads/patients/' . $filename;

                $patient->setMeta('profile_image', $path);

                return back()->with('success', 'Profile image updated successfully.');
            }

            return back()->with('error', 'No image file provided.');
        } catch (Exception $e) {
            return back()->with('error', 'Error updating profile image: ' . $e->getMessage());
        }
    }

    public function addIndoorTreatment($id)
    {
        try {
            $patient = PatientInquiry::findOrFail($id);

            // Fetch past indoor treatments
            $treatments = PatientTreatment::where('inquiry_id', $patient->id)
                ->where('type', 'indoor')
                ->orderBy('created_at', 'desc')
                ->get();

            // Group treatments by date + time
            $groupedTreatments = $treatments->groupBy(function($item) {
                return ($item->date ?? 'No Date') . '||' . ($item->time ?? 'No Time');
            });

            // Fetch active charges
            $charges = Charges::where(function ($query) {
                $query->whereIn('delete_status', ['0', ''])->orWhereNull('delete_status');
            })
            ->orderBy('charges_name')
            ->get();

            // Fetch latest invoice for payment values
            $invoice = Invoice::where('patient_id', $patient->id)->latest()->first();

            return view('branches.profile.add_indoor_treatment', compact(
                'patient',
                'treatments',
                'groupedTreatments',
                'charges',
                'invoice'
            ));
        } catch (\Exception $e) {
            Log::error('Error in addIndoorTreatment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading Indoor Treatment page: ' . $e->getMessage());
        }
    }

    public function saveProfileIndoorTreatment(Request $request, $id)
    {
        try {
            $patient = PatientInquiry::findOrFail($id);

            // Set status to IPD so they show up on the Indoor Patients page
            $patient->setMeta('pt_status', 'IPD');

            // Do NOT delete existing indoor treatments to preserve multiple records for the same date at different times.
            // Every new submission appends the new treatment slots.

            // New slot-based inputs
            // slot_date[N], slot_time[N], slot_medicine[N][], slot_note[N][]
            $slotDates = $request->input('slot_date', []);
            $slotTimes = $request->input('slot_time', []);
            $slotTemps = $request->input('slot_temp', []);
            $slotPulses = $request->input('slot_pulse', []);
            $slotBps = $request->input('slot_bp', []);
            $slotSpo2s = $request->input('slot_spo2', []);
            $slotMedicines = $request->input('slot_medicine', []);
            $slotNotes = $request->input('slot_note', []);

            foreach ($slotMedicines as $slotIndex => $medicines) {
                if (!is_array($medicines))
                    continue;

                $date = isset($slotDates[$slotIndex]) && !empty($slotDates[$slotIndex])
                    ? $slotDates[$slotIndex]
                    : null;

                $time = isset($slotTimes[$slotIndex]) && !empty($slotTimes[$slotIndex])
                    ? $slotTimes[$slotIndex]
                    : null;

                $temp = isset($slotTemps[$slotIndex]) && !empty(trim($slotTemps[$slotIndex])) ? trim($slotTemps[$slotIndex]) : null;
                $pulse = isset($slotPulses[$slotIndex]) && !empty(trim($slotPulses[$slotIndex])) ? trim($slotPulses[$slotIndex]) : null;
                $bp = isset($slotBps[$slotIndex]) && !empty(trim($slotBps[$slotIndex])) ? trim($slotBps[$slotIndex]) : null;
                $spo2 = isset($slotSpo2s[$slotIndex]) && !empty(trim($slotSpo2s[$slotIndex])) ? trim($slotSpo2s[$slotIndex]) : null;

                $notes = $slotNotes[$slotIndex] ?? [];

                foreach ($medicines as $rowIndex => $medicine) {
                    if (!empty(trim($medicine))) {
                        PatientTreatment::create([
                            'patient_id' => $patient->patient_id,
                            'inquiry_id' => $patient->id,
                            'type' => 'indoor',
                            'medicine' => trim($medicine),
                            'dose' => null, // Dose removed
                            'days' => null, // Days removed
                            'date' => $date,
                            'time' => $time,
                            'temp' => $temp,
                            'pulse' => $pulse,
                            'bp' => $bp,
                            'spo2' => $spo2,
                            'note' => isset($notes[$rowIndex]) && !empty(trim($notes[$rowIndex]))
                                ? trim($notes[$rowIndex])
                                : null,
                        ]);
                    }
                }
            }

            // Process Payment Information if submitted
            $totalPayment = floatval($request->input('total_payment', 0));
            $givenPayment = floatval($request->input('given_payment', 0));
            $discountPayment = floatval($request->input('discount_payment', 0));
            $duePayment = floatval($request->input('due_payment', 0));
            $paymentMethod = $request->input('payment_method', 'Cash');

            if ($request->has('total_payment') || $totalPayment > 0 || $givenPayment > 0) {
                $invoice = Invoice::where('patient_id', $patient->id)->latest()->first();

                if (!$invoice && ($totalPayment > 0 || $givenPayment > 0)) {
                    $invoiceNo = 'INV-IND-' . $patient->id . '-' . time();
                    $branchId = $patient->branch_id ?? 'SVC-0005';
                    $pNameClean = preg_replace('/[^A-Za-z0-9]/', '', $patient->patient_name ?? 'Patient');
                    $invoiceFile = $pNameClean . 'SVC-' . $invoiceNo . '-' . now()->format('d-m-Y') . '.pdf';

                    $invoice = Invoice::create([
                        'patient_id' => $patient->id,
                        'invoice_no' => $invoiceNo,
                        'total_payment' => $totalPayment,
                        'given_payment' => $givenPayment,
                        'discount' => $discountPayment,
                        'due_payment' => $duePayment,
                        'branch_id' => $branchId,
                        'invoice_file' => $invoiceFile,
                    ]);
                } else if ($invoice) {
                    $invoice->total_payment = max($invoice->total_payment, $totalPayment);
                    if ($givenPayment > 0) {
                        $invoice->given_payment += $givenPayment;
                    }
                    $invoice->discount = $discountPayment;
                    $invoice->due_payment = $duePayment;
                    $invoice->save();
                }

                if ($invoice && $givenPayment > 0) {
                    PatientTransaction::create([
                        'patient_id' => $patient->id,
                        'invoice_id' => $invoice->id,
                        'type' => 'credit',
                        'amount' => $givenPayment,
                        'description' => 'Indoor Treatment Payment (' . ($paymentMethod ?: 'Cash') . ')',
                    ]);
                }
            }

            return redirect()->route('indoor.patients')->with('success', 'Indoor treatment & payment details saved successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error saving indoor treatment: ' . $e->getMessage());
        }
    }



    public function editFollowUp($patient_id, $followup_id)
    {
        try {
            $patient = PatientInquiry::where('patient_id', $patient_id)->firstOrFail();
            $followup = Followups::with('metas')->findOrFail($followup_id);

            $followupMetaValues = [];
            $metaKeys = [
                'pt_status',
                'temperature',
                'weight',
                'spo2',
                'blood_pressure',
                'pulse',
                'rbs',
                'diagnosis',
                'hb',
                'tc',
                'pc',
                'mp',
                'hb1ac',
                'fbs',
                'pp2bs',
                's_widal',
                'usg',
                'x_ray',
                'sgpt',
                's_creatinine',
                'ns1ag',
                'dengue_igm',
                's_cholesterol',
                's_triglyceride',
                'hdl',
                'ldl',
                'vldl',
                's_b12',
                's_d3',
                'urine',
                's_t3',
                'crp',
                's_t4',
                's_tsh',
                'esr',
                'complain',
                'investigation',
                'past_history',
                'family_history',
                'specific_test',
                'reference_by',
                'referto',
                'notes',
                'total_payment',
                'discount_payment',
                'given_payment',
                'due_payment',
                'cash_payment',
                'gp_payment',
                'cheque_payment',
                'non_hdl_c',
                'chol_hdl_ratio',
                'foc'
            ];

            foreach ($metaKeys as $key) {
                $followupMetaValues[$key] = [];
            }

            foreach ($followup->metas as $meta) {
                $key = $meta->meta_key;

                if (preg_match('/^(.+)_(\d+)$/', $key, $matches)) {
                    $baseKey = $matches[1];
                    $index = (int) $matches[2];

                    if (in_array($baseKey, $metaKeys)) {
                        $followupMetaValues[$baseKey][$index] = $meta->meta_value;
                    }
                } else {
                    if (in_array($key, $metaKeys)) {
                        $followupMetaValues[$key][] = $meta->meta_value;
                    }
                }
            }

            foreach ($followupMetaValues as $key => $values) {
                if (count($values) > 0) {
                    ksort($followupMetaValues[$key]);
                    $followupMetaValues[$key] = array_values($followupMetaValues[$key]);
                }
            }

            $treatments = [];
            $groups = ['inside', 'homeo', 'prescription', 'indoor', 'other'];

            foreach ($groups as $group) {
                $treatments[$group] = PatientTreatment::where('patient_id', $patient->patient_id)
                    ->where('followup_id', $followup->id)
                    ->where('type', $group)
                    ->get()
                    ->toArray();
            }

            $doctors = \App\Models\User::where('user_role', 6)->get();
            $charges = \App\Models\Charges::where(function ($query) {
                $query->whereIn('delete_status', ['0', ''])->orWhereNull('delete_status');
            })
                ->orderBy('charges_name')
                ->get();

            return view('branches.profile.edit_follow_up', compact(
                'patient',
                'followup',
                'treatments',
                'followupMetaValues',
                'doctors',
                'charges'
            ));
        } catch (\Exception $e) {
            Log::error('Error in editFollowUp: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading follow-up: ' . $e->getMessage());
        }
    }

    public function updateFollowUp(Request $request, $patient_id, $followup_id)
    {
        try {
            $patient = PatientInquiry::where('patient_id', $patient_id)->firstOrFail();
            $followup = Followups::findOrFail($followup_id);

            $followup->update([
                'followup_date' => $request->followup_date,
            ]);

            // Update patient gender and phone core record meta
            if ($request->has('gender')) {
                $patient->setMeta('gender', $request->gender);
            }
            if ($request->has('phone')) {
                $patient->setMeta('phone', $request->phone);
            }
            $patient->save(); // Redundant but good practice if setMeta doesn't save (but it does via updateOrCreate)

            $followup->metas()->delete();
            $excluded = [
                '_token',
                '_method',
                'followup_date',
                'inside_medicine',
                'homeo_medicine',
                'prescription_medicine',
                'indoor_medicine',
                'other_medicine',
                'inside_dose',
                'inside_timing',
                'homeo_timing',
                'prescription_dose',
                'prescription_timing',
                'indoor_dose',
                'indoor_note',
                'other_note'
            ];

            $metaFields = $request->except($excluded);

            foreach ($metaFields as $key => $value) {
                if ($key === 'charge_id') {
                    $followup->setMeta($key, is_array($value) ? json_encode($value) : $value);
                    continue;
                }

                if (is_array($value)) {
                    foreach ($value as $index => $item) {
                        if (!empty($item) || $item === '0') {
                            $followup->setMeta("{$key}_{$index}", $item);
                        }
                    }
                } else {
                    if (!empty($value) || $value === '0') {
                        $followup->setMeta($key, $value);
                    }
                }
            }

            PatientTreatment::where('followup_id', $followup->id)->delete();

            $groups = [
                'inside' => ['dose', 'timing', 'days'],
                'homeo' => ['dose', 'timing', 'days'],
                'prescription' => ['dose', 'timing', 'days'],
                'indoor' => ['dose', 'note', 'days', 'date', 'time'],
                'other' => ['note'],
            ];

            foreach ($groups as $type => $fields) {
                $medicineKey = $type . '_medicine';

                if ($request->has($medicineKey)) {
                    foreach ($request->$medicineKey as $i => $medicine) {
                        if (!empty(trim($medicine))) {
                            $data = [
                                'followup_id' => $followup->id,
                                'patient_id' => $patient->patient_id,
                                'type' => $type,
                                'medicine' => trim($medicine),
                            ];

                            foreach ($fields as $f) {
                                // Handle dose fields with dual input (textbox + dropdown)
                                if ($f === 'dose') {
                                    $textboxField = $type . '_dose';
                                    $dropdownField = $type . '_dose_dropdown';

                                    // Get textbox value (manual entry)
                                    $textboxValues = $request->input($textboxField, []);
                                    $textboxValue = $textboxValues[$i] ?? null;

                                    // Get dropdown value
                                    $dropdownValues = $request->input($dropdownField, []);
                                    $dropdownValue = $dropdownValues[$i] ?? null;

                                    // Use textbox value if available, otherwise use dropdown value
                                    $data[$f] = !empty($textboxValue) ? $textboxValue : $dropdownValue;

                                    \Log::info("Followup dose processing for {$type}[{$i}]: textbox={$textboxValue}, dropdown={$dropdownValue}, final={$data[$f]}");
                                } else {
                                    // Handle other fields normally
                                    $fieldName = $type . '_' . $f;
                                    $fieldValues = $request->input($fieldName, []);
                                    if (isset($fieldValues[$i])) {
                                        $data[$f] = $fieldValues[$i];
                                    }
                                }
                            }

                            PatientTreatment::create($data);
                        }
                    }
                }
            }

            return redirect()
                ->route('svc.profile', ['id' => $patient->id])
                ->with('success', 'Follow-up updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error in updateFollowUp: ' . $e->getMessage());
            return back()->with('error', 'Error updating follow-up: ' . $e->getMessage());
        }
    }

    public function deleteFollowUp($id)
    {
        try {
            $followup = Followups::findOrFail($id);


            $patientId = $followup->patient_id;
            $patientInquiry = PatientInquiry::where('patient_id', $patientId)->first();

            if (!$patientInquiry) {
                return redirect()->route('svc-patient')->with('error', 'Patient not found.');
            }

            PatientTreatment::where('followup_id', $id)->delete();
            $followup->metas()->delete();

            $followup->delete();

            return redirect()
                ->route('svc.profile', ['id' => $patientInquiry->id])
                ->with('success', 'Follow-up record deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting follow-up: ' . $e->getMessage());

            return redirect()
                ->route('svc.profile', ['id' => $patientInquiry->id ?? null])
                ->with('error', 'Error deleting follow-up record: ' . $e->getMessage());
        }
    }

    public function exportSvcPatients(Request $request)
    {
        try {
            $query = PatientInquiry::where('branch', 'SVC');

            if ($request->has('name_search') && !empty($request->name_search)) {
                $query->where('patient_name', 'like', '%' . $request->name_search . '%');
            }

            if ($request->has('global_search') && !empty($request->global_search)) {
                $searchTerm = $request->global_search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('patient_id', 'like', '%' . $searchTerm . '%')
                        ->orWhere('address', 'like', '%' . $searchTerm . '%')
                        ->orWhere('diagnosis', 'like', '%' . $searchTerm . '%')
                        ->orWhere('age', 'like', '%' . $searchTerm . '%')
                        ->orWhereRaw("DATE_FORMAT(inquiry_date, '%d/%m/%Y') LIKE ?", ['%' . $searchTerm . '%'])
                        ->orWhereRaw("DATE_FORMAT(next_follow_date, '%d/%m/%Y') LIKE ?", ['%' . $searchTerm . '%'])
                        ->orWhere('inquiry_date', 'like', '%' . $searchTerm . '%')
                        ->orWhere('next_follow_date', 'like', '%' . $searchTerm . '%');
                });
            }

            $patients = $query->orderBy('created_at', 'desc')->get();

            $csvData = "Patient ID,Name,Address,Age,Diagnosis,Inquiry Date,Follow Up Date\n";

            foreach ($patients as $patient) {
                $csvData .= '"' .
                    $patient->patient_id . '","' .
                    $patient->patient_name . '","' .
                    $patient->address . '","' .
                    $patient->age . '","' .
                    $patient->diagnosis . '","' .
                    ($patient->inquiry_date ? \Carbon\Carbon::parse($patient->inquiry_date)->format('d/m/Y') : '') . '","' .
                    ($patient->next_follow_date ? \Carbon\Carbon::parse($patient->next_follow_date)->format('d/m/Y') : '') . '"' .
                    "\n";
            }

            $filename = 'svc_patients_' . date('Y-m-d_H-i-s') . '.csv';

            return response($csvData)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return back()->with('error', 'Error exporting data: ' . $e->getMessage());
        }
    }

    // public function addFollowUp(Request $request, $patient_id)
    // {
    //     $patient = PatientInquiry::with(['followups.metas', 'treatments'])
    //         ->where('patient_id', $patient_id)
    //         ->firstOrFail();

    //     $followupDates = $patient->followups()
    //         ->select('followup_date')
    //         ->distinct()
    //         ->orderBy('followup_date', 'desc')
    //         ->pluck('followup_date')
    //         ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'));

    //     $selectedDate = $request->query('date');

    //     // If no date is selected, default to today
    //     if (!$selectedDate) {
    //         $selectedDate = now()->format('Y-m-d');
    //     }

    //     // Rest of your existing code remains the same...
    //     $followupMetaValues = [];
    //     $metaKeys = [
    //         'pt_status','temperature','weight','spo2','blood_pressure','pulse','rbs',
    //         'diagnosis','hb','tc','pc','mp','hb1ac','fbs','pp2bs','s_widal','usg',
    //         'x_ray','sgpt','s_creatinine','ns1ag','dengue_igm','s_cholesterol',
    //         's_triglyceride','hdl','ldl','vldl','s_b12','s_d3','urine','s_t3','crp',
    //         's_t4','s_tsh','esr'
    //     ];

    //     foreach ($metaKeys as $key) {
    //         $followupMetaValues[$key] = [''];
    //     }

    //     $treatments = [
    //         'inside' => [],
    //         'homeo' => [],
    //         'prescription' => [],
    //         'indoor' => [],
    //         'other' => []
    //     ];

    //     if ($selectedDate) {
    //         $followup = $patient->followups()
    //             ->whereDate('followup_date', $selectedDate)
    //             ->latest('created_at')
    //             ->first();

    //         if ($followup) {
    //             $allFollowupMetas = $followup->metas()->get();

    //             foreach ($metaKeys as $key) {
    //                 $values = $allFollowupMetas
    //                     ->filter(function($meta) use ($key) {
    //                         return $meta->meta_key === $key ||
    //                                Str::startsWith($meta->meta_key, $key . '_');
    //                     })
    //                     ->sortBy(function($meta) {
    //                         if (preg_match('/_(\d+)$/', $meta->meta_key, $matches)) {
    //                             return (int)$matches[1];
    //                         }
    //                         return 0;
    //                     })
    //                     ->pluck('meta_value')
    //                     ->values()
    //                     ->toArray();

    //                 $followupMetaValues[$key] = $values;
    //             }

    //             $followupTreatments = PatientTreatment::where('followup_id', $followup->id)->get();

    //             foreach ($followupTreatments as $treatment) {
    //                 $type = $treatment->type;
    //                 if (array_key_exists($type, $treatments)) {
    //                     $treatments[$type][] = [
    //                         'medicine' => $treatment->medicine,
    //                         'dose' => $treatment->dose,
    //                         'timing' => $treatment->timing,
    //                         'note' => $treatment->note
    //                     ];
    //                 }
    //             }
    //         }
    //     }

    //     return view('branches.profile.add_follow_up', compact(
    //         'patient',
    //         'metaKeys',
    //         'selectedDate',
    //         'followupDates',
    //         'treatments',
    //         'followupMetaValues'
    //     ));
    // }
    // public function storeFollowUp(Request $request, $patient_id)
    // {
    //      try {
    //         $patient = PatientInquiry::where('patient_id', $patient_id)->firstOrFail();

    //         $followup = Followups::create([
    //             'patient_id'   => $patient->patient_id,
    //             'inquiry_id'   => $patient->id,
    //             'followup_date' => $request->followup_date,
    //             'next_follow_date' => $request->next_follow_date,
    //             'followups_time' => $request->followups_time, 
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);

    //         $followup->metas()->delete();

    //         $excluded = [
    //             '_token',
    //             'followup_date',
    //             'followups_time', // Add this
    //             'inside_medicine', 'homeo_medicine',
    //             'prescription_medicine', 'indoor_medicine',
    //             'other_medicine'
    //         ];

    //         $metaFields = $request->except($excluded);

    //         foreach ($metaFields as $key => $value) {
    //             if (is_array($value)) {
    //                 foreach ($value as $index => $item) {
    //                     if ($item === null || $item === '') {
    //                         continue;
    //                     }

    //                     FollowupMeta::create([
    //                         'followup_id' => $followup->id,
    //                         'meta_key'    => "{$key}_{$index}",
    //                         'meta_value'  => $item
    //                     ]);
    //                 }
    //             } else {
    //                 if ($value !== null && $value !== '') {
    //                     FollowupMeta::create([
    //                         'followup_id' => $followup->id,
    //                         'meta_key'    => $key,
    //                         'meta_value'  => $value
    //                     ]);
    //                 }
    //             }
    //         }

    //         PatientTreatment::where('followup_id', $followup->id)->delete();

    //         $groups = [
    //             'inside'        => ['dose', 'timing'],
    //             'homeo'         => ['timing'],
    //             'prescription'  => ['dose', 'timing'],
    //             'indoor'        => ['dose', 'note'],
    //             'other'         => ['note'],
    //         ];

    //         foreach ($groups as $type => $fields) {
    //             $medicineKey = $type . '_medicine';

    //             if ($request->has($medicineKey)) {
    //                 foreach ($request->$medicineKey as $i => $medicine) {
    //                     if (!empty($medicine)) {
    //                         $data = [
    //                             'followup_id' => $followup->id,
    //                             'inquiry_id' => null,
    //                             'patient_id'  => $patient->patient_id,
    //                             'type'        => $type,
    //                             'medicine'    => $medicine,
    //                         ];

    //                         foreach ($fields as $f) {
    //                             $fieldName = $type . '_' . $f;
    //                             $fieldValues = $request->input($fieldName, []);
    //                             if (isset($fieldValues[$i])) {
    //                                 $data[$f] = $fieldValues[$i];
    //                             }
    //                         }

    //                         PatientTreatment::create($data);
    //                     }
    //                 }
    //             }
    //         }

    //         // FIX: Redirect back to the follow-up page with the selected date
    //         return redirect()
    //             ->route('add.follow.up', [
    //                 'patient_id' => $patient->patient_id,
    //                 'date' => $request->followup_date // Pass the date as query parameter
    //             ])
    //             ->with('success', 'Follow-up data saved successfully.');

    //     } catch (\Exception $e) {
    //         return redirect()
    //             ->back()
    //             ->with('error', 'Error saving follow-up data: ' . $e->getMessage());
    //     }
    // }
    public function addFollowUp(Request $request, $patient_id)
    {
        $patient = PatientInquiry::with(['followups.metas', 'treatments'])
            ->where('patient_id', $patient_id)
            ->firstOrFail();

        // Get unique followup dates with time from meta
        $followupDates = $patient->followups()
            ->with([
                'metas' => function ($query) {
                    $query->where('meta_key', 'followups_time');
                }
            ])
            ->orderBy('followup_date', 'desc')
            ->get()
            ->map(function ($followup) {
                // Get time from meta
                $timeMeta = $followup->metas->firstWhere('meta_key', 'followups_time');
                $followup->followups_time = $timeMeta ? $timeMeta->meta_value : '00:00:00';
                return $followup;
            })
            ->groupBy('followup_date');

        $selectedDate = $request->query('date');
        $selectedTime = $request->query('time');

        // If no date is selected, default to today
        if (!$selectedDate) {
            $selectedDate = now()->format('Y-m-d');
        }

        // Initialize variables
        $followupMetaValues = [];
        $metaKeys = [
            'pt_status',
            'temperature',
            'weight',
            'spo2',
            'blood_pressure',
            'pulse',
            'rbs',
            'diagnosis',
            'hb',
            'tc',
            'pc',
            'MP',
            'HB1AC',
            'fbs',
            'pp2bs',
            'S_widal',
            'usg',
            'X_ray',
            'SGPT',
            's_creatinine',
            'NS1Ag',
            'DengueIGM',
            's_cholesterol',
            'STriglyceride',
            'HDL',
            'LDL',
            'VLDL',
            'SB12',
            'SD3',
            'Urine',
            'St3',
            'crp',
            'St4',
            'STSH',
            'ESR',
            'notes',
            'reference_by',
            'referto',
            'specific_test',
            'total_payment',
            'given_payment',
            'payment_method',
            'due_payment',
            'complain',
            'investigation',
            'past_history',
            'family_history',
            'non_hdl_c',
            'chol_hdl_ratio',
            'foc'
        ];

        foreach ($metaKeys as $key) {
            if ($key === 'reference_by' || $key === 'payment_method') {
                $followupMetaValues[$key] = [$patient->getMeta($key) ?? ''];
            } else {
                $followupMetaValues[$key] = [''];
            }
        }

        $treatments = [
            'inside' => [],
            'homeo' => [],
            'prescription' => [],
            'indoor' => [],
            'other' => []
        ];

        if ($selectedDate) {
            $query = $patient->followups()
                ->whereDate('followup_date', $selectedDate);

            $followup = null;

            // If specific time is selected, filter by time from meta
            if ($selectedTime) {
                $followups = $query->with('metas')->get();
                foreach ($followups as $f) {
                    $timeMeta = $f->metas->firstWhere('meta_key', 'followups_time');
                    if ($timeMeta && $timeMeta->meta_value == $selectedTime) {
                        $followup = $f;
                        break;
                    }
                }
            } else {
                $followup = $query->latest('created_at')->first();
            }

            if ($followup) {
                $allFollowupMetas = $followup->metas()->get();

                foreach ($metaKeys as $key) {
                    $values = $allFollowupMetas
                        ->filter(function ($meta) use ($key) {
                            return $meta->meta_key === $key ||
                                Str::startsWith($meta->meta_key, $key . '_');
                        })
                        ->sortBy(function ($meta) {
                            if (preg_match('/_(\d+)$/', $meta->meta_key, $matches)) {
                                return (int) $matches[1];
                            }
                            return 0;
                        })
                        ->pluck('meta_value')
                        ->values()
                        ->toArray();

                    if (!empty($values)) {
                        $followupMetaValues[$key] = $values;
                    }
                }

                $followupTreatments = PatientTreatment::where('followup_id', $followup->id)->get();

                foreach ($followupTreatments as $treatment) {
                    $type = $treatment->type;
                    if (array_key_exists($type, $treatments)) {
                        $treatments[$type][] = [
                            'medicine' => $treatment->medicine,
                            'dose' => $treatment->dose,
                            'timing' => $treatment->timing,
                            'days' => $treatment->days,
                            'date' => $treatment->date,
                            'time' => $treatment->time,
                            'note' => $treatment->note
                        ];
                    }
                }
            }
        }

        $doctors = User::where('user_role', 6)->get();

        // Get active charges
        $charges = Charges::where(function ($query) {
            $query->whereIn('delete_status', ['0', ''])->orWhereNull('delete_status');
        })
            ->orderBy('charges_name')
            ->get();

        return view('branches.profile.add_follow_up', compact(
            'patient',
            'metaKeys',
            'selectedDate',
            'selectedTime',
            'followupDates',
            'treatments',
            'followupMetaValues',
            'doctors',
            'followup',
            'charges'
        ));
    }

    public function getFollowupHistory(Request $request, $patient_id)
    {
        try {
            $patient = PatientInquiry::where('patient_id', $patient_id)->firstOrFail();
            $date = $request->query('date');
            $time = $request->query('time');

            if (!$date) {
                return response()->json([
                    'success' => false,
                    'html' => '<div class="alert alert-warning">Please select a date first.</div>'
                ]);
            }

            // Get all followups for the selected date with their metas
            $followups = Followups::with(['metas', 'treatments'])
                ->where('patient_id', $patient->patient_id)
                ->whereDate('followup_date', $date)
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($followup) {
                    // Add followups_time from meta to the followup object
                    $timeMeta = $followup->metas->firstWhere('meta_key', 'followups_time');
                    $followup->followups_time = $timeMeta ? $timeMeta->meta_value : '00:00:00';
                    return $followup;
                });

            // If specific time is selected, filter by time
            if ($time) {
                $followups = $followups->filter(function ($followup) use ($time) {
                    return $followup->followups_time == $time;
                });
            }

            if ($followups->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'html' => '<div class="alert alert-warning">No follow-up data found for this date.</div>'
                ]);
            }

            // Prepare meta keys for the view
            $metaKeys = [
                'pt_status',
                'temperature',
                'weight',
                'spo2',
                'blood_pressure',
                'pulse',
                'rbs',
                'diagnosis',
                'hb',
                'tc',
                'pc',
                'mp',
                'hb1ac',
                'fbs',
                'pp2bs',
                's_widal',
                'usg',
                'x_ray',
                'sgpt',
                's_creatinine',
                'ns1ag',
                'dengue_igm',
                's_cholesterol',
                's_triglyceride',
                'hdl',
                'ldl',
                'vldl',
                's_b12',
                's_d3',
                'urine',
                's_t3',
                'crp',
                's_t4',
                's_tsh',
                'esr'
            ];

            $html = view('branches.profile.followup_history_time', [
                'patient' => $patient,
                'followups' => $followups,
                'selectedDate' => $date,
                'selectedTime' => $time,
                'metaKeys' => $metaKeys  // Pass metaKeys to the view
            ])->render();

            return response()->json([
                'success' => true,
                'html' => $html,
                'count' => $followups->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('Followup History Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'html' => '<div class="alert alert-danger">Error loading history data: ' . $e->getMessage() . '</div>'
            ]);
        }
    }

    public function storeFollowUp(Request $request, $patient_id)
    {
        try {
            $patient = PatientInquiry::where('patient_id', $patient_id)->firstOrFail();

            // Check if a followup already exists for this date and time
            $existingFollowups = Followups::with('metas')
                ->where('patient_id', $patient->patient_id)
                ->whereDate('followup_date', $request->followup_date)
                ->get();

            $existingFollowup = null;
            foreach ($existingFollowups as $followup) {
                $timeMeta = $followup->metas->firstWhere('meta_key', 'followups_time');
                if ($timeMeta && $timeMeta->meta_value == $request->followups_time) {
                    $existingFollowup = $followup;
                    break;
                }
            }

            if ($existingFollowup) {
                // Update existing followup
                $followup = $existingFollowup;
                $followup->doctor_id = $request->doctor_id;
                $followup->next_follow_date = $request->next_follow_date;
                $followup->updated_at = now();
                $followup->save();
            } else {
                // Create new followup
                $followup = Followups::create([
                    'patient_id' => $patient->patient_id,
                    'inquiry_id' => $patient->id,
                    'doctor_id' => $request->doctor_id,
                    'followup_date' => $request->followup_date,
                    'next_follow_date' => $request->next_follow_date,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $followup->metas()->delete();

            // Store followups_time as meta
            if ($request->followups_time) {
                FollowupMeta::create([
                    'followup_id' => $followup->id,
                    'meta_key' => 'followups_time',
                    'meta_value' => $request->followups_time
                ]);
            }

            $excluded = [
                '_token',
                'followup_date',
                'followups_time',
                'next_follow_date',
                'inside_medicine',
                'homeo_medicine',
                'prescription_medicine',
                'indoor_medicine',
                'other_medicine'
            ];

            $metaFields = $request->except($excluded);

            foreach ($metaFields as $key => $value) {
                if ($key === 'charge_id') {
                    if ($value !== null && $value !== '') {
                        FollowupMeta::create([
                            'followup_id' => $followup->id,
                            'meta_key' => $key,
                            'meta_value' => is_array($value) ? json_encode($value) : $value
                        ]);
                    }
                    continue;
                }

                if (is_array($value)) {
                    foreach ($value as $index => $item) {
                        if ($item === null || $item === '') {
                            continue;
                        }

                        FollowupMeta::create([
                            'followup_id' => $followup->id,
                            'meta_key' => "{$key}_{$index}",
                            'meta_value' => $item
                        ]);
                    }
                } else {
                    if ($value !== null && $value !== '') {
                        FollowupMeta::create([
                            'followup_id' => $followup->id,
                            'meta_key' => $key,
                            'meta_value' => $value
                        ]);
                    }
                }
            }

            PatientTreatment::where('followup_id', $followup->id)->delete();

            $groups = [
                'inside' => ['dose', 'timing', 'days'],
                'homeo' => ['dose', 'timing', 'days'],
                'prescription' => ['dose', 'timing', 'days'],
                'indoor' => ['dose', 'note', 'days', 'date', 'time'],
                'other' => ['note'],
            ];

            foreach ($groups as $type => $fields) {
                $medicineKey = $type . '_medicine';

                if ($request->has($medicineKey)) {
                    foreach ($request->$medicineKey as $i => $medicine) {
                        if (!empty($medicine)) {
                            $data = [
                                'followup_id' => $followup->id,
                                'inquiry_id' => null,
                                'patient_id' => $patient->patient_id,
                                'type' => $type,
                                'medicine' => $medicine,
                            ];

                            foreach ($fields as $f) {
                                $fieldName = $type . '_' . $f;
                                $fieldValues = $request->input($fieldName, []);
                                if (isset($fieldValues[$i])) {
                                    $data[$f] = $fieldValues[$i];
                                }
                            }

                            PatientTreatment::create($data);
                        }
                    }
                }
            }

            // Create Invoice and Transactions for Followup Charges
            $totalPayment = $request->input('total_payment', 0);
            if ($totalPayment > 0) {
                // Check if an invoice already exists for this followup to avoid duplicates
                $existingInvoice = Invoice::where('invoice_no', 'LIKE', 'INV-FOL-' . $followup->id . '%')->first();

                if (!$existingInvoice) {
                    // Generate unique invoice number
                    $invoiceNo = 'INV-FOL-' . $followup->id;
                    $counter = 1;
                    $finalInvoiceNo = $invoiceNo;
                    while (Invoice::where('invoice_no', $finalInvoiceNo)->exists()) {
                        $finalInvoiceNo = $invoiceNo . '-' . $counter;
                        $counter++;
                    }

                    // Use the patient's branch_id
                    $branchId = $patient->branch_id ?? 'SVC-0005';

                    // Generate Filename
                    $pNameClean = preg_replace('/[^A-Za-z0-9]/', '', $patient->patient_name ?? 'Patient');
                    $invoiceFile = $pNameClean . 'SVC-' . $finalInvoiceNo . '-' . now()->format('d-m-Y') . '.pdf';

                    $selectedChargeIds = $request->input('charge_id', []);
                    if (!is_array($selectedChargeIds))
                        $selectedChargeIds = [$selectedChargeIds];

                    $chargesData = [];

                    // Regular charges
                    $selectedCharges = Charges::whereIn('id', $selectedChargeIds)->get();
                    foreach ($selectedCharges as $c) {
                        $chargesData[] = [
                            'charge_id' => $c->id,
                            'charge_name' => $c->charges_name,
                            'price' => $c->charges_price
                        ];
                    }

                    // Custom charges
                    $customNames = $request->input('custom_charge_name', []);
                    $customPrices = $request->input('custom_charge_price', []);
                    if (is_array($customNames)) {
                        foreach ($customNames as $i => $name) {
                            if (!empty($name)) {
                                $chargesData[] = [
                                    'charge_id' => null,
                                    'charge_name' => $name,
                                    'price' => $customPrices[$i] ?? 0
                                ];
                            }
                        }
                    }

                    if (empty($chargesData)) {
                        $chargesData = [
                            [
                                'charge_id' => null,
                                'charge_name' => 'Followup Charges',
                                'price' => $totalPayment
                            ]
                        ];
                    }

                    $cashPayment   = (float) $request->input('cash_payment', 0);
                    $gpayPayment   = (float) $request->input('gp_payment', 0);
                    $chequePayment = (float) $request->input('cheque_payment', 0);
                    $givenPayment  = $cashPayment + $gpayPayment + $chequePayment;

                    // Fallback: if no split payments, use given_payment field directly
                    if ($givenPayment <= 0) {
                        $givenPayment = (float) $request->input('given_payment', 0);
                    }

                    $discountPayment = (float) $request->input('discount_payment', 0);
                    $duePayment = max(0, (float) $totalPayment - $discountPayment - $givenPayment);

                    $invoice = Invoice::create([
                        'branch_id'      => $branchId,
                        'patient_id'     => $patient->id,
                        'invoice_no'     => $finalInvoiceNo,
                        'invoice_date'   => now()->format('Y-m-d'),
                        'address'        => $request->address ?? $patient->address ?? '',
                        'phone'          => $patient->getMeta('phone') ?? '',
                        'price'          => $totalPayment,
                        'total_payment'  => $totalPayment,
                        'discount'       => $discountPayment,
                        'given_payment'  => $givenPayment,
                        'due_payment'    => $duePayment,
                        'cash_payment'   => $cashPayment,
                        'gpay_payment'   => $gpayPayment,
                        'cheque_payment' => $chequePayment,
                        'invoice_file'   => $invoiceFile,
                        'charges_data'   => $chargesData,
                    ]);

                    // Determine branch prefix
                    if ($branchId === 'LB-0007') {
                        $descPrefix = 'LHR Service';
                    } elseif ($branchId === 'BH-00023') {
                        $descPrefix = 'Hydra Service';
                    } elseif ($branchId === 'SVC-0005') {
                        $descPrefix = 'SVC Service';
                    } else {
                        $descPrefix = 'FNF Service';
                    }

                    // Debit Transaction
                    PatientTransaction::create([
                        'branch_id' => $branchId,
                        'patient_id' => $patient->id,
                        'invoice_id' => $invoice->id,
                        'type' => 'debit',
                        'amount' => $totalPayment,
                        'description' => $descPrefix . ' (Followup) - Invoice Generated: ' . $invoice->invoice_no,
                    ]);

                    // Credit Transaction
                    if ($givenPayment > 0) {
                        PatientTransaction::create([
                            'branch_id' => $branchId,
                            'patient_id' => $patient->id,
                            'invoice_id' => $invoice->id,
                            'type' => 'credit',
                            'amount' => $givenPayment,
                            'description' => $descPrefix . ' (Followup) Payment Received (' . ($request->input('payment_method') ?? 'Cash') . ') for Invoice: ' . $invoice->invoice_no,
                        ]);
                    }
                }
            }

            // Redirect to SVC patients list after successful save
            return redirect()
                ->route('svc-patient')
                ->with('success', 'Follow-up data saved successfully for ' . $patient->patient_name . '.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error saving follow-up data: ' . $e->getMessage());
        }
    }


    public function getFollowupDetails($followup_id)
    {
        try {
            $followup = Followups::with(['metas', 'treatments'])->findOrFail($followup_id);

            // Add followups_time from meta
            $timeMeta = $followup->metas->firstWhere('meta_key', 'followups_time');
            $followup->followups_time = $timeMeta ? $timeMeta->meta_value : '00:00:00';

            // Meta keys for display
            $metaKeys = [
                'pt_status',
                'temperature',
                'weight',
                'spo2',
                'blood_pressure',
                'pulse',
                'rbs',
                'diagnosis',
                'hb',
                'tc',
                'pc',
                'mp',
                'hb1ac',
                'fbs',
                'pp2bs',
                's_widal',
                'usg',
                'x_ray',
                'sgpt',
                's_creatinine',
                'ns1ag',
                'dengue_igm',
                's_cholesterol',
                's_triglyceride',
                'hdl',
                'ldl',
                'vldl',
                's_b12',
                's_d3',
                'urine',
                's_t3',
                'crp',
                's_t4',
                's_tsh',
                'esr'
            ];

            $html = view('branches.profile.partials.single_followup_details', [
                'followup' => $followup,
                'metaKeys' => $metaKeys
            ])->render();

            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'html' => '<div class="alert alert-danger">Error loading visit details: ' . $e->getMessage() . '</div>'
            ]);
        }
    }
    public function getFullFollowupDetails($followup_id)
    {
        try {
            $followup = Followups::with(['metas', 'treatments'])->findOrFail($followup_id);
            $patient = PatientInquiry::where('patient_id', $followup->patient_id)->first();

            if (!$patient) {
                return response()->json([
                    'success' => false,
                    'html' => '<div class="alert alert-danger">Patient not found</div>'
                ]);
            }

            // Add followups_time from meta
            $timeMeta = $followup->metas->firstWhere('meta_key', 'followups_time');
            $followup->followups_time = $timeMeta ? $timeMeta->meta_value : '00:00:00';

            $html = view('branches.profile.full_followup_details', [
                'followup' => $followup,
                'patient' => $patient
            ])->render();

            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            \Log::error('Full Followup Details Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'html' => '<div class="alert alert-danger">Error loading full details: ' . $e->getMessage() . '</div>'
            ]);
        }
    }

    // Get all meta values as an array
    public function getAllMetaValues()
    {
        $metas = $this->metas->pluck('meta_value', 'meta_key')->toArray();

        // Handle array values (like weight_0, weight_1)
        $result = [];
        foreach ($metas as $key => $value) {
            if (preg_match('/^(.+)_(\d+)$/', $key, $matches)) {
                $baseKey = $matches[1];
                $index = $matches[2];
                if (!isset($result[$baseKey])) {
                    $result[$baseKey] = [];
                }
                $result[$baseKey][$index] = $value;
            } else {
                $result[$key] = $value;
            }
        }

        // Convert arrays to comma-separated strings for display
        foreach ($result as $key => $value) {
            if (is_array($value)) {
                $result[$key] = implode(', ', array_filter($value));
            }
        }

        return $result;
    }

    // Get meta value with fallback
    public function getMeta($key, $default = null)
    {
        $meta = $this->metas->firstWhere('meta_key', $key);

        if (!$meta) {
            // Check for array values
            $arrayValues = $this->metas
                ->filter(function ($item) use ($key) {
                    return str_starts_with($item->meta_key, $key . '_');
                })
                ->sortBy(function ($item) {
                    if (preg_match('/_(\d+)$/', $item->meta_key, $matches)) {
                        return (int) $matches[1];
                    }
                    return 0;
                })
                ->pluck('meta_value')
                ->values();

            if ($arrayValues->isNotEmpty()) {
                return $arrayValues->implode(', ');
            }

            return $default;
        }

        return $meta->meta_value ?: $default;
    }
}
// public function getFollowupHistory(Request $request, $patient_id)
// {
//     $selectedDate = $request->query('date');

//     if (!$selectedDate) {
//         return response()->json([
//             'success' => false,
//             'message' => 'No date selected'
//         ]);
//     }

//     $patient = PatientInquiry::with(['followups.metas', 'treatments'])
//         ->where('patient_id', $patient_id)
//         ->firstOrFail();

//     $followup = $patient->followups()
//         ->whereDate('followup_date', $selectedDate)
//         ->latest('created_at')
//         ->first();

//     if (!$followup) {
//         return response()->json([
//             'success' => false,
//             'message' => 'No followup found for this date'
//         ]);
//     }

//     $allFollowupMetas = $followup->metas()->get();

//     $metaKeys = [
//         'pt_status','temperature','weight','spo2','blood_pressure','pulse','rbs',
//         'diagnosis','hb','tc','pc','mp','hb1ac','fbs','pp2bs','s_widal','usg',
//         'x_ray','sgpt','s_creatinine','ns1ag','dengue_igm','s_cholesterol',
//         's_triglyceride','hdl','ldl','vldl','s_b12','s_d3','urine','s_t3','crp',
//         's_t4','s_tsh','esr'
//     ];

//     $followupMetaValues = [];
//     foreach ($metaKeys as $key) {
//         $values = $allFollowupMetas
//             ->filter(function($meta) use ($key) {
//                 return $meta->meta_key === $key ||
//                        Str::startsWith($meta->meta_key, $key . '_');
//             })
//             ->sortBy(function($meta) {
//                 if (preg_match('/_(\d+)$/', $meta->meta_key, $matches)) {
//                     return (int)$matches[1];
//                 }
//                 return 0;
//             })
//             ->pluck('meta_value')
//             ->filter(function($value) {
//                 return $value !== null && $value !== 'null' && $value !== '';
//             })
//             ->values()
//             ->toArray();

//         $followupMetaValues[$key] = $values;
//     }

//     $treatments = [
//         'inside' => [],
//         'homeo' => [],
//         'prescription' => [],
//         'indoor' => [],
//         'other' => []
//     ];

//     $followupTreatments = PatientTreatment::where('followup_id', $followup->id)->get();
//     foreach ($followupTreatments as $treatment) {
//         $type = $treatment->type;
//         if (array_key_exists($type, $treatments)) {
//             $treatments[$type][] = [
//                 'medicine' => $treatment->medicine,
//                 'dose' => $treatment->dose,
//                 'timing' => $treatment->timing,
//                 'note' => $treatment->note
//             ];
//         }
//     }

//     $historyContent = view('branches.profile.followup_history', compact(
//         'patient',
//         'followup',
//         'followupMetaValues',
//         'treatments',
//         'selectedDate'
//     ))->render();

//     return response()->json([
//         'success' => true,
//         'html' => $historyContent
//     ]);
// }
