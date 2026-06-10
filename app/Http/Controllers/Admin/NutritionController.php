<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nutrition;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NutritionController extends Controller
{
    public function index()
    {
        $nutritions = Nutrition::orderBy('id', 'desc')->paginate(10);
        return view('admin.nutritions.create_nutrition', compact('nutritions'));
    }

    public function store(Request $request)
    {
          try {
            $nutrition = Nutrition::create([
                'nutrition_name' => $request->nutrition_name,
                'energy_kcal' => $request->energy_kcal,
                'water' => $request->water,
                'fat' => $request->fat,
                'total_fiber' => $request->total_fiber,
                'carbohydrate' => $request->carbohydrate,
                'protein' => $request->protein,
                'vitamin_c' => $request->vitamin_c,
                'insoluable_fiber' => $request->insoluable_fiber,
                'soluable_fiber' => $request->soluable_fiber,
                'biotin' => $request->biotin,
                'total_folates' => $request->total_folates,
                'calcium' => $request->calcium,
                'cu' => $request->cu,
                'fe' => $request->fe,
                'mg' => $request->mg,
                'p' => $request->p,
                'k' => $request->k,
                'se' => $request->se,
                'na' => $request->na,
                'za' => $request->za,
                'delete_status' => $request->delete_status ?? 'active',
                'delete_by' => $request->delete_by ?? '',
            ]);

            return redirect()->route('nutrition-info')->with('success', 'Nutrition data inserted successfully!');

        } catch (Exception $e) {
            Log::error('Nutrition insertion failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to insert nutrition data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getNutritionData(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $nutrition = Nutrition::orderBy('id', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $nutrition
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch nutrition data.'
            ], 500);
        }
    }

      public function uploadCsv(Request $request)
    {
        try {
            $request->validate([
                'csv_file' => 'required|file|mimes:csv,txt|max:10240' 
            ]);

            $file = $request->file('csv_file');
            $csvData = array_map('str_getcsv', file($file));
            $headers = array_shift($csvData); 

            $importedCount = 0;
            $duplicateCount = 0;
            $errors = [];

            foreach ($csvData as $row) {
                if (count($row) !== count($headers)) {
                    $errors[] = "Row skipped: Incorrect number of columns";
                    continue;
                }

                $data = array_combine($headers, $row);

            
                $exists = Nutrition::where('nutrition_name', $data['nutrition_name'])->exists();

                if ($exists) {
                    $duplicateCount++;
                    continue;
                }

               
                Nutrition::create([
                    'nutrition_name' => $data['nutrition_name'] ?? null,
                    'energy_kcal' => $data['energy_kcal'] ?? null,
                    'water' => $data['water'] ?? null,
                    'fat' => $data['fat'] ?? null,
                    'total_fiber' => $data['total_fiber'] ?? null,
                    'carbohydrate' => $data['carbohydrate'] ?? null,
                    'protein' => $data['protein'] ?? null,
                    'vitamin_c' => $data['vitamin_c'] ?? null,
                    'insoluable_fiber' => $data['insoluable_fiber'] ?? null,
                    'soluable_fiber' => $data['soluable_fiber'] ?? null,
                    'biotin' => $data['biotin'] ?? null,
                    'total_folates' => $data['total_folates'] ?? null,
                    'calcium' => $data['calcium'] ?? null,
                    'cu' => $data['cu'] ?? null,
                    'fe' => $data['fe'] ?? null,
                    'mg' => $data['mg'] ?? null,
                    'p' => $data['p'] ?? null,
                    'k' => $data['k'] ?? null,
                    'se' => $data['se'] ?? null,
                    'na' => $data['na'] ?? null,
                    'za' => $data['za'] ?? null,
                    'delete_status' => 'active',
                    'delete_by' => '',
                ]);

                $importedCount++;
            }

            $message = "CSV imported successfully! {$importedCount} records added.";
            if ($duplicateCount > 0) {
                $message .= " {$duplicateCount} duplicate records skipped.";
            }
            if (!empty($errors)) {
                $message .= " " . count($errors) . " errors occurred.";
            }

            return redirect()->route('nutrition-info')->with('success', $message);

        } catch (Exception $e) {
            Log::error('CSV upload failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to upload CSV file: ' . $e->getMessage());
        }
    }
     public function downloadSampleCsv()
    {
        try {
            $fileName = 'nutrition_sample.csv';
            $headers = [
                'nutrition_name', 'energy_kcal', 'water', 'fat', 'total_fiber',
                'carbohydrate', 'protein', 'vitamin_c', 'insoluable_fiber',
                'soluable_fiber', 'biotin', 'total_folates', 'calcium', 'cu',
                'fe', 'mg', 'p', 'k', 'se', 'na', 'za'
            ];

          
            $sampleData = [
                ['Apple', '52', '86', '0.2', '2.4', '14', '0.3', '4.6', '1.8', '0.6', '0.000001', '0.000003', '6', '0.04', '0.12', '5', '11', '107', '0', '1', '0.04'],
                ['Banana', '89', '75', '0.3', '2.6', '23', '1.1', '8.7', '2.1', '0.5', '0.000002', '0.000020', '5', '0.08', '0.26', '27', '22', '358', '1', '1', '0.15'],
                ['Orange', '47', '87', '0.1', '2.4', '12', '0.9', '53.2', '1.8', '0.6', '0.000001', '0.000030', '40', '0.04', '0.10', '10', '14', '181', '0', '0', '0.07'],
                ['Carrot', '41', '88', '0.2', '2.8', '10', '0.9', '5.9', '2.1', '0.7', '0.000003', '0.000019', '33', '0.05', '0.30', '12', '35', '320', '0', '69', '0.24'],
                ['Broccoli', '34', '89', '0.4', '2.6', '7', '2.8', '89.2', '2.0', '0.6', '0.000002', '0.000063', '47', '0.05', '0.73', '21', '66', '316', '3', '33', '0.41'],
                ['Chicken Breast', '165', '65', '3.6', '0', '0', '31', '0', '0', '0', '0.000009', '0.000004', '15', '0.04', '0.70', '28', '228', '256', '22', '74', '1.00'],
                ['Brown Rice', '111', '70', '0.9', '1.8', '23', '2.6', '0', '1.4', '0.4', '0.000005', '0.000008', '10', '0.11', '0.40', '43', '77', '43', '0', '1', '0.62'],
                ['Almonds', '579', '4', '50', '12.5', '22', '21', '0', '9.8', '2.7', '0.000057', '0.000044', '269', '1.00', '3.71', '270', '481', '733', '4', '1', '3.12'],
                ['Milk', '42', '88', '1', '0', '5', '3.4', '0', '0', '0', '0.000003', '0.000005', '125', '0.01', '0.03', '11', '95', '150', '2', '44', '0.38'],
                ['Egg', '155', '75', '11', '0', '1.1', '13', '0', '0', '0', '0.000015', '0.000047', '50', '0.07', '1.75', '10', '172', '126', '31', '142', '1.29']
            ];

            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);

            foreach ($sampleData as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);

            return response()->streamDownload(function() use ($headers, $sampleData) {
                $output = fopen('php://output', 'w');
                fputcsv($output, $headers);
                foreach ($sampleData as $row) {
                    fputcsv($output, $row);
                }
                fclose($output);
            }, $fileName, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]);

        } catch (Exception $e) {
            Log::error('Sample CSV download failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to download sample CSV.');
        }
    }
      public function edit($id)
    {
        try {
            $nutrition = Nutrition::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $nutrition
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Nutrition data not found.'
            ], 404);
        }
    }

public function update(Request $request, $id)
    {
        try {
            $nutrition = Nutrition::findOrFail($id);
 
            $nutrition->update([
                'nutrition_name' => $request->nutrition_name ?: '',
                'energy_kcal' => $request->energy_kcal ?: '',
                'water' => $request->water ?: '',
                'fat' => $request->fat ?: '',
                'total_fiber' => $request->total_fiber ?: '',
                'carbohydrate' => $request->carbohydrate ?: '',
                'protein' => $request->protein ?: '',
                'vitamin_c' => $request->vitamin_c ?: '',
                'insoluable_fiber' => $request->insoluable_fiber ?: '',
                'soluable_fiber' => $request->soluable_fiber ?: '',
                'biotin' => $request->biotin ?: '',
                'total_folates' => $request->total_folates ?: '',
                'calcium' => $request->calcium ?: '',
                'cu' => $request->cu ?: '',
                'fe' => $request->fe ?: '',
                'mg' => $request->mg ?: '',
                'p' => $request->p ?: '',
                'k' => $request->k ?: '',
                'se' => $request->se ?: '',
                'na' => $request->na ?: '',
                'za' => $request->za ?: '',
            ]);
 
            return response()->json([
                'success' => true,
                'message' => 'Nutrition data updated successfully!',
                'data' => $nutrition
            ]);
 
        } catch (Exception $e) {
            Log::error('Nutrition update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update nutrition data.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $nutrition = Nutrition::findOrFail($id);
            $nutrition->delete();

            return response()->json([
                'success' => true,
                'message' => 'Nutrition data deleted successfully!'
            ]);

        } catch (Exception $e) {
            Log::error('Nutrition deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete nutrition data.'
            ], 500);
        }
    }
}
