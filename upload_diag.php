<?php
$host = 'figurenfit.com';
$port = 21;
$user = 'zhz51w9gtvox';
$pass = 'gwjB$sR8$MB7L7UW';

$conn = ftp_connect($host, $port, 15);
if (!$conn || !ftp_login($conn, $user, $pass)) {
    echo "FTP login failed!\n";
    exit;
}

ftp_pasv($conn, true);

// Create the diagnostic script contents
$diagCode = <<<'CODE'
<?php
// Live DB Diagnostic Script
define('LARAVEL_START', microtime(true));

$paths = [
    __DIR__.'/svc_final/bootstrap/app.php',
    __DIR__.'/../svc_final/bootstrap/app.php',
    __DIR__.'/../bootstrap/app.php',
    __DIR__.'/bootstrap/app.php'
];

$appPath = null;
foreach ($paths as $path) {
    if (file_exists($path)) {
        $appPath = $path;
        break;
    }
}

if (!$appPath) {
    echo "ERROR: Could not locate bootstrap/app.php!\n";
    exit;
}

require dirname($appPath).'/../vendor/autoload.php';
$app = require_once $appPath;
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain');

echo "Laravel DB Diagnostic\n";
echo "---------------------\n";

try {
    $patientsCount = \App\Models\AccInquiry::count();
    echo "Total Inquiries (Patients): $patientsCount\n";
    
    $optsCount = \App\Models\Opt::count();
    echo "Total Opts: $optsCount\n";
    
    // Find the patient with ID 2035
    $patientId = 2035;
    $patient = \App\Models\AccInquiry::find($patientId);
    if ($patient) {
        echo "\nPatient 2035 Details:\n";
        echo "ID: " . $patient->id . "\n";
        echo "Patient Code: " . $patient->patient_id . "\n";
        echo "Name: " . $patient->patient_name . "\n";
        echo "Height: " . $patient->height . "\n";
        echo "Weight: " . $patient->weight . "\n";
        echo "BMI: " . $patient->bmi . "\n";
        echo "Inquiry FOC: " . $patient->inquiry_foc . "\n";
        echo "Delete Status: " . $patient->delete_status . "\n";
        
        // Find Opt records for this patient
        $opts = \App\Models\Opt::where('patient_id', $patient->patient_id)->get();
        echo "\nOpt records found for patient " . $patient->patient_id . ": " . $opts->count() . "\n";
        foreach ($opts as $opt) {
            echo " - Opt ID: " . $opt->id . " | Created At: " . $opt->created_at . "\n";
            $metas = \App\Models\OptMeta::where('opt_id', $opt->id)->get()->pluck('meta_value', 'meta_key')->toArray();
            echo "   Metas:\n";
            print_r($metas);
        }
    } else {
        echo "Patient 2035 not found!\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
CODE;

// Upload to all potential public folders
$destinations = [
    "public_html/SVC_Project/db_diag.php",
    "public_html/SVC_Project/public/db_diag.php",
    "public_html/SVC_Project/svc_final/public/db_diag.php"
];

foreach ($destinations as $dest) {
    $tempFile = fopen('php://temp', 'r+');
    fwrite($tempFile, $diagCode);
    rewind($tempFile);
    
    if (@ftp_fput($conn, $dest, $tempFile, FTP_BINARY)) {
        echo "Uploaded successfully to $dest!\n";
    } else {
        echo "Failed to upload to $dest\n";
    }
}

ftp_close($conn);
