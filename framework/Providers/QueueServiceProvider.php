<?php

namespace Framework\Providers;

use Framework\Config\Config;
use Framework\Queue\DatabaseDriver;
use Framework\Queue\QueueManager;

class QueueServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('queue.manager', function ($app) {

            $manager = new QueueManager();

            $manager->addDriver('database', function (array $config) {
                return new DatabaseDriver($config);
            });

            return $manager;
        });

        $this->app->alias('queue.manager', QueueManager::class);

        $this->app->bind('queue', function ($app) {
           return $app->resolve('queue.manager')->connect(\config('queue.default'));
        });

    }

    public function boot()
    {

    }

}