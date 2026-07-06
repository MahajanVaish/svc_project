<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LHREnquiriesExport;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\LHRInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\FollowUp; // Add this line
use App\Models\LhrFollowup;
use App\Models\ManageProgram;
use App\Models\User;
use App\Models\Invoice;
use App\Models\PatientTransaction;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LHRController extends Controller
{
    /**
     * Display LHR Pending Patients page
     */
    public function pending(Request $request)
    {
        // Get pending inquiries only
        $query = LHRInquiry::pending();

        // Global Search
        if ($request->has('search') && $request->search) {
            $query->globalSearch($request->search);
        }

        // Filter by follow up date
        if ($request->has('follow_up_date') && $request->follow_up_date) {
            $query->whereDate('next_follow_up', $request->follow_up_date);
        }

        // Get per page value
        $perPage = $request->per_page ?? 5;

        // Get inquiries with pagination
        $inquiries = $query->orderBy('next_follow_up', 'asc')
            ->latest()
            ->paginate($perPage);

        return view('admin.lhr.pending-patients', [
            'title' => 'LHR Pending Patient',
            'inquiries' => $inquiries
        ]);
    }

    /**
     * Display LHR Joined Patients page
     */
    public function joined(Request $request)
    {
        // Get joined inquiries only
        $query = LHRInquiry::joined();

        // Global Search
        if ($request->has('search') && $request->search) {
            $query->globalSearch($request->search);
        }

        // Filter by join date
        if ($request->has('join_date') && $request->join_date) {
            $query->whereDate('created_at', $request->join_date);
        }

        // Get per page value
        $perPage = $request->per_page ?? 5;

        // Get inquiries with pagination
        $inquiries = $query->latest()
            ->paginate($perPage);

        return view('admin.lhr.joined', [
            'title' => 'LHR Joined Patient',
            'inquiries' => $inquiries
        ]);
    }

    /**
     * Show form to add new inquiry
     */
    public function addInquiry()
    {
        $accUser = User::where('email', auth()->user()->email)->first();

        if (!$accUser) {
            dd("ACC user not found");
        }

        $branches = Branch::all();
        $branchName = optional($accUser->branch)->branch_name;
        $branchId = auth()->user()->user_branch;
        $programs = ManageProgram::where('delete_status', 0)
            ->whereIn('branch', ['LHR', 'ALL'])
            ->get();

        return view('admin.lhr.add-inquiry', compact(
            'branches',
            'branchName',
            'branchId',
            'programs'
        ))->with('title', 'Add New Inquiry');
    }




    public function storeInquiry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_name' => 'required|string|max:255',
            'mobile_no' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'inquiry_date' => 'required|date',
            'address' => 'nullable|string|max:500',

            // Gender & Basic Info
            'gender' => 'required|in:male,female,other',
            'age' => 'required|integer|min:1|max:120',
            'year' => 'nullable|string',
            'area' => 'required_if:status_name,joined|array',
            'area.*' => 'nullable|array',
            'session' => 'required_if:status_name,joined|array',
            'session.*' => 'nullable|numeric',
            'area_code' => 'nullable|array',
            'area_code.*' => 'nullable|string',
            'energy' => 'nullable|array',
            'energy.*' => 'nullable|string',
            'frequency' => 'nullable|array',
            'frequency.*' => 'nullable|string',
            'shot' => 'nullable|array',
            'shot.*' => 'nullable|string',
            'staff_name' => 'nullable|string',
            'status_name' => 'required|in:pending,joined',

            // Medical Questions
            'hormonal_issues' => 'required|in:yes,no',
            'medication' => 'required|in:yes,no',
            'previous_treatment' => 'required|in:yes,no',
            'pcod_thyroid' => 'required|in:yes,no',
            'skin_conditions' => 'required|in:yes,no',
            'ongoing_treatments' => 'required|in:yes,no',
            'implants_tattoos' => 'required|in:yes,no',

            // Procedures
            'procedure' => 'nullable|array',
            'procedure.*' => 'string|in:waxing,threading,cream',

            // Reference Information
            'reference_by' => 'nullable|string|max:255',
            'next_follow_up' => 'nullable|date',
            'notes' => 'nullable|string',

            // Payments
            'foc' => 'nullable|boolean',
            'total_payment' => 'nullable|numeric|min:0',
            'discount_payment' => 'nullable|numeric|min:0',
            'given_payment' => 'nullable|numeric|min:0',
            'due_payment' => 'nullable|numeric|min:0',
            'cash_payment' => 'nullable|numeric|min:0',
            'gp_payment' => 'nullable|numeric|min:0',
            'cheque_payment' => 'nullable|numeric|min:0',

            // Files
            'before_picture_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'before_picture_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'before_picture_3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'before_picture_4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'before_picture_5' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'after_picture_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'after_picture_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'after_picture_3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'after_picture_4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'after_picture_5' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // Account + Time
            'account' => 'nullable|string|max:100',
            'time' => 'nullable|date_format:H:i',
            'diet' => 'nullable|string|max:255',
            'exercise' => 'nullable|string|max:255',
            'sleep' => 'nullable|string|max:255',
            'water' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            // ✅ Get branch information
            $branchId = $request->branch_id;  // Assuming format like "LHR BD-0001"


            $branchName = explode('-', $branchId)[0];  // LHR BD


            $branchName = trim($branchName);

            $maxNumber = LHRInquiry::withTrashed()
                ->where('branch', $branchName)
                ->where('patient_id', 'LIKE', $branchName . '-%')
                ->lockForUpdate()
                ->max(DB::raw('CAST(SUBSTRING(patient_id, LOCATE("-", patient_id) + 1) AS UNSIGNED)'));


            $nextNumber = $maxNumber ? (int) $maxNumber + 1 : 1;


            $patientId = $branchName . '-' . '0000' . $nextNumber;

            // FILE UPLOADS
            // Handle all before pictures
            $beforePicturePaths = [];
            for ($i = 1; $i <= 5; $i++) {
                $fieldName = "before_picture_{$i}";
                if ($request->hasFile($fieldName)) {
                    $beforePicturePaths[$fieldName] = $request->file($fieldName)
                        ->store('lhr/before_pictures', 'public');
                } else {
                    $beforePicturePaths[$fieldName] = null;
                }
            }

            // Handle all after pictures
            $afterPicturePaths = [];
            for ($i = 1; $i <= 5; $i++) {
                $fieldName = "after_picture_{$i}";
                if ($request->hasFile($fieldName)) {
                    $afterPicturePaths[$fieldName] = $request->file($fieldName)
                        ->store('lhr/after_pictures', 'public');
                } else {
                    $afterPicturePaths[$fieldName] = null;
                }
            }

            // PAYMENTS
            $total = $request->total_payment ? floatval($request->total_payment) : 0; // Use provided value or default to 0
            $discount = $request->discount_payment ?? 0;
            $cash = $request->cash_payment ? floatval($request->cash_payment) : 0;
            $gpay = $request->gp_payment ? floatval($request->gp_payment) : 0;
            $cheque = $request->cheque_payment ? floatval($request->cheque_payment) : 0;
            $given = $cash + $gpay + $cheque;
            $due = $request->due_payment ? floatval($request->due_payment) : max(0, ($total - $discount) - $given); // Use calculated due from form or recalculate

            $foc = $request->has('foc');
            $procedureJson = $request->has('procedure')
                ? json_encode($request->procedure)
                : null;

            $status = $request->status_name;

            // Handle treatment taken flag - Check if any treatments were taken today
            $isTreatmentTaken = $request->input('is_treatment_taken'); // Array of indices

            // SAVE INQUIRY
            $inquiry = LHRInquiry::create([
                'patient_id' => $patientId,
                'branch_id' => $branchId,
                'branch' => $branchName,

                // Patient Info
                'patient_name' => $request->patient_name,
                'mobile_no' => $request->mobile_no,
                'email' => $request->email,
                'inquiry_date' => $request->inquiry_date,
                'address' => $request->address,

                // Basic info
                'gender' => $request->gender,
                'age' => $request->age,
                'year' => $request->year,
                'area' => json_encode($request->input('area')),
                'session' => json_encode($request->input('session')),
                'area_code' => json_encode($request->input('area_code')),
                'energy' => json_encode($request->input('energy')),
                'frequency' => json_encode($request->input('frequency')),
                'shot' => json_encode($request->input('shot')),
                'staff_name' => $request->staff_name,
                'status_name' => $status,

                // Medical
                'hormonal_issues' => $request->hormonal_issues,
                'medication' => $request->medication,
                'previous_treatment' => $request->previous_treatment,
                'pcod_thyroid' => $request->pcod_thyroid,
                'skin_conditions' => $request->skin_conditions,
                'ongoing_treatments' => $request->ongoing_treatments,
                'implants_tattoos' => $request->implants_tattoos,

                // Procedure
                'procedure' => $procedureJson,

                // Reference
                'reference_by' => $request->reference_by,
                'next_follow_up' => $request->next_follow_up,
                'notes' => $request->notes,

                // Payment
                'foc' => $foc,
                'total_payment' => $foc ? 0 : $total,
                'discount_payment' => $foc ? 0 : $discount,
                'given_payment' => $foc ? 0 : $given,
                'due_payment' => $foc ? 0 : $due,
                'cash_payment' => $foc ? 0 : $cash,
                'google_pay' => $foc ? 0 : $gpay,
                'cheque_payment' => $foc ? 0 : $cheque,

                // Files
                'before_picture_1' => $beforePicturePaths['before_picture_1'],
                'before_picture_2' => $beforePicturePaths['before_picture_2'],
                'before_picture_3' => $beforePicturePaths['before_picture_3'],
                'before_picture_4' => $beforePicturePaths['before_picture_4'],
                'before_picture_5' => $beforePicturePaths['before_picture_5'],
                'after_picture_1' => $afterPicturePaths['after_picture_1'],
                'after_picture_2' => $afterPicturePaths['after_picture_2'],
                'after_picture_3' => $afterPicturePaths['after_picture_3'],
                'after_picture_4' => $afterPicturePaths['after_picture_4'],
                'after_picture_5' => $afterPicturePaths['after_picture_5'],

                // Account
                'account' => $request->account,
                'time' => $request->time ?? '13:00',
                'diet' => $request->diet,
                'exercise' => $request->exercise,
                'sleep' => $request->sleep,
                'water' => $request->water,
            ]);



            // Create Invoice if there's registration charge (not FOC)
            if (!$foc && $total > 0) {
                $paymentMethod = $given > 0 ? 'Split' : 'None';
                $this->createLHRInquiryInvoice($inquiry, $total, $given, $due, $paymentMethod, $discount, $cash, $gpay, $cheque);
            }

            DB::commit();

            $message = $status === 'joined'
                ? 'Patient added to joined list successfully!'
                : 'Inquiry added to pending list successfully!';

            $redirectRoute = $status === 'joined' ? 'lhr.joined' : 'lhr.pending';

            return redirect()->route($redirectRoute)->with('success', $message);

        } catch (\Throwable $e) {
            DB::rollBack();

            // Clean up uploaded files on error
            if (isset($beforePicturePaths)) {
                foreach ($beforePicturePaths as $path) {
                    if ($path)
                        Storage::disk('public')->delete($path);
                }
            }
            if (isset($afterPicturePaths)) {
                foreach ($afterPicturePaths as $path) {
                    if ($path)
                        Storage::disk('public')->delete($path);
                }
            }

            Log::error('Store Inquiry Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to save inquiry. Please try again.')->withInput();
        }
    }
    /**
     * Move pending inquiry to joined
     */
    public function moveToJoined($id)
    {
        $inquiry = LHRInquiry::findOrFail($id);

        // Update status to joined
        $inquiry->update([
            'status_name' => 'joined',
            'updated_at' => now()
        ]);

        return redirect()->route('lhr.joined')
            ->with('success', 'Patient moved to joined list successfully!');
    }

    /**
     * Move joined inquiry back to pending
     */
    public function moveToPending($id)
    {
        $inquiry = LHRInquiry::findOrFail($id);

        // Update status to pending
        $inquiry->update([
            'status_name' => 'pending',
            'updated_at' => now()
        ]);

        return redirect()->route('lhr.pending')
            ->with('success', 'Patient moved to pending list successfully!');
    }

    /**
     * Show form to edit inquiry
     */
    public function edit($id)
    {
        $inquiry = LHRInquiry::findOrFail($id);

        // Debug log
        Log::info('Edit inquiry ID: ' . $id, [
            'patient_name' => $inquiry->patient_name,
            'before_picture' => $inquiry->before_picture_1,
            'after_picture' => $inquiry->after_picture_1,
        ]);

        $programs = ManageProgram::where('delete_status', 0)
            ->whereIn('branch', ['LHR', 'ALL'])
            ->get();

        return view('admin.lhr.edit-inquiry', [
            'title' => 'Edit Inquiry',
            'inquiry' => $inquiry,
            'programs' => $programs
        ]);
    }

    /**
     * Update inquiry - FIXED
     */
    public function update(Request $request, $id)
    {
        $inquiry = LHRInquiry::findOrFail($id);

        // Log request data for debugging
        Log::info('Update request for ID: ' . $id, $request->all());

        // Validation rules
        $rules = [
            // Patient Information
            'patient_name' => 'required|string|max:255',
            'mobile_no' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'inquiry_date' => 'required|date',
            'address' => 'nullable|string|max:500',

            // Gender & Basic Info
            'gender' => 'required|in:male,female,other',
            'age' => 'required|integer|min:1|max:120',
            'year' => 'nullable|string',
            'area' => 'required_if:status_name,joined|array',
            'area.*' => 'nullable|array',
            'session' => 'required_if:status_name,joined|array',
            'session.*' => 'nullable|numeric',
            'area_code' => 'nullable|array',
            'area_code.*' => 'nullable|string',
            'energy' => 'nullable|array',
            'energy.*' => 'nullable|string',
            'frequency' => 'nullable|array',
            'frequency.*' => 'nullable|string',
            'shot' => 'nullable|array',
            'shot.*' => 'nullable|string',
            'staff_name' => 'nullable|string',
            'status_name' => 'required|in:pending,joined',

            // Medical Questions
            'hormonal_issues' => 'required|in:yes,no',
            'medication' => 'required|in:yes,no',
            'previous_treatment' => 'required|in:yes,no',
            'pcod_thyroid' => 'required|in:yes,no',
            'skin_conditions' => 'required|in:yes,no',
            'ongoing_treatments' => 'required|in:yes,no',
            'implants_tattoos' => 'required|in:yes,no',

            // Procedures
            'procedure' => 'nullable|array',
            'procedure.*' => 'string|in:waxing,threading,cream',

            // Reference Information
            'reference_by' => 'nullable|string|max:255',
            'next_follow_up' => 'nullable|date',
            'notes' => 'nullable|string',

            // Payment Information
            'foc' => 'nullable|boolean',
            'total_payment' => 'nullable|numeric|min:0',
            'discount_payment' => 'nullable|numeric|min:0',
            'given_payment' => 'nullable|numeric|min:0',
            'due_payment' => 'nullable|numeric|min:0',
            'cash_payment' => 'nullable|numeric|min:0',
            'gp_payment' => 'nullable|numeric|min:0',
            'cheque_payment' => 'nullable|numeric|min:0',

            // Files
            'before_picture_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'before_picture_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'before_picture_3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'before_picture_4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'before_picture_5' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'after_picture_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'after_picture_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'after_picture_3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'after_picture_4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'after_picture_5' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // Account and Time
            'account' => 'nullable|string|max:100',
            'time' => 'nullable|date_format:H:i',
            'diet' => 'nullable|string|max:255',
            'exercise' => 'nullable|string|max:255',
            'sleep' => 'nullable|string|max:255',
            'water' => 'nullable|string|max:255',
        ];

        // Validate request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        try {
            // Handle all before pictures (1-5)
            for ($i = 1; $i <= 5; $i++) {
                $fieldName = "before_picture_{$i}";
                $removeFieldName = "remove_before_picture_{$i}";

                if ($request->hasFile($fieldName)) {
                    // Delete old file if exists
                    $oldField = $inquiry->$fieldName;
                    if ($oldField && Storage::disk('public')->exists($oldField)) {
                        Storage::disk('public')->delete($oldField);
                    }
                    // Store new file
                    $validated[$fieldName] = $request->file($fieldName)->store('lhr/before_pictures', 'public');
                } elseif ($request->has($removeFieldName)) {
                    // Remove existing picture
                    $oldField = $inquiry->$fieldName;
                    if ($oldField && Storage::disk('public')->exists($oldField)) {
                        Storage::disk('public')->delete($oldField);
                    }
                    $validated[$fieldName] = null;
                } else {
                    // Keep existing picture
                    $validated[$fieldName] = $inquiry->$fieldName;
                }
            }

            // Handle all after pictures (1-5)
            for ($i = 1; $i <= 5; $i++) {
                $fieldName = "after_picture_{$i}";
                $removeFieldName = "remove_after_picture_{$i}";

                if ($request->hasFile($fieldName)) {
                    // Delete old file if exists
                    $oldField = $inquiry->$fieldName;
                    if ($oldField && Storage::disk('public')->exists($oldField)) {
                        Storage::disk('public')->delete($oldField);
                    }
                    // Store new file
                    $validated[$fieldName] = $request->file($fieldName)->store('lhr/after_pictures', 'public');
                } elseif ($request->has($removeFieldName)) {
                    // Remove existing picture
                    $oldField = $inquiry->$fieldName;
                    if ($oldField && Storage::disk('public')->exists($oldField)) {
                        Storage::disk('public')->delete($oldField);
                    }
                    $validated[$fieldName] = null;
                } else {
                    // Keep existing picture
                    $validated[$fieldName] = $inquiry->$fieldName;
                }
            }

            // Handle procedures array
            if ($request->has('procedure') && is_array($request->procedure)) {
                $validated['procedure'] = json_encode($request->procedure);
            } else {
                $validated['procedure'] = $inquiry->procedure;
            }

            // Handle treatment arrays
            foreach (['area', 'session', 'area_code', 'energy', 'frequency', 'shot'] as $field) {
                if ($request->has($field)) {
                    $values = $request->$field;
                    if (is_array($values)) {
                        $values = array_map(function($val) {
                            if (is_string($val) && strtolower($val) === 'null') return '';
                            return $val;
                        }, $values);
                        $validated[$field] = json_encode($values);
                    } else {
                        $validated[$field] = (is_string($values) && strtolower($values) === 'null') ? '' : $values;
                    }
                }
            }

            // Handle FOC
            $validated['foc'] = $request->has('foc') ? true : false;

            // Calculate due payment
            if (!$validated['foc']) {
                $total = $validated['total_payment'] ?? 0;
                $discount = $validated['discount_payment'] ?? 0;
                $cash = $validated['cash_payment'] ?? 0;
                $gpay = $validated['gp_payment'] ?? 0;
                $cheque = $validated['cheque_payment'] ?? 0;
                $given = $cash + $gpay + $cheque;
                $validated['given_payment'] = $given;
                $due = ($total - $discount) - $given;
                $validated['due_payment'] = max(0, $due);
                $validated['google_pay'] = $gpay;
                unset($validated['gp_payment']);
            } else {
                $validated['total_payment'] = 0;
                $validated['discount_payment'] = 0;
                $validated['given_payment'] = 0;
                $validated['due_payment'] = 0;
                $validated['cash_payment'] = 0;
                $validated['google_pay'] = 0;
                $validated['cheque_payment'] = 0;
            }

            // Format time
            if (!empty($validated['time'])) {
                $validated['time'] = date('H:i:s', strtotime($validated['time']));
            } else {
                $validated['time'] = $inquiry->time;
            }



            // Get current and new status
            $currentStatus = $inquiry->status_name;
            $newStatus = $validated['status_name'];

            // Update inquiry
            $updated = $inquiry->update($validated);

            if ($updated) {
                // Set success message
                $message = 'Inquiry updated successfully!';

                if ($currentStatus !== $newStatus) {
                    $message = $newStatus === 'joined'
                        ? 'Patient moved to joined list successfully!'
                        : 'Patient moved to pending list successfully!';
                }

                // Redirect based on new status
                $redirectRoute = $newStatus === 'joined' ? 'lhr.joined' : 'lhr.pending';

                Log::info('Update successful for ID: ' . $id);
                return redirect()->route($redirectRoute)
                    ->with('success', $message);
            } else {
                throw new \Exception('Failed to update inquiry');
            }
        } catch (\Exception $e) {
            Log::error('Update error for ID ' . $id . ': ' . $e->getMessage());
            Log::error('Error trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Error updating inquiry: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete inquiry - FIXED
     */
    public function destroy(Request $request, $id)
    {
        try {
            Log::info('Delete request received for ID: ' . $id);

            $inquiry = LHRInquiry::findOrFail($id);

            Log::info('Inquiry found: ' . $inquiry->patient_name);

            // Get status before deletion for redirect
            $status = $inquiry->status_name;

            Log::info('Inquiry status: ' . $status);

            // Delete files if exist
            if ($inquiry->before_picture_1) {
                $beforePath = $inquiry->before_picture_1;
                Log::info('Before picture path: ' . $beforePath);
                if (Storage::disk('public')->exists($beforePath)) {
                    Storage::disk('public')->delete($beforePath);
                    Log::info('Before picture deleted');
                }
            }

            if ($inquiry->after_picture_1) {
                $afterPath = $inquiry->after_picture_1;
                Log::info('After picture path: ' . $afterPath);
                if (Storage::disk('public')->exists($afterPath)) {
                    Storage::disk('public')->delete($afterPath);
                    Log::info('After picture deleted');
                }
            }

            // Delete the inquiry
            $inquiry->delete();
            Log::info('Inquiry deleted from database');

            // If it's an AJAX request
            if ($request->ajax()) {
                Log::info('AJAX response sent');
                return response()->json([
                    'success' => true,
                    'message' => 'Inquiry deleted successfully',
                    'status' => $status
                ]);
            }

            // If it's a regular request
            $redirectRoute = $status === 'joined' ? 'lhr.joined' : 'lhr.pending';
            Log::info('Redirecting to: ' . $redirectRoute);

            return redirect()->route($redirectRoute)
                ->with('success', 'Inquiry deleted successfully');
        } catch (\Exception $e) {
            Log::error('Delete error for ID ' . $id . ': ' . $e->getMessage());
            Log::error('Error trace: ' . $e->getTraceAsString());

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
    /**
     * Change inquiry status
     */
    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status_name' => 'required|in:pending,joined'
        ]);

        $inquiry = LHRInquiry::find($id);

        if (!$inquiry) {
            return response()->json([
                'success' => false,
                'message' => 'Inquiry not found'
            ], 404);
        }

        $inquiry->status_name = $request->status_name;
        $inquiry->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }

    /**
     * Bulk status update
     */
    public function bulkStatusUpdate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:lhr_inquiries,id',
            'status_name' => 'required|in:pending,joined'
        ]);

        LHRInquiry::whereIn('id', $request->ids)
            ->update(['status_name' => $request->status_name]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully for selected records'
        ]);
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics()
    {
        $totalInquiries = LHRInquiry::count();
        $pendingInquiries = LHRInquiry::where('status_name', 'pending')->count();
        $joinedInquiries = LHRInquiry::where('status_name', 'joined')->count();
        $todayInquiries = LHRInquiry::whereDate('created_at', today())->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $totalInquiries,
                'pending' => $pendingInquiries,
                'joined' => $joinedInquiries,
                'today' => $todayInquiries
            ]
        ]);
    }

    // public function showPatientProfile($id)
    // {
    //     return view('admin.lhr.patient-profile');
    // }

    public function showPatientProfile($id)
    {
        try {
            $inquiry = LHRInquiry::findOrFail($id);

            Log::info('Patient Profile - ID: ' . $id, [
                'patient_name' => $inquiry->patient_name,
                'mobile_no' => $inquiry->mobile_no,
                'email' => $inquiry->email,
                'medical_data' => [
                    'hormonal_issues' => $inquiry->hormonal_issues,
                    'pcod_thyroid' => $inquiry->pcod_thyroid,
                    'ongoing_treatments' => $inquiry->ongoing_treatments,
                    'medication' => $inquiry->medication,
                    'skin_conditions' => $inquiry->skin_conditions,
                    'previous_treatment' => $inquiry->previous_treatment,
                    'procedure' => $inquiry->procedure,
                    'implants_tattoos' => $inquiry->implants_tattoos,
                ]
            ]);

            // Fetch all active follow-ups for session calculations
            $allFollowUps = LhrFollowup::where('patient_id', $inquiry->patient_id)
                ->where('delete_status', 'active')
                ->latest('inquiry_date')
                ->get();

            // Paginated follow-ups for display (10 per page)
            $followUps = LhrFollowup::where('patient_id', $inquiry->patient_id)
                ->where('delete_status', 'active')
                ->latest('inquiry_date')
                ->paginate(2, ['*'], 'followup_page');

            // Journey also uses follow-ups, let's use the same paginated set or separate
            // User wants separate pagination for journey? Probably same is fine but let's see.

            // Paginate Programs (derived from inquiry areas)
            $areas = json_decode($inquiry->area, true) ?: (is_string($inquiry->area) ? [$inquiry->area] : []);
            $sessions_list = json_decode($inquiry->session, true) ?: (is_string($inquiry->session) ? [$inquiry->session] : []);

            $programs_data = [];
            foreach ($areas as $index => $area) {
                // Calculate used sessions for this specific area using entries from allFollowUps
                $currentAreaStr = is_array($area) ? implode(', ', $area) : (string) $area;
                $currentAreaFollows = $allFollowUps->filter(function ($f) use ($currentAreaStr) {
                    if (empty($currentAreaStr))
                        return false;
                    $fArea = is_array($f->area) ? implode(', ', $f->area) : (string) $f->area;
                    return str_contains(strtolower($fArea), strtolower($currentAreaStr)) || str_contains(strtolower($currentAreaStr), strtolower($fArea));
                });

                $programs_data[] = [
                    'area' => $area,
                    'total_sessions' => $sessions_list[$index] ?? 0,
                    'used_sessions' => $currentAreaFollows->count(),
                    'last_session' => $currentAreaFollows->first(),
                    'original_index' => $index
                ];
            }

            $currentPagePrograms = \Illuminate\Pagination\Paginator::resolveCurrentPage('program_page');
            $perPagePrograms = 5;
            $currentItemsPrograms = array_slice($programs_data, ($currentPagePrograms - 1) * $perPagePrograms, $perPagePrograms);
            $paginatedPrograms = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentItemsPrograms,
                count($programs_data),
                $perPagePrograms,
                $currentPagePrograms,
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'pageName' => 'program_page']
            );

            $payments = collect([]);

            return view('admin.lhr.patient-profile', [
                'title' => 'Patient Profile - ' . $inquiry->patient_name,
                'inquiry' => $inquiry,
                'followUps' => $followUps,
                'programs' => $paginatedPrograms,
                'payments' => $payments,
                'allFollowUps' => $allFollowUps // Pass all if needed for other logic
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading patient profile: ' . $e->getMessage());
            return redirect()->route('lhr.joined')
                ->with('error', 'Patient not found');
        }
    }

    /**
     * Update the LHR patient's profile image.
     */
    public function updateProfileImage(Request $request, $id)
    {
        try {
            $patient = LHRInquiry::findOrFail($id);

            $request->validate([
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('profile_image')) {
                // Delete old image if exists
                $oldImage = $patient->profile_image;
                if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }

                // Store new image in public disk under lhr/profiles
                $image = $request->file('profile_image');
                $filename = 'lhr_patient_' . $id . '_' . time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('lhr/profiles', $filename, 'public');

                $patient->update(['profile_image' => $path]);

                return back()->with('success', 'Profile image updated successfully.');
            }

            return back()->with('error', 'No image file provided.');
        } catch (\Exception $e) {
            Log::error('Error updating LHR profile image: ' . $e->getMessage());
            return back()->with('error', 'Error updating profile image: ' . $e->getMessage());
        }
    }


    public function followup($id)
    {
        $accUser = User::where('email', auth()->user()->email)->first();
        // dd($accUser);   
        if (!$accUser) {
            dd("ACC user not found");
        }
        $branches = Branch::all();   // <-- missing

        $branchName = optional($accUser->branch)->branch_name;
        $programs = ManageProgram::where('delete_status', 0)
            ->whereIn('branch', ['LHR', 'ALL'])
            ->get();

        $branchId = auth()->user()->user_branch;
        $inquiry = LHRInquiry::findOrFail($id);
        // dd($branchName);

        return view('admin.lhr.followup', compact('inquiry', 'branchId', 'branchName', 'branches', 'programs'));
    }

    /**
     * Store follow up data
     */
    public function storeFollowup(Request $request, $id)
    {
        // dd($request->all());
        // No validation - only fields with * will be validated by frontend

        try {

            // FOC handling
            $isFoc = $request->boolean('foc');

            $registrationCharges = $isFoc ? 0 : ($request->registration_charges ?? 0);
            $discountAmount = $isFoc ? 0 : ($request->discount_payment ?? 0);
            $cash = $isFoc ? 0 : floatval($request->cash_payment ?? 0);
            $gpay = $isFoc ? 0 : floatval($request->gp_payment ?? 0);
            $cheque = $isFoc ? 0 : floatval($request->cheque_payment ?? 0);
            $paidAmount = $cash + $gpay + $cheque;
            $dueAmount = $isFoc ? 0 : ($request->due_amount ?? 0);

            // Calculate due amount if not provided
            if ($dueAmount == 0 && !$isFoc) {
                $dueAmount = max(0, $registrationCharges - $paidAmount);
            }            // Get Inquiry Data
            $inquiry = LHRInquiry::findOrFail($id);

            // Handle multiple treatment rows
            $areas = $request->input('area') ?? [];
            $sessions = $request->input('session') ?? [];
            $area_codes = $request->input('area_code') ?? [];
            $energies = $request->input('energy') ?? [];
            $frequencies = $request->input('frequency') ?? [];
            $shots = $request->input('shot') ?? [];
            $isTreatmentTaken = $request->input('is_treatment_taken') ?? [];

            $firstFollowup = null;

            if (!empty($areas)) {
                foreach ($areas as $index => $areaSet) {
                    // Skip if treatment not taken for this row
                    if (!isset($isTreatmentTaken[$index])) {
                        continue;
                    }

                    $areaName = is_array($areaSet) ? implode(', ', $areaSet) : $areaSet;

                    $energy = $energies[$index] ?? null;
                    $frequency = $frequencies[$index] ?? null;
                    $shot = $shots[$index] ?? null;

                    if (is_string($energy) && strtolower($energy) === 'null') $energy = null;
                    if (is_string($frequency) && strtolower($frequency) === 'null') $frequency = null;
                    if (is_string($shot) && strtolower($shot) === 'null') $shot = null;

                    $followup = LhrFollowup::create([
                        'patient_id' => $inquiry->patient_id,
                        'branch_id' => $request->branch_id ?? $inquiry->branch_id,
                        'branch' => $request->branch ?? $inquiry->branch,
                        'patient_name' => $request->patient_name ?? $inquiry->patient_name,
                        'address' => $request->address ?? $inquiry->address,
                        'inquiry_date' => $request->inquiry_date,
                        'inquiry_time' => $request->inquiry_time,
                        'gender' => $request->gender,
                        'age' => $request->age ?? $inquiry->age,
                        'area' => $areaName,
                        'session' => $sessions[$index] ?? null,
                        'afra_code' => $area_codes[$index] ?? null,
                        'energy' => $energy,
                        'frequency' => $frequency,
                        'shot' => $shot,
                        'staff_name' => auth()->user()->name ?? 'Admin',
                        'month_year' => now()->format('m-Y'),
                        'refranceby' => $request->refranceby ?? '',
                        'next_follow_date' => $request->next_follow_date ?? '',
                        'notes' => $request->notes ?? '',
                        'payment_method' => $paidAmount > 0 ? 'Split' : 'None',
                        'total_payment' => ($firstFollowup === null) ? $registrationCharges : 0,
                        'discount_payment' => ($firstFollowup === null) ? $discountAmount : 0,
                        'given_payment' => ($firstFollowup === null) ? $paidAmount : 0,
                        'due_payment' => ($firstFollowup === null) ? $dueAmount : 0,
                        'foc' => $isFoc ? 1 : 0,
                        'cash_price' => ($firstFollowup === null) ? $cash : 0,
                        'gpay_price' => ($firstFollowup === null) ? $gpay : 0,
                        'cheque_price' => ($firstFollowup === null) ? $cheque : 0,
                        'delete_status' => 'active',
                        'delete_by' => auth()->user()->name ?? 'system',
                    ]);

                    if (!$firstFollowup) {
                        $firstFollowup = $followup;
                    }
                }
            }

            if (!$firstFollowup) {
                // Create at least one record for the payment even if no areas selected
                $firstFollowup = LhrFollowup::create([
                    'patient_id' => $inquiry->patient_id,
                    'branch_id' => $request->branch_id ?? $inquiry->branch_id,
                    'branch' => $request->branch ?? $inquiry->branch,
                    'patient_name' => $request->patient_name ?? $inquiry->patient_name,
                    'address' => $request->address ?? $inquiry->address,
                    'inquiry_date' => $request->inquiry_date,
                    'inquiry_time' => $request->inquiry_time,
                    'gender' => $request->gender,
                    'age' => $request->age ?? $inquiry->age,
                    'area' => '',
                    'session' => null,
                    'afra_code' => null,
                    'energy' => null,
                    'frequency' => null,
                    'shot' => null,
                    'staff_name' => auth()->user()->name ?? 'Admin',
                    'month_year' => now()->format('m-Y'),
                    'refranceby' => $request->refranceby ?? '',
                    'next_follow_date' => $request->next_follow_date ?? '',
                    'notes' => $request->notes ?? '',
                    'payment_method' => $paidAmount > 0 ? 'Split' : 'None',
                    'total_payment' => $registrationCharges,
                    'discount_payment' => 0,
                    'given_payment' => $paidAmount,
                    'due_payment' => $dueAmount,
                    'foc' => $isFoc ? 1 : 0,
                    'cash_price' => $cash,
                    'gpay_price' => $gpay,
                    'cheque_price' => $cheque,
                    'delete_status' => 'active',
                    'delete_by' => auth()->user()->name ?? 'system',
                ]);
            }

            // Update original inquiry with next follow up info
            $inquiry->update([
                'next_follow_up' => $request->next_follow_date,
                'notes' => $request->notes,
                'staff_name' => auth()->user()->name ?? $inquiry->staff_name
            ]);

            // Create Invoice if there's payment (not FOC and registration charges > 0)
            if (!$isFoc && $registrationCharges > 0 && $firstFollowup) {
                $paymentMethod = $paidAmount > 0 ? 'Split' : 'None';
                $this->createLHRFollowupInvoice($inquiry, $firstFollowup, $registrationCharges, $paidAmount, $dueAmount, $paymentMethod, $discountAmount, $cash, $gpay, $cheque);
            }

            return redirect()->route('lhr.patient.profile', $id)
                ->with('success', 'Follow up record added successfully!');
        } catch (\Exception $e) {

            Log::error('Followup Error: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong while saving follow up.');
        }
    }

    /**
     * Create invoice for LHR inquiry
     */
    private function createLHRInquiryInvoice($inquiry, $registrationCharges, $paidAmount, $dueAmount, $paymentMethod, $discount = 0, $cash = 0, $gpay = 0, $cheque = 0)
    {
        try {
            // Generate unique invoice number
            $lastInvoice = Invoice::where('branch_id', $inquiry->branch_id)
                ->orderBy('id', 'desc')
                ->first();

            $invoiceNumber = 'LB-00001'; // Default
            if ($lastInvoice && preg_match('/LB-(\d+)/', $lastInvoice->invoice_no, $matches)) {
                $nextNumber = (int) $matches[1] + 1;
                $invoiceNumber = 'LB-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            }

            // Create invoice
            $invoice = Invoice::create([
                'branch_id' => $inquiry->branch_id,
                'patient_id' => $inquiry->id,
                'program_id' => null, // LHR inquiry doesn't have program
                'invoice_no' => $invoiceNumber,
                'invoice_date' => now()->format('Y-m-d'),
                'address' => $inquiry->address,
                'phone' => '', // LHR inquiry doesn't have phone
                'price' => $registrationCharges,
                'pending_due' => $dueAmount,
                'total_payment' => $registrationCharges,
                'discount' => $discount,
                'given_payment' => $paidAmount,
                'due_payment' => $dueAmount,
                'cash_payment' => $cash,
                'gpay_payment' => $gpay,
                'cheque_payment' => $cheque,
                'invoice_file' => null,
                'charges_data' => [
                    [
                        'charge_name' => 'Registration & Consultation Charges',
                        'amount' => $registrationCharges,
                        'price' => $registrationCharges
                    ]
                ],
                'programs_data' => [
                    [
                        'program_name' => 'LHR Registration & Initial Consultation',
                        'amount' => $registrationCharges,
                        'price' => $registrationCharges,
                        'inquiry_date' => $inquiry->inquiry_date,
                        'payment_method' => $paymentMethod
                    ]
                ]
            ]);

            // Create transaction records
            // 1. Debit Transaction (Charges)
            if ($registrationCharges > 0) {
                PatientTransaction::create([
                    'branch_id' => $inquiry->branch_id,
                    'patient_id' => $inquiry->id,
                    'invoice_id' => $invoice->id,
                    'type' => 'debit',
                    'amount' => $registrationCharges,
                    'description' => 'LHR Registration & Consultation Charges Generated - Invoice: ' . $invoiceNumber,
                    'created_at' => now(),
                ]);
            }

            // 2. Credit Transaction (Payment Received)
            if ($paidAmount > 0) {
                PatientTransaction::create([
                    'branch_id' => $inquiry->branch_id,
                    'patient_id' => $inquiry->id,
                    'invoice_id' => $invoice->id,
                    'type' => 'credit',
                    'amount' => $paidAmount,
                    'payment_method' => $paymentMethod,
                    'description' => 'LHR Inquiry Payment - Invoice: ' . $invoiceNumber,
                    'created_at' => now(),
                ]);
            }

            // 3. Discount Transaction
            if ($discount > 0) {
                PatientTransaction::create([
                    'branch_id' => $inquiry->branch_id,
                    'patient_id' => $inquiry->id,
                    'invoice_id' => $invoice->id,
                    'type' => 'discount',
                    'amount' => $discount,
                    'description' => 'LHR Inquiry Discount - Invoice: ' . $invoiceNumber,
                    'created_at' => now(),
                ]);
            }

            Log::info('LHR Inquiry Invoice Created', [
                'invoice_id' => $invoice->id,
                'invoice_no' => $invoiceNumber,
                'patient_id' => $inquiry->id,
                'amount' => $registrationCharges,
                'paid' => $paidAmount,
                'due' => $dueAmount
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating LHR inquiry invoice: ' . $e->getMessage());
            // Don't throw exception here, just log it so inquiry creation doesn't fail
        }
    }

    /**
     * Create invoice for LHR followup
     */
    private function createLHRFollowupInvoice($inquiry, $followup, $registrationCharges, $paidAmount, $dueAmount, $paymentMethod, $discount = 0, $cash = 0, $gpay = 0, $cheque = 0)
    {
        try {
            // Generate unique invoice number
            $lastInvoice = Invoice::where('branch_id', $inquiry->branch_id)
                ->orderBy('id', 'desc')
                ->first();

            $invoiceNumber = 'LB-00001'; // Default
            if ($lastInvoice && preg_match('/LB-(\d+)/', $lastInvoice->invoice_no, $matches)) {
                $nextNumber = (int) $matches[1] + 1;
                $invoiceNumber = 'LB-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            }

            // Create invoice
            $invoice = Invoice::create([
                'branch_id' => $inquiry->branch_id,
                'patient_id' => $inquiry->id,
                'program_id' => null, // LHR followup doesn't have program
                'invoice_no' => $invoiceNumber,
                'invoice_date' => now()->format('Y-m-d'),
                'address' => $inquiry->address,
                'phone' => '', // LHR inquiry doesn't have phone
                'price' => $registrationCharges,
                'pending_due' => $dueAmount,
                'total_payment' => $registrationCharges,
                'discount' => $discount,
                'given_payment' => $paidAmount,
                'due_payment' => $dueAmount,
                'cash_payment' => $cash,
                'gpay_payment' => $gpay,
                'cheque_payment' => $cheque,
                'invoice_file' => null,
                'charges_data' => [
                    [
                        'charge_name' => 'Registration & Consultation Charges',
                        'amount' => $registrationCharges,
                        'price' => $registrationCharges
                    ]
                ],
                'programs_data' => [
                    [
                        'program_name' => 'LHR Followup Service',
                        'amount' => $registrationCharges,
                        'price' => $registrationCharges,
                        'followup_date' => $followup->inquiry_date,
                        'payment_method' => $paymentMethod
                    ]
                ]
            ]);

            // Create transaction records
            // 1. Debit Transaction (Charges billed)
            if ($registrationCharges > 0) {
                PatientTransaction::create([
                    'branch_id' => $inquiry->branch_id,
                    'patient_id' => $inquiry->id,
                    'invoice_id' => $invoice->id,
                    'type' => 'debit',
                    'amount' => $registrationCharges,
                    'description' => 'LHR Followup Charges - Invoice: ' . $invoiceNumber,
                    'created_at' => now(),
                ]);
            }

            // 2. Credit Transaction (Payment received)
            if ($paidAmount > 0) {
                PatientTransaction::create([
                    'branch_id' => $inquiry->branch_id,
                    'patient_id' => $inquiry->id,
                    'invoice_id' => $invoice->id,
                    'type' => 'credit',
                    'amount' => $paidAmount,
                    'description' => 'LHR Followup Payment - Invoice: ' . $invoiceNumber,
                    'created_at' => now(),
                ]);
            }

            // 3. Discount Transaction
            if ($discount > 0) {
                PatientTransaction::create([
                    'branch_id' => $inquiry->branch_id,
                    'patient_id' => $inquiry->id,
                    'invoice_id' => $invoice->id,
                    'type' => 'discount',
                    'amount' => $discount,
                    'description' => 'LHR Followup Discount - Invoice: ' . $invoiceNumber,
                    'created_at' => now(),
                ]);
            }

            Log::info('LHR Followup Invoice Created', [
                'invoice_id' => $invoice->id,
                'invoice_no' => $invoiceNumber,
                'patient_id' => $inquiry->id,
                'amount' => $registrationCharges,
                'paid' => $paidAmount,
                'due' => $dueAmount
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating LHR followup invoice: ' . $e->getMessage());
            // Don't throw exception here, just log it so followup creation doesn't fail
        }
    }


    public function exportPending(Request $request)
    {
        try {
            // Get pending inquiries with filters
            $query = LHRInquiry::where('status_name', 'pending');

            // Apply search filter if present
            if ($request->has('search') && $request->search) {
                $query->where('patient_name', 'like', '%' . $request->search . '%');
            }

            // Apply follow up date filter if present
            if ($request->has('follow_up_date') && $request->follow_up_date) {
                $query->whereDate('next_follow_up', $request->follow_up_date);
            }

            $inquiries = $query->orderBy('next_follow_up', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();

            if ($inquiries->isEmpty()) {
                return redirect()->route('lhr.pending')
                    ->with('error', 'No data to export');
            }

            $hasFilters = $request->has('search') || $request->has('follow_up_date');

            $filename = 'LHR_Pending_Patients_' . date('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(new LHREnquiriesExport($inquiries, 'pending', $hasFilters), $filename);
        } catch (\Exception $e) {
            Log::error('Export pending error: ' . $e->getMessage());
            return redirect()->route('lhr.pending')
                ->with('error', 'Error exporting data: ' . $e->getMessage());
        }
    }

    public function exportAllPending()
    {
        try {
            $inquiries = LHRInquiry::where('status_name', 'pending')
                ->orderBy('next_follow_up', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();

            if ($inquiries->isEmpty()) {
                return redirect()->route('lhr.pending')
                    ->with('error', 'No pending patients to export');
            }

            $filename = 'LHR_All_Pending_Patients_' . date('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(new LHREnquiriesExport($inquiries, 'pending', false), $filename);
        } catch (\Exception $e) {
            Log::error('Export all pending error: ' . $e->getMessage());
            return redirect()->route('lhr.pending')
                ->with('error', 'Error exporting data: ' . $e->getMessage());
        }
    }


    public function exportJoined(Request $request)
    {
        try {
            // Get joined inquiries with filters
            $query = LHRInquiry::where('status_name', 'joined');

            // Apply search filter if present
            if ($request->has('search') && $request->search) {
                $query->where('patient_name', 'like', '%' . $request->search . '%');
            }

            // Apply join date filter if present
            if ($request->has('join_date') && $request->join_date) {
                $query->whereDate('created_at', $request->join_date);
            }

            $inquiries = $query->latest()->get();

            if ($inquiries->isEmpty()) {
                return redirect()->route('lhr.joined')
                    ->with('error', 'No data to export');
            }

            $hasFilters = $request->has('search') || $request->has('join_date');

            $filename = 'LHR_Joined_Patients_' . date('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(new LHREnquiriesExport($inquiries, 'joined', $hasFilters), $filename);
        } catch (\Exception $e) {
            Log::error('Export joined error: ' . $e->getMessage());
            return redirect()->route('lhr.joined')
                ->with('error', 'Error exporting data: ' . $e->getMessage());
        }
    }

    /**
     * Export all joined data to Excel
     */
    public function exportAllJoined()
    {
        try {
            $inquiries = LHRInquiry::where('status_name', 'joined')
                ->latest()
                ->get();

            if ($inquiries->isEmpty()) {
                return redirect()->route('lhr.joined')
                    ->with('error', 'No joined patients to export');
            }

            $filename = 'LHR_All_Joined_Patients_' . date('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(new LHREnquiriesExport($inquiries, 'joined', false), $filename);
        } catch (\Exception $e) {
            Log::error('Export all joined error: ' . $e->getMessage());
            return redirect()->route('lhr.joined')
                ->with('error', 'Error exporting data: ' . $e->getMessage());
        }
    }

    public function getUsedSessions(Request $request)
    {
        $patientId = $request->get('patient_id');
        $area = $request->get('area');

        if (!$patientId || !$area) {
            return response()->json(['used_sessions' => 0]);
        }

        // Count followups where area matches
        $usedCount = \App\Models\LhrFollowup::where('patient_id', $patientId)
            ->where('area', 'like', '%' . $area . '%')
            ->count();

        return response()->json(['used_sessions' => $usedCount]);
    }

    public function getLatestSettings(Request $request)
    {
        $patientId = $request->get('patient_id');
        $area = $request->get('area');

        if (!$patientId || !$area) {
            return response()->json(['success' => false]);
        }

        $latest = \App\Models\LhrFollowup::where('patient_id', $patientId)
            ->where(function($query) use ($area) {
                $query->where('area', 'like', '%' . $area . '%')
                      ->orWhereRaw('LOWER(?) LIKE LOWER(CONCAT("%", area, "%"))', [$area]);
            })
            ->latest('id')
            ->first();

        if ($latest) {
            return response()->json([
                'success' => true,
                'energy' => $latest->energy,
                'frequency' => $latest->frequency,
                'shot' => $latest->shot,
            ]);
        }

        return response()->json(['success' => false]);
    }

    public function removeInquiryArea(Request $request)
    {
        $inquiryId = $request->get('inquiry_id');
        $areaName = $request->get('area');

        $inquiry = LHRInquiry::find($inquiryId);
        if (!$inquiry) {
            return response()->json(['success' => false, 'message' => 'Inquiry not found']);
        }

        $areas = json_decode($inquiry->area, true) ?: (is_string($inquiry->area) ? [$inquiry->area] : []);
        $sessions = json_decode($inquiry->session, true) ?: (is_string($inquiry->session) ? [$inquiry->session] : []);
        $codes = json_decode($inquiry->area_code, true) ?: (is_string($inquiry->area_code) ? [$inquiry->area_code] : []);
        $energies = json_decode($inquiry->energy, true) ?: (is_string($inquiry->energy) ? [$inquiry->energy] : []);
        $freqs = json_decode($inquiry->frequency, true) ?: (is_string($inquiry->frequency) ? [$inquiry->frequency] : []);
        $shots = json_decode($inquiry->shot, true) ?: (is_string($inquiry->shot) ? [$inquiry->shot] : []);

        $newAreas = [];
        $newSessions = [];
        $newCodes = [];
        $newEnergies = [];
        $newFreqs = [];
        $newShots = [];

        foreach ($areas as $idx => $area) {
            if ($area !== $areaName) {
                $newAreas[] = $area;
                $newSessions[] = $sessions[$idx] ?? '';
                $newCodes[] = $codes[$idx] ?? '';
                $newEnergies[] = $energies[$idx] ?? '';
                $newFreqs[] = $freqs[$idx] ?? '';
                $newShots[] = $shots[$idx] ?? '';
            }
        }

        $inquiry->area = json_encode($newAreas);
        $inquiry->session = json_encode($newSessions);
        $inquiry->area_code = json_encode($newCodes);
        $inquiry->energy = json_encode($newEnergies);
        $inquiry->frequency = json_encode($newFreqs);
        $inquiry->shot = json_encode($newShots);
        $inquiry->save();

        return response()->json(['success' => true]);
    }
}


