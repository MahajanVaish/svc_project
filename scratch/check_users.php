<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$users = User::all();
echo "=== User Branch and Roles ===\n";
foreach ($users as $u) {
    $roles = $u->roles->pluck('name')->toArray();
    echo "  User: {$u->name} (ID: {$u->id}) - Branch: '{$u->user_branch}' - Roles: " . implode(', ', $roles) . "\n";
}
