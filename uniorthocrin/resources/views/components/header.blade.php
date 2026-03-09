<header class="bg-white w-full shadow-sm" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto flex items-center justify-between h-20 px-6">
        <!-- Logo -->
        <div class="flex items-center flex-shrink-0">
            <a href="/">
                <img src="{{ asset('images/std-hor.png') }}" alt="UniOrthocrin" class="h-12 w-auto">
            </a>
        </div>
        
        <!-- Menu Desktop -->
        <nav class="hidden md:flex flex-1 justify-center">
            <ul class="flex space-x-8">
                @php $user = auth()->user(); @endphp
                @if($user && in_array($user->user_type_id, [1,2]))
                    <li><a href="/marketing-list" class="text-[#910039] font-medium hover:underline">Marketing</a></li>
                @endif
                <li><a href="/produtos-list" class="text-[#910039] font-medium hover:underline">Produtos</a></li>
                <li><a href="/biblioteca-list" class="text-[#910039] font-medium hover:underline">Biblioteca</a></li>
                <li><a href="/treinamentos-list" class="text-[#910039] font-medium hover:underline">Treinamentos</a></li>
                <li><a href="/news-list" class="text-[#910039] font-medium hover:underline">Radar</a></li>
            </ul>
        </nav>
        
        <!-- Busca e usuário Desktop -->
        <div class="hidden md:flex items-center gap-4">
            <form action="{{ route('search.results') }}" method="GET" class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar" class="pl-4 pr-10 py-2 rounded-full bg-[#F3F3F3] text-sm text-gray-700 focus:outline-none w-56 placeholder:text-gray-400">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-[#910039]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </button>
            </form>
            
            <!-- Dropdown de Notificações -->
            @auth
                @livewire('notification-dropdown')
            @endauth
            
            <!-- Menu do usuário -->
            <div class="relative group">
                <button class="relative p-2 text-gray-600 hover:text-[#910039] transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#910039] focus:ring-opacity-50 rounded-lg">
                    <i class="fas fa-user-circle text-xl"></i>
                </button>
                
                <!-- Dropdown menu -->
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                    <a href="/my-account" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-user-cog mr-3 text-[#910039]"></i>
                        <span class="font-medium">Minha Conta</span>
                    </a>
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
            <form action="{{ route('search.results') }}" method="GET" class="relative">
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
                    @php $user = auth()->user(); @endphp
                    @if($user && in_array($user->user_type_id, [1,2]))
                        <li>
                            <a href="/marketing-list" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3 text-gray-700 hover:bg-[#910039] hover:text-white rounded-lg transition-colors duration-200">
                                <i class="fas fa-bullhorn mr-3 text-[#910039]"></i>
                                <span class="font-medium">Marketing</span>
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="/produtos-list" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3 text-gray-700 hover:bg-[#910039] hover:text-white rounded-lg transition-colors duration-200">
                            <i class="fas fa-box mr-3 text-[#910039]"></i>
                            <span class="font-medium">Produtos</span>
                        </a>
                    </li>
                    <li>
                        <a href="/biblioteca-list" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3 text-gray-700 hover:bg-[#910039] hover:text-white rounded-lg transition-colors duration-200">
                            <i class="fas fa-book mr-3 text-[#910039]"></i>
                            <span class="font-medium">Biblioteca</span>
                        </a>
                    </li>
                    <li>
                        <a href="/treinamentos-list" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3 text-gray-700 hover:bg-[#910039] hover:text-white rounded-lg transition-colors duration-200">
                            <i class="fas fa-graduation-cap mr-3 text-[#910039]"></i>
                            <span class="font-medium">Treinamentos</span>
                        </a>
                    </li>
                    <li>
                        <a href="/news-list" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3 text-gray-700 hover:bg-[#910039] hover:text-white rounded-lg transition-colors duration-200">
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
                        <a href="/my-account" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                            <i class="fas fa-user-cog mr-3 text-[#910039]"></i>
                            <span class="font-medium">Minha Conta</span>
                        </a>
                        
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