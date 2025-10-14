<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Carbon\Carbon;

class UserNotification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'related_type',
        'related_id',
        'read_at',
    ];
    
    protected $casts = [
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    /**
     * Tipos de notificação disponíveis
     */
    const TYPE_INFO = 'info';
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_ERROR = 'error';
    
    /**
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Relacionamento polimórfico com o item relacionado
     */
    public function related(): MorphTo
    {
        return $this->morphTo();
    }
    
    /**
     * Verifica se a notificação foi lida
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }
    
    /**
     * Marca a notificação como lida
     */
    public function markAsRead(): void
    {
        $this->update(['read_at' => Carbon::now()]);
    }
    
    /**
     * Marca a notificação como não lida
     */
    public function markAsUnread(): void
    {
        $this->update(['read_at' => null]);
    }
    
    /**
     * Cria uma nova notificação
     */
    public static function createNotification($userId, $title, $message, $type = self::TYPE_INFO, $relatedType = null, $relatedId = null): self
    {
        return static::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'related_type' => $relatedType,
            'related_id' => $relatedId,
        ]);
    }
    
    /**
     * Cria notificação para conteúdo novo não visualizado
     */
    public static function createNewContentNotification($userId, $contentType, $contentId, $contentTitle): self
    {
        $typeLabels = [
            'App\Models\Campaign' => 'Campanha',
            'App\Models\Product' => 'Produto',
            'App\Models\Training' => 'Treinamento',
            'App\Models\Library' => 'Biblioteca',
            'App\Models\News' => 'Notícia',
        ];
        
        $typeLabel = $typeLabels[$contentType] ?? 'Conteúdo';
        
        return static::create([
            'user_id' => $userId,
            'title' => "Novo {$typeLabel} disponível",
            'message' => "{$typeLabel}: {$contentTitle}",
            'type' => self::TYPE_INFO,
            'related_type' => $contentType,
            'related_id' => $contentId,
        ]);
    }
    
    /**
     * Escopo para notificações não lidas
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
    
    /**
     * Escopo para notificações lidas
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }
    
    /**
     * Escopo para notificações de um usuário específico
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    
    /**
     * Escopo para notificações de um tipo específico
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
    
    /**
     * Escopo para notificações recentes
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>', Carbon::now()->subDays($days));
    }
    
    /**
     * Escopo para notificações relacionadas a um item específico
     */
    public function scopeRelatedTo($query, $type, $id)
    {
        return $query->where([
            'related_type' => $type,
            'related_id' => $id,
        ]);
    }
} 