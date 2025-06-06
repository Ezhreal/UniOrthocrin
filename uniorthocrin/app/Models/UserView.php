<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserView extends Model
{
    protected $fillable = [
        'user_id',
        'viewable_type',
        'viewable_id',
        'first_viewed_at',
        'last_viewed_at',
        'view_count',
        'download_count'
    ];

    protected $casts = [
        'first_viewed_at' => 'datetime',
        'last_viewed_at' => 'datetime',
        'view_count' => 'integer',
        'download_count' => 'integer'
    ];

    /**
     * Get the user that owns the view.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent viewable model.
     */
    public function viewable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Increment the view count.
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
        $this->update(['last_viewed_at' => now()]);
    }

    /**
     * Increment the download count.
     */
    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
    }
} 