<?php

namespace Framework\Routing;

use Exception;
use Framework\Contracts\Http\Request;
use Framework\Exception\ExceptionHandler;
use Throwable;

class Router
{
    const GET = "GET";
    const POST = "POST";
    const PUT = "PUT";
    const DELETE = "DELETE";
    const PATCH = "PATCH";

    /**
     * @var array
     */
    protected array $routes = [];

    /**
     * @var array
     */
    protected array $errorHandlers = [];

    /**
     * @var Route
     */
    protected Route $current;

    /**
     * @var string
     */
    private string $prefix = '';

    /**
     * @var array
     */
    protected array $groupPrefixes = [];

    /**
     * @param string $path
     * @param array|string|callable $handler
     * @return Route
     */
    public function get(string $path, array|string|callable $handler): Route
    {
        return $this->add(self::GET, $path, $handler);
    }

    /**
     * @param string $path
     * @param array|string|callable $handler
     * @return Route
     */
    public function post(string $path, array|string|callable $handler): Route
    {
        return $this->add(self::POST, $path, $handler);
    }

    /**
     * @param string $path
     * @param array|string|callable $handler
     * @return Route
     */
    public function put(string $path, array|string|callable $handler): Route
    {
        return $this->add(self::PUT, $path, $handler);
    }

    /**
     * @param string $path
     * @param array|string|callable $handler
     * @return Route
     */
    public function delete(string $path, array|string|callable $handler): Route
    {
        return $this->add(self::DELETE, $path, $handler);
    }

    /**
     * @param string $path
     * @param array|string|callable $handler
     * @return Route
     */
    public function patch(string $path, array|string|callable $handler): Route
    {
        return $this->add(self::PATCH, $path, $handler);
    }

    /**
     * @param string $prefix
     * @return $this
     */
    public function prefix(string $prefix): static
    {
        $prefix = trim($prefix, "/");
        $prefix = "/{$prefix}";
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * @param callable $routes
     * @return void
     */
    public function group(callable $routes): void
    {
        $this->groupPrefixes[] = $this->prefix;
        $this->prefix = '';
        $routes($this);
        array_pop($this->groupPrefixes);
    }

    /**
     * @param string $method
     * @param string $path
     * @param array|string|callable $handler
     * @return Route
     */
    private function add(string $method, string $path, array|string|callable $handler): Route
    {
        return $this->routes[] = new Route($method, $this->prefixedPath($path), $handler);
    }

    /**
     * @param int $code
     * @param callable $handler
     * @return void
     */
    public function errorHandler(int $code, callable $handler): void
    {
        $this->errorHandlers[$code] = $handler;
    }

    /**
     * @throws Throwable
     */
    public function dispatch(Request $request)
    {
        $requestMethod = $request->method();
        $requestPath = $request->uri();

        $matching = $this->match($requestMethod, $requestPath);

        if ($matching) {

            $this->current = $matching;

            try {
                return $matching->dispatch();
            } catch (Throwable $e) {
                return app(ExceptionHandler::class)->render($e);
            }
        }

        if (in_array($requestPath, $this->paths())) {
            return $this->dispatchNotAllowed();
        }

        return $this->dispatchNotFound();
    }

    /**
     * @param string $method
     * @param string $path
     * @return Route|null
     */
    private function match(string $method, string $path): ?Route
    {
        foreach ($this->routes as $route) {
            if ($route->matches($method, $path)) {
                return $route;
            }
        }

        return null;
    }


    /**
     * @return array
     */
    private function paths(): array
    {
        $paths = [];
        foreach ($this->routes as $route) {
            $paths[] = $route->path();
        }
        return $paths;
    }

    /**
     * @param string $path
     * @return string
     */
    private function prefixedPath(string $path): string
    {
        $singlePrefix = $this->prefix;
        $this->prefix = '';

        $fullPrefix = implode('', $this->groupPrefixes);
        return $fullPrefix . $singlePrefix . $path;
    }

    /**
     * @return Route|null
     */
    public function current(): ?Route
    {
        $requestMethod = app(Request::class)->method();
        $requestPath = app(Request::class)->uri();
        $matching = $this->match($requestMethod, $requestPath);

        if ($matching)
            $this->current = $matching;

        return $this->current ?? null;
    }

    /**
     * @throws Exception
     */
    public function route(string $name, array $parameters = []): string
    {
        foreach ($this->routes as $route) {
            if ($route->name() === $name) {
                $finds = [];
                $replaces = [];
                foreach ($parameters as $key => $value) {
                    // one set for required parameters
                    $finds[] = "{{$key}}";
                    $replaces[] = $value;
                    // ...and another for optional parameters
                    $finds[] = "{{$key}?}";
                    $replaces[] = $value;
                }
                $path = $route->path();
                $path = str_replace($finds, $replaces, $path);
                // remove any optional parameters not provided
                $path = preg_replace('#{[^}]+}#', '', $path);
                // we should think about warning if a required
                // parameter hasn't been provided...

                return $path;
            }
        }
        throw new Exception('no route with that name');
    }


    /**
     * @return mixed
     */
    public function dispatchNotAllowed(): mixed
    {
        $this->errorHandler(400, fn() => 'not allowed');
        return $this->errorHandlers[400]();
    }

    /**
     * @return mixed
     */
    public function dispatchNotFound(): mixed
    {
        $this->errorHandler(404, fn() => 'not found');
        return $this->errorHandlers[404]();
    }

    /**
     * @return mixed
     */
    public function dispatchError(): mixed
    {
        $this->errorHandler(500, fn() => 'server error');
        return $this->errorHandlers[500]();
    }

}