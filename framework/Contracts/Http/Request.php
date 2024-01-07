<?php

namespace Framework\Contracts\Http;

use Framework\Routing\Router;

class Request
{
    protected array $post = [];

    protected array $get = [];

    protected array $files = [];

    protected array $cookies = [];

    protected array $server = [];

    protected array $request = [];

    public function __construct()
    {
        $this->post = $_POST;
        $this->get = $_GET;
        $this->files = $_FILES;
        $this->cookies = $_COOKIE;
        $this->server = $_SERVER;
        $this->request = $_REQUEST;
    }

    public static function capture(): static
    {
        return new static();
    }

    public function method(): string
    {
        return $this->server['REQUEST_METHOD'] ? strtoupper($this->server['REQUEST_METHOD']) : Router::GET;
    }

    public function uri(): string
    {
        return $this->server['REQUEST_URI'] ?? '';
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if ($this->method() === Router::GET) {
            return $this->get[$key] ?? $this->server[$key] ?? $default;
        }

        if (in_array($this->method(), [Router::POST, Router::PUT])) {
            return $this->post[$key] ?? $this->files[$key] ?? $default;
        }

        return null;
    }

    public function cookies(string $name = null): mixed
    {
        if ($name)
            return $this->cookies[$name] ?? null;

        return $this->cookies;
    }

    public function files(string $name = null): mixed
    {
        if ($name)
            return $this->files[$name] ?? null;

        return $this->files;
    }

    public function server(string $name = null): mixed
    {
        if ($name)
            return $this->server[$name] ?? null;

        return $this->server;
    }

    public function request(string $name = null): mixed
    {
        if ($name)
            return $this->request[$name] ?? null;

        return $this->request;
    }

    public function headers(string $header = null)
    {
        $headers = [];

        foreach ($this->server as $name => $value) {

            if (str_starts_with($name, 'HTTP_')) {

                $headerName = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));

                $headers[$headerName] = $value;

            } else if ($name == "CONTENT_TYPE") {

                $headers["Content-Type"] = $value;

            } else if ($name == "CONTENT_LENGTH") {

                $headers["Content-Length"] = $value;
            }
        }

        return $header ? $headers[$header] : $headers;
    }

    public function __get(string $name)
    {
        return $this->get($name);
    }

}