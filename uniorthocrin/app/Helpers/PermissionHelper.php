<?php

namespace App\Helpers;

use App\Services\PermissionService;
use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    protected static $permissionService;

    /**
     * Obtém a instância do PermissionService
     */
    protected static function getPermissionService(): PermissionService
    {
        if (!static::$permissionService) {
            static::$permissionService = app(PermissionService::class);
        }
        return static::$permissionService;
    }

    /**
     * Verifica se o usuário atual pode acessar um recurso
     */
    public static function can(string $resource, string $action = 'view', $resourceId = null): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        return static::getPermissionService()->canAccess($user, $resource, $action, $resourceId);
    }

    /**
     * Verifica se o usuário atual pode visualizar um recurso
     */
    public static function canView(string $resource, $resourceId = null): bool
    {
        return static::can($resource, 'view', $resourceId);
    }

    /**
     * Verifica se o usuário atual pode criar um recurso
     */
    public static function canCreate(string $resource): bool
    {
        return static::can($resource, 'create');
    }

    /**
     * Verifica se o usuário atual pode editar um recurso
     */
    public static function canEdit(string $resource, $resourceId = null): bool
    {
        return static::can($resource, 'edit', $resourceId);
    }

    /**
     * Verifica se o usuário atual pode deletar um recurso
     */
    public static function canDelete(string $resource, $resourceId = null): bool
    {
        return static::can($resource, 'delete', $resourceId);
    }

    /**
     * Verifica se o usuário atual pode fazer download
     */
    public static function canDownload(string $resource, $resourceId = null): bool
    {
        return static::can($resource, 'download', $resourceId);
    }

    /**
     * Verifica se o usuário atual é admin
     */
    public static function isAdmin(): bool
    {
        $user = Auth::user();
        return $user && $user->user_type_id === 1;
    }

    /**
     * Verifica se o usuário atual é franqueado
     */
    public static function isFranqueado(): bool
    {
        $user = Auth::user();
        return $user && $user->user_type_id === 2;
    }

    /**
     * Verifica se o usuário atual é lojista
     */
    public static function isLojista(): bool
    {
        $user = Auth::user();
        return $user && $user->user_type_id === 3;
    }

    /**
     * Verifica se o usuário atual é representante
     */
    public static function isRepresentante(): bool
    {
        $user = Auth::user();
        return $user && $user->user_type_id === 4;
    }

    /**
     * Obtém o tipo de usuário atual
     */
    public static function getUserType(): ?string
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        $userTypeMap = [
            1 => 'admin',
            2 => 'franqueado',
            3 => 'lojista',
            4 => 'representante',
        ];

        return $userTypeMap[$user->user_type_id] ?? 'unknown';
    }

    /**
     * Obtém todas as permissões do usuário atual
     */
    public static function getUserPermissions(): array
    {
        $user = Auth::user();
        if (!$user) {
            return [];
        }

        return static::getPermissionService()->getUserPermissions($user);
    }
}
