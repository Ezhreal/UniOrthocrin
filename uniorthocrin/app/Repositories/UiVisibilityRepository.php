<?php

namespace App\Repositories;

use App\Models\UiVisibility;

class UiVisibilityRepository
{
    public function canView(string $feature, int $userTypeId): bool
    {
        return UiVisibility::where('feature', $feature)
            ->where('user_type_id', $userTypeId)
            ->where('can_view', true)
            ->exists();
    }
} 