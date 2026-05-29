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
        'order',
        'optimized_path',
        'thumbnail_sm_path',
        'thumbnail_md_path',
        'thumbnail_lg_path',
        'is_optimized',
        'chunk_upload_uuid',
        'status',
    ];

    protected $casts = [
        'size' => 'integer',
        'order' => 'integer',
        'is_optimized' => 'boolean',
    ];

    protected static function booted()
    {
        static::created(function ($file) {
            if ($file->type === 'image') {
                \App\Jobs\OptimizeImage::dispatch($file);
            }

            // Sempre agendar a sincronização para o FTP quando um arquivo privado for gravado
            if (str_starts_with($file->path, 'private/')) {
                $exists = \App\Models\FtpSync::where('file_id', $file->id)->exists();
                if (!$exists) {
                    $isThumbnail = str_contains($file->path, '/thumb/') || 
                                   str_contains($file->path, '/thumbnail') || 
                                   str_contains($file->path, '_thumb');

                    $sync = \App\Models\FtpSync::create([
                        'syncable_type' => get_class($file),
                        'syncable_id' => $file->id,
                        'file_id' => $isThumbnail ? null : $file->id,
                        'local_path' => storage_path('app/' . $file->path),
                        'remote_path' => $file->path,
                        'status' => 'pending'
                    ]);

                    \App\Jobs\UploadToFtpJob::dispatch($sync->id);
                }
            }
        });
    }

    /**
     * Get the full URL for the file.
     */
    public function getUrlAttribute(): string
    {
        $slug = session('active_profile_slug');
        $prefix = $slug ? '/' . $slug : '';

        // Se o path já começa com 'private/', não adiciona barra extra
        if (str_starts_with($this->path, 'private/')) {
            return url($prefix . '/' . $this->path);
        }
        
        return url($prefix . '/' . ltrim($this->path, '/'));
    }

    /**
     * Get the thumbnail URL or placeholder.
     */
    public function getThumbnailUrlAttribute(): string
    {
        $slug = session('active_profile_slug');
        $prefix = $slug ? '/' . $slug : '';

        // Se tem path, retorna a URL do arquivo original
        if ($this->path) {
            // Se o path já começa com 'private/', não adiciona barra extra
            if (str_starts_with($this->path, 'private/')) {
                return url($prefix . '/' . $this->path);
            }
            
            return url($prefix . '/' . ltrim($this->path, '/'));
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

    /**
     * Get URL for a specific file path, keeping context slug.
     */
    public function getUrlForPath(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        $slug = session('active_profile_slug');
        $prefix = $slug ? '/' . $slug : '';

        if (str_starts_with($path, 'private/')) {
            return url($prefix . '/' . $path);
        }
        
        return url($prefix . '/' . ltrim($path, '/'));
    }

    /**
     * Get the responsive srcset value for images.
     */
    public function getSrcsetAttribute(): string
    {
        if ($this->type !== 'image') {
            return '';
        }

        $sources = [];
        if ($this->thumbnail_sm_path) {
            $sources[] = $this->getUrlForPath($this->thumbnail_sm_path) . ' 320w';
        }
        if ($this->thumbnail_md_path) {
            $sources[] = $this->getUrlForPath($this->thumbnail_md_path) . ' 640w';
        }
        if ($this->thumbnail_lg_path) {
            $sources[] = $this->getUrlForPath($this->thumbnail_lg_path) . ' 1024w';
        }
        
        $mainPath = $this->optimized_path ?: $this->path;
        if ($mainPath) {
            $sources[] = $this->getUrlForPath($mainPath) . ' 1920w';
        }

        return implode(', ', $sources);
    }

    /**
     * Get optimized WebP URL falling back to original URL.
     */
    public function getOptimizedUrlAttribute(): string
    {
        return $this->getUrlForPath($this->optimized_path ?: $this->path);
    }

    /**
     * Get small size URL falling back to optimized or original.
     */
    public function getUrlSmAttribute(): string
    {
        return $this->getUrlForPath($this->thumbnail_sm_path ?: $this->optimized_path ?: $this->path);
    }

    /**
     * Get medium size URL falling back to optimized or original.
     */
    public function getUrlMdAttribute(): string
    {
        return $this->getUrlForPath($this->thumbnail_md_path ?: $this->optimized_path ?: $this->path);
    }

    /**
     * Get large size URL falling back to optimized or original.
     */
    public function getUrlLgAttribute(): string
    {
        return $this->getUrlForPath($this->thumbnail_lg_path ?: $this->optimized_path ?: $this->path);
    }
} 