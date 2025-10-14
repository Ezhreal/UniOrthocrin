<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LibraryCategory;

class LibraryCategoryController extends Controller
{
    public function index()
    {
        $categories = LibraryCategory::withCount('library')->get();
        
        return view('admin.library-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:library_categories,name',
            'description' => 'nullable|string|max:1000',
        ]);

        LibraryCategory::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.library-categories.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    public function destroy(LibraryCategory $libraryCategory)
    {
        // Verificar se há arquivos da biblioteca associados
        if ($libraryCategory->library()->count() > 0) {
            return response()->json([
                'error' => 'Não é possível excluir uma categoria que possui arquivos associados.'
            ], 422);
        }

        $libraryCategory->delete();

        return response()->json([
            'message' => 'Categoria excluída com sucesso!'
        ]);
    }
}
