<?php

namespace App\Services;

use App\Repositories\UserNotificationRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class UserNotificationService
{
    protected $userNotificationRepository;
    
    public function __construct(UserNotificationRepository $userNotificationRepository)
    {
        $this->userNotificationRepository = $userNotificationRepository;
    }
    
    /**
     * Obtém notificações não lidas para o usuário atual
     */
    public function getCurrentUserUnreadNotifications(int $limit = null): Collection
    {
        if (!Auth::check()) {
            return collect();
        }
        
        return $this->userNotificationRepository->getUnreadNotifications(Auth::id(), $limit);
    }
    
    /**
     * Obtém todas as notificações do usuário atual
     */
    public function getCurrentUserNotifications(int $limit = null, bool $includeRead = true): Collection
    {
        if (!Auth::check()) {
            return collect();
        }
        
        return $this->userNotificationRepository->getUserNotifications(Auth::id(), $limit, $includeRead);
    }
    
    /**
     * Obtém notificações do usuário atual por tipo
     */
    public function getCurrentUserNotificationsByType(string $type, int $limit = null): Collection
    {
        if (!Auth::check()) {
            return collect();
        }
        
        return $this->userNotificationRepository->getUserNotificationsByType(Auth::id(), $type, $limit);
    }
    
    /**
     * Obtém notificações relacionadas a um item específico para o usuário atual
     */
    public function getCurrentUserNotificationsRelatedTo(string $relatedType, int $relatedId): Collection
    {
        if (!Auth::check()) {
            return collect();
        }
        
        return $this->userNotificationRepository->getNotificationsRelatedTo(Auth::id(), $relatedType, $relatedId);
    }
    
    /**
     * Obtém notificações recentes do usuário atual
     */
    public function getCurrentUserRecentNotifications(int $days = 30, int $limit = null): Collection
    {
        if (!Auth::check()) {
            return collect();
        }
        
        return $this->userNotificationRepository->getRecentNotifications(Auth::id(), $days, $limit);
    }
    
    /**
     * Marca uma notificação como lida para o usuário atual
     */
    public function markAsRead(int $notificationId): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        $notification = $this->userNotificationRepository->getUserNotifications(Auth::id())->where('id', $notificationId)->first();
        
        // Verifica se a notificação pertence ao usuário atual
        if ($notification && $notification->user_id === Auth::id()) {
            return $this->userNotificationRepository->markAsRead($notificationId);
        }
        
        return false;
    }
    
    /**
     * Marca múltiplas notificações como lidas para o usuário atual
     */
    public function markMultipleAsRead(array $notificationIds): int
    {
        if (!Auth::check()) {
            return 0;
        }
        
        // Filtra apenas notificações do usuário atual
        $userNotifications = $this->userNotificationRepository->getUserNotifications(Auth::id())
            ->whereIn('id', $notificationIds)
            ->pluck('id')
            ->toArray();
        
        if (!empty($userNotifications)) {
            return $this->userNotificationRepository->markMultipleAsRead($userNotifications);
        }
        
        return 0;
    }
    
    /**
     * Marca todas as notificações do usuário atual como lidas
     */
    public function markAllAsRead(): int
    {
        if (!Auth::check()) {
            return 0;
        }
        
        return $this->userNotificationRepository->markAllAsRead(Auth::id());
    }
    
    /**
     * Marca uma notificação como não lida para o usuário atual
     */
    public function markAsUnread(int $notificationId): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        $notification = $this->userNotificationRepository->getUserNotifications(Auth::id())->where('id', $notificationId)->first();
        
        // Verifica se a notificação pertence ao usuário atual
        if ($notification && $notification->user_id === Auth::id()) {
            return $this->userNotificationRepository->markAsUnread($notificationId);
        }
        
        return false;
    }
    
    /**
     * Remove uma notificação para o usuário atual
     */
    public function deleteNotification(int $notificationId): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        $notification = $this->userNotificationRepository->getUserNotifications(Auth::id())->where('id', $notificationId)->first();
        
        // Verifica se a notificação pertence ao usuário atual
        if ($notification && $notification->user_id === Auth::id()) {
            return $this->userNotificationRepository->deleteNotification($notificationId);
        }
        
        return false;
    }
    
    /**
     * Remove múltiplas notificações para o usuário atual
     */
    public function deleteMultipleNotifications(array $notificationIds): int
    {
        if (!Auth::check()) {
            return 0;
        }
        
        // Filtra apenas notificações do usuário atual
        $userNotifications = $this->userNotificationRepository->getUserNotifications(Auth::id())
            ->whereIn('id', $notificationIds)
            ->pluck('id')
            ->toArray();
        
        if (!empty($userNotifications)) {
            return $this->userNotificationRepository->deleteMultipleNotifications($userNotifications);
        }
        
        return 0;
    }
    
    /**
     * Remove notificações lidas antigas do usuário atual
     */
    public function deleteOldReadNotifications(int $daysOld = 30): int
    {
        if (!Auth::check()) {
            return 0;
        }
        
        return $this->userNotificationRepository->deleteReadNotifications(Auth::id(), $daysOld);
    }
    
    /**
     * Obtém estatísticas de notificações para o usuário atual
     */
    public function getCurrentUserNotificationStats(): array
    {
        if (!Auth::check()) {
            return [];
        }
        
        return $this->userNotificationRepository->getNotificationStats(Auth::id());
    }
    
    /**
     * Obtém contagem de notificações não lidas para o usuário atual
     */
    public function getCurrentUserUnreadCount(): int
    {
        if (!Auth::check()) {
            return 0;
        }
        
        return $this->userNotificationRepository->getUnreadCount(Auth::id());
    }
    
    /**
     * Verifica se o usuário atual tem notificações não lidas
     */
    public function hasCurrentUserUnreadNotifications(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        return $this->userNotificationRepository->hasUnreadNotifications(Auth::id());
    }
    
    /**
     * Obtém notificações para o dropdown do usuário atual
     */
    public function getCurrentUserNotificationsForDropdown(int $limit = 10): Collection
    {
        if (!Auth::check()) {
            return collect();
        }
        
        return $this->userNotificationRepository->getNotificationsForDropdown(Auth::id(), $limit);
    }
    
    /**
     * Cria uma notificação para o usuário atual
     */
    public function createNotificationForCurrentUser(string $title, string $message, string $type = 'info', ?string $relatedType = null, ?int $relatedId = null): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        try {
            $this->userNotificationRepository->createNotification(
                Auth::id(),
                $title,
                $message,
                $type,
                $relatedType,
                $relatedId
            );
            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao criar notificação para usuário atual', [
                'user_id' => Auth::id(),
                'title' => $title,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
    
    /**
     * Cria notificação para conteúdo novo para o usuário atual
     */
    public function createNewContentNotificationForCurrentUser(string $contentType, int $contentId, string $contentTitle): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        try {
            $this->userNotificationRepository->createNewContentNotification(
                Auth::id(),
                $contentType,
                $contentId,
                $contentTitle
            );
            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao criar notificação de conteúdo novo para usuário atual', [
                'user_id' => Auth::id(),
                'content_type' => $contentType,
                'content_id' => $contentId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
    
    /**
     * Obtém notificações formatadas para exibição
     */
    public function getFormattedNotificationsForCurrentUser(int $limit = 10): array
    {
        if (!Auth::check()) {
            return [];
        }
        
        $notifications = $this->getCurrentUserNotificationsForDropdown($limit);
        
        return $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'type' => $notification->type,
                'related_type' => $notification->related_type,
                'related_id' => $notification->related_id,
                'created_at' => $notification->created_at->diffForHumans(),
                'created_at_raw' => $notification->created_at->toISOString(),
                'is_read' => $notification->isRead(),
                'type_icon' => $this->getTypeIcon($notification->type),
                'type_color' => $this->getTypeColor($notification->type),
            ];
        })->toArray();
    }
    
    /**
     * Obtém ícone para o tipo de notificação
     */
    protected function getTypeIcon(string $type): string
    {
        return match ($type) {
            'success' => 'fas fa-check-circle',
            'warning' => 'fas fa-exclamation-triangle',
            'error' => 'fas fa-times-circle',
            default => 'fas fa-info-circle',
        };
    }
    
    /**
     * Obtém cor para o tipo de notificação
     */
    protected function getTypeColor(string $type): string
    {
        return match ($type) {
            'success' => 'text-green-600',
            'warning' => 'text-yellow-600',
            'error' => 'text-red-600',
            default => 'text-blue-600',
        };
    }
}
