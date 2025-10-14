<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\ProductRepository;

class ProductService
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getLatestProducts(User $user, $limit = 8)
    {
        return $this->productRepository->getLatestForUser($user, $limit);
    }

    public function getAllProducts(User $user)
    {
        return $this->productRepository->getAllForUser($user);
    }

    public function getProductById($id, User $user)
    {
        return $this->productRepository->findByIdForUser($id, $user);
    }

    public function getFilteredProducts(User $user, array $filters = [])
    {
        return $this->productRepository->getFilteredForUser($user, $filters);
    }

    public function getTotalProducts(User $user)
    {
        return $this->productRepository->getTotalForUser($user);
    }

    public function getProductImages($product)
    {
        return $this->productRepository->getProductImages($product);
    }

    public function getProductVideos($product)
    {
        return $this->productRepository->getProductVideos($product);
    }
} 