<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UniOrthocrin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/std-icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @livewireStyles
</head>
<body class="bg-background min-h-screen" data-tour-page="@yield('tour_page_key', 'client_dashboard')">
    <!-- Admin View Indicator -->
    @if(auth()->check() && auth()->user()->isAdmin())
        <div class="bg-[#910039] text-white px-6 py-2 text-sm font-medium flex flex-wrap items-center justify-between sticky top-0 z-[60] border-b border-[#7a0030]">
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-white/20 text-white">
                    <i class="fas fa-eye text-xs"></i>
                </span>
                <span>Perfil: <strong class="text-white uppercase font-bold">{{ session('active_profile')->name ?? '' }}</strong></span>
            </div>
            <div class="flex items-center space-x-3 mt-2 sm:mt-0">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-3 py-1 bg-white hover:bg-gray-100 text-[#910039] text-xs font-bold rounded transition-colors">
                    <i class="fas fa-arrow-left mr-1.5 text-[10px]"></i> Painel Admin
                </a>
            </div>
        </div>
    @endif

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
            const ct = (resp.headers.get('Content-Type') || '').toLowerCase();
            if (ct.includes('application/json')) {
                const data = await resp.json();
                if (data && data.success && data.downloadUrl) {
                    title.textContent = 'Download Iniciado';
                    message.textContent = 'Redirecionando para download...';
                    spinner.innerHTML = '<i class="fas fa-check text-xl text-green-300"></i>';
                    setTimeout(() => { alert.classList.add('hidden'); }, 2000);
                    window.location.href = data.downloadUrl;
                } else {
                    title.textContent = 'Erro no Download';
                    message.textContent = data?.message || 'Erro desconhecido';
                    spinner.innerHTML = '<i class="fas fa-exclamation-triangle text-xl text-red-300"></i>';
                    setTimeout(() => { alert.classList.add('hidden'); }, 5000);
                }
            } else {
                const blob = await resp.blob();
                if (!resp.ok) {
                    title.textContent = 'Erro no Download';
                    message.textContent = 'Não foi possível baixar o arquivo';
                    spinner.innerHTML = '<i class="fas fa-exclamation-triangle text-xl text-red-300"></i>';
                    setTimeout(() => { alert.classList.add('hidden'); }, 5000);
                    return false;
                }
                let filename = 'download';
                const cd = resp.headers.get('Content-Disposition');
                if (cd) {
                    const utf8 = /filename\*=(?:UTF-8'')?([^;]+)/i.exec(cd);
                    const plain = /filename="([^"]+)"/i.exec(cd) || /filename=([^;\s]+)/i.exec(cd);
                    if (utf8) {
                        filename = decodeURIComponent(utf8[1].trim().replace(/^["']|["']$/g, ''));
                    } else if (plain) {
                        filename = plain[1].trim().replace(/^["']|["']$/g, '');
                    }
                }
                title.textContent = 'Download Iniciado';
                message.textContent = 'Salvando arquivo...';
                spinner.innerHTML = '<i class="fas fa-check text-xl text-green-300"></i>';
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                a.remove();
                URL.revokeObjectURL(url);
                setTimeout(() => { alert.classList.add('hidden'); }, 2000);
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

    // Notificações: marcar como lida e remover (event delegation para funcionar com Livewire)
    function getCsrf() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }
    var notificationsUnreadUrl = '{{ url()->route("notifications.unread-count") }}';
    function refreshNotifications() {
        if (typeof window.Livewire !== 'undefined') {
            window.Livewire.dispatch('notifications-updated');
        }
        fetch(notificationsUnreadUrl, { method: 'GET', credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var badge = document.querySelector('[data-notification-badge]');
                if (!badge) return;
                var n = (data && data.data && data.data.unread_count !== undefined) ? data.data.unread_count : 0;
                badge.textContent = n > 99 ? '99+' : n;
                if (n > 0) {
                    badge.classList.remove('hidden');
                    badge.style.display = '';
                } else {
                    badge.classList.add('hidden');
                    badge.style.display = 'none';
                }
            })
            .catch(function() {});
    }
    // Fase de CAPTURA (true) para rodar ANTES do Alpine/Livewire e garantir que Marcar como Lida / Excluir funcionem
    document.addEventListener('click', async function (e) {
        const verLink = e.target.closest('.notification-ver-btn');
        const markReadBtn = e.target.closest('.notification-mark-read-btn');
        const deleteBtn = e.target.closest('.notification-delete-btn');
        if (verLink && verLink.href) {
            e.preventDefault();
            e.stopPropagation();
            const id = verLink.getAttribute('data-notification-id');
            const url = verLink.getAttribute('data-mark-read-url');
            const href = verLink.getAttribute('data-href');
            if (id && url && href) {
                try {
                    await fetch(url, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': getCsrf(),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ notification_id: parseInt(id, 10) })
                    });
                } catch (err) {}
                window.location.href = href;
            } else {
                if (href) window.location.href = href;
            }
            return;
        }
        if (markReadBtn && !markReadBtn.disabled) {
            e.preventDefault();
            e.stopPropagation();
            const id = markReadBtn.getAttribute('data-notification-id');
            const url = markReadBtn.getAttribute('data-mark-read-url');
            if (!id || !url) return;
            markReadBtn.disabled = true;
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrf(),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ notification_id: parseInt(id, 10) })
                });
                const data = await res.json();
                if (data && data.success) refreshNotifications();
            } catch (err) {}
            markReadBtn.disabled = false;
            return;
        }
        if (deleteBtn && !deleteBtn.disabled) {
            e.preventDefault();
            e.stopPropagation();
            if (!confirm('Remover esta notificação?')) return;
            const id = deleteBtn.getAttribute('data-notification-id');
            const url = deleteBtn.getAttribute('data-delete-url');
            if (!id || !url) return;
            deleteBtn.disabled = true;
            try {
                const deleteUrl = url + (url.indexOf('?') >= 0 ? '&' : '?') + 'notification_id=' + encodeURIComponent(id);
                const res = await fetch(deleteUrl, {
                    method: 'DELETE',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrf(),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await res.json();
                if (data && data.success) refreshNotifications();
            } catch (err) {}
            deleteBtn.disabled = false;
            return;
        }
    }, true);
    </script>
</body>
</html> 