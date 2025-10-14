<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SessionService;
use App\Services\UserViewService;
use App\Services\UserNotificationService;
use Carbon\Carbon;

class CleanupOldData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:old-data 
                            {--days=365 : Número de dias para considerar dados como antigos}
                            {--type=all : Tipo de dados para limpar (sessions, views, notifications, all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpa dados antigos do sistema (sessões, visualizações, notificações)';

    protected $sessionService;
    protected $userViewService;
    protected $userNotificationService;

    /**
     * Create a new command instance.
     */
    public function __construct(
        SessionService $sessionService,
        UserViewService $userViewService,
        UserNotificationService $userNotificationService
    ) {
        parent::__construct();
        $this->sessionService = $sessionService;
        $this->userViewService = $userViewService;
        $this->userNotificationService = $userNotificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');
        $type = $this->option('type');

        $this->info("🧹 Iniciando limpeza de dados antigos (mais de {$days} dias)...");
        $this->newLine();

        $totalCleaned = 0;

        try {
            switch ($type) {
                case 'sessions':
                    $totalCleaned += $this->cleanupSessions($days);
                    break;
                    
                case 'views':
                    $totalCleaned += $this->cleanupViews($days);
                    break;
                    
                case 'notifications':
                    $totalCleaned += $this->cleanupNotifications($days);
                    break;
                    
                case 'all':
                default:
                    $totalCleaned += $this->cleanupSessions($days);
                    $totalCleaned += $this->cleanupViews($days);
                    $totalCleaned += $this->cleanupNotifications($days);
                    break;
            }

            $this->info("✅ Limpeza concluída com sucesso!");
            $this->info("📊 Total de registros removidos: {$totalCleaned}");
            
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("❌ Erro durante a limpeza: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Limpa sessões antigas
     */
    protected function cleanupSessions(int $days): int
    {
        $this->info("🔐 Limpando sessões antigas...");
        
        $deletedCount = $this->sessionService->cleanupExpiredSessions();
        
        $this->info("   - Sessões removidas: {$deletedCount}");
        
        return $deletedCount;
    }

    /**
     * Limpa visualizações antigas
     */
    protected function cleanupViews(int $days): int
    {
        $this->info("👁️ Limpando visualizações antigas...");
        
        $deletedCount = $this->userViewService->cleanupOldViews($days);
        
        $this->info("   - Visualizações removidas: {$deletedCount}");
        
        return $deletedCount;
    }

    /**
     * Limpa notificações antigas
     */
    protected function cleanupNotifications(int $days): int
    {
        $this->info("🔔 Limpando notificações antigas...");
        
        $deletedCount = $this->userNotificationService->deleteOldReadNotifications($days);
        
        $this->info("   - Notificações removidas: {$deletedCount}");
        
        return $deletedCount;
    }
}
