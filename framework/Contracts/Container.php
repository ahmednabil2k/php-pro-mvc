<?php

namespace Framework\Contracts;

use Exception;
use InvalidArgumentException;
use LogicException;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionNamedType;

class Container
{
    protected array $bindings = [];
    protected array $resolved = [];
    protected array $instances = [];
    protected array $aliases = [];

    public function bind(string $abstract, \Closure|string $concrete): static
    {
        $this->bindings[$abstract] = $concrete;
        $this->resolved[$abstract] = null;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function resolve(string $abstract)
    {
        if ($this->aliased($abstract)) {
            $abstract = $this->aliases[$abstract];
        }

        if ($this->isInstance($abstract)) {
            return $this->instances[$abstract];
        }

        if (!$this->resolved($abstract)) {

            if ($this->bound($abstract)) {

                if (is_callable($this->bindings[$abstract]))
                    $this->resolved[$abstract] = call_user_func($this->bindings[$abstract], $this);
                else
                    $this->resolved[$abstract] = $this->resolveInstanceUsingReflection($abstract);

            } else {
                $this->resolved[$abstract] = $this->resolveInstanceUsingReflection($abstract);
            }
        }

        return $this->resolved[$abstract];
    }

    public function instance(string $abstract, mixed $concrete): static
    {
        $this->alias($abstract, get_class($concrete));
        $this->instances[$abstract] = $concrete;
        return $this;
    }

    public function alias(string $abstract, $alias): static
    {
        if ($alias === $abstract) {
            throw new LogicException("[{$abstract}] is aliased to itself.");
        }

        $this->aliases[$alias] = $abstract;
        return $this;
    }

    public function bound(string $alias): bool
    {
        return isset($this->bindings[$alias]);
    }

    public function resolved(string $alias): bool
    {
        return isset($this->resolved[$alias]);
    }

    public function aliased(string $key): bool
    {
        return isset($this->aliases[$key]);
    }

    public function isInstance(string $key): bool
    {
        return isset($this->instances[$key]);
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function call(array|callable $callable, array $parameters = [])
    {
        if (is_array($callable) && !is_object($callable[0])) {
            $instance = $this->resolveInstanceUsingReflection($callable[0]);
            $callable[0] = $instance;
        }

        $reflector = $this->getReflector($callable);
        $dependencies = [];

        foreach ($reflector->getParameters() as $parameter) {
            $name = $parameter->getName();
            $type = $parameter->getType();

            if (isset($parameters[$name])) {
                $dependencies[$name] = $parameters[$name];
                continue;
            }

            if ($parameter->isDefaultValueAvailable()) {
                $dependencies[$name] = $parameter->getDefaultValue();
                continue;
            }

            if ($type instanceof ReflectionNamedType) {
                $dependencies[$name] = $this->resolve($type);
                continue;
            }

            throw new InvalidArgumentException("{$name} cannot be resolved");
        }

        return call_user_func($callable, ...array_values($dependencies));
    }

    /**
     * @throws Exception
     */
    public function make(string $abstract)
    {
        return $this->resolve($abstract);
    }

    /**
     * @throws ReflectionException
     */
    private function getReflector(array|callable $callable): ReflectionMethod|ReflectionFunction
    {
        if (is_array($callable)) {
            return new ReflectionMethod($callable[0], $callable[1]);
        }
        return new ReflectionFunction($callable);
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    private function resolveInstanceUsingReflection(string $abstract)
    {
        $class = $this->bound($abstract) ? $this->bindings[$abstract] : $abstract;

        $reflector = new \ReflectionClass($class);

        $constructor = $reflector->getConstructor();

        if (!$constructor)
            return new $class();

        $dependencies = [];
        foreach ($constructor->getParameters() as $parameter) {

            $name = $parameter->getName();
            $type = $parameter->getType();

            if ($parameter->isDefaultValueAvailable()) {
                $dependencies[$name] = $parameter->getDefaultValue();
                continue;
            }

            if ($type instanceof ReflectionNamedType) {
                $dependencies[$name] = $this->resolve($type);
                continue;
            }

            throw new InvalidArgumentException("{$name} cannot be resolved");
        }

        return new $class(...array_values($dependencies));
    }

}