<?php

namespace Framework\Database\Builder;

use Framework\Database\Connection\Connection;
use Framework\Database\Connection\MysqlConnection;

class MySqlQueryBuilder extends QueryBuilder
{
    private MysqlConnection $connection;

    public function __construct(MysqlConnection $connection)
    {
        $this->connection = $connection;
    }

    public function connection(): Connection
    {
        return $this->connection;
    }
}