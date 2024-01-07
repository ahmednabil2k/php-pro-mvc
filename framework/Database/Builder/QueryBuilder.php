<?php

namespace Framework\Database\Builder;

use Framework\Database\Connection\Connection;
use Framework\Database\Exceptions\QueryException;

abstract class QueryBuilder
{
    protected string $type;
    protected string|array $columns;
    protected array $values;
    protected string $table;
    protected array $wheres = [];
    protected int $limit;
    protected int $offset;
    abstract public function connection(): Connection;

    /**
     * Add select clause to the query
     */

    /**
     * Fetch all rows matching the current query
     * @throws QueryException
     */
    public function all(string $columns = '*'): array
    {
        $this->select($columns);

        $statement = $this->prepare();
        $statement->execute($this->getWhereValues());

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @throws QueryException
     */
    public function get(string $columns = '*'): array
    {
        $this->select($columns);

        $statement = $this->prepare();
        $statement->execute($this->getWhereValues());

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @throws QueryException
     */
    public function prepare(): \PDOStatement
    {
        $query = '';
        if ($this->type === 'select') {
            $query = $this->compileSelect($query);
            $query = $this->compileWheres($query);
            $query = $this->compileLimit($query);
        }

        if ($this->type === 'insert') {
            $query = $this->compileInsert($query);
        }

        if ($this->type === 'update') {
            $query = $this->compileUpdate($query);
            $query = $this->compileWheres($query);
        }

        if ($this->type === 'delete') {
            $query = $this->compileDelete($query);
            $query = $this->compileWheres($query);
        }

        if (empty($query)) {
            throw new QueryException('Unrecognised query type');
        }

        return $this->connection()->pdo()->prepare($query);
    }
    protected function compileSelect(string $query): string
    {
        $query .= " SELECT {$this->columns} FROM {$this->table}";
        return $query;
    }

    protected function compileWheres(string $query): string
    {
        if (count($this->wheres) === 0) {
            return $query;
        }
        $query .= ' WHERE';
        foreach ($this->wheres as $i => $where) {
            if ($i > 0) {
                $query .= ' AND';
            }
            [$column, $comparator, $value] = $where;
            $query .= " {$column} {$comparator} :{$column}";
        }
        return $query;
    }

    protected function compileInsert(string $query): string
    {
        $joinedColumns = join(', ', $this->columns);
        $joinedPlaceholders = join(', ', array_map(fn($column) => ":{$column}", $this->columns));
        $query .= " INSERT INTO {$this->table} ({$joinedColumns}) VALUES ({$joinedPlaceholders})";
        return $query;
    }

    protected function compileUpdate(string $query): string
    {
        $joinedColumns = '';
        foreach ($this->columns as $i => $column) {
            if ($i > 0) {
                $joinedColumns .= ', ';
            }
            $joinedColumns = " {$column} = :{$column}";
        }
        $query .= " UPDATE {$this->table} SET {$joinedColumns}";
        return $query;
    }

    /**
     * Add limit and offset clauses to the query
     */
    protected function compileLimit(string $query): string
    {
        if (isset($this->limit)) {
            $query .= " LIMIT {$this->limit}";
        }
        if (isset($this->offset)) {
            $query .= " OFFSET {$this->offset}";
        }
        return $query;
    }

    /**
     * Fetch the first row matching the current query
     * @throws QueryException
     */
    public function first(): array
    {
        $this->select();

        $statement = $this->take(1)->prepare();

        $statement->execute($this->getWhereValues());

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }


    /**
     * Limit a set of query results so that it's possible
     * to fetch a single or limited batch of rows
     */
    public function take(int $limit, int $offset = 0): static
    {
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }
    /**
     * Indicate which table the query is targeting
     */
    public function from(string $table): static
    {
        $this->table = $table;
        return $this;
    }
    /**
     * Indicate the query type is a "select" and remember
     * which fields should be returned by the query
     */
    public function select(string $columns = '*'): static
    {
        $this->type = 'select';
        $this->columns = $columns;
        return $this;
    }

    /**
     * @throws QueryException
     */
    public function insert(array $data): int
    {
        $this->type = 'insert';
        $this->columns = array_keys($data);
        $this->values = array_values($data);
        $statement = $this->prepare();
        return $statement->execute($data);
    }

    /**
     * @throws QueryException
     */
    public function update(array $data): int
    {
        $this->type = 'update';
        $this->columns = array_keys($data);
        $this->values = array_values($data);
        $statement = $this->prepare();
        return $statement->execute($this->getWhereValues() + $data);
    }

    /**
     * @throws QueryException
     */
    protected function compileDelete(string $query): string
    {
        $query .= " DELETE FROM {$this->table}";
        return $query;
    }

    /**
     * @throws QueryException
     */
    public function delete(): int
    {
        $this->type = 'delete';
        $statement = $this->prepare();
        return $statement->execute($this->getWhereValues());
    }


    /**
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @return $this
     */
    public function where(string $column, string $operator, mixed $value): static
    {
        $this->wheres[] = [$column, $operator, $value];
        return $this;
    }

    protected function getWhereValues(): array
    {
        $values = [];
        if (count($this->wheres) === 0) {
            return $values;
        }

        foreach ($this->wheres as $where) {
            $values[$where[0]] = $where[2];
        }
        return $values;
    }

    public function getLastInsertId(): bool|string
    {
        return $this->connection()->pdo()->lastInsertId();
    }

}