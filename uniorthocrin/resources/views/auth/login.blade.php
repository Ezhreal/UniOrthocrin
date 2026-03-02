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
    <div class="flex flex-col lg:flex-row w-screen h-screen">
        <!-- Coluna Esquerda: Splash -->
        <div class="hidden lg:flex lg:w-1/2 h-full items-center justify-center p-6">
            <img src="{{ asset('images/login-page.jpg') }}" alt="UniOrthocrin" class="object-cover w-full h-full rounded-lg" />
        </div>
        <!-- Coluna Direita: Formulário -->
        <div class="w-full lg:w-1/2 h-full flex items-center justify-center p-4">
            <div class="w-full max-w-md px-4 sm:px-8">
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

    <!-- Modal aviso de novo endereço da plataforma -->
    <div id="address-change-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 p-6">
            <div class="flex items-start gap-3 mb-4">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-white">
                    <i class="fas fa-info text-sm"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-primary mb-1">A plataforma mudou de endereço</h2>
                    <p class="text-sm text-text">
                        Para acessar a versão atual da plataforma UniOrthocrin, use o link abaixo. Recomendamos atualizar seus favoritos.
                    </p>
                </div>
            </div>
            <a href="https://uniorthocrin.com/login/" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center justify-center w-full px-4 py-2.5 mb-3 rounded-full bg-primary text-white text-sm font-semibold hover:bg-secondary transition-colors duration-200">
                Ir para a plataforma atual
            </a>
            <button type="button" id="close-address-change-modal"
                    class="w-full px-4 py-2.5 rounded-full border border-border text-xs font-semibold text-text hover:bg-background transition-colors duration-150">
                Continuar nesta tela mesmo assim
            </button>
        </div>
    </div>

    <script>
        // Fecha o modal quando o usuário clicar no botão de fechar
        document.addEventListener('DOMContentLoaded', function () {
            var closeBtn = document.getElementById('close-address-change-modal');
            var modal = document.getElementById('address-change-modal');
            if (closeBtn && modal) {
                closeBtn.addEventListener('click', function () {
                    modal.classList.add('hidden');
                });
            }
        });
    </script>
</body>

</html>