<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$metas = \App\Models\OptMeta::where('opt_id', 525)->get();
foreach ($metas as $m) {
    echo $m->meta_key . " => [" . $m->meta_value . "]\n";
}
