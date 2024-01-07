<?php

namespace Framework\Routing;

use Exception;

class Route
{
    /**
     * @var string
     */
    private string $method;

    /**
     * @var string
     */
    private string $path;

    /**
     * @var array|callable|string
     */
    private $handler;

    /**
     * @var array
     */
    protected array $parameters = [];

    /**
     * @var string|null
     */
    protected ?string $name = null;

    /**
     * @param string $method
     * @param string $path
     * @param array|string|callable $handler
     */
    public function __construct(
        string $method,
        string $path,
        array|string|callable $handler
    )
    {
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;
    }

    /**
     * @param string $method
     * @param string $path
     * @return bool
     */
    public function matches(string $method, string &$path): bool
    {
        if (
            $this->method() === $method
            && $this->path() === $path
        ) {
            return true;
        }

        $parameterNames = [];
        $pattern = $this->normalisePath($this->path());
        //$path = rtrim($path, '/');

        $pattern = preg_replace_callback('#{([^}]+)}/#', function (array $found) use (&$parameterNames) {
            $parameterNames[] = rtrim($found[1], '?');

            if (str_ends_with($found[1], '?')) {
                return '([^/]*)(?:/?)';
            }

            return '([^/]+)/';
        }, $pattern);

        if (
            !str_contains($pattern, '+')
            && !str_contains($pattern, '*')
        ) {
            return false;
        }

        preg_match_all("#{$pattern}#", $this->normalisePath($path), $matches);

        $parameterValues = [];

        if (count($matches[1]) > 0) {
            foreach ($matches[1] as $value) {
                if ($value) {
                    $parameterValues[] = $value;
                    continue;
                }

                $parameterValues[] = null;
            }

            $emptyValues = array_fill(0, count($parameterNames), false);
            $parameterValues += $emptyValues;

            $this->parameters = array_combine($parameterNames, $parameterValues);

            return true;
        }

        return false;
    }

    /**
     * @param string $path
     * @return string
     */
    private function normalisePath(string $path): string
    {
        $path = trim($path, '/');
        $path = "/{$path}/";
        return preg_replace('/[\/]{2,}/', '/', $path);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function dispatch(): mixed
    {

        if ($this->handler instanceof \Closure)
            return app()->call($this->handler);

        if (is_array($this->handler)) {
            [$controller, $method] = $this->handler;
            return app()->call([$controller, $method]);
        }

        if (is_string($this->handler)) {
            [$controller, $method] = explode('@', $this->handler);
            return app()->call([$controller, $method]);
        }

        throw new Exception('Invalid route action...');
    }


    /**
     * @return string
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * @return callable
     */
    public function handler(): callable
    {
        return $this->handler;
    }

    /**
     * @return array
     */
    public function parameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param string|null $name
     * @return Route|string|null
     */
    public function name(string $name = null): Route|string|null
    {
        if ($name) {
            $this->name = $name;
            return $this;
        }

        return $this->name;
    }

}