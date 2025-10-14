<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NewsService;

class NewsController extends Controller
{
    private $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $filters = $request->only(['search', 'category_id']);
        $news = $this->newsService->getFilteredNews($user, $filters);
        return view('news-list', compact('news'));
    }

    public function show($id, Request $request)
    {
        $user = $request->user();
        $newsItem = $this->newsService->getNewsById($id, $user);
        return view('news-detail', compact('newsItem'));
    }
} 