<?php

namespace Framework\Queue;
class QueueManager
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
        $config = config("queue.{$name}");
        if (!$config)
            throw new \Exception("No config provided for {$name} queue driver!");

        $config['_connection'] = $name;
        return $this->drivers[$config['driver']]($config);
    }
}