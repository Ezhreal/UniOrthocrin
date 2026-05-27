<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MediaService;

class MediaController extends Controller
{
    private $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function index($profile_slug, Request $request)
    {
        $user = $request->user();
        $filters = $request->only(['search', 'category_id']);
        
        if ($request->has('search') || $request->has('category_id')) {
            // Se há filtros, usa paginação normal
            $mediaItems = $this->mediaService->getFilteredMedia($user, $filters);
            return view('na-midia-list', compact('mediaItems'));
        } else {
            // Se não há filtros, agrupa por categoria
            $mediaByCategory = $this->mediaService->getMediaByCategory($user);
            return view('na-midia-list', compact('mediaByCategory'));
        }
    }

    public function show($profile_slug, $id, Request $request)
    {
        $user = $request->user();
        $mediaItem = $this->mediaService->getMediaById($id, $user);
        return view('na-midia-detail', compact('mediaItem'));
    }
}
