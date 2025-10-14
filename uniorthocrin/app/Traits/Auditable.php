<?php

namespace App\Traits;

use App\Services\AuditService;
use Illuminate\Http\Request;

trait Auditable
{
    protected $auditService;

    /**
     * Obtém a instância do AuditService
     */
    protected function getAuditService(): AuditService
    {
        if (!$this->auditService) {
            $this->auditService = app(AuditService::class);
        }
        return $this->auditService;
    }

    /**
     * Registra criação de um recurso
     */
    protected function auditCreate(string $resourceType, $resourceId, array $data, Request $request = null): void
    {
        $this->getAuditService()->logCreate($resourceType, $resourceId, $data, $request);
    }

    /**
     * Registra atualização de um recurso
     */
    protected function auditUpdate(string $resourceType, $resourceId, array $oldData, array $newData, Request $request = null): void
    {
        $this->getAuditService()->logUpdate($resourceType, $resourceId, $oldData, $newData, $request);
    }

    /**
     * Registra exclusão de um recurso
     */
    protected function auditDelete(string $resourceType, $resourceId, array $data, Request $request = null): void
    {
        $this->getAuditService()->logDelete($resourceType, $resourceId, $data, $request);
    }

    /**
     * Registra visualização de um recurso
     */
    protected function auditView(string $resourceType, $resourceId, Request $request = null): void
    {
        $this->getAuditService()->logView($resourceType, $resourceId, $request);
    }

    /**
     * Registra download de um arquivo
     */
    protected function auditDownload(string $resourceType, $resourceId, string $fileName, Request $request = null): void
    {
        $this->getAuditService()->logDownload($resourceType, $resourceId, $fileName, $request);
    }

    /**
     * Registra upload de arquivo
     */
    protected function auditUpload(string $resourceType, $resourceId, string $fileName, Request $request = null): void
    {
        $this->getAuditService()->logUpload($resourceType, $resourceId, $fileName, $request);
    }

    /**
     * Registra falha em uma operação
     */
    protected function auditFailure(
        string $action,
        string $resourceType,
        $resourceId = null,
        string $message = null,
        array $context = [],
        Request $request = null
    ): void {
        $this->getAuditService()->logFailure($action, $resourceType, $resourceId, $message, $context, $request);
    }

    /**
     * Obtém o nome do recurso baseado na classe do controller
     */
    protected function getResourceName(): string
    {
        $className = class_basename($this);
        return strtolower(str_replace('Controller', '', $className));
    }

    /**
     * Obtém dados seguros para auditoria (remove campos sensíveis)
     */
    protected function getSafeData(array $data): array
    {
        $sensitiveFields = ['password', 'password_confirmation', 'token', 'secret', 'key'];
        
        return array_filter($data, function ($key) use ($sensitiveFields) {
            return !in_array(strtolower($key), $sensitiveFields);
        }, ARRAY_FILTER_USE_KEY);
    }
}
