<?php

namespace Framework\Providers;

use Framework\Session\SessionManager;
use Framework\Session\SessionNativeDriver;
use Framework\Session\SessionProxy;

class SessionServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('session.manager', function ($app) {
            $manager = new SessionManager();

            $manager->addDriver('native', function ($config) {
                return new SessionNativeDriver($config);
            });

            return $manager;
        });

        $this->app->alias('session.manager', SessionManager::class);

        $this->app->bind('session', function ($app) {
            return new SessionProxy(
                $app->resolve('session.manager')->connect(\config('session.default'))
            );
        });
    }

    public function boot()
    {

    }

}