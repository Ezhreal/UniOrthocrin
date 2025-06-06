<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibraryPermission extends Model
{
    protected $fillable = [
        'library_id',
        'user_type_id',
        'can_view',
        'can_download'
    ];

    protected $casts = [
        'can_view' => 'boolean',
        'can_download' => 'boolean'
    ];

    /**
     * Get the library item that owns the permission.
     */
    public function library(): BelongsTo
    {
        return $this->belongsTo(Library::class);
    }

    /**
     * Get the user type that owns the permission.
     */
    public function userType(): BelongsTo
    {
        return $this->belongsTo(UserType::class);
    }
} 