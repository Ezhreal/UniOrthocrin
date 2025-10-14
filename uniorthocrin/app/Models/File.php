<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'name',
        'path',
        'type',
        'extension',
        'mime_type',
        'size',
        'order'
    ];

    protected $casts = [
        'size' => 'integer',
        'order' => 'integer'
    ];

    /**
     * Get the full URL for the file.
     */
    public function getUrlAttribute(): string
    {
        // Se o path já começa com 'private/', não adiciona barra extra
        if (str_starts_with($this->path, 'private/')) {
            return url('/' . $this->path);
        }
        
        return url('/' . ltrim($this->path, '/'));
    }

    /**
     * Get the thumbnail URL or placeholder.
     */
    public function getThumbnailUrlAttribute(): string
    {
        // Se tem path, retorna a URL do arquivo original
        if ($this->path) {
            // Se o path já começa com 'private/', não adiciona barra extra
            if (str_starts_with($this->path, 'private/')) {
                return url('/' . $this->path);
            }
            
            return url('/' . ltrim($this->path, '/'));
        }

        // Placeholder baseado no tipo de arquivo apenas se não tiver arquivo
        if ($this->isImage()) {
            return 'https://placehold.co/600x600?text=Imagem';
        } elseif ($this->isVideo()) {
            return 'https://placehold.co/600x600?text=Vídeo';
        } elseif ($this->isPdf()) {
            return 'https://placehold.co/600x600?text=PDF';
        } elseif ($this->isAudio()) {
            return 'https://placehold.co/600x600?text=Áudio';
        }
        
        return 'https://placehold.co/600x600?text=Arquivo';
    }

    /**
     * Get the full path for the file.
     */
    public function getFullPathAttribute(): string
    {
        return storage_path('app/' . $this->path);
    }

    /**
     * Check if the file is an image.
     */
    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    /**
     * Check if the file is a video.
     */
    public function isVideo(): bool
    {
        return $this->type === 'video';
    }

    /**
     * Check if the file is a PDF.
     */
    public function isPdf(): bool
    {
        return $this->type === 'pdf';
    }

    /**
     * Check if the file is an audio.
     */
    public function isAudio(): bool
    {
        return $this->type === 'audio';
    }
} 