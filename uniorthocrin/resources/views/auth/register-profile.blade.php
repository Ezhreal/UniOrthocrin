<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - UniOrthocrin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/std-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
                        'modal': '0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1)',
                    },
                }
            }
        };
    </script>
</head>
<body class="font-sans h-[100dvh] overflow-hidden bg-background text-text antialiased">

    <!-- Modal de Seleção Obrigatória de Perfil -->
    <div id="profile-selection-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 overflow-y-auto transition-opacity duration-300 {{ old('profile', $profile) ? 'hidden' : '' }}" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="relative w-full max-w-xl rounded-3xl bg-white p-6 sm:p-8 shadow-modal border border-gray-100 my-8">
            
            <!-- Cabeçalho do Modal -->
            <div class="text-center mb-6 sm:mb-8">
                <img src="{{ asset('images/std-ver.png') }}" alt="UniOrthocrin" class="mx-auto mb-4 h-14 sm:h-16 w-auto object-contain">
                <span class="inline-block px-3 py-1 bg-primary/10 text-primary text-xs font-bold rounded-full uppercase tracking-wider mb-2">Primeiro Passo do Cadastro</span>
                <h2 id="modal-title" class="text-xl sm:text-2xl font-bold text-gray-900 leading-tight">
                    👋 Olá! Seja bem-vindo(a) à UniOrthocrin
                </h2>
                <p class="mt-2 text-sm text-gray-600 leading-relaxed max-w-md mx-auto">
                    Para configurarmos os acessos e materiais corretos para você, nos diga em qual perfil você se encaixa:
                </p>
            </div>

            <!-- Lista de Opções de Perfil -->
            <div class="space-y-3.5 sm:space-y-4">
                <!-- Opção 1: Franqueado -->
                <button type="button" 
                        class="btn-profile-card group w-full text-left p-4 sm:p-5 rounded-2xl border-2 border-gray-200 hover:border-primary bg-white hover:bg-primary/[0.02] transition-all duration-200 shadow-sm hover:shadow-md flex items-center gap-4 cursor-pointer"
                        data-profile="franquia"
                        data-title="Franqueado"
                        data-icon="fa-shop"
                        data-desc="Exclusivo para franqueados oficiais e proprietários de lojas exclusivas Orthocrin.">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center text-xl sm:text-2xl group-hover:bg-primary group-hover:text-white transition-colors shrink-0">
                        <i class="fa-solid fa-shop"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="mb-1">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 group-hover:text-primary transition-colors">Franqueado</h3>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">
                            Exclusivo para franqueados oficiais e proprietários de lojas exclusivas Orthocrin.
                        </p>
                    </div>
                    <div class="text-gray-300 group-hover:text-primary group-hover:translate-x-1 transition-all pr-1">
                        <i class="fa-solid fa-chevron-right text-lg"></i>
                    </div>
                </button>

                <!-- Opção 2: Lojista Multimarca -->
                <button type="button" 
                        class="btn-profile-card group w-full text-left p-4 sm:p-5 rounded-2xl border-2 border-gray-200 hover:border-primary bg-white hover:bg-primary/[0.02] transition-all duration-200 shadow-sm hover:shadow-md flex items-center gap-4 cursor-pointer"
                        data-profile="lojista"
                        data-title="Lojista Multimarca"
                        data-icon="fa-store"
                        data-desc="Para lojistas e revendedores parceiros que comercializam produtos e linhas Orthocrin.">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center text-xl sm:text-2xl group-hover:bg-primary group-hover:text-white transition-colors shrink-0">
                        <i class="fa-solid fa-store"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="mb-1">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 group-hover:text-primary transition-colors">Lojista Multimarca</h3>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">
                            Para lojistas e revendedores parceiros que comercializam produtos e linhas Orthocrin.
                        </p>
                    </div>
                    <div class="text-gray-300 group-hover:text-primary group-hover:translate-x-1 transition-all pr-1">
                        <i class="fa-solid fa-chevron-right text-lg"></i>
                    </div>
                </button>

                <!-- Opção 3: Representante -->
                <button type="button" 
                        class="btn-profile-card group w-full text-left p-4 sm:p-5 rounded-2xl border-2 border-gray-200 hover:border-primary bg-white hover:bg-primary/[0.02] transition-all duration-200 shadow-sm hover:shadow-md flex items-center gap-4 cursor-pointer"
                        data-profile="representante"
                        data-title="Representante"
                        data-icon="fa-user-tie"
                        data-desc="Para representantes comerciais e equipes de vendas autorizadas Orthocrin.">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center text-xl sm:text-2xl group-hover:bg-primary group-hover:text-white transition-colors shrink-0">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="mb-1">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 group-hover:text-primary transition-colors">Representante</h3>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">
                            Para representantes comerciais e equipes de vendas autorizadas Orthocrin.
                        </p>
                    </div>
                    <div class="text-gray-300 group-hover:text-primary group-hover:translate-x-1 transition-all pr-1">
                        <i class="fa-solid fa-chevron-right text-lg"></i>
                    </div>
                </button>
            </div>

            <!-- Rodapé do Modal -->
            <div class="mt-6 sm:mt-8 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                <span>Passo 1 de 2: Definição do Perfil</span>
                <a href="{{ route('login') }}" class="font-semibold text-primary hover:underline">
                    Já possui uma conta? Entrar
                </a>
            </div>
        </div>
    </div>

    <!-- Layout Principal -->
    <div class="flex h-[100dvh] min-h-0 flex-col lg:flex-row">
        <!-- Coluna esquerda: imagem (desktop) -->
        <div class="relative hidden min-h-0 lg:block lg:w-[42%] xl:w-[44%]">
            <div class="flex h-full min-h-0 items-stretch p-4 xl:p-6">
                <img src="{{ asset('images/login-page.jpg') }}" alt="UniOrthocrin" class="h-full min-h-0 w-full rounded-2xl object-cover shadow-card" />
            </div>
        </div>

        <!-- Coluna formulário: preenche o espaço com proporção equilibrada e visual premium -->
        <div class="flex min-h-0 flex-1 flex-col justify-center overflow-y-auto lg:w-[58%] xl:w-[56%] bg-background">
            <div class="w-full max-w-2xl xl:max-w-3xl mx-auto px-5 sm:px-8 lg:px-10 xl:px-12 py-6 sm:py-8 flex flex-col justify-center">
                
                @if ($errors->any())
                    <div class="mb-4 shrink-0 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 shadow-sm" role="alert">
                        <div class="flex items-center gap-2 font-bold text-red-900 mb-1">
                            <i class="fa-solid fa-circle-exclamation text-red-600"></i> Por favor, corrija os itens abaixo:
                        </div>
                        <ul class="list-inside list-disc space-y-1 text-xs text-red-800 pl-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="rounded-3xl border border-gray-200/90 bg-white p-6 sm:p-8 lg:p-9 shadow-card">
                    <form action="{{ route('register.profile.store') }}" method="POST" class="space-y-6" id="register-form" novalidate>
                        @csrf

                        <!-- 1. Perfil Selecionado em Destaque -->
                        <section class="space-y-3" aria-labelledby="profile-heading">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-xs font-bold text-primary">1</span>
                                    <h2 id="profile-heading" class="text-xs font-bold uppercase tracking-wider text-gray-500">Tipo de Parceiro</h2>
                                </div>
                            </div>

                            <!-- Card de Destaque do Perfil -->
                            <div id="profile-highlight-box" class="rounded-2xl border-2 border-primary/25 bg-gradient-to-r from-primary/[0.03] via-primary/[0.06] to-primary/[0.03] p-4 sm:p-5 transition-all shadow-sm">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div class="flex items-center gap-3.5">
                                        <div id="profile-icon-badge" class="w-12 h-12 rounded-2xl bg-primary text-white flex items-center justify-center text-xl shadow-md shrink-0">
                                            <i id="profile-icon-display" class="fa-solid fa-shop"></i>
                                        </div>
                                        <div>
                                            <span class="text-[11px] font-bold uppercase tracking-wider text-primary flex items-center gap-1.5">
                                                <i class="fa-solid fa-circle-check text-emerald-600"></i> Perfil Selecionado
                                            </span>
                                            <h3 id="selected-profile-title" class="text-lg sm:text-xl font-bold text-gray-900 leading-tight">Franqueado</h3>
                                            <p id="selected-profile-helper" class="text-xs text-gray-500 mt-0.5">Se não for este perfil, volte e veja novamente.</p>
                                        </div>
                                    </div>
                                    <button type="button" id="btn-change-profile" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-primary/30 hover:border-primary text-primary hover:bg-primary/5 rounded-xl font-semibold text-xs transition shadow-sm self-start sm:self-auto cursor-pointer shrink-0">
                                        <i class="fa-solid fa-arrow-rotate-left"></i> Escolher outro perfil
                                    </button>
                                </div>
                            </div>

                            <!-- Campo oculto com o perfil selecionado -->
                            <input type="hidden" id="profile" name="profile" value="{{ old('profile', $profile) }}" required>
                        </section>

                        <div id="registration-fields" class="{{ old('profile', $profile) ? '' : 'hidden' }} space-y-6">

                            <!-- 2. Conta -->
                            <section class="space-y-3" aria-labelledby="account-heading">
                                <div class="flex items-center gap-2">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-xs font-bold text-primary">2</span>
                                    <h2 id="account-heading" class="text-xs font-bold uppercase tracking-wider text-gray-500">Dados da Conta</h2>
                                </div>
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="name" class="mb-1.5 block text-xs font-semibold text-gray-700">Nome completo *</label>
                                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name" placeholder="Seu nome ou responsável"
                                            class="w-full rounded-xl border border-border bg-gray-50/50 px-3.5 py-2.5 text-sm transition placeholder:text-gray-400 focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20" />
                                    </div>
                                    <div>
                                        <label for="email" class="mb-1.5 block text-xs font-semibold text-gray-700">E-mail de acesso *</label>
                                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" placeholder="seuemail@exemplo.com.br"
                                            class="w-full rounded-xl border border-border bg-gray-50/50 px-3.5 py-2.5 text-sm transition placeholder:text-gray-400 focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20" />
                                    </div>
                                </div>
                            </section>

                            @php
                                $p = old('profile', $profile);
                                $showCompany = in_array($p, ['franquia', 'lojista', 'representante'], true) && $p !== '';
                            @endphp
                            <section id="company-fields" class="{{ $showCompany ? '' : 'hidden' }} space-y-3" aria-labelledby="company-heading">
                                <div class="flex items-center gap-2">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-emerald-600/10 text-xs font-bold text-emerald-800">3</span>
                                    <h2 id="company-heading" class="text-xs font-bold uppercase tracking-wider text-gray-500">Dados da Empresa</h2>
                                </div>
                                <div class="rounded-2xl border border-gray-200 bg-gray-50/40 p-4 sm:p-5">
                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div class="sm:col-span-2">
                                            <label for="razao_social" class="mb-1.5 block text-xs font-semibold text-gray-700">Razão social</label>
                                            <input id="razao_social" name="razao_social" type="text" value="{{ old('razao_social') }}" autocomplete="organization" placeholder="Razão social da empresa"
                                                class="w-full rounded-xl border border-border bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" />
                                        </div>
                                        <div>
                                            <label for="nome_fantasia" class="mb-1.5 block text-xs font-semibold text-gray-700">Nome fantasia</label>
                                            <input id="nome_fantasia" name="nome_fantasia" type="text" value="{{ old('nome_fantasia') }}" placeholder="Nome comercial / loja"
                                                class="w-full rounded-xl border border-border bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" />
                                        </div>
                                        <div>
                                            <label for="cnpj" class="mb-1.5 block text-xs font-semibold text-gray-700">CNPJ</label>
                                            <input id="cnpj" name="cnpj" type="text" value="{{ old('cnpj') }}" inputmode="numeric" autocomplete="off" placeholder="00.000.000/0000-00"
                                                class="w-full rounded-xl border border-border bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" />
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- 4. Senha -->
                            <section class="space-y-3" aria-labelledby="security-heading">
                                <div class="flex items-center gap-2">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-xs font-bold text-primary">4</span>
                                    <h2 id="security-heading" class="text-xs font-bold uppercase tracking-wider text-gray-500">Segurança de Acesso</h2>
                                </div>
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="password" class="mb-1.5 block text-xs font-semibold text-gray-700">Criar senha *</label>
                                        <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password" placeholder="Mínimo 8 caracteres"
                                            class="w-full rounded-xl border border-border bg-gray-50/50 px-3.5 py-2.5 text-sm focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20" />
                                    </div>
                                    <div>
                                        <label for="password_confirmation" class="mb-1.5 block text-xs font-semibold text-gray-700">Confirmar senha *</label>
                                        <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password" placeholder="Repita a senha criada"
                                            class="w-full rounded-xl border border-border bg-gray-50/50 px-3.5 py-2.5 text-sm focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20" />
                                    </div>
                                </div>
                            </section>

                            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                                <a href="{{ route('login') }}" class="text-center text-sm font-semibold text-gray-600 hover:text-primary hover:underline sm:text-left transition">
                                    ← Voltar para o login
                                </a>
                                <button type="submit" class="w-full rounded-xl bg-primary px-8 py-3 text-sm font-bold text-white shadow-md transition hover:bg-[#7a0030] hover:shadow-lg sm:w-auto cursor-pointer">
                                    Cadastrar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <p class="mt-4 text-center text-xs text-text/80">
                    Ao cadastrar-se, sua conta passará por aprovação conforme as diretrizes Orthocrin.
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('register-form');
            const password = document.getElementById('password');
            const confirmation = document.getElementById('password_confirmation');
            const profileInput = document.getElementById('profile');
            const companyFields = document.getElementById('company-fields');
            const registrationFields = document.getElementById('registration-fields');
            const modal = document.getElementById('profile-selection-modal');
            const btnChangeProfile = document.getElementById('btn-change-profile');
            const profileCards = document.querySelectorAll('.btn-profile-card');
            
            const profileTitleDisplay = document.getElementById('selected-profile-title');
            const profileIconDisplay = document.getElementById('profile-icon-display');

            const profileMetadata = {
                'franquia': {
                    title: 'Franqueado',
                    icon: 'fa-shop',
                    desc: 'Exclusivo para franqueados oficiais e proprietários de lojas exclusivas Orthocrin.'
                },
                'lojista': {
                    title: 'Lojista Multimarca',
                    icon: 'fa-store',
                    desc: 'Para lojistas e revendedores parceiros que comercializam produtos e linhas Orthocrin.'
                },
                'representante': {
                    title: 'Representante',
                    icon: 'fa-user-tie',
                    desc: 'Para representantes comerciais e equipes de vendas autorizadas Orthocrin.'
                }
            };

            const profilesWithCompany = ['franquia', 'lojista', 'representante'];

            const selectProfile = (key) => {
                if (!profileMetadata[key]) return;

                profileInput.value = key;
                const meta = profileMetadata[key];

                // Atualizar card de destaque
                profileTitleDisplay.textContent = meta.title;
                profileIconDisplay.className = `fa-solid ${meta.icon}`;

                // Fechar modal
                modal.classList.add('hidden');

                // Liberar campos do formulário
                registrationFields.classList.remove('hidden');
                if (profilesWithCompany.includes(key)) {
                    companyFields.classList.remove('hidden');
                } else {
                    companyFields.classList.add('hidden');
                }
            };

            // Listener dos cards do modal
            profileCards.forEach(card => {
                card.addEventListener('click', () => {
                    const profileKey = card.getAttribute('data-profile');
                    selectProfile(profileKey);
                });
            });

            // Abrir modal ao clicar em "Escolher outro perfil"
            if (btnChangeProfile) {
                btnChangeProfile.addEventListener('click', () => {
                    modal.classList.remove('hidden');
                });
            }

            // Inicializar estado do perfil se já preenchido
            if (profileInput.value && profileMetadata[profileInput.value]) {
                selectProfile(profileInput.value);
            } else {
                modal.classList.remove('hidden');
            }

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

            form.addEventListener('submit', (e) => {
                if (!profileInput.value) {
                    e.preventDefault();
                    modal.classList.remove('hidden');
                }
            });
        });
    </script>
</body>
</html>
