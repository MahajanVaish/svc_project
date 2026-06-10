<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Followups;
use App\Models\PatientInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DoctorController extends Controller
{
    public function myPatients(Request $request)
    {
        $user = Auth::user();
        if (!($user->user_role == 6 || $user->hasRole('Doctor'))) {
            abort(403);
        }

        $search = trim((string) $request->input('q'));

        $baseFollowups = Followups::query()
            ->where('doctor_id', $user->id)
            ->whereNotNull('inquiry_id');

        if ($search !== '') {
            $baseFollowups->whereHas('inquiry', function ($q) use ($search) {
                $q->where('patient_name', 'like', "%{$search}%")
                  ->orWhere('patient_id', 'like', "%{$search}%");
            });
        }

        $inquiryIds = $baseFollowups->pluck('inquiry_id')->unique()->toArray();

        $patients = PatientInquiry::whereIn('id', $inquiryIds)
            ->orderBy('patient_name')
            ->paginate(20)
            ->withQueryString();

        return view('doctor.my_patients', compact('patients', 'search'));
    }

    public function meetingHistory(Request $request)
    {
        $user = Auth::user();
        if (!($user->user_role == 6 || $user->hasRole('Doctor'))) {
            abort(403);
        }

        $search = trim((string) $request->input('q'));

        $query = Followups::with(['inquiry', 'metas'])
            ->where('doctor_id', $user->id)
            ->whereNotNull('zoom_meeting_id');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('zoom_meeting_id', 'like', "%{$search}%")
                  ->orWhereHas('inquiry', function ($qi) use ($search) {
                      $qi->where('patient_name', 'like', "%{$search}%")
                         ->orWhere('patient_id', 'like', "%{$search}%");
                  });
            });
        }

        $meetings = $query->orderBy('followup_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('doctor.meeting_history', compact('meetings', 'search'));
    }

    public function updateMeetingSchedule(Request $request, $id)
    {
        $user = Auth::user();
        if (!($user->user_role == 6 || $user->hasRole('Doctor'))) {
            abort(403);
        }

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['nullable'],
        ]);

        $followup = Followups::with('metas')->findOrFail($id);
        if ($followup->doctor_id !== $user->id) {
            abort(403);
        }

        $date = Carbon::parse($validated['date'])->format('Y-m-d');
        $start = $validated['start_time'];
        $end = $validated['end_time'] ?? null;

        // Normalize times to H:i:s
        $start = strlen($start) === 5 ? ($start . ':00') : $start; // HH:MM -> HH:MM:SS
        if ($end) {
            $end = strlen($end) === 5 ? ($end . ':00') : $end;
        } else {
            // Derive end time +30 mins when not provided
            $end = Carbon::parse($date . ' ' . $start)->addMinutes(30)->format('H:i:s');
        }

        $followup->followup_date = $date;
        $followup->save();
        $followup->setMeta('followups_time', $start);
        $followup->setMeta('followups_end_time', $end);

        return back()->with('success', 'Meeting schedule updated.');
    }

    public function deleteMeeting(Request $request, $id)
    {
        $user = Auth::user();
        if (!($user->user_role == 6 || $user->hasRole('Doctor'))) {
            abort(403);
        }

        $followup = Followups::findOrFail($id);
        if ($followup->doctor_id !== $user->id) {
            abort(403);
        }

        // Clear Zoom fields locally. (Optional: integrate Zoom API delete here.)
        $followup->zoom_meeting_id = null;
        $followup->zoom_start_url = null;
        $followup->zoom_join_url = null;
        $followup->zoom_password = null;
        $followup->save();

        return back()->with('success', 'Meeting removed from history.');
    }
}

