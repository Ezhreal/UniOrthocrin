<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StartOneDriveWorker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'onedrive:worker {--daemon : Run as daemon}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inicia o worker de OneDrive com configurações otimizadas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Iniciando worker de OneDrive...');
        
        if ($this->option('daemon')) {
            $this->info('📡 Modo daemon ativado');
            $this->call('queue:work', [
                'connection' => 'database',
                '--timeout' => 600,
                '--tries' => 3,
                '--memory' => 512,
                '--sleep' => 3,
                '--verbose' => true
            ]);
        } else {
            $this->info('⚡ Executando uma vez...');
            $this->call('queue:work', [
                'connection' => 'database',
                '--timeout' => 600,
                '--tries' => 3,
                '--memory' => 512,
                '--once' => true
            ]);
        }
    }
}
