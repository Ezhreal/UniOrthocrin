<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductSeries;
use App\Models\ProductCategory;

class ProductSeriesController extends Controller
{
    public function index()
    {
        $series = ProductSeries::with(['category', 'products'])->withCount('products')->get();
        $categories = ProductCategory::all();
        
        return view('admin.product-series.index', compact('series', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        // Verificar se já existe uma série com o mesmo nome na categoria
        $existingSeries = ProductSeries::where('category_id', $request->category_id)
            ->where('name', $request->name)
            ->first();

        if ($existingSeries) {
            return back()->withErrors([
                'name' => 'Já existe uma série com este nome nesta categoria.'
            ])->withInput();
        }

        ProductSeries::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.product-series.index')
            ->with('success', 'Série criada com sucesso!');
    }

    public function destroy(ProductSeries $productSeries)
    {
        // Verificar se há produtos associados
        if ($productSeries->products()->count() > 0) {
            return response()->json([
                'error' => 'Não é possível excluir uma série que possui produtos associados.'
            ], 422);
        }

        $productSeries->delete();

        return response()->json([
            'message' => 'Série excluída com sucesso!'
        ]);
    }
}
