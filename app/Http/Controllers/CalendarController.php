<?php

namespace App\Http\Controllers;

use App\Models\Followups;
use Illuminate\Http\Request;
use App\Models\PatientInquiry;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{ public function index(Request $request)
    {
        $view = $request->input('view', 'month');
        $currentDate = $request->input('date', date('Y-m-d'));

        $currentCarbon = Carbon::parse($currentDate);
        $currentYear = $currentCarbon->year;
        $currentMonth = $currentCarbon->month;
        $today = Carbon::today();

      
        $allEvents = collect();

        $inquiries = PatientInquiry::whereNotNull('next_follow_date')->get()->map(function ($inquiry) use ($today) {
            $inquiryDate = $inquiry->next_follow_date ? Carbon::parse($inquiry->next_follow_date) : null;
            $isPast = $inquiryDate ? $inquiryDate->lt($today) : false;
            
            return [
                'id' => 'inquiry_' . $inquiry->id,
                'type' => 'inquiry',
                'event_id' => $inquiry->id,
                'patient_id' => $inquiry->patient_id,
                'patient_name' => $inquiry->patient_name,
                'inquiry_date' => $inquiry->inquiry_date,
                'next_follow_date' => $inquiry->next_follow_date,
                'branch' => $inquiry->branch,
                'branch_id' => $inquiry->branch_id,
                'phone' => $inquiry->phone,
                'email' => $inquiry->email,
                'age' => $inquiry->age,
                'address' => $inquiry->address,
                'diagnosis' => $inquiry->diagnosis,
                'is_past' => $isPast,
                'event_date' => $inquiry->next_follow_date,
                'patient_data' => $inquiry,
                'date_display' => $inquiryDate ? $inquiryDate->format('d-M-Y H:i') : 'N/A',
                'status' => $isPast ? 'Past Inquiry' : 'New Inquiry'
            ];
        });

       
        $followups = Followups::whereNotNull('next_follow_date')
            ->with('inquiry')
            ->get()
            ->map(function ($followup) use ($today) {
                $followupDate = $followup->next_follow_date ? Carbon::parse($followup->next_follow_date) : null;
                $isPast = $followupDate ? $followupDate->lt($today) : false;
                $patient = $followup->inquiry;
                
                return [
                    'id' => 'followup_' . $followup->id,
                    'type' => 'followup',
                    'event_id' => $followup->id,
                    'patient_id' => $followup->patient_id,
                    'patient_name' => $patient ? $patient->patient_name : 'Unknown',
                    'inquiry_date' => $patient ? $patient->inquiry_date : null,
                    'next_follow_date' => $followup->next_follow_date,
                    'branch' => $patient ? $patient->branch : 'SVC',
                    'branch_id' => $patient ? $patient->branch_id : 'SVC-0005',
                    'phone' => $patient ? $patient->phone : 'N/A',
                    'email' => $patient ? $patient->email : 'N/A',
                    'age' => $patient ? $patient->age : 'N/A',
                    'address' => $patient ? $patient->address : 'N/A',
                    'diagnosis' => $patient ? $patient->diagnosis : 'N/A',
                    'is_past' => $isPast,
                    'event_date' => $followup->next_follow_date, // Follow-up date as event date
                    'patient_data' => $patient,
                    'followup_data' => $followup,
                    'date_display' => $followupDate ? $followupDate->format('d-M-Y H:i') : 'N/A',
                    'status' => $isPast ? 'Past Follow-up' : 'Upcoming Follow-up'
                ];
            });

      
        $allEvents = $inquiries->merge($followups)->sortBy('event_date');


        $eventsByDate = $allEvents->groupBy(function($event) {
            $eventDate = Carbon::parse($event['event_date']);
            return $eventDate->format('Y-m-d');
        });

     
        $filteredEvents = $allEvents->filter(function ($item) use ($view, $currentCarbon, $currentYear, $currentMonth) {
            if (empty($item['event_date'])) return false;
            
            $eventDate = Carbon::parse($item['event_date']);
            
            switch ($view) {
                case 'day':
                    return $eventDate->format('Y-m-d') == $currentCarbon->format('Y-m-d');
                    
                case 'week':
                    $startOfWeek = $currentCarbon->copy()->startOfWeek();
                    $endOfWeek = $currentCarbon->copy()->endOfWeek();
                    return $eventDate->between($startOfWeek, $endOfWeek);
                    
                case 'month':
                default:
                    return $eventDate->year == $currentYear && $eventDate->month == $currentMonth;
            }
        });

   
        if ($view == 'day' || $view == 'week') {
            $filteredEvents = $filteredEvents->sortBy('event_date');
        }

        
        $followUps = $filteredEvents;

        return view('calendar.followup', compact(
            'view',
            'currentYear',
            'currentMonth',
            'currentDate',
            'currentCarbon',
            'allEvents',
            'filteredEvents',
            'followUps',
            'eventsByDate',
            'today'
        ));
    }


    public function getInquiryDetails($id)
    {
        try {
            Log::info('Fetching patient details for ID: ' . $id);

            $patient = null;
            $eventType = '';
            $eventDate = null;
            $isPast = false;
            $today = Carbon::today();
            $additionalData = [];

        
            if (strpos($id, 'inquiry_') === 0) {
                $patientId = str_replace('inquiry_', '', $id);
                $patient = PatientInquiry::find($patientId);
                $eventType = 'inquiry';
                $eventDate = $patient ? ($patient->next_follow_date ?: $patient->inquiry_date) : null;
                if ($eventDate) {
                    $eventDateCarbon = Carbon::parse($eventDate);
                    $isPast = $eventDateCarbon->lt($today);
                }
            } elseif (strpos($id, 'followup_') === 0) {
                $followupId = str_replace('followup_', '', $id);
                $followup = Followups::with('inquiry')->find($followupId);
                if ($followup && $followup->inquiry) {
                    $patient = $followup->inquiry;
                    $eventType = 'followup';
                    $eventDate = $followup->next_follow_date;
                    if ($eventDate) {
                        $eventDateCarbon = Carbon::parse($eventDate);
                        $isPast = $eventDateCarbon->lt($today);
                    }
                    $additionalData['followup_id'] = $followup->id;
                }
            } else {
                $patient = PatientInquiry::find($id);
                $eventType = 'inquiry';
                $eventDate = $patient ? ($patient->next_follow_date ?: $patient->inquiry_date) : null;
                if ($eventDate) {
                    $eventDateCarbon = Carbon::parse($eventDate);
                    $isPast = $eventDateCarbon->lt($today);
                }
            }

            if (!$patient) {
                Log::error('Patient not found with ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'error' => 'Patient not found'
                ], 404);
            }

            Log::info('Patient found: ' . $patient->patient_name);

            $patientData = [
                'id' => $patient->id,
                'event_id' => $id,
                'event_type' => $eventType,
                'patient_id' => $patient->patient_id,
                'patient_name' => $patient->patient_name,
                'inquiry_date' => $patient->inquiry_date ? Carbon::parse($patient->inquiry_date)->format('d-M-Y H:i') : 'N/A',
                'next_follow_date' => $patient->next_follow_date ? Carbon::parse($patient->next_follow_date)->format('d-M-Y H:i') : 'N/A',
                'event_date' => $eventDate ? Carbon::parse($eventDate)->format('d-M-Y H:i') : 'N/A',
                'branch' => $patient->branch ?: 'SVC',
                'branch_id' => $patient->branch_id ?: 'SVC-0005',
                'mobile' => $patient->mobile ?: 'N/A',
                'phone' => $patient->phone ?: 'N/A',
                'email' => $patient->email ?: 'N/A',
                'age' => $patient->age ?: 'N/A',
                'address' => $patient->address ?: 'N/A',
                'diagnosis' => $patient->diagnosis ?: 'N/A',
                'is_past' => $isPast,
                'status' => $isPast ? 'Past Event' : 'Upcoming Event'
            ];

         
            $patientData = array_merge($patientData, $additionalData);

            Log::info('Patient data prepared:', $patientData);

            return response()->json([
                'success' => true,
                'patient' => $patientData
            ]);

        } catch (\Exception $e) {
            Log::error('Patient details error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'error' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

  
    public function showPatientProfile($id)
    {
       
        $patient = PatientInquiry::findOrFail($id);
        return redirect()->route('svc.profile', ['id' => $patient->id]);
    }
}
