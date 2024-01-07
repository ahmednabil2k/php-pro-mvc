<?php

return [

    'default' => env('CACHE_DRIVER', 'memory'),

    'prefix' => env('CACHE_PREFIX', 'cache_'),

    'memory' => [
        'driver' => 'memory',
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