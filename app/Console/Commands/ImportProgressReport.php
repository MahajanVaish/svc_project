<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportProgressReport extends Command
{
    protected $signature = 'import:progress-report';
    protected $description = 'Import progress report data from old DB to new table';

    public function handle()
    {
        $this->info('Starting progress report import...');

        try {
            // Fetch all from old table using old_mysql connection
            $oldData = DB::connection('old_mysql')->table('wp_fnf_acc_progress_report')->get();

            $insertedCount = 0;

            foreach ($oldData as $old) {
                // Map old data to new table structure
                $data = [
                    'patient_id' => $old->patient_id,
                    'branch_name' => $old->branch_name ?? null,
                    'branch_id' => $old->branch_id ?? null,
                    'patient_name' => $old->patient_name ?? null,
                    'date' => $old->date ?? null,
                    'time' => $old->time ?? null,
                    'body_part' => $old->body_part ?? null,
                    'bp_p' => $old->bp_p ?? null,
                    'pulse' => $old->pulse ?? null,
                    'detox' => $old->detox ?? null,
                    'breast_reshaping' => $old->breast_reshaping ?? null,
                    'face_program' => $old->face_program ?? null,
                    'relaxation' => $old->relaxation ?? null,
                    'lypolysis_treatment' => $old->lypolysis_treatment ?? null,
                    'weight' => $old->weight ?? null,
                    'councilor_doctor' => $old->councilor_doctor ?? null,
                    'exercise' => $old->exercise ?? null,
                    'delete_status' => $old->delete_status ?? null,
                    'delete_by' => $old->delete_by ?? null,
                ];

                DB::table('progress_report')->insert($data);
                $insertedCount++;
            }

            $this->info("Progress report import completed. Inserted: {$insertedCount} records.");

        } catch (\Exception $e) {
            $this->error('Import failed: ' . $e->getMessage());
        }
    }
}
