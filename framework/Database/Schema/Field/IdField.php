<?php

namespace Framework\Database\Schema\Field;

use Framework\Database\Exceptions\QueryException;

class IdField extends Field
{
    public bool $primary;
    /**
     * @throws QueryException
     */
    public function default(int|string $default): static
    {
        throw new QueryException('ID fields cannot have a default value');
    }

    public function format(): string
    {
        $auto_increment = isset($this->primary) && $this->primary ? 'AUTO_INCREMENT' : '';
        return "`{$this->name}` int(11) unsigned NOT NULL {$auto_increment}";
    }

    public function primary(): static
    {
        $this->primary = true;
        return $this;
    }
}