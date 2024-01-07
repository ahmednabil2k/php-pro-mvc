<?php

namespace Framework\Cache;
class CacheManager
{

    protected array $drivers = [];

    /**
     * @param string $name
     * @param \Closure $closure
     * @return $this
     */
    public function addDriver(string $name, \Closure $closure): static
    {
        $this->drivers[$name] = $closure;
        return $this;
    }

    /**
     * @throws \Exception
     */
    public function connect(string $name)
    {
        $config = config("cache.{$name}");
        if (!$config)
            throw new \Exception("No config provided for {$name} cache!");

        return $this->drivers[$config['driver']]($config);
    }
}