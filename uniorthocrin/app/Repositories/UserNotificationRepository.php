<?php

namespace App\Repositories;

use App\Models\UserNotification;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class UserNotificationRepository
{
    protected $model;
    
    public function __construct(UserNotification $model)
    {
        $this->model = $model;
    }
    
    /**
     * Cria uma nova notificação
     */
    public function createNotification(int $userId, string $title, string $message, string $type = 'info', ?string $relatedType = null, ?int $relatedId = null): UserNotification
    {
        return UserNotification::createNotification($userId, $title, $message, $type, $relatedType, $relatedId);
    }
    
    /**
     * Cria notificação para conteúdo novo
     */
    public function createNewContentNotification(int $userId, string $contentType, int $contentId, string $contentTitle): UserNotification
    {
        return UserNotification::createNewContentNotification($userId, $contentType, $contentId, $contentTitle);
    }
    
    /**
     * Obtém notificações não lidas de um usuário
     */
    public function getUnreadNotifications(int $userId, int $limit = null): Collection
    {
        $query = $this->model
            ->forUser($userId)
            ->unread()
            ->orderBy('created_at', 'desc');
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }
    
    /**
     * Obtém todas as notificações de um usuário
     */
    public function getUserNotifications(int $userId, int $limit = null, bool $includeRead = true): Collection
    {
        $query = $this->model
            ->forUser($userId)
            ->orderBy('created_at', 'desc');
        
        if (!$includeRead) {
            $query->unread();
        }
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }
    
    /**
     * Obtém notificações de um usuário por tipo
     */
    public function getUserNotificationsByType(int $userId, string $type, int $limit = null): Collection
    {
        $query = $this->model
            ->forUser($userId)
            ->ofType($type)
            ->orderBy('created_at', 'desc');
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }
    
    /**
     * Obtém notificações relacionadas a um item específico
     */
    public function getNotificationsRelatedTo(int $userId, string $relatedType, int $relatedId): Collection
    {
        return $this->model
            ->forUser($userId)
            ->relatedTo($relatedType, $relatedId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    /**
     * Obtém notificações recentes de um usuário
     */
    public function getRecentNotifications(int $userId, int $days = 30, int $limit = null): Collection
    {
        $query = $this->model
            ->forUser($userId)
            ->recent($days)
            ->orderBy('created_at', 'desc');
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }
    
    /**
     * Marca uma notificação como lida
     */
    public function markAsRead(int $notificationId): bool
    {
        $notification = $this->model->find($notificationId);
        
        if ($notification) {
            $notification->markAsRead();
            return true;
        }
        
        return false;
    }
    
    /**
     * Marca múltiplas notificações como lidas
     */
    public function markMultipleAsRead(array $notificationIds): int
    {
        return $this->model
            ->whereIn('id', $notificationIds)
            ->update(['read_at' => Carbon::now()]);
    }
    
    /**
     * Marca todas as notificações de um usuário como lidas
     */
    public function markAllAsRead(int $userId): int
    {
        return $this->model
            ->forUser($userId)
            ->unread()
            ->update(['read_at' => Carbon::now()]);
    }
    
    /**
     * Marca uma notificação como não lida
     */
    public function markAsUnread(int $notificationId): bool
    {
        $notification = $this->model->find($notificationId);
        
        if ($notification) {
            $notification->markAsUnread();
            return true;
        }
        
        return false;
    }
    
    /**
     * Remove uma notificação
     */
    public function deleteNotification(int $notificationId): bool
    {
        $notification = $this->model->find($notificationId);
        
        if ($notification) {
            return $notification->delete();
        }
        
        return false;
    }
    
    /**
     * Remove múltiplas notificações
     */
    public function deleteMultipleNotifications(array $notificationIds): int
    {
        return $this->model->whereIn('id', $notificationIds)->delete();
    }
    
    /**
     * Remove todas as notificações lidas de um usuário
     */
    public function deleteReadNotifications(int $userId, int $daysOld = 30): int
    {
        return $this->model
            ->forUser($userId)
            ->read()
            ->where('created_at', '<', Carbon::now()->subDays($daysOld))
            ->delete();
    }
    
    /**
     * Obtém estatísticas de notificações para um usuário
     */
    public function getNotificationStats(int $userId): array
    {
        $totalNotifications = $this->model->forUser($userId)->count();
        $unreadCount = $this->model->forUser($userId)->unread()->count();
        $readCount = $this->model->forUser($userId)->read()->count();
        
        $notificationsByType = $this->model
            ->forUser($userId)
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get()
            ->keyBy('type');
        
        return [
            'total_notifications' => $totalNotifications,
            'unread_count' => $unreadCount,
            'read_count' => $readCount,
            'notifications_by_type' => $notificationsByType,
            'last_notification_at' => $this->model->forUser($userId)->max('created_at'),
        ];
    }
    
    /**
     * Obtém contagem de notificações não lidas
     */
    public function getUnreadCount(int $userId): int
    {
        return $this->model
            ->forUser($userId)
            ->unread()
            ->count();
    }
    
    /**
     * Verifica se um usuário tem notificações não lidas
     */
    public function hasUnreadNotifications(int $userId): bool
    {
        return $this->model
            ->forUser($userId)
            ->unread()
            ->exists();
    }
    
    /**
     * Obtém notificações para o dropdown (últimas 10 não lidas)
     */
    public function getNotificationsForDropdown(int $userId, int $limit = 10): Collection
    {
        return $this->model
            ->forUser($userId)
            ->unread()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
