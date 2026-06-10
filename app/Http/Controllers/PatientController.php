<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PatientController extends Controller
{


    /**
     * Get patient list (names + details) filtered by date range.
     * Returns patients from all tables for the given branch.
     */
    public function getPatientList(Request $request)
    {
        try {
            $user       = Auth::user();
            $branchId   = $request->input('branch_id');
            $branchName = $request->input('branch_name');
            $fromDate   = $request->input('from_date');
            $toDate     = $request->input('to_date');
            $groupBy    = $request->input('group_by', 'day'); // 'day' or 'month'
            $status     = $request->input('status', 'all'); // 'all', 'pending', 'joined', 'diet_chart', 'online_abroad'

            // Resolve branch
            if (!$branchId && $branchName) {
                $branch = DB::table('branches')->where('branch_name', $branchName)->first();
                if ($branch) { $branchId = $branch->branch_id; $branchName = $branch->branch_name; }
            }

            if (!$user->hasRole('Superadmin')) {
                $b = DB::table('branches')
                    ->where('branch_name', $user->user_branch)
                    ->orWhere('branch_id', $user->user_branch)
                    ->first();
                if ($b) { $branchId = $b->branch_id; $branchName = $b->branch_name; }
                else    { return response()->json(['success' => true, 'groups' => []]); }
            }

            $prefix = $branchId ? explode('-', $branchId)[0] : null;
            $dateFmt = $groupBy === 'month' ? '%Y-%m' : '%Y-%m-%d';

            // ── patient_inquiry ──────────────────────────────────────────────
            $svcQ = DB::table('patient_inquiry')
                ->selectRaw("patient_id, patient_name, inquiry_date, address, age, diagnosis, 'SVC' as source")
                ->whereNull('deleted_at');
            if ($branchId) $svcQ->where('branch_id', $branchId);
            if ($fromDate && $toDate) $svcQ->whereBetween('inquiry_date', [$fromDate, $toDate]);

            if ($status === 'pending' || $status === 'diet_chart' || $status === 'online_abroad') {
                $svcQ->whereRaw('1 = 0');
            }

            // ── lhr_inquiries ────────────────────────────────────────────────
            $lhrQ = DB::table('lhr_inquiries')
                ->selectRaw("patient_id, patient_name, inquiry_date, address, age, '' as diagnosis, 'LHR' as source")
                ->whereNull('deleted_at');
            if ($branchId) {
                $lhrQ->where(function($q) use ($branchId, $prefix) {
                    $q->where('branch_id', $branchId)->orWhere('branch', 'LIKE', "%$prefix%");
                });
            }
            if ($fromDate && $toDate) $lhrQ->whereBetween('inquiry_date', [$fromDate, $toDate]);

            if ($status === 'pending') {
                $lhrQ->where(function($q) { $q->where('status_name', 'Pending')->orWhere('status_name', 'pending'); });
            } elseif ($status === 'joined') {
                $lhrQ->where(function($q) { $q->where('status_name', 'Joined')->orWhere('status_name', 'joined'); });
            } elseif ($status === 'diet_chart' || $status === 'online_abroad') {
                $lhrQ->whereRaw('1 = 0');
            }

            // ── hydra_inquiries ──────────────────────────────────────────────
            $hydraQ = DB::table('hydra_inquiries')
                ->selectRaw("patient_id, patient_name, inquiry_date, address, age, '' as diagnosis, 'HYDRA' as source");
            if ($branchId) {
                $hydraQ->where(function($q) use ($branchId, $prefix) {
                    $q->where('branch_id', $branchId)->orWhere('branch', 'LIKE', "%$prefix%");
                });
            }
            if ($fromDate && $toDate) $hydraQ->whereBetween('inquiry_date', [$fromDate, $toDate]);

            if ($status === 'pending') {
                $hydraQ->where(function($q) { $q->where('status_name', 'Pending')->orWhere('status_name', 'pending'); });
            } elseif ($status === 'joined') {
                $hydraQ->where(function($q) { $q->where('status_name', 'Joined')->orWhere('status_name', 'joined'); });
            } elseif ($status === 'diet_chart' || $status === 'online_abroad') {
                $hydraQ->whereRaw('1 = 0');
            }

            // ── acc_inquirys ─────────────────────────────────────────────────
            $accQ = DB::table('acc_inquirys')
                ->selectRaw("patient_id,
                    CONCAT(COALESCE(patient_f_name,''), ' ', COALESCE(patient_m_name,''), ' ', COALESCE(patient_l_name,'')) as patient_name,
                    inquiry_date, address, age, diagnosis, 'ACC' as source")
                ->where('delete_status', '0');
            if ($branchId) {
                $accQ->where(function($q) use ($branchId, $prefix) {
                    $q->where('branch_id', $branchId)->orWhere('branch', 'LIKE', "%$prefix%");
                });
            }
            if ($fromDate && $toDate) $accQ->whereBetween('inquiry_date', [$fromDate, $toDate]);

            if ($status === 'pending') {
                $accQ->whereJsonContains('status_history', 'Pending')
                     ->whereJsonDoesntContain('status_history', 'Diet Chart')
                     ->whereJsonDoesntContain('status_history', 'Joined')
                     ->where(function ($q) {
                         $q->where('is_online_abroad', '!=', 1)->orWhereNull('is_online_abroad');
                     });
            } elseif ($status === 'joined') {
                $accQ->whereJsonContains('status_history', 'Joined')
                     ->where(function ($q) {
                         $q->where('is_online_abroad', '!=', 1)->orWhereNull('is_online_abroad');
                     });
            } elseif ($status === 'diet_chart') {
                $accQ->where(function($q) {
                    $q->whereJsonContains('status_history', 'Diet Chart')
                      ->orWhereJsonContains('status_history', 'Active');
                })->where(function ($q) {
                    $q->where('is_online_abroad', '!=', 1)->orWhereNull('is_online_abroad');
                });
            } elseif ($status === 'online_abroad') {
                $accQ->where('is_online_abroad', 1);
            }

            // Merge all results
            $all = collect()
                ->merge($svcQ->get())
                ->merge($lhrQ->get())
                ->merge($hydraQ->get())
                ->merge($accQ->get())
                ->sortBy('inquiry_date');

            // Group by period
            $grouped = $all->groupBy(function ($row) use ($dateFmt, $groupBy) {
                $date = $row->inquiry_date;
                if (!$date) return 'Unknown';
                try {
                    return $groupBy === 'month'
                        ? \Carbon\Carbon::parse($date)->format('Y-m')
                        : \Carbon\Carbon::parse($date)->format('Y-m-d');
                } catch (\Exception $e) { return 'Unknown'; }
            });

            $result = $grouped->map(function ($patients, $period) use ($groupBy) {
                $label = $period;
                if ($period !== 'Unknown') {
                    try {
                        $label = $groupBy === 'month'
                            ? \Carbon\Carbon::createFromFormat('Y-m', $period)->format('F Y')
                            : \Carbon\Carbon::createFromFormat('Y-m-d', $period)->format('d M Y');
                    } catch (\Exception $e) {}
                }

                return [
                    'period'   => $period,
                    'label'    => $label,
                    'count'    => $patients->count(),
                    'patients' => $patients->map(function ($p) {
                        return [
                            'patient_id'   => $p->patient_id ?? '—',
                            'patient_name' => trim($p->patient_name) ?: 'N/A',
                            'inquiry_date' => $p->inquiry_date
                                ? \Carbon\Carbon::parse($p->inquiry_date)->format('d M Y')
                                : '—',
                            'age'          => $p->age ?? '—',
                            'address'      => $p->address ?? '—',
                            'diagnosis'    => $p->diagnosis ?? '—',
                            'source'       => $p->source,
                        ];
                    })->values(),
                ];
            })->sortKeys()->values();

            return response()->json([
                'success'     => true,
                'groups'      => $result,
                'total'       => $all->count(),
                'branch_name' => $branchName,
                'from_date'   => $fromDate,
                'to_date'     => $toDate,
                'group_by'    => $groupBy,
            ]);

        } catch (\Exception $e) {
            Log::error('getPatientList error: ' . $e->getMessage());
            return response()->json(['success' => false, 'groups' => [], 'error' => $e->getMessage()], 500);
        }
    }

    public function getTotalPatients(Request $request)
    {
        try {
            $user = Auth::user();
            $branchId = $request->input('branch_id');
            $branchName = $request->input('branch_name');
            $status = $request->input('status', 'all');

            // If branch_name is provided, convert it to branch_id
            if (!$branchId && $branchName) {
                $branch = DB::table('branches')
                    ->where('branch_name', $branchName)
                    ->first();

                if ($branch) {
                    $branchId = $branch->branch_id;
                }
            }

            // Get branch ID based on user role
            if (!$user->hasRole('Superadmin')) {
                $userBranch = $user->user_branch;
                $branch = DB::table('branches')
                    ->where('branch_name', $userBranch)
                    ->orWhere('branch_id', $userBranch)
                    ->first();

                if ($branch) {
                    $branchId = $branch->branch_id;
                    $branchName = $branch->branch_name;
                } else {
                    return response()->json([
                        'success' => true,
                        'patient_count' => 0,
                        'branch_id' => $branchId,
                        'message' => 'No branch found for user'
                    ]);
                }
            }


            $totalCount = 0;


            $patientInquiryQuery = DB::table('patient_inquiry')
                ->when($branchId, function ($query) use ($branchId) {
                    return $query->where('branch_id', $branchId);
                });

            if ($status === 'pending' || $status === 'diet_chart' || $status === 'online_abroad') {
                $patientInquiryQuery->whereRaw('1 = 0');
            }

            $patientInquiryCount = $patientInquiryQuery->count();


            $lhrQuery = DB::table('lhr_inquiries')
                ->when($branchId, function ($query) use ($branchId) {

                    return $query->where(function ($q) use ($branchId) {
                        $q->where('branch_id', $branchId)
                            ->orWhere('branch', 'LIKE', '%' . explode('-', $branchId)[0] . '%');
                    });
                });

            if ($status === 'pending') {
                $lhrQuery->where(function($q) { $q->where('status_name', 'Pending')->orWhere('status_name', 'pending'); });
            } elseif ($status === 'joined') {
                $lhrQuery->where(function($q) { $q->where('status_name', 'Joined')->orWhere('status_name', 'joined'); });
            } elseif ($status === 'diet_chart' || $status === 'online_abroad') {
                $lhrQuery->whereRaw('1 = 0');
            }

            $lhrCount = $lhrQuery->count();


            $hydraQuery = DB::table('hydra_inquiries')
                ->when($branchId, function ($query) use ($branchId) {

                    return $query->where(function ($q) use ($branchId) {
                        $q->where('branch_id', $branchId)
                            ->orWhere('branch', 'LIKE', '%' . explode('-', $branchId)[0] . '%');
                    });
                });

            if ($status === 'pending') {
                $hydraQuery->where(function($q) { $q->where('status_name', 'Pending')->orWhere('status_name', 'pending'); });
            } elseif ($status === 'joined') {
                $hydraQuery->where(function($q) { $q->where('status_name', 'Joined')->orWhere('status_name', 'joined'); });
            } elseif ($status === 'diet_chart' || $status === 'online_abroad') {
                $hydraQuery->whereRaw('1 = 0');
            }

            $hydraCount = $hydraQuery->count();

            $accQuery = DB::table('acc_inquirys')
                ->where('delete_status', '0')
                ->when($branchId, function ($query) use ($branchId) {
                    $prefix = explode('-', $branchId)[0];

                    $query->where(function ($q) use ($branchId, $prefix) {
                        $q->where('branch_id', $branchId)
                            ->orWhere('branch', 'LIKE', '%' . $prefix . '%');
                    });
                });

            if ($status === 'pending') {
                $accQuery->whereJsonContains('status_history', 'Pending')
                         ->whereJsonDoesntContain('status_history', 'Diet Chart')
                         ->whereJsonDoesntContain('status_history', 'Joined')
                         ->where(function ($q) {
                             $q->where('is_online_abroad', '!=', 1)->orWhereNull('is_online_abroad');
                         });
            } elseif ($status === 'joined') {
                $accQuery->whereJsonContains('status_history', 'Joined')
                         ->where(function ($q) {
                             $q->where('is_online_abroad', '!=', 1)->orWhereNull('is_online_abroad');
                         });
            } elseif ($status === 'diet_chart') {
                $accQuery->where(function($q) {
                             $q->whereJsonContains('status_history', 'Diet Chart')
                               ->orWhereJsonContains('status_history', 'Active');
                         })
                         ->where(function ($q) {
                             $q->where('is_online_abroad', '!=', 1)->orWhereNull('is_online_abroad');
                         });
            } elseif ($status === 'online_abroad') {
                $accQuery->where('is_online_abroad', 1);
            }

            $acccount = $accQuery->count();

            $totalCount = $patientInquiryCount + $lhrCount + $hydraCount + $acccount;

            return response()->json([
                'success' => true,
                'patient_count' => $totalCount,
                'branch_id' => $branchId,
                'branch_name' => $branchName,
                'breakdown' => [
                    'patient_inquiry' => $patientInquiryCount,
                    'lhr_inquiries' => $lhrCount,
                    'hydra_inquiries' => $hydraCount,
                    'acc_inquirys'  => $acccount
                ],
                'message' => 'Total patients count from all sources'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getTotalPatients: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'patient_count' => 0,
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getPatientsByPeriod(Request $request)
    {
        try {
            $user = Auth::user();
            $branchId = $request->input('branch_id');
            $branchName = $request->input('branch_name');
            $fromDate = $request->input('from_date');
            $toDate = $request->input('to_date');
            $groupBy = $request->input('group_by', 'day'); // 'day' or 'month'

            if (!$branchId && $branchName) {
                $branch = DB::table('branches')->where('branch_name', $branchName)->first();
                if ($branch) {
                    $branchId = $branch->branch_id;
                    $branchName = $branch->branch_name;
                }
            }

            if (!$user->hasRole('Superadmin')) {
                $userBranch = $user->user_branch;
                $branch = DB::table('branches')
                    ->where('branch_name', $userBranch)
                    ->orWhere('branch_id', $userBranch)
                    ->first();

                if ($branch) {
                    $branchId = $branch->branch_id;
                    $branchName = $branch->branch_name;
                } else {
                    return response()->json(['success' => true, 'data' => [], 'branch_id' => $branchId]);
                }
            }

            $format = $groupBy === 'month' ? '%Y-%m' : '%Y-%m-%d';
            $prefix = $branchId ? explode('-', $branchId)[0] : null;

            // Helper to build grouped query
            $buildQuery = function ($table, $dateCol = 'inquiry_date') use ($branchId, $prefix, $fromDate, $toDate, $format) {
                $query = DB::table($table)
                    ->selectRaw("DATE_FORMAT({$dateCol}, '{$format}') as period, COUNT(*) as count");

                if ($branchId) {
                    if ($table === 'patient_inquiry') {
                        $query->where('branch_id', $branchId);
                    } else {
                        $query->where(function ($q) use ($branchId, $prefix) {
                            $q->where('branch_id', $branchId)
                              ->orWhere('branch', 'LIKE', '%' . $prefix . '%');
                        });
                    }
                }

                if (!empty($fromDate) && !empty($toDate)) {
                    $query->whereBetween($dateCol, [$fromDate, $toDate]);
                }

                return $query->groupByRaw("DATE_FORMAT({$dateCol}, '{$format}')")
                             ->orderBy('period')
                             ->get()
                             ->keyBy('period');
            };

            $svcData    = $buildQuery('patient_inquiry');
            $lhrData    = $buildQuery('lhr_inquiries');
            $hydraData  = $buildQuery('hydra_inquiries');
            $accData    = $buildQuery('acc_inquirys');

            // Merge all periods
            $allPeriods = collect($svcData->keys())
                ->merge($lhrData->keys())
                ->merge($hydraData->keys())
                ->merge($accData->keys())
                ->unique()
                ->sort()
                ->values();

            $result = $allPeriods->map(function ($period) use ($svcData, $lhrData, $hydraData, $accData, $groupBy) {
                $svc   = $svcData->get($period)->count   ?? 0;
                $lhr   = $lhrData->get($period)->count   ?? 0;
                $hydra = $hydraData->get($period)->count ?? 0;
                $acc   = $accData->get($period)->count   ?? 0;
                $total = $svc + $lhr + $hydra + $acc;

                // Format label
                if ($groupBy === 'month') {
                    $label = \Carbon\Carbon::createFromFormat('Y-m', $period)->format('M Y');
                } else {
                    $label = \Carbon\Carbon::createFromFormat('Y-m-d', $period)->format('d M Y');
                }

                return [
                    'period'    => $period,
                    'label'     => $label,
                    'total'     => $total,
                    'breakdown' => compact('svc', 'lhr', 'hydra', 'acc'),
                ];
            })->values();

            return response()->json([
                'success'     => true,
                'data'        => $result,
                'group_by'    => $groupBy,
                'branch_id'   => $branchId,
                'branch_name' => $branchName,
                'from_date'   => $fromDate,
                'to_date'     => $toDate,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getPatientsByPeriod: ' . $e->getMessage());
            return response()->json(['success' => false, 'data' => [], 'error' => $e->getMessage()], 500);
        }
    }

    public function getFilteredPatients(Request $request)
    {
        try {
            $user = Auth::user();
            $branchId = $request->input('branch_id');
            $branchName = $request->input('branch_name');
            $fromDate = $request->input('from_date');
            $toDate = $request->input('to_date');
            $status = $request->input('status', 'all');


            if (!$branchId && $branchName) {
                $branch = DB::table('branches')
                    ->where('branch_name', $branchName)
                    ->first();

                if ($branch) {
                    $branchId = $branch->branch_id;
                    $branchName = $branch->branch_name;
                }
            }


            if (!$user->hasRole('Superadmin')) {
                $userBranch = $user->user_branch;
                $branch = DB::table('branches')
                    ->where('branch_name', $userBranch)
                    ->orWhere('branch_id', $userBranch)
                    ->first();

                if ($branch) {
                    $branchId = $branch->branch_id;
                    $branchName = $branch->branch_name;
                } else {
                    return response()->json([
                        'success' => true,
                        'patient_count' => 0,
                        'branch_id' => $branchId,
                        'from_date' => $fromDate,
                        'to_date' => $toDate,
                        'breakdown' => []
                    ]);
                }
            }


            $patientInquiryCount = 0;
            $lhrCount = 0;
            $hydraCount = 0;
            $totalCount = 0;
            $acccount = 0;

            $patientInquiryQuery = DB::table('patient_inquiry');

            if ($branchId) {
                $patientInquiryQuery->where('branch_id', $branchId);
            }

            if (!empty($fromDate) && !empty($toDate)) {
                $patientInquiryQuery->whereBetween('inquiry_date', [$fromDate, $toDate]);
            }

            if ($status === 'pending' || $status === 'diet_chart' || $status === 'online_abroad') {
                $patientInquiryQuery->whereRaw('1 = 0');
            }

            $patientInquiryCount = $patientInquiryQuery->count();


            $lhrQuery = DB::table('lhr_inquiries');

            if ($branchId) {
                $lhrQuery->where(function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId)
                        ->orWhere('branch', 'LIKE', '%' . explode('-', $branchId)[0] . '%');
                });
            }

            if (!empty($fromDate) && !empty($toDate)) {
                $lhrQuery->whereBetween('inquiry_date', [$fromDate, $toDate]);
            }

            if ($status === 'pending') {
                $lhrQuery->where(function($q) { $q->where('status_name', 'Pending')->orWhere('status_name', 'pending'); });
            } elseif ($status === 'joined') {
                $lhrQuery->where(function($q) { $q->where('status_name', 'Joined')->orWhere('status_name', 'joined'); });
            } elseif ($status === 'diet_chart' || $status === 'online_abroad') {
                $lhrQuery->whereRaw('1 = 0');
            }

            $lhrCount = $lhrQuery->count();


            $hydraQuery = DB::table('hydra_inquiries');

            if ($branchId) {
                $hydraQuery->where(function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId)
                        ->orWhere('branch', 'LIKE', '%' . explode('-', $branchId)[0] . '%');
                });
            }

            if (!empty($fromDate) && !empty($toDate)) {
                $hydraQuery->whereBetween('inquiry_date', [$fromDate, $toDate]);
            }

            if ($status === 'pending') {
                $hydraQuery->where(function($q) { $q->where('status_name', 'Pending')->orWhere('status_name', 'pending'); });
            } elseif ($status === 'joined') {
                $hydraQuery->where(function($q) { $q->where('status_name', 'Joined')->orWhere('status_name', 'joined'); });
            } elseif ($status === 'diet_chart' || $status === 'online_abroad') {
                $hydraQuery->whereRaw('1 = 0');
            }

            $hydraCount = $hydraQuery->count();

            $accQuery = DB::table('acc_inquirys')
                ->where('delete_status', '0');

            if ($branchId) {
                $accQuery->where(function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId)
                        ->orWhere('branch', 'LIKE', '%' . explode('-', $branchId)[0] . '%');
                });
            }

            if (!empty($fromDate) && !empty($toDate)) {
                $accQuery->whereBetween('inquiry_date', [$fromDate, $toDate]);
            }

            if ($status === 'pending') {
                $accQuery->whereJsonContains('status_history', 'Pending')
                         ->whereJsonDoesntContain('status_history', 'Diet Chart')
                         ->whereJsonDoesntContain('status_history', 'Joined')
                         ->where(function ($q) {
                             $q->where('is_online_abroad', '!=', 1)->orWhereNull('is_online_abroad');
                         });
            } elseif ($status === 'joined') {
                $accQuery->whereJsonContains('status_history', 'Joined')
                         ->where(function ($q) {
                             $q->where('is_online_abroad', '!=', 1)->orWhereNull('is_online_abroad');
                         });
            } elseif ($status === 'diet_chart') {
                $accQuery->where(function($q) {
                             $q->whereJsonContains('status_history', 'Diet Chart')
                               ->orWhereJsonContains('status_history', 'Active');
                         })
                         ->where(function ($q) {
                             $q->where('is_online_abroad', '!=', 1)->orWhereNull('is_online_abroad');
                         });
            } elseif ($status === 'online_abroad') {
                $accQuery->where('is_online_abroad', 1);
            }

            $acccount = $accQuery->count();


            $totalCount = $patientInquiryCount + $lhrCount + $hydraCount + $acccount;

            return response()->json([
                'success' => true,
                'patient_count' => $totalCount,
                'branch_id' => $branchId,
                'branch_name' => $branchName,
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'breakdown' => [
                    'patient_inquiry' => $patientInquiryCount,
                    'lhr_inquiries' => $lhrCount,
                    'hydra_inquiries' => $hydraCount,
                    'acc_inquirys' => $acccount
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getFilteredPatients: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'patient_count' => 0,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
