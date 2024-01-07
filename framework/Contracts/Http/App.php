<?php

namespace Framework\Contracts\Http;

use Dotenv\Dotenv;
use Framework\Contracts\Container;
use Throwable;

class App extends Container
{
    private static $instance;

    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct(){}
    private function __clone() {}

    /**
     * @throws Throwable
     */
    public function bootstrap()
    {
        $basePath = basePath();
        $this->configure($basePath);
        $this->bindProviders($basePath);
    }

    private function configure(?string $basePath)
    {
        $dotenv = Dotenv::createImmutable($basePath);
        $dotenv->load();
    }

    /**
     * @throws \ReflectionException
     */
    private function bindProviders(?string $basePath)
    {
        $providers = require_once $basePath . '/config/providers.php';

        foreach ($providers as $index => $provider) {
            $service = new $provider($this);
            $service->register();
            $providers[$index] = $service;
        }

        foreach ($providers as $provider) {
            $this->call([$provider, 'boot']);
        }
    }

}