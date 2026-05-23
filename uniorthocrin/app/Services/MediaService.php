<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\MediaRepository;

class MediaService
{
    private $mediaRepository;

    public function __construct(MediaRepository $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }

    public function getAllMedia(User $user)
    {
        return $this->mediaRepository->getAllForUser($user);
    }

    public function getMediaById($id, User $user)
    {
        return $this->mediaRepository->findByIdForUser($id, $user);
    }

    public function getFilteredMedia(User $user, array $filters = [])
    {
        return $this->mediaRepository->getFilteredForUser($user, $filters);
    }

    public function getMediaByCategory(User $user)
    {
        return $this->mediaRepository->getMediaByCategory($user);
    }
}
