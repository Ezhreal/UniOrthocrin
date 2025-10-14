<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserNotificationRepository;
use App\Repositories\UserViewRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NotificationGeneratorService
{
    protected $userNotificationRepository;
    protected $userViewRepository;
    
    public function __construct(
        UserNotificationRepository $userNotificationRepository,
        UserViewRepository $userViewRepository
    ) {
        $this->userNotificationRepository = $userNotificationRepository;
        $this->userViewRepository = $userViewRepository;
    }
    
    /**
     * Cria notificações para conteúdo novo para todos os usuários
     */
    public function createNotificationsForNewContent(string $contentType, int $contentId, string $contentTitle): int
    {
        try {
            // Obtém todos os usuários ativos
            $users = User::where('status', 'active')->get();
            
            $notificationsCreated = 0;
            
            foreach ($users as $user) {
                // Verifica se o usuário já viu este conteúdo
                $hasViewed = $this->userViewRepository->hasUserViewed(
                    $user->id, 
                    $contentType, 
                    $contentId
                );
                
                // Só cria notificação se o usuário não viu
                if (!$hasViewed) {
                    $this->userNotificationRepository->createNewContentNotification(
                        $user->id,
                        $contentType,
                        $contentId,
                        $contentTitle
                    );
                    $notificationsCreated++;
                }
            }
            
            return $notificationsCreated;
            
        } catch (\Exception $e) {
            Log::error('Erro ao criar notificações para conteúdo novo', [
                'content_type' => $contentType,
                'content_id' => $contentId,
                'content_title' => $contentTitle,
                'error' => $e->getMessage(),
            ]);
            
            return 0;
        }
    }
    
    /**
     * Cria notificações para conteúdo novo para usuários específicos
     */
    public function createNotificationsForSpecificUsers(
        array $userIds, 
        string $contentType, 
        int $contentId, 
        string $contentTitle
    ): int {
        try {
            $notificationsCreated = 0;
            
            foreach ($userIds as $userId) {
                // Verifica se o usuário já viu este conteúdo
                $hasViewed = $this->userViewRepository->hasUserViewed(
                    $userId, 
                    $contentType, 
                    $contentId
                );
                
                // Só cria notificação se o usuário não viu
                if (!$hasViewed) {
                    $this->userNotificationRepository->createNewContentNotification(
                        $userId,
                        $contentType,
                        $contentId,
                        $contentTitle
                    );
                    $notificationsCreated++;
                }
            }
            
            return $notificationsCreated;
            
        } catch (\Exception $e) {
            Log::error('Erro ao criar notificações para usuários específicos', [
                'user_ids' => $userIds,
                'content_type' => $contentType,
                'content_id' => $contentId,
                'content_title' => $contentTitle,
                'error' => $e->getMessage(),
            ]);
            
            return 0;
        }
    }
    
    /**
     * Cria notificações para conteúdo novo baseado em permissões
     */
    public function createNotificationsBasedOnPermissions(
        string $contentType, 
        int $contentId, 
        string $contentTitle,
        string $permissionType = null
    ): int {
        try {
            // Obtém usuários baseado no tipo de permissão
            $users = $this->getUsersByPermissionType($permissionType);
            
            $notificationsCreated = 0;
            
            foreach ($users as $user) {
                // Verifica se o usuário já viu este conteúdo
                $hasViewed = $this->userViewRepository->hasUserViewed(
                    $user->id, 
                    $contentType, 
                    $contentId
                );
                
                // Só cria notificação se o usuário não viu
                if (!$hasViewed) {
                    $this->userNotificationRepository->createNewContentNotification(
                        $user->id,
                        $contentType,
                        $contentId,
                        $contentTitle
                    );
                    $notificationsCreated++;
                }
            }
            
            return $notificationsCreated;
            
        } catch (\Exception $e) {
            Log::error('Erro ao criar notificações baseado em permissões', [
                'content_type' => $contentType,
                'content_id' => $contentId,
                'content_title' => $contentTitle,
                'permission_type' => $permissionType,
                'error' => $e->getMessage(),
            ]);
            
            return 0;
        }
    }
    
    /**
     * Obtém usuários baseado no tipo de permissão
     */
    protected function getUsersByPermissionType(?string $permissionType): Collection
    {
        if (!$permissionType) {
            return User::where('status', 'active')->get();
        }
        
        // Aqui você pode implementar lógica específica baseada no tipo de permissão
        // Por exemplo, usuários que têm acesso a produtos, treinamentos, etc.
        
        switch ($permissionType) {
            case 'products':
                return User::where('status', 'active')
                    ->whereHas('productPermissions')
                    ->get();
                    
            case 'trainings':
                return User::where('status', 'active')
                    ->whereHas('trainingPermissions')
                    ->get();
                    
            case 'library':
                return User::where('status', 'active')
                    ->whereHas('libraryPermissions')
                    ->get();
                    
            case 'news':
                return User::where('status', 'active')
                    ->whereHas('newsPermissions')
                    ->get();
                    
            default:
                return User::where('status', 'active')->get();
        }
    }
    
    /**
     * Cria notificação personalizada para um usuário
     */
    public function createCustomNotification(
        int $userId, 
        string $title, 
        string $message, 
        string $type = 'info',
        ?string $relatedType = null,
        ?int $relatedId = null
    ): bool {
        try {
            $this->userNotificationRepository->createNotification(
                $userId,
                $title,
                $message,
                $type,
                $relatedType,
                $relatedId
            );
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Erro ao criar notificação personalizada', [
                'user_id' => $userId,
                'title' => $title,
                'message' => $message,
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }
    
    /**
     * Cria notificação para múltiplos usuários
     */
    public function createCustomNotificationForMultipleUsers(
        array $userIds, 
        string $title, 
        string $message, 
        string $type = 'info',
        ?string $relatedType = null,
        ?int $relatedId = null
    ): int {
        $notificationsCreated = 0;
        
        foreach ($userIds as $userId) {
            if ($this->createCustomNotification($userId, $title, $message, $type, $relatedType, $relatedId)) {
                $notificationsCreated++;
            }
        }
        
        return $notificationsCreated;
    }
    
    /**
     * Cria notificação de sistema para todos os usuários
     */
    public function createSystemNotificationForAllUsers(
        string $title, 
        string $message, 
        string $type = 'info'
    ): int {
        try {
            $users = User::where('status', 'active')->get();
            
            $notificationsCreated = 0;
            
            foreach ($users as $user) {
                $this->userNotificationRepository->createNotification(
                    $user->id,
                    $title,
                    $message,
                    $type
                );
                $notificationsCreated++;
            }
            
            return $notificationsCreated;
            
        } catch (\Exception $e) {
            Log::error('Erro ao criar notificação de sistema para todos os usuários', [
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);
            
            return 0;
        }
    }
}
