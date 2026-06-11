<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccInquiry;
use App\Models\Branch;
use App\Models\DietPlan;
use App\Models\HydraInquiry;
use App\Models\LHRInquiry;
use App\Models\MonthlyAssessment;
use App\Models\Opt;
use App\Models\OptMeta;
use App\Models\PatientInquiry;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class InquiryDietChartController extends Controller
{
   public function dietChart(Request $request)
{
    $query = AccInquiry::where(function ($q) {
            $q->whereNull('delete_status')
              ->orWhere('delete_status', '0');
        })
        ->where(function ($q) {
            // New records: status_history is a JSON array containing Diet Chart or Active
            $q->whereJsonContains('status_history', 'Diet Chart')
              ->orWhereJsonContains('status_history', 'Active')
              // Old records: status_history is null/empty but user_status was a plain string
              ->orWhere(function ($sub) {
                  $sub->where(function ($s) {
                          $s->whereNull('status_history')
                            ->orWhere('status_history', '')
                            ->orWhere('status_history', '[]');
                      })
                      ->where(function ($s) {
                          $s->where('user_status', 'Diet Chart')
                            ->orWhere('user_status', 'Active');
                      });
              });
        });

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('patient_id', 'like', "%$search%")
              ->orWhere('patient_f_name', 'like', "%$search%")
              ->orWhere('phone_no', 'like', "%$search%")
              ->orWhere('address', 'like', "%$search%")
              ->orWhere('diagnosis', 'like', "%$search%");
        });
    }

    $inquiries = $query->orderBy('id', 'desc')->paginate(10);

    return view('admin.inquiry.patient_diet_chart', compact('inquiries'));
}


    public function create(Request $request)
    {
        $lead = null;
        $selectedStatuses = [];
        $optMeta = []; // For repopulating health metrics and payment fields on edit

        if ($request->id) {
            // Use withoutGlobalScopes to ensure the record is found regardless of branch restriction
            $lead = AccInquiry::withoutGlobalScopes()->find($request->id);

            // Load existing statuses - use the casted array directly
            if ($lead) {
                $selectedStatuses = $lead->status_history ?? [];

                // Ensure it's always an array
                if (is_string($selectedStatuses)) {
                    $selectedStatuses = json_decode($selectedStatuses, true) ?? [];
                } elseif (!is_array($selectedStatuses)) {
                    $selectedStatuses = [];
                }

                // Load latest Opt meta for health metrics and payment fields
                // Search by BOTH the string patient_id AND the numeric acc_inquirys.id
                // to handle old records where patient_id may not be set
                $latestOpt = null;

                if (!empty($lead->patient_id)) {
                    $latestOpt = Opt::where('patient_id', $lead->patient_id)
                        ->where(function ($q) {
                            $q->whereNull('delete_status')
                              ->orWhere('delete_status', '')
                              ->orWhere('delete_status', '0');
                        })
                        ->orderByDesc('id')
                        ->first();
                }

                // Fallback: old data may have Opt saved with the numeric acc_inquirys.id as patient_id
                if (!$latestOpt) {
                    $latestOpt = Opt::where('patient_id', (string) $lead->id)
                        ->where(function ($q) {
                            $q->whereNull('delete_status')
                              ->orWhere('delete_status', '')
                              ->orWhere('delete_status', '0');
                        })
                        ->orderByDesc('id')
                        ->first();
                }

                if ($latestOpt) {
                    $metaRecords = OptMeta::where('opt_id', $latestOpt->id)->get();
                    foreach ($metaRecords as $meta) {
                        $optMeta[$meta->meta_key] = $meta->meta_value;
                    }
                }

                // If health metrics still empty, fill from any Opt record linked by patient name
                // (covers patients imported from old system with mismatched patient_id)
                if (empty($optMeta['diet']) && empty($optMeta['exercise']) && !empty($lead->patient_name)) {
                    $nameOpt = Opt::where('patient_name', $lead->patient_name)
                        ->where(function ($q) {
                            $q->whereNull('delete_status')
                              ->orWhere('delete_status', '')
                              ->orWhere('delete_status', '0');
                        })
                        ->orderByDesc('id')
                        ->first();

                    if ($nameOpt) {
                        $metaRecords = OptMeta::where('opt_id', $nameOpt->id)->get();
                        foreach ($metaRecords as $meta) {
                            // Only fill keys that are missing
                            if (!isset($optMeta[$meta->meta_key])) {
                                $optMeta[$meta->meta_key] = $meta->meta_value;
                            }
                        }
                    }
                }
            }
        }

        $branches = Branch::all(['branch_id', 'branch_name']);

        $doctors = \App\Models\User::where('user_role', 6)
            ->orWhereHas('roles', function ($query) {
                $query->where('name', 'Doctor');
            })
            ->get(['id', 'name']);

        $joinedPrograms = \App\Models\ManageProgram::where('delete_status', 0)
            ->orderBy('program_name', 'asc')
            ->get();

        return view('admin.inquiry.add_inquiry', compact('branches', 'lead', 'selectedStatuses', 'doctors', 'joinedPrograms', 'optMeta'));
    }

    public function getPatientsByBranch(Request $request)
{
    $branchId = $request->branch_id;
    $user = auth()->user();
    $isSuperadmin = $user->hasRole('Superadmin');
 
    if (!$branchId && !$isSuperadmin) {
        return response()->json(['success' => false], 400);
    }
 
    $patients = collect();
 
    $patients = $patients->merge(
        AccInquiry::where('delete_status', '0')
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->get([
                'id',
                'patient_id',
                'branch_id',
                'age',
                DB::raw("CONCAT(patient_f_name,' ',patient_l_name) as patient_name")
            ])
    );
 
    $patients = $patients->merge(
        PatientInquiry::withTrashed()
            ->where(function ($q) {
                $q->where('delete_status', '0')
                  ->orWhere('delete_status', '');
            })
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->get([
                'id',
                'patient_id',
                'branch_id',
                'age',
                'patient_name'
            ])
    );
    
 
 
    // dd($patients);
 
    $patients = $patients->merge(
        LHRInquiry::whereNull('deleted_at')
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->get([
                'id',
                'patient_id',
                'branch_id',
                'age',
                'patient_name'
            ])
    );
 
 
    $patients = $patients->merge(
        HydraInquiry::when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->get([
                'id',
                'patient_id',
                'branch_id',
                'age',
                'patient_name'
            ])
    );
 
    if ($patients->isEmpty()) {
        return response()->json([
            'success' => true,
            'patients' => []
        ]);
    }
 
    return response()->json([
        'success' => true,
        'patients' => $patients->sortBy('patient_name')->values()
    ]);
}



    // public function store(Request $request)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'branch'            => 'required',
    //             'patient_name'      => 'required|string|max:255',
    //             'gender'            => 'nullable|in:Male,Female,Other',
    //             'email'             => 'nullable|email',
    //             'phone_no'          => 'nullable|string|max:20',
    //             'age'               => 'nullable|integer|min:0',
    //             'height'            => 'nullable|numeric|min:0',
    //             'weight'            => 'nullable|numeric|min:0',
    //             'address'           => 'nullable|string',
    //             'refrance'          => 'nullable|string|max:255',
    //             'inquiry_date'      => 'required|date',
    //             'inquiry_time'      => 'required|date_format:H:i',
    //             'inquery_given_by'  => 'nullable|string|max:255',
    //             'payment'           => 'nullable|numeric|min:0',
    //             'inquiry_foc'       => 'nullable|in:Yes,No',
    //             'diagnosis'         => 'nullable|string',
    //             'client_old_new'    => 'nullable|in:New,Old,Regular,VIP,Corporate',
    //             'user_status'       => 'nullable|array',
    //             'user_status.*'     => 'in:Pending,Diet Chart,Joined,Active',
    //             'existing_patient_id' => 'nullable|string',
    //             'branch_id'         => 'nullable|string', // Add this field
    //         ]);

    //         $leadId = $request->lead_id;
    //         $selectedStatuses = $request->user_status ?? [];
    //         $existingPatientId = $request->existing_patient_id;
    //         $branchId = $request->branch_id; // Get branch ID from request

    //         // If no status selected, default to Pending for new records
    //         if (!$leadId && empty($selectedStatuses)) {
    //             $selectedStatuses = ['Pending'];
    //         }

    //         $branch = Branch::where('branch_name', $validated['branch'])
    //             ->where('delete_status', 0)
    //             ->first();

    //         if (!$branch) {
    //             return back()->with('error', 'Selected branch not found!')->withInput();
    //         }

    //         $bmi = null;
    //         if (!empty($validated['height']) && !empty($validated['weight']) && $validated['height'] > 0) {
    //             $heightMeter = $validated['height'] / 100;
    //             $bmi = round($validated['weight'] / ($heightMeter * $heightMeter), 2);
    //         }

    //         $inquiryFoc = $request->has('inquiry_foc') ? 'Yes' : 'No';
    //         $payment = $inquiryFoc === 'Yes' ? 0 : ($validated['payment'] ?? 0);
    //         $clientType = $request->client_old_new ?? 'New';

    //         // Determine primary status for redirection (use first selected or default)
    //         $primaryStatus = !empty($selectedStatuses) ? $selectedStatuses[0] : 'Pending';

    //         // ✅ FIX: Store branch_id as "SVC-0001" format, not numeric ID
    //         $inquiryData = [
    //             'branch'            => $validated['branch'],
    //             'branch_id'         => $branchId ?: $branch->branch_id, // Store branch identifier like "SVC-0001"
    //             'patient_name'      => $validated['patient_name'],
    //             'gender'            => $validated['gender'] ?? null,
    //             'email'             => $validated['email'] ?? null,
    //             'phone_no'          => $validated['phone_no'] ?? null,
    //             'age'               => $validated['age'] ?? null,
    //             'height'            => $validated['height'] ?? null,
    //             'weight'            => $validated['weight'] ?? null,
    //             'bmi'               => $bmi,
    //             'address'           => $validated['address'] ?? null,
    //             'refrance'          => $validated['refrance'] ?? null,
    //             'inquiry_date'      => $validated['inquiry_date'],
    //             'inquiry_time'      => $validated['inquiry_time'],
    //             'inquery_given_by'  => $validated['inquery_given_by'] ?? null,
    //             'payment'           => $payment,
    //             'inquiry_foc'       => $inquiryFoc,
    //             'diagnosis'         => $validated['diagnosis'] ?? null,
    //             'client_old_new'    => $clientType,
    //             'user_status'       => $primaryStatus,
    //             'status_history'    => $selectedStatuses,
    //             'delete_status'     => '0',
    //         ];

    //         DB::beginTransaction();

    //         if ($leadId) {
    //             // UPDATE EXISTING INQUIRY
    //             $inquiry = AccInquiry::find($leadId);
    //             $inquiry->update($inquiryData);

    //             DB::commit();

    //             // Redirection logic
    //             if (in_array('Pending', $selectedStatuses)) {
    //                 return redirect()->route('pending.inquiry')->with('success', 'Inquiry updated successfully!');
    //             } elseif (in_array('Joined', $selectedStatuses)) {
    //                 return redirect()->route('joined.inquiry')->with('success', 'Patient updated successfully!');
    //             } elseif (in_array('Diet Chart', $selectedStatuses) || in_array('Active', $selectedStatuses)) {
    //                 return redirect()->route('diet.chart')->with('success', 'Inquiry updated successfully!');
    //             } else {
    //                 return redirect()->back()->with('success', 'Inquiry updated successfully!');
    //             }
    //         }

    //         // CREATE NEW INQUIRY - Check if we should use existing PatientInquiry ID
    //         if ($existingPatientId) {
    //             // Use the existing patient ID from PatientInquiry table
    //             $patientId = $existingPatientId;

    //             // Check if this patient already exists in AccInquiry
    //             $existingAccInquiry = AccInquiry::where('patient_id', $patientId)->first();
    //             if ($existingAccInquiry) {
    //                 // Update existing record instead of creating new
    //                 $existingAccInquiry->update($inquiryData);
    //                 DB::commit();

    //                 return redirect()
    //                     ->route('diet.chart')
    //                     ->with('success', 'Patient updated successfully! Patient ID: ' . $patientId);
    //             }
    //         } else {
    //             // Generate new patient ID for AccInquiry
    //             $lastInquiry = AccInquiry::orderBy('id', 'desc')->first();
    //             $branchCode = explode('-', $branch->branch_id)[0];

    //             $patientId = $branchCode . '-' . str_pad(
    //                 ($lastInquiry ? $lastInquiry->id + 1 : 1),
    //                 7,
    //                 '0',
    //                 STR_PAD_LEFT
    //             );
    //         }

    //         $inquiryData['patient_id'] = $patientId;
    //         AccInquiry::create($inquiryData);

    //         DB::commit();

    //         // Redirection for new inquiries
    //         if (in_array('Pending', $selectedStatuses)) {
    //             return redirect()
    //                 ->route('pending.inquiry')
    //                 ->with('success', 'Inquiry added successfully! Patient ID: ' . $patientId);
    //         } elseif (in_array('Joined', $selectedStatuses)) {
    //             return redirect()
    //                 ->route('joined.inquiry')
    //                 ->with('success', 'Patient added successfully! Patient ID: ' . $patientId);
    //         } else {
    //             return redirect()
    //                 ->route('diet.chart')
    //                 ->with('success', 'Inquiry added successfully! Patient ID: ' . $patientId);
    //         }
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()
    //             ->with('error', 'Error saving inquiry: ' . $e->getMessage())
    //             ->withInput();
    //     }
    // }


public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'branch' => 'required',
                'patient_f_name' => 'required|string|max:100',
                'patient_m_name' => 'nullable|string|max:100',
                'patient_l_name' => 'required|string|max:100',
                'gender' => 'nullable|in:Male,Female,Other',
                'email' => 'nullable|email',
                'phone_no' => 'nullable|string|max:20',
                'age' => 'nullable|integer|min:0',
                'height' => 'nullable|numeric|min:0',
                'weight' => 'nullable|numeric|min:0',
                'address' => 'nullable|string',
                'refrance' => 'nullable|string|max:255',
                'reference_by' => 'nullable|string|max:255',
                'reference_to' => 'nullable|string|max:255',
                'inquiry_date' => 'required|date',
                'inquiry_time' => 'required|date_format:H:i',
                'inquery_given_by' => 'nullable|string|max:255',
                'total_payment' => 'nullable|numeric|min:0',
                'inquiry_foc' => 'nullable',  // Accepts checkbox value "1" or "Yes"/"No"
                'diagnosis' => 'nullable|string',
                'client_old_new' => 'nullable|in:New,Old,Regular,VIP,Corporate',
                'user_status' => 'nullable|array',
                'user_status.*' => 'in:Pending,Diet Chart,Joined,Active,InBody',
                'existing_patient_id' => 'nullable|string',
            ]);

            $leadId = $request->lead_id;
            $selectedStatuses = $request->user_status ?? [];
            $existingPatientId = $request->existing_patient_id;

            if (! $leadId && empty($selectedStatuses)) {
                $selectedStatuses = ['Pending'];
            }

            // For editing, if branch is readonly in form, it should still pass the branch ID
            $branch = Branch::where('branch_id', $validated['branch'])
                ->where('delete_status', 0)
                ->first();

            if (! $branch) {
                return back()->with('error', 'Selected branch not found!')->withInput();
            }

            $bmi = null;
            if (! empty($validated['height']) && ! empty($validated['weight']) && $validated['height'] > 0) {
                $heightMeter = $validated['height'] / 100;
                $bmi = round($validated['weight'] / ($heightMeter * $heightMeter), 2);
            }

            // FOC: checkbox sends "1", we store as "Yes"/"No"
            $inquiryFoc = ($request->has('inquiry_foc') && !empty($request->input('inquiry_foc'))) ? 'Yes' : 'No';
            $payment = $inquiryFoc === 'Yes' ? 0 : ($request->total_payment ?? 0);
            $clientType = $request->client_old_new ?? 'New';

            $primaryStatus = ! empty($selectedStatuses) ? $selectedStatuses[0] : 'Pending';

            // Build patient name from parts
            $patientName = trim($validated['patient_f_name'].' '.
                           ($validated['patient_m_name'] ? $validated['patient_m_name'].' ' : '').
                           $validated['patient_l_name']);

            // Support both old field name 'refrance' and new 'reference_by'
            $refrance = $validated['reference_by'] ?? $validated['refrance'] ?? '';

            $inquiryData = [
                'branch' => $branch->branch_name,
                'branch_id' => $validated['branch'],
                'patient_f_name' => $validated['patient_f_name'],
                'patient_m_name' => $validated['patient_m_name'] ?? null,
                'patient_l_name' => $validated['patient_l_name'],
                'patient_name' => $patientName,
                'gender' => $validated['gender'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone_no' => $validated['phone_no'] ?? '',
                'age' => $validated['age'] ?? '',
                'height' => $validated['height'] ?? '',
                'weight' => $validated['weight'] ?? '',
                'bmi' => $bmi ?? '',
                'address' => $validated['address'] ?? '',
                'refrance' => $refrance,
                'reference_to' => $request->reference_to ?? '',
                'inquiry_date' => $validated['inquiry_date'],
                'inquiry_time' => $validated['inquiry_time'],
                'inquery_given_by' => $validated['inquery_given_by'] ?? '',
                'payment' => $payment,
                'inquiry_foc' => $inquiryFoc,
                'complain' => $request->complain ?? '',
                'diagnosis' => $validated['diagnosis'] ?? '',
                'client_old_new' => $clientType,
                'user_status' => $primaryStatus,
                'status_history' => $selectedStatuses,
                'is_online_abroad' => $request->is_online_abroad ?? 0,
                'discount_payment' => $request->discount_payment ?? 0,
                'delete_status' => '0',
            ];


            DB::beginTransaction();

            if ($leadId) {
                // UPDATE EXISTING INQUIRY
                $inquiry = AccInquiry::find($leadId);

                if (! $inquiry) {
                    DB::rollBack();
                    return back()->with('error', 'Inquiry not found!')->withInput();
                }

                $inquiry->update($inquiryData);

                // Also save health metrics and payment meta to opt_metas
                $this->saveInquiryMetaFields($request, $inquiry->patient_id, $branch);

                DB::commit();

                // Redirection logic
                if (in_array('Pending', $selectedStatuses)) {
                    return redirect()->route('pending.inquiry')->with('success', 'Inquiry updated successfully!');
                } elseif (in_array('Joined', $selectedStatuses)) {
                    return redirect()->route('joined.inquiry')->with('success', 'Patient updated successfully!');
                } elseif (in_array('Diet Chart', $selectedStatuses) || in_array('Active', $selectedStatuses)) {
                    return redirect()->route('diet.chart')->with('success', 'Inquiry updated successfully!');
                } else {
                    return redirect()->back()->with('success', 'Inquiry updated successfully!');
                }
            }

            // CREATE NEW INQUIRY
            if ($existingPatientId) {
                $patientId = $existingPatientId;

                $existingAccInquiry = AccInquiry::where('patient_id', $patientId)->first();
                if ($existingAccInquiry) {
                    $existingAccInquiry->update($inquiryData);
                    $this->saveInquiryMetaFields($request, $patientId, $branch);
                    DB::commit();

                    return redirect()
                        ->route('diet.chart')
                        ->with('success', 'Patient updated successfully! Patient ID: '.$patientId);
                }
            } else {
                // Generate new patient ID with format SVC-00001, SVC-00002, etc.
                $branchCode = explode('-', $validated['branch'])[0];

                // Get the last patient ID for this branch
                $lastPatient = AccInquiry::where('patient_id', 'like', $branchCode.'-%')
                    ->orderByRaw('CAST(SUBSTRING(patient_id, LOCATE("-", patient_id) + 1) AS UNSIGNED) DESC')
                    ->first();

                if ($lastPatient && $lastPatient->patient_id) {
                    $lastNumber = (int) substr($lastPatient->patient_id, strpos($lastPatient->patient_id, '-') + 1);
                    $nextNumber = $lastNumber + 1;
                } else {
                    $nextNumber = 1;
                }

                // Format: SVC-00001 (5 digits with leading zeros)
                $patientId = $branchCode.'-'.str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            }

            $inquiryData['patient_id'] = $patientId;
            AccInquiry::create($inquiryData);

            // Also save health metrics and payment meta to opt_metas
            $this->saveInquiryMetaFields($request, $patientId, $branch);

            DB::commit();

            // Redirection for new inquiries
            if (in_array('Pending', $selectedStatuses)) {
                return redirect()
                    ->route('pending.inquiry')
                    ->with('success', 'Inquiry added successfully! Patient ID: '.$patientId);
            } elseif (in_array('Joined', $selectedStatuses)) {
                return redirect()
                    ->route('joined.inquiry')
                    ->with('success', 'Patient added successfully! Patient ID: '.$patientId);
            } else {
                return redirect()
                    ->route('diet.chart')
                    ->with('success', 'Inquiry added successfully! Patient ID: '.$patientId);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error saving inquiry: '.$e->getMessage());
            \Log::error('Trace: '.$e->getTraceAsString());

            return back()
                ->with('error', 'Error saving inquiry: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Save health metrics and payment details to opt_metas for the inquiry form.
     * This ensures fields like diet/exercise/sleep/water and given_payment/payment_method
     * are persisted and can be read back when editing.
     */
    private function saveInquiryMetaFields(Request $request, string $patientId, $branch): void
    {
        // Find or create the Opt record for this patient
        $opt = Opt::where('patient_id', $patientId)
            ->where(function ($q) {
                $q->whereNull('delete_status')
                  ->orWhere('delete_status', '')
                  ->orWhere('delete_status', '0');
            })
            ->orderByDesc('id')
            ->first();

        if (! $opt) {
            $opt = Opt::create([
                'patient_id'   => $patientId,
                'patient_name' => $request->patient_f_name . ' ' . ($request->patient_m_name ? $request->patient_m_name . ' ' : '') . $request->patient_l_name,
                'branch_id'    => $branch->branch_id,
                'branch'       => $branch->branch_name,
                'delete_status' => '0',
            ]);
        }

        // Fields to store in opt_metas from add_inquiry form
        $metaFields = [
            // Health Metrics section
            'diet', 'exercise', 'sleep', 'water',
            // Payment details
            'given_payment', 'payment_method', 'due_payment',
        ];

        foreach ($metaFields as $field) {
            if ($request->has($field)) {
                $opt->setMetaValue($field, $request->input($field) ?? '');
            }
        }

        // Set FOC status in opt_metas as well
        $optFoc = ($request->has('inquiry_foc') && !empty($request->input('inquiry_foc'))) ? 'Yes' : 'No';
        $opt->setMetaValue('inquiry_foc', $optFoc);

        // Save joined program selections and construct programs_array
        if ($request->has('joined_program_id') && is_array($request->joined_program_id)) {
            $programIds = [];
            $allPrograms = [];
            foreach ($request->joined_program_id as $index => $pId) {
                if (!empty($pId)) {
                    $programIds[] = $pId;
                    
                    // Fetch program name
                    $progModel = \App\Models\ManageProgram::find($pId);
                    $progName = $progModel ? $progModel->program_name : '';
                    
                    $session = $request->session[$index] ?? '';
                    $months = $request->months[$index] ?? '';
                    
                    // Save individual metadata fields for compatibility
                    $opt->setMetaValue("selected_program_{$index}", $progName);
                    $opt->setMetaValue("session_{$index}", $session);
                    $opt->setMetaValue("months_{$index}", $months);
                    
                    $allPrograms[] = [
                        'program' => $progName,
                        'session' => $session,
                        'months' => $months,
                        'total' => ($index === 0) ? ($request->total_payment ?? '0.00') : '0.00',
                        'payment_method' => $request->payment_method ?? 'Cash',
                        'payment_date' => date('Y-m-d'),
                        'index' => $index,
                        'created_at' => now()->format('Y-m-d H:i:s')
                    ];
                    
                    if ($index === 0) {
                        $opt->setMetaValue('selected_program', $progName);
                        $opt->setMetaValue('session', $session);
                        $opt->setMetaValue('months', $months);
                    }
                }
            }
            
            // Save JSON encoded lists
            $opt->setMetaValue('joined_program_ids', json_encode(array_values($programIds)));
            $opt->setMetaValue('programs_array', json_encode($allPrograms));
        } else {
            $opt->setMetaValue('joined_program_ids', json_encode([]));
            $opt->setMetaValue('programs_array', json_encode([]));
        }
    }


    public function destroy($id)
    {
        $delete = AccInquiry::where('id', $id)->update([
            'delete_status' => '1',
            'delete_by' => auth()->id(),
        ]);

        if ($delete) {
            return redirect()->back()->with('success', 'Inquiry deleted successfully');
        }

        return redirect()->back()->with('error', 'Failed to delete inquiry');
    }


    public function export(Request $request)
    {
        $query = AccInquiry::where('delete_status', '0')
            ->where(function ($q) {
                $q->where('user_status', 'Diet Chart')
                    ->orWhere('user_status', 'Active');
            });

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('patient_id', 'like', '%' . $search . '%')
                    ->orWhere('patient_name', 'like', '%' . $search . '%')
                    ->orWhere('phone_no', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%')
                    ->orWhere('diagnosis', 'like', '%' . $search . '%');
            });
        }

        $inquiries = $query->orderBy('created_at', 'desc')->get();

        if ($inquiries->isEmpty()) {
            return redirect()->route('diet.chart')->with('error', 'No inquiries found to export.');
        }

        $filename = 'diet_chart_inquiries_export_' . date('Y-m-d_H-i') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $file = fopen('php://output', 'w');

        $headers = ['Patient ID', 'Patient Name', 'Phone Number', 'Address', 'Branch', 'Diagnosis', 'Reference By', 'Inquiry By', 'Status'];
        fputcsv($file, $headers);

        foreach ($inquiries as $inquiry) {
            $row = [
                $inquiry->patient_id ?? 'N/A',
                $inquiry->patient_name ?? 'N/A',
                $inquiry->phone_no ?? 'N/A',
                $inquiry->address ?? 'N/A',
                $inquiry->branch ?? 'N/A',
                $inquiry->diagnosis ?? 'N/A',
                $inquiry->refrance ?? 'N/A',
                $inquiry->inquery_given_by ?? 'N/A',
                $inquiry->user_status ?? 'N/A',
            ];
            fputcsv($file, $row);
        }

        fclose($file);
        exit;
    }

    // public function patientProfile($id)
    // {
    //     try {
    //         // Find the patient by ID in AccInquiry
    //         $patient = AccInquiry::where('id', $id)
    //             ->where('delete_status', '0')
    //             ->firstOrFail();

    //         // Initialize variables with default values
    //         $optData = null;
    //         $optMeta = [];
    //         $programDetails = [];

    //         // Find the corresponding diet chart data from Opt table
    //         $optData = Opt::where('patient_id', $id)
    //             ->where('delete_status', '0')
    //             ->first();

    //         // Get all meta data for this Opt record if exists
    //         if ($optData) {
    //             // Fetch all meta data for this Opt record
    //             $metaRecords = OptMeta::where('opt_id', $optData->id)->get();
    //             foreach ($metaRecords as $meta) {
    //                 $optMeta[$meta->meta_key] = $meta->meta_value;
    //             }

    //             // Fetch payment program details from meta data
    //             if (isset($optMeta['selected_program']) && $optMeta['selected_program']) {
    //                 $programDetails[] = [
    //                     'program_name' => $optMeta['selected_program'] ?? '',
    //                     'session' => $optMeta['session'] ?? '',
    //                     'months' => $optMeta['months'] ?? '',
    //                     'payment_date' => $optMeta['pod_bd_date'] ?? '', // Using diet chart date as payment date
    //                     'payment_method' => $optMeta['payment_method'] ?? '',
    //                     'total' => $optMeta['total_payment'] ?? '0.00',
    //                     'discount' => $optMeta['discount_payment'] ?? '0.00',
    //                     'given' => $optMeta['given_payment'] ?? '0.00',
    //                     'due' => $optMeta['due_payment'] ?? '0.00',
    //                 ];
    //             }
    //         }

    //         // Return the patient profile view with all data
    //         return view('admin.inquiry.patient-profile', compact(
    //             'patient',
    //             'optData',
    //             'optMeta',
    //             'programDetails'
    //         ));

    //     } catch (\Exception $e) {
    //         return redirect()->route('diet.chart')
    //             ->with('error', 'Patient not found or has been deleted.');
    //     }
    // }
    
    public function dietJoinPatient($id)
    {
        try {
            // Find the patient by ID
            $patient = AccInquiry::where('id', $id)
                ->where('delete_status', '0')
                ->firstOrFail();

            // Fetch available programs for the program selection dropdowns
            $available_programs = \App\Models\ManageProgram::where('delete_status', 0)
                ->orderBy('program_name', 'asc')
                ->get();

            // Fetch the latest Opt (Diet H/O) record for this patient
            $latestOpt = Opt::where('patient_id', $patient->patient_id)
                ->where(function ($q) {
                    $q->whereNull('delete_status')
                      ->orWhere('delete_status', '')
                      ->orWhere('delete_status', '0');
                })
                ->orderByDesc('id')
                ->first();

            // Load meta key/value pairs for the latest Opt record
            $latestMeta = [];
            if ($latestOpt) {
                $metaRecords = OptMeta::where('opt_id', $latestOpt->id)->get();
                foreach ($metaRecords as $meta) {
                    $latestMeta[$meta->meta_key] = $meta->meta_value;
                }
            }

            // Fetch diet history (all non-deleted Opt records for this patient)
            // Eager-load meta to avoid N+1 queries in the lab history loop below
            $dietHistory = Opt::where('patient_id', $patient->patient_id)
                ->where(function ($q) {
                    $q->whereNull('delete_status')
                      ->orWhere('delete_status', '')
                      ->orWhere('delete_status', '0');
                })
                ->with('meta')
                ->orderByDesc('id')
                ->get();

            // Fetch lab history by parsing metadata of all Opt records
            $labHistory = [];
            foreach ($dietHistory as $historyOpt) {
                // Use the already-loaded meta relationship to avoid N+1
                $metaData = $historyOpt->meta->pluck('meta_value', 'meta_key')->toArray();
                
                $hasLabData = false;
                $labData = [];
                
                $labKeys = [
                    's_cholesterol', 's_triglycerides', 'hdl', 'ldl', 'vldl', 'non_hdl_c', 'chol_hdl_ratio',
                    's_insulin', 'sgpt', 's_creatinine', 's_uric_acid', 'ra_test', 'hb', 'tc', 'pc', 'mp_lab',
                    'esr', 'crp', 'hb1ac', 'fbs', 'pp2bs', 's_widal', 'ns1ag', 'dengue_igm', 's_b12', 's_d3',
                    's_t3', 's_t4', 's_tsh', 'urine_lab', 'specific_test', 'usg_abdomen', 'chest_xray', 'mri_ct_scan'
                ];
                
                foreach ($labKeys as $key) {
                    $val = $metaData[$key] ?? '';
                    $labData[$key] = $val;
                    if ($val !== '') {
                        $hasLabData = true;
                    }
                }
                
                if ($hasLabData) {
                    $dateVal = $metaData['pod_bd_date'] ?? null;
                    $formattedDate = 'N/A';
                    if ($dateVal) {
                        try {
                            $formattedDate = \Carbon\Carbon::parse($dateVal)->format('d M, Y');
                        } catch (\Exception $ex) {
                            $formattedDate = $dateVal;
                        }
                    } else if ($historyOpt->created_at) {
                        $formattedDate = $historyOpt->created_at->format('d M, Y');
                    }
                    
                    $labHistory[] = [
                        'date' => $formattedDate,
                        'data' => $labData
                    ];
                }
            }

            // Return the diet chart form view with all the patient data and history
            return view('admin.inquiry.diet_join_patient', compact(
                'patient',
                'available_programs',
                'latestOpt',
                'latestMeta',
                'dietHistory',
                'labHistory'
            ));
        } catch (\Exception $e) {
            Log::error('Error in dietJoinPatient: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('diet.chart')
                ->with('error', 'Patient not found or has been deleted.');
        }
    }

    public function editDietJoinPatient($id)
    {
        try {
            // Find the Opt record by ID
            $optData = Opt::where('id', $id)
                ->where(function ($q) {
                    $q->whereNull('delete_status')
                      ->orWhere('delete_status', '')
                      ->orWhere('delete_status', '0');
                })
                ->firstOrFail();

            // Find the patient in AccInquiry
            $patient = AccInquiry::where('patient_id', $optData->patient_id)
                ->where('delete_status', '0')
                ->firstOrFail();

            // Fetch available programs
            $available_programs = \App\Models\ManageProgram::where('delete_status', 0)
                ->orderBy('program_name', 'asc')
                ->get();

            // Get meta key-value pairs
            $optMeta = [];
            $metaRecords = OptMeta::where('opt_id', $optData->id)->get();
            foreach ($metaRecords as $meta) {
                $optMeta[$meta->meta_key] = $meta->meta_value;
            }

            // Fetch full diet history (all non-deleted Opt records for this patient)
            $dietHistory = Opt::where('patient_id', $optData->patient_id)
                ->where(function ($q) {
                    $q->whereNull('delete_status')
                      ->orWhere('delete_status', '')
                      ->orWhere('delete_status', '0');
                })
                ->with('meta')
                ->orderByDesc('id')
                ->get();

            // Fetch measurement history (monthly assessments for this patient)
            $measurements = \App\Models\MonthlyAssessment::where('patient_inquiry_id', $patient->id)
                ->active()
                ->orderByDesc('assessment_date')
                ->get();

            return view('admin.inquiry.edit_diet_join_patient', compact(
                'patient',
                'optData',
                'optMeta',
                'available_programs',
                'dietHistory',
                'measurements'
            ));
        } catch (\Exception $e) {
            Log::error('Error in editDietJoinPatient: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('diet.chart')
                ->with('error', 'Diet chart not found or has been deleted.');
        }
    }

    public function updateDietChart(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $opt = Opt::findOrFail($id);

            // Update basic opt data
            $opt->update([
                'blood_group' => $request->blood_group,
            ]);

            // Save all basic fields
            $basicFields = [
                'pod_bd_date',
                'pod_bmr',
                'pod_ovr_weight',
                'pod_undr_weight',
                'pod_trg_weight',
                'pod_bdy_lmp',
                'pod_calories',
                'pod_fh',
                'pod_pah',
                'pod_medication',
                'pod_s_sholesterol',
                'pod_s_triglyceride',
                'pod_hdl',
                'pod_ldl',
                'pod_vldl',
                'pod_tsh',
                'pod_t3',
                'pod_t4',
                'pod_b12',
                'pod_vit_d3',
                'pod_hb',
                'pod_vit_bp',
                'pod_hbac',
                'pod_vld_date',
                'pod_sugar_rbs',
                'pod_sugar_fbs',
                'pod_sugar_pp2bs',
                'time',
                'activity',
                'early_morning',
                'bed_time',
                'occupation',
                'breakfast',
                'lunch',
                'dinner',
                'brunch',
                'snacks',
                'water_intake',
                'water_unit',
                'fasting_day',
                'habit',
                'food_choices',
                'milk',
                'salt',
                'food_allergy',
                'walking_time',
                'sleeping_time',
                'oil',
                'anything_else',
                'alcohol',
                'position',
                'total_payment',
                'discount_payment',
                'given_payment',
                'due_payment',
                'payment_method',
                'due_date',
                'lead_body_weight',
                'birth_date',
                // Additional fields from form
                'under_weight',
                'over_weight',
                'target_weight',
                'pa_h',
                'f_h',
                'waking_time',
                'physical_activity',
                'fast_food',
                // Health Metrics section
                'diet',
                'exercise',
                'sleep',
                'water',
                // Laboratory Investigation section
                's_insulin',
                'sgpt',
                's_creatinine',
                's_uric_acid',
                'ra_test',
                'usg_abdomen',
                'chest_xray',
                'mri_ct_scan',
                // Height/weight fields
                'pod_data',
                'pod_bdy_weight',
            ];

            foreach ($basicFields as $field) {
                if ($request->has($field)) {
                    $opt->setMetaValue($field, $request->input($field) ?? '');
                }
            }

            if ($request->has('selected_program') && is_array($request->selected_program)) {
                $allPrograms = [];

                foreach ($request->selected_program as $index => $program) {
                    if (!empty($program)) {
                        $session = $request->session[$index] ?? '';
                        $months = $request->months[$index] ?? '';

                        $opt->setMetaValue("selected_program_{$index}", $program);
                        $opt->setMetaValue("session_{$index}", $session);
                        $opt->setMetaValue("months_{$index}", $months);

                        $allPrograms[] = [
                            'program' => $program,
                            'session' => $session,
                            'months' => $months,
                            'total' => ($index === 0) ? ($request->total_payment ?? '0.00') : '0.00',
                            'payment_method' => $request->payment_method ?? '',
                            'payment_date' => $request->pod_bd_date ?? date('Y-m-d'),
                            'index' => $index,
                            'created_at' => now()->format('Y-m-d H:i:s')
                        ];

                        if ($index == 0) {
                            $opt->setMetaValue('selected_program', $program);
                            $opt->setMetaValue('session', $session);
                            $opt->setMetaValue('months', $months);
                        }
                    }
                }

                if (!empty($allPrograms)) {
                    $opt->setMetaValue('programs_array', json_encode($allPrograms));
                }
            }

            // Handle file uploads
            $this->handleFileUploads($request, $opt);

            // Sync invoice and transactions
            $this->syncDietInvoiceAndTransactions($opt, $request);

            DB::commit();

            return redirect()->route('diet.chart')
                ->with('success', 'Diet chart updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in updateDietChart: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return back()->with('error', 'Error updating diet chart: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updateDietHistoryMeta(Request $request)
    {
        try {
            $opt = Opt::findOrFail($request->history_id);

            if ($request->has('blood_group')) {
                $opt->blood_group = $request->blood_group;
                $opt->save();
            }

            $exclude = ['history_id', '_token', 'blood_group'];
            foreach ($request->except($exclude) as $key => $value) {
                $opt->setMetaValue($key, $value ?? '');
            }

            return response()->json([
                'success' => true,
                'message' => 'Diet history updated successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in updateDietHistoryMeta: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update diet history: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteDietHistory($id)
    {
        try {
            $opt = Opt::findOrFail($id);
            $opt->delete_status = '1';
            $opt->delete_by = auth()->check() ? auth()->user()->name : 'System';
            $opt->save();

            return response()->json([
                'success' => true,
                'message' => 'Diet history deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in deleteDietHistory: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete diet history.'
            ], 500);
        }
    }

    public function saveDietChart(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('saveDietChart received data', $request->all());
        DB::beginTransaction();

        try {
            $optData = [
                'patient_id' => $request->patient_id,
                'patient_name' => $request->patient_name,
                'branch_id' => $request->branch_id,
                'branch' => $request->branch,
                'blood_group' => $request->blood_group,
                'delete_status' => '0',
            ];

            // Check if an existing Opt record exists for this patient — UPDATE instead of creating new
            $latestOptId = $request->input('latest_opt_id');
            $opt = null;

            if ($latestOptId) {
                $opt = Opt::where('id', $latestOptId)
                    ->where('patient_id', $request->patient_id)
                    ->where(function ($q) {
                        $q->whereNull('delete_status')
                          ->orWhere('delete_status', '')
                          ->orWhere('delete_status', '0');
                    })
                    ->first();
            }

            if ($opt) {
                // UPDATE existing Opt record
                $opt->update($optData);
            } else {
                // CREATE new Opt record only if no existing one found
                $opt = Opt::create($optData);
            }

            // Save all basic fields — includes ALL form fields from diet_join_patient
            $basicFields = [
                'pod_bd_date',
                'pod_bmr',
                'pod_bmr_value',
                'pod_ovr_weight',
                'pod_undr_weight',
                'pod_trg_weight',
                'pod_bdy_lmp',
                'pod_calories',
                'pod_fh',
                'pod_pah',
                'pod_medication',
                'pod_s_sholesterol',
                'pod_s_triglyceride',
                'pod_hdl',
                'pod_ldl',
                'pod_vldl',
                'pod_tsh',
                'pod_t3',
                'pod_t4',
                'pod_b12',
                'pod_vit_d3',
                'pod_hb',
                'pod_vit_bp',
                'pod_hbac',
                'pod_vld_date',
                'pod_sugar_rbs',
                'pod_sugar_fbs',
                'pod_sugar_pp2bs',
                'time',
                'activity',
                'early_morning',
                'bed_time',
                'occupation',
                'breakfast',
                'lunch',
                'dinner',
                'brunch',
                'snacks',
                'water_intake',
                'water_unit',
                'fasting_day',
                'habit',
                'food_choices',
                'milk',
                'salt',
                'food_allergy',
                'walking_time',
                'sleeping_time',
                'oil',
                'anything_else',
                'alcohol',
                'position',
                'total_payment',
                'discount_payment',
                'given_payment',
                'due_payment',
                'payment_method',
                'due_date',
                'lead_body_weight',
                'birth_date',
                'validity_date',
                // Additional fields from form
                'under_weight',
                'over_weight',
                'target_weight',
                'pa_h',
                'pod_fh',
                'waking_time',
                'physical_activity',
                'fast_food',
                // Patient clinical fields
                'bg_rh',
                'diagnosis',
                // Health Metrics section
                'diet',
                'exercise',
                'sleep',
                'water',
                // Laboratory Investigation section
                's_insulin',
                'sgpt',
                's_creatinine',
                's_uric_acid',
                'ra_test',
                'hb',
                'tc',
                'pc',
                'mp_lab',
                'esr',
                'crp',
                'hb1ac',
                'fbs',
                'pp2bs',
                's_widal',
                'ns1ag',
                'dengue_igm',
                's_b12',
                's_d3',
                's_t3',
                's_t4',
                's_tsh',
                'urine_lab',
                'specific_test',
                'usg_abdomen',
                'chest_xray',
                'mri_ct_scan',
                // Lipid Profile section
                's_cholesterol',
                's_triglycerides',
                'hdl',
                'ldl',
                'vldl',
                'non_hdl_c',
                'chol_hdl_ratio',
                // Height/weight fields from diet_join_patient form
                'pod_data',
                'pod_bdy_weight',
            ];

            foreach ($basicFields as $field) {
                if ($request->has($field)) {
                    $opt->setMetaValue($field, $request->input($field) ?? '');
                }
            }

            if ($request->has('selected_program') && is_array($request->selected_program)) {
                $allPrograms = [];

                foreach ($request->selected_program as $index => $program) {
                    if (!empty($program)) {
                        $session = $request->session[$index] ?? '';
                        $months = $request->months[$index] ?? '';

                        $opt->setMetaValue("selected_program_{$index}", $program);
                        $opt->setMetaValue("session_{$index}", $session); 
                        $opt->setMetaValue("months_{$index}", $months);

                        // Add to programs array
                        $allPrograms[] = [
                            'program' => $program,
                            'session' => $session,
                            'months' => $months,
                            'total' => ($index === 0) ? ($request->total_payment ?? '0.00') : '0.00',
                            'payment_method' => $request->payment_method ?? '',
                            'payment_date' => $request->pod_bd_date ?? date('Y-m-d'),
                            'index' => $index,
                            'created_at' => now()->format('Y-m-d H:i:s')
                        ];

                        if ($index == 0) {
                            $opt->setMetaValue('selected_program', $program);
                            $opt->setMetaValue('session', $session);
                            $opt->setMetaValue('months', $months);
                        }
                    }
                }

                // Save the complete programs array as JSON
                if (!empty($allPrograms)) {
                    $opt->setMetaValue('programs_array', json_encode($allPrograms));
                }
            }

            // Handle file uploads
            $this->handleFileUploads($request, $opt);

            // Sync invoice and transactions
            $this->syncDietInvoiceAndTransactions($opt, $request);

            DB::commit();


            return redirect()->route('diet.chart')
                ->with('success', 'Diet chart saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    private function handleFileUploads($request, $opt)
    {
        $beforeFolder = public_path('before');
        $afterFolder = public_path('after');

        if (!file_exists($beforeFolder)) mkdir($beforeFolder, 0755, true);
        if (!file_exists($afterFolder)) mkdir($afterFolder, 0755, true);



        // Additional pictures
        for ($i = 1; $i <= 5; $i++) {
            // Before pictures
            $beforeKey = 'before_picture_' . $i;
            if ($request->hasFile($beforeKey)) {
                $file = $request->file($beforeKey);
                $fileName = 'before_' . $i . '_' . time() . '_' . $opt->id . '.' . $file->getClientOriginalExtension();
                $file->move($beforeFolder, $fileName);
                $opt->setMetaValue($beforeKey, $fileName);
            }

            // After pictures
            $afterKey = 'after_picture_' . $i;
            if ($request->hasFile($afterKey)) {
                $file = $request->file($afterKey);
                $fileName = 'after_' . $i . '_' . time() . '_' . $opt->id . '.' . $file->getClientOriginalExtension();
                $file->move($afterFolder, $fileName);
                $opt->setMetaValue($afterKey, $fileName);
            }
        }
    }
public function patientProfile($id)
{
    // dd('test');
    try {
        // Find the patient by ID in AccInquiry
        $patient = AccInquiry::where('id', $id)
            ->where('delete_status', '0')
            ->firstOrFail();

        $optData = null;
        $optMeta = [];
        $programDetails = [];
        $monthlyAssessments = [];
        $progressReports = [];
        $beforeImages = [];
        $afterImages = [];

        // IMPORTANT: paginator default (will be overwritten below)
        $dietPlans = DietPlan::whereRaw('1 = 0')->paginate(5);

        // $optData = OptMeta::where('id', $id)->first();
        $optData = Opt::where('patient_id', $patient->patient_id)
            ->where(function ($q) {
                $q->whereNull('delete_status')
                  ->orWhere('delete_status', '')
                  ->orWhere('delete_status', '0');
            })
            ->orderByDesc('id')
            ->first();


        if ($optData) {
   $optMetaRecords = $optData->meta()->get();
    foreach ($optMetaRecords as $meta) {
        $optMeta[$meta->meta_key] = $meta->meta_value;
    }

    $programDetails = $this->getAllProgramDetails($optData->id, $optMeta);
    $beforeImages = $this->getAllImages($optData->id, 'before');
    $afterImages = $this->getAllImages($optData->id, 'after');
        }

        $patientId = $patient->patient_id ?? null;

        // Monthly assessments are keyed by the numeric acc_inquirys.id
        $monthlyAssessments = MonthlyAssessment::where('patient_inquiry_id', $id)
            ->active()
            ->get();

        // Progress reports may be saved with either the numeric id OR the ST-XXXXX code
        // so we search both to cover all cases
        $progressReportQuery = Progress::where('delete_status', '0')
            ->where(function ($q) use ($id, $patientId) {
                $q->where('patient_id', (string) $id);
                if ($patientId) {
                    $q->orWhere('patient_id', $patientId);
                }
            })
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc');

        $progressReports = $progressReportQuery->paginate(10)->withQueryString();

        // Build all possible patient_id values to search across
        $searchIds = array_filter(array_unique([
            (string) $id,           // numeric AccInquiry id
            (string) $patientId,    // e.g. "ST-02081"
        ]));

        // Also collect patient_id values from other tables for this patient
        // (PatientInquiry, LHRInquiry, HydraInquiry may have same patient_name)
        $extraIds = [];
        if ($patient->patient_name) {
            // Check PatientInquiry
            $piIds = \DB::table('patient_inquiry')
                ->where('patient_name', $patient->patient_name)
                ->pluck('patient_id')
                ->toArray();
            $extraIds = array_merge($extraIds, $piIds);

            // Check lhr_inquiries
            $lhrIds = \DB::table('lhr_inquiries')
                ->where('patient_name', $patient->patient_name)
                ->pluck('patient_id')
                ->toArray();
            $extraIds = array_merge($extraIds, $lhrIds);

            // Check hydra_inquiries
            $hydraIds = \DB::table('hydra_inquiries')
                ->where('patient_name', $patient->patient_name)
                ->pluck('patient_id')
                ->toArray();
            $extraIds = array_merge($extraIds, $hydraIds);
        }

        // Also check acc_inquirys itself — the diet plan may have been saved
        // with the acc_inquirys.patient_id (ST-XXXXX) OR the numeric id.
        // Pull all patient_id values linked to this acc record's name/phone.
        $accIds = \DB::table('acc_inquirys')
            ->where(function ($q) use ($patient) {
                if ($patient->patient_name) {
                    $q->whereRaw(
                        "TRIM(CONCAT(COALESCE(patient_f_name,''), ' ', COALESCE(patient_m_name,''), ' ', COALESCE(patient_l_name,''))) = ?",
                        [trim($patient->patient_name)]
                    );
                }
                if ($patient->phone_no) {
                    $q->orWhere('phone_no', $patient->phone_no);
                }
            })
            ->pluck('patient_id')
            ->toArray();
        $extraIds = array_merge($extraIds, $accIds);

        $allSearchIds = array_filter(array_unique(array_merge($searchIds, $extraIds)));

        $dietPlans = DietPlan::where(function ($query) use ($allSearchIds, $patient) {
                $query->whereIn('patient_id', $allSearchIds);
                // Also match by patient_name as last resort
                if ($patient->patient_name) {
                    $query->orWhere('patient_name', $patient->patient_name);
                }
            })
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(5)
            ->withQueryString();

        \Log::info('Patient Profile - Diet Plans:', [
            'patient_inquiry_id' => $id,
            'patient_id_from_table' => $patientId,
            'patient_name' => $patient->patient_name,
            'phone_no' => $patient->phone_no ?? null,
            'all_search_ids' => array_values($allSearchIds),
            'found_diet_plans_count' => $dietPlans->total(),
        ]);
// dd($beforeImages, $afterImages);

        // Build dietPlansWithNutrition: aggregate nutrition data for each diet plan
        $dietPlansWithNutrition = [];
        foreach ($dietPlans as $plan) {
            $menus = $plan->time_search_menus;
            if (!is_array($menus)) {
                try {
                    $menus = json_decode($menus, true) ?: [];
                } catch (\Exception $e) {
                    $menus = [];
                }
            }

            // Collect all recipe names from the plan
            $recipeNames = [];
            foreach ($menus as $menu) {
                $items = [];
                if (!empty($menu['recipes']) && is_array($menu['recipes'])) {
                    $items = $menu['recipes'];
                } else {
                    $raw = $menu['selected_recipes'] ?? $menu['search_menu'] ?? '';
                    if (!empty($raw)) {
                        if (is_string($raw) && (str_starts_with(trim($raw), '[') || str_starts_with(trim($raw), '{'))) {
                            $items = json_decode($raw, true) ?: [];
                        } else {
                            foreach (array_filter(array_map('trim', explode(',', $raw))) as $name) {
                                $items[] = ['name' => $name, 'qty' => ''];
                            }
                        }
                    }
                }
                foreach ($items as $item) {
                    $name = is_array($item) ? ($item['name'] ?? '') : $item;
                    if (!empty($name)) {
                        $qty = is_array($item) ? (float)($item['qty'] ?? 1) : 1;
                        $recipeNames[] = ['name' => $name, 'qty' => max(1, $qty)];
                    }
                }
            }

            // Fetch nutrition records for all recipe names at once
            $names = array_column($recipeNames, 'name');
            $nutritionRecords = \App\Models\Nutrition::whereIn('nutrition_name', $names)->get()->keyBy('nutrition_name');

            $totals = [
                'protein'          => 0,
                'total_folates'    => 0,
                'carbohydrate'     => 0,
                'calcium'          => 0,
                'insoluable_fiber' => 0,
            ];

            $menuItems = [];
            foreach ($recipeNames as $entry) {
                $rec = $nutritionRecords->get($entry['name']);
                if ($rec) {
                    $qty = $entry['qty'];
                    $menuItems[] = [
                        'name'             => $entry['name'],
                        'quantity'         => $qty,
                        'protein'          => (float)$rec->protein * $qty,
                        'total_folates'    => (float)$rec->total_folates * $qty,
                        'carbohydrate'     => (float)$rec->carbohydrate * $qty,
                        'calcium'          => (float)$rec->calcium * $qty,
                        'insoluable_fiber' => (float)$rec->insoluable_fiber * $qty,
                    ];
                    $totals['protein']          += (float)$rec->protein * $qty;
                    $totals['total_folates']    += (float)$rec->total_folates * $qty;
                    $totals['carbohydrate']     += (float)$rec->carbohydrate * $qty;
                    $totals['calcium']          += (float)$rec->calcium * $qty;
                    $totals['insoluable_fiber'] += (float)$rec->insoluable_fiber * $qty;
                }
            }

            $dietPlansWithNutrition[] = [
                'diet_name'       => $plan->diet_name,
                'date'            => $plan->date,
                'total_nutrition' => $totals,
                'menu_items'      => $menuItems,
            ];
        }

        return view('admin.inquiry.patient-profile', compact(
            'patient',
            'optData',
            'optMeta',
            'programDetails',
            'monthlyAssessments',
            'progressReports',
            'beforeImages',
            'afterImages',
            'dietPlans',
            'dietPlansWithNutrition'
        ));
    } catch (\Exception $e) {
        \Log::error('Patient profile error: ' . $e->getMessage());
        return redirect()->route('diet.chart')
            ->with('error', 'Patient not found or has been deleted.');
    }
}
private function getAllImages($optId, $type = 'before')
{
    \Log::info('getAllImages() START', [
        'opt_id' => $optId,
        'type' => $type
    ]);

    $images = [];

    // 🔍 LOG ALL META FOR THIS OPT
    $allMeta = OptMeta::where('opt_id', $optId)->get(['meta_key', 'meta_value']);
    \Log::info('ALL META RECORDS', $allMeta->toArray());
// dd($allMeta);
    // 🔹 PROFILE IMAGE (before_profile_photo / after_profile_photo)
    $profileKey = $type === 'before' ? 'before_profile_photo' : 'after_profile_photo';

    $profileImage = OptMeta::where('opt_id', $optId)
        ->where('meta_key', $profileKey)
        ->first();

    \Log::info('PROFILE IMAGE META', [
        'key' => $profileKey,
        'record' => $profileImage
    ]);

    if ($profileImage) {
        $folder = $type === 'before' ? 'before' : 'after';
        $fullPath = public_path($folder . '/' . $profileImage->meta_value);

        \Log::info('PROFILE IMAGE FILE CHECK', [
            'path' => $fullPath,
            'exists' => file_exists($fullPath)
        ]);

        if (file_exists($fullPath)) {
            $images[] = [
                'path' => asset($folder . '/' . $profileImage->meta_value),
                'weight' => null,
                'height' => null,
                'date' => null,
                'notes' => null,
                'filename' => $profileImage->meta_value,
                'index' => 0
            ];
        }
    }

    // 🔁 MULTI IMAGES LOOP
    for ($i = 1; $i <= 20; $i++) {

        $imageKey  = "{$type}_picture_{$i}";
        $weightKey = "{$type}_weight_{$i}";
        $heightKey = "{$type}_height_{$i}";
        $dateKey   = "{$type}_date_{$i}";
        $notesKey  = "{$type}_notes_{$i}";

        $imageMeta = OptMeta::where('opt_id', $optId)
            ->where('meta_key', $imageKey)
            ->first();

        \Log::info("CHECK IMAGE META {$imageKey}", [
            'exists' => (bool) $imageMeta,
            'value' => $imageMeta?->meta_value
        ]);

        if (!$imageMeta || empty($imageMeta->meta_value)) {
            continue;
        }

        $folder = $type === 'before' ? 'before' : 'after';
        $fullPath = public_path($folder . '/' . $imageMeta->meta_value);

        \Log::info("FILE CHECK {$imageKey}", [
            'path' => $fullPath,
            'exists' => file_exists($fullPath)
        ]);

        if (!file_exists($fullPath)) {
            continue;
        }

        $images[] = [
            'path' => asset($folder . '/' . $imageMeta->meta_value),
            'weight' => OptMeta::where('opt_id', $optId)->where('meta_key', $weightKey)->value('meta_value'),
            'height' => OptMeta::where('opt_id', $optId)->where('meta_key', $heightKey)->value('meta_value'),
            'date' => OptMeta::where('opt_id', $optId)->where('meta_key', $dateKey)->value('meta_value'),
            'notes' => OptMeta::where('opt_id', $optId)->where('meta_key', $notesKey)->value('meta_value'),
            'filename' => $imageMeta->meta_value,
            'index' => $i
        ];
    }

    // ✅ FINAL RESULT LOG
    \Log::info('FINAL IMAGES RETURNED', [
        'count' => count($images),
        'images' => $images
    ]);

    return $images;
}


    // public function patientProfile($id)
    // {
    //     try {
    //         // Find the patient by ID in AccInquiry
    //         $patient = AccInquiry::where('id', $id)
    //             ->where('delete_status', '0')
    //             ->firstOrFail();
          
    //         $optData = null;
    //         $optMeta = [];
    //         $programDetails = [];
    //         $monthlyAssessments = [];
    //         $progressReports = [];
    //         $beforeImages = [];
    //         $afterImages = [];
    //         $dietPlans = collect(); // Initialize as empty collection

    //         $optData = Opt::where('id', $id)->first();

    //         if ($optData) {
    //             $metaRecords = OptMeta::where('opt_id', $optData->id)->get();
    //             foreach ($metaRecords as $meta) {
    //                 $optMeta[$meta->meta_key] = $meta->meta_value;
    //             }

    //             $programDetails = $this->getAllProgramDetails($optData->id, $optMeta);

    //             $beforeImages = $this->getAllImages($optData->id, 'before');

    //             $afterImages = $this->getAllImages($optData->id, 'after');
    //         }

    //         $patientId = $patient->patient_id ?? null;

    //         if ($patientId) {
    //             $monthlyAssessments = MonthlyAssessment::where('patient_inquiry_id', $id)
    //                 ->active()
    //                 ->get();
    //         }

    //         if ($patientId) {
    //             // Get progress reports
    //             $progressReports = Progress::where('patient_id', $patientId)
    //                 ->where('delete_status', '0')
    //                 ->orderBy('date', 'desc')
    //                 ->orderBy('time', 'desc')
    //                 ->get();
    //         }
    //         // dd($patientId);
    //         //    dd($patientId);
    //         $dietPlans = DietPlan::where('patient_id', $id)
    //             ->orderBy('date', 'desc')
    //             ->orderBy('created_at', 'desc')
    //             ->get();
    //         // Method 2: If no results, try searching by patient_id (from patient_inquiry table)
    //         if ($dietPlans->isEmpty() && $patientId) {
    //             $dietPlans = DietPlan::where('patient_id', $patientId)
    //                 ->orderBy('date', 'desc')
    //                 ->orderBy('created_at', 'desc')
    //                 ->get();
    //         }

    //         // Method 3: If still no results, try searching by patient name
    //         if ($dietPlans->isEmpty() && $patient->patient_name) {
    //             $dietPlans = DietPlan::where('patient_name', $patient->patient_name)
    //                 ->orderBy('date', 'desc')
    //                 ->orderBy('created_at', 'desc')
    //                 ->get();
    //         }

    //         // Method 4: As a last resort, check if patient_id matches any string in database
    //         if ($dietPlans->isEmpty()) {
    //             $dietPlans = DietPlan::where(function ($query) use ($id, $patientId) {
    //                 $query->where('patient_id', $id)
    //                     ->orWhere('patient_id', $patientId)
    //                     ->orWhere('patient_id', 'like', '%' . $id . '%');
    //             })
    //                 ->orderBy('date', 'desc')
    //                 ->orderBy('created_at', 'desc')
    //                 ->get();
    //         }

    //         // Debug logging to check what we found
    //         \Log::info('Patient Profile - Diet Plans:', [
    //             'patient_inquiry_id' => $id,
    //             'patient_id_from_table' => $patientId,
    //             'patient_name' => $patient->patient_name,
    //             'found_diet_plans_count' => $dietPlans->count(),
    //             'diet_plans' => $dietPlans->map(function ($plan) {
    //                 return [
    //                     'id' => $plan->id,
    //                     'patient_id' => $plan->patient_id,
    //                     'patient_name' => $plan->patient_name,
    //                     'diet_name' => $plan->diet_name,
    //                     'date' => $plan->date
    //                 ];
    //             })->toArray()
    //         ]);

    //         // Return the patient profile view with all data
    //         return view('admin.inquiry.patient-profile', compact(
    //             'patient',
    //             'optData',
    //             'optMeta',
    //             'programDetails',
    //             'monthlyAssessments',
    //             'progressReports',
    //             'beforeImages',
    //             'afterImages',
    //             'dietPlans'
    //         ));
    //     } catch (\Exception $e) {
    //         \Log::error('Patient profile error: ' . $e->getMessage());
    //         return redirect()->route('diet.chart')
    //             ->with('error', 'Patient not found or has been deleted.');
    //     }
    // }
    // New method to get all images with dimensions
    // private function getAllImages($optId, $type = 'before')
    // {
    //     $images = [];
    // $profileImage = OptMeta::where('opt_id', $optId)
    //     ->where('meta_key', 'before_profile_photo')
    //     ->first();

    // if ($profileImage && file_exists(public_path('before/' . $profileImage->meta_value))) {
    //     $images[] = asset('before/' . $profileImage->meta_value);
    // }

    //     for ($i = 1; $i <= 20; $i++) {
    //         $imageKey = $type . '_picture_' . $i;
    //         $weightKey = $type . '_weight_' . $i;
    //         $heightKey = $type . '_height_' . $i;
    //         $dateKey = $type . '_date_' . $i;
    //         $notesKey = $type . '_notes_' . $i;

    //         // Get image filename
    //         $imageMeta = OptMeta::where('opt_id', $optId)
    //             ->where('meta_key', $imageKey)
    //             ->first();
    //             // dd($imageMeta);

    //         if ($imageMeta && !empty($imageMeta->meta_value)) {
    //             $imagePath = null;
    //             $folder = $type == 'before' ? 'before' : 'after';

    //             // Check if file exists
    //             if (file_exists(public_path($folder . '/' . $imageMeta->meta_value))) {
    //                 $imagePath = asset($folder . '/' . $imageMeta->meta_value);
    //             }

    //             // Get dimensions and other data
    //             $weight = OptMeta::where('opt_id', $optId)
    //                 ->where('meta_key', $weightKey)
    //                 ->value('meta_value');

    //             $height = OptMeta::where('opt_id', $optId)
    //                 ->where('meta_key', $heightKey)
    //                 ->value('meta_value');

    //             $date = OptMeta::where('opt_id', $optId)
    //                 ->where('meta_key', $dateKey)
    //                 ->value('meta_value');

    //             $notes = OptMeta::where('opt_id', $optId)
    //                 ->where('meta_key', $notesKey)
    //                 ->value('meta_value');

    //             if ($imagePath) {
    //                 $images[] = [
    //                     'path' => $imagePath,
    //                     'weight' => $weight,
    //                     'height' => $height,
    //                     'date' => $date,
    //                     'notes' => $notes,
    //                     'filename' => $imageMeta->meta_value,
    //                     'index' => $i
    //                 ];
    //             }
    //         }
    //     }

    //     // For backward compatibility, also check single image
    //     if (empty($images) && $type == 'before') {
    //         $singleImage = OptMeta::where('opt_id', $optId)
    //             ->where('meta_key', 'before_profile_photo')
    //             ->first();

    //         if ($singleImage && !empty($singleImage->meta_value)) {
    //             if (file_exists(public_path('before/' . $singleImage->meta_value))) {
    //                 $images[] = [
    //                     'path' => asset('before/' . $singleImage->meta_value),
    //                     'weight' => null,
    //                     'height' => null,
    //                     'date' => null,
    //                     'notes' => null,
    //                     'filename' => $singleImage->meta_value,
    //                     'index' => 1
    //                 ];
    //             }
    //         }
    //     } elseif (empty($images) && $type == 'after') {
    //         $singleImage = OptMeta::where('opt_id', $optId)
    //             ->where('meta_key', 'after_profile_photo')
    //             ->first();

    //         if ($singleImage && !empty($singleImage->meta_value)) {
    //             if (file_exists(public_path('after/' . $singleImage->meta_value))) {
    //                 $images[] = [
    //                     'path' => asset('after/' . $singleImage->meta_value),
    //                     'weight' => null,
    //                     'height' => null,
    //                     'date' => null,
    //                     'notes' => null,
    //                     'filename' => $singleImage->meta_value,
    //                     'index' => 1
    //                 ];
    //             }
    //         }
    //     }

    //     return $images;
    // }

    // Helper method to get all program details
    private function getAllProgramDetails($optId, $optMeta)
    {
        $programDetails = [];

        $indexedPrograms = OptMeta::where('opt_id', $optId)
            ->where('meta_key', 'LIKE', 'selected_program_%')
            ->orderBy('meta_key')
            ->get();

        foreach ($indexedPrograms as $programMeta) {
            $key = $programMeta->meta_key;
            if (strpos($key, 'selected_program_') === 0) {
                $index = substr($key, strlen('selected_program_'));

                if (is_numeric($index)) {
                    $sessionMeta = OptMeta::where('opt_id', $optId)
                        ->where('meta_key', 'session_' . $index)
                        ->first();

                    $session = $sessionMeta ? $sessionMeta->meta_value : '';

                    $monthsMeta = OptMeta::where('opt_id', $optId)
                        ->where('meta_key', 'months_' . $index)
                        ->first();

                    $months = $monthsMeta ? $monthsMeta->meta_value : '';

                    $programDetails[] = [
                        'program_name' => $programMeta->meta_value,
                        'session' => $session,
                        'months' => $months,
                        'position' => $optMeta['position'] ?? '',
                        'payment_date' => $optMeta['pod_bd_date'] ?? date('Y-m-d'),
                        'payment_method' => $optMeta['payment_method'] ?? '',
                        'total' => $optMeta['total_payment'] ?? '0.00',
                        'discount' => $optMeta['discount_payment'] ?? '0.00',
                        'given' => $optMeta['given_payment'] ?? '0.00',
                        'due' => $optMeta['due_payment'] ?? '0.00',
                        'due_date' => $optMeta['due_date'] ?? '',
                        'original_index' => $index,
                    ];
                }
            }
        }

        if (empty($programDetails)) {
            $singleProgram = OptMeta::where('opt_id', $optId)
                ->where('meta_key', 'selected_program')
                ->first();

            if ($singleProgram) {
                $session = OptMeta::where('opt_id', $optId)
                    ->where('meta_key', 'session')
                    ->value('meta_value');

                $months = OptMeta::where('opt_id', $optId)
                    ->where('meta_key', 'months')
                    ->value('meta_value');

                $programDetails[] = [
                    'program_name' => $singleProgram->meta_value,
                    'session' => $session ?? '',
                    'months' => $months ?? '',
                    'position' => $optMeta['position'] ?? '',
                    'payment_date' => $optMeta['pod_bd_date'] ?? date('Y-m-d'),
                    'payment_method' => $optMeta['payment_method'] ?? '',
                    'total' => $optMeta['total_payment'] ?? '0.00',
                    'discount' => $optMeta['discount_payment'] ?? '0.00',
                    'given' => $optMeta['given_payment'] ?? '0.00',
                    'due' => $optMeta['due_payment'] ?? '0.00',
                    'due_date' => $optMeta['due_date'] ?? '',
                    'original_index' => 0,
                ];
            }
        }

        return $programDetails;
    }

    // Add these methods to your InquiryDietChartController
    public function updatePaymentProgram(Request $request)
    {
        try {
            \Log::info('Update Payment Program Request:', $request->all());

            $patientId = $request->patient_id;
            $paymentIndex = $request->payment_index;

            // Find the patient
            $patient = AccInquiry::where('id', $patientId)
                ->where('delete_status', '0')
                ->firstOrFail();

            // Find the opt record using the literal patient_id
            $optData = Opt::where('patient_id', $patient->patient_id)
                ->where(function ($q) {
                    $q->whereNull('delete_status')
                      ->orWhere('delete_status', '')
                      ->orWhere('delete_status', '0');
                })
                ->orderByDesc('id')
                ->first();

            if (!$optData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient diet chart not found'
                ], 404);
            }

            // Update indexed keys if index is numeric
            if (is_numeric($paymentIndex)) {
                $optData->setMetaValue("selected_program_{$paymentIndex}", $request->program_name);
                $optData->setMetaValue("session_{$paymentIndex}", $request->session);
                $optData->setMetaValue("months_{$paymentIndex}", $request->months);
            } else {
                $optData->setMetaValue('selected_program', $request->program_name);
                $optData->setMetaValue('session', $request->session);
                $optData->setMetaValue('months', $request->months);
            }

            $optData->setMetaValue('payment_method', $request->payment_method);
            $optData->setMetaValue('total_payment', $request->total);
            $optData->setMetaValue('discount_payment', $request->discount);
            $optData->setMetaValue('given_payment', $request->given);
            $optData->setMetaValue('due_payment', $request->due);
            $optData->setMetaValue('due_date', $request->due_date);

            if ($request->payment_date) {
                $optData->setMetaValue('pod_bd_date', $request->payment_date);
            }

            if ($request->payment_status == '1') {
                $optData->setMetaValue('due_payment', '0.00');
            }

            // Rebuild the programs_array JSON
            $allPrograms = [];
            $indexedPrograms = OptMeta::where('opt_id', $optData->id)
                ->where('meta_key', 'LIKE', 'selected_program_%')
                ->orderBy('meta_key')
                ->get();

            foreach ($indexedPrograms as $programMeta) {
                $key = $programMeta->meta_key;
                $idx = substr($key, strlen('selected_program_'));
                if (is_numeric($idx)) {
                    $session = $optData->getMetaValue('session_' . $idx);
                    $months = $optData->getMetaValue('months_' . $idx);

                    if ($idx == $paymentIndex) {
                        $pName = $request->program_name;
                        $session = $request->session;
                        $months = $request->months;
                    } else {
                        $pName = $programMeta->meta_value;
                    }

                    $allPrograms[] = [
                        'program' => $pName,
                        'session' => $session,
                        'months' => $months,
                        'total' => ($idx == 0) ? ($request->total ?? '0.00') : '0.00',
                        'payment_method' => $request->payment_method ?? '',
                        'payment_date' => $request->payment_date ?? date('Y-m-d'),
                        'index' => $idx,
                        'created_at' => now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            if (empty($allPrograms)) {
                $allPrograms[] = [
                    'program' => $request->program_name,
                    'session' => $request->session,
                    'months' => $request->months,
                    'total' => $request->total ?? '0.00',
                    'payment_method' => $request->payment_method ?? '',
                    'payment_date' => $request->payment_date ?? date('Y-m-d'),
                    'index' => 0,
                    'created_at' => now()->format('Y-m-d H:i:s')
                ];
            }

            $optData->setMetaValue('programs_array', json_encode($allPrograms));

            // Sync invoice and transactions
            $this->syncDietInvoiceAndTransactions($optData, $request);

            return response()->json([
                'success' => true,
                'message' => 'Payment program updated successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating payment program: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating payment program: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deletePaymentProgram(Request $request)
    {
        try {
            \Log::info('Delete Payment Program Request:', $request->all());

            $patientId = $request->patient_id;

            // Find the patient first
            $patient = AccInquiry::where('id', $patientId)
                ->where('delete_status', '0')
                ->firstOrFail();

            // Find the opt record using literal patient_id
            $optData = Opt::where('patient_id', $patient->patient_id)
                ->where(function ($q) {
                    $q->whereNull('delete_status')
                      ->orWhere('delete_status', '')
                      ->orWhere('delete_status', '0');
                })
                ->orderByDesc('id')
                ->first();

            if (!$optData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient diet chart not found'
                ], 404);
            }

            // Clear all payment-related meta data
            $paymentMetaKeys = [
                'selected_program',
                'session',
                'months',
                'position',
                'total_payment',
                'discount_payment',
                'given_payment',
                'due_payment',
                'payment_method',
                'due_date',
                'pod_bd_date',
                'programs_array'
            ];

            foreach ($paymentMetaKeys as $key) {
                $optData->meta()->where('meta_key', $key)->delete();
            }

            // Also delete any indexed selected_program keys
            $optData->meta()->where('meta_key', 'LIKE', 'selected_program_%')->delete();
            $optData->meta()->where('meta_key', 'LIKE', 'session_%')->delete();
            $optData->meta()->where('meta_key', 'LIKE', 'months_%')->delete();

            // Find and delete corresponding invoice and transactions
            $invoiceNo = 'INV-DIET-' . $optData->id . '-' . $optData->patient_id;
            $invoice = \App\Models\Invoice::where('invoice_no', $invoiceNo)->first();
            if ($invoice) {
                \App\Models\PatientTransaction::where('invoice_id', $invoice->id)->delete();
                $invoice->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment program deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting payment program: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error deleting payment program: ' . $e->getMessage()
            ], 500);
        }
    }

    // Add this method to your InquiryDietChartController
    // public function addProgressReport(Request $request)
    // {
    //     try {
    //         \Log::info('Add Progress Report Request:', $request->all());

    //         // Debug: Check all incoming data
    //         \Log::info('All Request Data:', [
    //             'all_data' => $request->all(),
    //             'report_type' => $request->report_type,
    //             'patient_id' => $request->patient_id
    //         ]);

    //         $request->validate([
    //             'patient_id' => 'required|integer', // Changed to integer
    //             'date' => 'required|date',
    //             'time' => 'required',
    //             'report_type' => 'required|in:lymphysis,detox,breast_reshaping,face_program,relaxation,progress',
    //         ]);

    //         // Find the patient - CORRECTED: Use AccInquiry table with id
    //         $patient = AccInquiry::where('id', $request->patient_id)
    //             ->where('delete_status', '0')
    //             ->first();

    //         if (!$patient) {
    //             \Log::error('Patient not found with ID: ' . $request->patient_id);
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Patient not found!'
    //             ], 404);
    //         }

    //         // Get branch info
    //         $branch = Branch::where('branch_name', $patient->branch)
    //             ->where('delete_status', 0)
    //             ->first();

    //         // Prepare base data
    //         $progressData = [
    //             'patient_id' => $patient->id, // Use the AccInquiry ID, not patient_id
    //             'branch_name' => $patient->branch,
    //             'branch_id' => $branch ? $branch->branch_id : '',
    //             'patient_name' => $patient->patient_name,
    //             'date' => $request->date,
    //             'time' => $request->time,
    //             'weight' => $request->weight ?? null,
    //             'councilor_doctor' => $request->councilor_doctor ?? null,
    //             'exercise' => $request->exercise ?? null,
    //             'delete_status' => '0',
    //             // Initialize all possible fields with null
    //             'body_part' => null,
    //             'lypolysis_treatment' => null,
    //             'detox' => null,
    //             'breast_reshaping' => null,
    //             'face_program' => null,
    //             'relaxation' => null,
    //             'bp_p' => $request->bp ?? null,
    //             'pulse' => $request->pulse ?? null,
    //         ];

    //         // Map form fields to database columns
    //         switch ($request->report_type) {
    //             case 'lymphysis':
    //                 $progressData['lypolysis_treatment'] = $request->lypolysis_treatment ?? '';
    //                 break;

    //             case 'detox':
    //                 // Check both field names for compatibility
    //                 $progressData['detox'] = $request->detox ?? $request->detox_treatment ?? '';
    //                 break;

    //             case 'breast_reshaping':
    //                 $progressData['breast_reshaping'] = $request->breast_reshaping ?? '';
    //                 break;

    //             case 'face_program':
    //                 $progressData['face_program'] = $request->face_program ?? '';
    //                 break;

    //             case 'relaxation':
    //                 $progressData['relaxation'] = $request->relaxation ?? '';
    //                 break;

    //             case 'progress':
    //                 $progressData['body_part'] = $request->body_part ?? '';
    //                 break;
    //         }

    //         \Log::info('Progress Data to be saved:', $progressData);

    //         // Create progress report
    //         $progressReport = Progress::create($progressData);

    //         \Log::info('Progress report created successfully with ID: ' . $progressReport->id);

    //         return response()->json([
    //             'success' => true,
    //             'message' => ucfirst(str_replace('_', ' ', $request->report_type)) . ' report added successfully!',
    //             'report_id' => $progressReport->id
    //         ]);
    //     } catch (\Exception $e) {
    //         \Log::error('Error adding progress report: ' . $e->getMessage());
    //         \Log::error('Error trace: ' . $e->getTraceAsString());

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error adding report: ' . $e->getMessage(),
    //             'error_details' => $e->getTraceAsString()
    //         ], 500);
    //     }
    // }


    public function addProgressReport(Request $request)
    {
        try {
            \Log::info('Add Progress Report Request:', $request->all());

            \Log::info('All Request Data:', [
                'all_data' => $request->all(),
                'report_type' => $request->report_type,
                'patient_id' => $request->patient_id
            ]);

            // ❌ REMOVED VALIDATION
            // $request->validate([...]);

            // Find the patient - CORRECTED: Use AccInquiry table with id
            $patient = AccInquiry::where('id', $request->patient_id)
                ->where('delete_status', '0')
                ->first();

            if (!$patient) {
                \Log::error('Patient not found with ID: ' . $request->patient_id);
                return response()->json([
                    'success' => false,
                    'message' => 'Patient not found!'
                ], 404);
            }

            // Get branch info
            $branch = Branch::where('branch_name', $patient->branch)
                ->where('delete_status', 0)
                ->first();

            // Prepare base data
            $progressData = [
                'patient_id' => $patient->id,
                'branch_name' => $patient->branch,
                'branch_id' => $branch ? $branch->branch_id : '',
                'patient_name' => $patient->patient_name,
                'date' => $request->date,
                'time' => $request->time,
                'weight' => $request->weight ?? null,
                'councilor_doctor' => $request->councilor_doctor ?? null,
                'exercise' => $request->exercise ?? null,
                'delete_status' => '0',
                'body_part' => null,
                'lypolysis_treatment' => null,
                'detox' => null,
                'breast_reshaping' => null,
                'face_program' => null,
                'relaxation' => null,
                'bp_p' => $request->bp ?? null,
                'pulse' => $request->pulse ?? null,
            ];

            // Map form fields to database columns
            switch ($request->report_type) {
                case 'lymphysis':
                    $progressData['lypolysis_treatment'] = $request->lypolysis_treatment ?? '';
                    break;

                case 'detox':
                    $progressData['detox'] = $request->detox ?? $request->detox_treatment ?? '';
                    break;

                case 'breast_reshaping':
                    $progressData['breast_reshaping'] = $request->breast_reshaping ?? '';
                    break;

                case 'face_program':
                    $progressData['face_program'] = $request->face_program ?? '';
                    break;

                case 'relaxation':
                    $progressData['relaxation'] = $request->relaxation ?? '';
                    break;

                case 'progress':
                    $progressData['body_part'] = $request->body_part ?? '';
                    break;
            }

            \Log::info('Progress Data to be saved:', $progressData);

            // Create progress report
            $progressReport = Progress::create($progressData);

            \Log::info('Progress report created successfully with ID: ' . $progressReport->id);

            return response()->json([
                'success' => true,
                'message' => ucfirst(str_replace('_', ' ', $request->report_type)) . ' report added successfully!',
                'report_id' => $progressReport->id
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error adding report: ' . $e->getMessage(),
                'error_details' => $e->getTraceAsString()
            ], 500);
        }
    }

    // Get progress report details for editing
    public function getProgressReportDetails($id)
    {
        try {
            $report = Progress::where('id', $id)
                ->where('delete_status', '0')
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'report' => $report
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Report not found'
            ], 404);
        }
    }

    // Update progress report
    public function updateProgressReport(Request $request)
    {
        try {
            \Log::info('Update Progress Report Request:', $request->all());

            $request->validate([
                'report_id' => 'required',
                'report_type' => 'required',
                'date' => 'required|date',
                'time' => 'required',
            ]);

            $report = Progress::where('id', $request->report_id)
                ->where('delete_status', '0')
                ->firstOrFail();

            // Update common fields
            $report->date = $request->date;
            $report->time = $request->time;
            $report->weight = $request->weight;
            $report->councilor_doctor = $request->councilor_doctor;
            $report->exercise = $request->exercise;
            $report->bp_p = $request->bp;
            $report->pulse = $request->pulse;

            // Update type-specific field
            switch ($request->report_type) {
                case 'lymphysis':
                    $report->lypolysis_treatment = $request->lypolysis_treatment;
                    break;
                case 'detox':
                    $report->detox = $request->detox_treatment;
                    break;
                case 'breast_reshaping':
                    $report->breast_reshaping = $request->breast_reshaping;
                    break;
                case 'face_program':
                    $report->face_program = $request->face_program;
                    break;
                case 'relaxation':
                    $report->relaxation = $request->relaxation;
                    break;
                case 'progress':
                    $report->body_part = $request->body_part;
                    break;
            }

            $report->save();

            return response()->json([
                'success' => true,
                'message' => ucfirst(str_replace('_', ' ', $request->report_type)) . ' report updated successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating progress report: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating report: ' . $e->getMessage()
            ], 500);
        }
    }

    // Delete progress report
    public function deleteProgressReport(Request $request)
    {
        try {
            \Log::info('Delete Progress Report Request:', $request->all());

            $report = Progress::where('id', $request->report_id)
                ->where('delete_status', '0')
                ->firstOrFail();

            $report->delete_status = '1';
            $report->delete_by = auth()->id();
            $report->save();

            return response()->json([
                'success' => true,
                'message' => ucfirst(str_replace('_', ' ', $request->report_type)) . ' report deleted successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting progress report: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error deleting report: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Synchronize diet program payment details with Invoice and PatientTransaction tables.
     */
    private function syncDietInvoiceAndTransactions($opt, Request $request)
    {
        $patient = AccInquiry::withoutGlobalScope('branch_restriction')
            ->where('patient_id', $opt->patient_id)
            ->first();

        if (!$patient) {
            $patient = PatientInquiry::where('patient_id', $opt->patient_id)->first();
        }

        if (!$patient) {
            \Log::warning("syncDietInvoiceAndTransactions: Patient record not found for patient_id: {$opt->patient_id}");
            return;
        }

        $totalPayment = (float) ($request->total_payment ?? $request->total ?? 0);
        $discountPayment = (float) ($request->discount_payment ?? $request->discount ?? 0);
        $givenPayment = (float) ($request->given_payment ?? $request->given ?? 0);
        $duePayment = (float) ($request->due_payment ?? $request->due ?? 0);
        $paymentMethod = $request->payment_method ?? 'Cash';
        $paymentDate = $request->pod_bd_date ?? $request->payment_date ?? now()->format('Y-m-d');

        // Invoice should be generated even if total payment is 0 (e.g. complimentary or just to have a record)
        // if ($totalPayment <= 0 && $givenPayment <= 0) {
        //     return;
        // }

        $invoiceNo = 'INV-DIET-' . $opt->id . '-' . $opt->patient_id;

        // Process programs
        $validPrograms = [];
        if ($request->has('selected_program') && is_array($request->selected_program)) {
            foreach ($request->selected_program as $index => $programName) {
                if (!empty($programName)) {
                    $session = $request->session[$index] ?? '';
                    $months = $request->months[$index] ?? '';
                    $price = ($index === 0) ? $totalPayment : 0;

                    $validPrograms[] = [
                        'program_name' => $programName,
                        'session' => $session,
                        'months' => $months,
                        'price' => $price
                    ];
                }
            }
        } elseif ($request->filled('program_name')) {
            $validPrograms[] = [
                'program_name' => $request->program_name,
                'session' => $request->session ?? '',
                'months' => $request->months ?? '',
                'price' => $totalPayment
            ];
        }

        if (empty($validPrograms)) {
            $validPrograms[] = [
                'program_name' => 'Diet Program',
                'session' => '',
                'months' => '',
                'price' => $totalPayment
            ];
        }

        $branch = Branch::where('branch_id', $opt->branch_id)->first();
        $invoiceFile = null;
        if ($branch) {
            $pNameClean = preg_replace('/[^A-Za-z0-9]/', '', $patient->patient_name ?? 'Patient');
            $bNameClean = preg_replace('/[^A-Za-z0-9]/', '', $branch->branch_name ?? 'Branch');
            $invoiceFile = $pNameClean . $bNameClean . '-' . $invoiceNo . '-' . now()->format('d-m-Y') . '.pdf';
        }

        // Check if invoice already exists
        $invoice = \App\Models\Invoice::where('invoice_no', $invoiceNo)->first();

        $invoiceData = [
            'branch_id' => $opt->branch_id,
            'patient_id' => $patient->id,
            'invoice_no' => $invoiceNo,
            'invoice_date' => $paymentDate,
            'address' => $patient->address ?? '',
            'phone' => $patient->phone_no ?? $patient->getMeta('phone') ?? '',
            'price' => $totalPayment,
            'total_payment' => $totalPayment,
            'discount' => $discountPayment,
            'given_payment' => $givenPayment,
            'due_payment' => $duePayment,
            'programs_data' => $validPrograms,
        ];

        if ($invoiceFile) {
            $invoiceData['invoice_file'] = $invoiceFile;
        }

        if ($invoice) {
            $invoice->update($invoiceData);
        } else {
            $invoice = \App\Models\Invoice::create($invoiceData);
        }

        // Description details
        $descPrefix = 'Diet H/O Service - Invoice Generated: ';
        if ($opt->branch_id === 'PP-0002') {
            $descPrefix = 'FNF PP Service - Invoice Generated: ';
        }

        $itemNames = [];
        foreach ($validPrograms as $p) {
            $itemNames[] = $p['program_name'];
        }
        $itemsDetail = !empty($itemNames) ? ' (' . implode(', ', $itemNames) . ')' : '';

        // Sync Debit Transaction
        $debitTx = \App\Models\PatientTransaction::where('invoice_id', $invoice->id)->where('type', 'debit')->first();
        $debitData = [
            'branch_id' => $opt->branch_id,
            'patient_id' => $patient->id,
            'invoice_id' => $invoice->id,
            'type' => 'debit',
            'amount' => $totalPayment - $discountPayment,
            'description' => $descPrefix . $invoice->invoice_no . $itemsDetail,
        ];
        if ($debitTx) {
            $debitTx->update($debitData);
        } else {
            \App\Models\PatientTransaction::create($debitData);
        }

        // Sync Credit Transaction
        $creditTx = \App\Models\PatientTransaction::where('invoice_id', $invoice->id)->where('type', 'credit')->first();
        if ($givenPayment > 0) {
            $creditData = [
                'branch_id' => $opt->branch_id,
                'patient_id' => $patient->id,
                'invoice_id' => $invoice->id,
                'type' => 'credit',
                'amount' => $givenPayment,
                'description' => ($opt->branch_id === 'PP-0002' ? 'FNF PP Service' : 'Diet H/O Service') . ' Payment Received (' . $paymentMethod . ') for Invoice: ' . $invoice->invoice_no . $itemsDetail,
            ];
            if ($creditTx) {
                $creditTx->update($creditData);
            } else {
                \App\Models\PatientTransaction::create($creditData);
            }
        } else {
            if ($creditTx) {
                $creditTx->delete();
            }
        }
    }

    /**
     * Reverse a patient from Diet Chart / Joined back to Follow-up list.
     */
    public function reverseToFollowup(Request $request, $id)
    {
        try {
            $inquiry = AccInquiry::findOrFail($id);

            $nextFollowupDate = $request->input('next_followup_date');
            $source = $request->input('source', 'diet_chart');

            if (empty($nextFollowupDate)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please select a valid follow-up date.',
                ], 422);
            }

            // Get current status_history and remove Diet Chart / Joined / Active
            $statusHistory = $inquiry->status_history ?? [];
            $statusHistory = array_values(array_filter($statusHistory, function ($s) {
                return !in_array($s, ['Diet Chart', 'Joined', 'Active']);
            }));

            // Ensure "Pending" is in the history
            if (!in_array('Pending', $statusHistory)) {
                $statusHistory[] = 'Pending';
            }

            $inquiry->status_history = $statusHistory;
            $inquiry->user_status = 'Pending';
            $inquiry->next_followup_date = $nextFollowupDate;
            $inquiry->save();

            $patientName = $inquiry->patient_name ?? $inquiry->patient_f_name ?? 'Patient';

            return response()->json([
                'success' => true,
                'message' => "{$patientName} has been moved to Follow-up for {$nextFollowupDate}.",
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Patient inquiry not found.',
            ], 404);
        } catch (\Exception $e) {
            \Log::error('reverseToFollowup error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }
}
