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
        
        $globalFilterCategoryId = $request->get('category');
        $globalSearch = $request->get('search');
        
        $groupedProducts = [];

        // 1. Obter todas as categorias de produtos visíveis para o usuário.
        // Assumindo que getCategoriesWithProductCount retorna todas as categorias relevantes para o usuário.
        $allCategories = $this->productCategoryService->getCategoriesWithProductCount($user);

        $categoriesToProcess = collect();
        if ($globalFilterCategoryId) {
            $singleCategory = $allCategories->firstWhere('id', $globalFilterCategoryId);
            if ($singleCategory) {
                $categoriesToProcess->push($singleCategory);
            }
        } else {
            // Nenhum filtro de categoria global, processa todas as categorias
            $categoriesToProcess = $allCategories;
        }

        foreach ($categoriesToProcess as $category) {
            // Para cada categoria, obter seus produtos, aplicando a busca global, se houver
            $productsInThisCategory = $this->productService->getFilteredProducts($user, [
                'product_category_id' => $category->id, // Filtra pelos produtos da categoria atual no loop
                'search' => $globalSearch,
                'per_page' => null, // Não paginar, buscar todos os produtos para esta categoria
                'eager_load' => ['category', 'series', 'images']
            ]);

            // Adicionar a categoria aos produtos agrupados apenas se ela realmente tiver produtos após o filtro de busca
            if ($productsInThisCategory->isNotEmpty()) {
                $seriesInThisCategory = $productsInThisCategory
                    ->pluck('series')
                    ->filter()
                    ->unique('id');

                $groupedProducts[] = [
                    'category' => $category,
                    'products' => $productsInThisCategory,
                    'series' => $seriesInThisCategory,
                ];
            }
        }
        
        // $categories é mantido para compatibilidade, caso seja usado em outras partes do layout ou em lógicas futuras.
        $categories = $this->productCategoryService->getCategoriesWithProductCount($user);

        return view('produtos-list', compact(
            'groupedProducts',
            'categories',
            'globalFilterCategoryId',
            'globalSearch'
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