<?php

namespace App\Models;

use App\Models\Traits\HasFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class News extends Model
{
    use HasFiles;

    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'author_id',
        'published_at',
        'status'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'status' => 'string'
    ];

    /**
     * Get the author that owns the news.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the permissions for the news.
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(NewsPermission::class);
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
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
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

        return \Str::limit(strip_tags($this->content), 150);
    }
} 