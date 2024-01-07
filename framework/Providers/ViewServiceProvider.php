<?php

namespace Framework\Providers;

use Exception;
use Framework\Contracts\Http\App;
use Framework\View\Engine\BasicEngine;
use Framework\View\Engine\BladeEngine;
use Framework\View\Manager;

class ViewServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('view', function ($app) {

            $manager = new Manager();

            $this->bindPaths($app, $manager);
            $this->bindMacros($app, $manager);
            $this->bindEngines($app, $manager);

            return $manager;
        });
    }

    public function boot()
    {
        // TODO: Implement boot() method.
    }

    private function bindPaths(App $app, Manager $manager)
    {
        $basePath = basePath();
        $manager->addPath($basePath . '/resources/views');
    }

    private function bindMacros(App $app, Manager $manager)
    {
        $manager->addMacro('escape', fn(string $value) => htmlspecialchars($value));
        $manager->addMacro('include', fn(...$params) => print view(...$params));
    }

    /**
     * @throws Exception
     */
    private function bindEngines(App $app, Manager $manager)
    {
        $app->bind('view.engine.basic', fn() => new BasicEngine());
        $app->bind('view.engine.blade', fn() => new BladeEngine());

        $manager->addEngine('basic.php', $app->resolve('view.engine.basic'));
        $manager->addEngine('blade.php', $app->resolve('view.engine.blade'));
    }
}