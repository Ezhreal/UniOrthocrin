<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrainingCategory;

class TrainingCategoryController extends Controller
{
    public function index()
    {
        $categories = TrainingCategory::withCount('trainings')->get();
        
        return view('admin.training-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:training_categories,name',
            'description' => 'nullable|string|max:1000',
        ]);

        TrainingCategory::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.training-categories.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    public function destroy(TrainingCategory $trainingCategory)
    {
        // Verificar se há treinamentos associados
        if ($trainingCategory->trainings()->count() > 0) {
            return response()->json([
                'error' => 'Não é possível excluir uma categoria que possui treinamentos associados.'
            ], 422);
        }

        $trainingCategory->delete();

        return response()->json([
            'message' => 'Categoria excluída com sucesso!'
        ]);
    }
}
