<?php

namespace App\Models;

use App\Models\Traits\HasCampaignContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CampaignMiscellaneous extends Model
{
    use HasCampaignContent;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'campaign_miscellaneous';

    protected $fillable = [
        'campaign_id',
        'name',
        'description',
        'type',
        'status',
        'thumbnail_path'
    ];

    protected $casts = [
        'status' => 'string',
        'type' => 'string'
    ];

    /**
     * Get the campaign that owns the miscellaneous item.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the files for the miscellaneous item.
     */
    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'campaign_miscellaneous_files')
            ->withPivot(['file_type', 'sort_order', 'is_primary'])
            ->withTimestamps();
    }

    /**
     * Scope a query to only include miscellaneous items of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get the miscellaneous type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'spot' => 'Spot',
            'tag' => 'Tag',
            'sticker' => 'Sticker',
            'script' => 'Script',
            default => ucfirst($this->type)
        };
    }

    /**
     * Get the icon class based on the type.
     */
    public function getIconClassAttribute(): string
    {
        return match($this->type) {
            'spot' => 'fas fa-broadcast-tower',
            'tag' => 'fas fa-tag',
            'sticker' => 'fas fa-sticky-note',
            'script' => 'fas fa-file-alt',
            default => 'fas fa-file'
        };
    }

    /**
     * Get the color class based on the type.
     */
    public function getColorClassAttribute(): string
    {
        return match($this->type) {
            'spot' => 'text-blue-600',
            'tag' => 'text-green-600',
            'sticker' => 'text-yellow-600',
            'script' => 'text-purple-600',
            default => 'text-gray-600'
        };
    }
}
