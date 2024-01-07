<?php

namespace Framework\Providers;

use Framework\Config\Config;

class ConfigServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('config', function ($app) {
           return new Config();
        });

        $this->app->alias('config', Config::class);
    }

    public function boot()
    {

    }

}