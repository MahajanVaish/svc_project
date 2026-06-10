<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ACCUsers;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function index()
    {
        $user = auth()->user();


        if (!$user->hasRole('Superadmin')) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        

        $branches = Branch::all();


        if ($branches->isEmpty()) {
            return view('admin.dashboard', compact('branches'))
                ->with('warning', 'No branches found in the database.');
        }

        return view('admin.dashboard', compact('branches'));
    }


    public function SVC()
    {
        return view('admin.SVC.svc');
    }

    public function storeUser(Request $request)
    {
       
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'user_role' => 'required|string',
            'user_branch' => 'required|string',
        ]);
      
        try {
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'user_role' => $validated['user_role'],
                'user_branch' => $validated['user_branch'],
            ]);
            // dd('here');
            return back()->with('success', 'User created successfully');
        } catch (\Exception $e) {
            // Log the error for debugging
            dd($e->getMessage());
            \Log::error('User creation failed: ' . $e->getMessage());

            return back()->with('error', 'Failed to create user. Please try again.');
        }
    }

    public function paymentSummary(Request $request)
    {
        $branches = Branch::all();
        
        if ($request->ajax()) {
            $branchId = $request->input('branch_id');
            $status = $request->input('status'); // 'paid', 'due', 'all'
            $year = $request->input('year');
            $month = $request->input('month');
            
            $query = \App\Models\Invoice::query();
            
            if ($branchId) {
                $query->where('branch_id', $branchId);
            }
            
            if ($year) {
                $query->whereYear('invoice_date', $year);
            }
            
            if ($month) {
                $query->whereMonth('invoice_date', $month);
            }
            
            $data = [];
            $globalPaid = 0;
            $globalDue = 0;
            
            if (!$branchId) {
                // Show data per branch
                foreach ($branches as $branch) {
                    $branchQuery = \App\Models\Invoice::where('branch_id', $branch->branch_id);
                    
                    if ($year) $branchQuery->whereYear('invoice_date', $year);
                    if ($month) $branchQuery->whereMonth('invoice_date', $month);
                    
                    $paidVal = (clone $branchQuery)->sum('given_payment');
                    $dueVal = (clone $branchQuery)->sum('due_payment');
                    $totalVal = (clone $branchQuery)->sum('total_payment');
                    
                    $globalPaid += $paidVal;
                    $globalDue += $dueVal;
                    
                    if ($status === 'paid') {
                        $value = $paidVal;
                    } elseif ($status === 'due') {
                        $value = $dueVal;
                    } else {
                        $value = $totalVal;
                    }
                    
                    $data[] = [
                        'label' => $branch->branch_name,
                        'value' => (float)$value,
                        'paid' => (float)$paidVal,
                        'due' => (float)$dueVal
                    ];
                }
            } else {
                // Show breakdown for specific branch
                $paidQuery = \App\Models\Invoice::where('branch_id', $branchId);
                $dueQuery = \App\Models\Invoice::where('branch_id', $branchId);
                
                if ($year) {
                    $paidQuery->whereYear('invoice_date', $year);
                    $dueQuery->whereYear('invoice_date', $year);
                }
                if ($month) {
                    $paidQuery->whereMonth('invoice_date', $month);
                    $dueQuery->whereMonth('invoice_date', $month);
                }
                
                $paid = $paidQuery->sum('given_payment');
                $due = $dueQuery->sum('due_payment');
                
                $globalPaid = $paid;
                $globalDue = $due;
                
                $data = [
                    ['label' => 'Paid', 'value' => (float)$paid],
                    ['label' => 'Due', 'value' => (float)$due]
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'totals' => [
                    'paid' => (float)$globalPaid,
                    'due' => (float)$globalDue,
                    'revenue' => (float)($globalPaid + $globalDue)
                ]
            ]);
        }
        
        return view('admin.payment-summary', compact('branches'));
    }
}
