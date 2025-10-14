<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'user_type_id',
        'permissible_type',
        'permissible_id',
        'can_view',
        'can_edit',
        'can_delete'
    ];

    public function permissible()
    {
        return $this->morphTo();
    }
} 