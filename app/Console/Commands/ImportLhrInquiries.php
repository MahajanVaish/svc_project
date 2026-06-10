<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportLhrInquiries extends Command
{
    protected $signature = 'import:old-lhr-inquiries';
    protected $description = 'Import LHR inquiries from old database';

    public function handle()
    {
        $this->info('Starting LHR inquiries import...');

        try {
            $oldData = DB::connection('old_mysql')->table('wp_fnf_acc_lhr_inquiry')->where('delete_status', '!=', 'deleted')->get();

            foreach ($oldData as $old) {
                $data = [
                    'patient_id'        => $old->patient_id,
                    'branch'            => $old->branch,
                    'branch_id'         => $old->branch_id,
                    'patient_name'      => $old->patient_name,
                    'inquiry_date'      => $this->convertDate($old->inquiry_date),
                    'address'           => $old->address,
                    'gender'            => strtolower($old->gender),
                    'age'               => (int) $old->age,
                    'year'              => $old->month_year ?? null,
                    'area'              => $old->afra_code ?? null,
                    'session'           => $old->status ?? null,
                    'area_code'         => null,
                    'energy'            => $old->energy,
                    'frequency'         => $old->frequency,
                    'shot'              => $old->shot,
                    'staff_name'        => $old->staff_name,
                    'status_name'       => $this->mapStatus($old->status),
                    'hormonal_issues'   => $this->yesNo($old->hormonal_issues),
                    'medication'        => $this->yesNo($old->thyroids_issues),
                    'previous_treatment' => 'no',
                    'pcod_thyroid'      => 'no',
                    'skin_conditions'   => $this->yesNo($old->skin_conditions),
                    'ongoing_treatments' => $this->yesNo($old->ongoing_skin),
                    'implants_tattoos'  => $this->yesNo($old->implantations_tattoos),
                    'procedure'         => $this->sanitizeProcedure($old->tattoos_note),
                    'reference_by'      => $old->refranceby ?? null,
                    'statement_1'       => null,
                    'statement_2'       => null,
                    'next_follow_up'    => $this->convertDate($old->next_follow_date),
                    'notes'             => $old->notes,
                    'foc'               => $this->parseBooleanToInt($old->foc),
                    'total_payment'     => $this->parseDecimal($old->total_payment),
                    'discount_payment'  => $this->parseDecimal($old->discount_payment),
                    'given_payment'     => $this->parseDecimal($old->given_payment),
                    'due_payment'       => $this->parseDecimal($old->due_payment),
                    'cash_payment'      => $this->parseDecimal($old->cash_price),
                    'google_pay'        => $this->parseDecimal($old->gpay_price),
                    'cheque_payment'    => $this->parseDecimal($old->cheque_price),
                    'before_picture_1'  => $old->before_image,
                    'after_picture_1'   => $old->after_image,
                    'account'           => null,
                    'time'              => $this->sanitizeTime($old->inquiry_time),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ];

                DB::table('lhr_inquiries')->insert($data);
            }

            $this->info('LHR inquiries import completed successfully.');
        } catch (\Exception $e) {
            $this->error('LHR inquiries import failed');
            $this->error($e->getMessage());
        }
    }

    private function convertDate($date)
    {
        if (!$date) return null;

        $formats = ['Y-m-d', 'd/m/Y', 'm/d/Y'];

        foreach ($formats as $format) {
            $dt = \DateTime::createFromFormat($format, $date);
            if ($dt && $dt->format($format) === $date) {
                return $dt->format('Y-m-d');
            }
        }

        $timestamp = strtotime($date);
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }

        return null;
    }

    private function parseDecimal($value)
    {
        if (!$value) return 0.00;
        $clean = preg_replace('/[^0-9.\-]/', '', $value);
        return is_numeric($clean) ? floatval($clean) : 0.00;
    }

    private function yesNo($value)
    {
        if (!$value) return 'no';
        $v = strtolower(trim($value));
        return in_array($v, ['yes', 'y', '1']) ? 'yes' : 'no';
    }

    private function parseBooleanToInt($value)
    {
        $v = strtolower(trim($value));
        if (in_array($v, ['yes', 'y', '1'])) return 1;
        if (in_array($v, ['no', 'n', '0'])) return 0;
        return 0;
    }

    private function mapStatus($value)
    {
        $v = strtolower(trim($value ?? ''));
        if (in_array($v, ['joined', 'join'])) return 'joined';
        return 'pending';
    }

    private function sanitizeProcedure($value)
    {
        if (!$value) return null;
        $clean = trim($value);
        if (strlen($clean) > 10000) {
            $clean = substr($clean, 0, 10000);
        }
        return $clean;
    }

    private function sanitizeTime($time)
    {
        $time = trim($time);
        if (empty($time)) {
            return null;
        }

        if (preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $time)) {
            if (strlen($time) === 5) {
                return $time . ':00';
            }
            return $time;
        }

        $timestamp = strtotime($time);
        if ($timestamp !== false) {
            return date('H:i:s', $timestamp);
        }

        return null;
    }
}
