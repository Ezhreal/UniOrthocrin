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
                $q->where('user_type_id', session('active_profile_id') ?? $user->user_type_id)
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
                $q->where('user_type_id', session('active_profile_id') ?? $user->user_type_id)
                  ->where('can_view', true);
            })
            ->firstOrFail();
    }

    public function getFilteredForUser(User $user, array $filters = [])
    {
        $query = $this->model->active()
            ->with(['category', 'files'])
            ->whereHas('permissions', function($q) use ($user) {
                $q->where('user_type_id', session('active_profile_id') ?? $user->user_type_id)
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

        // Ordem alfabética por nome
        $query->orderBy('name');

        // Paginação
        $perPage = $filters['per_page'] ?? 12;
        
        return $query->paginate($perPage);
    }

    public function getDocumentsByCategory(User $user)
    {
        $categories = $this->model->active()
            ->with(['category', 'files'])
            ->whereHas('permissions', function($q) use ($user) {
                $q->where('user_type_id', session('active_profile_id') ?? $user->user_type_id)
                  ->where('can_view', true);
            })
            ->orderBy('name')
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
            
            // Documentos já vêm ordenados por name; garantir ordem alfabética dentro do grupo
            $documentsSorted = $documents->sortBy('name')->values();
            
            $result[] = [
                'category' => $category,
                'documents' => $documentsSorted
            ];
        }
        
        // Ordenar grupos por nome da categoria
        return collect($result)->sortBy(function ($group) {
            return $group['category']->name ?? '';
        })->values();
    }
} 