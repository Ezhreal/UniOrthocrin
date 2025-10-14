<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TrainingService;

class TrainingController extends Controller
{
    private $trainingService;

    public function __construct(TrainingService $trainingService)
    {
        $this->trainingService = $trainingService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $filters = $request->only(['search', 'category_id']);
        
        if ($request->has('search') || $request->has('category_id')) {
            // Se há filtros, usa paginação normal
            $trainings = $this->trainingService->getFilteredTrainings($user, $filters);
            return view('treinamentos-list', compact('trainings'));
        } else {
            // Se não há filtros, agrupa por categoria
            $trainingsByCategory = $this->trainingService->getTrainingsByCategory($user);
            return view('treinamentos-list', compact('trainingsByCategory'));
        }
    }

    public function show($id, Request $request)
    {
        $user = $request->user();
        $training = $this->trainingService->getTrainingById($id, $user);
        return view('treinamentos-detail', compact('training'));
    }
} 