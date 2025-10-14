<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\NewsRepository;

class NewsService
{
    private $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function getRadarNews(User $user, $limit = 3)
    {
        return $this->newsRepository->getLatestForUser($user, $limit);
    }

    public function getAllNews(User $user)
    {
        return $this->newsRepository->getAllForUser($user);
    }

    public function getNewsById($id, User $user)
    {
        return $this->newsRepository->findByIdForUser($id, $user);
    }

    public function getFilteredNews(User $user, array $filters = [])
    {
        return $this->newsRepository->getFilteredForUser($user, $filters);
    }
} 