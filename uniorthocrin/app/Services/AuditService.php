<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuditService
{
    /**
     * Registra uma ação de auditoria
     */
    public function log(
        string $action,
        string $resourceType,
        $resourceId = null,
        array $oldValues = [],
        array $newValues = [],
        string $status = 'success',
        string $message = null,
        Request $request = null
    ): AuditLog {
        $user = Auth::user();
        $request = $request ?: request();

        $auditLog = AuditLog::create([
            'user_id' => $user ? $user->id : null,
            'action' => $action,
            'resource_type' => $resourceType,
            'resource_id' => $resourceId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'status' => $status,
            'message' => $message,
        ]);

        // Log também no sistema de logs do Laravel para backup
        Log::info('Audit Log Created', [
            'audit_id' => $auditLog->id,
            'action' => $action,
            'resource_type' => $resourceType,
            'resource_id' => $resourceId,
            'user_id' => $user ? $user->id : null,
        ]);

        return $auditLog;
    }

    /**
     * Registra criação de um recurso
     */
    public function logCreate(string $resourceType, $resourceId, array $data, Request $request = null): AuditLog
    {
        return $this->log(
            'create',
            $resourceType,
            $resourceId,
            [],
            $data,
            'success',
            "Recurso {$resourceType} criado com sucesso",
            $request
        );
    }

    /**
     * Registra atualização de um recurso
     */
    public function logUpdate(string $resourceType, $resourceId, array $oldData, array $newData, Request $request = null): AuditLog
    {
        return $this->log(
            'update',
            $resourceType,
            $resourceId,
            $oldData,
            $newData,
            'success',
            "Recurso {$resourceType} atualizado com sucesso",
            $request
        );
    }

    /**
     * Registra exclusão de um recurso
     */
    public function logDelete(string $resourceType, $resourceId, array $data, Request $request = null): AuditLog
    {
        return $this->log(
            'delete',
            $resourceType,
            $resourceId,
            $data,
            [],
            'success',
            "Recurso {$resourceType} excluído com sucesso",
            $request
        );
    }

    /**
     * Registra visualização de um recurso
     */
    public function logView(string $resourceType, $resourceId, Request $request = null): AuditLog
    {
        return $this->log(
            'view',
            $resourceType,
            $resourceId,
            [],
            [],
            'success',
            "Recurso {$resourceType} visualizado",
            $request
        );
    }

    /**
     * Registra download de um arquivo
     */
    public function logDownload(string $resourceType, $resourceId, string $fileName, Request $request = null): AuditLog
    {
        return $this->log(
            'download',
            $resourceType,
            $resourceId,
            [],
            ['file_name' => $fileName],
            'success',
            "Arquivo {$fileName} baixado",
            $request
        );
    }

    /**
     * Registra upload de arquivo
     */
    public function logUpload(string $resourceType, $resourceId, string $fileName, Request $request = null): AuditLog
    {
        return $this->log(
            'upload',
            $resourceType,
            $resourceId,
            [],
            ['file_name' => $fileName],
            'success',
            "Arquivo {$fileName} enviado",
            $request
        );
    }

    /**
     * Registra falha em uma operação
     */
    public function logFailure(
        string $action,
        string $resourceType,
        $resourceId = null,
        string $message = null,
        array $context = [],
        Request $request = null
    ): AuditLog {
        return $this->log(
            $action,
            $resourceType,
            $resourceId,
            [],
            $context,
            'failed',
            $message ?: "Falha na operação {$action}",
            $request
        );
    }

    /**
     * Registra login do usuário
     */
    public function logLogin(User $user, Request $request = null): AuditLog
    {
        return $this->log(
            'login',
            'user',
            $user->id,
            [],
            ['email' => $user->email],
            'success',
            "Usuário fez login",
            $request
        );
    }

    /**
     * Registra logout do usuário
     */
    public function logLogout(User $user, Request $request = null): AuditLog
    {
        return $this->log(
            'logout',
            'user',
            $user->id,
            [],
            ['email' => $user->email],
            'success',
            "Usuário fez logout",
            $request
        );
    }

    /**
     * Obtém logs de auditoria com filtros
     */
    public function getLogs(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = AuditLog::with('user');

        if (isset($filters['user_id'])) {
            $query->forUser($filters['user_id']);
        }

        if (isset($filters['resource_type'])) {
            $query->forResource($filters['resource_type'], $filters['resource_id'] ?? null);
        }

        if (isset($filters['action'])) {
            $query->forAction($filters['action']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->inDateRange($filters['start_date'], $filters['end_date']);
        }

        if (isset($filters['limit'])) {
            $query->limit($filters['limit']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Obtém estatísticas de auditoria
     */
    public function getStats(array $filters = []): array
    {
        $query = AuditLog::query();

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->inDateRange($filters['start_date'], $filters['end_date']);
        }

        $total = $query->count();
        $successful = $query->clone()->successful()->count();
        $failed = $query->clone()->failed()->count();

        $actions = $query->clone()
            ->selectRaw('action, COUNT(*) as count')
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->get()
            ->pluck('count', 'action')
            ->toArray();

        $resources = $query->clone()
            ->selectRaw('resource_type, COUNT(*) as count')
            ->groupBy('resource_type')
            ->orderBy('count', 'desc')
            ->get()
            ->pluck('count', 'resource_type')
            ->toArray();

        return [
            'total' => $total,
            'successful' => $successful,
            'failed' => $failed,
            'success_rate' => $total > 0 ? round(($successful / $total) * 100, 2) : 0,
            'actions' => $actions,
            'resources' => $resources,
        ];
    }
}
