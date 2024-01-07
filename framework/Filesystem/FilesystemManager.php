<?php

namespace Framework\Filesystem;
class FilesystemManager
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
        $config = config("filesystem.{$name}");
        if (!$config)
            throw new \Exception("No config provided for {$name} filesystem driver!");

        return $this->drivers[$config['driver']]($config);
    }
}