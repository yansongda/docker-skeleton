<?php

declare(strict_types=1);

return [
    'app_name' => env('APP_NAME', 'app'),
    'app_env' => env('APP_ENV', 'prod'),
    'scan_cacheable' => env('SCAN_CACHEABLE', false),

    'app' => [
        'name' => env('APP_NAME', 'app'),
        'env' => env('APP_ENV', 'prod'),
        'url' => env('APP_URL', 'https://yansongda.cn/'),
    ],
];
