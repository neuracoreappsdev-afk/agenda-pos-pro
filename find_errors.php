<?php
$logPath = 'storage/logs/laravel.log';
if (file_exists($logPath)) {
    $log = file_get_contents($logPath);
    preg_match_all('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] \w+\.ERROR: ([^#\n]+)/', $log, $matches);
    $lastErrors = array_slice($matches[1], -10);
    foreach ($lastErrors as $error) {
        echo "ERROR: " . trim($error) . "\n";
    }
}
