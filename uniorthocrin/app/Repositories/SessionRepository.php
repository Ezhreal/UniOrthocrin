<?php

namespace App\Repositories;

use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SessionRepository
{
    protected $model;
    
    public function __construct(Session $model)
    {
        $this->model = $model;
    }
    
    /**
     * Obtém todas as sessões ativas de um usuário
     */
    public function getActiveSessionsForUser(int $userId): Collection
    {
        return $this->model
            ->forUser($userId)
            ->active()
            ->orderBy('last_activity', 'desc')
            ->get();
    }
    
    /**
     * Obtém todas as sessões expiradas
     */
    public function getExpiredSessions(): Collection
    {
        return $this->model
            ->expired()
            ->get();
    }
    
    /**
     * Obtém uma sessão específica
     */
    public function findById(string $sessionId): ?Session
    {
        return $this->model->find($sessionId);
    }
    
    /**
     * Atualiza a atividade de uma sessão
     */
    public function updateActivity(string $sessionId): bool
    {
        $session = $this->findById($sessionId);
        
        if ($session) {
            $session->updateActivity();
            return true;
        }
        
        return false;
    }
    
    /**
     * Remove uma sessão específica
     */
    public function deleteSession(string $sessionId): bool
    {
        $session = $this->findById($sessionId);
        
        if ($session) {
            return $session->delete();
        }
        
        return false;
    }
    
    /**
     * Remove todas as sessões de um usuário (logout de todos os dispositivos)
     */
    public function deleteAllSessionsForUser(int $userId): int
    {
        return $this->model
            ->forUser($userId)
            ->delete();
    }
    
    /**
     * Remove todas as sessões expiradas
     */
    public function deleteExpiredSessions(): int
    {
        $expiredSessions = $this->getExpiredSessions();
        $count = $expiredSessions->count();
        
        $expiredSessions->each(function ($session) {
            $session->delete();
        });
        
        return $count;
    }
    
    /**
     * Obtém estatísticas de sessão para um usuário
     */
    public function getSessionStatsForUser(int $userId): array
    {
        $activeSessions = $this->getActiveSessionsForUser($userId);
        $totalSessions = $this->model->forUser($userId)->count();
        
        return [
            'active_sessions' => $activeSessions->count(),
            'total_sessions' => $totalSessions,
            'last_activity' => $activeSessions->first()?->last_activity,
            'devices' => $activeSessions->map(function ($session) {
                return [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'user_agent' => $session->user_agent,
                    'last_activity' => $session->last_activity,
                ];
            })->toArray(),
        ];
    }
    
    /**
     * Verifica se um usuário tem sessões ativas
     */
    public function hasActiveSessions(int $userId): bool
    {
        return $this->model
            ->forUser($userId)
            ->active()
            ->exists();
    }
    
    /**
     * Obtém a sessão mais recente de um usuário
     */
    public function getLatestSessionForUser(int $userId): ?Session
    {
        return $this->model
            ->forUser($userId)
            ->orderBy('last_activity', 'desc')
            ->first();
    }
}
