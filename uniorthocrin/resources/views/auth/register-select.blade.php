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
<body class="font-sans min-h-screen bg-background">
    <div class="max-w-5xl mx-auto px-4 py-10">
        <div class="max-w-md mx-auto text-center mb-8">
            <img src="{{ asset('images/std-ver.png') }}" alt="UniOrthocrin" class="mx-auto mb-4 w-40 h-24 object-contain" />
            <h1 class="text-2xl font-bold text-primary mb-2">Escolha seu perfil de cadastro</h1>
            <p class="text-text text-sm">Selecione o tipo de usuário para continuar o cadastro.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

        <div class="text-center mt-8">
            <a href="{{ route('login') }}" class="text-primary text-sm font-semibold hover:underline">Voltar para login</a>
        </div>
    </div>
</body>
</html>
