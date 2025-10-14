<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdatePasswords extends Command
{
    protected $signature = 'users:update-passwords';
    protected $description = 'Update user passwords to use Bcrypt';

    public function handle()
    {
        $this->info('Atualizando senhas dos usuários...');

        $users = User::all();
        
        foreach ($users as $user) {
            $user->password = Hash::make('12345678');
            $user->save();
            $this->line("Senha atualizada para: {$user->email}");
        }

        $this->info('Todas as senhas foram atualizadas para: 12345678');
    }
}
