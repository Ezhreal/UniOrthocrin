<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Escolha de Perfil</title>
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
        <!-- Coluna Direita: Conteúdo -->
        <div class="w-full lg:w-1/2 h-full flex items-center justify-center p-4">
            <div class="w-full max-w-md px-4 sm:px-8">
                <div class="mb-8 flex flex-col items-center">
                    <img src="{{ asset('images/std-ver.png') }}" alt="UniOrthocrin Logo" class="mb-2 w-32 h-16 object-contain" />
                </div>
                <h1 class="text-2xl font-bold text-primary mb-2 text-center">Escolha seu perfil de cadastro</h1>
                <p class="text-text text-sm text-center mb-8 max-w-xs mx-auto">
                    Selecione o tipo de usuário para continuar o cadastro.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <a href="{{ route('register.profile', 'franquia') }}" class="rounded-xl border border-border bg-white p-6 text-center hover:border-primary hover:shadow-md transition">
                        <div class="text-4xl mb-3">🏢</div>
                        <h2 class="text-primary font-bold text-lg">Franquia</h2>
                    </a>
                    <a href="{{ route('register.profile', 'representante') }}" class="rounded-xl border border-border bg-white p-6 text-center hover:border-primary hover:shadow-md transition">
                        <div class="text-4xl mb-3">👤</div>
                        <h2 class="text-primary font-bold text-lg">Representante</h2>
                    </a>
                    <a href="{{ route('register.profile', 'lojista') }}" class="rounded-xl border border-border bg-white p-6 text-center hover:border-primary hover:shadow-md transition">
                        <div class="text-4xl mb-3">🛒</div>
                        <h2 class="text-primary font-bold text-lg">Lojista</h2>
                    </a>
                </div>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-primary text-sm font-semibold hover:underline">Voltar para login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
