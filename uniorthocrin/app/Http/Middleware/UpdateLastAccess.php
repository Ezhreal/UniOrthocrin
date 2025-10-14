<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Atualiza last_access se o usuário estiver autenticado
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            
            // Atualiza apenas se passou mais de 5 minutos desde o último acesso
            if (!$user->last_access || $user->last_access->diffInMinutes(Carbon::now()) >= 5) {
                $user->update(['last_access' => Carbon::now()]);
            }
        }
        
        return $response;
    }
}
