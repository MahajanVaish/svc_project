<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportManagePrograms extends Command
{
    protected $signature = 'import:manage-programs';
    protected $description = 'Import manage programs from old wp_fnf_acc_program table';

    public function handle()
    {
        $this->info('Starting manage programs import...');

        try {
            $oldData = DB::connection('old_mysql')->table('wp_fnf_acc_program')->get();

            $insertedCount = 0;

            foreach ($oldData as $old) {
                // Convert program_price to decimal, fallback 0.00 if invalid
                $programPrice = is_numeric($old->program_price) ? floatval($old->program_price) : 0.00;

                // Map gender values strictly to enum options
                $gender = in_array($old->gender, ['Male', 'Female', 'Both']) ? $old->gender : 'Both';

                $data = [
                    'program_name' => $old->program_name,
                    'program_short_name' => $old->program_short_name,
                    'gender' => $gender,
                    'branch' => $old->branch ?? null, // old table doesn’t have branch column, so null
                    'program_price' => $programPrice,
                    'profram_log' => $old->profram_log ?? null,
                    'delete_status' => is_numeric($old->delete_status) ? (int)$old->delete_status : 0,
                    'delete_by' => is_numeric($old->delete_by) ? (int)$old->delete_by : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                DB::table('manage_programs')->insert($data);
                $insertedCount++;
            }

            $this->info("Import completed. Inserted: {$insertedCount} records.");

        } catch (\Exception $e) {
            $this->error('Import failed: ' . $e->getMessage());
        }
    }
}
