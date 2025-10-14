<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface RepositoryInterface
{
    public function getAllForUser(User $user);
    public function findByIdForUser($id, User $user);
} 