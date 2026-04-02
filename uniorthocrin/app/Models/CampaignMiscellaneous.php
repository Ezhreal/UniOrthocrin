<?php

namespace App\Models;

use App\Models\Traits\HasCampaignContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CampaignMiscellaneous extends Model
{
    use HasCampaignContent;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'campaign_miscellaneous';

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
     * Get the campaign that owns the miscellaneous item.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the files for the miscellaneous item.
     */
    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'campaign_miscellaneous_files')
            ->withPivot(['file_type', 'sort_order', 'is_primary'])
            ->withTimestamps();
    }

    /**
     * Scope a query to only include miscellaneous items of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Agrupamento para a página de marketing: uma seção com título, sem repetir o rótulo por arquivo.
     *
     * @return array{id: string, order: int, title: string}
     */
    public static function marketingSectionMeta(string $type): array
    {
        return match ($type) {
            'spot' => ['id' => 'spots', 'order' => 10, 'title' => 'Spots'],
            'tag' => ['id' => 'tags', 'order' => 20, 'title' => 'Tags'],
            'adesivo', 'sticker' => ['id' => 'adesivos', 'order' => 30, 'title' => 'Adesivos'],
            'banner', 'faixa' => ['id' => 'banners', 'order' => 40, 'title' => 'Banners e faixas'],
            'script' => ['id' => 'script', 'order' => 50, 'title' => 'Materiais internos'],
            default => ['id' => 'outros', 'order' => 60, 'title' => 'Outros materiais'],
        };
    }

    /**
     * Get the miscellaneous type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'spot' => 'Spot',
            'tag' => 'Tag',
            'sticker' => 'Sticker',
            'script' => 'Materiais Internos',
            'adesivo' => 'Adesivo',
            'banner' => 'Banner',
            'faixa' => 'Faixa',
            default => ucfirst($this->type)
        };
    }

    /**
     * Get the icon class based on the type.
     */
    public function getIconClassAttribute(): string
    {
        return match($this->type) {
            'spot' => 'fas fa-broadcast-tower',
            'tag' => 'fas fa-tag',
            'sticker' => 'fas fa-sticky-note',
            'script' => 'fas fa-file-alt',
            'adesivo' => 'fas fa-sticky-note',
            'banner' => 'fas fa-image',
            'faixa' => 'fas fa-align-center',
            default => 'fas fa-file'
        };
    }

    /**
     * Get the color class based on the type.
     */
    public function getColorClassAttribute(): string
    {
        return match($this->type) {
            'spot' => 'text-blue-600',
            'tag' => 'text-green-600',
            'sticker' => 'text-yellow-600',
            'script' => 'text-purple-600',
            'adesivo' => 'text-yellow-600',
            'banner' => 'text-indigo-600',
            'faixa' => 'text-teal-600',
            default => 'text-gray-600'
        };
    }
}
