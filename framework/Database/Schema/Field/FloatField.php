<?php

namespace Framework\Database\Schema\Field;

class FloatField extends Field
{
    protected float $default;
    public function default(float $default): static
    {
        $this->default = $default;
        return $this;
    }

    public function format(): string
    {
        $template = "`{$this->name}` float";
        if ($this->nullable) {
            $template .= " DEFAULT NULL";
        }
        if (isset($this->default)) {
            $template .= " DEFAULT '{$this->default}'";
        }
        return $template;
    }
}