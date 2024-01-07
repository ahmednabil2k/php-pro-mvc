<?php

namespace Framework\Providers;

use Framework\Database\Connection\MysqlConnection;
use Framework\Database\Factory;
class DBServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('db.factory', function ($app) {
            $factory = new Factory();

            $factory = $factory->addConnector('mysql', function ($config) {
                return new MysqlConnection($config);
            });

            return $factory;
        });

        $this->app->bind('db.default', function ($app) {
            return $app->resolve('db.factory')->connect(config('database.default'));
        });

    }

    public function boot()
    {

    }



}