<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Appointment;
use App\Models\AccInquiry;
use App\Models\Branch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperadmin = $user->hasRole('Superadmin');
        $branchId = $user->user_branch;

        $appointments = Appointment::when(!$isSuperadmin && !empty($branchId), function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(20);

        $branches = Branch::where(function ($q) {
                $q->where('delete_status', '0')
                  ->orWhere('delete_status', '');
            })->get();
        
        $patients = AccInquiry::where(function ($q) {
                $q->where('delete_status', '0')
                  ->orWhere('delete_status', '');
            })
            ->when(!$isSuperadmin && !empty($branchId), function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->get(['id', 'patient_id', 'patient_f_name', 'patient_m_name', 'patient_l_name', 'phone_no']);

        return view('admin.appointment.index', compact('appointments', 'branches', 'patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_name' => 'required',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
        ]);

        $patientId = $request->patient_id;
        $branchId = $request->branch_id ?? Auth::user()->user_branch;

        if (empty($patientId)) {
            $prefix = explode('-', $branchId)[0] ?: 'SVC';
            
            $maxInquiry = AccInquiry::where('patient_id', 'LIKE', $prefix . '-%')
                ->max(DB::raw('CAST(SUBSTRING(patient_id, LOCATE("-", patient_id) + 1) AS UNSIGNED)'));
                
            $maxAppointment = Appointment::where('patient_id', 'LIKE', $prefix . '-%')
                ->max(DB::raw('CAST(SUBSTRING(patient_id, LOCATE("-", patient_id) + 1) AS UNSIGNED)'));
            
            $maxNumber = max((int)$maxInquiry, (int)$maxAppointment);
            $nextNumber = $maxNumber + 1;
            $patientId = $prefix . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        }

        Appointment::create([
            'patient_id' => $patientId,
            'patient_name' => $request->patient_name,
            'phone' => $request->phone,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'content' => $request->content,
            'branch_id' => $branchId,
            'status' => 'Pending',
            'created_by' => Auth::user()->name,
        ]);

        return redirect()->back()->with('success', 'Appointment created successfully!');
    }
    
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update($request->all());
        return redirect()->back()->with('success', 'Appointment updated successfully!');
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();
        return redirect()->back()->with('success', 'Appointment deleted successfully!');
    }
}
