<?php

namespace App\Repositories;

use App\Models\Library;
use App\Models\User;
use App\Repositories\Interfaces\RepositoryInterface;

class LibraryRepository implements RepositoryInterface
{
    private $model;

    public function __construct(Library $model)
    {
        $this->model = $model;
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
            $query->where('library_category_id', $filters['category_id']);
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

    public function getDocumentsByCategory(User $user)
    {
        $categories = $this->model->active()
            ->with(['category', 'files'])
            ->whereHas('permissions', function($q) use ($user) {
                $q->where('user_type_id', $user->user_type_id)
                  ->where('can_view', true);
            })
            ->get()
            ->groupBy('library_category_id');

        $result = [];
        foreach ($categories as $categoryId => $documents) {
            $firstDocument = $documents->first();
            $category = $firstDocument->category ?? null;
            
            // Se não há categoria, criar uma categoria padrão
            if (!$category) {
                $category = (object) [
                    'id' => 0,
                    'name' => 'Sem Categoria',
                    'description' => 'Documentos sem categoria específica'
                ];
            }
            
            $result[] = [
                'category' => $category,
                'documents' => $documents
            ];
        }

        return collect($result);
    }
} 