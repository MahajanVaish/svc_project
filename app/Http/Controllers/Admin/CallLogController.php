<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccInquiry;
use App\Models\CallLog;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CallLogController extends Controller
{
    public function create($id)
    {
        $inquiry = AccInquiry::findOrFail($id);
        $branches = Branch::all();

        // Time slots as requested by user
        $timeSlots = [
            'Morning',
            'Afternoon',
            'Evening',
            'Night',
            'Mid-night'
        ];

        return view('admin.call_log.create', compact('inquiry', 'timeSlots', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'inquiry_id' => 'required|exists:acc_inquirys,id',
            'branch' => 'required|string',
            'call_date' => 'required|date',
            'call_time' => 'required',
            'time_slot' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $inquiry = AccInquiry::find($request->inquiry_id);
        $branch  = Branch::where('branch_name', $request->branch)->first();

        CallLog::create([
            'patient_id' => $inquiry->patient_id,
            'inquiry_id' => $inquiry->id,
            'branch'     => $request->branch,
            'branch_id'  => $branch ? $branch->branch_id : ($inquiry->branch_id ?? ''),
            'user_id'    => Auth::id(),
            'call_date'  => $request->call_date,
            'call_time'  => $request->call_time,
            'time_slot'  => $request->time_slot,
            'remarks'    => $request->remarks,
        ]);

        return redirect()->route('patient.profile', $inquiry->id)->with('success', 'Call log recorded successfully.');
    }

    public function history($patient_id)
    {
        $logs = CallLog::where('patient_id', $patient_id)
            ->with(['user', 'branch'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($logs);
    }
}
