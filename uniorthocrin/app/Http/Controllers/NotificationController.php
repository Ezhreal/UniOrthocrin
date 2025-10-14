<?php

namespace App\Http\Controllers;

use App\Services\UserNotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;
    
    public function __construct(UserNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    
    /**
     * Obtém notificações para o dropdown
     */
    public function getDropdownNotifications(): JsonResponse
    {
        try {
            $notifications = $this->notificationService->getFormattedNotificationsForCurrentUser(10);
            $unreadCount = $this->notificationService->getCurrentUserUnreadCount();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'notifications' => $notifications,
                    'unread_count' => $unreadCount,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar notificações',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Obtém todas as notificações do usuário
     */
    public function getUserNotifications(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 20);
            $includeRead = $request->get('include_read', true);
            
            $notifications = $this->notificationService->getCurrentUserNotifications($limit, $includeRead);
            
            return response()->json([
                'success' => true,
                'data' => $notifications,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar notificações',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Marca uma notificação como lida
     */
    public function markAsRead(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'notification_id' => 'required|integer',
            ]);
            
            $notificationId = $request->input('notification_id');
            $success = $this->notificationService->markAsRead($notificationId);
            
            if ($success) {
                $unreadCount = $this->notificationService->getCurrentUserUnreadCount();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Notificação marcada como lida',
                    'data' => [
                        'unread_count' => $unreadCount,
                    ],
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Notificação não encontrada ou não pertence ao usuário',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao marcar notificação como lida',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Marca múltiplas notificações como lidas
     */
    public function markMultipleAsRead(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'notification_ids' => 'required|array',
                'notification_ids.*' => 'integer',
            ]);
            
            $notificationIds = $request->input('notification_ids');
            $markedCount = $this->notificationService->markMultipleAsRead($notificationIds);
            
            $unreadCount = $this->notificationService->getCurrentUserUnreadCount();
            
            return response()->json([
                'success' => true,
                'message' => "{$markedCount} notificação(ões) marcada(s) como lida(s)",
                'data' => [
                    'marked_count' => $markedCount,
                    'unread_count' => $unreadCount,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao marcar notificações como lidas',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Marca todas as notificações como lidas
     */
    public function markAllAsRead(): JsonResponse
    {
        try {
            $markedCount = $this->notificationService->markAllAsRead();
            
            return response()->json([
                'success' => true,
                'message' => "Todas as notificações foram marcadas como lidas",
                'data' => [
                    'marked_count' => $markedCount,
                    'unread_count' => 0,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao marcar todas as notificações como lidas',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Remove uma notificação
     */
    public function deleteNotification(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'notification_id' => 'required|integer',
            ]);
            
            $notificationId = $request->input('notification_id');
            $success = $this->notificationService->deleteNotification($notificationId);
            
            if ($success) {
                $unreadCount = $this->notificationService->getCurrentUserUnreadCount();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Notificação removida com sucesso',
                    'data' => [
                        'unread_count' => $unreadCount,
                    ],
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Notificação não encontrada ou não pertence ao usuário',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover notificação',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Remove múltiplas notificações
     */
    public function deleteMultipleNotifications(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'notification_ids' => 'required|array',
                'notification_ids.*' => 'integer',
            ]);
            
            $notificationIds = $request->input('notification_ids');
            $deletedCount = $this->notificationService->deleteMultipleNotifications($notificationIds);
            
            $unreadCount = $this->notificationService->getCurrentUserUnreadCount();
            
            return response()->json([
                'success' => true,
                'message' => "{$deletedCount} notificação(ões) removida(s)",
                'data' => [
                    'deleted_count' => $deletedCount,
                    'unread_count' => $unreadCount,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover notificações',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Obtém estatísticas de notificações
     */
    public function getNotificationStats(): JsonResponse
    {
        try {
            $stats = $this->notificationService->getCurrentUserNotificationStats();
            
            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar estatísticas',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Obtém contagem de notificações não lidas
     */
    public function getUnreadCount(): JsonResponse
    {
        try {
            $unreadCount = $this->notificationService->getCurrentUserUnreadCount();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'unread_count' => $unreadCount,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar contagem de notificações',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
} 