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
<body class="font-sans min-h-screen min-w-screen">
    <div class="flex flex-col lg:flex-row w-screen h-screen">
        <!-- Coluna Esquerda: Splash -->
        <div class="hidden lg:flex lg:w-1/2 h-full items-center justify-center p-6">
            <img src="{{ asset('images/login-page.jpg') }}" alt="UniOrthocrin" class="object-cover w-full h-full rounded-lg" />
        </div>
        <!-- Coluna Direita: Formulário -->
        <div class="w-full lg:w-1/2 h-full flex items-center justify-center p-4">
            <div class="w-full max-w-md px-4 sm:px-8">
                <div class="mb-8 flex flex-col items-center">
                    <img src="{{ asset('images/std-ver.png') }}" alt="UniOrthocrin Logo" class="mb-2 w-32 h-16 object-contain">
                </div>
                <h1 class="text-2xl font-bold text-primary mb-2 text-center">Crie sua conta</h1>
                <p class="text-text text-sm text-center mb-6 max-w-xs mx-auto">
                    Primeiro, nos conte quem é você para personalizarmos o seu acesso.
                </p>

                @if ($errors->any())
                    <div class="mb-4 text-red-500 text-xs">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('register.profile.store') }}" method="POST" class="space-y-4" id="register-form" novalidate>
                    @csrf
                    <div class="mb-4 rounded-2xl border border-primary/30 bg-primary/5 px-4 py-3">
                        <p class="text-xs font-semibold text-primary mb-2 uppercase tracking-wide">Passo 1</p>
                        <label for="profile" class="block text-sm font-semibold text-primary mb-1">Você é?</label>
                        <p class="text-[11px] text-text mb-2">
                            Escolha abaixo o tipo de parceiro que melhor representa você.
                        </p>
                        <select id="profile" name="profile" required class="w-full px-3 py-2 border border-primary rounded-md bg-white text-text focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                            <option value="">Selecione uma opção</option>
                            <option value="franquia" {{ old('profile', $profile) === 'franquia' ? 'selected' : '' }}>Sou Franquia</option>
                            <option value="representante" {{ old('profile', $profile) === 'representante' ? 'selected' : '' }}>Sou Representante</option>
                            <option value="lojista" {{ old('profile', $profile) === 'lojista' ? 'selected' : '' }}>Sou Lojista</option>
                        </select>
                    </div>

                    <div id="registration-fields" class="{{ old('profile', $profile) ? '' : 'hidden' }} space-y-4">
                    <div>
                        <label for="name" class="block text-xs font-semibold text-text mb-1">Nome completo *</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold text-text mb-1">E-mail *</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
                    </div>

                    <div id="lojista-fields" class="{{ old('profile', $profile) === 'lojista' ? '' : 'hidden' }}">
                        <div>
                            <label for="razao_social" class="block text-xs font-semibold text-text mb-1">Razão Social *</label>
                            <input id="razao_social" name="razao_social" type="text" value="{{ old('razao_social') }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
                        </div>

                        <div>
                            <label for="nome_fantasia" class="block text-xs font-semibold text-text mb-1">Nome Fantasia *</label>
                            <input id="nome_fantasia" name="nome_fantasia" type="text" value="{{ old('nome_fantasia') }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold text-text mb-1">Senha *</label>
                        <input id="password" name="password" type="password" required minlength="8" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-text mb-1">Confirmar Senha *</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('login') }}" class="text-primary text-xs sm:text-sm font-semibold hover:underline">Voltar para login</a>
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-full font-semibold hover:bg-secondary transition">Cadastrar</button>
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('register-form');
            const password = document.getElementById('password');
            const confirmation = document.getElementById('password_confirmation');
            const profileSelect = document.getElementById('profile');
            const lojistaFields = document.getElementById('lojista-fields');
            const lojistaInputs = lojistaFields ? lojistaFields.querySelectorAll('input') : [];
            const registrationFields = document.getElementById('registration-fields');

            const validatePasswordMatch = () => {
                if (confirmation.value && password.value !== confirmation.value) {
                    confirmation.setCustomValidity('As senhas não coincidem.');
                } else {
                    confirmation.setCustomValidity('');
                }
            };

            const updateLojistaVisibility = () => {
                if (!profileSelect || !lojistaFields) return;

                if (profileSelect.value === 'lojista') {
                    lojistaFields.classList.remove('hidden');
                    lojistaInputs.forEach((input) => {
                        input.required = true;
                    });
                } else {
                    lojistaFields.classList.add('hidden');
                    lojistaInputs.forEach((input) => {
                        input.required = false;
                        input.setCustomValidity('');
                    });
                }
            };

            const updateRegistrationVisibility = () => {
                if (!profileSelect || !registrationFields) return;

                if (profileSelect.value) {
                    registrationFields.classList.remove('hidden');
                } else {
                    registrationFields.classList.add('hidden');
                }
            };

            if (profileSelect) {
                profileSelect.addEventListener('change', () => {
                    updateLojistaVisibility();
                    updateRegistrationVisibility();
                });
                // Estado inicial baseado no valor atual
                updateLojistaVisibility();
                updateRegistrationVisibility();
            }

            form.querySelectorAll('input[required], select[required]').forEach((input) => {
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
