<?php

use App\Models\Log;

function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    // Uncomment one of the following alternatives
    $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
}

function setActive($path, $active = 'is-active')
{
    return (Request::is($path) || Request::is($path.'/*')) ? $active : '';
}

function logdb($message, $context = null, $extra = null, $level = 'INFO') {
    $env = app()->environment();

    $log = Log::create([
        'env' => $env,
        'message' => $message,
        'level' => $level,
        'context' => $context,
        'extra' => $extra,
    ]);

    return $log;
}
