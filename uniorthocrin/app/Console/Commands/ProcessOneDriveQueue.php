<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;

class ProcessOneDriveQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'onedrive:process {--timeout=600 : Timeout em segundos} {--tries=3 : Número de tentativas}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processa filas de upload do OneDrive com configurações otimizadas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $timeout = $this->option('timeout');
        $tries = $this->option('tries');

        $this->info("Iniciando worker de filas OneDrive...");
        $this->info("Timeout: {$timeout}s | Tentativas: {$tries}");

        // Executar worker com configurações otimizadas
        $this->call('queue:work', [
            'connection' => 'database',
            '--timeout' => $timeout,
            '--tries' => $tries,
            '--memory' => 512,
            '--sleep' => 3,
            '--verbose' => true
        ]);
    }
}
