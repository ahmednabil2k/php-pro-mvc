<?php

namespace Framework\Validation\Rule;
class EmailRule implements Rule
{

    public function passes(array $data, string $field, array $params = []): bool
    {
        if (empty($data[$field]))
            return true;
        return str_contains($data[$field], '@');
    }

    public function message(array $data, string $field, array $params): string|null
    {
        return "{$field} is not a valid email";
    }
}