<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\UserViewService;
use App\Services\SessionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    protected $userViewService;
    protected $sessionService;
    
    public function __construct(UserViewService $userViewService, SessionService $sessionService)
    {
        $this->userViewService = $userViewService;
        $this->sessionService = $sessionService;
    }
    
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Só rastreia se o usuário estiver autenticado E não for rota de autenticação
        if (!Auth::check() || $this->isAuthRoute($request)) {
            return $response;
        }
        
        try {
            // Atualiza atividade da sessão
            $this->sessionService->updateCurrentSessionActivity();
            
            // Rastreia visualização da página
            $this->trackPageView($request);
            
        } catch (\Exception $e) {
            // Log do erro mas não interrompe a requisição
            Log::error('Erro ao rastrear atividade do usuário', [
                'user_id' => Auth::id(),
                'url' => $request->fullUrl(),
                'error' => $e->getMessage(),
            ]);
        }
        
        return $response;
    }
    
    /**
     * Verifica se é uma rota de autenticação
     */
    protected function isAuthRoute(Request $request): bool
    {
        $authRoutes = ['login', 'logout', 'password.*', 'email.*'];
        $path = $request->path();
        
        foreach ($authRoutes as $route) {
            if (str_starts_with($path, $route)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Rastreia visualização da página
     */
    protected function trackPageView(Request $request): void
    {
        $path = $request->path();
        $method = $request->method();
        
        // Só rastreia requisições GET (visualizações)
        if ($method !== 'GET') {
            return;
        }
        
        // Identifica o tipo de conteúdo baseado na rota
        $contentType = $this->getContentTypeFromRoute($path);
        
        if ($contentType) {
            // Extrai o ID do conteúdo da URL
            $contentId = $this->extractContentIdFromPath($path);
            
            if ($contentId) {
                // Registra a visualização
                $this->userViewService->recordView($contentType, $contentId);
            }
        }
    }
    
    /**
     * Identifica o tipo de conteúdo baseado na rota
     */
    protected function getContentTypeFromRoute(string $path): ?string
    {
        // Mapeia rotas para tipos de conteúdo
        $routeMapping = [
            'marketing' => 'App\Models\Campaign',
            'produtos' => 'App\Models\Product',
            'treinamentos' => 'App\Models\Training',
            'biblioteca' => 'App\Models\Library',
            'news' => 'App\Models\News',
        ];
        
        foreach ($routeMapping as $route => $model) {
            if (str_starts_with($path, $route)) {
                return $model;
            }
        }
        
        return null;
    }
    
    /**
     * Extrai o ID do conteúdo da URL
     */
    protected function extractContentIdFromPath(string $path): ?int
    {
        // Padrão: /tipo/id ou /tipo-list
        $segments = explode('/', trim($path, '/'));
        
        // Se tem 2 segmentos e o segundo é numérico, é um ID
        if (count($segments) === 2 && is_numeric($segments[1])) {
            return (int) $segments[1];
        }
        
        return null;
    }
}
