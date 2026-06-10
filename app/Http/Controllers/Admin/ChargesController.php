<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Charges;
use Illuminate\Http\Request;

class ChargesController extends Controller
{
    public function SVCcharges(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');

        $charges = Charges::when($search, function ($query) use ($search) {
            return $query->where('charges_name', 'like', '%' . $search . '%');
        })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return view('admin.charges.svc_charges_create', compact('charges'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'charges_name' => 'required',
            'charges_price' => 'required|numeric|min:0',
        ]);

        Charges::create([
            'charges_name' => $request->charges_name,
            'charges_price' => $request->charges_price,
            'delete_status' => '0',
            'delete_by' => null,
        ]);

        return redirect()->route('svc.charges')->with('success', 'Charge created successfully.');
    }

    public function update(Request $request, $id)
    {
        try {
            $charge = Charges::findOrFail($id);
            $charge->update([
                'charges_name' => $request->charges_name,
                'charges_price' => $request->charges_price
            ]);
            
            return redirect()->route('svc.charges')->with('success', 'Charge updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('svc.charges')->with('error', 'Error updating charge: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            $charge = Charges::findOrFail($id);
            $charge->delete();

            return redirect()->route('svc.charges')->with('success', 'Charge deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('svc.charges')->with('error', 'Error deleting charge: ' . $e->getMessage());
        }
    }
}
