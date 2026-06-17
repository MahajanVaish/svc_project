<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$opt = \App\Models\Opt::where('patient_id', 'ST-02140')->first();
$patient = \App\Models\AccInquiry::withoutGlobalScope('branch_restriction')->where('patient_id', 'ST-02140')->first();

if ($opt && $patient) {
    echo "Found patient: " . $patient->patient_name . "\n";
    $controller = app(\App\Http\Controllers\Admin\InquiryDietChartController::class);
    
    $request = new \Illuminate\Http\Request();
    $request->merge([
        'total_payment' => $patient->payment ?? 0,
        'given_payment' => $patient->payment ?? 0,
        'cash_payment' => $patient->payment ?? 0,
        'payment_method' => 'Cash',
        'pod_bd_date' => date('Y-m-d')
    ]);

    // Use reflection to call private method
    $reflection = new \ReflectionClass($controller);
    $method = $reflection->getMethod('syncDietInvoiceAndTransactions');
    $method->setAccessible(true);
    
    $method->invoke($controller, $opt, $request);
    
    echo "Done syncing.\n";
} else {
    echo "Patient or Opt not found.\n";
}
