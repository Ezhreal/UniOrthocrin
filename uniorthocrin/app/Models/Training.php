<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Training extends Model
{

    protected $fillable = [
        'name',
        'training_category_id',
        'description',
        'content_type',
        'status',
        'thumbnail_path'
    ];

    protected $casts = [
        'status' => 'string',
        'content_type' => 'string'
    ];

    /**
     * Get the category that owns the training.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(TrainingCategory::class, 'training_category_id');
    }

    /**
     * Get the permissions for the training.
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(TrainingPermission::class);
    }

    /**
     * Get all files for the training.
     */
    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'training_files')
                    ->withPivot('file_type', 'sort_order', 'is_primary')
                    ->orderBy('pivot_sort_order');
    }

    /**
     * Get videos for the training.
     */
    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'training_files')
                    ->wherePivot('file_type', 'video')
                    ->withPivot('file_type', 'sort_order', 'is_primary')
                    ->orderBy('pivot_sort_order');
    }

    /**
     * Get PDFs for the training.
     */
    public function pdfs(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'training_files')
                    ->wherePivot('file_type', 'pdf')
                    ->withPivot('file_type', 'sort_order', 'is_primary')
                    ->orderBy('pivot_sort_order');
    }

    /**
     * Get the user views for the training.
     */
    public function userViews(): HasMany
    {
        return $this->hasMany(UserView::class, 'viewable_id')
            ->where('viewable_type', self::class);
    }

    /**
     * Get the download options for the training.
     */
    public function downloadOptions(): HasMany
    {
        return $this->hasMany(DownloadOption::class, 'resource_id')
            ->where('resource_type', self::class);
    }

    /**
     * Scope a query to only include active trainings.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Check if the training is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the training can be downloaded by the given user.
     */
    public function canBeDownloadedBy(User $user): bool
    {
        // Verificar se o treinamento está ativo
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