<?php

namespace App\Repositories;

use App\Helpers\VideoUrlHelper;
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
                $q->where('user_type_id', session('active_profile_id') ?? $user->user_type_id)
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
                $q->where('user_type_id', session('active_profile_id') ?? $user->user_type_id)
                  ->where('can_view', true);
            })
            ->get();
    }

    public function findByIdForUser($id, User $user)
    {
        return $this->model->active()
            ->where('id', $id)
            ->whereHas('permissions', function($q) use ($user) {
                $q->where('user_type_id', session('active_profile_id') ?? $user->user_type_id)
                  ->where('can_view', true);
            })
            ->firstOrFail();
    }

    public function getFilteredForUser(User $user, array $filters = [])
    {
        $query = $this->model->active()
            ->with(['category', 'series', 'files'])
            ->whereHas('permissions', function($q) use ($user) {
                $q->where('user_type_id', session('active_profile_id') ?? $user->user_type_id)
                  ->where('can_view', true);
            });

        // Filtro por categoria
        $categoryId = $filters['category_id'] ?? $filters['product_category_id'] ?? null;
        if (!empty($categoryId)) {
            $query->where('product_category_id', $categoryId);
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
                $q->where('user_type_id', session('active_profile_id') ?? $user->user_type_id)
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
                'src' => $file->optimized_url,
                'srcset' => $file->srcset,
                'alt' => $product->name . ' - ' . $file->name
            ];
        });
    }

    public function getProductVideos($product)
    {
        $result = collect();

        // --- Vídeo via URL externa (YouTube / Vimeo) ---
        if ($product->video_source === 'url' && !empty($product->video_url)) {
            $embedUrl = VideoUrlHelper::toEmbedUrl($product->video_url);
            $result->push([
                'id'           => 'ext_' . $product->id,
                'title'        => $product->name,
                'file_name'    => null,
                'video_url'    => $product->video_url,
                'embed_url'    => $embedUrl,
                'video_source' => 'url',
                'type'         => 'video',
                'thumbnail'    => VideoUrlHelper::getThumbnailUrl($product->video_url)
                                   ?? ($product->thumbnail_path ? url('/' . $product->thumbnail_path) : 'https://placehold.co/600x600?text=Vídeo'),
            ]);
        }

        // --- Vídeos por upload (tabela files) ---
        $videos = $product->videos()->get();
        $videos->each(function ($file) use ($product, &$result) {
            $result->push([
                'id'           => $file->id,
                'title'        => $product->name . ' - ' . $file->name,
                'file_name'    => $file->name,
                'video_url'    => $file->url,
                'embed_url'    => null,
                'video_source' => 'upload',
                'type'         => 'video',
                'thumbnail'    => $product->thumbnail_path
                                    ? url('/' . $product->thumbnail_path)
                                    : 'https://placehold.co/600x600?text=Vídeo',
            ]);
        });

        return $result;
    }
} 