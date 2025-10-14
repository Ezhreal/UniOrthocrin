<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'content',
        'excerpt',
        'published_at',
        'status',
        'thumbnail_path',
        'news_file_id',
        'news_category_id',
    ];

    protected $casts = [
        'status' => 'string'
    ];

    /**
     * Get the thumbnail URL for the news.
     */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->news_img_file) {
            if (Str::startsWith($this->news_img_file, 'private/')) {
                return url('/' . $this->news_img_file);
            }
            return url('/' . ltrim($this->news_img_file, '/'));
        }
        return asset('images/placeholder-news.jpg');
    }

    /**
     * Get the user that owns the news.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the user types that have permissions for the news.
     */
    public function userTypes(): BelongsToMany
    {
        return $this->belongsToMany(UserType::class, 'news_permissions', 'news_id', 'user_type_id');
    }

    /**
     * Get the permissions for the news.
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(NewsPermission::class);
    }

    /**
     * Get the main file for the news.
     */
    public function mainFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'news_file_id');
    }

    /**
     * Get the image for the news (alias for mainFile).
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(File::class, 'news_file_id');
    }

    /**
     * Get the user views for the news.
     */
    public function userViews(): HasMany
    {
        return $this->hasMany(UserView::class, 'viewable_id')
            ->where('viewable_type', self::class);
    }

    /**
     * Scope a query to only include published news.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include draft news.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Check if the news is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' 
            && $this->published_at 
            && $this->published_at <= now();
    }

    /**
     * Check if the news is draft.
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Get the excerpt text, generating it from content if not set.
     */
    public function getExcerptText(): string
    {
        if ($this->excerpt) {
            return $this->excerpt;
        }

        return Str::limit(strip_tags($this->content), 150);
    }

    /**
     * Get the category that owns the news.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }

    /**
     * Check if the news can be downloaded by the given user.
     */
    public function canBeDownloadedBy(User $user): bool
    {
        // Verificar se a notícia está publicada
        if (!$this->isPublished()) {
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
