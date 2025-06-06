<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DownloadOption extends Model
{
    protected $fillable = [
        'resource_type',
        'resource_id',
        'option_name',
        'description',
        'estimated_size'
    ];

    protected $casts = [
        'estimated_size' => 'integer'
    ];

    /**
     * Get the parent resource model.
     */
    public function resource(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the formatted estimated size.
     */
    public function getFormattedSizeAttribute(): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $size = $this->estimated_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }
} 