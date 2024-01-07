<?php

namespace Framework\Database\Schema\Field;

class StringField extends Field
{
    protected string $default;
    protected int $length = 255;

    public function __construct(string $name, int $length)
    {
       parent::__construct($name);
       $this->length = $length;
    }

    public function default(string $default): static
    {
        $this->default = $default;
        return $this;
    }

    public function format(): string
    {
        $length = $this->length;
        $template = "`{$this->name}` varchar({$length})";

        if ($this->nullable) {
            $template .= " DEFAULT NULL";
        }
        if (isset($this->default)) {
            $template .= " DEFAULT '{$this->default}'";
        }
        return $template;
    }
}