<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaPermission extends Model
{
    protected $fillable = [
        'media_id',
        'user_type_id',
        'can_view',
        'can_download'
    ];

    protected $casts = [
        'can_view' => 'boolean',
        'can_download' => 'boolean'
    ];

    /**
     * Get the media item that owns the permission.
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    /**
     * Get the user type that owns the permission.
     */
    public function userType(): BelongsTo
    {
        return $this->belongsTo(UserType::class);
    }
}
