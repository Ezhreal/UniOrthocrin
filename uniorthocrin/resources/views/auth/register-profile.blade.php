<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - {{ $profileLabel }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/std-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
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
                        'sans': ['Plus Jakarta Sans', 'system-ui', 'sans-serif'],
                    },
                    boxShadow: {
                        'card': '0 4px 6px -1px rgb(0 0 0 / 0.06), 0 12px 24px -4px rgb(145 0 57 / 0.08)',
                    },
                }
            }
        };
    </script>
</head>
<body class="font-sans h-[100dvh] overflow-hidden bg-background text-text antialiased">
    <div class="flex h-[100dvh] min-h-0 flex-col lg:flex-row">
        <!-- Coluna esquerda: imagem (desktop) -->
        <div class="relative hidden min-h-0 lg:block lg:w-[42%] xl:w-[44%]">
            <div class="flex h-full min-h-0 items-stretch p-4 xl:p-6">
                <img src="{{ asset('images/login-page.jpg') }}" alt="UniOrthocrin" class="h-full min-h-0 w-full rounded-2xl object-cover shadow-card" />
            </div>
        </div>

        <!-- Coluna formulário: cabeçalho fixo + scroll só no bloco do formulário -->
        <div class="flex min-h-0 flex-1 flex-col lg:w-[58%] xl:w-[56%]">
            <div class="mx-auto flex h-full min-h-0 w-full max-w-xl flex-col px-4 pt-6 sm:px-6 lg:px-10 lg:pt-8">
                <header class="mb-4 shrink-0 text-center sm:mb-5">
                    <img src="{{ asset('images/std-ver.png') }}" alt="UniOrthocrin" class="mx-auto mb-3 h-16 w-auto object-contain sm:h-[4.5rem]">
                    <p class="mx-auto max-w-md text-sm leading-relaxed text-text">
                        Escolha seu perfil e complete os dados para acessar a plataforma.
                    </p>
                </header>

                @if ($errors->any())
                    <div class="mb-4 shrink-0 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                        <p class="font-semibold text-red-900">Corrija os itens abaixo:</p>
                        <ul class="mt-2 list-inside list-disc space-y-1 text-red-800">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="min-h-0 flex-1 overflow-y-auto overscroll-y-contain pb-6 [-webkit-overflow-scrolling:touch]">
                <div class="rounded-2xl border border-gray-200/80 bg-white p-5 shadow-card sm:p-7 lg:p-8">
                    <form action="{{ route('register.profile.store') }}" method="POST" class="space-y-8" id="register-form" novalidate>
                        @csrf

                        <!-- Perfil -->
                        <section class="space-y-3" aria-labelledby="profile-heading">
                            <div class="flex items-center gap-2">
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-xs font-bold text-primary">1</span>
                                <h2 id="profile-heading" class="text-sm font-semibold text-gray-900">Tipo de parceiro</h2>
                            </div>
                            <div class="rounded-xl border border-primary/20 bg-primary/[0.04] p-4 sm:p-5">
                                <label for="profile" class="mb-1 block text-sm font-medium text-primary">Você é? *</label>
                                <p class="mb-3 text-xs text-text">Selecione para liberar o restante do formulário.</p>
                                <select id="profile" name="profile" required
                                    class="w-full rounded-lg border border-primary/30 bg-white px-3 py-3 text-sm text-gray-800 shadow-sm transition focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/25">
                                    <option value="">Selecione uma opção</option>
                                    <option value="franquia" {{ old('profile', $profile) === 'franquia' ? 'selected' : '' }}>Sou Franquia</option>
                                    <option value="representante" {{ old('profile', $profile) === 'representante' ? 'selected' : '' }}>Sou Representante</option>
                                    <option value="lojista" {{ old('profile', $profile) === 'lojista' ? 'selected' : '' }}>Sou Lojista</option>
                                </select>
                            </div>
                        </section>

                        <div id="registration-fields" class="{{ old('profile', $profile) ? '' : 'hidden' }} space-y-8">

                            <!-- Conta -->
                            <section class="space-y-4" aria-labelledby="account-heading">
                                <div class="flex items-center gap-2">
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-xs font-bold text-primary">2</span>
                                    <h2 id="account-heading" class="text-sm font-semibold text-gray-900">Dados da conta</h2>
                                </div>
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="name" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-600">Nome completo *</label>
                                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name"
                                            class="w-full rounded-lg border border-border bg-gray-50/50 px-3 py-3 text-sm transition placeholder:text-gray-400 focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20" />
                                    </div>
                                    <div>
                                        <label for="email" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-600">E-mail *</label>
                                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                                            class="w-full rounded-lg border border-border bg-gray-50/50 px-3 py-3 text-sm transition focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20" />
                                    </div>
                                </div>
                            </section>

                            @php
                                $p = old('profile', $profile);
                                $showCompany = in_array($p, ['franquia', 'lojista', 'representante'], true) && $p !== '';
                            @endphp
                            <section id="company-fields" class="{{ $showCompany ? '' : 'hidden' }} space-y-4" aria-labelledby="company-heading">
                                <div class="flex items-center gap-2">
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-emerald-600/10 text-xs font-bold text-emerald-800">3</span>
                                    <h2 id="company-heading" class="text-sm font-semibold text-gray-900">Dados da empresa</h2>
                                </div>
                                <div class="rounded-xl border border-gray-200 bg-gradient-to-b from-gray-50/80 to-white p-4 sm:p-5">
                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div class="sm:col-span-2">
                                            <label for="razao_social" class="mb-1.5 block text-xs font-semibold text-gray-600">Razão social</label>
                                            <input id="razao_social" name="razao_social" type="text" value="{{ old('razao_social') }}" autocomplete="organization"
                                                class="w-full rounded-lg border border-border px-3 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" />
                                        </div>
                                        <div>
                                            <label for="nome_fantasia" class="mb-1.5 block text-xs font-semibold text-gray-600">Nome fantasia</label>
                                            <input id="nome_fantasia" name="nome_fantasia" type="text" value="{{ old('nome_fantasia') }}"
                                                class="w-full rounded-lg border border-border px-3 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" />
                                        </div>
                                        <div>
                                            <label for="cnpj" class="mb-1.5 block text-xs font-semibold text-gray-600">CNPJ</label>
                                            <input id="cnpj" name="cnpj" type="text" value="{{ old('cnpj') }}" inputmode="numeric" autocomplete="off"
                                                class="w-full rounded-lg border border-border px-3 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" />
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- Senha -->
                            <section class="space-y-4" aria-labelledby="security-heading">
                                <div class="flex items-center gap-2">
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-xs font-bold text-primary">4</span>
                                    <h2 id="security-heading" class="text-sm font-semibold text-gray-900">Segurança</h2>
                                </div>
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="password" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-600">Senha *</label>
                                        <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password"
                                            class="w-full rounded-lg border border-border bg-gray-50/50 px-3 py-3 text-sm focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20" />
                                        <p class="mt-1.5 text-[11px] text-text">Mínimo de 8 caracteres.</p>
                                    </div>
                                    <div>
                                        <label for="password_confirmation" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-600">Confirmar senha *</label>
                                        <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password"
                                            class="w-full rounded-lg border border-border bg-gray-50/50 px-3 py-3 text-sm focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20" />
                                    </div>
                                </div>
                            </section>

                            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                                <a href="{{ route('login') }}" class="text-center text-sm font-semibold text-primary hover:underline sm:text-left">
                                    ← Voltar para o login
                                </a>
                                <button type="submit" class="w-full rounded-full bg-primary px-8 py-3.5 text-sm font-semibold text-white shadow-md transition hover:bg-[#7a0030] hover:shadow-lg sm:w-auto">
                                    Cadastrar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <p class="mt-4 text-center text-[11px] text-text/80">
                    Ao cadastrar-se, você poderá acessar os materiais conforme o perfil Orthocrin associado à sua conta.
                </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('register-form');
            const password = document.getElementById('password');
            const confirmation = document.getElementById('password_confirmation');
            const profileSelect = document.getElementById('profile');
            const companyFields = document.getElementById('company-fields');
            const registrationFields = document.getElementById('registration-fields');

            const profilesWithCompany = ['franquia', 'lojista', 'representante'];

            const validatePasswordMatch = () => {
                if (confirmation.value && password.value !== confirmation.value) {
                    confirmation.setCustomValidity('As senhas não coincidem.');
                } else {
                    confirmation.setCustomValidity('');
                }
            };

            const updateCompanyVisibility = () => {
                if (!profileSelect || !companyFields) return;

                const v = profileSelect.value;
                if (profilesWithCompany.includes(v)) {
                    companyFields.classList.remove('hidden');
                } else {
                    companyFields.classList.add('hidden');
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
                    updateCompanyVisibility();
                    updateRegistrationVisibility();
                });
                updateCompanyVisibility();
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
