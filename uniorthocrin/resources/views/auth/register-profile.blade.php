<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - {{ $profileLabel }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/std-icon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#910039',
                        'secondary': '#FEAD00',
                        'text': '#747474',
                        'border': '#DDDDDD',
                        'background': '#F9F9F9',
                    },
                    fontFamily: {
                        'sans': ['Inter', 'sans-serif'],
                    }
                }
            }
        };
    </script>
</head>
<body class="font-sans min-h-screen bg-background py-8 px-4">
    <div class="max-w-xl mx-auto bg-white border border-border rounded-xl p-6">
        <div class="text-center mb-6">
            <img src="{{ asset('images/std-ver.png') }}" alt="UniOrthocrin" class="mx-auto mb-3 w-32 h-20 object-contain">
            <h1 class="text-2xl font-bold text-primary">Cadastro {{ $profileLabel }}</h1>
        </div>

        @if ($errors->any())
            <div class="mb-4 text-red-500 text-sm">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register.profile.store', $profile) }}" method="POST" class="space-y-4" id="register-form" novalidate>
            @csrf
            <div>
                <label for="name" class="block text-xs font-semibold text-text mb-1">Nome completo *</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold text-text mb-1">E-mail *</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
            </div>

            @if ($profile === 'lojista')
                <div>
                    <label for="razao_social" class="block text-xs font-semibold text-text mb-1">Razão Social *</label>
                    <input id="razao_social" name="razao_social" type="text" value="{{ old('razao_social') }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
                </div>

                <div>
                    <label for="nome_fantasia" class="block text-xs font-semibold text-text mb-1">Nome Fantasia *</label>
                    <input id="nome_fantasia" name="nome_fantasia" type="text" value="{{ old('nome_fantasia') }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
                </div>
            @endif

            <div>
                <label for="password" class="block text-xs font-semibold text-text mb-1">Senha *</label>
                <input id="password" name="password" type="password" required minlength="8" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-text mb-1">Confirmar Senha *</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
            </div>

            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('register.select') }}" class="text-primary text-sm font-semibold hover:underline">Voltar</a>
                <button type="submit" class="px-6 py-2 bg-primary text-white rounded-full font-semibold hover:bg-secondary transition">Cadastrar</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('register-form');
            const password = document.getElementById('password');
            const confirmation = document.getElementById('password_confirmation');

            const validatePasswordMatch = () => {
                if (confirmation.value && password.value !== confirmation.value) {
                    confirmation.setCustomValidity('As senhas não coincidem.');
                } else {
                    confirmation.setCustomValidity('');
                }
            };

            form.querySelectorAll('input[required]').forEach((input) => {
                input.addEventListener('input', () => {
                    if (input.validity.valueMissing) {
                        input.setCustomValidity('Este campo é obrigatório.');
                    } else {
                        input.setCustomValidity('');
                    }
                    if (input.type === 'email' && input.value && input.validity.typeMismatch) {
                        input.setCustomValidity('Informe um e-mail válido.');
                    }
                    validatePasswordMatch();
                });
            });

            password.addEventListener('input', validatePasswordMatch);
            confirmation.addEventListener('input', validatePasswordMatch);
        });
    </script>
</body>
</html>
