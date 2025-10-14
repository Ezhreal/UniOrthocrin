<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\ProductCategoryRepository;

class ProductCategoryService
{
    private $productCategoryRepository;

    public function __construct(ProductCategoryRepository $productCategoryRepository)
    {
        $this->productCategoryRepository = $productCategoryRepository;
    }

    public function getCategoriesWithProductCount(User $user)
    {
        return $this->productCategoryRepository->getCategoriesWithProductCount($user);
    }

    public function getAllCategories(User $user)
    {
        return $this->productCategoryRepository->getAllCategories($user);
    }
}
