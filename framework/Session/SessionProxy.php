<?php

namespace Framework\Session;

class SessionProxy implements SessionDriver
{
    protected SessionDriver $driver;
    public function __construct(SessionDriver $driver)
    {
        $this->driver = $driver;
    }

    public function has(string $key): bool
    {
        return $this->driver->has($this->addPrefix($key));
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->driver->get($this->addPrefix($key), $default);
    }

    public function put(string $key, mixed $value, int $seconds = null): SessionDriver
    {
        return $this->driver->put($this->addPrefix($key), $value, $seconds);
    }

    public function forget(string $key): SessionDriver
    {
        return $this->driver->forget($this->addPrefix($key));
    }

    public function flush(): SessionDriver
    {
        return $this->driver->flush();
    }

    public function driver(): SessionDriver
    {
        return $this->driver;
    }

    private function addPrefix(string $key): string
    {
        return config('session.prefix') . $key;
    }
}