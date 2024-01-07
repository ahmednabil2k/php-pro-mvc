<?php

namespace Framework\Database;

use Framework\Database\Connection\MysqlConnection;
use Framework\Database\Exceptions\ConnectionException;
use Framework\Database\Relation\Relationship;

abstract class Model
{
    protected string $table;
    protected string $primaryKey = 'id';

    protected string $connection = '';

    protected array $attributes;

    protected array $dirty = [];


    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * @throws ConnectionException
     */
    protected function connection(): Connection\Connection
    {
        return $this->connection ? app("db.{$this->connection}") : app('db.default');
    }

    protected function table(): string
    {
        return $this->table;
    }

    public static function attributes(array $attributes = []): static
    {
        return new static($attributes);
    }

    /**
     * @throws ConnectionException
     */
    public static function query(): mixed
    {
        $model = new static();
        $query = $model->connection()->query();
        return (new ModelCollector($query, static::class))->from($model->table());
    }

    /**
     * @throws ConnectionException
     */
    public static function __callStatic(string $method, array $parameters = []): mixed
    {
        return static::query()->$method(...$parameters);
    }

    public function __get(string $property)
    {
        $getter = "get" . ucfirst($property) . "Attribute";
        if (method_exists($this, $getter)) {
            $this->attributes[$property] = $this->$getter($this->attributes[$property] ?? null);
            return $this->attributes[$property];
        }

        if (method_exists($this, $property)) {
            $relation = $this->$property();
            $method = $relation->method();
            return $relation->$method();
        }

        if (isset($this->attributes[$property])) {
            return $this->attributes[$property];
        }

        return null;
    }

    public function __set(string $property, $value): void
    {
        $this->dirty[] = $property;

        $setter = 'set' . ucfirst($property) . 'Attribute';
        if (method_exists($this, $setter)) {
            $this->attributes[$property] = $this->$setter($value);
            return;
        }

        $this->attributes[$property] = $value;
    }

    public function save()
    {
        $values = [];
        foreach ($this->dirty as $dirty) {
            $values[$dirty] = $this->attributes[$dirty];
        }

        $query = static::query();

        if (isset($this->attributes[$this->primaryKey])) {

            $query
                ->where($this->primaryKey, '=', $this->attributes[$this->primaryKey])
                ->update($values);

            return $this;
        }

        $query->insert($values);

        $this->attributes[$this->primaryKey] = $query->getLastInsertId();
        $this->dirty = [];
        return $this;
    }

    /**
     * @throws ConnectionException
     */
    public static function find($id)
    {
        $model = new static();
        return static::query()->where($model->primaryKey, '=', $id)->first();
    }

    /**
     * @throws ConnectionException
     */
    public function delete(): static
    {
        if (isset($this->attributes[$this->primaryKey])) {
            static::query()
                ->where($this->primaryKey, '=', $this->attributes[$this->primaryKey])
                ->delete();
        }
        return $this;
    }

    public function hasOne(string $class, string $foreignKey, string $primaryKey = 'id'): Relationship
    {
        $query = $class::query()
            ->where(
                $foreignKey,
                '=',
                $this->attributes[$this->primaryKey ?? $primaryKey]
            );
        return new Relationship($query, 'first');
    }

    public function hasMany(string $class, string $foreignKey, string $primaryKey = 'id'): Relationship
    {
        $query = $class::query()
            ->where(
                $foreignKey,
                '=',
                $this->attributes[$this->primaryKey ?? $primaryKey]
            );
        return new Relationship($query, 'get');
    }

    public function belongsTo(string $class, string $foreignKey, string $primaryKey = 'id'): Relationship
    {
        $query = $class::query()
            ->where(
                $this->primaryKey ?? $primaryKey,
                '=',
                $this->attributes[$foreignKey],
            );
        return new Relationship($query, 'first');
    }

}