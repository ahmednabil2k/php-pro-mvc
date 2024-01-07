<?php

namespace Framework\Database\Schema\Field;

class DateTimeField extends Field
{
    protected string $default;
    public const CURRENT_TIMESTAMP = 'CURRENT_TIMESTAMP';

    public function default(string $default): static
    {
        $this->default = $default;
        return $this;
    }
    public function currentTimestamps(): static
    {
        $this->default = static::CURRENT_TIMESTAMP;
        return $this;
    }

    public function format(): string
    {
        $template = "`{$this->name}` datetime";
        if ($this->nullable) {
            $template .= " DEFAULT NULL";
        }

        if (isset($this->default)) {

            if ($this->default == static::CURRENT_TIMESTAMP) {
                $template .= " DEFAULT CURRENT_TIMESTAMP";
            } else {
                $template .= " DEFAULT '{$this->default}'";
            }
        }
        return $template;
    }
}