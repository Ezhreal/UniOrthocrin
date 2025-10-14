<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\User;
use App\Repositories\Interfaces\RepositoryInterface;

class ProductRepository implements RepositoryInterface
{
    private $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function getLatestForUser(User $user, $limit = 8)
    {
        return $this->model->active()
            ->with(['category', 'series', 'files'])
            ->whereHas('permissions', function($q) use ($user) {
                $q->where('user_type_id', $user->user_type_id)
                  ->where('can_view', true);
            })
            ->latest('id')
            ->take($limit)
            ->get();
    }

    public function getAllForUser(User $user)
    {
        return $this->model->active()
            ->whereHas('permissions', function($q) use ($user) {
                $q->where('user_type_id', $user->user_type_id)
                  ->where('can_view', true);
            })
            ->get();
    }

    public function findByIdForUser($id, User $user)
    {
        return $this->model->active()
            ->where('id', $id)
            ->whereHas('permissions', function($q) use ($user) {
                $q->where('user_type_id', $user->user_type_id)
                  ->where('can_view', true);
            })
            ->firstOrFail();
    }

    public function getFilteredForUser(User $user, array $filters = [])
    {
        $query = $this->model->active()
            ->with(['category', 'series', 'files'])
            ->whereHas('permissions', function($q) use ($user) {
                $q->where('user_type_id', $user->user_type_id)
                  ->where('can_view', true);
            });

        // Filtro por categoria
        if (!empty($filters['category_id'])) {
            $query->where('product_category_id', $filters['category_id']);
        }

        // Filtro por busca
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Paginação
        $perPage = $filters['per_page'] ?? 12;
        
        return $query->latest('id')->paginate($perPage);
    }

    public function getTotalForUser(User $user)
    {
        return $this->model->active()
            ->whereHas('permissions', function($q) use ($user) {
                $q->where('user_type_id', $user->user_type_id)
                  ->where('can_view', true);
            })
            ->count();
    }

    public function getProductImages($product)
    {
        // Buscar imagens relacionadas ao produto usando a nova estrutura
        $images = $product->images()->get();
        
        return $images->map(function($file) use ($product) {
            return [
                'src' => $file->url,
                'alt' => $product->name . ' - ' . $file->name
            ];
        });
    }

    public function getProductVideos($product)
    {
        // Buscar vídeos relacionados ao produto usando a nova estrutura
        $videos = $product->videos()->get();
        
        return $videos->map(function($file) use ($product) {
            return [
                'id' => $file->id,
                'title' => $product->name . ' - ' . $file->name,
                'video_url' => $file->url,
                'type' => 'video',
                'thumbnail' => $product->thumbnail_path ? url('/' . $product->thumbnail_path) : 'https://placehold.co/600x600?text=Vídeo'
            ];
        });
    }
} 