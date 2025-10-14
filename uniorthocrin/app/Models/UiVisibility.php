<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UiVisibility extends Model
{
    protected $table = 'ui_visibilities';
    protected $fillable = [
        'feature',
        'user_type_id',
        'can_view',
    ];
} 