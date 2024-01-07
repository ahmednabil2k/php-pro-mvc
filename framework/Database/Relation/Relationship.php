<?php

namespace Framework\Database\Relation;
use Framework\Database\ModelCollector;

class Relationship
{
    private ModelCollector $collector;

    private string $method;

    public function __construct(ModelCollector $collector, string $method)
    {
        $this->collector = $collector;
        $this->method = $method;
    }
    public function __invoke(array $parameters = []): ModelCollector
    {
        return $this->collector;
    }
    public function __call(string $method, array $parameters = []): mixed
    {
        return $this->collector->$method(...$parameters);
    }

    public function method(): string
    {
        return $this->method;
    }

}

