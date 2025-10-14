<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsCategory;

class NewsCategoryController extends Controller
{
    public function index()
    {
        $categories = NewsCategory::withCount('news')->get();
        
        return view('admin.news-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:news_categories,name',
            'description' => 'nullable|string|max:1000',
        ]);

        NewsCategory::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.news-categories.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    public function destroy(NewsCategory $newsCategory)
    {
        // Verificar se há notícias associadas
        if ($newsCategory->news()->count() > 0) {
            return response()->json([
                'error' => 'Não é possível excluir uma categoria que possui notícias associadas.'
            ], 422);
        }

        $newsCategory->delete();

        return response()->json([
            'message' => 'Categoria excluída com sucesso!'
        ]);
    }
}
