<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    private const PROFILE_TYPES = [
        'franquia' => 2,
        'lojista' => 3,
        'representante' => 4,
    ];

    public function showForm(Request $request, ?string $profile = null)
    {
        $profile = $request->old('profile', $profile ?? 'franquia');
        if (! $this->isValidProfile($profile)) {
            $profile = 'franquia';
        }

        return view('auth.register-profile', [
            'profile' => $profile,
            'profileLabel' => $this->profileLabel($profile),
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'profile' => ['required', 'string', Rule::in(array_keys(self::PROFILE_TYPES))],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'razao_social' => ['nullable', 'string', 'max:255'],
            'nome_fantasia' => ['nullable', 'string', 'max:255'],
            'cnpj' => ['nullable', 'string', 'max:20'],
        ];

        $validated = $request->validate($rules);

        $profile = $validated['profile'];
        $userTypeId = $this->resolveUserTypeId($profile);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'user_type_id' => $userTypeId,
            'status' => 'active',
        ];

        if (in_array($profile, ['franquia', 'lojista', 'representante'], true)) {
            $payload['razao_social'] = $request->input('razao_social');
            $payload['nome_fantasia'] = $request->input('nome_fantasia');
            $payload['cnpj'] = $request->input('cnpj');
        }

        User::create($payload);

        return redirect()
            ->route('login')
            ->with('success', 'Cadastro realizado com sucesso. Faça login para continuar.');
    }

    private function isValidProfile(string $profile): bool
    {
        return array_key_exists($profile, self::PROFILE_TYPES);
    }

    private function profileLabel(string $profile): string
    {
        return match ($profile) {
            'franquia' => 'Franquia',
            'representante' => 'Representante',
            'lojista' => 'Lojista',
            default => 'Perfil',
        };
    }

    private function resolveUserTypeId(string $profile): int
    {
        $fallback = self::PROFILE_TYPES[$profile];

        $nameLike = match ($profile) {
            'franquia' => ['franqueado', 'franquia'],
            'representante' => ['representante'],
            'lojista' => ['lojista'],
            default => [],
        };

        foreach ($nameLike as $needle) {
            $found = UserType::query()
                ->whereRaw('LOWER(name) LIKE ?', ['%' . mb_strtolower($needle) . '%'])
                ->value('id');

            if ($found) {
                return (int) $found;
            }
        }

        return $fallback;
    }
}
