<?php

namespace Framework\Database;

use Framework\Database\Connection\Connection;
use Framework\Database\Exceptions\ConnectionException;

class Factory
{
    protected array $connectors;

    /**
     * @param string $name
     * @param \Closure $closure
     * @return $this
     */
    public function addConnector(string $name, \Closure $closure): static
    {
        $this->connectors[$name] = $closure;
        return $this;
    }

    /**
     * @throws ConnectionException
     */
    public function connectWithConfig(array $config): Connection
    {
        if (!isset($config['driver']))
            throw new ConnectionException('No driver specified!');

        if (!isset($this->connectors[$config['driver']]))
            throw new ConnectionException("unknown database driver: {$config['driver']}!");

        return $this->connectors[$config['driver']]($config);
    }

    /**
     * @throws ConnectionException
     */
    public function connect(string $name): Connection
    {
        $config = config('database.connections');

        if (!isset($config[$name]))
            throw new ConnectionException("unknown database driver: {$name}!");

        return $this->connectWithConfig($config[$name]);
    }



}