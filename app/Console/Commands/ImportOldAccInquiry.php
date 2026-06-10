<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImportOldAccInquiry extends Command
{
    /**
     * Command name
     */
    protected $signature = 'import:old-acc-inquiry';

    /**
     * Command description
     */  
    protected $description = 'Import data from old wp_fnf_acc_inquiry table to acc_inquirys table';

    /**
     * Execute the console command
     */
    public function handle()
    {
        $this->info('Import started...');

        $oldRecords = DB::connection('mysql_old')
            ->table('wp_fnf_acc_inquiry')
            ->where('delete_status', '!=', 'deleted')
            ->get();

        if ($oldRecords->isEmpty()) {
            $this->warn('No data found in old table.');
            return Command::SUCCESS;
        }

        DB::beginTransaction();

        try {
            foreach ($oldRecords as $old) {

                DB::table('acc_inquirys')->insert([
                    'patient_id'        => $old->patient_id,
                    'patient_f_name'    => $old->patient_f_name,
                    'patient_m_name'    => $old->patient_m_name,
                    'patient_l_name'    => $old->patient_l_name,
                    'branch'            => $old->branch,
                    'branch_id'         => $old->branch_id,
                    'gender'            => $old->gender,
                    'phone_no'          => $old->phone_no,
                    'age'               => $old->age,
                    'height'            => $old->height,
                    'weight'            => $old->weight,
                    'bmi'               => $old->bmi,
                    'address'           => $old->address,
                    'refrance'          => $old->refrance,
                    'email'             => $old->email,
                    'inquiry_date'      => $old->date,   // old → new mapping
                    'inquiry_time'      => $old->time,
                    'inquery_given_by'  => $old->inquery_given_by,
                    'payment'           => $old->payment,
                    'inquiry_foc'       => $old->inquiry_foc,
                    'diagnosis'         => $old->diagnosis,
                    'user_status'       => $old->user_status,
                    'client_old_new'    => $old->client_old_new,
                    'delete_status'     => $old->delete_status,
                    'delete_by'         => $old->delete_by,
                    'status_history'    => null,
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),
                ]);
            }

            DB::commit();
            $this->info('✅ Data imported successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Import failed');
            $this->error($e->getMessage());
        }

        return Command::SUCCESS;
    }
}
