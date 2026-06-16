<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use App\Models\DietPlan;
use App\Models\Recipe;
use App\Models\PatientInquiry;
use App\Http\Controllers\Controller;
use App\Models\AccInquiry;
use App\Models\HydraInquiry;
use App\Models\LHRInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DietPlanController extends Controller
{
    /**
     * Show diet plan form with branches from database
     */
    public function create(Request $request)
    {
        try {
            $user = auth()->user();
            $userBranch = $user->user_branch; // e.g. SVC-0001
            // Allow Superadmin and Doctor to see all branches
            $isSuperadmin = $user->hasRole('Superadmin') || $user->hasRole('Doctor');
            $requestedBranchId = $request->query('branch_id');

            // 🔹 Branch query
            $branches = Branch::where(function ($q) {
                $q->where('delete_status', '0')
                    ->orWhere('delete_status', '');
            })
                ->when(!$isSuperadmin, function ($q) use ($userBranch, $requestedBranchId) {
                    $q->where(
                        function ($sub) use ($userBranch, $requestedBranchId) {
                            $sub->where('branch_id', $userBranch);
                            if ($requestedBranchId) {
                                $sub->orWhere('branch_id', $requestedBranchId);
                            }
                        }
                    );
                })
                ->orderBy('branch_name', 'asc')
                ->get();


            // 🔹 Fallback (optional, but safe)
            if ($branches->isEmpty() && $isSuperadmin) {
                $branches = Branch::orderBy('branch_name', 'asc')->get();
            }

            // 🔹 Debug logs
            Log::info('Branches for diet plan form (user: ' . $user->id . ')');
            foreach ($branches as $branch) {
                Log::info('Branch ID: ' . $branch->branch_id . ', Name: ' . $branch->branch_name);
            }

            return view('admin.diet.diet_plan', compact('branches'));

        } catch (\Exception $e) {

            Log::error('Error in DietPlanController::create: ' . $e->getMessage());

            return view('admin.diet.diet_plan', [
                'branches' => collect([])
            ])->with('error', 'Unable to load branches.');
        }
    }

    public function getPatientsByBranch(Request $request)
    {
        $branchId = $request->branch_id; // branch selected
        $search = $request->search;
        $user = auth()->user();
        $isSuperadmin = $user->hasRole('Superadmin') || $user->hasRole('Doctor');

        // Only non-admin users must select a branch
        if (!$branchId && !$isSuperadmin) {
            return response()->json(['success' => false, 'message' => 'Branch not selected'], 400);
        }

        // Check for branch name to allow fallback search in 'branch' column
        $branch = Branch::where('branch_id', $branchId)->first();
        $branchName = $branch ? $branch->branch_name : null;


        $patients = collect();

        $filterByBranch = !empty($branchId); // Only filter if branchId provided

        // AccInquiry
        $accPatients = AccInquiry::where(function ($q) {
            $q->where('delete_status', '0')
                ->orWhere('delete_status', '');
        })
            ->when($filterByBranch, fn($q) => $q->where('branch_id', $branchId))
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
                    trim((string) ($p->patient_f_name ?? '')) . ' ' .
                    trim((string) ($p->patient_m_name ?? '')) . ' ' .
                    trim((string) ($p->patient_l_name ?? ''))
                );

                return [
                    'id' => $p->id,
                    'patient_id' => $p->patient_id,
                    'branch_id' => $p->branch_id,
                    'age' => $p->age,
                    'patient_name' => $patientName !== '' ? $patientName : ($p->patient_id ?? 'Unknown')
                ];
            });

        $patients = $patients->merge($accPatients);

        // PatientInquiry
        $patientInquiryPatients = PatientInquiry::when($filterByBranch, function ($q) use ($branchId, $branchName) {
            $q->where(
                function ($subQ) use ($branchId, $branchName) {
                    $subQ->where('branch_id', $branchId);
                    if ($branchName) {
                        $subQ->orWhere('branch', $branchName);
                    }
                }
            );
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
                $patientName = trim((string) ($p->patient_name ?? ''));
                return [
                    'id' => $p->id,
                    'patient_id' => $p->patient_id,
                    'branch_id' => $p->branch_id,
                    'age' => $p->age,
                    'patient_name' => $patientName !== '' ? $patientName : ($p->patient_id ?? 'Unknown')
                ];
            });

        $patients = $patients->merge($patientInquiryPatients);

        // LHRInquiry
        $lhrPatients = LHRInquiry::whereNull('deleted_at')
            ->when($filterByBranch, fn($q) => $q->where('branch_id', $branchId))
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
                $patientName = trim((string) ($p->patient_name ?? ''));
                return [
                    'id' => $p->id,
                    'patient_id' => $p->patient_id,
                    'branch_id' => $p->branch_id,
                    'age' => $p->age,
                    'patient_name' => $patientName !== '' ? $patientName : ($p->patient_id ?? 'Unknown')
                ];
            });

        $patients = $patients->merge($lhrPatients);

        // HydraInquiry
        $hydraPatients = HydraInquiry::when($filterByBranch, fn($q) => $q->where('branch_id', $branchId))
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
                $patientName = trim((string) ($p->patient_name ?? ''));
                return [
                    'id' => $p->id,
                    'patient_id' => $p->patient_id,
                    'branch_id' => $p->branch_id,
                    'age' => $p->age,
                    'patient_name' => $patientName !== '' ? $patientName : ($p->patient_id ?? 'Unknown')
                ];
            });

        $patients = $patients->merge($hydraPatients);

        $forcePatientId = $request->force_patient_id;
        if ($forcePatientId && !$patients->contains('patient_id', $forcePatientId)) {
            $p = \App\Models\PatientInquiry::where('patient_id', $forcePatientId)->first();
            if (!$p) $p = \App\Models\AccInquiry::where('patient_id', $forcePatientId)->first();
            if (!$p) $p = \App\Models\LHRInquiry::where('patient_id', $forcePatientId)->first();
            if (!$p) $p = \App\Models\HydraInquiry::where('patient_id', $forcePatientId)->first();
            
            if ($p) {
                $patientName = $p->patient_name ?? trim(($p->patient_f_name ?? '') . ' ' . ($p->patient_l_name ?? ''));
                $patients->push([
                    'id' => $p->id,
                    'patient_id' => $p->patient_id,
                    'branch_id' => $p->branch_id,
                    'age' => $p->age,
                    'patient_name' => $patientName !== '' ? $patientName : ($p->patient_id ?? 'Unknown')
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'patients' => $patients->sortBy('patient_name')->values()
        ]);
    }

    /**
     * Get distinct diet name suggestions for autocomplete (AJAX)
     */
    public function getDietNameSuggestions(Request $request)
    {
        try {
            $query = $request->query('q', '');

            $suggestions = DietPlan::select('diet_name')
                ->when($query, fn($q) => $q->where('diet_name', 'like', '%' . $query . '%'))
                ->distinct()
                ->orderBy('diet_name', 'asc')
                ->limit(10)
                ->pluck('diet_name');

            return response()->json(['success' => true, 'suggestions' => $suggestions]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'suggestions' => []]);
        }
    }

    /**
     * Get all recipes for search menu dropdown (AJAX)
     */
    public function getRecipes(Request $request)
    {
        try {
            Log::info('Fetching recipes for search menu dropdown');

            // Fetch all recipes from database
            $recipes = Recipe::orderBy('name', 'asc')->get(['id', 'name']);

            Log::info('Found ' . $recipes->count() . ' recipes');

            return response()->json([
                'success' => true,
                'recipes' => $recipes
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching recipes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching recipes: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());
        try {
            // Validate the request
            $validated = $request->validate([
                'branch_id' => 'required|string|max:255',
                'patient_id' => 'required',
                'date' => 'required|date',
                'diet_name' => 'required|string|max:255',
                'general_notes' => 'nullable|string',
                'next_follow_up_date' => 'nullable|date',
                'diet' => 'nullable|string',
                'exercise' => 'nullable|string',
                'sleep' => 'nullable|string',
                'water' => 'nullable|string',
            ]);

            Log::info('Storing diet plan with data:', $request->all());

            $patientName = null;
            $pId = $validated['patient_id'];

            // Attempt to find patient name across multiple tables
            $patient = DB::table('patient_inquiry')->where('patient_id', $pId)->first()
                ?? DB::table('patient_inquiry')->where('id', $pId)->first()
                ?? DB::table('acc_inquirys')->where('patient_id', $pId)->first()
                ?? DB::table('acc_inquirys')->where('id', $pId)->first()
                ?? DB::table('lhr_inquiries')->where('patient_id', $pId)->first()
                ?? DB::table('hydra_inquiries')->where('patient_id', $pId)->first();

            if ($patient) {
                // ACC patients have no patient_name column — build full name from parts
                if (!empty($patient->patient_name)) {
                    $patientName = $patient->patient_name;
                } elseif (!empty($patient->patient_f_name)) {
                    $patientName = trim(
                        ($patient->patient_f_name ?? '') . ' ' .
                        ($patient->patient_m_name ?? '') . ' ' .
                        ($patient->patient_l_name ?? '')
                    );
                }
            }

            $timeSearchMenusArray = [];
            if ($request->has('time_search_menus')) {
                foreach ($request->time_search_menus as $index => $menu) {
                    $hasTime     = !empty($menu['time']);
                    $hasRecipes  = !empty($menu['recipes']) || !empty($menu['selected_recipes']) || !empty($menu['search_menu']);
                    $hasNotes    = !empty($menu['notes']);
                    $hasQuantity = !empty($menu['quantity']);

                    if ($hasTime || $hasRecipes || $hasNotes || $hasQuantity) {

                        $recipesList    = null;
                        $searchMenuValue = '';

                        // 1. recipes array sent directly from JSON payload (new JS path)
                        if (!empty($menu['recipes']) && is_array($menu['recipes'])) {
                            $recipesList    = $menu['recipes'];
                            $searchMenuValue = implode(', ', array_column($recipesList, 'name'));

                        // 2. selected_recipes as JSON string (fallback)
                        } elseif (!empty($menu['selected_recipes'])) {
                            $decoded = json_decode($menu['selected_recipes'], true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                $recipesList    = $decoded;
                                $searchMenuValue = implode(', ', array_column($recipesList, 'name'));
                            } else {
                                $searchMenuValue = $menu['selected_recipes'];
                            }

                        // 3. plain text search_menu (legacy)
                        } elseif (!empty($menu['search_menu'])) {
                            $searchMenuValue = $menu['search_menu'];
                        }

                        $timeSearchMenusArray[] = [
                            'time'        => $menu['time'] ?? '',
                            'search_menu' => $searchMenuValue,
                            'recipes'     => $recipesList ?? [],
                            'quantity'    => $menu['quantity'] ?? '',
                            'notes'       => $menu['notes'] ?? '',
                        ];
                    }
                }
            }

            Log::info('Processed time_search_menus array:', $timeSearchMenusArray);

            // Create diet plan
            $dietPlan = DietPlan::create([
                'branch_id' => $validated['branch_id'],
                'patient_id' => $validated['patient_id'],
                'patient_name' => $patientName,
                'date' => $validated['date'],
                'diet_name' => $validated['diet_name'],
                'time_search_menus' => !empty($timeSearchMenusArray) ? json_encode($timeSearchMenusArray) : null,
                'general_notes' => $validated['general_notes'] ?? null,
                'next_follow_up_date' => $validated['next_follow_up_date'] ?? null,
                'diet' => $validated['diet'] ?? null,
                'exercise' => $validated['exercise'] ?? null,
                'sleep' => $validated['sleep'] ?? null,
                'water' => $validated['water'] ?? null,
                'created_by' => Auth::id(),
            ]);

            Log::info('Diet plan created successfully for branch: ' . $validated['branch_id']);
            Log::info('Diet Plan ID: ' . $dietPlan->id);

            return response()->json([
                'success' => true,
                'message' => 'Diet plan created successfully!',
                'redirect' => route('diet.plan')
            ]);

        } catch (\Exception $e) {
            Log::error('Error storing diet plan: ' . $e->getMessage());
            Log::error('Error trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error creating diet plan: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Test function to check branch-patient mapping
     */
    public function testBranchPatients()
    {
        echo "<h1>Testing Branch-Patient Mapping</h1>";

        // Get all branches
        echo "<h3>Branches in branches table:</h3>";
        $branches = Branch::all();
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Branch ID</th><th>Branch Name</th></tr>";
        foreach ($branches as $branch) {
            echo "<tr><td>{$branch->branch_id}</td><td>{$branch->branch_name}</td></tr>";
        }
        echo "</table>";

        // Get all patients with their branch info
        echo "<h3>Patients in patient_inquiry table:</h3>";
        $patients = DB::table('patient_inquiry')
            ->select('id', 'patient_name', 'patient_id', 'branch_id', 'branch')
            ->limit(20)
            ->get();

        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Patient Name</th><th>Patient ID</th><th>Branch ID</th><th>Branch</th></tr>";
        foreach ($patients as $patient) {
            echo "<tr>
                    <td>{$patient->id}</td>
                    <td>{$patient->patient_name}</td>
                    <td>{$patient->patient_id}</td>
                    <td>{$patient->branch_id}</td>
                    <td>{$patient->branch}</td>
                  </tr>";
        }
        echo "</table>";

        return '';
    }

    public function edit($id)
    {
        try {
            $dietPlan = DietPlan::findOrFail($id);

            // Format dates as plain Y-m-d strings so the JS date input works correctly
            // (the 'date' cast serializes to ISO format which <input type="date"> can't parse)
            $data = $dietPlan->toArray();
            $data['date']               = $dietPlan->date ? $dietPlan->date->format('Y-m-d') : null;
            $data['next_follow_up_date'] = $dietPlan->next_follow_up_date
                ? $dietPlan->next_follow_up_date->format('Y-m-d')
                : null;

            // time_search_menus is cast as array — keep it as array for JSON response
            $data['time_search_menus'] = $dietPlan->time_search_menus ?? [];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching diet plan for edit: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Diet plan not found'
            ], 404);
        }
    }

    /**
     * Update the specified diet plan in storage.
     */
    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'diet_plan_id' => 'required|exists:diet_plans,id',
                'date' => 'required|date',
                'diet_name' => 'required|string|max:255',
                'general_notes' => 'nullable|string',
                'next_follow_up_date' => 'nullable|date',
                'diet' => 'nullable|string',
                'exercise' => 'nullable|string',
                'sleep' => 'nullable|string',
                'water' => 'nullable|string',
            ]);

            $dietPlan = DietPlan::findOrFail($validated['diet_plan_id']);

            // Process time_search_menus
            $timeSearchMenusArray = [];
            if ($request->has('time_search_menus')) {
                foreach ($request->time_search_menus as $index => $menu) {
                    $hasTime     = !empty($menu['time']);
                    $hasRecipes  = !empty($menu['recipes']) || !empty($menu['selected_recipes']) || !empty($menu['search_menu']);
                    $hasNotes    = !empty($menu['notes']);
                    $hasQuantity = !empty($menu['quantity']);

                    if ($hasTime || $hasRecipes || $hasNotes || $hasQuantity) {

                        $recipesList    = null;
                        $searchMenuValue = '';

                        // 1. recipes array sent directly from JSON payload (new JS path)
                        if (!empty($menu['recipes']) && is_array($menu['recipes'])) {
                            $recipesList    = $menu['recipes'];
                            $searchMenuValue = implode(', ', array_column($recipesList, 'name'));

                        // 2. selected_recipes as JSON string (fallback)
                        } elseif (!empty($menu['selected_recipes'])) {
                            $decoded = json_decode($menu['selected_recipes'], true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                $recipesList    = $decoded;
                                $searchMenuValue = implode(', ', array_column($recipesList, 'name'));
                            } else {
                                $searchMenuValue = $menu['selected_recipes'];
                            }

                        // 3. plain text search_menu (legacy)
                        } elseif (!empty($menu['search_menu'])) {
                            $searchMenuValue = $menu['search_menu'];
                        }

                        $timeSearchMenusArray[] = [
                            'time'             => $menu['time'] ?? '',
                            'search_menu'      => $searchMenuValue,
                            'selected_recipes' => $searchMenuValue,
                            'recipes'          => $recipesList ?? [],
                            'quantity'         => $menu['quantity'] ?? '',
                            'notes'            => $menu['notes'] ?? '',
                        ];
                    }
                }
            }

            // Update diet plan
            $dietPlan->update([
                'date' => $validated['date'],
                'diet_name' => $validated['diet_name'],
                'time_search_menus' => !empty($timeSearchMenusArray) ? json_encode($timeSearchMenusArray) : null,
                'general_notes' => $validated['general_notes'] ?? null,
                'next_follow_up_date' => $validated['next_follow_up_date'] ?? null,
                'updated_at' => now(),
                'diet' => $validated['diet'] ?? null,
                'exercise' => $validated['exercise'] ?? null,
                'sleep' => $validated['sleep'] ?? null,
                'water' => $validated['water'] ?? null,
            ]);

            \Log::info('Diet plan updated successfully:', [
                'id' => $dietPlan->id,
                'patient_id' => $dietPlan->patient_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Diet plan updated successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating diet plan: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating diet plan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified diet plan from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $validated = $request->validate([
                'diet_plan_id' => 'required|exists:diet_plans,id'
            ]);

            $dietPlan = DietPlan::findOrFail($validated['diet_plan_id']);
            $dietPlan->delete();

            \Log::info('Diet plan deleted successfully:', [
                'id' => $validated['diet_plan_id']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Diet plan deleted successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error deleting diet plan: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error deleting diet plan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function print($id)
    {
        try {
            $dietPlan = DietPlan::with(['branch'])->findOrFail($id);

            // Re-resolve patient details
            $pId = $dietPlan->patient_id;
            $patient = DB::table('patient_inquiry')->where('patient_id', $pId)->first()
                ?? DB::table('patient_inquiry')->where('id', $pId)->first()
                ?? DB::table('acc_inquirys')->where('patient_id', $pId)->first()
                ?? DB::table('acc_inquirys')->where('id', $pId)->first()
                ?? DB::table('lhr_inquiries')->where('patient_id', $pId)->first()
                ?? DB::table('hydra_inquiries')->where('patient_id', $pId)->first();

            // Resolve the AccInquiry numeric id for the "Return to Profile" link
            $accPatient = DB::table('acc_inquirys')->where('patient_id', $pId)->first()
                ?? DB::table('acc_inquirys')->where('id', $pId)->first();
            $profileId = $accPatient ? $accPatient->id : null;

            $menus = is_string($dietPlan->time_search_menus)
                ? json_decode($dietPlan->time_search_menus, true)
                : $dietPlan->time_search_menus;

            $allRecipes = Recipe::with(['ingredients.nutrition'])->get()->keyBy('name');

            return view('admin.diet.print_diet_plan', compact('dietPlan', 'patient', 'menus', 'allRecipes', 'profileId'));
        } catch (\Exception $e) {
            \Log::error('Error printing diet plan: ' . $e->getMessage());
            return back()->with('error', 'Diet plan not found.');
        }
    }
}