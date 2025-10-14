<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\LibraryRepository;

class LibraryService
{
    private $libraryRepository;

    public function __construct(LibraryRepository $libraryRepository)
    {
        $this->libraryRepository = $libraryRepository;
    }

    public function getAllDocuments(User $user)
    {
        return $this->libraryRepository->getAllForUser($user);
    }

    public function getDocumentById($id, User $user)
    {
        return $this->libraryRepository->findByIdForUser($id, $user);
    }

    public function getFilteredDocuments(User $user, array $filters = [])
    {
        return $this->libraryRepository->getFilteredForUser($user, $filters);
    }

    public function getDocumentsByCategory(User $user)
    {
        return $this->libraryRepository->getDocumentsByCategory($user);
    }
} 