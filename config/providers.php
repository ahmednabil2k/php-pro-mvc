<?php

use Framework\Providers\{CacheServiceProvider,
    ConfigServiceProvider,
    DBServiceProvider,
    FilesystemServiceProvider,
    QueueServiceProvider,
    RouteServiceProvider,
    SessionServiceProvider,
    ValidationServiceProvider,
    ViewServiceProvider};

return [
    ConfigServiceProvider::class,
    SessionServiceProvider::class,
    DBServiceProvider::class,
    CacheServiceProvider::class,
    FilesystemServiceProvider::class,
    QueueServiceProvider::class,
    RouteServiceProvider::class,
    ViewServiceProvider::class,
    ValidationServiceProvider::class
];