<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImportOldCharges extends Command
{
    /**
     * Command name
     */
    protected $signature = 'import:old-charges';

    /**
     * Command description
     */
    protected $description = 'Import charges data from wp_fnf_acc_svc_charges to charges table';

    /**
     * Execute the console command
     */
    public function handle()
    {
        $this->info('Charges import started...');

        // 🔴 Old database connection forcefully use karo
        $oldCharges = DB::connection('mysql_old')
            ->table('wp_fnf_acc_svc_charges')
            ->where('delete_status', '!=', 'deleted')
            ->get();

        if ($oldCharges->isEmpty()) {
            $this->warn('No charges data found in old table.');
            return Command::SUCCESS;
        }

        DB::beginTransaction();

        try {
            foreach ($oldCharges as $charge) {

                DB::table('charges')->insert([
                    'charges_name'  => $charge->charges_name,
                    'charges_price' => $charge->charges_price,
                    'delete_status' => $charge->delete_status,
                    'delete_by'     => is_numeric($charge->delete_by) ? $charge->delete_by : null,
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                    'deleted_at'    => null,
                ]);
            }

            DB::commit();
            $this->info('✅ Charges data imported successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Charges import failed');
            $this->error($e->getMessage());
        }

        return Command::SUCCESS;
    }
}
