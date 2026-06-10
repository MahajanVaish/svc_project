<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HydraFollowUp;
use App\Models\HydraInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HydraEnquiriesExport;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\PatientTransaction;

class HydraController extends Controller
{
    
    public function pending(Request $request)
    {
        try {
           
            $query = HydraInquiry::where('status_name', 'pending');

          
            if ($request->has('search') && $request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('patient_name', 'like', '%' . $request->search . '%')
                      ->orWhere('patient_id', 'like', '%' . $request->search . '%')
                      ->orWhere('phone_number', 'like', '%' . $request->search . '%');
                });
            }
                
      
            $perPage = $request->per_page ?? 10;

            $inquiries = $query->orderBy('id', 'desc')
                ->paginate($perPage);

            return view('admin.hydra.pending', [
                'title' => 'Hydra Pending Patient Inquiry',
                'inquiries' => $inquiries
            ]);
        } catch (\Exception $e) {
            Log::error('Error in HydraController pending method: ' . $e->getMessage());

            return view('admin.hydra.pending', [
                'title' => 'Hydra Pending Patient Inquiry',
                'inquiries' => collect()
            ]);
        }
    }

 
    public function joined(Request $request)
    {
        try {
        
            $query = HydraInquiry::where('status_name', 'joined');

           
            if ($request->has('search') && $request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('patient_name', 'like', '%' . $request->search . '%')
                      ->orWhere('patient_id', 'like', '%' . $request->search . '%')
                      ->orWhere('phone_number', 'like', '%' . $request->search . '%');
                });
            }

      
            $perPage = $request->per_page ?? 5;

       
            $inquiries = $query->orderBy('id', 'desc')
                ->paginate($perPage);

            return view('admin.hydra.joined', [
                'title' => 'Hydra Joined Patient',
                'inquiries' => $inquiries
            ]);
        } catch (\Exception $e) {
            Log::error('Error in HydraController joined method: ' . $e->getMessage());

            return view('admin.hydra.joined', [
                'title' => 'Hydra Joined Patient',
                'inquiries' => collect()
            ]);
        }
    }


    /**
     * Show form to add new inquiry
     */
    public function addInquiry()
    {
        $accUser = User::where('email', auth()->user()->email)->first();
        // dd($accUser);
        if (!$accUser) {
            dd("ACC user not found");
        }
        $branches = Branch::all(); 

        $branchName = optional($accUser->branch)->branch_name;

        $branchId = auth()->user()->user_branch;

        return view('admin.hydra.add-inquiry', compact(
            'branches',
            'branchName',
            'branchId'
        ))->with('title', 'Add New Inquiry');
    }


public function storeInquiry(Request $request)
{
    // Validate request
    $validator = Validator::make($request->all(), [
        'patient_name' => 'required|string|max:255',
        'inquiry_date' => 'required|date',
        'address' => 'nullable|string',
        'inquiry_time' => 'nullable|date_format:H:i',
        'phone_number' => 'nullable|string|max:20',
        'gender' => 'required|in:male,female,other',
        'age' => 'required|integer|min:1|max:120',
        'reference_by' => 'nullable|string|max:255',
        'session' => 'nullable|string|max:100',
        'next_follow_up' => 'nullable|date',
        'foc' => 'nullable|boolean',
        'total_payment' => 'nullable|numeric|min:0',
        'given_payment' => 'nullable|numeric|min:0',
        'due_payment' => 'nullable|numeric|min:0',
        'payment_method' => 'nullable|in:Cash,Online,Cheque',
        'status_name' => 'required|in:pending,joined',
        'branch_id' => 'required|string',
        'diet' => 'nullable|string|max:255',
        'exercise' => 'nullable|string|max:255',
        'sleep' => 'nullable|string|max:255',
        'water' => 'nullable|string|max:255',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    DB::beginTransaction();

    try {
        // ✅ Get branch information from request
        $branchId = $request->branch_id; 
        $branch = Branch::where('branch_id', $branchId)->first();
        
        if (!$branch) {
            // Fallback for safety - fetch the first Hydra-like branch
            $branch = Branch::where('branch_id', 'BH-00023')->first() ?: Branch::where('branch_name', 'like', '%HYDRA%')->first();
            if ($branch) $branchId = $branch->branch_id;
        }

        if (!$branch) {
            throw new \Exception("Branch not found for ID: $branchId");
        }

        // Use branch name for prefixing (e.g., "BD HYDRA")
        $branchName = $branch->branch_name;
        $branchPrefix = trim($branchName);
        
        // AUTO-GENERATE PATIENT ID WITH YOUR LOGIC:
        // Find the maximum number for this branch (without deleted records)
        $maxNumber = HydraInquiry::where('branch', $branchPrefix)
            ->where('patient_id', 'LIKE', $branchPrefix . '-%')
            ->lockForUpdate()
            ->max(DB::raw('CAST(SUBSTRING(patient_id, LOCATE("-", patient_id) + 1) AS UNSIGNED)'));
        
        // Calculate next number
        $nextNumber = $maxNumber ? (int)$maxNumber + 1 : 1;
        
        // Generate patient ID with "0000" prefix and then the number
        $patientId = $branchPrefix . '-' . '0000' . $nextNumber;

        // Calculate due payment if not provided
        $duePayment = $request->due_payment;
        if (!$request->has('due_payment') || $request->due_payment === null) {
            $total = $request->total_payment ?? 0;
            $given = $request->given_payment ?? 0;
            $duePayment = $total - $given;
            $duePayment = max(0, $duePayment);
        }

        // Determine payment mode from payment method dropdown
        $paymentMode = $request->payment_method ?: null;

        // Handle FOC (Free of Cost) logic
        $foc = $request->has('foc') ? true : false;
        $total = $request->total_payment ?? 0;
        $given = $request->given_payment ?? 0;
        
        // If FOC is true, set all payments to 0
        if ($foc) {
            $total = 0;
            $given = 0;
            $duePayment = 0;
        }

        // Create inquiry
        $inquiry = HydraInquiry::create([
            // Patient ID, Branch, and Branch ID from the logic above
            'patient_id' => $patientId,
            'branch_id' => $branchId,
            'branch' => $branchPrefix,
            
            // Patient Info
            'patient_name' => $request->patient_name,
            'inquiry_date' => $request->inquiry_date,
            'address' => $request->address,
            'inquiry_time' => $request->inquiry_time,
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
            'age' => $request->age,
            'reference_by' => $request->reference_by,
            'session' => $request->input('session'),
            'next_follow_up' => $request->next_follow_up,
            
            // Payment Info
            'foc' => $foc,
            'total_payment' => $total,
            'given_payment' => $given,
            'due_payment' => $duePayment,
            'payment_mode' => $foc ? 'FOC' : ($request->payment_method ?: null),
            
            // Status
            'status_name' => $request->status_name,
            'diet' => $request->diet,
            'exercise' => $request->exercise,
            'sleep' => $request->sleep,
            'water' => $request->water,
        ]);

        // Create/Update Invoice and Transactions if payment is set
        if ($total > 0 && $branch) {
            $this->createHydraInvoice($inquiry, $branch, $total, $given, $paymentMode, $duePayment);
        }

        DB::commit();

        // Set success message based on status
        $message = $request->status_name === 'joined'
            ? 'Patient added to joined list successfully!'
            : 'Inquiry added to pending list successfully!';

        // Redirect based on status
        $redirectRoute = $request->status_name === 'joined' ? 'hydra.joined' : 'hydra.pending';

        return redirect()->route($redirectRoute)
            ->with('success', $message);
            
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error storing hydra inquiry: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Error adding inquiry: ' . $e->getMessage())
            ->withInput();
    }
}

    public function edit($id)
    {
        try {
            $inquiry = HydraInquiry::findOrFail($id);

            return view('admin.hydra.edit-inquiry', [
                'title' => 'Edit Inquiry',
                'inquiry' => $inquiry
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching inquiry for edit: ' . $e->getMessage());

            $redirectRoute = request()->has('redirect') && request('redirect') == 'joined'
                ? 'hydra.joined'
                : 'hydra.pending';

            return redirect()->route($redirectRoute)
                ->with('error', 'Inquiry not found');
        }
    }

    /**
     * Update inquiry
     */
    public function update(Request $request, $id)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'patient_name' => 'required|string|max:255',
            'inquiry_date' => 'required|date',
            'address' => 'nullable|string',
            'inquiry_time' => 'nullable|date_format:H:i',
            'phone_number' => 'nullable|string|max:20',
            'gender' => 'required|in:male,female,other',
            'age' => 'required|integer|min:1|max:120',
            'reference_by' => 'nullable|string|max:255',
            'session' => 'nullable|string|max:100',
            'next_follow_up' => 'nullable|date',
            'foc' => 'nullable|boolean',
            'total_payment' => 'nullable|numeric|min:0',
            'given_payment' => 'nullable|numeric|min:0',
            'due_payment' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:Cash,Online,Cheque',
            'status_name' => 'required|in:pending,joined',
            'diet' => 'nullable|string|max:255',
            'exercise' => 'nullable|string|max:255',
            'sleep' => 'nullable|string|max:255',
            'water' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $inquiry = HydraInquiry::findOrFail($id);

            // Calculate due payment if not provided
            $duePayment = $request->due_payment;
            if (!$request->has('due_payment') || $request->due_payment === null) {
                $total = $request->total_payment ?? 0;
                $given = $request->given_payment ?? 0;
                $duePayment = $total - $given;
                $duePayment = max(0, $duePayment);
            }

            // Determine payment mode from payment method dropdown
            $paymentMode = $request->payment_method ?: null;

            // Update inquiry
            $inquiry->update([
                'patient_name' => $request->patient_name,
                'inquiry_date' => $request->inquiry_date,
                'address' => $request->address,
                'inquiry_time' => $request->inquiry_time,
                'phone_number' => $request->phone_number,
                'gender' => $request->gender,
                'age' => $request->age,
                'reference_by' => $request->reference_by,
                'session' => $request->input('session'),
                'next_follow_up' => $request->next_follow_up,
                'foc' => $request->has('foc') ? true : false,
                'total_payment' => $request->total_payment ?? 0,
                'given_payment' => $request->given_payment ?? 0,
                'due_payment' => $duePayment ?? 0,
                'payment_mode' => $request->has('foc') ? 'FOC' : ($request->payment_method ?: null),
                'status_name' => $request->status_name,
                'diet' => $request->diet,
                'exercise' => $request->exercise,
                'sleep' => $request->sleep,
                'water' => $request->water,
            ]);

            // Create/Update Invoice and Transactions if payment is set
            $total = $request->total_payment ?? 0;
            if ($total > 0 || $request->has('foc')) {
                $branch = Branch::where('branch_id', $inquiry->branch_id)->first();
                if ($branch) {
                    $this->createHydraInvoice($inquiry, $branch, $total, $request->given_payment ?? 0, $request->payment_method, $duePayment ?? 0);
                }
            }

            // Set success message
            $message = 'Inquiry updated successfully!';

            // Redirect based on status
            $redirectRoute = $request->status_name === 'joined' ? 'hydra.joined' : 'hydra.pending';

            return redirect()->route($redirectRoute)
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error updating hydra inquiry: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error updating inquiry: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Move pending inquiry to joined
     */
    public function moveToJoined($id)
    {
        try {
            $inquiry = HydraInquiry::findOrFail($id);
            $inquiry->update(['status_name' => 'joined']);

            return redirect()->route('hydra.joined')
                ->with('success', 'Patient moved to joined list successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error moving patient: ' . $e->getMessage());
        }
    }

    /**
     * Move joined inquiry back to pending
     */
    public function moveToPending($id)
    {
        try {
            $inquiry = HydraInquiry::findOrFail($id);
            $inquiry->update(['status_name' => 'pending']);

            return redirect()->route('hydra.pending')
                ->with('success', 'Patient moved to pending list successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error moving patient: ' . $e->getMessage());
        }
    }

    /**
     * Delete inquiry
     */
    public function destroy(Request $request, $id)
    {
        try {
            $inquiry = HydraInquiry::findOrFail($id);
            $status = $inquiry->status_name;
            $inquiry->delete();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Inquiry deleted successfully',
                    'status' => $status
                ]);
            }

            $redirectRoute = $status === 'joined' ? 'hydra.joined' : 'hydra.pending';
            return redirect()->route($redirectRoute)
                ->with('success', 'Inquiry deleted successfully');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting inquiry: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error deleting inquiry: ' . $e->getMessage());
        }
    }

    public function createFollowUp($id)
    {
        try {
            $inquiry = HydraInquiry::findOrFail($id);

            return view('admin.hydra.create-followup', [
                'title' => 'Add Follow Up - ' . $inquiry->patient_name,
                'inquiry' => $inquiry
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching inquiry for follow up: ' . $e->getMessage());

            return redirect()->route('hydra.pending')
                ->with('error', 'Inquiry not found');
        }
    }

    /**
     * Store new follow up
     */
    public function storeFollowUp(Request $request, $id)
    {

        // Validate request
        $validator = Validator::make($request->all(), [
            'follow_up_date' => 'required|date',
            'follow_up_time' => 'nullable|date_format:H:i',
            'patient_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'age' => 'required|integer|min:1|max:120',
            'next_follow_up_date' => 'nullable|date',
            'foc' => 'nullable|boolean',
            'total_payment' => 'nullable|numeric|min:0',
            'discount_payment' => 'nullable|numeric|min:0',
            'given_payment' => 'nullable|numeric|min:0',
            'cash_payment' => 'nullable|numeric|min:0',
            'google_pay' => 'nullable|numeric|min:0',
            'phone_number' => 'nullable|string|max:20',
            'session' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $inquiry = HydraInquiry::findOrFail($id);

            // Calculate due payment
            $total = $request->total_payment ?? 0;
            $discount = $request->discount_payment ?? 0;
            $given = $request->given_payment ?? 0;
            $duePayment = ($total - $discount) - $given;
            $duePayment = max(0, $duePayment);

            // Create follow up
            $followUp = HydraFollowUp::create([
                'hydra_inquiry_id' => $id,
                'follow_up_date' => $request->follow_up_date,
                'follow_up_time' => $request->follow_up_time,
                'patient_name' => $request->patient_name,
                'gender' => $request->gender,
                'age' => $request->age,
                'next_follow_up_date' => $request->next_follow_up_date,
                'foc' => $request->has('foc') ? true : false,
                'total_payment' => $total,
                'discount_payment' => $discount,
                'given_payment' => $given,
                'due_payment' => $duePayment,
                'cash_payment' => $request->cash_payment ?? 0,
                'google_pay' => $request->google_pay ?? 0,
                'phone_number' => $request->phone_number,
                'session' => $request->input('session'),
                'address' => $request->address,
                'notes' => $request->notes
            ]);

            // Create/Update Invoice and Transactions if payment is set
            if ($total > 0 && !$followUp->foc) {
                $this->createHydraFollowupInvoice($inquiry, $followUp, $total, $given, $duePayment);
            }

            return redirect()->route('hydra.patient.profile', $id)
                ->with('success', 'Follow up added successfully!');
        } catch (\Exception $e) {
            Log::error('Error storing follow up: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error adding follow up: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function showPatientProfile($id)
    {
        try {
            // Get inquiry
            $inquiry = HydraInquiry::findOrFail($id);

            // Get follow ups from new table
            $followUps = HydraFollowUp::where('hydra_inquiry_id', $id)
                ->orderBy('follow_up_date', 'desc')
                ->orderBy('follow_up_time', 'desc')
                ->get();

            return view('admin.hydra.patient-profile', [
                'title' => 'Patient Profile - ' . $inquiry->patient_name,
                'inquiry' => $inquiry,
                'followUps' => $followUps
            ]);
        } catch (\Exception $e) {
            Log::error('Error in showPatientProfile: ' . $e->getMessage());

            // If table doesn't exist, show profile without follow ups
            $inquiry = HydraInquiry::find($id);
            if ($inquiry) {
                return view('admin.hydra.patient-profile', [
                    'title' => 'Patient Profile - ' . $inquiry->patient_name,
                    'inquiry' => $inquiry,
                    'followUps' => collect() // Empty collection
                ]);
            }

            return redirect()->route('hydra.pending')
                ->with('error', 'Patient not found');
        }
    }

    /**
     * Update the patient's profile image.
     */
    public function updateProfileImage(Request $request, $id)
    {
        try {
            $patient = HydraInquiry::findOrFail($id);

            $request->validate([
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('profile_image')) {
                // Delete old image if exists
                $oldImage = $patient->profile_image;
                if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }

                $image = $request->file('profile_image');
                $filename = 'hydra_patient_' . $id . '_' . time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('hydra/profiles', $filename, 'public');

                $patient->update(['profile_image' => $path]);

                return back()->with('success', 'Profile image updated successfully.');
            }

            return back()->with('error', 'No image file provided.');
        } catch (\Exception $e) {
            Log::error('Error updating Hydra profile image: ' . $e->getMessage());
            return back()->with('error', 'Error updating profile image: ' . $e->getMessage());
        }
    }

    /**
     * Export filtered pending data to Excel
     */
    public function exportPending(Request $request)
    {
        try {
            // Get pending inquiries with filters
            $query = HydraInquiry::where('status_name', 'pending');

            // Apply search filter if present
            if ($request->has('search') && $request->search) {
                $query->where('patient_name', 'like', '%' . $request->search . '%');
            }

            $inquiries = $query->orderBy('created_at', 'desc')
                ->get();

            if ($inquiries->isEmpty()) {
                return redirect()->route('hydra.pending')
                    ->with('error', 'No data to export');
            }

            $hasFilters = $request->has('search');

            $filename = 'Hydra_Pending_Patients_' . date('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(new HydraEnquiriesExport($inquiries, 'pending', $hasFilters), $filename);
        } catch (\Exception $e) {
            Log::error('Export pending error: ' . $e->getMessage());
            return redirect()->route('hydra.pending')
                ->with('error', 'Error exporting data: ' . $e->getMessage());
        }
    }

    /**
     * Export all pending data to Excel
     */
    public function exportAllPending()
    {
        try {
            $inquiries = HydraInquiry::where('status_name', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();

            if ($inquiries->isEmpty()) {
                return redirect()->route('hydra.pending')
                    ->with('error', 'No pending patients to export');
            }

            $filename = 'Hydra_All_Pending_Patients_' . date('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(new HydraEnquiriesExport($inquiries, 'pending', false), $filename);
        } catch (\Exception $e) {
            Log::error('Export all pending error: ' . $e->getMessage());
            return redirect()->route('hydra.pending')
                ->with('error', 'Error exporting data: ' . $e->getMessage());
        }
    }

    /**
     * Export filtered joined data to Excel
     */
    public function exportJoined(Request $request)
    {
        try {
            // Get joined inquiries with filters
            $query = HydraInquiry::where('status_name', 'joined');

            // Apply search filter if present
            if ($request->has('search') && $request->search) {
                $query->where('patient_name', 'like', '%' . $request->search . '%');
            }

            $inquiries = $query->orderBy('created_at', 'desc')
                ->get();

            if ($inquiries->isEmpty()) {
                return redirect()->route('hydra.joined')
                    ->with('error', 'No data to export');
            }

            $hasFilters = $request->has('search');

            $filename = 'Hydra_Joined_Patients_' . date('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(new HydraEnquiriesExport($inquiries, 'joined', $hasFilters), $filename);
        } catch (\Exception $e) {
            Log::error('Export joined error: ' . $e->getMessage());
            return redirect()->route('hydra.joined')
                ->with('error', 'Error exporting data: ' . $e->getMessage());
        }
    }

    /**
     * Export all joined data to Excel
     */
    public function exportAllJoined()
    {
        try {
            $inquiries = HydraInquiry::where('status_name', 'joined')
                ->orderBy('created_at', 'desc')
                ->get();

            if ($inquiries->isEmpty()) {
                return redirect()->route('hydra.joined')
                    ->with('error', 'No joined patients to export');
            }

            $filename = 'Hydra_All_Joined_Patients_' . date('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(new HydraEnquiriesExport($inquiries, 'joined', false), $filename);
        } catch (\Exception $e) {
            Log::error('Export all joined error: ' . $e->getMessage());
            return redirect()->route('hydra.joined')
                ->with('error', 'Error exporting data: ' . $e->getMessage());
        }
    }
    /**
     * Helper to create or update invoice and transactions for Hydra inquiries.
     */
    private function createHydraInvoice($inquiry, $branch, $totalPayment, $givenPayment, $paymentMethod, $duePayment)
    {
        $invoiceNo = 'INV-HYD-' . $inquiry->patient_id;
        
        // Use existing invoice if available
        $invoice = Invoice::where('patient_id', $inquiry->id)
            ->where('invoice_no', 'NOT LIKE', 'INV-FOL-%')
            ->first();

        if ($invoice) {
            $invoice->update([
                'price' => $totalPayment,
                'total_payment' => $totalPayment,
                'given_payment' => $givenPayment,
                'due_payment' => $duePayment,
                'branch_id' => $branch->branch_id,
            ]);
            
            // Update Transactions
            PatientTransaction::where('invoice_id', $invoice->id)->where('type', 'debit')->update(['amount' => $totalPayment]);
            
            // Remove old credit transactions for this invoice and add new one
            PatientTransaction::where('invoice_id', $invoice->id)->where('type', 'credit')->delete();
            
            if ($givenPayment > 0) {
                PatientTransaction::create([
                    'branch_id' => $branch->branch_id,
                    'patient_id' => $inquiry->id,
                    'invoice_id' => $invoice->id,
                    'type' => 'credit',
                    'amount' => $givenPayment,
                    'description' => 'Hydra Service Payment Received (' . ($paymentMethod ?? 'Cash') . ') for Invoice: ' . $invoice->invoice_no
                ]);
            }
        } else {
            // Check for duplicate invoice number
            $counter = 1;
            $finalInvoiceNo = $invoiceNo;
            while (Invoice::where('invoice_no', $finalInvoiceNo)->exists()) {
                $finalInvoiceNo = $invoiceNo . '-' . $counter;
                $counter++;
            }

            // Generate Filename
            $pNameClean = preg_replace('/[^A-Za-z0-9]/', '', $inquiry->patient_name ?? 'Patient');
            $bNameClean = preg_replace('/[^A-Za-z0-9]/', '', $branch->branch_name ?? 'Branch');
            $invoiceFile = $pNameClean . $bNameClean . '-' . $finalInvoiceNo . '-' . now()->format('d-m-Y') . '.pdf';

            $chargesData = [[
                'charge_id' => null,
                'charge_name' => 'Hydra Service',
                'price' => $totalPayment
            ]];

            $invoice = Invoice::create([
                'branch_id' => $branch->branch_id,
                'patient_id' => $inquiry->id,
                'invoice_no' => $finalInvoiceNo,
                'invoice_date' => now()->format('Y-m-d'),
                'address' => $inquiry->address,
                'phone' => $inquiry->phone_number,
                'price' => $totalPayment,
                'total_payment' => $totalPayment,
                'given_payment' => $givenPayment,
                'due_payment' => $duePayment,
                'invoice_file' => $invoiceFile,
                'charges_data' => $chargesData,
            ]);

            // Debit Transaction
            PatientTransaction::create([
                'branch_id' => $branch->branch_id,
                'patient_id' => $inquiry->id,
                'invoice_id' => $invoice->id,
                'type' => 'debit',
                'amount' => $totalPayment,
                'description' => 'Hydra Service Charges Generated: ' . $invoice->invoice_no,
            ]);

            // Credit Transaction
            if ($givenPayment > 0) {
                PatientTransaction::create([
                    'branch_id' => $branch->branch_id,
                    'patient_id' => $inquiry->id,
                    'invoice_id' => $invoice->id,
                    'type' => 'credit',
                    'amount' => $givenPayment,
                    'description' => 'Hydra Service Payment Received (' . ($paymentMethod ?? 'Cash') . ') for Invoice: ' . $invoice->invoice_no,
                ]);
            }
        }
    }
    /**
     * Helper to create or update invoice and transactions for Hydra followups.
     */
    private function createHydraFollowupInvoice($inquiry, $followup, $totalPayment, $givenPayment, $duePayment)
    {
        $invoiceNo = 'INV-FOL-HYD-' . $inquiry->patient_id . '-' . time();
        
        $branchId = $inquiry->branch_id;
        $branch = Branch::where('branch_id', $branchId)->first();

        // Create Invoice
        $invoice = Invoice::create([
            'branch_id' => $branchId,
            'patient_id' => $inquiry->id,
            'invoice_no' => $invoiceNo,
            'invoice_date' => $followup->follow_up_date,
            'address' => $inquiry->address,
            'phone' => $inquiry->phone_number,
            'price' => $totalPayment,
            'total_payment' => $totalPayment,
            'given_payment' => $givenPayment,
            'due_payment' => $duePayment,
            'charges_data' => [[
                'charge_name' => 'Hydra Followup Service',
                'amount' => $totalPayment,
                'price' => $totalPayment
            ]],
        ]);

        // Debit Transaction
        PatientTransaction::create([
            'branch_id' => $branchId,
            'patient_id' => $inquiry->id,
            'invoice_id' => $invoice->id,
            'type' => 'debit',
            'amount' => $totalPayment,
            'description' => 'Hydra Followup Service Charges Generated - Invoice: ' . $invoiceNo,
            'created_at' => now(),
        ]);

        // Credit Transaction
        if ($givenPayment > 0) {
            PatientTransaction::create([
                'branch_id' => $branchId,
                'patient_id' => $inquiry->id,
                'invoice_id' => $invoice->id,
                'type' => 'credit',
                'amount' => $givenPayment,
                'description' => 'Hydra Followup Payment - Invoice: ' . $invoiceNo,
                'created_at' => now(),
            ]);
        }
    }
}
