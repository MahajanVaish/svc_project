<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$patient1 = \App\Models\AccInquiry::where('patient_f_name', 'like', '%Saziya%')
            ->orWhere('patient_name', 'like', '%Saziya%')
            ->first();
if ($patient1) {
    echo "Found in AccInquiry: ID=" . $patient1->id . " PatientID=" . $patient1->patient_id . " Name=" . $patient1->patient_name . " Status=" . $patient1->user_status . "\n";
} else {
    echo "Not found in AccInquiry\n";
}

$patient2 = \App\Models\PatientInquiry::where('patient_name', 'like', '%Saziya%')->first();
if ($patient2) {
    echo "Found in PatientInquiry: ID=" . $patient2->id . " PatientID=" . $patient2->patient_id . "\n";
} else {
    echo "Not found in PatientInquiry\n";
}
