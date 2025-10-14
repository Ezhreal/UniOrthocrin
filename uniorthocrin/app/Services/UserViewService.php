<?php

namespace App\Services;

use App\Repositories\UserViewRepository;
use App\Repositories\UserNotificationRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class UserViewService
{
    protected $userViewRepository;
    protected $userNotificationRepository;
    
    public function __construct(
        UserViewRepository $userViewRepository,
        UserNotificationRepository $userNotificationRepository
    ) {
        $this->userViewRepository = $userViewRepository;
        $this->userNotificationRepository = $userNotificationRepository;
    }
    
    /**
     * Registra uma visualização do usuário atual
     */
    public function recordView(string $viewableType, int $viewableId): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        $userId = Auth::id();
        
        try {
            $this->userViewRepository->recordView($userId, $viewableType, $viewableId);
            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao registrar visualização', [
                'user_id' => $userId,
                'viewable_type' => $viewableType,
                'viewable_id' => $viewableId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
    
    /**
     * Registra um download do usuário atual
     */
    public function recordDownload(string $viewableType, int $viewableId): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        $userId = Auth::id();
        
        try {
            return $this->userViewRepository->recordDownload($userId, $viewableType, $viewableId);
        } catch (\Exception $e) {
            Log::error('Erro ao registrar download', [
                'user_id' => $userId,
                'viewable_type' => $viewableType,
                'viewable_id' => $viewableId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
    
    /**
     * Verifica se o usuário atual já viu um item específico
     */
    public function hasUserViewed(string $viewableType, int $viewableId): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        return $this->userViewRepository->hasUserViewed(Auth::id(), $viewableType, $viewableId);
    }
    
    /**
     * Obtém estatísticas de visualização para o usuário atual
     */
    public function getCurrentUserViewStats(): array
    {
        if (!Auth::check()) {
            return [];
        }
        
        return $this->userViewRepository->getUserViewStats(Auth::id());
    }
    
    /**
     * Obtém visualizações recentes do usuário atual
     */
    public function getCurrentUserRecentViews(int $days = 30, int $limit = null): Collection
    {
        if (!Auth::check()) {
            return collect();
        }
        
        return $this->userViewRepository->getRecentUserViews(Auth::id(), $days, $limit);
    }
    
    /**
     * Obtém visualizações do usuário atual por tipo
     */
    public function getCurrentUserViewsByType(string $viewableType, int $limit = null): Collection
    {
        if (!Auth::check()) {
            return collect();
        }
        
        return $this->userViewRepository->getUserViewsByType(Auth::id(), $viewableType, $limit);
    }
    
    /**
     * Obtém itens não visualizados pelo usuário atual
     */
    public function getCurrentUserUnviewedItems(string $viewableType, array $itemIds): array
    {
        if (!Auth::check()) {
            return $itemIds; // Se não está logado, todos os itens são "não vistos"
        }
        
        return $this->userViewRepository->getUnviewedItems(Auth::id(), $viewableType, $itemIds);
    }
    
    /**
     * Obtém itens não visualizados com detalhes para o usuário atual
     */
    public function getCurrentUserUnviewedItemsWithDetails(string $viewableType, array $items): Collection
    {
        if (!Auth::check()) {
            return collect($items); // Se não está logado, todos os itens são "não vistos"
        }
        
        return $this->userViewRepository->getUnviewedItemsWithDetails(Auth::id(), $viewableType, $items);
    }
    
    /**
     * Obtém conteúdo novo não visualizado para notificações
     */
    public function getNewContentForNotifications(string $viewableType, array $items, int $limit = 10): Collection
    {
        if (!Auth::check()) {
            return collect($items)->take($limit);
        }
        
        $unviewedItems = $this->getCurrentUserUnviewedItemsWithDetails($viewableType, $items);
        
        // Filtra apenas itens criados nos últimos 7 dias
        $recentItems = $unviewedItems->filter(function ($item) {
            if (isset($item['created_at'])) {
                $createdAt = is_string($item['created_at']) ? Carbon::parse($item['created_at']) : $item['created_at'];
                return $createdAt->isAfter(Carbon::now()->subDays(7));
            }
            return true; // Se não tem data de criação, assume que é novo
        });
        
        return $recentItems->take($limit);
    }
    
    /**
     * Cria notificações para conteúdo novo não visualizado
     */
    public function createNotificationsForNewContent(string $viewableType, array $items): int
    {
        if (!Auth::check()) {
            return 0;
        }
        
        $newContent = $this->getNewContentForNotifications($viewableType, $items, 10);
        $notificationsCreated = 0;
        
        foreach ($newContent as $item) {
            try {
                $title = $item['title'] ?? $item['name'] ?? 'Novo conteúdo disponível';
                $this->userNotificationRepository->createNewContentNotification(
                    Auth::id(),
                    $viewableType,
                    $item['id'],
                    $title
                );
                $notificationsCreated++;
            } catch (\Exception $e) {
                Log::error('Erro ao criar notificação para conteúdo novo', [
                    'user_id' => Auth::id(),
                    'viewable_type' => $viewableType,
                    'item_id' => $item['id'] ?? null,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        return $notificationsCreated;
    }
    
    /**
     * Obtém histórico de visualizações do usuário atual
     */
    public function getCurrentUserViewHistory(int $limit = 50): Collection
    {
        if (!Auth::check()) {
            return collect();
        }
        
        return $this->userViewRepository->getUserViews(Auth::id(), $limit);
    }
    
    /**
     * Obtém itens mais visualizados por tipo
     */
    public function getMostViewedItems(string $viewableType, int $limit = 10): Collection
    {
        return $this->userViewRepository->getMostViewedItems($viewableType, $limit);
    }
    
    /**
     * Obtém itens mais baixados por tipo
     */
    public function getMostDownloadedItems(string $viewableType, int $limit = 10): Collection
    {
        return $this->userViewRepository->getMostDownloadedItems($viewableType, $limit);
    }
    
    /**
     * Obtém relatório de atividade do usuário atual
     */
    public function getCurrentUserActivityReport(int $days = 30): array
    {
        if (!Auth::check()) {
            return [];
        }
        
        $userId = Auth::id();
        $recentViews = $this->userViewRepository->getRecentUserViews($userId, $days);
        
        $activityByDay = $recentViews->groupBy(function ($view) {
            return $view->last_viewed_at->format('Y-m-d');
        })->map(function ($views) {
            return [
                'views' => $views->sum('view_count'),
                'downloads' => $views->sum('download_count'),
                'unique_items' => $views->count(),
            ];
        });
        
        $activityByType = $recentViews->groupBy('viewable_type')->map(function ($views) {
            return [
                'views' => $views->sum('view_count'),
                'downloads' => $views->sum('download_count'),
                'unique_items' => $views->count(),
            ];
        });
        
        return [
            'period_days' => $days,
            'total_views' => $recentViews->sum('view_count'),
            'total_downloads' => $recentViews->sum('download_count'),
            'unique_items_viewed' => $recentViews->count(),
            'activity_by_day' => $activityByDay,
            'activity_by_type' => $activityByType,
            'most_viewed_items' => $this->getMostViewedItems('App\Models\Campaign', 5),
            'most_downloaded_items' => $this->getMostDownloadedItems('App\Models\Campaign', 5),
        ];
    }
    
    /**
     * Limpa visualizações antigas do usuário atual
     */
    public function cleanupOldViews(int $days = 365): int
    {
        if (!Auth::check()) {
            return 0;
        }
        
        // Nota: Esta operação afeta todos os usuários, não apenas o atual
        // Em produção, considerar implementar limpeza por usuário específico
        return $this->userViewRepository->deleteOldViews($days);
    }
}
