<?php

namespace Framework\Middleware;

use Closure;
use Framework\Contracts\Http\Request;
use Framework\Routing\Router;

class CSRFTokenMiddleware implements Middleware
{
    /**
     * @throws \Exception
     */
    public function handle(Request $request, Closure $next)
    {
        $method = $request->method();
        if (!in_array($method, [Router::POST, Router::PUT])) {
            return $next($request);
        }

        $csrfToken = $request->request('csrf_token');
        if (!$csrfToken) {
            throw new \Exception('Missing csrf token!');
        }

        $csrfSessionToken = app('session')->get('csrf_token');
        if (!hash_equals($csrfSessionToken, $csrfToken)){
            throw new \Exception('csrf token mismatch!');
        }

        return $next($request);
    }
}