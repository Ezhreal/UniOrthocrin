<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetDefaultProfileSlug
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Se a rota atual tem o parâmetro profile_slug, usamos ele para definir o default
        $profileSlug = $request->route('profile_slug');

        if ($profileSlug) {
            URL::defaults(['profile_slug' => $profileSlug]);
        } elseif (Session::has('active_profile_slug')) {
            // 2. Senão, usamos o slug que está ativo na sessão
            URL::defaults(['profile_slug' => Session::get('active_profile_slug')]);
        }

        return $next($request);
    }
}
