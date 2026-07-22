<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Tables and Row Counts ===\n";
$tables = DB::select('SHOW TABLES');
$dbName = env('DB_DATABASE');
$keyName = "Tables_in_" . $dbName;

foreach ($tables as $table) {
    $tName = $table->$keyName;
    try {
        $count = DB::table($tName)->count();
        echo "  Table: $tName - Rows: $count\n";
    } catch (\Exception $e) {
        echo "  Table: $tName - Error: " . $e->getMessage() . "\n";
    }
}
