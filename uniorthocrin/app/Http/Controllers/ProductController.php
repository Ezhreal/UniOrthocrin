<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Services\ProductCategoryService;

class ProductController extends Controller
{
    private $productService;
    private $productCategoryService;

    public function __construct(ProductService $productService, ProductCategoryService $productCategoryService)
    {
        $this->productService = $productService;
        $this->productCategoryService = $productCategoryService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        
        // Parâmetros de filtro
        $categoryId = $request->get('category');
        $search = $request->get('search');
        $perPage = 12; // Produtos por página
        
        // Buscar categorias com contagem de produtos
        $categories = $this->productCategoryService->getCategoriesWithProductCount($user);
        
        // Buscar produtos filtrados
        $products = $this->productService->getFilteredProducts($user, [
            'product_category_id' => $categoryId,
            'search' => $search,
            'per_page' => $perPage
        ]);
        
        // Estatísticas
        $totalProducts = $this->productService->getTotalProducts($user);
        $filteredCount = $products->count();
        
        return view('produtos-list', compact(
            'products',
            'categories',
            'totalProducts',
            'filteredCount',
            'categoryId',
            'search'
        ));
    }

    public function show($id, Request $request)
    {
        $user = $request->user();
        $product = $this->productService->getProductById($id, $user);
        
        if (!$product) {
            abort(404);
        }
        
        // Buscar imagens e vídeos relacionados ao produto
        $images = $this->productService->getProductImages($product);
        $videos = $this->productService->getProductVideos($product);
        
        return view('produtos-detail', compact('product', 'images', 'videos'));
    }
} 