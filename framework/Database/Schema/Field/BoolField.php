<?php

namespace Framework\Database\Schema\Field;

class BoolField extends Field
{
    protected bool $default;
    public function default(bool $default): static
    {
        $this->default = $default;
        return $this;
    }
    public function format(): string
    {
        $template = "`{$this->name}` tinyint(4)";
        if ($this->nullable) {
            $template .= " DEFAULT NULL";
        }
        if (isset($this->default)) {
            $default = (int) $this->default;
            $template .= " DEFAULT {$default}";
        }
        return $template;
    }
}