<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== FIXING ALL BAD DATES ===\n\n";

// Disable strict mode for this session
\DB::statement("SET SESSION sql_mode = ''");

$tables = ['acc_inquirys', 'patient_inquiry', 'lhr_inquiries', 'hydra_inquiries'];

foreach ($tables as $table) {
    echo "--- Table: $table ---\n";
    
    $rows = \DB::table($table)->whereNotNull('inquiry_date')->select('id', 'inquiry_date')->get();
    
    $fixed = 0; $nulled = 0;
    foreach ($rows as $row) {
        $val = trim($row->inquiry_date);
        if ($val === '') {
            continue;
        }
        
        // If already Y-m-d format
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $val)) {
            $year = (int)substr($val, 0, 4);
            if ($year < 1950 || $year > 2030) {
                \DB::table($table)->where('id', $row->id)->update(['inquiry_date' => null]);
                $nulled++;
            }
            continue;
        }
        
        // Try d/m/Y
        $newDate = null;
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $val, $m)) {
            try {
                $d = \Carbon\Carbon::createFromFormat('d/m/Y', $val);
                if ($d->year >= 1950 && $d->year <= 2030) {
                    $newDate = $d->format('Y-m-d');
                    $fixed++;
                }
            } catch(\Exception $e) {}
        }
        
        \DB::table($table)->where('id', $row->id)->update(['inquiry_date' => $newDate]);
        if (!$newDate) {
            $nulled++;
        }
    }
    echo "  Fixed/Cleaned: $fixed, Nulled invalid: $nulled\n\n";
}

echo "\n=== FINAL SUMMARY ===\n";

$tables_info = [
    ['table' => 'acc_inquirys',    'filter' => "delete_status = '0'"],
    ['table' => 'patient_inquiry', 'filter' => "deleted_at IS NULL"],
    ['table' => 'lhr_inquiries',   'filter' => "deleted_at IS NULL"],
    ['table' => 'hydra_inquiries', 'filter' => "1=1"],
];

foreach ($tables_info as $t) {
    echo "\n{$t['table']}:\n";
    $rows = \DB::select("SELECT branch_id, YEAR(inquiry_date) as yr, COUNT(*) as cnt FROM `{$t['table']}` WHERE {$t['filter']} AND inquiry_date IS NOT NULL GROUP BY branch_id, YEAR(inquiry_date) ORDER BY branch_id, yr");
    foreach ($rows as $r) {
        if ($r->yr >= 2020 || $r->yr == null) {
            echo "  branch={$r->branch_id} year={$r->yr} count={$r->cnt}\n";
        }
    }
}

echo "\n=== Dashboard vs Analytics (FNF PP) ===\n";
$total = \DB::table('acc_inquirys')->where('branch_id','PP-0002')->where('delete_status','0')->count();
echo "Total all time: $total\n";
foreach ([2023,2024,2025,2026] as $y) {
    $cnt = \DB::table('acc_inquirys')->where('branch_id','PP-0002')->where('delete_status','0')->whereYear('inquiry_date',$y)->count();
    echo "  $y: $cnt\n";
}
