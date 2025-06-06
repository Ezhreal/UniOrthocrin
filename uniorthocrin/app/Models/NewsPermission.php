<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsPermission extends Model
{
    protected $fillable = [
        'news_id',
        'user_type_id',
        'can_view'
    ];

    protected $casts = [
        'can_view' => 'boolean'
    ];

    /**
     * Get the news that owns the permission.
     */
    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }

    /**
     * Get the user type that owns the permission.
     */
    public function userType(): BelongsTo
    {
        return $this->belongsTo(UserType::class);
    }
} 