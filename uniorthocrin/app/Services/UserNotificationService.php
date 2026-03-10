<?php

namespace App\Services;

use App\Repositories\UserNotificationRepository;
use App\Models\Notification;
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
     *
     * IMPORTANTE:
     * - Agora usamos o model Notification (notifications_optimized),
     *   que já suporta alvo por perfil (user_type), usuários específicos
     *   e para todos, em vez de uma tabela separada por usuário.
     */
    public function getCurrentUserUnreadNotifications(int $limit = null): Collection
    {
        if (!Auth::check()) {
            return collect();
        }

        $query = Notification::unreadForUser(Auth::id())
            ->orderBy('created_at', 'desc');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }
    
    /**
     * Obtém todas as notificações do usuário atual
     */
    public function getCurrentUserNotifications(int $limit = null, bool $includeRead = true): Collection
    {
        if (!Auth::check()) {
            return collect();
        }

        $query = Notification::forUser(Auth::id())
            ->orderBy('created_at', 'desc');

        if (!$includeRead) {
            $query = Notification::unreadForUser(Auth::id())
                ->orderBy('created_at', 'desc');
        }

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
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

        $query = Notification::forUser(Auth::id())
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->orderBy('created_at', 'desc');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }
    
    /**
     * Marca uma notificação como lida para o usuário atual
     */
    public function markAsRead(int $notificationId): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $notification = Notification::forUser(Auth::id())
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            $notification->markAsReadBy(Auth::id());
            return true;
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

        $userId = Auth::id();

        $notifications = Notification::forUser($userId)
            ->whereIn('id', $notificationIds)
            ->get();

        $count = 0;
        foreach ($notifications as $notification) {
            $notification->markAsReadBy($userId);
            $count++;
        }

        return $count;
    }
    
    /**
     * Marca todas as notificações do usuário atual como lidas
     */
    public function markAllAsRead(): int
    {
        if (!Auth::check()) {
            return 0;
        }

        $userId = Auth::id();
        $notifications = Notification::unreadForUser($userId)->get();
        $count = 0;

        foreach ($notifications as $notification) {
            $notification->markAsReadBy($userId);
            $count++;
        }

        return $count;
    }
    
    /**
     * Marca uma notificação como não lida para o usuário atual
     */
    public function markAsUnread(int $notificationId): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $notification = Notification::forUser(Auth::id())
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            $notification->markAsUnreadBy(Auth::id());
            return true;
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

        // ATENÇÃO: hoje remover notificação no front remove o registro global.
        // Mantemos o comportamento atual para não quebrar fluxos existentes.
        $notification = Notification::forUser(Auth::id())
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            return (bool) $notification->delete();
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

        $userId = Auth::id();

        $notifications = Notification::forUser($userId)
            ->whereIn('id', $notificationIds)
            ->get();

        $ids = $notifications->pluck('id')->all();

        if (empty($ids)) {
            return 0;
        }

        return Notification::whereIn('id', $ids)->delete();
    }
    
    /**
     * Remove notificações lidas antigas do usuário atual
     */
    public function deleteOldReadNotifications(int $daysOld = 30): int
    {
        if (!Auth::check()) {
            return 0;
        }

        $userId = Auth::id();

        $threshold = Carbon::now()->subDays($daysOld);

        $notifications = Notification::forUser($userId)
            ->where('created_at', '<', $threshold)
            ->get();

        $ids = $notifications->pluck('id')->all();

        if (empty($ids)) {
            return 0;
        }

        return Notification::whereIn('id', $ids)->delete();
    }
    
    /**
     * Obtém estatísticas de notificações para o usuário atual
     */
    public function getCurrentUserNotificationStats(): array
    {
        if (!Auth::check()) {
            return [];
        }

        $userId = Auth::id();

        $totalNotifications = Notification::forUser($userId)->count();
        $unreadCount = Notification::unreadForUser($userId)->count();

        // "Lidas" = total - não lidas (já que usamos read_by por usuário)
        $readCount = max(0, $totalNotifications - $unreadCount);

        return [
            'total_notifications' => $totalNotifications,
            'unread_count' => $unreadCount,
            'read_count' => $readCount,
            'notifications_by_type' => collect(), // não utilizado hoje no front
            'last_notification_at' => Notification::forUser($userId)->max('created_at'),
        ];
    }
    
    /**
     * Obtém contagem de notificações não lidas para o usuário atual
     */
    public function getCurrentUserUnreadCount(): int
    {
        if (!Auth::check()) {
            return 0;
        }

        return Notification::unreadCountForUser(Auth::id());
    }
    
    /**
     * Verifica se o usuário atual tem notificações não lidas
     */
    public function hasCurrentUserUnreadNotifications(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return Notification::unreadForUser(Auth::id())->exists();
    }
    
    /**
     * Obtém notificações para o dropdown do usuário atual
     */
    public function getCurrentUserNotificationsForDropdown(int $limit = 10): Collection
    {
        if (!Auth::check()) {
            return collect();
        }

        return Notification::unreadForUser(Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
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

        $userId = Auth::id();
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
                // Em notifications_optimized, "lida" = userId presente em read_by
                'is_read' => $notification->isReadBy(Auth::id()),
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
