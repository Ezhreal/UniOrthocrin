<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UniOrthocrin</title>
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
        }
    </script>
</head>

<body class="font-sans min-h-screen min-w-screen">
    <div class="flex w-screen h-screen">
        <!-- Coluna Esquerda: Splash -->
        <div class="w-1/2 h-full flex items-center justify-center p-6">
            <img src="https://placehold.co/800x1000" alt="Splash" class="object-cover w-full h-full rounded-lg" />
        </div>
        <!-- Coluna Direita: Formulário -->
        <div class="w-1/2 h-full flex items-center justify-center">
            <div class="w-full max-w-md px-8">
                <!-- Logo -->
                <div class="mb-8 flex flex-col items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="UniOrthocrin Logo" class="mb-2 w-32 h-16 object-contain" />
                </div>
                <!-- Título -->
                <h1 class="text-2xl font-bold text-primary mb-2 text-center">Bem vindo de volta</h1>
                <!-- Subtítulo -->
                <p class="text-text text-sm text-center mb-8 max-w-xs mx-auto">É ótimo ter você novamente na UniOrthocrin, sua plataforma completa de mídias para impulsionar o marketing da nossa marca.</p>
                <!-- Formulário -->
                <form action="{{ route('login.post') }}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-text text-xs font-semibold mb-1">E-mail</label>
                        <input id="email" name="email" type="email" required autofocus
                            class="w-full px-3 py-2 border border-border rounded-md text-text placeholder-text focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary bg-white" />
                    </div>
                    <div>
                        <label for="password" class="block text-text text-xs font-semibold mb-1">Senha</label>
                        <input id="password" name="password" type="password" required
                            class="w-full px-3 py-2 border border-border rounded-md text-text placeholder-text focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary bg-white" />
                    </div>
                    @if ($errors->any())
                    <div class="text-red-500 text-xs mb-2">
                        @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                        @endforeach
                    </div>
                    @endif
                    <div class="flex justify-end mb-2">
                        <a href="#" class="text-xs text-primary font-semibold hover:underline">Esqueceu a senha?</a>
                    </div>
                    <button type="submit"
                        class="w-full py-3 bg-primary text-white font-bold rounded-full hover:bg-secondary transition-colors duration-200 uppercase tracking-wider text-sm">
                        Acessar
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>