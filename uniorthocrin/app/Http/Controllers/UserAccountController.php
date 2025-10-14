<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserAccountService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserAccountController extends Controller
{
    private $userAccountService;

    public function __construct(UserAccountService $userAccountService)
    {
        $this->userAccountService = $userAccountService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        return view('my-account', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $validationRules = [
            'name' => 'required|string|max:255',
        ];

        // Validações condicionais baseadas no tipo de usuário
        if ($user->user_type_id == 2 || $user->user_type_id == 3) { // Franqueado ou Lojista
            $validationRules['razao_social'] = 'required|string|max:255';
            $validationRules['nome_fantasia'] = 'required|string|max:255';
            $validationRules['cpf_cnpj'] = 'required|string|max:20';
        } elseif ($user->user_type_id == 4) { // Representante
            $validationRules['representante_nome'] = 'required|string|max:255';
            $validationRules['cpf_cnpj'] = 'required|string|max:20';
        }

        $validated = $request->validate($validationRules);

        try {
            $this->userAccountService->updateProfile($user, $validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Perfil atualizado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar perfil: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $this->userAccountService->updatePassword($user, $validated['password']);
            
            return response()->json([
                'success' => true,
                'message' => 'Senha alterada com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar senha: ' . $e->getMessage()
            ], 500);
        }
    }
}
