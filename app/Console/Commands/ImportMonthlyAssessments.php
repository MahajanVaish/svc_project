<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportMonthlyAssessments extends Command
{
    protected $signature = 'import:monthly-assessments';
    protected $description = 'Import monthly assessments from old database';

    public function handle()
    {
        $this->info('Starting monthly assessments import...');

        try {
            $oldData = DB::connection('old_mysql')->table('wp_fnf_acc_monthly_assessment')->get();

            foreach ($oldData as $old) {
                $data = [
                    'branch_id' => $old->branch_id,
                    'patient_inquiry_id' => $old->id, // use old table's primary key as patient_inquiry_id
                    'patient_id' => $old->patient_id,
                    'assessment_date' => $this->convertDate($old->rept_date),
                    'status' => 'draft',  // default as per your table
                    'waist_upper' => $this->parseDecimal($old->rept_wast_upper),
                    'waist_middle' => $this->parseDecimal($old->rept_wast_midel),
                    'waist_lower' => $this->parseDecimal($old->rept_wast_lower),
                    'hips' => $this->parseDecimal($old->rept_hips),
                    'thighs' => $this->parseDecimal($old->rept_thighs),
                    'arms' => $this->parseDecimal($old->rept_arms),
                    'waist_hips_ratio' => $this->parseDecimal($old->rept_wais_hips, 99.99),
                    'weight' => $this->parseDecimal($old->rept_wt),
                    'bmi' => $this->parseDecimal($old->rept_bmi),
                    'bca_vbf' => $this->parseDecimal($old->rept_wbf),
                    'bca_arms' => $this->parseDecimal($old->rept_arms_2),
                    'bca_trunk' => $this->parseDecimal($old->rept_trunk),
                    'bca_legs' => $this->parseDecimal($old->rept_legs),
                    'bca_sf' => $this->parseDecimal($old->rept_sf),
                    'bca_vf' => $this->parseDecimal($old->rept_vf),
                    'muscle_vbf' => $this->parseDecimal($old->rept_skl_m),
                    'muscle_arms' => $this->parseDecimal($old->rept_wbf_2),
                    'muscle_trunk' => $this->parseDecimal($old->rept_arms_3),
                    'muscle_legs' => $this->parseDecimal($old->rept_trunk_2),
                    'notes' => null,
                    'assessed_by' => null,
                    'delete_status' => (int) $old->delete_status,
                    'delete_by' => is_numeric($old->delete_by) ? (int)$old->delete_by : null,
                    'delete_date' => null, // old table doesn't have delete_date
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                DB::table('monthly_assessments')->insert($data);
            }


            $this->info('Monthly assessments import completed successfully.');
        } catch (\Exception $e) {
            $this->error('Monthly assessments import failed');
            $this->error($e->getMessage());
        }
    }

    private function parseDecimal($value, $max = 9999.9)
    {
        if (!$value) return null;

        // Remove all except digits, dot, minus
        $clean = preg_replace('/[^0-9.\-]/', '', $value);

        if (!is_numeric($clean)) {
            return null;
        }

        $floatVal = floatval($clean);

        // Clamp value to DB max range
        if ($floatVal > $max) {
            $floatVal = $max;
        } elseif ($floatVal < -$max) {
            $floatVal = -$max;
        }

        return round($floatVal, 1);
    }



    private function convertDate($date)
    {
        if (!$date || trim($date) === '') {
            return null;
        }

        $date = trim($date);

        // Try YYYY-MM-DD first
        $dt = \DateTime::createFromFormat('Y-m-d', $date);
        if ($dt && $dt->format('Y-m-d') === $date) {
            return $date;
        }

        // Try common alternative formats
        $formats = ['d/m/Y', 'm/d/Y', 'd-m-Y', 'm-d-Y'];
        foreach ($formats as $format) {
            $dt = \DateTime::createFromFormat($format, $date);
            if ($dt && $dt->format($format) === $date) {
                return $dt->format('Y-m-d');
            }
        }

        // Try strtotime fallback
        $timestamp = strtotime($date);
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }

        // Still null? Log and skip
        $this->error("Invalid date format: '{$date}'");
        return null;
    }
}
