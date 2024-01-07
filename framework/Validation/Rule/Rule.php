<?php

namespace Framework\Validation\Rule;
interface Rule
{
    /**
     * @param array $data
     * @param string $field
     * @param array $params
     * @return bool
     */
    public function passes(array $data, string $field, array $params = []): bool;

    /**
     * @param array $data
     * @param string $field
     * @param array $params
     * @return string|null
     */
    public function message(array $data, string $field, array $params): string|null;
}