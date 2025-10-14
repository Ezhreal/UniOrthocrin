<?php

namespace App\Repositories;

use App\Models\ProductCategory;
use App\Models\User;

class ProductCategoryRepository
{
    private $model;

    public function __construct(ProductCategory $model)
    {
        $this->model = $model;
    }

    public function getCategoriesWithProductCount(User $user)
    {
        return $this->model->withCount(['products' => function($query) use ($user) {
            $query->whereHas('permissions', function($q) use ($user) {
                $q->where('user_type_id', $user->user_type_id)
                  ->where('can_view', true);
            });
        }])
        ->whereHas('products.permissions', function($query) use ($user) {
            $query->where('user_type_id', $user->user_type_id)
                  ->where('can_view', true);
        })
        ->orderBy('name')
        ->get();
    }

    public function getAllCategories(User $user)
    {
        return $this->model->whereHas('products.permissions', function($query) use ($user) {
            $query->where('user_type_id', $user->user_type_id)
                  ->where('can_view', true);
        })
        ->orderBy('name')
        ->get();
    }
}
