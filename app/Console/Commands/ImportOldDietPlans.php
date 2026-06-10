<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportOldDietPlans extends Command
{
    /**
     * Command name
     */
    protected $signature = 'import:old-diet-plans';

    /**
     * Command description
     */
    protected $description = 'Import diet plans from wp_fnf_acc_diet_chart to diet_plans table';

    /**
     * Execute the console command
     */
    public function handle()
    {
        $this->info('Diet plans import started...');

        // 🔴 OLD DATABASE CONNECTION FORCE
        $oldDietPlans = DB::connection('mysql_old')
            ->table('wp_fnf_acc_diet_chart')
            ->get();

        if ($oldDietPlans->isEmpty()) {
            $this->warn('No diet plans found in old table.');
            return Command::SUCCESS;
        }

        DB::beginTransaction();

        try {
            foreach ($oldDietPlans as $diet) {

                DB::table('diet_plans')->insert([
                    'branch_id'           => $diet->branch_id,
                    'patient_id'          => $diet->patient_id,
                    'patient_name'        => $diet->patient_name,
                    'date'                => $diet->date,
                    'diet_name'           => $diet->diet_name,
                    'time_search_menus'   => $diet->menu_time ?? null,
                    'general_notes'       => $diet->general_notes ?? null,
                    'next_follow_up_date' => $diet->next_follow_up_date ?? null,
                    'created_by'          => $diet->created_by ?? null,
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ]);
            }

            DB::commit();
            $this->info('✅ Diet plans imported successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Diet plans import failed');
            $this->error($e->getMessage());
        }

        return Command::SUCCESS;
    }
}
