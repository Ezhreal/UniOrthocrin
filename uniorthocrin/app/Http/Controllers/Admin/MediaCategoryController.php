<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MediaCategory;

class MediaCategoryController extends Controller
{
    public function index()
    {
        $categories = MediaCategory::withCount('media')->get();
        
        return view('admin.media-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:media_categories,name',
            'description' => 'nullable|string|max:1000',
        ]);

        MediaCategory::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.media-categories.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    public function destroy(MediaCategory $mediaCategory)
    {
        // Verificar se há itens de mídia associados
        if ($mediaCategory->media()->count() > 0) {
            return response()->json([
                'error' => 'Não é possível excluir uma categoria que possui itens de mídia associados.'
            ], 422);
        }

        $mediaCategory->delete();

        return response()->json([
            'message' => 'Categoria excluída com sucesso!'
        ]);
    }
}
