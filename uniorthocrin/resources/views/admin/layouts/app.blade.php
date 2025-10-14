<!DOCTYPE html>
<html lang="pt-BR" x-data="{ 
    sidebarOpen: true, 
    mobileMenuOpen: false,
    init() {
        // Verificar tamanho da tela no carregamento
        this.checkScreenSize();
        
        // Listener para redimensionamento
        window.addEventListener('resize', () => {
            this.checkScreenSize();
        });
    },
    checkScreenSize() {
        if (window.innerWidth >= 1024) {
            // Desktop: sempre mostrar sidebar expandido, fechar mobile menu
            this.sidebarOpen = true;
            this.mobileMenuOpen = false;
        } else {
            // Mobile: fechar sidebar por padrão
            this.sidebarOpen = false;
        }
    }
}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - UniOrthocrin')</title>
    @vite(['resources/css/app.css', 'resources/css/admin-modern.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-outfit">
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile Overlay -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileMenuOpen = false"
             class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden"></div>

        <!-- Sidebar -->
        <aside class="bg-primary-500 border-r border-primary-600 transition-all duration-300 ease-in-out
                      fixed lg:relative inset-y-0 left-0 z-50
                      lg:w-auto"
               :class="{
                   'w-[290px]': (sidebarOpen && !mobileMenuOpen) || mobileMenuOpen,
                   'w-full': mobileMenuOpen
               }"
               x-show="sidebarOpen || mobileMenuOpen"
               x-transition:enter="transition ease-in-out duration-300 transform"
               x-transition:enter-start="-translate-x-full lg:translate-x-0"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in-out duration-300 transform"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full lg:translate-x-0">
            
            <!-- Modern Logo -->
            <div class="flex items-center justify-between h-16 px-4">
                <template x-if="sidebarOpen || mobileMenuOpen">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('images/logo.png') }}" alt="UniOrthocrin" class="h-8 w-auto">
                    </div>
                </template>
                <template x-if="!sidebarOpen && !mobileMenuOpen">
                    <div class="flex items-center justify-center">
                        <img src="{{ asset('images/logo.png') }}" alt="UniOrthocrin" class="h-8 w-auto">
                    </div>
                </template>
                
                <!-- Close button for mobile -->
                <button x-show="mobileMenuOpen" 
                        @click="mobileMenuOpen = false"
                        class="lg:hidden p-2 rounded-lg text-white hover:bg-primary-600 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modern Navigation -->
            <nav class="mt-8 px-4">
                <div class="space-y-2">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       @click="if (window.innerWidth < 1024) mobileMenuOpen = false"
                       class="flex items-center px-3 py-2 text-white rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-primary-600' : 'hover:bg-primary-600' }}"
                       :class="(sidebarOpen || mobileMenuOpen) ? 'justify-start' : 'justify-center'">
                        <i class="fas fa-tachometer-alt text-base"></i>
                        <span x-show="sidebarOpen || mobileMenuOpen" class="ml-3 transition-opacity duration-200">Dashboard</span>
                    </a>

                    <!-- Campaigns (Separado) -->
                    <a href="{{ route('admin.campaigns.index') }}" 
                       @click="if (window.innerWidth < 1024) mobileMenuOpen = false"
                       class="flex items-center px-3 py-2 text-white rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.campaigns.*') ? 'bg-primary-600' : 'hover:bg-primary-600' }}"
                       :class="(sidebarOpen || mobileMenuOpen) ? 'justify-start' : 'justify-center'">
                        <i class="fas fa-bullhorn text-base"></i>
                        <span x-show="sidebarOpen || mobileMenuOpen" class="ml-3 transition-opacity duration-200">Campanhas</span>
                    </a>

                    <!-- Conteúdo Section -->
                    <div class="mt-6">
                        <div x-show="sidebarOpen || mobileMenuOpen" class="px-3 py-2 text-xs font-semibold uppercase tracking-wider" style="color: #510020;">
                            Conteúdo
                        </div>
                        
                        <!-- Products -->
                        <div class="space-y-1" x-data="{ productsOpen: false }">
                            <button @click="productsOpen = !productsOpen" 
                                    class="flex items-center justify-between w-full px-3 py-2 text-white rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.products.*') ? 'bg-primary-600' : 'hover:bg-primary-600' }}"
                                    :class="(sidebarOpen || mobileMenuOpen) ? 'justify-start' : 'justify-center'">
                                <div class="flex items-center">
                                    <i class="fas fa-box text-base"></i>
                                    <span x-show="sidebarOpen || mobileMenuOpen" class="ml-3 transition-opacity duration-200">Produtos</span>
                                </div>
                                <i x-show="sidebarOpen || mobileMenuOpen" class="fas fa-chevron-down text-xs transition-transform duration-200 ml-2" :class="productsOpen ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="(sidebarOpen || mobileMenuOpen) && productsOpen" class="ml-6 space-y-1 rounded-lg p-2" style="background-color: #510020;">
                                <a href="{{ route('admin.products.index') }}" 
                                   @click="if (window.innerWidth < 1024) mobileMenuOpen = false"
                                   class="block px-3 py-2 text-sm text-white hover:bg-white hover:bg-opacity-20 rounded transition-colors duration-200">
                                    Ver todos
                                </a>
                                <a href="{{ route('admin.product-categories.index') }}" 
                                   @click="if (window.innerWidth < 1024) mobileMenuOpen = false"
                                   class="block px-3 py-2 text-sm text-white hover:bg-white hover:bg-opacity-20 rounded transition-colors duration-200">
                                    Categorias
                                </a>
                                <a href="{{ route('admin.product-series.index') }}" 
                                   @click="if (window.innerWidth < 1024) mobileMenuOpen = false"
                                   class="block px-3 py-2 text-sm text-white hover:bg-white hover:bg-opacity-20 rounded transition-colors duration-200">
                                    Séries
                                </a>
                            </div>
                        </div>

                        <!-- Radar -->
                        <div class="space-y-1" x-data="{ newsOpen: false }">
                            <button @click="newsOpen = !newsOpen" 
                                    class="flex items-center justify-between w-full px-3 py-2 text-white rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.news.*') ? 'bg-primary-600' : 'hover:bg-primary-600' }}"
                                    :class="(sidebarOpen || mobileMenuOpen) ? 'justify-start' : 'justify-center'">
                                <div class="flex items-center">
                                    <i class="fas fa-newspaper text-base"></i>
                                    <span x-show="sidebarOpen || mobileMenuOpen" class="ml-3 transition-opacity duration-200">Radar</span>
                                </div>
                                <i x-show="sidebarOpen || mobileMenuOpen" class="fas fa-chevron-down text-xs transition-transform duration-200 ml-2" :class="newsOpen ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="(sidebarOpen || mobileMenuOpen) && newsOpen" class="ml-6 space-y-1 rounded-lg p-2" style="background-color: #510020;">
                                <a href="{{ route('admin.news.index') }}" 
                                   @click="if (window.innerWidth < 1024) mobileMenuOpen = false"
                                   class="block px-3 py-2 text-sm text-white hover:bg-white hover:bg-opacity-20 rounded transition-colors duration-200">
                                    Ver todas
                                </a>
                                <a href="{{ route('admin.news-categories.index') }}" 
                                   @click="if (window.innerWidth < 1024) mobileMenuOpen = false"
                                   class="block px-3 py-2 text-sm text-white hover:bg-white hover:bg-opacity-20 rounded transition-colors duration-200">
                                    Categorias
                                </a>
                            </div>
                        </div>

                        <!-- Biblioteca -->
                        <div class="space-y-1" x-data="{ libraryOpen: false }">
                            <button @click="libraryOpen = !libraryOpen" 
                                    class="flex items-center justify-between w-full px-3 py-2 text-white rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.library.*') ? 'bg-primary-600' : 'hover:bg-primary-600' }}"
                                    :class="(sidebarOpen || mobileMenuOpen) ? 'justify-start' : 'justify-center'">
                                <div class="flex items-center">
                                    <i class="fas fa-book text-base"></i>
                                    <span x-show="sidebarOpen || mobileMenuOpen" class="ml-3 transition-opacity duration-200">Biblioteca</span>
                                </div>
                                <i x-show="sidebarOpen || mobileMenuOpen" class="fas fa-chevron-down text-xs transition-transform duration-200 ml-2" :class="libraryOpen ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="(sidebarOpen || mobileMenuOpen) && libraryOpen" class="ml-6 space-y-1 rounded-lg p-2" style="background-color: #510020;">
                                <a href="{{ route('admin.library.index') }}" 
                                   @click="if (window.innerWidth < 1024) mobileMenuOpen = false"
                                   class="block px-3 py-2 text-sm text-white hover:bg-white hover:bg-opacity-20 rounded transition-colors duration-200">
                                    Ver todas
                                </a>
                                <a href="{{ route('admin.library-categories.index') }}" 
                                   @click="if (window.innerWidth < 1024) mobileMenuOpen = false"
                                   class="block px-3 py-2 text-sm text-white hover:bg-white hover:bg-opacity-20 rounded transition-colors duration-200">
                                    Categorias
                                </a>
                            </div>
                        </div>

                        <!-- Training -->
                        <div class="space-y-1" x-data="{ trainingOpen: false }">
                            <button @click="trainingOpen = !trainingOpen" 
                                    class="flex items-center justify-between w-full px-3 py-2 text-white rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.training.*') ? 'bg-primary-600' : 'hover:bg-primary-600' }}"
                                    :class="(sidebarOpen || mobileMenuOpen) ? 'justify-start' : 'justify-center'">
                                <div class="flex items-center">
                                    <i class="fas fa-graduation-cap text-base"></i>
                                    <span x-show="sidebarOpen || mobileMenuOpen" class="ml-3 transition-opacity duration-200">Treinamentos</span>
                                </div>
                                <i x-show="sidebarOpen || mobileMenuOpen" class="fas fa-chevron-down text-xs transition-transform duration-200 ml-2" :class="trainingOpen ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="(sidebarOpen || mobileMenuOpen) && trainingOpen" class="ml-6 space-y-1 rounded-lg p-2" style="background-color: #510020;">
                                <a href="{{ route('admin.training.index') }}" 
                                   @click="if (window.innerWidth < 1024) mobileMenuOpen = false"
                                   class="block px-3 py-2 text-sm text-white hover:bg-white hover:bg-opacity-20 rounded transition-colors duration-200">
                                    Ver todos
                                </a>
                                <a href="{{ route('admin.training-categories.index') }}" 
                                   @click="if (window.innerWidth < 1024) mobileMenuOpen = false"
                                   class="block px-3 py-2 text-sm text-white hover:bg-white hover:bg-opacity-20 rounded transition-colors duration-200">
                                    Categorias
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Usuários Section -->
                    <div class="mt-6">
                        <div x-show="sidebarOpen || mobileMenuOpen" class="px-3 py-2 text-xs font-semibold uppercase tracking-wider" style="color: #510020;">
                            Usuários
                        </div>
                        
                        <!-- Users -->
                        <a href="{{ route('admin.users.index') }}" 
                           @click="if (window.innerWidth < 1024) mobileMenuOpen = false"
                           class="flex items-center px-3 py-2 text-white rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-primary-600' : 'hover:bg-primary-600' }}"
                           :class="(sidebarOpen || mobileMenuOpen) ? 'justify-start' : 'justify-center'">
                            <i class="fas fa-users text-base"></i>
                            <span x-show="sidebarOpen || mobileMenuOpen" class="ml-3 transition-opacity duration-200">Usuários</span>
                        </a>

                        <!-- Profiles -->
                        <a href="{{ route('admin.users.index') }}" 
                           @click="if (window.innerWidth < 1024) mobileMenuOpen = false"
                           class="flex items-center px-3 py-2 text-white rounded-lg transition-colors duration-200 hover:bg-primary-600"
                           :class="(sidebarOpen || mobileMenuOpen) ? 'justify-start' : 'justify-center'">
                            <i class="fas fa-user-cog text-base"></i>
                            <span x-show="sidebarOpen || mobileMenuOpen" class="ml-3 transition-opacity duration-200">Perfis</span>
                        </a>
                    </div>

                    <!-- Admin Section -->
                    <div class="mt-6">
                        <div x-show="sidebarOpen || mobileMenuOpen" class="px-3 py-2 text-xs font-semibold uppercase tracking-wider" style="color: #510020;">
                            Admin
                        </div>
                        
                        <!-- Reports -->
                        <a href="{{ route('admin.reports.index') }}" 
                           @click="if (window.innerWidth < 1024) mobileMenuOpen = false"
                           class="flex items-center px-3 py-2 text-white rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-primary-600' : 'hover:bg-primary-600' }}"
                           :class="(sidebarOpen || mobileMenuOpen) ? 'justify-start' : 'justify-center'">
                            <i class="fas fa-chart-bar text-base"></i>
                            <span x-show="sidebarOpen || mobileMenuOpen" class="ml-3 transition-opacity duration-200">Relatórios</span>
                        </a>

                        <!-- Notifications -->
                        <a href="{{ route('admin.notifications.index') }}" 
                           @click="if (window.innerWidth < 1024) mobileMenuOpen = false"
                           class="flex items-center px-3 py-2 text-white rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.notifications.*') ? 'bg-primary-600' : 'hover:bg-primary-600' }}"
                           :class="(sidebarOpen || mobileMenuOpen) ? 'justify-start' : 'justify-center'">
                            <i class="fas fa-bell text-base"></i>
                            <span x-show="sidebarOpen || mobileMenuOpen" class="ml-3 transition-opacity duration-200">Notificações</span>
                        </a>
                    </div>
                </div>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-0">
        
        <!-- Modern Header -->
        <header class="header-modern">
            <div class="flex items-center justify-between px-6 py-4">
                <!-- Left side: Menu toggle + Search -->
                <div class="flex items-center space-x-4">
                    <!-- Menu toggle button (only for mobile) -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" 
                            class="lg:hidden p-2 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition-all duration-200">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <!-- Modern Search -->
                    <div class="hidden md:block w-80">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400 text-sm"></i>
                            </div>
                            <input type="text" placeholder="Buscar..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200">
                        </div>
                    </div>
                </div>

                <!-- Modern User Menu -->
                <div class="flex items-center space-x-4" x-data="{ open: false, notificationsOpen: false, notifications: [], unreadCount: 0 }" 
                     x-init="
                        loadNotifications();
                        setInterval(loadNotifications, 30000); // Atualizar a cada 30 segundos
                     ">
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open; loadNotifications()" 
                                    class="p-2 text-gray-400 hover:text-gray-500 relative hover-modern transition-colors duration-200">
                                <i class="fas fa-bell text-xl"></i>
                                <span x-show="unreadCount > 0" 
                                      x-text="unreadCount > 99 ? '99+' : unreadCount"
                                      class="absolute -top-1 -right-1 block h-5 w-5 rounded-full bg-error-500 text-white text-xs flex items-center justify-center ring-2 ring-white"></span>
                            </button>

                            <!-- Notifications Dropdown -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                
                                <!-- Header -->
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-semibold text-gray-900">Notificações</h3>
                                        <a href="{{ route('admin.notifications.index') }}" 
                                           class="text-xs text-primary-500 hover:text-primary-600">Ver todas</a>
                                    </div>
                                </div>

                                <!-- Notifications List -->
                                <div class="max-h-96 overflow-y-auto">
                                    <template x-if="notifications.length === 0">
                                        <div class="px-4 py-8 text-center text-gray-500">
                                            <i class="fas fa-bell-slash text-2xl mb-2"></i>
                                            <p class="text-sm">Nenhuma notificação</p>
                                        </div>
                                    </template>
                                    
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <div class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="h-8 w-8 rounded-full flex items-center justify-center"
                                                         :class="{
                                                             'bg-primary-100 text-primary-600': notification.type === 'info',
                                                             'bg-success-100 text-success-600': notification.type === 'success',
                                                             'bg-warning-100 text-warning-600': notification.type === 'warning',
                                                             'bg-error-100 text-error-600': notification.type === 'error'
                                                         }">
                                                        <i class="fas fa-bell text-xs"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate" x-text="notification.title"></p>
                                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2" x-text="notification.message"></p>
                                                    <p class="text-xs text-gray-400 mt-1" x-text="formatDate(notification.created_at)"></p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div x-show="!notification.read_at" class="h-2 w-2 rounded-full bg-primary-500"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Footer -->
                                <div class="px-4 py-3 border-t border-gray-200">
                                    <a href="{{ route('admin.notifications.create') }}" 
                                       class="block w-full text-center text-sm text-primary-500 hover:text-primary-600 font-medium">
                                        Criar Nova Notificação
                                    </a>
                                </div>
                            </div>
                        </div>

                    <!-- User Dropdown -->
                    <div class="relative">
                        <button @click="open = !open" class="flex items-center space-x-3 text-sm rounded-lg focus-modern hover-modern p-2">
                            <div class="h-8 w-8 rounded-lg bg-primary-500 flex items-center justify-center">
                                <span class="text-white text-sm font-medium">A</span>
                            </div>
                            <span class="hidden md:block text-gray-700 font-medium">Admin</span>
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </button>

                        <div x-show="open" @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-theme-lg py-1 z-50 border border-gray-200">
                            <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                <i class="fas fa-user mr-2"></i>Perfil
                            </a>
                            <hr class="my-1 border-gray-200">
                            <div class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Sair
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

            <!-- Modern Page Content -->
            <main class="p-6 w-full" style="overflow-y: auto;">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Função para carregar notificações
        function loadNotifications() {
            fetch('{{ route("admin.notifications.recent") }}')
                .then(response => response.json())
                .then(data => {
                    // Atualizar o contexto Alpine.js
                    const notificationComponent = document.querySelector('[x-data*="notifications"]');
                    if (notificationComponent) {
                        const alpineData = Alpine.$data(notificationComponent);
                        alpineData.notifications = data.notifications;
                        alpineData.unreadCount = data.unread_count;
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar notificações:', error);
                });
        }

        // Função para formatar data
        function formatDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffInMinutes = Math.floor((now - date) / (1000 * 60));
            
            if (diffInMinutes < 1) {
                return 'Agora mesmo';
            } else if (diffInMinutes < 60) {
                return `${diffInMinutes} min atrás`;
            } else if (diffInMinutes < 1440) {
                const hours = Math.floor(diffInMinutes / 60);
                return `${hours}h atrás`;
            } else {
                const days = Math.floor(diffInMinutes / 1440);
                return `${days}d atrás`;
            }
        }

        // Adicionar função formatDate ao contexto global
        window.formatDate = formatDate;
    </script>

</body>
</html>
