<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Session extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'id',
        'user_id',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity',
    ];
    
    protected $casts = [
        'last_activity' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    /**
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Verifica se a sessão está ativa (última atividade há menos de 2 horas)
     */
    public function isActive(): bool
    {
        return $this->last_activity && 
               $this->last_activity->isAfter(Carbon::now()->subHours(2));
    }
    
    /**
     * Verifica se a sessão expirou (última atividade há mais de 24 horas)
     */
    public function isExpired(): bool
    {
        return $this->last_activity && 
               $this->last_activity->isBefore(Carbon::now()->subHours(24));
    }
    
    /**
     * Atualiza a última atividade da sessão
     */
    public function updateActivity(): void
    {
        $this->update(['last_activity' => Carbon::now()]);
    }
    
    /**
     * Escopo para sessões ativas
     */
    public function scopeActive($query)
    {
        return $query->where('last_activity', '>', Carbon::now()->subHours(2));
    }
    
    /**
     * Escopo para sessões expiradas
     */
    public function scopeExpired($query)
    {
        return $query->where('last_activity', '<', Carbon::now()->subHours(24));
    }
    
    /**
     * Escopo para sessões de um usuário específico
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
