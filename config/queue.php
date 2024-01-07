<?php

return [

    'default' => env('QUEUE_DRIVER', 'sync'),

    'sync' => [
        'driver' => 'sync',
        'attempts' => 3,
    ],

    'database' => [
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'attempts' => 3,
    ],
];