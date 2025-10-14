<?php

namespace App\Models;

use App\Models\Traits\HasCampaignContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CampaignFolder extends Model
{
    use HasCampaignContent;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'campaign_folders';

    protected $fillable = [
        'campaign_id',
        'name',
        'description',
        'state',
        'status',
        'thumbnail_path'
    ];

    protected $casts = [
        'status' => 'string',
        'state' => 'string'
    ];

    /**
     * Get the campaign that owns the folder.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the files for the folder.
     */
    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'campaign_folder_files')
            ->withPivot(['file_type', 'sort_order', 'is_primary'])
            ->withTimestamps();
    }

    /**
     * Scope a query to only include folders of a specific state.
     */
    public function scopeOfState($query, $state)
    {
        return $query->where('state', $state);
    }

    /**
     * Get the state label.
     */
    public function getStateLabelAttribute(): string
    {
        return $this->state ?? 'N/A';
    }

    /**
     * Get available states for folders.
     */
    public static function getAvailableStates(): array
    {
        return [
            'MG/SP' => 'Minas Gerais / São Paulo',
            'DF/ES' => 'Distrito Federal / Espírito Santo',
            'RJ' => 'Rio de Janeiro',
            'RS' => 'Rio Grande do Sul',
            'SC' => 'Santa Catarina',
            'PR' => 'Paraná',
            'BA' => 'Bahia',
            'CE' => 'Ceará',
            'PE' => 'Pernambuco',
            'GO' => 'Goiás',
            'MT' => 'Mato Grosso',
            'MS' => 'Mato Grosso do Sul',
            'RO' => 'Rondônia',
            'AC' => 'Acre',
            'AP' => 'Amapá',
            'AM' => 'Amazonas',
            'PA' => 'Pará',
            'RR' => 'Roraima',
            'TO' => 'Tocantins',
            'PI' => 'Piauí',
            'MA' => 'Maranhão',
            'RN' => 'Rio Grande do Norte',
            'PB' => 'Paraíba',
            'AL' => 'Alagoas',
            'SE' => 'Sergipe'
        ];
    }
}
