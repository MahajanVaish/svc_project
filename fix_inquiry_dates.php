<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Fixing inquiry_date format in acc_inquirys ===\n\n";

// Find all records where inquiry_date is in d/m/Y format
$rows = \DB::select("
    SELECT id, inquiry_date 
    FROM acc_inquirys 
    WHERE inquiry_date IS NOT NULL 
    AND inquiry_date != ''
    AND inquiry_date REGEXP '^[0-9]{2}/[0-9]{2}/[0-9]{4}$'
");

echo "Found " . count($rows) . " records with d/m/Y format\n\n";

$fixed = 0;
$errors = 0;

foreach ($rows as $row) {
    try {
        // Parse d/m/Y -> Y-m-d
        $date = \Carbon\Carbon::createFromFormat('d/m/Y', $row->inquiry_date);
        $newDate = $date->format('Y-m-d');
        
        \DB::table('acc_inquirys')
            ->where('id', $row->id)
            ->update(['inquiry_date' => $newDate]);
        
        $fixed++;
        if ($fixed <= 5 || $fixed % 50 == 0) {
            echo "  Fixed id={$row->id}: '{$row->inquiry_date}' -> '$newDate'\n";
        }
    } catch (\Exception $e) {
        $errors++;
        echo "  ERROR id={$row->id} '{$row->inquiry_date}': " . $e->getMessage() . "\n";
    }
}

echo "\n=== Done ===\n";
echo "Fixed: $fixed\n";
echo "Errors: $errors\n";

// Verify
echo "\nVerification - year breakdown after fix:\n";
$years = \DB::table('acc_inquirys')
    ->whereNotNull('inquiry_date')->where('inquiry_date','!=','')
    ->selectRaw("YEAR(inquiry_date) as yr, COUNT(*) as cnt")
    ->groupBy('yr')->orderBy('yr')->get();
foreach($years as $y) echo "  {$y->yr}: {$y->cnt}\n";
