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

        if (in_array($user->user_type_id, [2, 3, 4], true)) {
            $validationRules['razao_social'] = 'nullable|string|max:255';
            $validationRules['nome_fantasia'] = 'nullable|string|max:255';
            $validationRules['cnpj'] = 'nullable|string|max:20';
        }

        $validated = $request->validate($validationRules);

        $payload = ['name' => $validated['name']];
        if (in_array($user->user_type_id, [2, 3, 4], true)) {
            $payload['razao_social'] = $request->input('razao_social');
            $payload['nome_fantasia'] = $request->input('nome_fantasia');
            $payload['cnpj'] = $request->input('cnpj');
            $payload['representante_nome'] = null;
            $payload['cpf_cnpj'] = null;
        }

        try {
            $this->userAccountService->updateProfile($user, $payload);
            
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
