<?php

namespace Framework\Database\Schema\Field;

abstract class Field
{
    public string $name;

    public bool $nullable = false;

    public bool $alter = false;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function nullable(): static
    {
        $this->nullable = true;
        return $this;
    }

    public function change(): static
    {
        $this->alter = true;
        return $this;
    }

    abstract public function format():string;
}