<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AccessHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'resource_type',
        'resource_id',
        'ip_address',
        'user_agent',
        'metadata',
        'created_at'
    ];

    protected $casts = [
        'metadata' => 'json',
        'created_at' => 'datetime'
    ];

    /**
     * Get the user that owns the access history.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent resource model.
     */
    public function resource(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include views.
     */
    public function scopeViews($query)
    {
        return $query->where('action', 'view');
    }

    /**
     * Scope a query to only include downloads.
     */
    public function scopeDownloads($query)
    {
        return $query->where('action', 'download');
    }

    /**
     * Check if the access is a view.
     */
    public function isView(): bool
    {
        return $this->action === 'view';
    }

    /**
     * Check if the access is a download.
     */
    public function isDownload(): bool
    {
        return $this->action === 'download';
    }
} 