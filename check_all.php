<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== COMPLETE DATABASE AUDIT ===\n\n";

// All branches
$branches = \DB::table('branches')->where('delete_status','0')->get();
echo "Branches:\n";
foreach($branches as $b) echo "  {$b->branch_id} = {$b->branch_name}\n";

echo "\n=== acc_inquirys (FNF branches) ===\n";
$acc = \DB::table('acc_inquirys')->where('delete_status','0')
    ->selectRaw("branch_id, YEAR(inquiry_date) as yr, COUNT(*) as cnt")
    ->groupBy('branch_id','yr')->orderBy('branch_id')->orderBy('yr')->get();
foreach($acc as $r) echo "  branch={$r->branch_id} year={$r->yr} count={$r->cnt}\n";

// Check bad dates still remaining
$bad = \DB::table('acc_inquirys')->where('delete_status','0')
    ->whereRaw("inquiry_date IS NOT NULL AND inquiry_date != '' AND YEAR(inquiry_date) IS NULL")
    ->selectRaw("COUNT(*) as cnt, MIN(inquiry_date) as sample")->first();
echo "\n  Bad dates remaining: {$bad->cnt} (sample: {$bad->sample})\n";

echo "\n=== patient_inquiry (SVC) ===\n";
$svc = \DB::table('patient_inquiry')
    ->selectRaw("branch_id, YEAR(inquiry_date) as yr, COUNT(*) as cnt")
    ->groupBy('branch_id','yr')->orderBy('branch_id')->orderBy('yr')->get();
foreach($svc as $r) echo "  branch={$r->branch_id} year={$r->yr} count={$r->cnt}\n";

echo "\n=== lhr_inquiries ===\n";
$lhr = \DB::table('lhr_inquiries')->whereNull('deleted_at')
    ->selectRaw("branch_id, YEAR(inquiry_date) as yr, COUNT(*) as cnt")
    ->groupBy('branch_id','yr')->orderBy('yr')->get();
foreach($lhr as $r) echo "  branch={$r->branch_id} year={$r->yr} count={$r->cnt}\n";

echo "\n=== hydra_inquiries ===\n";
$hydra = \DB::table('hydra_inquiries')
    ->selectRaw("branch_id, YEAR(inquiry_date) as yr, COUNT(*) as cnt")
    ->groupBy('branch_id','yr')->orderBy('yr')->get();
foreach($hydra as $r) echo "  branch={$r->branch_id} year={$r->yr} count={$r->cnt}\n";

echo "\n=== Dashboard vs Analytics comparison ===\n";
// Dashboard: total count per branch
$dash_pp = \DB::table('acc_inquirys')->where('branch_id','PP-0002')->where('delete_status','0')->count();
echo "Dashboard FNF PP total: $dash_pp\n";

// Analytics 2025
$anal_pp_2025 = \DB::table('acc_inquirys')->where('branch_id','PP-0002')->where('delete_status','0')
    ->whereYear('inquiry_date', 2025)->count();
echo "Analytics FNF PP 2025: $anal_pp_2025\n";

// Analytics 2026  
$anal_pp_2026 = \DB::table('acc_inquirys')->where('branch_id','PP-0002')->where('delete_status','0')
    ->whereYear('inquiry_date', 2026)->count();
echo "Analytics FNF PP 2026: $anal_pp_2026\n";

// Total with valid years
$valid = \DB::table('acc_inquirys')->where('branch_id','PP-0002')->where('delete_status','0')
    ->whereRaw("YEAR(inquiry_date) IS NOT NULL AND YEAR(inquiry_date) > 2000")->count();
echo "FNF PP with valid inquiry_date: $valid\n";
