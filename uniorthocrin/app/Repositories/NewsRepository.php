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
            ->whereHas('permissions', function($q) use ($user) {
                $q->where('user_type_id', $user->user_type_id)
                  ->where('can_view', true);
            })
            ->latest('published_at')
            ->take($limit)
            ->get();
    }

    public function getAllForUser(User $user)
    {
        return $this->model->published()
            ->with(['category', 'mainFile', 'author'])
            ->whereHas('permissions', function($q) use ($user) {
                $q->where('user_type_id', $user->user_type_id)
                  ->where('can_view', true);
            })
            ->get();
    }

    public function findByIdForUser($id, User $user)
    {
        return $this->model->published()
            ->with(['category', 'mainFile', 'author'])
            ->where('id', $id)
            ->whereHas('permissions', function($q) use ($user) {
                $q->where('user_type_id', $user->user_type_id)
                  ->where('can_view', true);
            })
            ->firstOrFail();
    }

    public function getFilteredForUser(User $user, array $filters = [])
    {
        $query = $this->model->published()
            ->with(['category', 'mainFile', 'author'])
            ->whereHas('permissions', function($q) use ($user) {
                $q->where('user_type_id', $user->user_type_id)
                  ->where('can_view', true);
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
        
        return $query->latest('published_at')->paginate($perPage);
    }
} 