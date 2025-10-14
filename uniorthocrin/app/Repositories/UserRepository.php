<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements RepositoryInterface
{
    private $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function findById($id): ?User
    {
        return $this->model->find($id);
    }

    public function updateProfile(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function updatePassword(User $user, string $newPassword): bool
    {
        return $user->update([
            'password' => Hash::make($newPassword)
        ]);
    }

    public function getAllForUser(User $user)
    {
        return $this->model->where('id', $user->id)->get();
    }

    public function findByIdForUser($id, User $user)
    {
        return $this->model->where('id', $id)->where('id', $user->id)->first();
    }
}
