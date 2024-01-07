<?php

namespace Framework\Database\Schema;

use Framework\Database\Connection\Connection;
use Framework\Database\Connection\MysqlConnection;
use Framework\Database\Schema\Field\Field;
class MySqlSchemaBuilder extends SchemaBuilder
{
    protected MysqlConnection $connection;

    public function __construct(MysqlConnection $connection)
    {
        $this->connection = $connection;
    }

    public function connection(): Connection
    {
        return $this->connection;
    }

    public function createTable(): void
    {
        $fields = array_map(fn($field) => $this->stringForField($field), $this->fields);

        $fields = $this->joinFields($fields);
        $primary = array_filter($this->fields, fn($field) => isset($field->primary));

        $fields .= isset($primary[0]) ? " , PRIMARY KEY (`{$primary[0]->name}`)" : '';

        $query = " CREATE TABLE `{$this->table}` (
                  {$fields}
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARACTER SET=utf8mb4;";

        $statement = $this->connection()->pdo()->prepare($query);
        $statement->execute();
    }

    public function alterTable(): void
    {
        $fields = array_map(fn($field) => $this->stringForField($field), $this->fields);
        $fields = $this->joinFields($fields);

        $primary = array_filter($this->fields, fn($field) => isset($field->primary));

        $fields .= isset($primary[0]) ? " , PRIMARY KEY (`{$primary[0]->name}`)" : '';

        $query = " ALTER TABLE `{$this->table}` {$fields}";

        $statement = $this->connection()->pdo()->prepare($query);
        $statement->execute();
    }

    private function stringForField(Field $field): string
    {
        if ($this->type === 'create') return $field->format();

        $prefix = '';

        if ($this->type === 'alter') {
            $prefix = 'ADD';
        }

        if ($field->alter) {
            $prefix = 'MODIFY';
        }

        return "{$prefix} " . $field->format();
    }

    private function joinFields(array $fields): string
    {
        $columns = '';
        $length = count($fields);

        foreach ($fields as $index => $field) {
            $columns .= $length > ($index + 1) ? $field . ',' . PHP_EOL : $field;
        }
        return $columns;
    }


}