<?php

namespace App\Repositories;

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
        return $this->model->active()
            ->with(['category', 'files'])
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
            ->with(['category', 'files'])
            ->whereHas('permissions', function($q) use ($user) {
                $q->where('user_type_id', $user->user_type_id)
                  ->where('can_view', true);
            })
            ->get();
    }

    public function findByIdForUser($id, User $user)
    {
        return $this->model->active()
            ->with(['category', 'files'])
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
            ->with(['category', 'files'])
            ->whereHas('permissions', function($q) use ($user) {
                $q->where('user_type_id', $user->user_type_id)
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
        // Buscar vídeos relacionados ao treinamento
        $videos = $training->files()->where('type', 'video')->get();
        
        return $videos->map(function($file) use ($training) {
            return [
                'id' => $file->id,
                'title' => $training->name . ' - ' . $file->name,
                'video_url' => $file->url,
                'type' => 'video',
                'thumbnail' => $file->thumbnail_url ?? 'https://placehold.co/600x600?text=Vídeo'
            ];
        });
    }

    public function getTrainingPdfs($training)
    {
        // Buscar PDFs relacionados ao treinamento
        $pdfs = $training->files()->where('type', 'pdf')->get();
        
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