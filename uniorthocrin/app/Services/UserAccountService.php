<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserAccountService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function updateProfile(User $user, array $data): bool
    {
        return $this->userRepository->updateProfile($user, $data);
    }

    public function updatePassword(User $user, string $newPassword): bool
    {
        return $this->userRepository->updatePassword($user, $newPassword);
    }

    public function getUserProfile(User $user): User
    {
        return $this->userRepository->findById($user->id);
    }
}
