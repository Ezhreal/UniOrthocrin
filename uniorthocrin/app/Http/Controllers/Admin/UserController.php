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

        $userTypeId = (int) $request->user_type_id;

        if (in_array($userTypeId, [2, 3, 4], true)) {
            $validationRules['razao_social'] = 'nullable|string|max:255';
            $validationRules['nome_fantasia'] = 'nullable|string|max:255';
            $validationRules['cnpj'] = 'nullable|string|max:20';
        }

        $request->validate($validationRules);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type_id' => $userTypeId,
            'status' => $request->status,
        ];

        if (in_array($userTypeId, [2, 3, 4], true)) {
            $userData['razao_social'] = $request->input('razao_social');
            $userData['nome_fantasia'] = $request->input('nome_fantasia');
            $userData['cnpj'] = $request->input('cnpj');
            $userData['representante_nome'] = null;
            $userData['cpf_cnpj'] = null;
        }

        User::create($userData);

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

        $userTypeId = (int) $request->user_type_id;

        if (in_array($userTypeId, [2, 3, 4], true)) {
            $validationRules['razao_social'] = 'nullable|string|max:255';
            $validationRules['nome_fantasia'] = 'nullable|string|max:255';
            $validationRules['cnpj'] = 'nullable|string|max:20';
        }

        $request->validate($validationRules);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'user_type_id' => $userTypeId,
            'status' => $request->status,
        ];

        if (in_array($userTypeId, [2, 3, 4], true)) {
            $data['razao_social'] = $request->input('razao_social');
            $data['nome_fantasia'] = $request->input('nome_fantasia');
            $data['cnpj'] = $request->input('cnpj');
            $data['representante_nome'] = null;
            $data['cpf_cnpj'] = null;
        } else {
            $data['razao_social'] = null;
            $data['nome_fantasia'] = null;
            $data['cnpj'] = null;
            $data['representante_nome'] = null;
            $data['cpf_cnpj'] = null;
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

        if (in_array($user->user_type_id, [2, 3, 4], true)) {
            $validationRules['razao_social'] = 'nullable|string|max:255';
            $validationRules['nome_fantasia'] = 'nullable|string|max:255';
            $validationRules['cnpj'] = 'nullable|string|max:20';
        }

        $validated = $request->validate($validationRules);

        $data = [
            'name' => $validated['name'],
        ];

        if (in_array($user->user_type_id, [2, 3, 4], true)) {
            $data['razao_social'] = $request->input('razao_social');
            $data['nome_fantasia'] = $request->input('nome_fantasia');
            $data['cnpj'] = $request->input('cnpj');
            $data['representante_nome'] = null;
            $data['cpf_cnpj'] = null;
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
