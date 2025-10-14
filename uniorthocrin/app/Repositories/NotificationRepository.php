<?php

namespace App\Repositories;

use App\Models\UserNotification;
use App\Models\User;

class NotificationRepository
{
    private $model;

    public function __construct(UserNotification $model)
    {
        $this->model = $model;
    }

    public function getLatestForUser(User $user, $limit = 5)
    {
        return $this->model->where('user_id', $user->id)
            ->latest('created_at')
            ->take($limit)
            ->get();
    }

    public function getAllForUser(User $user)
    {
        return $this->model->where('user_id', $user->id)
            ->latest('created_at')
            ->get();
    }

    public function findByIdForUser($id, User $user)
    {
        return $this->model->where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();
    }
} 