<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$branchId = 'PP-0002';

// Check what format those 453 "year blank" records have
echo "Sample of 453 records with blank year in inquiry_date:\n";
$rows = \DB::table('acc_inquirys')
    ->where('branch_id', $branchId)
    ->where('delete_status','0')
    ->whereRaw("inquiry_date != '' AND inquiry_date IS NOT NULL AND YEAR(inquiry_date) IS NULL")
    ->select('id','patient_f_name','inquiry_date','created_at')
    ->limit(10)->get();

foreach($rows as $r) {
    echo "  id={$r->id} inquiry_date='{$r->inquiry_date}' created_at='{$r->created_at}'\n";
}

// Try raw value
echo "\nRaw inquiry_date values (BINARY read):\n";
$raw = \DB::select("SELECT id, CAST(inquiry_date AS CHAR) as raw_date FROM acc_inquirys WHERE branch_id = ? AND delete_status = '0' AND YEAR(inquiry_date) IS NULL LIMIT 5", [$branchId]);
foreach($raw as $r) {
    echo "  id={$r->id} raw='{$r->raw_date}'\n";
}
