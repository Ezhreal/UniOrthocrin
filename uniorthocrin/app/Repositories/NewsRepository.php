<?php

namespace App\Repositories;

use App\Models\News;
use App\Models\User;
use App\Repositories\Interfaces\RepositoryInterface;

class NewsRepository implements RepositoryInterface
{
    private $model;

    public function __construct(News $model)
    {
        $this->model = $model;
    }

    public function getLatestForUser(User $user, $limit = 3)
    {
        return $this->model->published()
            ->with(['category', 'mainFile', 'author'])
            ->where(function ($q) use ($user) {
                $q->whereHas('permissions', function ($permissionQuery) use ($user) {
                    $permissionQuery->where('user_type_id', session('active_profile_id') ?? $user->user_type_id)
                                    ->where('can_view', true);
                })
                ->orWhereDoesntHave('permissions');
            })
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->take($limit)
            ->get();
    }

    public function getAllForUser(User $user)
    {
        return $this->model->published()
            ->with(['category', 'mainFile', 'author'])
            ->where(function ($q) use ($user) {
                $q->whereHas('permissions', function ($permissionQuery) use ($user) {
                    $permissionQuery->where('user_type_id', session('active_profile_id') ?? $user->user_type_id)
                                    ->where('can_view', true);
                })
                ->orWhereDoesntHave('permissions');
            })
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();
    }

    public function findByIdForUser($id, User $user)
    {
        return $this->model->published()
            ->with(['category', 'mainFile', 'author'])
            ->where('id', $id)
            ->where(function ($q) use ($user) {
                $q->whereHas('permissions', function ($permissionQuery) use ($user) {
                    $permissionQuery->where('user_type_id', session('active_profile_id') ?? $user->user_type_id)
                                    ->where('can_view', true);
                })
                ->orWhereDoesntHave('permissions');
            })
            ->firstOrFail();
    }

    public function getFilteredForUser(User $user, array $filters = [])
    {
        $query = $this->model->published()
            ->with(['category', 'mainFile', 'author'])
            ->where(function ($q) use ($user) {
                $q->whereHas('permissions', function ($permissionQuery) use ($user) {
                    $permissionQuery->where('user_type_id', session('active_profile_id') ?? $user->user_type_id)
                                    ->where('can_view', true);
                })
                ->orWhereDoesntHave('permissions');
            });

        // Filtro por categoria
        if (!empty($filters['category_id'])) {
            $query->where('news_category_id', $filters['category_id']);
        }

        // Filtro por busca
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Paginação
        $perPage = $filters['per_page'] ?? 12;
        
        return $query
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate($perPage);
    }
} 