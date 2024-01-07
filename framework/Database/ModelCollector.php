<?php

namespace Framework\Database;

use Framework\Database\Builder\QueryBuilder;
use Framework\Database\Exceptions\QueryException;

class ModelCollector
{

    private QueryBuilder $builder;

    private string $class;

    public function __construct(QueryBuilder $builder, string $class)
    {
        $this->builder = $builder;
        $this->class = $class;
    }

    /**
     * @throws QueryException
     */
    public function first()
    {
        $row = $this->builder->first();
        if ($row) {
            $row = new $this->class($row[0]);
        }
        return $row;
    }

    public function all()
    {
        $rows = $this->builder->all();
        if ($rows) {
            foreach ($rows as $index => $row) {
                $rows[$index] = new $this->class($row);
            }
        }
        return $rows;
    }

    public function get(string $columns = '*')
    {
        $rows = $this->builder->get($columns);
        if ($rows) {
            foreach ($rows as $index => $row) {
                $rows[$index] = new $this->class($row);
            }
        }
        return $rows;
    }

    public function __call(string $name, array $arguments)
    {
        $result = $this->builder->$name(...$arguments);
        if ($result instanceof QueryBuilder) {
            $this->builder = $result;
            return $this;
        }

        return $result;
    }
}