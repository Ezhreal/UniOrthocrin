<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\ProfileService;
use App\Models\UserType;

class CheckProfileContext
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Pegar o slug do perfil da rota (prefixo dinâmico {profile_slug})
        $profileSlug = $request->route('profile_slug');

        if (!$profileSlug) {
            return $next($request);
        }

        // Buscar o tipo de usuário correspondente ao slug
        $profile = UserType::where('slug', $profileSlug)->first();

        if (!$profile) {
            abort(404, 'Perfil não encontrado.');
        }

        // Admin pode acessar qualquer perfil
        if ($user->isAdmin()) {
            $this->profileService->setActiveProfile($profile);
            return $next($request);
        }

        // Verificar se o usuário possui este perfil na pivot
        if (!$user->profiles->contains('id', $profile->id)) {
            abort(403, 'Você não tem permissão para acessar este perfil.');
        }

        // Atualizar o perfil ativo na sessão para coincidir com a URL
        $this->profileService->setActiveProfile($profile);

        return $next($request);
    }
}
