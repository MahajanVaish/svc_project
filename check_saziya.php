<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$patient = \App\Models\AccInquiry::where('patient_f_name', 'like', '%Saziya%')->first();
if ($patient) {
    echo "AccInquiry ID: " . $patient->id . "\n";
    echo "Patient ID: " . $patient->patient_id . "\n";
    $opts = \App\Models\Opt::where('patient_id', $patient->patient_id)->count();
    echo "Opts count: " . $opts . "\n";
} else {
    echo "Not found in AccInquiry\n";
}
