<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NoStoreForAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (auth()->check()) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
        }

        return $response;
    }
}


