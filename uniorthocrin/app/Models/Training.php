<?php

namespace App\Models;

use App\Models\Traits\HasFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Training extends Model
{
    use HasFiles;

    protected $fillable = [
        'name',
        'category_id',
        'description',
        'content_type',
        'status'
    ];

    protected $casts = [
        'content_type' => 'string',
        'status' => 'string'
    ];

    /**
     * Get the category that owns the training.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(TrainingCategory::class, 'category_id');
    }

    /**
     * Get the permissions for the training.
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(TrainingPermission::class);
    }

    /**
     * Get the user views for the training.
     */
    public function userViews(): HasMany
    {
        return $this->hasMany(UserView::class, 'viewable_id')
            ->where('viewable_type', self::class);
    }

    /**
     * Get the download options for the training.
     */
    public function downloadOptions(): HasMany
    {
        return $this->hasMany(DownloadOption::class, 'resource_id')
            ->where('resource_type', self::class);
    }

    /**
     * Scope a query to only include active trainings.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Check if the training is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the training is a video.
     */
    public function isVideo(): bool
    {
        return $this->content_type === 'video';
    }

    /**
     * Check if the training is a PDF.
     */
    public function isPdf(): bool
    {
        return $this->content_type === 'pdf';
    }
} 