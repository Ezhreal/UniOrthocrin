<?php

namespace App\Repositories;

use App\Models\UserView;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class UserViewRepository
{
    protected $model;
    
    public function __construct(UserView $model)
    {
        $this->model = $model;
    }
    
    /**
     * Registra uma visualização
     */
    public function recordView(int $userId, string $viewableType, int $viewableId): UserView
    {
        return UserView::recordView($userId, $viewableType, $viewableId);
    }
    
    /**
     * Registra um download
     */
    public function recordDownload(int $userId, string $viewableType, int $viewableId): bool
    {
        $view = $this->getUserView($userId, $viewableType, $viewableId);
        
        if ($view) {
            $view->recordDownload();
            return true;
        }
        
        return false;
    }
    
    /**
     * Obtém a visualização de um usuário para um item específico
     */
    public function getUserView(int $userId, string $viewableType, int $viewableId): ?UserView
    {
        return UserView::getLastView($userId, $viewableType, $viewableId);
    }
    
    /**
     * Verifica se um usuário já viu um item específico
     */
    public function hasUserViewed(int $userId, string $viewableType, int $viewableId): bool
    {
        return UserView::hasUserViewed($userId, $viewableType, $viewableId);
    }
    
    /**
     * Obtém todas as visualizações de um usuário
     */
    public function getUserViews(int $userId, int $limit = null): Collection
    {
        $query = $this->model->forUser($userId)->orderBy('last_viewed_at', 'desc');
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }
    
    /**
     * Obtém visualizações de um usuário por tipo de conteúdo
     */
    public function getUserViewsByType(int $userId, string $viewableType, int $limit = null): Collection
    {
        $query = $this->model
            ->forUser($userId)
            ->ofType($viewableType)
            ->orderBy('last_viewed_at', 'desc');
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }
    
    /**
     * Obtém visualizações recentes de um usuário
     */
    public function getRecentUserViews(int $userId, int $days = 30, int $limit = null): Collection
    {
        $query = $this->model
            ->forUser($userId)
            ->recent($days)
            ->orderBy('last_viewed_at', 'desc');
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }
    
    /**
     * Obtém estatísticas de visualização para um usuário
     */
    public function getUserViewStats(int $userId): array
    {
        $totalViews = $this->model->forUser($userId)->sum('view_count');
        $totalDownloads = $this->model->forUser($userId)->sum('download_count');
        $uniqueItems = $this->model->forUser($userId)->count();
        
        $viewsByType = $this->model
            ->forUser($userId)
            ->selectRaw('viewable_type, SUM(view_count) as total_views, SUM(download_count) as total_downloads')
            ->groupBy('viewable_type')
            ->get()
            ->keyBy('viewable_type');
        
        return [
            'total_views' => $totalViews,
            'total_downloads' => $totalDownloads,
            'unique_items_viewed' => $uniqueItems,
            'views_by_type' => $viewsByType,
            'last_viewed_at' => $this->model->forUser($userId)->max('last_viewed_at'),
        ];
    }
    
    /**
     * Obtém itens não visualizados por um usuário
     */
    public function getUnviewedItems(int $userId, string $viewableType, array $itemIds): array
    {
        $viewedIds = $this->model
            ->forUser($userId)
            ->ofType($viewableType)
            ->whereIn('viewable_id', $itemIds)
            ->pluck('viewable_id')
            ->toArray();
        
        return array_diff($itemIds, $viewedIds);
    }
    
    /**
     * Obtém itens não visualizados com informações completas
     */
    public function getUnviewedItemsWithDetails(int $userId, string $viewableType, array $items): Collection
    {
        $viewedIds = $this->model
            ->forUser($userId)
            ->ofType($viewableType)
            ->pluck('viewable_id')
            ->toArray();
        
        return collect($items)->filter(function ($item) use ($viewedIds) {
            return !in_array($item['id'], $viewedIds);
        });
    }
    
    /**
     * Obtém itens mais visualizados por tipo
     */
    public function getMostViewedItems(string $viewableType, int $limit = 10): Collection
    {
        return $this->model
            ->ofType($viewableType)
            ->selectRaw('viewable_id, SUM(view_count) as total_views, SUM(download_count) as total_downloads')
            ->groupBy('viewable_id')
            ->orderBy('total_views', 'desc')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Obtém itens mais baixados por tipo
     */
    public function getMostDownloadedItems(string $viewableType, int $limit = 10): Collection
    {
        return $this->model
            ->ofType($viewableType)
            ->selectRaw('viewable_id, SUM(download_count) as total_downloads, SUM(view_count) as total_views')
            ->groupBy('viewable_id')
            ->orderBy('total_downloads', 'desc')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Remove visualizações antigas (mais de X dias)
     */
    public function deleteOldViews(int $days = 365): int
    {
        $oldViews = $this->model
            ->where('last_viewed_at', '<', Carbon::now()->subDays($days))
            ->get();
        
        $count = $oldViews->count();
        
        $oldViews->each(function ($view) {
            $view->delete();
        });
        
        return $count;
    }
}
