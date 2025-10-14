<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Services\NewsService;
use App\Services\TrainingService;
use App\Models\Campaign;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Garantir que todas as variáveis sejam passadas
        $produtos = collect();
        $news = collect();
        $treinamentos = collect();
        $notificacoes = collect();
        $featuredCampaigns = collect();
        
        try {
            $produtos = app(ProductService::class)->getLatestProducts($user) ?? collect();
        } catch (\Exception $e) {
            // Ignora erro
        }
        
        try {
            $news = app(NewsService::class)->getRadarNews($user) ?? collect();
        } catch (\Exception $e) {
            // Ignora erro
        }
        
        try {
            $treinamentos = app(TrainingService::class)->getNewTrainings($user) ?? collect();
        } catch (\Exception $e) {
            // Ignora erro
        }
        
        try {
            $notificacoes = \App\Models\Notification::forUser($user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $notificacoes = collect();
        }
        
        // Campanhas em destaque (banner) - apenas para Admin (ID 1) e Franqueado (ID 2)
        try {
            if (in_array($user->user_type_id, [1, 2])) {
                $featuredCampaigns = Campaign::query()
                    ->where('status', 'active')
                    ->where('is_featured', true)
                    ->whereNotNull('banner_path')
                    ->where('visible_franchise_only', true) // Apenas campanhas exclusivas para franqueados
                    ->orderByDesc('created_at')
                    ->take(10)
                    ->get();
            } else {
                $featuredCampaigns = collect(); // Lojistas/Representantes não veem banner
            }
        } catch (\Exception $e) {
            $featuredCampaigns = collect();
        }
        
        return view('dashboard', compact('produtos', 'news', 'treinamentos', 'notificacoes', 'featuredCampaigns'));
    }
} 