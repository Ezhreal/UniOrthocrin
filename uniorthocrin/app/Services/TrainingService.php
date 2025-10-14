<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\TrainingRepository;

class TrainingService
{
    private $trainingRepository;

    public function __construct(TrainingRepository $trainingRepository)
    {
        $this->trainingRepository = $trainingRepository;
    }

    public function getNewTrainings(User $user, $limit = 3)
    {
        return $this->trainingRepository->getLatestForUser($user, $limit);
    }

    public function getAllTrainings(User $user)
    {
        return $this->trainingRepository->getAllForUser($user);
    }

    public function getTrainingById($id, User $user)
    {
        return $this->trainingRepository->findByIdForUser($id, $user);
    }

    public function getFilteredTrainings(User $user, array $filters = [])
    {
        return $this->trainingRepository->getFilteredForUser($user, $filters);
    }

    public function getTotalTrainings(User $user)
    {
        return $this->trainingRepository->getTotalForUser($user);
    }

    public function getTrainingVideos($training)
    {
        return $this->trainingRepository->getTrainingVideos($training);
    }

    public function getTrainingPdfs($training)
    {
        return $this->trainingRepository->getTrainingPdfs($training);
    }

    public function getTrainingsByCategory(User $user)
    {
        return $this->trainingRepository->getTrainingsByCategory($user);
    }
} 