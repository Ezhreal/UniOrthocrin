<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Media extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'media';

    protected $fillable = [
        'name',
        'media_category_id',
        'description',
        'status',
        'thumbnail_path'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    /**
     * Get the category that owns the media item.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(MediaCategory::class, 'media_category_id');
    }

    /**
     * Get the permissions for the media item.
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(MediaPermission::class);
    }

    /**
     * Get all files for the media item.
     */
    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'media_files')
                    ->withPivot('file_type', 'sort_order', 'is_primary')
                    ->orderByPivot('sort_order');
    }

    /**
     * Get the user views for the media item.
     */
    public function userViews(): HasMany
    {
        return $this->hasMany(UserView::class, 'viewable_id')
            ->where('viewable_type', self::class);
    }

    /**
     * Get the download options for the media item.
     */
    public function downloadOptions(): HasMany
    {
        return $this->hasMany(DownloadOption::class, 'resource_id')
            ->where('resource_type', self::class);
    }

    /**
     * Scope a query to only include active media items.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Check if the media item is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the media item can be downloaded by the given user.
     */
    public function canBeDownloadedBy(User $user, ?int $activeProfileId = null): bool
    {
        // Verificar se o item está ativo
        if (!$this->isActive()) {
            return false;
        }

        $profileId = $activeProfileId ?? session('active_profile_id') ?? $user->user_type_id;

        // Verificar permissões específicas
        $permission = $this->permissions()
            ->where('user_type_id', $profileId)
            ->first();

        // Se não há permissão específica, permitir para usuários autenticados
        return $permission ? $permission->can_download : true;
    }

    protected static function booted()
    {
        static::saved(function ($media) {
            if ($media->thumbnail_path && ($media->wasChanged('thumbnail_path') || !$media->exists)) {
                \App\Jobs\OptimizeModelImage::dispatch($media, 'thumbnail_path');
            }
        });
    }
}
