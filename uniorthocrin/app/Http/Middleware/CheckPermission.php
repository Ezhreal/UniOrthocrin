<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission = null, string $resource = null): Response
    {
        $user = Auth::user();

        // Se não estiver autenticado, redirecionar para login
        if (!$user) {
            return redirect()->route('login');
        }

        // Admin tem acesso total
        if ($user->user_type_id === 1) {
            return $next($request);
        }

        // Verificar permissão específica se fornecida
        if ($permission) {
            if (!$this->hasPermission($user, $permission, $resource, $request)) {
                abort(403, 'Acesso negado. Você não tem permissão para acessar este recurso.');
            }
        }

        // Verificar acesso baseado no tipo de usuário
        if (!$this->canAccessResource($user, $request)) {
            abort(403, 'Acesso negado. Seu tipo de usuário não tem acesso a este recurso.');
        }

        return $next($request);
    }

    /**
     * Verifica se o usuário tem uma permissão específica
     */
    protected function hasPermission($user, string $permission, string $resource = null, Request $request = null): bool
    {
        // Verificar permissões do Spatie se disponível
        if (method_exists($user, 'hasPermissionTo')) {
            return $user->hasPermissionTo($permission);
        }

        // Verificar permissões customizadas baseadas no recurso
        if ($resource && $request) {
            return $this->checkResourcePermission($user, $permission, $resource, $request);
        }

        return false;
    }

    /**
     * Verifica acesso baseado no tipo de usuário
     */
    protected function canAccessResource($user, Request $request): bool
    {
        $route = $request->route();
        $routeName = $route ? $route->getName() : null;
        $userTypeId = $user->user_type_id;

        // Mapeamento de rotas por tipo de usuário
        $accessMap = [
            1 => ['*'], // Admin - acesso total
            2 => [ // Franqueado
                'admin.dashboard',
                'admin.products.*',
                'admin.campaigns.*',
                'admin.library.*',
                'admin.training.*',
                'admin.news.*',
                'admin.users.index',
                'admin.users.show',
            ],
            3 => [ // Lojista
                'admin.dashboard',
                'admin.products.index',
                'admin.products.show',
                'admin.campaigns.index',
                'admin.campaigns.show',
                'admin.library.index',
                'admin.library.show',
                'admin.training.index',
                'admin.training.show',
                'admin.news.index',
                'admin.news.show',
            ],
            4 => [ // Representante
                'admin.dashboard',
                'admin.products.index',
                'admin.products.show',
                'admin.campaigns.index',
                'admin.campaigns.show',
                'admin.library.index',
                'admin.library.show',
                'admin.training.index',
                'admin.training.show',
                'admin.news.index',
                'admin.news.show',
            ],
        ];

        $allowedRoutes = $accessMap[$userTypeId] ?? [];

        // Se não há rotas específicas ou se é admin, permitir
        if (empty($allowedRoutes) || in_array('*', $allowedRoutes)) {
            return true;
        }

        // Verificar se a rota atual está permitida
        if ($routeName) {
            foreach ($allowedRoutes as $allowedRoute) {
                if (fnmatch($allowedRoute, $routeName)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Verifica permissão específica de recurso
     */
    protected function checkResourcePermission($user, string $permission, string $resource, Request $request): bool
    {
        $resourceId = $request->route($resource);
        
        if (!$resourceId) {
            return false;
        }

        // Buscar o recurso e suas permissões
        $modelClass = $this->getModelClass($resource);
        if (!$modelClass) {
            return false;
        }

        $resourceModel = $modelClass::find($resourceId);
        if (!$resourceModel) {
            return false;
        }

        // Verificar se o recurso tem o trait HasPermissions
        if (!method_exists($resourceModel, 'hasPermission')) {
            return false;
        }

        return $resourceModel->hasPermission($user->user_type_id, $permission);
    }

    /**
     * Obtém a classe do modelo baseada no nome do recurso
     */
    protected function getModelClass(string $resource): ?string
    {
        $modelMap = [
            'product' => \App\Models\Product::class,
            'campaign' => \App\Models\Campaign::class,
            'library' => \App\Models\Library::class,
            'training' => \App\Models\Training::class,
            'news' => \App\Models\News::class,
            'user' => \App\Models\User::class,
        ];

        return $modelMap[$resource] ?? null;
    }
}
