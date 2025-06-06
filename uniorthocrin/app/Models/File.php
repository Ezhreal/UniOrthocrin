<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Model
{
    protected $fillable = [
        'name',
        'type',
        'path',
        'thumbnail_path',
        'size',
        'extension',
        'mime_type',
        'order'
    ];

    protected $casts = [
        'size' => 'integer',
        'order' => 'integer'
    ];

    /**
     * Get the parent fileable model.
     */
    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the file's URL.
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    /**
     * Get the thumbnail URL if exists.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_path ? asset('storage/' . $this->thumbnail_path) : null;
    }

    /**
     * Get the formatted file size.
     */
    public function getFormattedSizeAttribute(): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $size = $this->size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }
} 