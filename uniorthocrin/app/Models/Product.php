<?php

namespace App\Models;

use App\Models\Traits\HasFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasPermissions;

class Product extends Model
{
    use HasFiles, HasPermissions;

    protected $fillable = [
        'name',
        'product_series_id',
        'product_category_id',
        'description',
        'status',
        'thumbnail_path'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    /**
     * Get the permissions for the product.
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(ProductPermission::class);
    }

    /**
     * Get the user views for the product.
     */
    public function userViews(): HasMany
    {
        return $this->hasMany(UserView::class, 'viewable_id')
            ->where('viewable_type', self::class);
    }

    /**
     * Get the download options for the product.
     */
    public function downloadOptions(): HasMany
    {
        return $this->hasMany(DownloadOption::class, 'resource_id')
            ->where('resource_type', self::class);
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Check if the product is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get the series that owns the product.
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(ProductSeries::class, 'product_series_id');
    }

    /**
     * Get the files for the product.
     */
    public function files()
    {
        return $this->belongsToMany(File::class, 'product_files', 'product_id', 'file_id')
                    ->withPivot('file_type', 'sort_order', 'is_primary')
                    ->withTimestamps();
    }

    /**
     * Get the main file for the product.
     */
    public function mainFile()
    {
        return $this->files()->wherePivot('is_primary', true)->first();
    }

    /**
     * Check if a user has permission to view the product.
     */
    public function canBeViewedBy(User $user): bool
    {
        return $this->permissions()
            ->where('user_type_id', $user->user_type_id)
            ->where('can_view', true)
            ->exists();
    }

    /**
     * Check if a user has permission to download the product.
     */
    public function canBeDownloadedBy(User $user): bool
    {
        return $this->permissions()
            ->where('user_type_id', $user->user_type_id)
            ->where('can_download', true)
            ->exists();
    }

    /**
     * Get the images for the product.
     */
    public function images()
    {
        return $this->belongsToMany(File::class, 'product_files', 'product_id', 'file_id')
                    ->wherePivot('file_type', 'image')
                    ->withPivot('file_type', 'sort_order', 'is_primary')
                    ->orderBy('pivot_sort_order');
    }

    /**
     * Get the videos for the product.
     */
    public function videos()
    {
        return $this->belongsToMany(File::class, 'product_files', 'product_id', 'file_id')
                    ->wherePivot('file_type', 'video')
                    ->withPivot('file_type', 'sort_order', 'is_primary')
                    ->orderBy('pivot_sort_order');
    }

    /**
     * Get the documents for the product.
     */
    public function documents()
    {
        return $this->files()->wherePivot('file_type', 'pdf')->orderBy('pivot_sort_order');
    }

    /**
     * Check if the product has any files.
     */
    public function hasFiles(): bool
    {
        return $this->files()->exists();
    }
} 