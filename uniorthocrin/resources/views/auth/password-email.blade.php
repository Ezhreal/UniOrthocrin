<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci minha senha - UniOrthocrin</title>
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
        }
    </script>
</head>
<body class="font-sans min-h-screen bg-background flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-white border border-border rounded-xl p-6">
        <div class="text-center mb-6">
            <img src="{{ asset('images/std-ver.png') }}" alt="UniOrthocrin" class="mx-auto mb-3 w-32 h-20 object-contain">
            <h1 class="text-2xl font-bold text-primary">Esqueci minha senha</h1>
            <p class="text-text text-sm mt-2">
                Informe o e-mail cadastrado para receber uma nova senha de acesso.
            </p>
        </div>

        @if (session('status'))
            <div class="mb-4 text-emerald-600 text-sm">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 text-red-500 text-sm">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-semibold text-text mb-1">E-mail cadastrado *</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                       class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
            </div>

            <button type="submit"
                    class="w-full py-2.5 bg-primary text-white font-semibold rounded-full hover:bg-secondary transition">
                Enviar nova senha
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-primary text-xs font-semibold hover:underline">
                Voltar para login
            </a>
        </div>
    </div>
</body>
</html>
