<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportNutritionData extends Command
{
    protected $signature = 'import:nutrition-data';
    protected $description = 'Import nutrition data from old database wp_fnf_acc_monthly_assessment to nutrition table';

   public function handle()
{
    $this->info('Starting nutrition data import...');

    $insertedCount = 0;
    $skippedCount = 0;

    try {
        $oldData = DB::connection('old_mysql')->table('wp_fnf_acc_monthly_assessment')->get();

        foreach ($oldData as $old) {
            $nutritionName = trim($old->nutrition_name ?? '');

            // If nutrition_name is empty, fill with default string or NULL based on your DB change
            if ($nutritionName === '') {
                // Option A: If you made nutrition_name nullable, use null
                // $nutritionName = null;

                // Option B: If you set default value in DB, just leave it as is
                $nutritionName = 'Unknown Nutrition';  // safer option
            }

            $data = [
                'nutrition_name' => $nutritionName,
                'energy_kcal' => $old->energy_kcal ?? null,
                'water' => $old->water ?? null,
                'fat' => $old->fat ?? null,
                'total_fiber' => $old->total_fiber ?? null,
                'carbohydrate' => $old->carbohydrate ?? null,
                'protein' => $old->protein ?? null,
                'vitamin_c' => $old->vitamin_c ?? null,
                'insoluable_fiber' => $old->insoluable_fiber ?? null,
                'soluable_fiber' => $old->soluable_fiber ?? null,
                'biotin' => $old->biotin ?? null,
                'total_folates' => $old->total_folates ?? null,
                'calcium' => $old->calcium ?? null,
                'cu' => $old->cu ?? null,
                'fe' => $old->fe ?? null,
                'mg' => $old->mg ?? null,
                'p' => $old->p ?? null,
                'k' => $old->k ?? null,
                'se' => $old->se ?? null,
                'na' => $old->na ?? null,
                'za' => $old->za ?? null,
                'delete_status' => $old->delete_status ?? null,
                'delete_by' => $old->delete_by ?? null,
            ];

            DB::table('nutrition')->insert($data);
            $insertedCount++;
        }

        $this->info("Nutrition data import completed successfully.");
        $this->info("Inserted: {$insertedCount} records.");

    } catch (\Exception $e) {
        $this->error('Nutrition data import failed: ' . $e->getMessage());
    }
}

}
