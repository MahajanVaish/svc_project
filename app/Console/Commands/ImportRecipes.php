<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportRecipes extends Command
{
    protected $signature = 'import:recipes';
    protected $description = 'Import recipes from old wp_fnf_acc_recipe table';

    public function handle()
    {
        $this->info('Starting recipes import...');

        try {
            $oldRecipes = DB::connection('old_mysql')->table('wp_fnf_acc_recipe')->get();

            $insertedRecipes = 0;
            $insertedIngredients = 0;

            foreach ($oldRecipes as $old) {
                // Insert into recipes table
                $recipeId = DB::table('recipes')->insertGetId([
                    'name' => $old->recipe_name,
                    'description' => $old->recipe_data,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $insertedRecipes++;

                // You have no nutrition_id or quantity in old table for ingredients
                // You must decide how to get nutrition_id and quantity for this recipe
                // Skipping recipe_ingredients insert because no data is present

                // If you have recipe_menu containing ingredients, parse it here
                // For example, if recipe_menu contains comma-separated nutrition_ids and quantities,
                // parse it, then insert in recipe_ingredients table.

                // Example (pseudo):
                // $ingredients = explode(',', $old->recipe_menu);
                // foreach($ingredients as $ingredient) {
                //    $nutritionId = ...; // extract nutrition_id
                //    $quantity = ...; // extract quantity or set default
                //    DB::table('recipe_ingredients')->insert([
                //       'recipe_id' => $recipeId,
                //       'nutrition_id' => $nutritionId,
                //       'quantity' => $quantity,
                //       'created_at' => now(),
                //       'updated_at' => now(),
                //    ]);
                //    $insertedIngredients++;
                // }

            }

            $this->info("Recipes imported: {$insertedRecipes}");
            $this->info("Recipe ingredients imported: {$insertedIngredients} (if implemented)");

        } catch (\Exception $e) {
            $this->error('Import failed: ' . $e->getMessage());
        }
    }
}
