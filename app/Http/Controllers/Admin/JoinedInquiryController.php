<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccInquiry;
use Illuminate\Http\Request;

class JoinedInquiryController extends Controller
{
    public function joinedInquiry(Request $request)
    {
        // Show inquiries that have 'Joined' in their status_history 
        $query = AccInquiry::where('delete_status', '0')
            ->where(function ($sq) {
                $sq->whereJsonContains('status_history', 'Joined')
                   ->orWhere('status_history', 'like', '%Joined%');
            })
            ->where(function ($q) {
                $q->where('is_online_abroad', '!=', 1)
                  ->orWhereNull('is_online_abroad');
            });

        if (!auth()->user()->hasRole('Superadmin')) {
            $query->where('branch_id', auth()->user()->user_branch);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('patient_id', 'like', '%' . $search . '%')
                    ->orWhere('patient_f_name', 'like', '%' . $search . '%')
                    ->orWhere('phone_no', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%')
                    ->orWhere('diagnosis', 'like', '%' . $search . '%')
                    ->orWhereIn('patient_id', function ($subquery) use ($search) {
                        $subquery->select('patient_id')
                            ->from('opts')
                            ->join('opt_meta', 'opts.id', '=', 'opt_meta.opt_id')
                            ->where(function ($qq) use ($search) {
                                $qq->where(function ($qqq) use ($search) {
                                    $qqq->where('meta_key', 'selected_program')
                                        ->where('meta_value', 'like', '%' . $search . '%');
                                })
                                    ->orWhere(function ($qqq) use ($search) {
                                        $qqq->where('meta_key', 'programs_array')
                                            ->where('meta_value', 'like', '%' . $search . '%');
                                    });
                            });
                    });
            });
        }

        $inquiries = $query->orderBy('id', 'desc')->paginate(10);

        if ($request->ajax()) {
            return view('admin.inquiry.joined_inquiry_table', compact('inquiries'))->render();
        }

        return view('admin.inquiry.joined_inquiry', compact('inquiries'));
    }

    public function exportJoinedInquiries(Request $request)
    {
        $query = AccInquiry::where('delete_status', '0')
            ->where(function ($sq) {
                $sq->whereJsonContains('status_history', 'Joined')
                   ->orWhere('status_history', 'like', '%Joined%');
            })
            ->where(function ($q) {
                $q->where('is_online_abroad', '!=', 1)
                  ->orWhereNull('is_online_abroad');
            });

        if (!auth()->user()->hasRole('Superadmin')) {
            $query->where('branch_id', auth()->user()->user_branch);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('patient_id', 'like', '%' . $search . '%')
                    ->orWhere('patient_f_name', 'like', '%' . $search . '%')
                    ->orWhere('phone_no', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%')
                    ->orWhere('diagnosis', 'like', '%' . $search . '%')
                    ->orWhereIn('patient_id', function ($subquery) use ($search) {
                        $subquery->select('patient_id')
                            ->from('opts')
                            ->join('opt_meta', 'opts.id', '=', 'opt_meta.opt_id')
                            ->where(function ($qq) use ($search) {
                                $qq->where(function ($qqq) use ($search) {
                                    $qqq->where('meta_key', 'selected_program')
                                        ->where('meta_value', 'like', '%' . $search . '%');
                                })
                                    ->orWhere(function ($qqq) use ($search) {
                                        $qqq->where('meta_key', 'programs_array')
                                            ->where('meta_value', 'like', '%' . $search . '%');
                                    });
                            });
                    });
            });
        }

        $inquiries = $query->orderBy('created_at', 'desc')->get();

        if ($inquiries->isEmpty()) {
            return redirect()->route('joined.inquiry')->with('error', 'No joined patients found to export.');
        }

        $filename = 'joined_patients_export_' . date('Y-m-d_H-i') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $file = fopen('php://output', 'w');

        $headers = ['Patient ID', 'Patient Name', 'Phone Number', 'Address', 'Branch', 'Diagnosis', 'Reference By', 'Inquiry By', 'Date Joined'];
        fputcsv($file, $headers);

        foreach ($inquiries as $inquiry) {
            $row = [
                $inquiry->patient_id ?? 'N/A',
                $inquiry->patient_name ?? 'N/A',
                $inquiry->phone_no ?? 'N/A',
                $inquiry->address ?? 'N/A',
                $inquiry->branch ?? 'N/A',
                $inquiry->diagnosis ?? 'N/A',
                $inquiry->refrance ?? 'N/A',
                $inquiry->inquery_given_by ?? 'N/A',
                $inquiry->date ? date('d/m/Y', strtotime($inquiry->date)) : 'N/A',
            ];
            fputcsv($file, $row);
        }

        fclose($file);
        exit;
    }
}
