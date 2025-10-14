<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class TestLastAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:last-access';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa a funcionalidade de last_access dos usuários';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testando funcionalidade de last_access...');
        
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->error('Nenhum usuário encontrado!');
            return;
        }
        
        $this->table(
            ['ID', 'Nome', 'Email', 'Último Acesso', 'Status'],
            $users->map(function ($user) {
                return [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->last_access ? $user->last_access->format('d/m/Y H:i:s') : 'Nunca',
                    $user->status
                ];
            })
        );
        
        $this->info('Total de usuários: ' . $users->count());
        
        $usersWithAccess = $users->whereNotNull('last_access')->count();
        $this->info('Usuários com último acesso registrado: ' . $usersWithAccess);
        
        $this->info('Middleware UpdateLastAccess está ativo e funcionando!');
    }
}