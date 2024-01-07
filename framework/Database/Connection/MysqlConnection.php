<?php

namespace Framework\Database\Connection;

use Framework\Database\Builder\MySqlQueryBuilder;
use Framework\Database\Exceptions\ConnectionException;
use Framework\Database\Exceptions\QueryException;
use Framework\Database\Schema\MySqlSchemaBuilder;
use Framework\Database\Schema\SchemaBuilder;

class MysqlConnection extends Connection
{
    private \PDO $pdo;
    private array $config;

    /**
     * @throws ConnectionException
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        [
            'host' => $host,
            'port' => $port,
            'database' => $database,
            'username' => $username,
            'password' => $password,
        ] = $this->config;

        if (empty($host) || empty($database) || empty($username)) {
            throw new ConnectionException('Invalid database configurations');
        }

        $this->pdo = new \PDO("mysql:host={$host};port={$port};dbname={$database}", $username, $password);
    }

    public function pdo(): \PDO
    {
        return $this->pdo;
    }

    public function query(): MySqlQueryBuilder
    {
        return new MySqlQueryBuilder($this);
    }

    public function schemaBuilder(): SchemaBuilder
    {
        return new MySqlSchemaBuilder($this);
    }

    /**
     * @return bool
     */
    public function disconnect(): bool
    {
        unset($this->pdo);
        return true;
    }

    /**
     * @throws ConnectionException
     */
    public function __call(string $name, array $arguments)
    {
        $queryBuilder = $this->query();
        return $queryBuilder->{$name}(...$arguments);
    }

    /**
     * @throws QueryException
     */
    public function getTables(): array
    {
        $queryBuilder = $this->query();
        $database = $this->config['database'];

        return $queryBuilder
            ->from('information_schema.tables')
            ->select('TABLE_NAME as name')
            ->where('table_schema', '=', $database)
            ->get();
    }

    /**
     * @throws QueryException
     */
    public function hasTable(string $name): bool
    {
        $queryBuilder = $this->query();
        $database = $this->config['database'];

        $result = $queryBuilder
            ->from('information_schema.tables')
            ->select('TABLE_NAME as name')
            ->where('table_schema', '=', $database)
            ->where('TABLE_NAME', '=', $name)
            ->first();

        return (bool) count($result);
    }

    public function dropTables(): int
    {
        $database = $this->config['database'];

        $this->pdo()->query('SET FOREIGN_KEY_CHECKS = 0');

        $query = "SELECT concat('DROP TABLE IF EXISTS ', table_name, ';')
          FROM information_schema.tables
          WHERE table_schema = '$database'";

        foreach($this->pdo()->query($query) as $row) {
            $this->pdo()->exec($row[0]);
        }

        $this->pdo()->query('SET FOREIGN_KEY_CHECKS = 1');
        return 1;
    }
}