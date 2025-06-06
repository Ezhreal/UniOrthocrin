<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'level',
        'status'
    ];

    protected $casts = [
        'level' => 'integer',
        'status' => 'string'
    ];

    /**
     * Get the users associated with this user type.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Scope a query to only include active user types.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Check if the user type is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
} 