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
     * Get the state label (exibição: SP removido, DF → Outros Estados).
     */
    public function getStateLabelAttribute(): string
    {
        return self::getStateDisplayLabel($this->state ?? '');
    }

    /**
     * Retorna o rótulo de exibição para um valor de state (para uso em listas/views).
     */
    public static function getStateDisplayLabel(?string $state): string
    {
        return match ($state ?? '') {
            'MG/SP' => 'MG',
            'DF/ES' => 'Outros Estados',
            default => $state ?? 'N/A',
        };
    }

    /**
     * Get available states for folders.
     */
    public static function getAvailableStates(): array
    {
        return [
            'MG/SP' => 'MG',
            'DF/ES' => 'Outros Estados',
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
