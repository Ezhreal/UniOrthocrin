<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    /**
     * Get the products for the category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Check if the category is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get all active products in this category.
     */
    public function getActiveProducts()
    {
        return $this->products()->active()->get();
    }

    /**
     * Count active products in this category.
     */
    public function countActiveProducts(): int
    {
        return $this->products()->active()->count();
    }

    /**
     * Check if the category has any active products.
     */
    public function hasActiveProducts(): bool
    {
        return $this->countActiveProducts() > 0;
    }
} 