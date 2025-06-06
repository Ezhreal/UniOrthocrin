<?php

namespace App\Models;

use App\Models\Traits\HasFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFiles;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'visible_franchise_only',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'visible_franchise_only' => 'boolean',
        'status' => 'string'
    ];

    /**
     * Get the user views for the campaign.
     */
    public function userViews(): HasMany
    {
        return $this->hasMany(UserView::class, 'viewable_id')
            ->where('viewable_type', self::class);
    }

    /**
     * Get the download options for the campaign.
     */
    public function downloadOptions(): HasMany
    {
        return $this->hasMany(DownloadOption::class, 'resource_id')
            ->where('resource_type', self::class);
    }

    /**
     * Scope a query to only include active campaigns.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include current campaigns.
     */
    public function scopeCurrent($query)
    {
        return $query->where(function($query) {
            $query->whereNull('end_date')
                ->orWhere('end_date', '>=', now());
        })->where(function($query) {
            $query->whereNull('start_date')
                ->orWhere('start_date', '<=', now());
        });
    }

    /**
     * Check if the campaign is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the campaign is current.
     */
    public function isCurrent(): bool
    {
        if ($this->end_date && $this->end_date < now()) {
            return false;
        }

        if ($this->start_date && $this->start_date > now()) {
            return false;
        }

        return true;
    }

    /**
     * Check if the campaign is visible only to franchises.
     */
    public function isVisibleFranchiseOnly(): bool
    {
        return $this->visible_franchise_only;
    }
} 