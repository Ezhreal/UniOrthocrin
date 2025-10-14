<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LibraryService;

class LibraryController extends Controller
{
    private $libraryService;

    public function __construct(LibraryService $libraryService)
    {
        $this->libraryService = $libraryService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $filters = $request->only(['search', 'category_id']);
        
        if ($request->has('search') || $request->has('category_id')) {
            // Se há filtros, usa paginação normal
            $documents = $this->libraryService->getFilteredDocuments($user, $filters);
            return view('biblioteca-list', compact('documents'));
        } else {
            // Se não há filtros, agrupa por categoria
            $documentsByCategory = $this->libraryService->getDocumentsByCategory($user);
            return view('biblioteca-list', compact('documentsByCategory'));
        }
    }

    public function show($id, Request $request)
    {
        $user = $request->user();
        $document = $this->libraryService->getDocumentById($id, $user);
        return view('library-detail', compact('document'));
    }
} 