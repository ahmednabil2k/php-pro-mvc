<?php

return [

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    'local' => [
        'driver' => 'local',
        'path' => __DIR__ . "/../storage/framework/app",
    ],
    's3' => [
        'type' => 's3',
        'key' => '',
        'secret' => '',
        'token' => '',
        'region' => '',
        'bucket' => '',
    ],
    'ftp' => [
        'type' => 'ftp',
        'host' => '',
        'root' => '',
        'username' => '',
        'password' => '',
    ],
];