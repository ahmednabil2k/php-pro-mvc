<?php

namespace Framework\Validation\Rule;
class RequiredRule implements Rule
{

    public function passes(array $data, string $field, array $params = []): bool
    {
        return !empty($data[$field]);
    }

    public function message(array $data, string $field, array $params): string|null
    {
        return "{$field} is required";
    }
}