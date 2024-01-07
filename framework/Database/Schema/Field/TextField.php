<?php

namespace Framework\Database\Schema\Field;

class TextField extends Field
{
    protected string $default;
    public function default(string $default): static
    {
        $this->default = $default;
        return $this;
    }

    public function format(): string
    {
        $template = "`{$this->name}` text";
        if ($this->nullable) {
            $template .= " DEFAULT NULL";
        }
        if (isset($this->default)) {
            $default = $this->default;
            $template .= " DEFAULT {$default}";
        }
        return $template;
    }
}