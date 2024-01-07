<?php

namespace Framework\Config;
class Config
{

    private array $loaded = [];

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     * @throws \Exception
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $segments = explode('.', $key);
        $file = array_shift($segments);
        $separator = DIRECTORY_SEPARATOR;
        $filePath = configPath() . "{$separator}{$file}.php";

        if (!file_exists($filePath))
            throw new \Exception("Unknown config file: {$file} on config folder");

        if (!$this->loaded($file)){
            $this->loadFile($file, $filePath);
        }

        $value = $this->parseValue($this->loaded[$file], $segments);
        return $value ?? $default;
    }

    private function loaded(string $file): bool
    {
        return isset($this->loaded[$file]);
    }

    private function loadFile(string $file, string $filePath): void
    {
        $this->loaded[$file] = (array) require_once $filePath;
    }

    private function parseValue(array $config, array $segments)
    {
        $current = $config;
        foreach ($segments as $segment) {

            if (!isset($current[$segment]))
                return null;

            $current = $current[$segment];
        }
        return $current;
    }

}