<?php
 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\ManageProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
 
class ManageProgramController extends Controller
{
    /**
     * Display a listing of the programs.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');
 
        $query = ManageProgram::where('delete_status', 0);
 
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                // Remove commas from search term for price comparison
                $cleanSearch = str_replace(',', '', $search);
 
                // Search in program_name
                $q->where('program_name', 'LIKE', "%{$search}%")
                  // Search in program_short_name
                  ->orWhere('program_short_name', 'LIKE', "%{$search}%")
                  // Search in gender
                  ->orWhere('gender', 'LIKE', "%{$search}%")
                  // Search in branch
                  ->orWhere('branch', 'LIKE', "%{$search}%")
                  // Search in price (numeric comparison)
                  ->orWhere('program_price', '=', $cleanSearch)
                  // Also search with LIKE for price containing digits
                  ->orWhereRaw("CAST(program_price AS CHAR) LIKE ?", ["%{$cleanSearch}%"]);
            });
        }
 
        $programs = $query->orderBy('id', 'desc')->paginate($perPage);
 
        // Append query parameters to pagination links
        if ($request->has('search')) {
            $programs->appends(['search' => $request->search]);
        }
        if ($request->has('per_page')) {
            $programs->appends(['per_page' => $request->per_page]);
        }
 
        return view('admin.programs.manage', compact('programs'));
    }
 
    /**
     * Store a newly created program in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'program_name' => 'required|string|max:255',
            'program_short_name' => 'required|string|max:50',
            'gender' => 'required|in:Male,Female,Both',
            'branch' => 'required|string|max:100',
            'program_price' => 'required|numeric|min:0',
        ]);
 
        ManageProgram::create([
            'program_name' => $request->program_name,
            'program_short_name' => $request->program_short_name,
            'gender' => $request->gender,
            'branch' => $request->branch,
            'program_price' => $request->program_price,
            'delete_status' => 0
        ]);
 
        return redirect()->route('admin.manage-programs')->with('success', 'Program created successfully!');
    }
 
    /**
     * Update the specified program in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'program_name' => 'required|string|max:255',
            'program_short_name' => 'required|string|max:50',
            'program_price' => 'required|numeric|min:0',
        ]);
 
        $program = ManageProgram::where('delete_status', 0)->findOrFail($id);
 
        $program->update([
            'program_name' => $request->program_name,
            'program_short_name' => $request->program_short_name,
            'program_price' => $request->program_price,
            // Note: Gender and Branch are NOT updated from edit modal
        ]);
 
        return redirect()->route('admin.manage-programs')->with('success', 'Program updated successfully!');
    }
 
    /**
     * Remove the specified program from storage (soft delete).
     */
    public function destroy($id)
    {
        $program = ManageProgram::where('delete_status', 0)->findOrFail($id);
 
        $program->update([
            'delete_status' => 1,
            'delete_by' => Auth::id()
        ]);
 
        return redirect()->route('admin.manage-programs')->with('success', 'Program deleted successfully!');
    }
}