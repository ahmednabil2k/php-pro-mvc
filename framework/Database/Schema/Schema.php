<?php

namespace Framework\Database\Schema;

use Framework\Database\Connection\Connection;
use Framework\Database\Exceptions\ConnectionException;
use Framework\Database\Factory;

class Schema
{
    protected Connection $connection;

    private function create(string $table, \Closure $closure): void
    {
        $this->runSchemaQuery($table, 'create', $closure);
    }

    private function alter(string $table, \Closure $closure): void
    {
        $this->runSchemaQuery($table, 'alter', $closure);
    }

    private function runSchemaQuery(string $table, string $type, \Closure $closure): void
    {
        $connection = $this->connection ?? app('db.default');

        $schemaBuilder = $connection->schemaBuilder();
        $schemaBuilder = $schemaBuilder->setTable($table)->setType($type);

        call_user_func($closure, $schemaBuilder);
        $schemaBuilder->execute();
    }

    /**
     * @throws ConnectionException
     */
    private function connection(string $name): static
    {
        $this->connection = app("db.{$name}");
        return $this;
    }

    public static function __callStatic(string $name, array $arguments)
    {
        return (new static())->{$name}(...$arguments);
    }
}