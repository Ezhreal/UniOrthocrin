<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Library extends Model
{

    /**
     * The table associated with the model.
     */
    protected $table = 'library';

    protected $fillable = [
        'name',
        'library_category_id',
        'description',
        'status',
        'thumbnail_path'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    /**
     * Get the category that owns the library item.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(LibraryCategory::class, 'library_category_id');
    }

    /**
     * Get the permissions for the library item.
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(LibraryPermission::class);
    }

    /**
     * Get all files for the library item.
     */
    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'library_files')
                    ->withPivot('file_type', 'sort_order', 'is_primary')
                    ->orderBy('pivot_sort_order');
    }

    /**
     * Get the user views for the library item.
     */
    public function userViews(): HasMany
    {
        return $this->hasMany(UserView::class, 'viewable_id')
            ->where('viewable_type', self::class);
    }

    /**
     * Get the download options for the library item.
     */
    public function downloadOptions(): HasMany
    {
        return $this->hasMany(DownloadOption::class, 'resource_id')
            ->where('resource_type', self::class);
    }

    /**
     * Scope a query to only include active library items.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Check if the library item is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the library item can be downloaded by the given user.
     */
    public function canBeDownloadedBy(User $user): bool
    {
        // Verificar se o item está ativo
        if (!$this->isActive()) {
            return false;
        }

        // Verificar permissões específicas
        $permission = $this->permissions()
            ->where('user_type_id', $user->user_type_id)
            ->first();

        // Se não há permissão específica, permitir para usuários autenticados
        return $permission ? $permission->can_download : true;
    }
} 