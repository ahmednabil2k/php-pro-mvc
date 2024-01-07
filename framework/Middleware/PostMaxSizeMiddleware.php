<?php

namespace Framework\Middleware;

use Closure;
use Framework\Contracts\Http\Request;

class PostMaxSizeMiddleware implements Middleware
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}