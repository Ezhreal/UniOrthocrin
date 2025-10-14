<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UniOrthocrin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @livewireStyles
</head>
<body class="bg-background min-h-screen">
    @include('components.header')
    
    <!-- Alerta de Download -->
    <div id="download-alert" class="fixed top-4 right-4 z-50 hidden">
        <div class="bg-[#910039] text-white px-6 py-4 rounded-lg shadow-lg max-w-sm">
            <div class="flex items-center gap-3">
                <div id="download-spinner" class="animate-spin">
                    <i class="fas fa-spinner text-xl"></i>
                </div>
                <div>
                    <div id="download-title" class="font-semibold">Processando Download</div>
                    <div id="download-message" class="text-sm opacity-90">Preparando arquivos...</div>
                </div>
            </div>
        </div>
    </div>
    
    @yield('content')
    @include('components.footer')
    
    @livewireScripts
    @stack('scripts')
    <script>
    async function handleDownloadSubmit(e, form) {
        e.preventDefault();
        
        // Mostrar feedback visual
        const alert = document.getElementById('download-alert');
        const title = document.getElementById('download-title');
        const message = document.getElementById('download-message');
        const spinner = document.getElementById('download-spinner');
        
        alert.classList.remove('hidden');
        title.textContent = 'Processando Download';
        message.textContent = 'Preparando arquivos...';
        spinner.innerHTML = '<i class="fas fa-spinner text-xl animate-spin"></i>';
        
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrf = tokenMeta ? tokenMeta.getAttribute('content') : (form.querySelector('input[name=_token]')?.value || '');
        const formData = new FormData(form);
        
        try {
            const resp = await fetch(form.action, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf }, body: formData });
            const data = await resp.json();
            
            if (data && data.success && data.downloadUrl) {
                // Atualizar feedback para sucesso
                title.textContent = 'Download Iniciado';
                message.textContent = 'Redirecionando para download...';
                spinner.innerHTML = '<i class="fas fa-check text-xl text-green-300"></i>';
                
                // Esconder alerta após 2 segundos
                setTimeout(() => {
                    alert.classList.add('hidden');
                }, 2000);
                
                window.location.href = data.downloadUrl;
            } else {
                // Mostrar erro
                title.textContent = 'Erro no Download';
                message.textContent = data?.message || 'Erro desconhecido';
                spinner.innerHTML = '<i class="fas fa-exclamation-triangle text-xl text-red-300"></i>';
                
                // Esconder alerta após 5 segundos
                setTimeout(() => {
                    alert.classList.add('hidden');
                }, 5000);
            }
        } catch (err) {
            // Mostrar erro
            title.textContent = 'Erro na Requisição';
            message.textContent = 'Erro de rede ao iniciar download';
            spinner.innerHTML = '<i class="fas fa-exclamation-triangle text-xl text-red-300"></i>';
            
            // Esconder alerta após 5 segundos
            setTimeout(() => {
                alert.classList.add('hidden');
            }, 5000);
        }
        return false;
    }
    </script>
</body>
</html> 