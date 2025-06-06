<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPermission extends Model
{
    protected $fillable = [
        'product_id',
        'user_type_id',
        'can_view',
        'can_download'
    ];

    protected $casts = [
        'can_view' => 'boolean',
        'can_download' => 'boolean'
    ];

    /**
     * Get the product that owns the permission.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user type that owns the permission.
     */
    public function userType(): BelongsTo
    {
        return $this->belongsTo(UserType::class);
    }

    /**
     * Check if a user has this permission.
     */
    public function isGrantedTo(User $user): bool
    {
        return $user->user_type_id === $this->user_type_id;
    }

    /**
     * Check if a user type has this permission.
     */
    public function isGrantedToUserType(UserType $userType): bool
    {
        return $userType->id === $this->user_type_id;
    }

    /**
     * Check if the permission allows viewing.
     */
    public function allowsViewing(): bool
    {
        return $this->can_view;
    }

    /**
     * Check if the permission allows downloading.
     */
    public function allowsDownloading(): bool
    {
        return $this->can_download;
    }
} 