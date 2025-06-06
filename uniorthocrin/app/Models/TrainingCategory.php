<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingCategory extends Model
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
     * Get the trainings for the category.
     */
    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class, 'category_id');
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
} 