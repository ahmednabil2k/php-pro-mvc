<?php

namespace Framework\Providers;

use Framework\Contracts\Http\App;
use Framework\Routing\Router;
use Framework\Validation\Manager;
use Framework\Validation\Rule\EmailRule;
use Framework\Validation\Rule\MinRule;
use Framework\Validation\Rule\RequiredRule;

class RouteServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('router', function ($app) {
            $router = new Router();
            $this->apiRoutes($router);
            $this->webRoutes($router);
            return $router;
        });

        $this->app->alias('router', Router::class);;
    }

    public function boot()
    {

    }

    private function webRoutes(Router $router)
    {
        $routes = require_once basePath() . "/routes/web.php";
        $routes($router);
    }

    private function apiRoutes(Router $router)
    {
        $routes = require_once basePath() . "/routes/api.php";
        $routes($router);
    }


}