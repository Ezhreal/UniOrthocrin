<?php

namespace App\Repositories;

use App\Helpers\VideoUrlHelper;
use App\Models\Training;
use App\Models\User;
use App\Repositories\Interfaces\RepositoryInterface;

class TrainingRepository implements RepositoryInterface
{
    private $model;

    public function __construct(Training $model)
    {
        $this->model = $model;
    }

    public function getLatestForUser(User $user, $limit = 3)
    {
        $userTypeId = session('active_profile_id') ?? $user->user_type_id;
        
        return $this->model->active()
            ->with(['category', 'files'])
            ->whereHas('permissions', function($q) use ($userTypeId) {
                $q->where('user_type_id', $userTypeId)
                  ->where('can_view', true);
            })
            ->latest('id')
            ->take($limit)
            ->get();
    }

    public function getAllForUser(User $user)
    {
        $userTypeId = session('active_profile_id') ?? $user->user_type_id;

        return $this->model->active()
            ->with(['category', 'files'])
            ->whereHas('permissions', function($q) use ($userTypeId) {
                $q->where('user_type_id', $userTypeId)
                  ->where('can_view', true);
            })
            ->get();
    }

    public function findByIdForUser($id, User $user)
    {
        $userTypeId = session('active_profile_id') ?? $user->user_type_id;

        return $this->model->active()
            ->with(['category', 'files'])
            ->where('id', $id)
            ->whereHas('permissions', function($q) use ($userTypeId) {
                $q->where('user_type_id', $userTypeId)
                  ->where('can_view', true);
            })
            ->firstOrFail();
    }

    public function getFilteredForUser(User $user, array $filters = [])
    {
        $userTypeId = session('active_profile_id') ?? $user->user_type_id;

        $query = $this->model->active()
            ->with(['category', 'files'])
            ->whereHas('permissions', function($q) use ($userTypeId) {
                $q->where('user_type_id', $userTypeId)
                  ->where('can_view', true);
            });

        // Filtro por categoria
        if (!empty($filters['category_id'])) {
            $query->where('training_category_id', $filters['category_id']);
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

    public function getTrainingVideos($training)
    {
        $result = collect();

        // --- Vídeo via URL externa (YouTube / Vimeo) ---
        if ($training->video_source === 'url' && !empty($training->video_url)) {
            $embedUrl = VideoUrlHelper::toEmbedUrl($training->video_url);
            $result->push([
                'id'           => 'ext_' . $training->id,
                'title'        => $training->name,
                'file_name'    => null,
                'video_url'    => $training->video_url,
                'embed_url'    => $embedUrl,
                'video_source' => 'url',
                'type'         => 'video',
                'thumbnail'    => VideoUrlHelper::getThumbnailUrl($training->video_url)
                                   ?? ($training->thumbnail_path ? url('/' . $training->thumbnail_path) : 'https://placehold.co/600x600?text=Vídeo'),
            ]);
        }

        // --- Vídeos por upload (tabela files) ---
        $videos = $training->videos()->get();
        $videos->each(function ($file) use ($training, &$result) {
            $result->push([
                'id'           => $file->id,
                'title'        => $training->name . ' - ' . $file->name,
                'file_name'    => $file->name,
                'video_url'    => $file->url,
                'embed_url'    => null,
                'video_source' => 'upload',
                'type'         => 'video',
                'thumbnail'    => $file->thumbnail_url ?? 'https://placehold.co/600x600?text=Vídeo',
            ]);
        });

        return $result;
    }

    public function getTrainingPdfs($training)
    {
        // Buscar PDFs relacionados ao treinamento
        $pdfs = $training->pdfs()->get();
        
        return $pdfs->map(function($file) use ($training) {
            return [
                'id' => $file->id,
                'title' => $training->name . ' - ' . $file->name,
                'pdf_url' => $file->url,
                'type' => 'pdf',
                'filename' => $file->name
            ];
        });
    }

    public function getTrainingsByCategory(User $user)
    {
        $categories = $this->model->active()
            ->with(['category', 'files'])
            ->whereHas('permissions', function($q) use ($user) {
                $q->where('user_type_id', $user->user_type_id)
                  ->where('can_view', true);
            })
            ->get()
            ->groupBy('training_category_id');

        $result = [];
        foreach ($categories as $categoryId => $trainings) {
            $category = $trainings->first()->category;
            $result[] = [
                'category' => $category ?: (object)['name' => 'Sem Categoria'],
                'trainings' => $trainings
            ];
        }

        return collect($result);
    }
} 