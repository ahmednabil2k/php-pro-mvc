<?php

use App\Http\Controllers\HomeController;
use Framework\Routing\Router;

return function (Router $router) {

    $router
        ->get(
            '/home',
                [HomeController::class, 'index']
            )
        ->name('home');

    $router->prefix('register')
        ->group(function () use($router) {

            $router
                ->get(
                    '',
                    [HomeController::class, 'showRegisterForm']
                )->name('show-register-form');

            $router
                ->post(
                    '',
                    [HomeController::class, 'register']
                )->name('register');

        });
};
