<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications_optimized';

    protected $fillable = [
        'title',
        'message',
        'type',
        'target_type',
        'target_ids',
        'read_by',
        'related_type',
        'related_id',
    ];

    protected $casts = [
        'target_ids' => 'array',
        'read_by' => 'array',
    ];

    /**
     * Relacionamento polimórfico para conteúdo relacionado
     */
    public function related(): MorphTo
    {
        return $this->morphTo('related');
    }

    /**
     * Verificar se um usuário leu esta notificação
     */
    public function isReadBy(int $userId): bool
    {
        return in_array($userId, $this->read_by ?? []);
    }

    /**
     * Marcar como lida por um usuário
     */
    public function markAsReadBy(int $userId): void
    {
        $readBy = $this->read_by ?? [];
        if (!in_array($userId, $readBy)) {
            $readBy[] = $userId;
            $this->update(['read_by' => $readBy]);
        }
    }

    /**
     * Marcar como não lida por um usuário
     */
    public function markAsUnreadBy(int $userId): void
    {
        $readBy = $this->read_by ?? [];
        $readBy = array_filter($readBy, fn($id) => $id !== $userId);
        $this->update(['read_by' => array_values($readBy)]);
    }

    /**
     * Contar quantos usuários leram
     */
    public function getReadCountAttribute(): int
    {
        return count($this->read_by ?? []);
    }

    /**
     * Contar quantos usuários devem receber (estimativa)
     */
    public function getTotalTargetCountAttribute(): int
    {
        switch ($this->target_type) {
            case 'all':
                return User::where('status', 'active')->count();
            case 'user_types':
                return User::whereIn('user_type_id', $this->target_ids ?? [])
                    ->where('status', 'active')
                    ->count();
            case 'specific_users':
                return count($this->target_ids ?? []);
            default:
                return 0;
        }
    }

    /**
     * Buscar notificações para um usuário específico
     */
    public static function forUser(int $userId): \Illuminate\Database\Eloquent\Builder
    {
        return static::where(function ($query) use ($userId) {
            $query->where('target_type', 'all')
                ->orWhere(function ($q) use ($userId) {
                    $q->where('target_type', 'user_types')
                      ->whereJsonContains('target_ids', User::find($userId)?->user_type_id);
                })
                ->orWhere(function ($q) use ($userId) {
                    $q->where('target_type', 'specific_users')
                      ->whereJsonContains('target_ids', $userId);
                });
        });
    }

    /**
     * Buscar notificações não lidas para um usuário
     */
    public static function unreadForUser(int $userId): \Illuminate\Database\Eloquent\Builder
    {
        return static::forUser($userId)
            ->whereJsonDoesntContain('read_by', $userId);
    }

    /**
     * Contar notificações não lidas para um usuário
     */
    public static function unreadCountForUser(int $userId): int
    {
        return static::unreadForUser($userId)->count();
    }
}