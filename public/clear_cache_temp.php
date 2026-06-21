<?php
// TEMPORARY FILE - DELETE AFTER USE
// Upload this to public_html/ on the live server
// Then visit: https://figurenfit.com/clear_cache_temp.php
// Then DELETE this file immediately for security!

if (php_sapi_name() !== 'cli') {
    $secret = $_GET['secret'] ?? '';
    if ($secret !== 'clear_svc_2024') {
        die('Unauthorized');
    }
}

$basePath = dirname(__DIR__); // Goes up from public/ to project root

echo "<pre>";
echo "Project root: $basePath\n\n";

// Clear view cache
$viewCachePath = $basePath . '/storage/framework/views';
$cleared = 0;
if (is_dir($viewCachePath)) {
    $files = glob($viewCachePath . '/*');
    foreach ($files as $file) {
        if (is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            unlink($file);
            $cleared++;
        }
    }
    echo "✅ View cache cleared: $cleared files deleted from $viewCachePath\n";
} else {
    echo "❌ View cache directory not found: $viewCachePath\n";
}

// Clear bootstrap cache
$cachePath = $basePath . '/bootstrap/cache';
if (is_dir($cachePath)) {
    $files = ['config.php', 'packages.php', 'services.php'];
    foreach ($files as $f) {
        $fp = $cachePath . '/' . $f;
        if (file_exists($fp)) {
            unlink($fp);
            echo "✅ Deleted bootstrap cache: $fp\n";
        }
    }
}

echo "\n✅ Done! Please DELETE this file now from the server.\n";
echo "</pre>";
