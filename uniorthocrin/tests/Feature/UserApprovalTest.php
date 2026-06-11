<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserType;
use App\Mail\UserApproved;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserApprovalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic user types if not already seeded
        if (UserType::count() === 0) {
            UserType::create(['id' => 1, 'name' => 'Admin', 'slug' => 'admin']);
            UserType::create(['id' => 2, 'name' => 'Franqueado', 'slug' => 'franqueado']);
            UserType::create(['id' => 3, 'name' => 'Lojista', 'slug' => 'lojista']);
            UserType::create(['id' => 4, 'name' => 'Representante', 'slug' => 'representante']);
        }
    }

    public function test_new_user_registration_sets_status_to_inactive_and_flashes_info()
    {
        $response = $this->post(route('register.profile.store'), [
            'profile' => 'franquia',
            'name' => 'Test User',
            'email' => 'test@uniorthocrin.com.br',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'razao_social' => 'Razao Social Test',
            'nome_fantasia' => 'Nome Fantasia Test',
            'cnpj' => '12.345.678/0001-90',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('info', 'Cadastro realizado com sucesso! Aguarde a aprovação do administrador.');

        $this->assertDatabaseHas('users', [
            'email' => 'test@uniorthocrin.com.br',
            'status' => 'inactive',
        ]);
    }

    public function test_inactive_user_cannot_log_in()
    {
        $user = User::create([
            'name' => 'Inactive User',
            'email' => 'inactive@uniorthocrin.com.br',
            'password' => Hash::make('password123'),
            'user_type_id' => 2,
            'status' => 'inactive',
        ]);

        $response = $this->post(route('login.post'), [
            'email' => 'inactive@uniorthocrin.com.br',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Seu cadastro ainda está aguardando aprovação.');
        $this->assertGuest();
    }

    public function test_active_user_can_log_in()
    {
        $user = User::create([
            'name' => 'Active User',
            'email' => 'active@uniorthocrin.com.br',
            'password' => Hash::make('password123'),
            'user_type_id' => 2,
            'status' => 'active',
        ]);

        $response = $this->post(route('login.post'), [
            'email' => 'active@uniorthocrin.com.br',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_can_approve_inactive_user()
    {
        Mail::fake();

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@uniorthocrin.com.br',
            'password' => Hash::make('password123'),
            'user_type_id' => 1, // Admin
            'status' => 'active',
        ]);
        
        $admin->profiles()->sync([1]);

        $user = User::create([
            'name' => 'Inactive User',
            'email' => 'inactive@uniorthocrin.com.br',
            'password' => Hash::make('password123'),
            'user_type_id' => 2,
            'status' => 'inactive',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.users.approve', $user));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Usuário aprovado com sucesso.');

        $this->assertEquals('active', $user->fresh()->status);

        Mail::assertSent(UserApproved::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email) && $mail->user->id === $user->id;
        });
    }
}
