<?php

namespace Framework\Providers;
use Framework\Contracts\Http\App;

abstract class ServiceProvider
{
    protected App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    abstract public function register();
    abstract public function boot();

}