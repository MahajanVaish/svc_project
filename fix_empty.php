<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Use raw PDO to bypass strict mode
$pdo = \DB::connection()->getPdo();
$pdo->exec("SET SESSION sql_mode = ''");

echo "SQL mode disabled\n";

$tables = ['patient_inquiry', 'acc_inquirys', 'lhr_inquiries', 'hydra_inquiries'];

// Fix empty string dates
foreach ($tables as $t) {
    $stmt = $pdo->exec("UPDATE `$t` SET inquiry_date = NULL WHERE inquiry_date = ''");
    if ($stmt > 0) echo "Fixed $stmt empty string dates in $t\n";
}

// Fix d/m/Y format in patient_inquiry
$stmt = $pdo->prepare("SELECT id, inquiry_date FROM patient_inquiry WHERE inquiry_date IS NOT NULL AND inquiry_date REGEXP '^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$'");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
$fixed = 0;
$upd = $pdo->prepare("UPDATE patient_inquiry SET inquiry_date = ? WHERE id = ?");
foreach ($rows as $r) {
    try {
        $d = \Carbon\Carbon::createFromFormat('d/m/Y', $r->inquiry_date);
        if ($d->year >= 1950 && $d->year <= 2030) {
            $upd->execute([$d->format('Y-m-d'), $r->id]);
            $fixed++;
        } else {
            $upd->execute([null, $r->id]);
        }
    } catch (\Exception $e) {
        $upd->execute([null, $r->id]);
    }
}
echo "Fixed $fixed d/m/Y dates in patient_inquiry\n";

// Null invalid years
foreach (['patient_inquiry', 'acc_inquirys'] as $t) {
    $stmt2 = $pdo->prepare("SELECT id FROM `$t` WHERE inquiry_date IS NOT NULL AND YEAR(inquiry_date) IS NOT NULL AND (YEAR(inquiry_date) < 1950 OR YEAR(inquiry_date) > 2030)");
    $stmt2->execute();
    $rows2 = $stmt2->fetchAll(PDO::FETCH_OBJ);
    $upd2 = $pdo->prepare("UPDATE `$t` SET inquiry_date = NULL WHERE id = ?");
    foreach ($rows2 as $r) $upd2->execute([$r->id]);
    if (count($rows2)) echo "Nulled " . count($rows2) . " invalid year dates in $t\n";
}

echo "\n=== FINAL COUNTS ===\n";
$checks = [
    'FNF PP (acc_inquirys)'   => "SELECT COUNT(*) FROM acc_inquirys WHERE branch_id='PP-0002' AND delete_status='0'",
    'SVC (patient_inquiry)'   => "SELECT COUNT(*) FROM patient_inquiry WHERE branch_id='SVC-0005'",
    'LHR (lhr_inquiries)'     => "SELECT COUNT(*) FROM lhr_inquiries WHERE branch_id='LB-0007'",
    'HYDRA (hydra_inquiries)' => "SELECT COUNT(*) FROM hydra_inquiries WHERE branch_id='BH-00023'",
];
foreach ($checks as $label => $sql) {
    $cnt = $pdo->query($sql)->fetchColumn();
    echo "  $label: $cnt\n";
}

echo "\n=== FNF PP by year (analytics) ===\n";
$expr = "CASE WHEN inquiry_date REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$' THEN inquiry_date ELSE NULL END";
$stmt3 = $pdo->query("SELECT YEAR($expr) as yr, COUNT(*) as cnt FROM acc_inquirys WHERE branch_id='PP-0002' AND delete_status='0' AND $expr IS NOT NULL GROUP BY yr ORDER BY yr");
foreach ($stmt3->fetchAll(PDO::FETCH_OBJ) as $r) echo "  {$r->yr}: {$r->cnt}\n";
