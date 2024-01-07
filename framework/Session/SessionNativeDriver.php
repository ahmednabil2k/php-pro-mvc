<?php

namespace Framework\Session;
class SessionNativeDriver implements SessionDriver
{
    protected array $config = [];
    public function __construct(array $config)
    {
        $this->config = $config;
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->has($key) ? $_SESSION[$key] : $default;
    }

    public function put(string $key, mixed $value): static
    {
        $_SESSION[$key] = $value;
        return $this;
    }

    public function forget(string $key): static
    {
        if ($this->has($key))
            unset($_SESSION[$key]);
        return $this;
    }

    public function flush(): static
    {
        $prefix = config('session.prefix');

        foreach (array_keys($_SESSION) as $key) {
            if (str_starts_with($key, $prefix)) {
                unset($_SESSION[$key]);
            }
        }
        return $this;
    }
}