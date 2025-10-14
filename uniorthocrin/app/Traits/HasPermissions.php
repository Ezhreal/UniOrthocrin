<?php

namespace App\Traits;

use App\Models\Permission;

trait HasPermissions
{
    public function permissions()
    {
        return $this->morphMany(Permission::class, 'permissible');
    }

    public function hasPermission($userTypeId, $action = 'view')
    {
        return $this->permissions()
            ->where('user_type_id', $userTypeId)
            ->where("can_{$action}", true)
            ->exists();
    }
} 