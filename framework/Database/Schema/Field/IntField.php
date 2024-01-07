<?php

namespace Framework\Database\Schema\Field;

class IntField extends Field
{
    protected int $default;
    public function default(int $default): static
    {
        $this->default = $default;
        return $this;
    }

    public function format(): string
    {
        $template = "`{$this->name}` int(11)";
        if ($this->nullable) {
            $template .= " DEFAULT NULL";
        }
        if (isset($this->default)) {
            $template .= " DEFAULT '{$this->default}'";
        }
        return $template;
    }
}