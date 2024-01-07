<?php

namespace Framework\Cache;
class CacheProxy implements CacheDriver
{
    protected CacheDriver $driver;
    public function __construct(CacheDriver $driver)
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

    public function put(string $key, mixed $value, int $seconds = null): CacheDriver
    {
        return $this->driver->put($this->addPrefix($key), $value, $seconds);
    }

    public function forget(string $key): CacheDriver
    {
        return $this->driver->forget($this->addPrefix($key));
    }

    public function flush(): CacheDriver
    {
        return $this->driver->flush();
    }

    public function driver(): CacheDriver
    {
        return $this->driver;
    }

    private function addPrefix(string $key): string
    {
        return config('cache.prefix') . $key;
    }
}