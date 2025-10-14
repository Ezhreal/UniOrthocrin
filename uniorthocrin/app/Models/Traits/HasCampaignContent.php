<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasCampaignContent
{
    /**
     * Get the files for the content item.
     */
    abstract public function files(): BelongsToMany;

    /**
     * Get the primary file for the content item.
     */
    public function primaryFile()
    {
        return $this->files()->wherePivot('is_primary', true)->first();
    }

    /**
     * Get the main image file for the content item.
     */
    public function mainImage()
    {
        return $this->files()->wherePivot('file_type', 'image')
            ->orderBy('pivot_sort_order')
            ->first();
    }

    /**
     * Get all image files for the content item.
     */
    public function images()
    {
        return $this->files()->wherePivot('file_type', 'image')
            ->orderBy('pivot_sort_order');
    }

    /**
     * Get all video files for the content item.
     */
    public function videos()
    {
        return $this->files()->wherePivot('file_type', 'video')
            ->orderBy('pivot_sort_order');
    }

    /**
     * Get all document files for the content item.
     */
    public function documents()
    {
        return $this->files()->wherePivot('file_type', 'document')
            ->orderBy('pivot_sort_order');
    }

    /**
     * Get all audio files for the content item.
     */
    public function audios()
    {
        return $this->files()->wherePivot('file_type', 'audio')
            ->orderBy('pivot_sort_order');
    }

    /**
     * Scope a query to only include active content items.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Check if the content item is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get the main thumbnail URL.
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return asset('storage/' . $this->thumbnail_path);
        }

        $mainImage = $this->mainImage();
        return $mainImage ? $mainImage->url : null;
    }

    /**
     * Get the main file URL.
     */
    public function getMainFileUrlAttribute()
    {
        $primaryFile = $this->primaryFile();
        return $primaryFile ? $primaryFile->url : null;
    }

    /**
     * Get the file count for this content item.
     */
    public function getFileCountAttribute(): int
    {
        return $this->files()->count();
    }

    /**
     * Get the image count for this content item.
     */
    public function getImageCountAttribute(): int
    {
        return $this->images()->count();
    }

    /**
     * Get the video count for this content item.
     */
    public function getVideoCountAttribute(): int
    {
        return $this->videos()->count();
    }

    /**
     * Get the document count for this content item.
     */
    public function getDocumentCountAttribute(): int
    {
        return $this->documents()->count();
    }

    /**
     * Get the audio count for this content item.
     */
    public function getAudioCountAttribute(): int
    {
        return $this->audios()->count();
    }

    /**
     * Check if this content item has a main image.
     */
    public function hasImage(): bool
    {
        return $this->images()->count() > 0;
    }

    /**
     * Check if this content item has a video.
     */
    public function hasVideo(): bool
    {
        return $this->videos()->count() > 0;
    }

    /**
     * Check if this content item has an audio file.
     */
    public function hasAudio(): bool
    {
        return $this->audios()->count() > 0;
    }

    /**
     * Check if this content item has documents.
     */
    public function hasDocuments(): bool
    {
        return $this->documents()->count() > 0;
    }

    /**
     * Get the content type for display.
     */
    public function getContentTypeAttribute(): string
    {
        return class_basename($this);
    }

    /**
     * Get the content type label.
     */
    public function getContentTypeLabelAttribute(): string
    {
        return match($this->content_type) {
            'CampaignPost' => 'Post',
            'CampaignFolder' => 'Pasta',
            'CampaignVideo' => 'Vídeo',
            'CampaignMiscellaneous' => 'Diversos',
            default => $this->content_type
        };
    }

    /**
     * Get the content icon class.
     */
    public function getContentIconClassAttribute(): string
    {
        return match($this->content_type) {
            'CampaignPost' => 'fas fa-newspaper',
            'CampaignFolder' => 'fas fa-folder',
            'CampaignVideo' => 'fas fa-video',
            'CampaignMiscellaneous' => 'fas fa-ellipsis-h',
            default => 'fas fa-file'
        };
    }

    /**
     * Get the content color class.
     */
    public function getContentColorClassAttribute(): string
    {
        return match($this->content_type) {
            'CampaignPost' => 'text-blue-600',
            'CampaignFolder' => 'text-green-600',
            'CampaignVideo' => 'text-red-600',
            'CampaignMiscellaneous' => 'text-purple-600',
            default => 'text-gray-600'
        };
    }

    /**
     * Get the content background color class.
     */
    public function getContentBgColorClassAttribute(): string
    {
        return match($this->content_type) {
            'CampaignPost' => 'bg-blue-50',
            'CampaignFolder' => 'bg-green-50',
            'CampaignVideo' => 'bg-red-50',
            'CampaignMiscellaneous' => 'bg-purple-50',
            default => 'bg-gray-50'
        };
    }
}
