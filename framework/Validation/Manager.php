<?php

namespace Framework\Validation;
use Framework\Validation\Rule\Rule;

class Manager
{
    /**
     * @var array
     */
    protected array $rules = [];

    /**
     * @var array
     */
    protected array $errors = [];

    /**
     * @param string $name
     * @param Rule $rule
     * @return $this
     */
    public function addRule(string $name, Rule $rule): static
    {
        $this->rules[$name] = $rule;
        return $this;
    }

    /**
     * @param array $data
     * @param array $rules
     * @return array
     */
    public function validate(array $data, array $rules): array
    {

        foreach ($rules as $field => $fieldRules) {
            foreach ($fieldRules as $rule) {
                $name = $rule;
                $params = [];

                if (str_contains($rule, ':')) {
                    [$name, $params] = explode(':', $rule);
                    $params = explode(',', $params);
                }

                $processor = $this->rules[$name];

                if (!$processor->passes($data, $field, $params)) {
                    if (!isset($this->errors[$field])) {
                        $this->errors[$field] = [];
                    }
                    $this->errors[$field][] = $processor->message($data, $field, $params);
                }
            }
        }

        if (count($this->errors)) {
            $exception = new ValidationException('', 422);
            $exception->setErrors($this->errors);
            throw $exception;
        }
        return array_intersect_key($data, $rules);

    }

}