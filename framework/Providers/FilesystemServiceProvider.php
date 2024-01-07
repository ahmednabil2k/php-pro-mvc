<?php

namespace Framework\Providers;

use Framework\Filesystem\FilesystemManager;
use Framework\Filesystem\LocalFilesystem;
class FilesystemServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('filesystem.manager', function ($app) {
            $manager = new FilesystemManager();

            $manager->addDriver('local', function ($config) {
                return new LocalFilesystem($config);
            });

            return $manager;
        });

        $this->app->alias('filesystem.manager', FilesystemManager::class);

        $this->app->bind('filesystem', function ($app) {
            return $app->resolve('filesystem.manager')->connect(\config('filesystem.default'));
        });
    }

    public function boot()
    {

    }

}