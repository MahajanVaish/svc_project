<?php

namespace App\Http\Controllers;

use App\Models\AccInquiry;
use App\Models\PatientInquiry;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    /**
     * Display follow-up patients
     */
        public function followup(Request $request)
        {
            // Get search parameters
            $searchName = $request->input('search_name');
            $perPage = $request->input('per_page', 10);

            // Query for follow-up patients from AccInquiry (excluding active Diet Chart & Joined patients)
            $query = AccInquiry::where(function ($q) {
                    $q->whereNull('delete_status')
                      ->orWhere('delete_status', '0');
                })
                ->whereNotNull('next_followup_date')
                ->where('next_followup_date', '>=', Carbon::today()->format('Y-m-d'))
                ->where(function ($q) {
                    $q->whereJsonDoesntContain('status_history', 'Diet Chart')
                      ->whereJsonDoesntContain('status_history', 'Joined')
                      ->where(function ($sub) {
                          $sub->whereNull('user_status')
                              ->orWhereNotIn('user_status', ['Diet Chart', 'Joined', 'Active']);
                      });
                })
                ->orderBy('next_followup_date', 'asc')
                ->orderBy('patient_f_name', 'asc');

            // Apply search filters
            if ($searchName) {
                $query->where(function($q) use ($searchName) {
                    $q->where('patient_f_name', 'like', '%' . $searchName . '%')
                      ->orWhere('patient_l_name', 'like', '%' . $searchName . '%')
                      ->orWhere('patient_m_name', 'like', '%' . $searchName . '%');
                });
            }

            // Get paginated results
            $followupPatients = $query->paginate($perPage);

            if ($request->ajax()) {
                return view('admin.inquiries.followup_table', compact('followupPatients'))->render();
            }

            return view('admin.inquiries.followup', compact('followupPatients'));
        }

    /**
     * Show the form for creating a new inquiry
     */
    public function create()
    {
        $user = auth()->user();
        $isSuperadmin = $user->hasRole('Superadmin') || $user->hasRole('Doctor');
        $userBranch = $user->user_branch;

        $branches = \App\Models\Branch::where(function ($q) {
                $q->where('delete_status', '0')
                  ->orWhere('delete_status', '')
                  ->orWhereNull('delete_status');
            })
            ->when(!$isSuperadmin, function ($q) use ($userBranch) {
                $q->where('branch_id', $userBranch);
            })
            ->get();

        return view('admin.inquiries.create', compact('branches'));
    }

    /** 
     * Store a newly created inquiry in storage
     */
    public function store(Request $request)
    {
        try {
            // Validation
            $validated = $request->validate([
                'branch' => 'nullable|string|max:255',
                'first_name' => 'required|string|max:100',
                'middle_name' => 'nullable|string|max:100',
                'last_name' => 'nullable|string|max:100',
                'gender' => 'nullable|string|in:male,female,other',
                'phone' => 'required|string|max:20',
                'email' => 'nullable|email|max:100',
                'age' => 'nullable|integer|min:0|max:150',
                'height' => 'nullable|numeric|min:0',
                'weight' => 'nullable|numeric|min:0',
                'bmi' => 'nullable|numeric|min:0',
                'address' => 'nullable|string',
                'reference_by' => 'nullable|string|max:255',
                'diet_plan' => 'nullable|string',
                'diagnosis' => 'nullable|string',
                't1s3_am' => 'nullable|string|max:100',
                'daytime' => 'nullable|string|max:100',
                'disc' => 'nullable|string',
                'inquiry_by' => 'nullable|string|max:255',
                'client' => 'nullable|string|max:100',
                'payment' => 'nullable|string|max:50',
                'foc' => 'nullable|boolean',
                'time' => 'nullable|string',
                'date' => 'nullable|date',
                'next_followup_date' => 'nullable|date|after_or_equal:today',
            ]);

            $branchId = $request->branch;
            $branchObj = \App\Models\Branch::where('branch_id', $branchId)->first();
            $branchName = $branchObj ? $branchObj->branch_name : $branchId;

            // Create patient inquiry
            $inquiry = new PatientInquiry();
            $inquiry->patient_name = trim($request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name);
            $inquiry->age = $request->age;
            $inquiry->address = $request->address;
            $inquiry->diagnosis = $request->diagnosis;
            $inquiry->branch = $branchName;
            $inquiry->branch_id = $branchId;
            $inquiry->inquiry_date = Carbon::now();
            $inquiry->status = 'pending'; // Default status

            // Generate patient ID if needed
            $lastPatient = PatientInquiry::orderBy('id', 'desc')->first();
            if ($lastPatient && strpos($lastPatient->patient_id, 'PP-') === 0) {
                $lastNumber = intval(substr($lastPatient->patient_id, 3));
                $newNumber = str_pad($lastNumber + 1, 8, '0', STR_PAD_LEFT);
                $inquiry->patient_id = 'PP-' . $newNumber;
            } else {
                $inquiry->patient_id = 'PP-00000001';
            }

            $inquiry->save();

            // Save meta data
            $inquiry->setMeta('first_name', $request->first_name);
            $inquiry->setMeta('middle_name', $request->middle_name);
            $inquiry->setMeta('last_name', $request->last_name);
            $inquiry->setMeta('phone', $request->phone);
            $inquiry->setMeta('email', $request->email);
            $inquiry->setMeta('gender', $request->gender);
            $inquiry->setMeta('height', $request->height);
            $inquiry->setMeta('weight', $request->weight);
            $inquiry->setMeta('bmi', $request->bmi);
            $inquiry->setMeta('reference_by', $request->reference_by);
            $inquiry->setMeta('diet_plan', $request->diet_plan);
            $inquiry->setMeta('t1s3_am', $request->t1s3_am);
            $inquiry->setMeta('daytime', $request->daytime);
            $inquiry->setMeta('disc', $request->disc);
            $inquiry->setMeta('inquiry_by', $request->inquiry_by);
            $inquiry->setMeta('client', $request->client);
            $inquiry->setMeta('payment', $request->payment);
            $inquiry->setMeta('cash_payment', $request->cash_payment);
            $inquiry->setMeta('gpay_payment', $request->gpay_payment);
            $inquiry->setMeta('cheque_payment', $request->cheque_payment);
            $inquiry->setMeta('given_payment', $request->given_payment);
            $inquiry->setMeta('foc', $request->has('foc') ? '1' : '0');
            $inquiry->setMeta('time', $request->time);
            $inquiry->setMeta('date', $request->date);
            $inquiry->setMeta('next_followup_date', $request->next_followup_date);
            
            // Detailed Lipid Profile
            $inquiry->setMeta('s_cholesterol', $request->s_cholesterol);
            $inquiry->setMeta('s_triglycerides', $request->s_triglycerides);
            $inquiry->setMeta('hdl', $request->hdl);
            $inquiry->setMeta('ldl', $request->ldl);
            $inquiry->setMeta('vldl', $request->vldl);
            $inquiry->setMeta('non_hdl_c', $request->non_hdl_c);
            $inquiry->setMeta('chol_hdl_ratio', $request->chol_hdl_ratio);

            return redirect()->route('followup.patients.appointment')
                ->with('success', 'New inquiry added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error adding inquiry: ' . $e->getMessage());
        }
    }

    /**
     * Update follow-up date for a patient
     */
    public function updateFollowupDate(Request $request)
    {
        try {
            $validated = $request->validate([
                'patient_id' => 'required|exists:acc_inquirys,id',
                'next_followup_date' => 'nullable|date',
            ]);

            $patient = AccInquiry::findOrFail($validated['patient_id']);
            
            $patient->next_followup_date = $validated['next_followup_date'] ?: null;
            $patient->save();

            return response()->json([
                'success' => true,
                'message' => $validated['next_followup_date'] 
                    ? 'Follow-up date updated successfully!' 
                    : 'Follow-up date removed successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating follow-up date: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display joined patients
     */
    public function joined(Request $request)
    {
        // Simple method to just return the view
        return view('admin.inquiries.joined');
    }

    /**
     * Display pending inquiries
     */
    public function pending(Request $request)
    {
        // Show inquiries that have 'Pending' but NOT 'Diet Chart' or 'Joined', and are NOT online/abroad
        $query = AccInquiry::where('delete_status', '0')
                            ->whereJsonContains('status_history', 'Pending')
                            ->whereJsonDoesntContain('status_history', 'Diet Chart')
                            ->whereJsonDoesntContain('status_history', 'Joined')
                            ->where(function ($q) {
                                $q->where('is_online_abroad', '!=', 1)
                                  ->orWhereNull('is_online_abroad');
                            });
    
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('patient_id', 'like', '%' . $search . '%')
                  ->orWhere('patient_f_name', 'like', '%' . $search . '%')
                  ->orWhere('phone_no', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%')
                  ->orWhere('diagnosis', 'like', '%' . $search . '%');
            });
        }
    
        $inquiries = $query->orderBy('id', 'desc')->paginate(10);
    
        return view('admin.inquiry.pending_inquiry', compact('inquiries'));
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
 
}
