<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GlobalSearchService;

class SearchController extends Controller
{
    protected GlobalSearchService $searchService;

    public function __construct(GlobalSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Página de resultados da busca global (título: Resultados).
     */
    public function index($profile_slug, Request $request)
    {
        $query = $request->get('q') ?: $request->get('search') ?: '';
        $user = $request->user();

        $results = $this->searchService->search($user, $query);

        return view('resultados', [
            'results' => $results,
            'query' => $query,
        ]);
    }
}
