<?php

namespace App\Models;

use App\Models\Traits\HasFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFiles;

    protected $fillable = [
        'name',
        'serie',
        'category_id',
        'description',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
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
     * Get the full path of the product file.
     */
    public function getFilePath(): string
    {
        return storage_path('app/products/' . $this->serie . '/' . $this->getFirstFile());
    }

    /**
     * Check if the product has any files.
     */
    public function hasFiles(): bool
    {
        return $this->files()->exists();
    }
} 