<?php

namespace Framework\Database\Schema;

use Framework\Database\Connection\Connection;
use Framework\Database\Schema\Field\BoolField;
use Framework\Database\Schema\Field\DateTimeField;
use Framework\Database\Schema\Field\FloatField;
use Framework\Database\Schema\Field\IdField;
use Framework\Database\Schema\Field\IntField;
use Framework\Database\Schema\Field\StringField;
use Framework\Database\Schema\Field\TextField;

abstract class SchemaBuilder
{
    protected string $type;

    protected string $table;

    protected array $fields = [];

    abstract public function connection(): Connection;

    abstract public function createTable(): void;

    abstract public function alterTable(): void;

    public function execute(): void
    {
        if ($this->type === 'create') {
            $this->createTable();
        }

        if ($this->type === 'alter') {
            $this->alterTable();
        }
    }

    public function setTable(string $table): static
    {
        $this->table = $table;
        return $this;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function bool(string $name): BoolField
    {
        return $this->fields[] = new BoolField($name);
    }
    public function dateTime(string $name): DateTimeField
    {
        return $this->fields[] = new DateTimeField($name);
    }
    public function float(string $name): FloatField
    {
        return $this->fields[] = new FloatField($name);
    }
    public function id(string $name): IdField
    {
        return $this->fields[] = new IdField($name);
    }
    public function int(string $name): IntField
    {
        return $this->fields[] = new IntField($name);
    }
    public function string(string $name, int $length = 255): StringField
    {
        return $this->fields[] = new StringField($name, $length);
    }
    public function text(string $name): TextField
    {
        return $this->fields[] = new TextField($name);
    }

}