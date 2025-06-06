<?php

namespace App\Models\Traits;

use App\Models\File;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasFiles
{
    /**
     * Get all files for the model.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable')->orderBy('order');
    }

    /**
     * Get files of a specific type.
     */
    public function getFilesByType(string $type): MorphMany
    {
        return $this->files()->where('type', $type);
    }

    /**
     * Get images for the model.
     */
    public function images(): MorphMany
    {
        return $this->getFilesByType('image');
    }

    /**
     * Get videos for the model.
     */
    public function videos(): MorphMany
    {
        return $this->getFilesByType('video');
    }

    /**
     * Get PDFs for the model.
     */
    public function pdfs(): MorphMany
    {
        return $this->getFilesByType('pdf');
    }

    /**
     * Get audio files for the model.
     */
    public function audioFiles(): MorphMany
    {
        return $this->getFilesByType('audio');
    }
} 