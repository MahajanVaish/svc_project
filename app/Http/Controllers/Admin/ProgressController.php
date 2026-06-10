<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccInquiry;
use App\Models\Branch;
use App\Models\HydraInquiry;
use App\Models\LHRInquiry;
use App\Models\PatientInquiry;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProgressController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userBranch = $user->user_branch;
        $isSuperadmin = $user->hasRole('Superadmin');

        $branches = Branch::where(function ($q) {
            $q->where('delete_status', '0')
                ->orWhere('delete_status', '');
        })
            ->when(!$isSuperadmin, function ($q) use ($userBranch) {
            $q->where('branch_id', $userBranch);
        })
            ->orderBy('branch_name', 'asc')
            ->get(['branch_id', 'branch_name']);

        $programs = \App\Models\ManageProgram::where('delete_status', '0')
            ->where('branch', 'FNF')
            ->orderBy('program_name', 'asc')
            ->get(['id', 'program_name']);

        return view('admin.progress.progress_report', compact('branches', 'programs'));
    }



    // public function store(Request $request)
    // {

    //     $request->validate([
    //         'patient_id' => 'required',
    //         'branch_id' => 'required',
    //         'patient_name' => 'required',
    //         'date' => 'required|date',
    //         'time' => 'required',
    //     ]);

    //     // Get branch name from branch_id
    //     $branch = Branch::where('branch_id', $request->branch_id)->first();
    //     $branch_name = $branch ? $branch->branch_name : '';

    //     // ✅ ALL FIELDS INCLUDED
    //     $progress = Progress::create([
    //         'patient_id' => $request->patient_id,
    //         'branch_name' => $branch_name, // Get from branch table
    //         'branch_id' => $request->branch_id,
    //         'patient_name' => $request->patient_name,
    //         'date' => $request->date,
    //         'time' => $request->time,
    //         'body_part' => $request->body_part ?? '',
    //         'bp_p' => $request->bp_p ?? '',
    //         'pulse' => $request->pulse ?? '',
    //         'detox' => $request->detox ?? '',
    //         'breast_reshaping' => $request->breast_reshaping ?? '',
    //         'face_program' => $request->face_program ?? '',
    //         'relaxation' => $request->relaxation ?? '',
    //         'lypolysis_treatment' => $request->lypolysis_treatment ?? '',
    //         'weight' => $request->weight ?? '',
    //         'councilor_doctor' => $request->councilor_doctor ?? '',
    //         'exercise' => $request->exercise ?? '',
    //         'delete_status' => '0', // Default value
    //         'delete_by' => Auth::check() ? Auth::id() : null,
    //     ]);

    //     return redirect()->back()->with('success', 'Progress report saved successfully.');
    // }


  public function store(Request $request)
    {
 
        try {
            $request->validate([
                'patient_id' => 'required|exists:acc_inquirys,id',
                'branch_id' => 'required|exists:branches,branch_id',
                'patient_name' => 'required|string',
                'date' => 'required|date',
                'time' => 'required',
                'diet' => 'nullable|string|max:500',
                'sleep' => 'nullable|string|max:500',
                'water' => 'nullable|string|max:500',
                'medication' => 'nullable|string|max:500',
            ]);
 
            $branch = Branch::where('branch_id', $request->branch_id)->first();
            if (!$branch) {
                return redirect()->back()->withErrors(['branch_id' => 'Invalid branch selected'])->withInput();
            }
 
            // Convert arrays to comma-separated strings
            $bodyPart = is_array($request->body_part) ? implode(', ', array_filter($request->body_part)) : ($request->body_part ?? null);
            $programName = is_array($request->program_name) ? implode(', ', array_filter($request->program_name)) : ($request->program_name ?? null);
 
            Progress::create([
                'patient_id' => $request->patient_id,
                'branch_name' => $branch->branch_name,
                'branch_id' => $request->branch_id,
                'patient_name' => $request->patient_name,
                'date' => $request->date,
                'time' => $request->time,
                'body_part' => $bodyPart,
                'bp_p' => $request->bp_p ?? null,
                'pulse' => $request->pulse ?? null,
                'detox' => $request->detox ?? null,
                'face_program' => $programName,
                'lypolysis_treatment' => $request->lypolysis_treatment ?? null,
                'weight' => $request->weight ?? null,
                'height' => $request->height ?? null,
                'bmi' => $request->bmi ?? null,
                'councilor_doctor' => $request->councilor_doctor ?? null,
                'exercise' => $request->exercise ?? null,
                'diet' => $request->diet ?? null,
                'sleep' => $request->sleep ?? null,
                'water' => $request->water ?? null,
                'medication' => $request->medication ?? null,
                'delete_status' => '0',
                'delete_by' => Auth::id() ?? null,
            ]);
//  dd($request->all());
            return redirect()->back()->with('success', 'Progress report saved successfully.');
        }
        catch (\Exception $e) {
            \Log::error('Progress Store Error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to save progress report: ' . $e->getMessage()])->withInput();
        }
    }
    // public function getPatientsByBranch(Request $request)
// {
//     $branchId = $request->branch_id;
//     $user = auth()->user();
//     $isSuperadmin = $user->hasRole('Superadmin');

    //     if (!$branchId && !$isSuperadmin) {
//         return response()->json(['success' => false], 400);
//     }

    //     $patients = collect();

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
//                 DB::raw("CONCAT(patient_f_name,' ',patient_l_name) as patient_name")
//             ])
//     );

    //     $patients = $patients->merge(
//         PatientInquiry::withTrashed()
//             ->where(function ($q) {
//                 $q->where('delete_status', '0')
//                   ->orWhere('delete_status', '');
//             })
//             ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
//             ->get([
//                 'id',
//                 'patient_id',
//                 'branch_id',
//                 'age',
//                 'patient_name'
//             ])
//     );



    //     // dd($patients);

    //     $patients = $patients->merge(
//         LHRInquiry::whereNull('deleted_at')
//             ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
//             ->get([
//                 'id',
//                 'patient_id',
//                 'branch_id',
//                 'age',
//                 'patient_name'
//             ])
//     );


    //     $patients = $patients->merge(
//         HydraInquiry::when($branchId, fn ($q) => $q->where('branch_id', $branchId))
//             ->get([
//                 'id',
//                 'patient_id',
//                 'branch_id',
//                 'age',
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

    public function getPatientsByBranch(Request $request, $branchId)
    {
        $search = $request->query('search');
        $user = auth()->user();
        $isSuperadmin = $user->hasRole('Superadmin');

        // 🚨 Branch is mandatory
        if (!$branchId) {
            return response()->json([
                'success' => true,
                'patients' => []
            ]);
        }

        $patients = collect();

        // 1. AccInquiry
        $accPatients = AccInquiry::where('branch_id', $branchId)
            ->where(function ($q) {
                $q->where('delete_status', '0')
                    ->orWhere('delete_status', '');
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('patient_f_name', 'like', '%' . $search . '%')
                        ->orWhere('patient_l_name', 'like', '%' . $search . '%')
                        ->orWhere('patient_id', 'like', '%' . $search . '%');
                });
            })
            ->limit(50)
            ->get([
                'id',
                'patient_id',
                'branch_id',
                'age',
                'patient_f_name',
                'patient_m_name',
                'patient_l_name',
            ])
            ->map(function ($p) {
                $patientName = trim(
                    trim((string)($p->patient_f_name ?? '')) . ' ' .
                    trim((string)($p->patient_m_name ?? '')) . ' ' .
                    trim((string)($p->patient_l_name ?? ''))
                );

                return [
                    'id' => $p->id,
                    'patient_id' => $p->patient_id,
                    'branch_id' => $p->branch_id,
                    'age' => $p->age,
                    'patient_name' => $patientName !== '' ? $patientName : ($p->patient_id ?? 'Unknown'),
                    'table' => 'acc_inquirys'
                ];
            });
        $patients = $patients->merge($accPatients);

        // 2. PatientInquiry
        $pInquiryPatients = PatientInquiry::where('branch_id', $branchId)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('patient_name', 'like', '%' . $search . '%')
                        ->orWhere('patient_id', 'like', '%' . $search . '%');
                });
            })
            ->limit(50)
            ->get([
                'id',
                'patient_id',
                'branch_id',
                'age',
                'patient_name'
            ])
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'patient_id' => $p->patient_id,
                    'branch_id' => $p->branch_id,
                    'age' => $p->age,
                    'patient_name' => $p->patient_name ?: ($p->patient_id ?? 'Unknown'),
                    'table' => 'patient_inquiry'
                ];
            });
        $patients = $patients->merge($pInquiryPatients);

        // 3. LHRInquiry
        $lhrPatients = LHRInquiry::whereNull('deleted_at')
            ->where('branch_id', $branchId)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('patient_name', 'like', '%' . $search . '%')
                        ->orWhere('patient_id', 'like', '%' . $search . '%');
                });
            })
            ->limit(50)
            ->get([
                'id',
                'patient_id',
                'branch_id',
                'age',
                'patient_name'
            ])
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'patient_id' => $p->patient_id,
                    'branch_id' => $p->branch_id,
                    'age' => $p->age,
                    'patient_name' => $p->patient_name ?: ($p->patient_id ?? 'Unknown'),
                    'table' => 'lhr_inquiries'
                ];
            });
        $patients = $patients->merge($lhrPatients);

        // 4. HydraInquiry
        $hydraPatients = HydraInquiry::where('branch_id', $branchId)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('patient_name', 'like', '%' . $search . '%')
                        ->orWhere('patient_id', 'like', '%' . $search . '%');
                });
            })
            ->limit(50)
            ->get([
                'id',
                'patient_id',
                'branch_id',
                'age',
                'patient_name'
            ])
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'patient_id' => $p->patient_id,
                    'branch_id' => $p->branch_id,
                    'age' => $p->age,
                    'patient_name' => $p->patient_name ?: ($p->patient_id ?? 'Unknown'),
                    'table' => 'hydra_inquiries'
                ];
            });
        $patients = $patients->merge($hydraPatients);

        return response()->json([
            'success' => true,
            'patients' => $patients->sortBy('patient_name')->values()
        ]);
    }

    public function getPatientPrefill($id)
    {
        try {
            $patient = AccInquiry::find($id);
            if (!$patient) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient not found',
                ], 404);
            }

            // Fetch diet H/O data to get selected programs
            $selectedPrograms = [];
            $opt = \App\Models\Opt::where('patient_id', $patient->patient_id)
                ->where('delete_status', '0')
                ->orderBy('created_at', 'desc')
                ->first();

            if ($opt) {
                // Get programs from diet H/O
                $indexedPrograms = \App\Models\OptMeta::where('opt_id', $opt->id)
                    ->where('meta_key', 'LIKE', 'selected_program_%')
                    ->orderBy('meta_key')
                    ->get();

                foreach ($indexedPrograms as $programMeta) {
                    $key = $programMeta->meta_key;
                    if (strpos($key, 'selected_program_') === 0) {
                        $index = substr($key, strlen('selected_program_'));

                        if (is_numeric($index)) {
                            $sessionMeta = \App\Models\OptMeta::where('opt_id', $opt->id)
                                ->where('meta_key', 'session_' . $index)
                                ->first();

                            $session = $sessionMeta ? $sessionMeta->meta_value : '';

                            $selectedPrograms[] = [
                                'program_name' => $programMeta->meta_value,
                                'session' => $session,
                            ];
                        }
                    }
                }

                // If no indexed programs found, try single program
                if (empty($selectedPrograms)) {
                    $singleProgram = \App\Models\OptMeta::where('opt_id', $opt->id)
                        ->where('meta_key', 'selected_program')
                        ->first();

                    if ($singleProgram) {
                        $session = \App\Models\OptMeta::where('opt_id', $opt->id)
                            ->where('meta_key', 'session')
                            ->value('meta_value');

                        $selectedPrograms[] = [
                            'program_name' => $singleProgram->meta_value,
                            'session' => $session ?? '',
                        ];
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'height' => $patient->height,
                    'weight' => $patient->weight,
                    'bmi' => $patient->bmi,
                    'selected_programs' => $selectedPrograms,
                ],
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching patient data: ' . $e->getMessage(),
            ], 500);
        }
    }
}