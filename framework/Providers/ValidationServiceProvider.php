<?php

namespace Framework\Providers;

use Framework\Contracts\Http\App;
use Framework\Validation\Manager;
use Framework\Validation\Rule\EmailRule;
use Framework\Validation\Rule\MinRule;
use Framework\Validation\Rule\RequiredRule;

class ValidationServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('validator', function ($app) {

            $manager = new Manager();
            $this->addRules($app, $manager);
            return $manager;
        });
    }

    public function boot()
    {
        // TODO: Implement boot() method.
    }

    private function addRules(App $app, Manager $manager)
    {
        $manager->addRule('required', new RequiredRule());
        $manager->addRule('email', new EmailRule());
        $manager->addRule('min', new MinRule());
    }


}