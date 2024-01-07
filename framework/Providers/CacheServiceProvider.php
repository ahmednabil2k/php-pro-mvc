<?php

namespace Framework\Providers;

use Framework\Cache\CacheProxy;
use Framework\Cache\InMemoryDriver;
use Framework\Cache\FileCacheDriver;
use Framework\Cache\MemCacheDriver;
use Framework\Cache\CacheManager;

class CacheServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('cache.manager', function ($app) {
            $manager = new CacheManager();

            $manager->addDriver('memory', function ($config) {
                return new InMemoryDriver($config);
            });

            $manager->addDriver('file', function ($config) {
                return new FileCacheDriver($config);
            });

            $manager->addDriver('memcache', function ($config) {
                return new MemCacheDriver($config);
            });

            return $manager;
        });

        $this->app->alias('cache.manager', CacheManager::class);

        $this->app->bind('cache', function ($app) {
            return new CacheProxy(
                $app->resolve('cache.manager')->connect(\config('cache.default'))
            );
        });
    }

    public function boot()
    {

    }

}