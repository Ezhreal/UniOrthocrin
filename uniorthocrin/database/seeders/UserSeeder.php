<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@uniorthocrin.com',
                'password' => Hash::make('12345678'),
                'user_type_id' => 1, // ajuste conforme o tipo de usuário comum no seu sistema
                'status' => 'active',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);
            User::create([
                'name' => 'Franqueado',
                'email' => 'franqueado@uniorthocrin.com',
                'password' => Hash::make('12345678'),
                'user_type_id' => 2, // ajuste conforme o tipo de usuário comum no seu sistema
                'status' => 'active',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);
            User::create([
                'name' => 'Lojista',
                'email' => 'lojista@uniorthocrin.com',
                'password' => Hash::make('12345678'),
                'user_type_id' => 3, // ajuste conforme o tipo de usuário comum no seu sistema
                'status' => 'active',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);
            User::create([
                'name' => 'Representante',
                'email' => 'representante@uniorthocrin.com',
                'password' => Hash::make('12345678'),
                'user_type_id' => 4, // ajuste conforme o tipo de usuário comum no seu sistema
                'status' => 'active',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);
    }
}