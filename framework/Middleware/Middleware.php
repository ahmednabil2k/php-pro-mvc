<?php

namespace Framework\Middleware;

use Closure;
use Framework\Contracts\Http\Request;

interface Middleware
{

    public function handle(Request $request, Closure $next);
}