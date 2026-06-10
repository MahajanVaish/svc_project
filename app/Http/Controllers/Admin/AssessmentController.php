<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccInquiry;
use App\Models\Branch;
use App\Models\HydraInquiry;
use App\Models\LHRInquiry;
use App\Models\MonthlyAssessment;
use App\Models\PatientInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssessmentController extends Controller
{
    public function monthlyAssessment(Request $request)
    {
        $user = auth()->user();
        $userBranch = $user->user_branch;
        // Allow Superadmin and Doctor to see all branches
        $isSuperadmin = $user->hasRole('Superadmin') || $user->hasRole('Doctor');
        $requestedBranchId = $request->query('branch_id');

        // Branches query
        $branches = Branch::where(function ($q) {
            $q->where('delete_status', '0')
                ->orWhere('delete_status', '');
        })
            ->when(!$isSuperadmin, function ($q) use ($userBranch, $requestedBranchId) {
            $q->where(function ($sub) use ($userBranch, $requestedBranchId) {
                    $sub->where('branch_id', $userBranch);
                    if ($requestedBranchId) {
                        $sub->orWhere('branch', $requestedBranchId);
                    }
                }
                );
            })
            ->orderBy('branch_name', 'asc')
            ->get();

        // Patients
        $patients = AccInquiry::where(function ($q) {
            $q->where('delete_status', '0')
                ->orWhere('delete_status', '');
        })
            ->when(!$isSuperadmin, function ($q) use ($userBranch) {
            $q->where('branch_id', $userBranch);
        })
            ->orderBy('patient_f_name', 'asc')
            ->limit(50)
            ->get([
            'id',
            'patient_id',
            'patient_f_name',
            'patient_l_name',
            'branch_id',
            'age'
        ]);

        return view(
            'admin.Assessment.monthly-assessment',
            compact('branches', 'patients')
        );
    }

    public function getPatientsByBranch(Request $request)
    {
        $branchId = $request->branch_id;
        $search = $request->search;
        $user = auth()->user();

        // Safety check for user
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $isSuperadmin = $user->hasRole('Superadmin') || $user->hasRole('Doctor');

        if (!$branchId && !$isSuperadmin) {
            return response()->json(['success' => false, 'message' => 'Branch ID is required'], 400);
        }

        // Check for branch name to allow fallback search in 'branch' column
        $branch = \App\Models\Branch::where('branch_id', $branchId)->first();
        $branchName = $branch ? $branch->branch_name : null;

        $patients = collect();

        // 1. AccInquiry Patients
        $patients = $patients->merge(
            AccInquiry::where(function ($q) {
            $q->where('delete_status', '0')
                ->orWhere('delete_status', '');
        })
            ->when($branchId, function ($q) use ($branchId, $branchName) {
                $q->where(function ($subQ) use ($branchId, $branchName) {
                    $subQ->where('branch_id', $branchId);
                    if ($branchName) {
                        $subQ->orWhere('branch', $branchName)
                            ->orWhere('branch', 'like', '%' . $branchName . '%');
                    }
                });
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
            'source' => 'acc'
            ];
        })
        );

        // 2. PatientInquiry Patients
        $patients = $patients->merge(
            PatientInquiry::when($branchId, function ($q) use ($branchId, $branchName) {
                $q->where(function ($subQ) use ($branchId, $branchName) {
                    $subQ->where('branch_id', $branchId);
                    if ($branchName) {
                        $subQ->orWhere('branch', $branchName)
                            ->orWhere('branch', 'like', '%' . $branchName . '%');
                    }
                });
            })
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
            $patientName = trim((string)($p->patient_name ?? ''));
            return [
            'id' => $p->id,
            'patient_id' => $p->patient_id,
            'branch_id' => $p->branch_id,
            'age' => $p->age,
            'patient_name' => $patientName !== '' ? $patientName : ($p->patient_id ?? 'Unknown'),
            'source' => 'inquiry'
            ];
        })
        );

        // 3. LHRInquiry Patients
        $patients = $patients->merge(
            LHRInquiry::whereNull('deleted_at')
            ->when($branchId, function ($q) use ($branchId, $branchName) {
                $q->where(function ($subQ) use ($branchId, $branchName) {
                    $subQ->where('branch_id', $branchId);
                    if ($branchName) {
                        $subQ->orWhere('branch', $branchName)
                            ->orWhere('branch', 'like', '%' . $branchName . '%');
                    }
                });
            })
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
            $patientName = trim((string)($p->patient_name ?? ''));
            return [
            'id' => $p->id,
            'patient_id' => $p->patient_id,
            'branch_id' => $p->branch_id,
            'age' => $p->age,
            'patient_name' => $patientName !== '' ? $patientName : ($p->patient_id ?? 'Unknown'),
            'source' => 'lhr'
            ];
        })
        );

        // 4. HydraInquiry Patients
        $patients = $patients->merge(
            HydraInquiry::when($branchId, function ($q) use ($branchId, $branchName) {
                $q->where(function ($subQ) use ($branchId, $branchName) {
                    $subQ->where('branch_id', $branchId);
                    if ($branchName) {
                        $subQ->orWhere('branch', $branchName)
                            ->orWhere('branch', 'like', '%' . $branchName . '%');
                    }
                });
            })
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
            $patientName = trim((string)($p->patient_name ?? ''));
            return [
            'id' => $p->id,
            'patient_id' => $p->patient_id,
            'branch_id' => $p->branch_id,
            'age' => $p->age,
            'patient_name' => $patientName !== '' ? $patientName : ($p->patient_id ?? 'Unknown'),
            'source' => 'hydra'
            ];
        })
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

    public function storeAssessment(Request $request)
    {
        try {
            $request->validate([
                'branch_id' => 'required', 
                'patient_id' => 'required', 
                'assessment_date' => 'required|date',
                'status' => 'required|in:draft,submitted',
            ]);

            $patientIdString = $request->patient_code;

            if (empty($patientIdString)) {
                $patient = AccInquiry::find($request->patient_id)
                    ?? PatientInquiry::find($request->patient_id)
                    ?? LHRInquiry::find($request->patient_id)
                    ?? HydraInquiry::find($request->patient_id);

                if ($patient) {
                    $patientIdString = $patient->patient_id;
                }
            }

            if (empty($patientIdString)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient ID not found'
                ], 400);
            }

            $measurements = $request->measurements ?? [];

            $assessmentData = [
                'branch_id' => $request->branch_id,
                'patient_inquiry_id' => $request->patient_id,
                'patient_id' => $patientIdString,
                'assessment_date' => $request->assessment_date,
                'status' => $request->status,
                'assessed_by' => Auth::check() ? Auth::user()->name : 'System',

                'waist_upper' => $this->getMeasurementValue($measurements, 'waist_upper'),
                'waist_middle' => $this->getMeasurementValue($measurements, 'waist_middle'),
                'waist_lower' => $this->getMeasurementValue($measurements, 'waist_lower'),
                'hips' => $this->getMeasurementValue($measurements, 'hips'),
                'thighs' => $this->getMeasurementValue($measurements, 'thighs'),
                'arms' => $this->getMeasurementValue($measurements, 'arms'),
                'waist_hips_ratio' => $this->getMeasurementValue($measurements, 'waist_hips'),
                'weight' => $this->getMeasurementValue($measurements, 'weight'),
                'bmi' => $this->getMeasurementValue($measurements, 'bmi'),

                'bca_vbf' => $this->getMeasurementValue($measurements, 'bca_vbf'),
                'bca_arms' => $this->getMeasurementValue($measurements, 'bca_arms'),
                'bca_trunk' => $this->getMeasurementValue($measurements, 'bca_trunk'),
                'bca_legs' => $this->getMeasurementValue($measurements, 'bca_legs'),
                'bca_sf' => $this->getMeasurementValue($measurements, 'bca_sf'),
                'bca_vf' => $this->getMeasurementValue($measurements, 'bca_vf'),

                'muscle_vbf' => $this->getMeasurementValue($measurements, 'muscle_vbf'),
                'muscle_arms' => $this->getMeasurementValue($measurements, 'muscle_arms'),
                'muscle_trunk' => $this->getMeasurementValue($measurements, 'muscle_trunk'),
                'muscle_legs' => $this->getMeasurementValue($measurements, 'muscle_legs'),
                'diet' => $request->diet,
                'exercise' => $request->exercise,
                'sleep' => $request->sleep,
                'water' => $request->water,
            ];

            $existingAssessment = MonthlyAssessment::where('patient_inquiry_id', $request->patient_id)
                ->whereDate('assessment_date', $request->assessment_date)
                ->first();

            if ($existingAssessment) {
                $existingAssessment->update($assessmentData);
                $assessment = $existingAssessment;
                $message = 'Assessment updated successfully!';
            }
            else {
                $assessment = MonthlyAssessment::create($assessmentData);
                $message = 'Assessment saved successfully!';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'assessment_id' => $assessment->id,
                'data' => $assessment
            ]);
        }
        catch (\Exception $e) {
            Log::error('Assessment Save Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving assessment: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getMeasurementValue($measurements, $key)
    {
        if (!isset($measurements[$key])) {
            return null;
        }

        if (is_array($measurements[$key]) && isset($measurements[$key]['value'])) {
            $value = $measurements[$key]['value'];
        }
        else {
            $value = $measurements[$key];
        }

        if (is_numeric($value)) {
            return (float)$value;
        }
        elseif ($value === '' || $value === null) {
            return null;
        }

        return $value;
    }

    public function getAssessmentHistory(Request $request)
    {
        try {
            $patientId = $request->input('patient_id');

            $assessments = MonthlyAssessment::where('patient_inquiry_id', $patientId)
                ->orderBy('assessment_date', 'desc')
                ->get(['id', 'assessment_date', 'status', 'weight', 'bmi', 'waist_middle', 'hips']);

            return response()->json([
                'success' => true,
                'assessments' => $assessments
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching assessment history'
            ], 500);
        }
    }

    public function getAssessmentDetails($id)
    {
        try {
            $assessment = MonthlyAssessment::with(['branch', 'patient'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'assessment' => $assessment,
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Assessment not found: ' . $e->getMessage()
            ], 404);
        }
    }

    public function updateAssessment(Request $request)
    {
        try {
            $validated = $request->validate([
                'assessment_id' => 'required|exists:monthly_assessments,id',
                'assessment_date' => 'required|date',
                'waist_upper' => 'nullable|numeric',
                'waist_middle' => 'nullable|numeric',
                'waist_lower' => 'nullable|numeric',
                'hips' => 'nullable|numeric',
                'thighs' => 'nullable|numeric',
                'arms' => 'nullable|numeric',
                'waist_hips_ratio' => 'nullable|numeric',
                'weight' => 'nullable|numeric',
                'bmi' => 'nullable|numeric',
                'bca_vbf' => 'nullable|numeric',
                'bca_arms' => 'nullable|numeric',
                'bca_trunk' => 'nullable|numeric',
                'bca_legs' => 'nullable|numeric',
                'bca_sf' => 'nullable|numeric',
                'bca_vf' => 'nullable|numeric',
                'muscle_vbf' => 'nullable|numeric',
                'muscle_arms' => 'nullable|numeric',
                'muscle_trunk' => 'nullable|numeric',
                'muscle_legs' => 'nullable|numeric',
                'diet' => 'nullable|string',
                'exercise' => 'nullable|string',
                'sleep' => 'nullable|string',
                'water' => 'nullable|string',
            ]);

            $assessment = MonthlyAssessment::find($request->assessment_id);

            if (!$assessment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assessment not found'
                ], 404);
            }

            $assessment->update([
                'assessment_date' => $validated['assessment_date'],
                'waist_upper' => $validated['waist_upper'] ?? null,
                'waist_middle' => $validated['waist_middle'] ?? null,
                'waist_lower' => $validated['waist_lower'] ?? null,
                'hips' => $validated['hips'] ?? null,
                'thighs' => $validated['thighs'] ?? null,
                'arms' => $validated['arms'] ?? null,
                'waist_hips_ratio' => $validated['waist_hips_ratio'] ?? null,
                'weight' => $validated['weight'] ?? null,
                'bmi' => $validated['bmi'] ?? null,
                'bca_vbf' => $validated['bca_vbf'] ?? null,
                'bca_arms' => $validated['bca_arms'] ?? null,
                'bca_trunk' => $validated['bca_trunk'] ?? null,
                'bca_legs' => $validated['bca_legs'] ?? null,
                'bca_sf' => $validated['bca_sf'] ?? null,
                'bca_vf' => $validated['bca_vf'] ?? null,
                'muscle_vbf' => $validated['muscle_vbf'] ?? null,
                'muscle_arms' => $validated['muscle_arms'] ?? null,
                'muscle_trunk' => $validated['muscle_trunk'] ?? null,
                'muscle_legs' => $validated['muscle_legs'] ?? null,
                'diet' => $validated['diet'] ?? null,
                'exercise' => $validated['exercise'] ?? null,
                'sleep' => $validated['sleep'] ?? null,
                'water' => $validated['water'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Assessment updated successfully',
                'data' => $assessment
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating assessment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteAssessment(Request $request)
    {
        try {
            $request->validate([
                'assessment_id' => 'required|exists:monthly_assessments,id'
            ]);

            $assessment = MonthlyAssessment::find($request->assessment_id);

            if (!$assessment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assessment not found'
                ], 404);
            }

            $assessment->update([
                'delete_status' => '1',
                'delete_by' => auth()->id(),
                'delete_date' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Assessment deleted successfully'
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting assessment: ' . $e->getMessage()
            ], 500);
        }
    }
}