<header class="bg-white w-full shadow-sm" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto flex items-center justify-between h-20 px-6">
        <!-- Logo -->
        <div class="flex items-center flex-shrink-0">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/std-hor.png') }}" alt="UniOrthocrin" class="h-12 w-auto">
            </a>
        </div>

        <!-- Menu Desktop -->
        <nav id="tour-navigation" class="hidden md:flex flex-1 justify-center">
            <ul class="flex space-x-8">
                @php 
                    $user = auth()->user(); 
                    $activeProfileId = session('active_profile_id') ?? ($user->user_type_id ?? 0);
                @endphp
                @if($user && in_array($activeProfileId, [1,2]))
                    <li><a href="{{ route('campanhas.list', ['profile_slug' => session('active_profile_slug')]) }}" class="text-[#910039] font-medium hover:underline">Marketing</a></li>
                @endif
                <li><a href="{{ route('produtos.list', ['profile_slug' => session('active_profile_slug')]) }}" class="text-[#910039] font-medium hover:underline">Produtos</a></li>
                <li><a href="{{ route('biblioteca.list', ['profile_slug' => session('active_profile_slug')]) }}" class="text-[#910039] font-medium hover:underline">Biblioteca</a></li>
                <li><a href="{{ route('media.list', ['profile_slug' => session('active_profile_slug')]) }}" class="text-[#910039] font-medium hover:underline">Na Mídia</a></li>
                <li><a href="{{ route('treinamentos.list', ['profile_slug' => session('active_profile_slug')]) }}" class="text-[#910039] font-medium hover:underline">Treinamentos</a></li>
                <li><a href="{{ route('radar.list', ['profile_slug' => session('active_profile_slug')]) }}" class="text-[#910039] font-medium hover:underline">Radar</a></li>
            </ul>
        </nav>

        <!-- Busca e usuário Desktop -->
        <div class="hidden md:flex items-center gap-4">
            <form action="{{ route('search.results', ['profile_slug' => session('active_profile_slug')]) }}" method="GET" class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar" class="pl-4 pr-10 py-2 rounded-full bg-[#F3F3F3] text-sm text-gray-700 focus:outline-none w-56 placeholder:text-gray-400">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-[#910039]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </button>
            </form>

            <!-- Dropdown de Notificações -->
            @auth
                <div id="tour-notifications" class="relative">
                    @livewire('notification-dropdown')
                </div>
            @endauth

            <!-- Botão "Como Usar" -->
            <button id="btn-trigger-help-tour" class="flex items-center gap-1.5 px-3 py-1.5 border border-gray-200 text-gray-600 hover:text-[#910039] hover:border-[#910039] transition-all duration-200 rounded-full text-xs font-semibold bg-white shadow-sm" title="Tutorial rápido desta página">
                <i class="fas fa-question-circle text-sm text-[#910039]"></i>
                <span>Como Usar?</span>
            </button>

            <!-- Menu do usuário -->
            <div id="tour-profile-menu" class="relative group" x-data="{ open: false }">
                <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-[#910039] transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#910039] focus:ring-opacity-50 rounded-lg">
                    <i class="fas fa-user-circle text-xl"></i>
                </button>

                <!-- Dropdown menu -->
                <div x-show="open" @click.away="open = false" style="display: none;"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50 transition-all duration-200">
                    <a href="{{ route('my.account', ['profile_slug' => session('active_profile_slug')]) }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-user-cog mr-3 text-[#910039]"></i>
                        <span class="font-medium">Minha Conta</span>
                    </a>
                    
                    @if($user && !$user->isAdmin() && $user->profiles->count() > 1)
                        <div class="border-t border-gray-100 my-1"></div>
                        <div class="px-4 py-2 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">
                            Alternar Perfil
                        </div>
                        @foreach($user->profiles as $p)
                            @if($p->id == session('active_profile_id'))
                                <div class="px-4 py-2 text-sm text-[#910039] flex items-center font-semibold bg-gray-50">
                                    <i class="fas fa-check mr-3"></i>
                                    <span>{{ $p->name }}</span>
                                </div>
                            @else
                                <form method="POST" action="{{ route('profile.switch', $p->id) }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                        <i class="fas fa-chevron-right mr-3 text-[#910039]/60"></i>
                                        <span>{{ $p->name }}</span>
                                    </button>
                                </form>
                            @endif
                        @endforeach
                    @endif

                    <div class="border-t border-gray-100 my-1"></div>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-sign-out-alt mr-3 text-gray-500"></i>
                            <span>Sair</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Button -->
        <div class="md:hidden flex items-center gap-3">
            <!-- Busca Mobile -->
            <form action="{{ route('search.results', ['profile_slug' => session('active_profile_slug')]) }}" method="GET" class="relative">
                <input type="text" name="q" placeholder="Buscar" class="pl-3 pr-9 py-1.5 rounded-full bg-[#F3F3F3] text-sm text-gray-700 focus:outline-none w-36 placeholder:text-gray-400">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-[#910039]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>

            <!-- Menu Hambúrguer -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-gray-600 hover:text-[#910039] transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#910039] focus:ring-opacity-50 rounded-lg">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileMenuOpen = false"
         class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 md:hidden"></div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 z-50 w-80 bg-white shadow-xl md:hidden">

        <!-- Mobile Menu Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <img src="{{ asset('images/std-hor.png') }}" alt="UniOrthocrin" class="h-8 w-auto">
            <button @click="mobileMenuOpen = false" class="p-2 text-gray-600 hover:text-[#910039] transition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu Content -->
        <div class="flex flex-col mb-5">
            <!-- Navigation Links -->
            <nav class="flex-1 px-6 py-6">
                <ul class="space-y-4">
                    @if($user && in_array($activeProfileId, [1,2]))
                        <li>
                            <a href="{{ route('campanhas.list', ['profile_slug' => session('active_profile_slug')]) }}" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3 text-gray-700 hover:bg-[#910039] hover:text-white rounded-lg transition-colors duration-200">
                                <i class="fas fa-bullhorn mr-3 text-[#910039]"></i>
                                <span class="font-medium">Marketing</span>
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('produtos.list', ['profile_slug' => session('active_profile_slug')]) }}" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3 text-gray-700 hover:bg-[#910039] hover:text-white rounded-lg transition-colors duration-200">
                            <i class="fas fa-box mr-3 text-[#910039]"></i>
                            <span class="font-medium">Produtos</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('biblioteca.list', ['profile_slug' => session('active_profile_slug')]) }}" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3 text-gray-700 hover:bg-[#910039] hover:text-white rounded-lg transition-colors duration-200">
                            <i class="fas fa-book mr-3 text-[#910039]"></i>
                            <span class="font-medium">Biblioteca</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('media.list', ['profile_slug' => session('active_profile_slug')]) }}" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3 text-gray-700 hover:bg-[#910039] hover:text-white rounded-lg transition-colors duration-200">
                            <i class="fas fa-photo-video mr-3 text-[#910039]"></i>
                            <span class="font-medium">Na Mídia</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('treinamentos.list', ['profile_slug' => session('active_profile_slug')]) }}" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3 text-gray-700 hover:bg-[#910039] hover:text-white rounded-lg transition-colors duration-200">
                            <i class="fas fa-graduation-cap mr-3 text-[#910039]"></i>
                            <span class="font-medium">Treinamentos</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('radar.list', ['profile_slug' => session('active_profile_slug')]) }}" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3 text-gray-700 hover:bg-[#910039] hover:text-white rounded-lg transition-colors duration-200">
                            <i class="fas fa-newspaper mr-3 text-[#910039]"></i>
                            <span class="font-medium">Radar</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Mobile User Section -->
            @auth
                <div class="border-t border-gray-200 px-6 py-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-user-circle text-2xl text-[#910039] mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-900">{{ auth()->user()->name ?? 'Usuário' }}</p>
                            <p class="text-sm text-gray-500">{{ auth()->user()->email ?? '' }}</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <a href="{{ route('my.account', ['profile_slug' => session('active_profile_slug')]) }}" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                            <i class="fas fa-user-cog mr-3 text-[#910039]"></i>
                            <span class="font-medium">Minha Conta</span>
                        </a>

                        @if($user && !$user->isAdmin() && $user->profiles->count() > 1)
                            <div class="border-t border-gray-100 my-2 pt-2">
                                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2 px-4">Alternar Perfil</p>
                                <div class="space-y-1">
                                    @foreach($user->profiles as $p)
                                        @if($p->id == session('active_profile_id'))
                                            <div class="px-4 py-2 text-sm text-[#910039] flex items-center font-semibold bg-gray-50 rounded-lg">
                                                <i class="fas fa-check mr-3"></i>
                                                <span>{{ $p->name }}</span>
                                            </div>
                                        @else
                                            <form method="POST" action="{{ route('profile.switch', $p->id) }}">
                                                @csrf
                                                <button type="submit" @click="mobileMenuOpen = false" class="w-full text-left flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                                                    <i class="fas fa-chevron-right mr-3 text-[#910039]/60"></i>
                                                    <span>{{ $p->name }}</span>
                                                </button>
                                            </form>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" @click="mobileMenuOpen = false" class="w-full text-left flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                                <i class="fas fa-sign-out-alt mr-3 text-gray-500"></i>
                                <span class="font-medium">Sair</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</header>
 