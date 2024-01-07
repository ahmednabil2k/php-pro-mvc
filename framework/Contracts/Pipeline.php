<?php

namespace Framework\Contracts;

use Framework\Contracts\Http\App;

class Pipeline
{
    protected App $app;

    protected mixed $data;
    protected array $handlers = [];

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function send(mixed $data): static
    {
        $this->data = $data;
        return $this;
    }

    public function through(array $handlers = []): static
    {
        $this->handlers = $handlers;
        return $this;
    }

    public function then(\Closure $destination)
    {
        if (empty($this->handlers))
            return call_user_func($destination);

        $pipeline = array_reduce(
            array_reverse($this->handlers),
            function ($nextClosure, $handler) {
                return function ($data) use ($nextClosure, $handler) {
                    return (new $handler())->handle($data, $nextClosure);
                };
            },
            function ($data) use ($destination) {
                return $destination($data);
            }
        );

        return $pipeline($this->data);
    }

}