<?php

namespace App\Models;

use App\Models\Traits\HasCampaignContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CampaignVideo extends Model
{
    use HasCampaignContent;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'campaign_videos';

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
     * Get the campaign that owns the video.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the files for the video.
     */
    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'campaign_video_files')
            ->withPivot(['file_type', 'sort_order', 'is_primary'])
            ->withTimestamps();
    }

    /**
     * Scope a query to only include videos of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get the video type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'reels' => 'Reels',
            'marketing_campaigns' => 'Campanhas de Marketing',
            default => ucfirst($this->type)
        };
    }

    /**
     * Get the main video file for the video.
     */
    public function mainVideo()
    {
        return $this->files()->wherePivot('file_type', 'video')
            ->orderBy('pivot_sort_order')
            ->first();
    }

    /**
     * Get the main video URL.
     */
    public function getMainVideoUrlAttribute()
    {
        $mainVideo = $this->mainVideo();
        return $mainVideo ? $mainVideo->url : null;
    }

    /**
     * Check if this video has a main video file.
     */
    public function hasVideo(): bool
    {
        return $this->videos()->count() > 0;
    }

    /**
     * Get the video duration (if available in file metadata).
     */
    public function getDurationAttribute()
    {
        $mainVideo = $this->mainVideo();
        // This would need to be implemented based on how video metadata is stored
        return $mainVideo ? $mainVideo->duration ?? null : null;
    }
}
