<?php

namespace App\Models;

use App\Models\Traits\HasCampaignContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CampaignPost extends Model
{
    use HasCampaignContent;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'campaign_posts';

    protected $fillable = [
        'campaign_id',
        'name',
        'description',
        'type',
        'status',
        'thumbnail_path'
    ];

    protected $casts = [
        'status' => 'string',
        'type' => 'string'
    ];

    /**
     * Get the campaign that owns the post.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the files for the post.
     */
    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'campaign_post_files')
            ->withPivot(['file_type', 'sort_order', 'is_primary'])
            ->withTimestamps();
    }

    /**
     * Scope a query to only include posts of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get the post type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'feeds' => 'Feeds',
            'stories_mg_sp' => 'Stories MG/SP',
            'stories_df_es' => 'Stories DF/ES',
            default => ucfirst($this->type)
        };
    }
}
