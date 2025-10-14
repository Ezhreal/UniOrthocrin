<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Carbon\Carbon;

class UserView extends Model
{
    protected $fillable = [
        'user_id',
        'viewable_type',
        'viewable_id',
        'first_viewed_at',
        'last_viewed_at',
        'view_count',
        'download_count',
    ];
    
    protected $casts = [
        'first_viewed_at' => 'datetime',
        'last_viewed_at' => 'datetime',
        'view_count' => 'integer',
        'download_count' => 'integer',
    ];
    
    /**
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Relacionamento polimórfico com o item visualizado
     */
    public function viewable(): MorphTo
    {
        return $this->morphTo();
    }
    
    /**
     * Registra uma nova visualização
     */
    public static function recordView($userId, $viewableType, $viewableId): self
    {
        $view = static::firstOrNew([
            'user_id' => $userId,
            'viewable_type' => $viewableType,
            'viewable_id' => $viewableId,
        ]);
        
        if ($view->exists) {
            // Atualizar visualização existente
            $view->update([
                'last_viewed_at' => Carbon::now(),
                'view_count' => $view->view_count + 1,
            ]);
        } else {
            // Criar nova visualização
            $view->fill([
                'first_viewed_at' => Carbon::now(),
                'last_viewed_at' => Carbon::now(),
                'view_count' => 1,
                'download_count' => 0,
            ]);
            $view->save();
        }
        
        return $view;
    }
    
    /**
     * Registra um download
     */
    public function recordDownload(): void
    {
        $this->increment('download_count');
        $this->update(['last_viewed_at' => Carbon::now()]);
    }
    
    /**
     * Verifica se o usuário já viu este item
     */
    public static function hasUserViewed($userId, $viewableType, $viewableId): bool
    {
        return static::where([
            'user_id' => $userId,
            'viewable_type' => $viewableType,
            'viewable_id' => $viewableId,
        ])->exists();
    }
    
    /**
     * Obtém a última visualização de um usuário para um item específico
     */
    public static function getLastView($userId, $viewableType, $viewableId): ?self
    {
        return static::where([
            'user_id' => $userId,
            'viewable_type' => $viewableType,
            'viewable_id' => $viewableId,
        ])->first();
    }
    
    /**
     * Escopo para visualizações de um usuário específico
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    
    /**
     * Escopo para um tipo específico de conteúdo
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('viewable_type', $type);
    }
    
    /**
     * Escopo para visualizações recentes
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('last_viewed_at', '>', Carbon::now()->subDays($days));
    }
} 