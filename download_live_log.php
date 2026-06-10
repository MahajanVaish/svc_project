
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

$logPath = "public_html/SVC_Project/svc_final/storage/logs/laravel.log";
echo "Downloading $logPath...\n";

$tempFile = fopen('php://temp', 'r+');
if (@ftp_fget($conn, $tempFile, $logPath, FTP_BINARY)) {
    rewind($tempFile);
    $content = stream_get_contents($tempFile);
    echo "SUCCESS: Last 30000 chars of $logPath:\n";
    echo substr($content, -30000);
} else {
    echo "FAILED to retrieve $logPath.\n";
}

ftp_close($conn);
