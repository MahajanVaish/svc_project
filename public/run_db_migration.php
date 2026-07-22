<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<pre>";
echo "=== Starting DB Migration ===\n";

try {
    // 1. Add columns to acc_inquirys
    Schema::table('acc_inquirys', function (Blueprint $table) {
        if (!Schema::hasColumn('acc_inquirys', 'diet')) {
            $table->text('diet')->nullable();
            echo "Added column 'diet' to acc_inquirys\n";
        }
        if (!Schema::hasColumn('acc_inquirys', 'exercise')) {
            $table->text('exercise')->nullable();
            echo "Added column 'exercise' to acc_inquirys\n";
        }
        if (!Schema::hasColumn('acc_inquirys', 'sleep')) {
            $table->text('sleep')->nullable();
            echo "Added column 'sleep' to acc_inquirys\n";
        }
        if (!Schema::hasColumn('acc_inquirys', 'water')) {
            $table->text('water')->nullable();
            echo "Added column 'water' to acc_inquirys\n";
        }
        if (!Schema::hasColumn('acc_inquirys', 'joined_program_ids')) {
            $table->text('joined_program_ids')->nullable();
            echo "Added column 'joined_program_ids' to acc_inquirys\n";
        }
        if (!Schema::hasColumn('acc_inquirys', 'programs_array')) {
            $table->text('programs_array')->nullable();
            echo "Added column 'programs_array' to acc_inquirys\n";
        }
        if (!Schema::hasColumn('acc_inquirys', 'cash_payment')) {
            $table->string('cash_payment', 50)->nullable();
            echo "Added column 'cash_payment' to acc_inquirys\n";
        }
        if (!Schema::hasColumn('acc_inquirys', 'gpay_payment')) {
            $table->string('gpay_payment', 50)->nullable();
            echo "Added column 'gpay_payment' to acc_inquirys\n";
        }
        if (!Schema::hasColumn('acc_inquirys', 'cheque_payment')) {
            $table->string('cheque_payment', 50)->nullable();
            echo "Added column 'cheque_payment' to acc_inquirys\n";
        }
        if (!Schema::hasColumn('acc_inquirys', 'due_payment')) {
            $table->string('due_payment', 50)->nullable();
            echo "Added column 'due_payment' to acc_inquirys\n";
        }
    });

    echo "\n=== Columns verified/added successfully! ===\n";

    // 2. Migrate legacy data from opts/opt_meta to acc_inquirys
    echo "Migrating legacy meta data to acc_inquirys table...\n";
    $patients = DB::table('acc_inquirys')->get();
    $migratedCount = 0;

    foreach ($patients as $p) {
        // Find matching opt IDs (by patient_id or id numeric fallback)
        $optIds = DB::table('opts')
            ->where(function($q) use ($p) {
                if (!empty($p->patient_id)) {
                    $q->where('patient_id', $p->patient_id);
                }
                if (!empty($p->id)) {
                    $q->orWhere('patient_id', (string) $p->id);
                }
            })
            ->where(function ($q) {
                $q->whereNull('delete_status')
                  ->orWhere('delete_status', '')
                  ->orWhere('delete_status', '0');
            })
            ->pluck('id')
            ->toArray();

        if (empty($optIds) && !empty($p->patient_name)) {
            // Name fallback
            $optIds = DB::table('opts')
                ->where('patient_name', $p->patient_name)
                ->where(function ($q) {
                    $q->whereNull('delete_status')
                      ->orWhere('delete_status', '')
                      ->orWhere('delete_status', '0');
                })
                ->pluck('id')
                ->toArray();
        }

        if (!empty($optIds)) {
            // Load and merge metadata keys
            $meta = DB::table('opt_meta')
                ->whereIn('opt_id', $optIds)
                ->orderBy('opt_id', 'asc')
                ->get()
                ->pluck('meta_value', 'meta_key')
                ->toArray();

            $updateData = [];
            $fields = ['diet', 'exercise', 'sleep', 'water', 'joined_program_ids', 'programs_array', 'cash_payment', 'gpay_payment', 'cheque_payment', 'due_payment', 'inquiry_foc'];
            
            foreach ($fields as $field) {
                if (isset($meta[$field]) && $meta[$field] !== '') {
                    $updateData[$field] = $meta[$field];
                }
            }

            if (!empty($updateData)) {
                DB::table('acc_inquirys')->where('id', $p->id)->update($updateData);
                $migratedCount++;
            }
        }
    }

    echo "Successfully migrated data for {$migratedCount} patients!\n";
    echo "=== Migration Complete ===\n";

} catch (\Exception $e) {
    echo "Migration Failed: " . $e->getMessage() . "\n";
}
echo "</pre>";
