<?php

namespace App\Models;

use App\Models\Traits\HasFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasPermissions;

class Campaign extends Model
{
    use HasFiles, HasPermissions;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'campaigns';

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'visible_franchise_only',
        'status',
        'thumbnail_path',
        'is_featured',
        'banner_path',
        'folder_mg_sp',
        'folder_df_es',
        'posts_feed',
        'posts_stories_mg_sp',
        'posts_stories_df_es',
        'spot_audio',
        'tag_pdf',
        'adesivo_pdf',
        'roteiros_pdf'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'visible_franchise_only' => 'boolean',
        'status' => 'string',
        'posts_feed' => 'array',
        'posts_stories_mg_sp' => 'array',
        'posts_stories_df_es' => 'array',
        'videos' => 'array'
    ];

    /**
     * Get the campaign posts for the campaign.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(CampaignPost::class);
    }

    /**
     * Get the campaign folders for the campaign.
     */
    public function folders(): HasMany
    {
        return $this->hasMany(CampaignFolder::class);
    }

    /**
     * Get the campaign videos for the campaign.
     */
    public function videos(): HasMany
    {
        return $this->hasMany(CampaignVideo::class);
    }

    /**
     * Get the campaign miscellaneous items for the campaign.
     */
    public function miscellaneous(): HasMany
    {
        return $this->hasMany(CampaignMiscellaneous::class);
    }

    /**
     * Get the user views for the campaign.
     */
    public function userViews(): HasMany
    {
        return $this->hasMany(UserView::class, 'viewable_id')
            ->where('viewable_type', self::class);
    }

    /**
     * Get the download options for the campaign.
     */
    public function downloadOptions(): HasMany
    {
        return $this->hasMany(DownloadOption::class, 'resource_id')
            ->where('resource_type', self::class);
    }

    /**
     * Get all content items (posts, folders, videos, miscellaneous) for the campaign.
     */
    public function getAllContent()
    {
        return collect()
            ->merge($this->posts)
            ->merge($this->folders)
            ->merge($this->videos)
            ->merge($this->miscellaneous)
            ->sortBy('created_at');
    }

    /**
     * Get active content items for the campaign.
     */
    public function getActiveContent()
    {
        return $this->getAllContent()->where('status', 'active');
    }

    /**
     * Scope a query to only include active campaigns.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include current campaigns.
     */
    public function scopeCurrent($query)
    {
        return $query->where(function($query) {
            $query->whereNull('end_date')
                ->orWhere('end_date', '>=', now()->toDateString());
        })->where(function($query) {
            $query->whereNull('start_date')
                ->orWhere('start_date', '<=', now()->toDateString());
        });
    }

    /**
     * Scope a query to only include campaigns visible to franchises.
     */
    public function scopeFranchiseOnly($query)
    {
        return $query->where('visible_franchise_only', true);
    }

    /**
     * Check if the campaign is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the campaign is current.
     */
    public function isCurrent(): bool
    {
        if ($this->end_date && $this->end_date < now()->toDateString()) {
            return false;
        }

        if ($this->start_date && $this->start_date > now()->toDateString()) {
            return false;
        }

        return true;
    }

    /**
     * Check if the campaign is archive-only.
     */
    public function isArchiveOnly(): bool
    {
        return $this->is_archive_only;
    }

    /**
     * Get the total count of content items in this campaign.
     */
    public function getContentCount(): int
    {
        return $this->posts()->count() + 
               $this->folders()->count() + 
               $this->videos()->count() + 
               $this->miscellaneous()->count();
    }

    /**
     * Get the main thumbnail for the campaign (from first content item).
     */
    public function getMainThumbnailAttribute()
    {
        // Primeiro, tentar usar o thumbnail_path da própria campanha
        if ($this->thumbnail_path && file_exists(storage_path('app/' . $this->thumbnail_path))) {
            return '/private/' . str_replace('private/', '', $this->thumbnail_path);
        }
        
        // Se não tiver, tentar usar o thumbnail de algum conteúdo
        $content = $this->getActiveContent()->first();
        if ($content && $content->thumbnail_path && file_exists(storage_path('app/' . $content->thumbnail_path))) {
            return '/private/' . str_replace('private/', '', $content->thumbnail_path);
        }
        
        // Se não tiver nenhum thumbnail válido, retornar null
        return null;
    }

    /**
     * Verificar se a campanha pode ser baixada pelo usuário
     */
    public function canBeDownloadedBy(User $user): bool
    {
        // Apenas Admin (ID 1) e Franqueado (ID 2) podem baixar campanhas
        if (!in_array($user->user_type_id, [1, 2])) {
            return false;
        }

        // Se for Franqueado, só pode baixar campanhas visíveis para franqueados
        if ($user->user_type_id === 2 && !$this->visible_franchise_only) {
            return false;
        }

        // Verificar se a campanha está ativa
        return $this->status === 'active';
    }
} 