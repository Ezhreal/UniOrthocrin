<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Repositories\UiVisibilityRepository;

class UiVisibilityService
{
    protected $repo;

    public function __construct(UiVisibilityRepository $repo)
    {
        $this->repo = $repo;
    }

    public function canView($feature)
    {
        $user = Auth::user();
        if (!$user) return false;
        // Admin vê tudo
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) return true;
        // Consulta na tabela
        return $this->repo->canView($feature, $user->user_type_id);
    }

    // Para controle de acesso à página pela URL
    public function canAccessPage($feature)
    {
        return $this->canView($feature);
    }
} 