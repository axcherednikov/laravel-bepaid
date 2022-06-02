<?php

namespace Excent\BePaidLaravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InjectBasicAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!empty($request->header('php-auth-user')) && !empty($request->header('php-auth-pw'))) {
            $_SERVER['PHP_AUTH_USER'] = (int)$request->header('php-auth-user');
            $_SERVER['PHP_AUTH_PW'] = $request->header('php-auth-pw');
        }

        if (!empty($request->header('authorization'))) {
            $_SERVER['HTTP_AUTHORIZATION'] = $request->header('authorization');
        }

        return $next($request);
    }
}
