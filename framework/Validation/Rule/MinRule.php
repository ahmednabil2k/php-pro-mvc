<?php

namespace Framework\Validation\Rule;
class MinRule implements Rule
{

    /**
     * @throws \Exception
     */
    public function passes(array $data, string $field, array $params = []): bool
    {
        if (empty($data[$field]))
            return true;

        if (empty($params))
            throw new \Exception('Min rule should provide min length like min:10');

        return strlen($data[$field]) > $params[0];
    }

    public function message(array $data, string $field, array $params): string|null
    {
        return "{$field} must not be less than {$params[0]} chars";
    }
}