<?php

namespace Framework\Database\Connection;

use Framework\Database\Builder\QueryBuilder;
use Framework\Database\Schema\SchemaBuilder;

abstract class Connection
{
    abstract public function pdo(): \PDO;
    abstract public function query(): QueryBuilder;
    abstract public function schemaBuilder(): SchemaBuilder;
    abstract public function disconnect(): bool;

    abstract public function getTables(): array;
    abstract public function hasTable(string $name): bool;
    abstract public function dropTables(): int;


}