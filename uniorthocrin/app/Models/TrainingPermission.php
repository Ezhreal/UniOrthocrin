<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingPermission extends Model
{
    protected $fillable = [
        'training_id',
        'user_type_id',
        'can_view',
        'can_download'
    ];

    protected $casts = [
        'can_view' => 'boolean',
        'can_download' => 'boolean'
    ];

    /**
     * Get the training that owns the permission.
     */
    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }

    /**
     * Get the user type that owns the permission.
     */
    public function userType(): BelongsTo
    {
        return $this->belongsTo(UserType::class);
    }
} 