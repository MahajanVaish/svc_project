<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nutrition;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::with(['ingredients.nutrition'])->latest()->paginate(5);
        $nutritions = Nutrition::all();
        $totalRecipes = $recipes->total();

        return view('admin.recipes.index', compact('recipes', 'nutritions', 'totalRecipes'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'recipe_name' => 'required|string|max:255',
            'recipe_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nutrition_data' => 'required|array|min:1',
            'nutrition_data.*.nutrition_id' => 'required|exists:nutrition,id',
            'nutrition_data.*.quantity' => 'required|numeric|min:0.01',
        ]);

        try {
            // Start transaction
            DB::beginTransaction();

            // Debug: Log the incoming data
            Log::info('Recipe Store Request:', $request->all());

            // Create nutrition data JSON for description as requested
            $nutritionDataJson = [];
            foreach ($request->nutrition_data as $data) {
                $nutrition = Nutrition::find($data['nutrition_id']);
                if ($nutrition) {
                    $nutritionDataJson[$nutrition->nutrition_name] = $data['quantity'];
                }
            }
            $descriptionJson = json_encode($nutritionDataJson);

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('recipe_image')) {
                $file = $request->file('recipe_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/recipes'), $filename);
                $imagePath = 'uploads/recipes/' . $filename;
            }
            
            // Create the recipe
            $recipe = Recipe::create([
                'name' => $request->recipe_name,
                'description' => $descriptionJson,
                'image' => $imagePath,
            ]);

            Log::info('Recipe created with ID: ' . $recipe->id);

            // Create recipe ingredients for the relationship
            foreach ($request->nutrition_data as $data) {
                RecipeIngredient::create([
                    'recipe_id' => $recipe->id,
                    'nutrition_id' => $data['nutrition_id'],
                    'quantity' => $data['quantity'],
                ]);
            }

            DB::commit();

            return redirect()->route('recipes.index')
                ->with('success', 'Recipe added successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating recipe: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error creating recipe: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $recipe = Recipe::with(['ingredients.nutrition'])->findOrFail($id);

        // Fallback: If ingredients relation is empty but description has JSON, populate from JSON
        if ($recipe->ingredients->isEmpty() && !empty($recipe->description)) {
            $decoded = json_decode($recipe->description, true);
            if (is_array($decoded)) {
                $tempIngredients = [];
                foreach ($decoded as $name => $quantity) {
                    $nutrition = Nutrition::where('nutrition_name', $name)->first();
                    if ($nutrition) {
                        $ingredient = new RecipeIngredient();
                        $ingredient->nutrition_id = $nutrition->id;
                        $ingredient->quantity = $quantity;
                        $ingredient->setRelation('nutrition', $nutrition);
                        $tempIngredients[] = $ingredient;
                    }
                }
                $recipe->setRelation('ingredients', collect($tempIngredients));
            }
        }

        $nutritions = Nutrition::all();

        return view('admin.recipes.edit', compact('recipe', 'nutritions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'recipe_name' => 'required|string|max:255',
            'recipe_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nutrition_data' => 'required|array|min:1',
            'nutrition_data.*.nutrition_id' => 'required|exists:nutrition,id',
            'nutrition_data.*.quantity' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();

            $recipe = Recipe::findOrFail($id);

            // Create nutrition data JSON for description as requested
            $nutritionDataJson = [];
            foreach ($request->nutrition_data as $data) {
                $nutrition = Nutrition::find($data['nutrition_id']);
                if ($nutrition) {
                    $nutritionDataJson[$nutrition->nutrition_name] = $data['quantity'];
                }
            }
            $descriptionJson = json_encode($nutritionDataJson);

            // Handle image upload
            $updateData = [
                'name' => $request->recipe_name,
                'description' => $descriptionJson,
            ];

            if ($request->hasFile('recipe_image')) {
                // Delete old image if exists
                if ($recipe->image && file_exists(public_path($recipe->image))) {
                    @unlink(public_path($recipe->image));
                }

                $file = $request->file('recipe_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/recipes'), $filename);
                $updateData['image'] = 'uploads/recipes/' . $filename;
            }

            $recipe->update($updateData);

            // Delete existing ingredients
            $recipe->ingredients()->delete();

            // Create new ingredients
            foreach ($request->nutrition_data as $data) {
                RecipeIngredient::create([
                    'recipe_id' => $recipe->id,
                    'nutrition_id' => $data['nutrition_id'],
                    'quantity' => $data['quantity'],
                ]);
            }

            DB::commit();

            return redirect()->route('recipes.index')
                ->with('success', 'Recipe updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error updating recipe: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $recipe = Recipe::findOrFail($id);


            $recipe->ingredients()->delete();

            // Delete recipe
            $recipe->delete();

            return response()->json([
                'success' => true,
                'message' => 'Recipe deleted successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting recipe: ' . $e->getMessage(),
            ], 500);
        }
    }
}
