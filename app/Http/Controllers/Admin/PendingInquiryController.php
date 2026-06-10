<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PendingInquiryController extends Controller
{
    public function pendingInquiry(Request $request)
    {
        // Show inquiries that have 'Pending' but NOT 'Diet Chart' or 'Joined'
        $query = AccInquiry::where('delete_status', '0')
                            ->whereJsonContains('status_history', 'Pending')
                            ->whereJsonDoesntContain('status_history', 'Diet Chart')
                            ->whereJsonDoesntContain('status_history', 'Joined')
                            ->where(function ($q) {
                                $q->where('is_online_abroad', '!=', 1)
                                  ->orWhereNull('is_online_abroad');
                            });

        if (!auth()->user()->hasRole('Superadmin')) {
            $query->where('branch_id', auth()->user()->user_branch);
        }
    
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
    
        if ($request->ajax()) {
            return view('admin.inquiry.pending_inquiry_table', compact('inquiries'))->render();
        }

        return view('admin.inquiry.pending_inquiry', compact('inquiries'));
    }

public function exportPendingInquiries(Request $request)
{
       $query = AccInquiry::where('delete_status', '0')
                        ->whereJsonContains('status_history', 'Pending')
                        ->whereJsonDoesntContain('status_history', 'Diet Chart')
                        ->whereJsonDoesntContain('status_history', 'Joined')
                        ->where(function ($q) {
                            $q->where('is_online_abroad', '!=', 1)
                              ->orWhereNull('is_online_abroad');
                        });

    if (!auth()->user()->hasRole('Superadmin')) {
        $query->where('branch_id', auth()->user()->user_branch);
    }

    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('patient_id', 'like', '%' . $search . '%')
              ->orWhere('patient_f_name', 'like', '%' . $search . '%')
              ->orWhere('phone_no', 'like', '%' . $search . '%')
              ->orWhere('address', 'like', '%' . $search . '%')
              ->orWhere('diagnosis', 'like', '%' . $search . '%');
        });
    }

    $inquiries = $query->orderBy('created_at', 'desc')->get();

    if ($inquiries->isEmpty()) {
        return redirect()->route('pending.inquiry')->with('error', 'No pending inquiries found to export.');
    }

    $filename = 'pending_inquiries_export_' . date('Y-m-d_H-i') . '.csv';

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $file = fopen('php://output', 'w');

    $headers = ['Patient ID', 'Patient Name', 'Phone Number', 'Address', 'Branch', 'Diagnosis', 'Reference By', 'Inquiry By'];
    fputcsv($file, $headers);

    foreach ($inquiries as $inquiry) {
        $row = [
            $inquiry->patient_id ?? 'N/A',
            $inquiry->patient_f_name     ?? 'N/A',
            $inquiry->phone_no ?? 'N/A',
            $inquiry->address ?? 'N/A',
            $inquiry->branch ?? 'N/A',
            $inquiry->diagnosis ?? 'N/A',
            $inquiry->refrance ?? 'N/A',
            $inquiry->inquery_given_by ?? 'N/A',
        ];
        fputcsv($file, $row);
    }

    fclose($file);
    exit;
}
}
