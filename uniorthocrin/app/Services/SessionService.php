<?php

namespace App\Services;

use App\Repositories\SessionRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session as LaravelSession;
use Carbon\Carbon;

class SessionService
{
    protected $sessionRepository;
    
    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }
    
    /**
     * Atualiza a atividade da sessão atual
     */
    public function updateCurrentSessionActivity(): bool
    {
        $sessionId = LaravelSession::getId();
        
        if ($sessionId && Auth::check()) {
            return $this->sessionRepository->updateActivity($sessionId);
        }
        
        return false;
    }
    
    /**
     * Obtém estatísticas de sessão para o usuário atual
     */
    public function getCurrentUserSessionStats(): array
    {
        if (!Auth::check()) {
            return [];
        }
        
        return $this->sessionRepository->getSessionStatsForUser(Auth::id());
    }
    
    /**
     * Força logout de todos os dispositivos do usuário
     */
    public function logoutFromAllDevices(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        $userId = Auth::id();
        $deletedCount = $this->sessionRepository->deleteAllSessionsForUser($userId);
        
        // Logout da sessão atual
        Auth::logout();
        LaravelSession::flush();
        
        return $deletedCount > 0;
    }
    
    /**
     * Remove uma sessão específica (logout de um dispositivo específico)
     */
    public function logoutFromDevice(string $sessionId): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        $userId = Auth::id();
        $session = $this->sessionRepository->findById($sessionId);
        
        // Verifica se a sessão pertence ao usuário atual
        if ($session && $session->user_id === $userId) {
            // Se for a sessão atual, faz logout
            if ($sessionId === LaravelSession::getId()) {
                Auth::logout();
                LaravelSession::flush();
            }
            
            return $this->sessionRepository->deleteSession($sessionId);
        }
        
        return false;
    }
    
    /**
     * Verifica se o usuário tem sessões ativas em outros dispositivos
     */
    public function hasActiveSessionsOnOtherDevices(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        $currentSessionId = LaravelSession::getId();
        $activeSessions = $this->sessionRepository->getActiveSessionsForUser(Auth::id());
        
        return $activeSessions->where('id', '!=', $currentSessionId)->isNotEmpty();
    }
    
    /**
     * Obtém lista de dispositivos ativos do usuário
     */
    public function getActiveDevices(): array
    {
        if (!Auth::check()) {
            return [];
        }
        
        $currentSessionId = LaravelSession::getId();
        $activeSessions = $this->sessionRepository->getActiveSessionsForUser(Auth::id());
        
        return $activeSessions->map(function ($session) use ($currentSessionId) {
            return [
                'id' => $session->id,
                'ip_address' => $session->ip_address,
                'user_agent' => $this->parseUserAgent($session->user_agent),
                'last_activity' => $session->last_activity,
                'is_current' => $session->id === $currentSessionId,
                'location' => $this->getLocationFromIp($session->ip_address),
            ];
        })->toArray();
    }
    
    /**
     * Limpa sessões expiradas
     */
    public function cleanupExpiredSessions(): int
    {
        return $this->sessionRepository->deleteExpiredSessions();
    }
    
    /**
     * Verifica se a sessão atual está ativa
     */
    public function isCurrentSessionActive(): bool
    {
        $sessionId = LaravelSession::getId();
        
        if ($sessionId && Auth::check()) {
            $session = $this->sessionRepository->findById($sessionId);
            return $session ? $session->isActive() : false;
        }
        
        return false;
    }
    
    /**
     * Verifica se a sessão atual expirou
     */
    public function isCurrentSessionExpired(): bool
    {
        $sessionId = LaravelSession::getId();
        
        if ($sessionId && Auth::check()) {
            $session = $this->sessionRepository->findById($sessionId);
            return $session ? $session->isExpired() : false;
        }
        
        return false;
    }
    
    /**
     * Renova a sessão atual se estiver próxima de expirar
     */
    public function refreshSessionIfNeeded(): bool
    {
        $sessionId = LaravelSession::getId();
        
        if ($sessionId && Auth::check()) {
            $session = $this->sessionRepository->findById($sessionId);
            
            if ($session && $session->last_activity) {
                // Renova se a última atividade foi há mais de 1 hora
                if ($session->last_activity->isBefore(Carbon::now()->subHour())) {
                    return $this->sessionRepository->updateActivity($sessionId);
                }
            }
        }
        
        return false;
    }
    
    /**
     * Parse do user agent para informações legíveis
     */
    protected function parseUserAgent(?string $userAgent): array
    {
        if (!$userAgent) {
            return ['browser' => 'Desconhecido', 'os' => 'Desconhecido', 'device' => 'Desconhecido'];
        }
        
        // Parse básico do user agent
        $browser = 'Desconhecido';
        $os = 'Desconhecido';
        $device = 'Desktop';
        
        if (strpos($userAgent, 'Chrome') !== false) {
            $browser = 'Chrome';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            $browser = 'Firefox';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            $browser = 'Safari';
        } elseif (strpos($userAgent, 'Edge') !== false) {
            $browser = 'Edge';
        }
        
        if (strpos($userAgent, 'Windows') !== false) {
            $os = 'Windows';
        } elseif (strpos($userAgent, 'Mac') !== false) {
            $os = 'macOS';
        } elseif (strpos($userAgent, 'Linux') !== false) {
            $os = 'Linux';
        } elseif (strpos($userAgent, 'Android') !== false) {
            $os = 'Android';
            $device = 'Mobile';
        } elseif (strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false) {
            $os = 'iOS';
            $device = 'Mobile';
        }
        
        return [
            'browser' => $browser,
            'os' => $os,
            'device' => $device,
            'raw' => $userAgent,
        ];
    }
    
    /**
     * Obtém localização aproximada do IP (mock - em produção usar serviço real)
     */
    protected function getLocationFromIp(?string $ipAddress): string
    {
        if (!$ipAddress || $ipAddress === '127.0.0.1' || $ipAddress === '::1') {
            return 'Local';
        }
        
        // Em produção, integrar com serviço de geolocalização
        // Por enquanto, retorna IP como localização
        return $ipAddress;
    }
}
