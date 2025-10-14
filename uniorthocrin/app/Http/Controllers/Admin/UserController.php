<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserType;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('userType');

        if ($search = $request->get('search')) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
        }

        if ($userType = $request->get('user_type')) {
            $query->where('user_type_id', $userType);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $users = $query->orderBy('name')->paginate(10);
        $userTypes = UserType::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'userTypes'));
    }

    public function create()
    {
        $userTypes = UserType::orderBy('name')->get();
        return view('admin.users.create', compact('userTypes'));
    }

    public function store(Request $request)
    {
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'user_type_id' => 'required|exists:user_types,id',
            'status' => 'required|in:active,inactive',
        ];

        $messages = [
            'cpf_cnpj.required' => 'O campo CPF/CNPJ é obrigatório.',
            'razao_social.required' => 'O campo Razão Social é obrigatório.',
            'nome_fantasia.required' => 'O campo Nome Fantasia é obrigatório.',
            'representante_nome.required' => 'O campo Nome do Representante é obrigatório.',
        ];

        // Validações condicionais baseadas no tipo de usuário
        $userTypeId = $request->user_type_id;
        
        if ($userTypeId == 2 || $userTypeId == 3) { // Franqueado ou Lojista
            $validationRules['razao_social'] = 'required|string|max:255';
            $validationRules['nome_fantasia'] = 'required|string|max:255';
            $validationRules['cpf_cnpj'] = 'required|string|max:20';
        } elseif ($userTypeId == 4) { // Representante
            $validationRules['representante_nome'] = 'required|string|max:255';
            $validationRules['cpf_cnpj'] = 'required|string|max:20';
        }
        
        $request->validate($validationRules, $messages);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type_id' => $request->user_type_id,
            'status' => $request->status,
        ];

        // Adicionar campos condicionais baseados no tipo de usuário
        if ($userTypeId == 2 || $userTypeId == 3) { // Franqueado ou Lojista
            $userData['razao_social'] = $request->razao_social;
            $userData['nome_fantasia'] = $request->nome_fantasia;
            $userData['cpf_cnpj'] = $request->cpf_cnpj;
        } elseif ($userTypeId == 4) { // Representante
            $userData['representante_nome'] = $request->representante_nome;
            $userData['cpf_cnpj'] = $request->cpf_cnpj;
        }

        $user = User::create($userData);


        return redirect()->route('admin.users.index')->with('success', 'Usuário criado com sucesso!');
    }

    public function show(User $user)
    {
        $user->load('userType');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $userTypes = UserType::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'userTypes'));
    }

    public function update(Request $request, User $user)
    {
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'user_type_id' => 'required|exists:user_types,id',
            'status' => 'required|in:active,inactive',
        ];

        // Validações condicionais baseadas no tipo de usuário
        $userTypeId = $request->user_type_id;
        
        if ($userTypeId == 2 || $userTypeId == 3) { // Franqueado ou Lojista
            $validationRules['razao_social'] = 'required|string|max:255';
            $validationRules['nome_fantasia'] = 'required|string|max:255';
            $validationRules['cpf_cnpj'] = 'required|string|max:20';
        } elseif ($userTypeId == 4) { // Representante
            $validationRules['representante_nome'] = 'required|string|max:255';
            $validationRules['cpf_cnpj'] = 'required|string|max:20';
        }

        $request->validate($validationRules);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'user_type_id' => $request->user_type_id,
            'status' => $request->status,
        ];

        // Adicionar campos condicionais baseados no tipo de usuário
        if ($userTypeId == 2 || $userTypeId == 3) { // Franqueado ou Lojista
            $data['razao_social'] = $request->razao_social;
            $data['nome_fantasia'] = $request->nome_fantasia;
            $data['cpf_cnpj'] = $request->cpf_cnpj;
        } elseif ($userTypeId == 4) { // Representante
            $data['representante_nome'] = $request->representante_nome;
            $data['cpf_cnpj'] = $request->cpf_cnpj;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuário deletado com sucesso!');
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        return view('admin.profile.index', compact('user'));
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

        $data = [
            'name' => $validated['name'],
        ];

        // Adicionar campos condicionais baseados no tipo de usuário
        if ($user->user_type_id == 2 || $user->user_type_id == 3) { // Franqueado ou Lojista
            $data['razao_social'] = $validated['razao_social'];
            $data['nome_fantasia'] = $validated['nome_fantasia'];
            $data['cpf_cnpj'] = $validated['cpf_cnpj'];
        } elseif ($user->user_type_id == 4) { // Representante
            $data['representante_nome'] = $validated['representante_nome'];
            $data['cpf_cnpj'] = $validated['cpf_cnpj'];
        }

        $user->update($data);

        return redirect()->route('admin.profile')->with('success', 'Perfil atualizado com sucesso!');
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'A senha atual está incorreta.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.profile')->with('success', 'Senha alterada com sucesso!');
    }

}
