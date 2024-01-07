<?php

return [

    'default' => env('SESSION_DRIVER', 'native'),

    'prefix' => env('SESSION_PREFIX', 'session_'),

    'native' => [
        'driver' => 'native',
        'seconds' => 31536000,
    ],
    'file' => [
        'driver' => 'file',
        'seconds' => 31536000,
    ],
    'memcache' => [
        'driver' => 'memcache',
        'host' => '127.0.0.1',
        'port' => 11211,
        'seconds' => 31536000,
    ],
];