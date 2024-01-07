<?php

namespace Framework\Cache;
class FileCacheDriver implements CacheDriver
{
    protected array $config = [];
    protected array $cached = [];
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function has(string $key): bool
    {
        $data = $this->cached[$key] = $this->read($key);
        return isset($data['expires']) && $data['expires'] > time();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->has($key) ? $this->cached[$key]['value'] : $default;
    }

    public function put(string $key, mixed $value, int $seconds = null): static
    {
        if (!is_int($seconds)) {
            $seconds = (int) $this->config['seconds'];
        }

        $data = $this->cached[$key] = [
            'value' => $value,
            'expires' => time() + $seconds,
        ];

        return $this->write($key, $data);
    }

    public function forget(string $key): static
    {
        unset($this->cached[$key]);

        $path = $this->path($key);
        if (is_file($path))
            unlink($path);

        return $this;
    }

    public function flush(): static
    {
        $this->cached = [];
        $base = $this->base();
        $separator = DIRECTORY_SEPARATOR;
        $files = glob("{$base}{$separator}*.json");

        foreach ($files as $file){
            if (is_file($file)) {
                unlink($file);
            }
        }

        return $this;
    }

    private function read(string $key)
    {
        $path = $this->path($key);
        if (!is_file($path))
            return null;

        return json_decode(file_get_contents($path), true);
    }

    private function write(string $key, mixed $data): static
    {
        $path = $this->path($key);
        file_put_contents($path, json_encode($data));
        return $this;
    }

    private function path(string $key): string
    {
        $base = $this->base();
        $separator = DIRECTORY_SEPARATOR;
        $key = sha1($key);
        return "{$base}{$separator}{$key}.json";
    }

    private function base(): string
    {
        $base = basePath();
        $separator = DIRECTORY_SEPARATOR;
        $base = "{$base}{$separator}storage{$separator}framework{$separator}cache";

        if (!file_exists($base)) {
            mkdir($base, 0777, true);
        }

        return $base;
    }
}