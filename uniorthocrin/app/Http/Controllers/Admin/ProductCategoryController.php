<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::withCount('products')->get();
        
        return view('admin.product-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name',
            'description' => 'nullable|string|max:1000',
        ]);

        ProductCategory::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    public function destroy(ProductCategory $productCategory)
    {
        // Verificar se há produtos associados
        if ($productCategory->products()->count() > 0) {
            return response()->json([
                'error' => 'Não é possível excluir uma categoria que possui produtos associados.'
            ], 422);
        }

        $productCategory->delete();

        return response()->json([
            'message' => 'Categoria excluída com sucesso!'
        ]);
    }
}
