<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

\DB::statement("SET SESSION sql_mode = ''");

echo "=== DASHBOARD COUNTS (getTotalPatients logic) ===\n";
$branches = [
    ['id' => 'PP-0002', 'name' => 'FNF PP',    'table' => 'acc_inquirys'],
    ['id' => 'ST-0001', 'name' => 'FNF ST',    'table' => 'acc_inquirys'],
    ['id' => 'BD-0004', 'name' => 'FNF BD',    'table' => 'acc_inquirys'],
    ['id' => 'SVC-0005','name' => 'SVC',        'table' => 'patient_inquiry'],
    ['id' => 'LB-0007', 'name' => 'LHR',        'table' => 'lhr_inquiries'],
    ['id' => 'BH-00023','name' => 'HYDRA',      'table' => 'hydra_inquiries'],
];

foreach ($branches as $b) {
    $t = $b['table'];
    $filter = $t === 'acc_inquirys' ? "delete_status = '0'" : ($t === 'lhr_inquiries' ? "deleted_at IS NULL" : "1=1");
    $cnt = \DB::select("SELECT COUNT(*) as cnt FROM `$t` WHERE branch_id = ? AND $filter", [$b['id']])[0]->cnt;
    echo "  {$b['name']} ({$b['id']}): $cnt\n";
}

echo "\n=== ANALYTICS COUNTS by year (using date fix) ===\n";
$dateExpr = "CASE 
    WHEN inquiry_date REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$' THEN inquiry_date
    WHEN inquiry_date REGEXP '^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$' THEN STR_TO_DATE(inquiry_date, '%d/%m/%Y')
    ELSE NULL END";

foreach (['PP-0002' => 'acc_inquirys'] as $bid => $table) {
    echo "\n  $bid ($table):\n";
    $rows = \DB::select("SELECT YEAR($dateExpr) as yr, COUNT(*) as cnt FROM `$table` WHERE branch_id = ? AND delete_status = '0' AND $dateExpr IS NOT NULL GROUP BY yr ORDER BY yr", [$bid]);
    foreach ($rows as $r) echo "    {$r->yr}: {$r->cnt}\n";
    $total = \DB::select("SELECT COUNT(*) as cnt FROM `$table` WHERE branch_id = ? AND delete_status='0'", [$bid])[0]->cnt;
    echo "    TOTAL all time: $total\n";
}

echo "\n=== SVC patient_inquiry by year ===\n";
$svcExpr = "CASE 
    WHEN inquiry_date REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$' THEN inquiry_date
    WHEN inquiry_date REGEXP '^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$' THEN STR_TO_DATE(inquiry_date, '%d/%m/%Y')
    ELSE created_at END";
$rows = \DB::select("SELECT YEAR($svcExpr) as yr, COUNT(*) as cnt FROM patient_inquiry WHERE branch_id = 'SVC-0005' AND $svcExpr IS NOT NULL GROUP BY yr ORDER BY yr");
foreach ($rows as $r) if ($r->yr >= 2020) echo "  {$r->yr}: {$r->cnt}\n";
$total = \DB::select("SELECT COUNT(*) as cnt FROM patient_inquiry WHERE branch_id='SVC-0005'")[0]->cnt;
echo "  TOTAL all time: $total\n";

echo "\n=== BAD DATES REMAINING ===\n";
foreach (['acc_inquirys','patient_inquiry','lhr_inquiries','hydra_inquiries'] as $t) {
    $bad = \DB::select("SELECT COUNT(*) as cnt FROM `$t` WHERE inquiry_date IS NOT NULL AND inquiry_date NOT REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$' AND inquiry_date != ''");
    if ($bad[0]->cnt > 0) echo "  $t: {$bad[0]->cnt} bad dates\n";
}
echo "  Done\n";
