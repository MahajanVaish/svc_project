<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatientTransaction;
use App\Models\PatientInquiry;
use App\Models\Invoice;
use App\Models\LHRInquiry;
use App\Models\HydraInquiry;
use App\Models\AccInquiry;
use App\Models\Branch;
use App\Models\Opt;
use App\Models\OptMeta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class PatientTransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $branchId = !$user->hasRole('Superadmin') ? $user->user_branch : null;

        // Group by patient_id and invoice_id to show per-program/invoice totals separately
        $query = PatientTransaction::query()
            ->select(
                'patient_id',
                'invoice_id',
                DB::raw('MAX(created_at) as last_transaction'),
                DB::raw('SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as total_billed'),
                DB::raw('SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as total_paid'),
                DB::raw('SUM(CASE WHEN type = "discount" THEN amount ELSE 0 END) as total_discount'),
                DB::raw('SUM(CASE WHEN type = "credit" THEN 1 ELSE 0 END) as payment_count')
            )
            ->with(['invoice', 'patient']);

        // Apply Branch Filtering based on Role/Branch (Collision-Aware)
        $branchFilter = function($q) use ($branchId) {
            $q->whereHas('patient', function($subQ) use ($branchId) {
                $subQ->where('branch_id', $branchId);
                // Add PP-0002 access for SVC users
                if ($branchId === 'SVC-0005') {
                    $subQ->orWhere('branch_id', 'PP-0002');
                }
            })
            ->orWhereHas('invoice', function($subQ) use ($branchId) {
                $subQ->where('branch_id', $branchId);
                // Add PP-0002 access for SVC users
                if ($branchId === 'SVC-0005') {
                    $subQ->orWhere('branch_id', 'PP-0002');
                }
            });
        };

        if ($branchId) {
            $query->where($branchFilter);
        }

        // Advanced Date Filtering
        $startDate = null;
        $endDate = null;

        if ($request->filled('date_filter')) {
            switch ($request->date_filter) {
                case 'today':
                    $startDate = Carbon::today()->startOfDay();
                    $endDate = Carbon::today()->endOfDay();
                    break;
                case 'week':
                    $startDate = Carbon::now()->startOfWeek();
                    $endDate = Carbon::now()->endOfWeek();
                    break;
                case 'month':
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    break;
                case 'custom':
                    if ($request->filled('start_date') && $request->filled('end_date')) {
                        $startDate = Carbon::parse($request->start_date)->startOfDay();
                        $endDate = Carbon::parse($request->end_date)->endOfDay();
                    }
                    break;
            }
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('patient_name', 'like', "%{$search}%")
                  ->orWhere('patient_id', 'like', "%{$search}%");
            });
        }

        $summary = $query->groupBy('patient_id', 'invoice_id')
            ->orderBy('last_transaction', 'desc')
            ->orderBy(DB::raw('MAX(id)'), 'desc')
            ->paginate(10);

        // Attach resolved patient and correct branch to each summary row
        $summary->getCollection()->transform(function($row) use ($branchId) {
            // Find an invoice to determine the correct branch for this patient session
            $sampleInvoice = Invoice::withoutGlobalScope('branch_restriction')
                ->where('patient_id', $row->patient_id)
                ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
                ->latest()
                ->first();
            
            $row->branch_id = $row->invoice ? $row->invoice->branch_id : ($sampleInvoice->branch_id ?? 'Unknown');
            
            // We need a temporary model instance to use the resolution logic
            $tempInvoice = new Invoice();
            $tempInvoice->patient_id = $row->patient_id;
            $tempInvoice->branch_id = $row->branch_id;
            
            $row->patient = $tempInvoice->resolved_patient;
            $row->balance = $row->total_billed - $row->total_paid - $row->total_discount;

            // Resolve profile image url (if available) for consistent list avatars
            $row->profile_image_url = null;
            if ($row->patient) {
                // Hydra/LHR store profile_image in public storage disk
                if ($row->patient instanceof HydraInquiry || $row->patient instanceof LHRInquiry) {
                    $img = $row->patient->profile_image ?? null;
                    if ($img && Storage::disk('public')->exists($img)) {
                        $row->profile_image_url = asset('storage/' . $img);
                    }
                }
                // SVC PatientInquiry stores profile_image as meta in public/uploads
                elseif ($row->patient instanceof PatientInquiry) {
                    $img = $row->patient->getMeta('profile_image');
                    if ($img && file_exists(public_path($img))) {
                        $row->profile_image_url = asset($img);
                    }
                }
                // AccInquiry and other fallbacks store profile_image in Opt meta
                else {
                    $pid = $row->patient->patient_id ?? null;
                    if ($pid) {
                        $optIds = Opt::where('patient_id', $pid)
                            ->where(function ($q) {
                                $q->whereNull('delete_status')
                                  ->orWhere('delete_status', '')
                                  ->orWhere('delete_status', '0');
                            })
                            ->pluck('id');

                        if ($optIds->isNotEmpty()) {
                            $img = OptMeta::whereIn('opt_id', $optIds)
                                ->where('meta_key', 'profile_image')
                                ->orderByDesc('id')
                                ->value('meta_value');

                            if ($img && file_exists(public_path($img))) {
                                $row->profile_image_url = asset($img);
                            }
                        }
                    }
                }
            }
            
            return $row;
        });
        
        return view('admin.finance.transactions', compact('summary'));
    }

    public function ledger($patient_id, $branch_id)
    {
        // Security check
        $user = auth()->user();
        if (!$user->hasRole('Superadmin') && $user->user_branch !== $branch_id) {
            return abort(403);
        }

        // Resolve Patient info
        $tempInvoice = new Invoice();
        $tempInvoice->patient_id = $patient_id;
        $tempInvoice->branch_id  = $branch_id;
        $patient = $tempInvoice->resolved_patient;

        if (!$patient) return abort(404, 'Patient not found');

        // --- Online/Abroad program label lookup ---
        // Try to find the patient's program label stored in OptMeta
        $onlineProgramLabel = null;
        $accPatient = AccInquiry::find($patient_id);
        if ($accPatient && $accPatient->is_online_abroad) {
            $optIds = Opt::where('patient_id', $accPatient->patient_id)->pluck('id');
            if ($optIds->isNotEmpty()) {
                // 1. Try the new key saved by our fix
                $label = OptMeta::whereIn('opt_id', $optIds)
                    ->where('meta_key', 'online_program_label')
                    ->orderByDesc('id')
                    ->value('meta_value');

                // 2. Fallback to program_name
                if (!$label) {
                    $label = OptMeta::whereIn('opt_id', $optIds)
                        ->where('meta_key', 'program_name')
                        ->orderByDesc('id')
                        ->value('meta_value');
                }

                // 3. Last resort: infer from invoice total_payment amount
                if (!$label) {
                    $firstInvoice = Invoice::where('patient_id', $patient_id)
                        ->where('branch_id', $branch_id)
                        ->orderBy('id')
                        ->first();
                    if ($firstInvoice) {
                        $amt = (int) $firstInvoice->total_payment;
                        if ($amt === 21000)      $label = '3 Months Program - ₹21,000';
                        elseif ($amt === 26000)  $label = '4 Months Program - ₹26,000';
                        elseif ($amt === 36000)  $label = '6 Months Program - ₹36,000';
                        else                     $label = 'Online/Abroad Program';
                    }
                }

                $onlineProgramLabel = $label;
            }
        }

        // Fetch all transactions
        $transactions = PatientTransaction::with(['program', 'invoice'])
            ->where('patient_id', $patient_id)
            ->where(function($q) use ($branch_id) {
                $q->whereHas('invoice', function($sq) use ($branch_id) {
                    $sq->where('branch_id', $branch_id);
                })->orWhereHas('patient', function($sq) use ($branch_id) {
                    $sq->where('branch_id', $branch_id);
                });
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Calculate running balance and totals
        $totalBilled = 0;
        $totalPaid = 0;
        $totalDiscount = 0;
        $runningBalance = 0;

        // Grouping logic for "Program Receipts"
        $programGroups = [];

        foreach ($transactions as $t) {
            if ($t->type == 'debit') {
                $totalBilled += $t->amount;
                $runningBalance += $t->amount;
            } elseif ($t->type == 'discount') {
                $totalDiscount += $t->amount;
                $runningBalance -= $t->amount;
            } else {
                $totalPaid += $t->amount;
                $runningBalance -= $t->amount;
            }
            $t->running_balance = $runningBalance;

            // Grouping by Invoice ID to keep program purchases/periods separate
            $groupKey = $t->invoice_id ?? 'general';
            if (!isset($programGroups[$groupKey])) {
                // Determine Default Service Label based on Branch
                $defaultServiceName = 'FNF Service';
                if ($branch_id === 'LB-0007') {
                    $defaultServiceName = 'LHR Service';
                } elseif ($branch_id === 'BH-00023') {
                    $defaultServiceName = 'Hydra Service';
                } elseif ($branch_id === 'SVC-0005') {
                    $defaultServiceName = 'SVC Service';
                } elseif ($branch_id === 'PP-0002') {
                    $defaultServiceName = 'FNF PP Service';
                }

                $programName = $defaultServiceName;
                if ($t->invoice) {
                    $programsData = $t->invoice->programs_data;
                    if (is_string($programsData)) $programsData = json_decode($programsData, true);
                    
                    $chargesData = $t->invoice->charges_data;
                    if (is_string($chargesData)) $chargesData = json_decode($chargesData, true);
                    
                    $names = [];
                    if (!empty($programsData) && is_array($programsData)) {
                        foreach ($programsData as $p) {
                            $names[] = $p['program_name'] ?? 'Service';
                        }
                    }
                    if (!empty($chargesData) && is_array($chargesData)) {
                        foreach ($chargesData as $c) {
                            $cName = $c['charge_name'] ?? 'Charge';
                            if (in_array($cName, ['Registration Charges', 'Registration', 'SVC-Charge', 'Followup Charges', 'Follow up charges', 'Consulting charges', 'Registration & Consultation Charges'])) {
                                if ($branch_id === 'LB-0007') $cName = 'LHR Service';
                                elseif ($branch_id === 'BH-00023') $cName = 'Hydra Service';
                                elseif ($branch_id === 'SVC-0005') $cName = 'SVC Service';
                                else $cName = 'FNF Service';
                            }
                            $names[] = $cName;
                        }
                    }
                    if (!empty($names)) {
                        $programName = implode(', ', array_unique($names));
                    } elseif ($t->invoice->program) {
                        $programName = $t->invoice->program->program_name;
                    } else {
                        $programName = $t->invoice->invoice_no;
                    }
                } elseif ($t->program) {
                    $programName = $t->program->program_name;
                } elseif (!empty($onlineProgramLabel)) {
                    $programName = $onlineProgramLabel;
                }

                $programGroups[$groupKey] = [
                    'program_name' => $programName,
                    'actual_price'    => 0,
                    'total_received'  => 0,
                    'total_discount'  => 0,
                    'payment_count'   => 0,
                    'last_payment'    => null,
                    'is_completed'    => false,
                    'invoice_no'      => $t->invoice ? $t->invoice->invoice_no : null
                ];
            }

            if ($t->type == 'debit') {
                $programGroups[$groupKey]['actual_price'] += $t->amount;
            } elseif ($t->type == 'discount') {
                $programGroups[$groupKey]['total_discount'] += $t->amount;
            } else {
                $programGroups[$groupKey]['total_received'] += $t->amount;
                $programGroups[$groupKey]['payment_count']++;
                $programGroups[$groupKey]['last_payment'] = $t->created_at;
            }
        }

        // Post-process to calculate completion correctly
        foreach ($programGroups as $groupKey => &$group) {
            $satisfiedAmount = $group['total_received'] + $group['total_discount'];
            if ($group['actual_price'] > 0 && $satisfiedAmount >= $group['actual_price']) {
                $group['is_completed'] = true;
            }
        }
        unset($group);

        // REVERSE transactions for "Latest First" display
        $transactions = $transactions->reverse();

        return view('admin.finance.ledger', compact(
            'patient',
            'transactions',
            'totalBilled',
            'totalPaid',
            'totalDiscount',
            'branch_id',
            'programGroups',
            'onlineProgramLabel'
        ));
    }

    /**
     * Delete all transactions and invoice for a patient session
     */
    public function deletePatientTransactions(\Illuminate\Http\Request $request)
    {
        try {
            $patientId = $request->input('patient_id');
            $invoiceId = $request->input('invoice_id');

            if (empty($patientId)) {
                return response()->json(['success' => false, 'message' => 'Patient ID required'], 400);
            }

            $deleted = 0;

            // Delete transactions for this specific invoice or all for patient
            if ($invoiceId) {
                $deleted += \App\Models\PatientTransaction::where('patient_id', $patientId)
                    ->where('invoice_id', $invoiceId)
                    ->delete();
                // Delete invoice
                \App\Models\Invoice::where('id', $invoiceId)->delete();
            } else {
                $deleted += \App\Models\PatientTransaction::where('patient_id', $patientId)->delete();
                \App\Models\Invoice::where('patient_id', $patientId)->delete();
            }

            return response()->json([
                'success' => true,
                'message' => "Deleted {$deleted} transaction(s) successfully."
            ]);
        } catch (\Exception $e) {
            \Log::error('Delete transaction error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function financialDashboard(Request $request)
    {
        $user = auth()->user();
        $isSuperadmin = $user->hasRole('Superadmin');
        $userBranch = $user->user_branch;

        $branches = Branch::where(function ($q) {
            $q->where('delete_status', '0')->orWhere('delete_status', '')->orWhereNull('delete_status');
        })->get();

        return view('admin.finance.financial_dashboard', compact('branches', 'isSuperadmin', 'userBranch'));
    }

    public function financialDashboardData(Request $request)
    {
        try {
            $user = auth()->user();
            $isSuperadmin = $user->hasRole('Superadmin');
            $requestedBranch = $request->input('branch_id');
            
            // Branch filtering logic
            $branchId = !$isSuperadmin ? $user->user_branch : ($requestedBranch ?: null);

            // Date filtering
            $dateFilter = $request->input('date_filter', 'all');
            $startDate = null;
            $endDate = null;

            if ($dateFilter === 'today') {
                $startDate = Carbon::today()->startOfDay();
                $endDate = Carbon::today()->endOfDay();
            } elseif ($dateFilter === 'week') {
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
            } elseif ($dateFilter === 'month') {
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
            } elseif ($dateFilter === 'year') {
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
            } elseif ($dateFilter === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $endDate = Carbon::parse($request->end_date)->endOfDay();
            }

            // Base query for transactions
            $baseQuery = PatientTransaction::query();
            if ($branchId) {
                $baseQuery->where(function($q) use ($branchId) {
                    $q->whereHas('patient', function($subQ) use ($branchId) {
                        $subQ->where('branch_id', $branchId);
                        if ($branchId === 'SVC-0005') $subQ->orWhere('branch_id', 'PP-0002');
                    })
                    ->orWhereHas('invoice', function($subQ) use ($branchId) {
                        $subQ->where('branch_id', $branchId);
                        if ($branchId === 'SVC-0005') $subQ->orWhere('branch_id', 'PP-0002');
                    });
                });
            }

            if ($startDate && $endDate) {
                $baseQuery->whereBetween('created_at', [$startDate, $endDate]);
            }

            // Calculations
            $totalBilled = (float) (clone $baseQuery)->where('type', 'debit')->sum('amount');
            $totalCollected = (float) (clone $baseQuery)->where('type', 'credit')->sum('amount');
            $totalDiscount = (float) (clone $baseQuery)->where('type', 'discount')->sum('amount');
            $totalDue = max(0, $totalBilled - $totalCollected - $totalDiscount);
            $collectionRate = $totalBilled > 0 ? round(($totalCollected / $totalBilled) * 100, 1) : 0;
            $transactionCount = (clone $baseQuery)->where('type', 'credit')->count();

            // Monthly breakdown trend (Chart & Table)
            $monthlyQuery = PatientTransaction::query();
            if ($branchId) {
                $monthlyQuery->where(function($q) use ($branchId) {
                    $q->whereHas('patient', fn($subQ) => $subQ->where('branch_id', $branchId))
                      ->orWhereHas('invoice', fn($subQ) => $subQ->where('branch_id', $branchId));
                });
            }
            if ($startDate && $endDate) {
                $monthlyQuery->whereBetween('created_at', [$startDate, $endDate]);
            }

            $monthlyTrendData = $monthlyQuery
                ->select(
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month_key"),
                    DB::raw("DATE_FORMAT(created_at, '%b %Y') as month_label"),
                    DB::raw("SUM(CASE WHEN type = 'debit' THEN amount ELSE 0 END) as billed"),
                    DB::raw("SUM(CASE WHEN type = 'credit' THEN amount ELSE 0 END) as collected"),
                    DB::raw("SUM(CASE WHEN type = 'discount' THEN amount ELSE 0 END) as discount")
                )
                ->groupBy('month_key', 'month_label')
                ->orderBy('month_key', 'asc')
                ->get()
                ->map(function($row) {
                    $billed = (float) $row->billed;
                    $collected = (float) $row->collected;
                    $discount = (float) $row->discount;
                    $due = max(0, $billed - $collected - $discount);
                    $rate = $billed > 0 ? round(($collected / $billed) * 100, 1) : 0;

                    return [
                        'month_key' => $row->month_key,
                        'month_label' => $row->month_label,
                        'billed' => $billed,
                        'collected' => $collected,
                        'discount' => $discount,
                        'due' => $due,
                        'collection_rate' => $rate
                    ];
                });

            // Payment Methods Breakdown (Cash, GPay, Cheque, Online)
            $paymentMethods = [
                'Cash' => 0,
                'GPay / UPI' => 0,
                'Cheque' => 0,
                'Bank / Online' => 0,
                'Other' => 0
            ];

            $creditTrxs = (clone $baseQuery)->where('type', 'credit')->get();
            foreach ($creditTrxs as $t) {
                $desc = strtolower($t->description ?? '');
                $amt = (float) $t->amount;

                if (str_contains($desc, 'gpay') || str_contains($desc, 'upi') || str_contains($desc, 'online')) {
                    $paymentMethods['GPay / UPI'] += $amt;
                } elseif (str_contains($desc, 'cheque')) {
                    $paymentMethods['Cheque'] += $amt;
                } elseif (str_contains($desc, 'bank') || str_contains($desc, 'transfer')) {
                    $paymentMethods['Bank / Online'] += $amt;
                } elseif (str_contains($desc, 'cash') || empty($desc)) {
                    $paymentMethods['Cash'] += $amt;
                } else {
                    $paymentMethods['Other'] += $amt;
                }
            }

            // Recent Real-Time Transactions Stream (Latest 20)
            $recentTransactions = (clone $baseQuery)
                ->with(['invoice'])
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get()
                ->map(function($t) {
                    $patientName = 'Patient #' . $t->patient_id;
                    $patientLiteralId = $t->patient_id;
                    $bId = $t->branch_id ?? ($t->invoice ? $t->invoice->branch_id : 'SVC-0005');

                    $tempInvoice = new Invoice();
                    $tempInvoice->patient_id = $t->patient_id;
                    $tempInvoice->branch_id = $bId;
                    $resolvedPatient = $tempInvoice->resolved_patient;

                    if ($resolvedPatient) {
                        $patientName = $resolvedPatient->patient_name ?? $patientName;
                        $patientLiteralId = $resolvedPatient->patient_id ?? $patientLiteralId;
                    }

                    return [
                        'id' => $t->id,
                        'invoice_id' => $t->invoice_id,
                        'invoice_no' => $t->invoice ? $t->invoice->invoice_no : 'INV-' . $t->id,
                        'patient_name' => $patientName,
                        'patient_id' => $patientLiteralId,
                        'branch_id' => $bId,
                        'type' => strtoupper($t->type),
                        'amount' => (float) $t->amount,
                        'description' => $t->description ?? 'Transaction Record',
                        'date_formatted' => $t->created_at ? $t->created_at->format('d M Y, h:i A') : 'N/A',
                        'receipt_url' => $t->invoice_id ? route('view.invoice', ['id' => $t->invoice_id, 'transaction_id' => $t->id]) : '#'
                    ];
                });

            return response()->json([
                'success' => true,
                'metrics' => [
                    'total_billed' => $totalBilled,
                    'total_collected' => $totalCollected,
                    'total_discount' => $totalDiscount,
                    'total_due' => $totalDue,
                    'collection_rate' => $collectionRate,
                    'transaction_count' => $transactionCount
                ],
                'monthly_trend' => $monthlyTrendData,
                'payment_methods' => $paymentMethods,
                'recent_transactions' => $recentTransactions
            ]);

        } catch (\Exception $e) {
            \Log::error('Financial Dashboard Data Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
