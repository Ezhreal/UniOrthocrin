<?php

namespace App\Console\Commands;

use App\Models\OneDriveSync;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupOrphanedOneDriveSyncs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'onedrive:cleanup {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove OneDrive sync records for files that no longer exist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('🔍 Modo dry-run: apenas mostrando o que seria deletado');
        } else {
            $this->info('🧹 Limpando registros órfãos de sincronização OneDrive...');
        }

        $orphanedCount = 0;
        $totalCount = OneDriveSync::count();
        
        $this->info("📊 Total de registros de sync: {$totalCount}");

        $syncs = OneDriveSync::all();
        
        foreach ($syncs as $sync) {
            $fullPath = storage_path('app/' . $sync->file_path);
            
            if (!file_exists($fullPath)) {
                $orphanedCount++;
                
                if ($dryRun) {
                    $this->warn("❌ Orfão: ID {$sync->id} - {$sync->file_path} (Status: {$sync->status})");
                } else {
                    $this->line("🗑️  Removendo: ID {$sync->id} - {$sync->file_path}");
                    $sync->delete();
                }
            }
        }

        if ($orphanedCount === 0) {
            $this->info('✅ Nenhum registro órfão encontrado!');
        } else {
            if ($dryRun) {
                $this->warn("⚠️  Encontrados {$orphanedCount} registros órfãos que seriam removidos");
                $this->info('💡 Execute sem --dry-run para remover');
            } else {
                $this->info("✅ Removidos {$orphanedCount} registros órfãos");
            }
        }

        return 0;
    }
}
