<?php

namespace App\Models;

use App\Models\Traits\HasFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Library extends Model
{
    use HasFiles;

    /**
     * The table associated with the model.
     */
    protected $table = 'library';

    protected $fillable = [
        'name',
        'category_id',
        'description',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    /**
     * Get the category that owns the library item.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(LibraryCategory::class, 'category_id');
    }

    /**
     * Get the permissions for the library item.
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(LibraryPermission::class);
    }

    /**
     * Get the user views for the library item.
     */
    public function userViews(): HasMany
    {
        return $this->hasMany(UserView::class, 'viewable_id')
            ->where('viewable_type', self::class);
    }

    /**
     * Get the download options for the library item.
     */
    public function downloadOptions(): HasMany
    {
        return $this->hasMany(DownloadOption::class, 'resource_id')
            ->where('resource_type', self::class);
    }

    /**
     * Scope a query to only include active library items.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Check if the library item is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
} 