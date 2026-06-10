<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class BranchController extends Controller
{
    // public function createBranch()
    // {
    //     $roles = Role::all();
    //     $branches = Branch::where('delete_status', '0')
    //                      ->orderBy('created_at', 'desc')
    //                      ->paginate(10);

    //     return view('admin.branches.create_branch', compact('branches','roles'));
    // }
    public function createBranch()
    {
        $roles = Role::all();
    
        $branches = Branch::where(function ($q) {
                $q->where('delete_status', '0')
                  ->orWhere('delete_status', '')
                  ->orWhereNull('delete_status');
            })
            ->orderBy('id', 'desc') // <-- fixed
            ->paginate(10);
    
        return view('admin.branches.create_branch', compact('branches', 'roles'));
    }
    
    /**
     * Store a newly created branch
     */
    public function storeBranch(Request $request)
    {
        $request->validate([
            'branch_name' => 'required|string|max:250',
            'show_branch' => 'required|string|max:50',
            'branch_short_name' => 'required|string|max:50',
            'address' => 'required|string',
        ]);

        try {
            $branchShortName = strtoupper($request->branch_short_name);

            // Get the latest branch with the same prefix to determine next sequence number
            $latestBranch = Branch::where('branch_id', 'like', $branchShortName . '-%')
                                ->orderBy('id', 'desc')
                                ->first();

            if ($latestBranch) {
                // Extract the number part correctly (everything after the dash)
                $branchIdParts = explode('-', $latestBranch->branch_id);
                $lastNumber = (int) $branchIdParts[1]; // Get the number part after dash
                $nextSequence = $lastNumber + 1;
            } else {
                // First branch with this prefix
                $nextSequence = 1;
            }

            // Generate branch ID in format: SVC-0001, ST-0001, FNF-0001, etc.
            $branchId = $branchShortName . '-' . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);

            Branch::create([
                'branch_id' => $branchId,
                'branch_name' => $request->branch_name,
                'show_branch' => $request->show_branch,
                'address' => $request->address,
                'delete_status' => '0',
                'delete_by' => Auth::id(),
            ]);

            return redirect()->route('create.branch')->with('success', 'Branch created successfully!');

        } catch (\Exception $e) {
            dd($e->getMessage());   
            return redirect()->back()->with('error', 'Error creating branch: ' . $e->getMessage());
        }
    }

    /**
     * Update branch - SIMPLE VERSION like charges
     */
    public function updateBranch(Request $request, $id)
    {
        $request->validate([
            'branch_name' => 'required|string|max:250',
            'show_branch' => 'required|string|max:50',
            'address' => 'required|string',
        ]);

        try {
            $branch = Branch::findOrFail($id);

            $branch->update([
                'branch_name' => $request->branch_name,
                'show_branch' => $request->show_branch,
                'address' => $request->address,
            ]);

            return redirect()->route('create.branch')->with('success', 'Branch updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating branch: ' . $e->getMessage());
        }
    }

    /**
     * Delete branch (soft delete)
     */
    public function deleteBranch($id)
    {
        try {
            $branch = Branch::findOrFail($id);

            $branch->update([
                'delete_status' => '1',
                'delete_by' => Auth::id(),
            ]);

            return redirect()->route('create.branch')->with('success', 'Branch deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting branch: ' . $e->getMessage());
        }
    }
}
