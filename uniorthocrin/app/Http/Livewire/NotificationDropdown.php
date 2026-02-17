<?php

namespace App\Http\Livewire;

use App\Services\UserNotificationService;
use Livewire\Component;
use Livewire\Attributes\On;

class NotificationDropdown extends Component
{
    public $notifications = [];
    public $unreadCount = 0;
    public $isOpen = false;
    public $isLoading = false;
    
    protected $notificationService;
    
    public function boot(UserNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    
    public function mount()
    {
        $this->loadNotifications();
    }
    
    public function render()
    {
        return view('livewire.notification-dropdown');
    }
    
    /**
     * Carrega as notificações
     */
    public function loadNotifications()
    {
        if (!auth()->check()) {
            return;
        }
        
        $this->isLoading = true;
        
        try {
            $this->notifications = $this->notificationService->getFormattedNotificationsForCurrentUser(10);
            $this->unreadCount = $this->notificationService->getCurrentUserUnreadCount();
        } catch (\Exception $e) {
            $this->notifications = [];
            $this->unreadCount = 0;
        }
        
        $this->isLoading = false;
    }
    
    /**
     * Abre/fecha o dropdown
     */
    public function toggleDropdown()
    {
        $this->isOpen = !$this->isOpen;
        
        if ($this->isOpen) {
            $this->loadNotifications();
        }
    }
    
    /**
     * Marca uma notificação como lida
     */
    public function markAsRead($notificationId)
    {
        if (!auth()->check()) {
            return;
        }
        
        $notificationId = (int) $notificationId;
        
        try {
            $success = $this->notificationService->markAsRead($notificationId);
            
            if ($success) {
                // Atualiza a notificação local
                $this->notifications = collect($this->notifications)->map(function ($notification) use ($notificationId) {
                    if ($notification['id'] == $notificationId) {
                        $notification['is_read'] = true;
                    }
                    return $notification;
                })->toArray();
                
                // Atualiza contador
                $this->unreadCount = $this->notificationService->getCurrentUserUnreadCount();
                
                $this->dispatch('notification-read', [
                    'notification_id' => $notificationId,
                    'unread_count' => $this->unreadCount
                ]);
            }
        } catch (\Exception $e) {
            // Em caso de erro, apenas loga
        }
    }
    
    /**
     * Marca todas as notificações como lidas
     */
    public function markAllAsRead()
    {
        if (!auth()->check()) {
            return;
        }
        
        try {
            $markedCount = $this->notificationService->markAllAsRead();
            
            if ($markedCount > 0) {
                // Marca todas como lidas localmente
                $this->notifications = collect($this->notifications)->map(function ($notification) {
                    $notification['is_read'] = true;
                    return $notification;
                })->toArray();
                
                // Atualiza contador
                $this->unreadCount = 0;
                
                $this->dispatch('all-notifications-read', [
                    'marked_count' => $markedCount
                ]);
            }
        } catch (\Exception $e) {
            // Em caso de erro, apenas loga
        }
    }
    
    /**
     * Remove uma notificação
     */
    public function deleteNotification($notificationId)
    {
        if (!auth()->check()) {
            return;
        }
        
        try {
            $success = $this->notificationService->deleteNotification($notificationId);
            
            if ($success) {
                // Remove a notificação localmente
                $this->notifications = collect($this->notifications)
                    ->filter(function ($notification) use ($notificationId) {
                        return $notification['id'] != $notificationId;
                    })
                    ->toArray();
                
                // Atualiza contador
                $this->unreadCount = $this->notificationService->getCurrentUserUnreadCount();
                
                $this->dispatch('notification-deleted', [
                    'notification_id' => $notificationId,
                    'unread_count' => $this->unreadCount
                ]);
            }
        } catch (\Exception $e) {
            // Em caso de erro, apenas loga
        }
    }
    
    /**
     * Atualiza notificações quando recebe evento
     */
    #[On('notifications-updated')]
    public function refreshNotifications()
    {
        $this->loadNotifications();
    }
    
    /**
     * Navega para o item relacionado à notificação (recebe apenas o ID para evitar quebra no HTML)
     */
    public function navigateToRelated($notificationId)
    {
        $notificationId = (int) $notificationId;
        $notification = collect($this->notifications)->firstWhere('id', $notificationId);
        
        if (!$notification || empty($notification['related_type']) || empty($notification['related_id'])) {
            return;
        }
        
        // Marca como lida primeiro
        $this->markAsRead($notificationId);
        
        $route = $this->getRouteForType($notification['related_type'], $notification['related_id']);
        
        if ($route) {
            return $this->redirect($route);
        }
    }
    
    /**
     * URL para o conteúdo relacionado (uso na view para link direto)
     */
    public function getRelatedUrl(array $notification): ?string
    {
        $type = $notification['related_type'] ?? null;
        $id = $notification['related_id'] ?? null;
        if (!$type || !$id) {
            return null;
        }
        return $this->getRouteForType($type, $id);
    }

    /**
     * Obtém a rota para o tipo de conteúdo
     */
    protected function getRouteForType($type, $id)
    {
        return match ($type) {
            'App\Models\Campaign' => "/marketing/{$id}",
            'App\Models\Product' => "/produtos/{$id}",
            'App\Models\Training' => "/treinamentos/{$id}",
            'App\Models\Library' => "/biblioteca/{$id}",
            'App\Models\News' => "/news/{$id}",
            default => null,
        };
    }
    
    /**
     * Obtém o ícone para o tipo de notificação
     */
    public function getTypeIcon($type)
    {
        return match ($type) {
            'success' => 'fas fa-check-circle',
            'warning' => 'fas fa-exclamation-triangle',
            'error' => 'fas fa-times-circle',
            default => 'fas fa-info-circle',
        };
    }
    
    /**
     * Obtém a cor para o tipo de notificação
     */
    public function getTypeColor($type)
    {
        return match ($type) {
            'success' => 'text-green-600',
            'warning' => 'text-yellow-600',
            'error' => 'text-red-600',
            default => 'text-blue-600',
        };
    }
}
