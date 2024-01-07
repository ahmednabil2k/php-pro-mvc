<?php

namespace Framework\Contracts\Kernel;

use Framework\Contracts\Http\App;
use Framework\Contracts\Http\Request;
use Framework\Contracts\Http\Response;
use Framework\Contracts\Pipeline;

class Http implements HttpKernel
{

    public function __construct(protected App $app){}

    /**
     * @throws \Throwable
     */
    public function bootstrap()
    {
        $this->app->bootstrap();
    }

    /**
     * @throws \Throwable
     */
    public function handle(Request $request): Response
    {
        $this->bootstrap();

        $response = (new Pipeline($this->app))
            ->send($request)
            ->through($this->middlewares())
            ->then($this->dispatchToRouter());

        return new Response($response);
    }

    private function middlewares(): array
    {
        return (array) require_once basePath() . DIRECTORY_SEPARATOR . "app/Http/kernel.php";
    }

    private function dispatchToRouter(): \Closure
    {
        return function ($request) {
            $this->app->instance('request', $request);
            return app('router')->dispatch($request);
        };
    }


    public function getApplication(): App
    {
        return $this->app;
    }
}