<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PermissionService
{
    /**
     * Verifica se um usuário pode acessar um recurso específico
     */
    public function canAccess(User $user, string $resource, string $action = 'view', $resourceId = null): bool
    {
        // Admin tem acesso total
        if ($user->isAdmin()) {
            return true;
        }

        // Verificar cache primeiro
        $cacheKey = "permission_{$user->id}_{$resource}_{$action}_{$resourceId}";
        $cached = Cache::get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        $result = $this->checkPermission($user, $resource, $action, $resourceId);
        
        // Cache por 5 minutos
        Cache::put($cacheKey, $result, 300);
        
        return $result;
    }

    /**
     * Verifica permissão específica
     */
    protected function checkPermission(User $user, string $resource, string $action, $resourceId = null): bool
    {
        try {
            // Verificar permissões baseadas no tipo de usuário
            if (!$this->hasUserTypeAccess($user, $resource, $action)) {
                return false;
            }

            // Se há um ID de recurso específico, verificar permissões do recurso
            if ($resourceId) {
                return $this->checkResourcePermission($user, $resource, $action, $resourceId);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao verificar permissão', [
                'user_id' => $user->id,
                'resource' => $resource,
                'action' => $action,
                'resource_id' => $resourceId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Verifica acesso baseado no tipo de usuário
     */
    protected function hasUserTypeAccess(User $user, string $resource, string $action): bool
    {
        $userTypeId = $user->user_type_id;
        
        // Mapeamento de permissões por tipo de usuário
        $permissions = [
            1 => [ // Admin
                'products' => ['view', 'create', 'edit', 'delete'],
                'campaigns' => ['view', 'create', 'edit', 'delete'],
                'library' => ['view', 'create', 'edit', 'delete'],
                'training' => ['view', 'create', 'edit', 'delete'],
                'news' => ['view', 'create', 'edit', 'delete'],
                'users' => ['view', 'create', 'edit', 'delete'],
            ],
            2 => [ // Franqueado
                'products' => ['view', 'create', 'edit', 'delete'],
                'campaigns' => ['view', 'create', 'edit', 'delete'],
                'library' => ['view', 'create', 'edit', 'delete'],
                'training' => ['view', 'create', 'edit', 'delete'],
                'news' => ['view', 'create', 'edit', 'delete'],
                'users' => ['view'],
            ],
            3 => [ // Lojista
                'products' => ['view'],
                'campaigns' => ['view'],
                'library' => ['view'],
                'training' => ['view'],
                'news' => ['view'],
                'users' => [],
            ],
            4 => [ // Representante
                'products' => ['view'],
                'campaigns' => ['view'],
                'library' => ['view'],
                'training' => ['view'],
                'news' => ['view'],
                'users' => [],
            ],
        ];

        $userPermissions = $permissions[$userTypeId] ?? [];
        $resourcePermissions = $userPermissions[$resource] ?? [];

        return in_array($action, $resourcePermissions);
    }

    /**
     * Verifica permissão específica de um recurso
     */
    protected function checkResourcePermission(User $user, string $resource, string $action, $resourceId): bool
    {
        $modelClass = $this->getModelClass($resource);
        if (!$modelClass) {
            return false;
        }

        $resourceModel = $modelClass::find($resourceId);
        if (!$resourceModel) {
            return false;
        }

        // Verificar se o recurso tem permissões específicas
        if (method_exists($resourceModel, 'hasPermission')) {
            return $resourceModel->hasPermission($user->user_type_id, $action);
        }

        // Se não tem permissões específicas, usar permissões do tipo de usuário
        return $this->hasUserTypeAccess($user, $resource, $action);
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

    /**
     * Limpa cache de permissões para um usuário
     */
    public function clearUserPermissionCache(User $user): void
    {
        $pattern = "permission_{$user->id}_*";
        // Note: Laravel não tem um método direto para limpar por padrão
        // Em produção, considere usar Redis com SCAN ou implementar um sistema de tags
        Cache::flush(); // Por simplicidade, limpa todo o cache
    }

    /**
     * Limpa cache de permissões para um recurso específico
     */
    public function clearResourcePermissionCache(string $resource, $resourceId = null): void
    {
        $pattern = $resourceId ? "*_{$resource}_*_{$resourceId}" : "*_{$resource}_*";
        // Similar ao método acima, em produção use Redis com SCAN
        Cache::flush();
    }

    /**
     * Obtém todas as permissões de um usuário
     */
    public function getUserPermissions(User $user): array
    {
        $userTypeId = $user->user_type_id;
        
        $permissions = [
            1 => [ // Admin
                'products' => ['view', 'create', 'edit', 'delete'],
                'campaigns' => ['view', 'create', 'edit', 'delete'],
                'library' => ['view', 'create', 'edit', 'delete'],
                'training' => ['view', 'create', 'edit', 'delete'],
                'news' => ['view', 'create', 'edit', 'delete'],
                'users' => ['view', 'create', 'edit', 'delete'],
            ],
            2 => [ // Franqueado
                'products' => ['view', 'create', 'edit', 'delete'],
                'campaigns' => ['view', 'create', 'edit', 'delete'],
                'library' => ['view', 'create', 'edit', 'delete'],
                'training' => ['view', 'create', 'edit', 'delete'],
                'news' => ['view', 'create', 'edit', 'delete'],
                'users' => ['view'],
            ],
            3 => [ // Lojista
                'products' => ['view'],
                'campaigns' => ['view'],
                'library' => ['view'],
                'training' => ['view'],
                'news' => ['view'],
                'users' => [],
            ],
            4 => [ // Representante
                'products' => ['view'],
                'campaigns' => ['view'],
                'library' => ['view'],
                'training' => ['view'],
                'news' => ['view'],
                'users' => [],
            ],
        ];

        return $permissions[$userTypeId] ?? [];
    }

    /**
     * Verifica se um usuário pode fazer download de um arquivo
     */
    public function canDownload(User $user, $resource, $resourceId = null): bool
    {
        return $this->canAccess($user, $resource, 'download', $resourceId);
    }

    /**
     * Verifica se um usuário pode editar um recurso
     */
    public function canEdit(User $user, $resource, $resourceId = null): bool
    {
        return $this->canAccess($user, $resource, 'edit', $resourceId);
    }

    /**
     * Verifica se um usuário pode deletar um recurso
     */
    public function canDelete(User $user, $resource, $resourceId = null): bool
    {
        return $this->canAccess($user, $resource, 'delete', $resourceId);
    }
}
