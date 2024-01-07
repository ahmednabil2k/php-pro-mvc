<?php

use Framework\Contracts\Http\App;

if (!function_exists('view')) {
    /**
     * @throws Exception
     */
    function view(string $template, array $data = [])
    {
        return app('view')->resolve($template, $data);
    }
}

if (!function_exists('validate')) {
    function validate(array $data, array $rules)
    {
        return app('validator')->validate($data, $rules);
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url)
    {
        header("Location: {$url}");
        exit;
    }
}

if (!function_exists('csrf')) {
    /**
     * @throws Exception
     */
    function csrf(): string
    {
        $token = bin2hex(random_bytes(32));
        app('session')->put('csrf_token', $token);
        return $token;
    }
}

if (!function_exists('secure')) {
    /**
     * @throws Exception
     */
    function secure(): void
    {
        if (!isset($_POST['csrf'])
            || !isset($_SESSION['token'])
            || !hash_equals($_SESSION['token'], $_POST['csrf'])) {

            throw new Exception('CSRF token mismatch');
        }
    }
}


if (!function_exists('env')) {
    /**
     * @param string $key
     * @param mixed|null $default
     * @return array|false|mixed|string
     */
    function env(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('app')) {
    function app(string $alias = null): mixed
    {
        if (is_null($alias)) {
            return App::getInstance();
        }
        return App::getInstance()->resolve($alias);
    }
}

if (!function_exists('basePath')) {
    function basePath(): string|null
    {
        return \app('paths.base');
    }
}

if (!function_exists('configPath')) {
    function configPath(): string|null
    {
        $separator = DIRECTORY_SEPARATOR;
        return basePath() . "{$separator}config";
    }
}

if (!function_exists('config')) {
    function config(string $key, mixed $default = null): string|array|null
    {
        return \app('config')->get($key, $default);
    }
}

if (!function_exists('request')) {
    function request(string $key = null, mixed $default = null): mixed
    {
        return $key ? \app('request')->get($key, $default) : \app('request');
    }
}

if (!function_exists('dd')) {
    function dd($var)
    {
        dump($var);
        die;
    }
}