<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || (int) (auth()->user()->user_type_id ?? 0) !== 1) {
            return redirect()->route('admin.login')->with('error', 'Acesso restrito ao administrador.');
        }
        
        return $next($request);
    }
}
