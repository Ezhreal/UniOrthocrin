<header class="bg-white w-full shadow-sm">
    <div class="max-w-7xl mx-auto flex items-center justify-between h-20 px-6">
        <!-- Logo -->
        <div class="flex items-center flex-shrink-0">
            <a href="/">
                <img src="{{ asset('images/logo.png') }}" alt="UniOrthocrin" class="h-12 w-auto">
            </a>
        </div>
        <!-- Menu centralizado -->
        <nav class="flex-1 flex justify-center">
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
        <!-- Busca e usuário -->
        <div class="flex items-center gap-4">
            <form action="#" method="GET" class="relative">
                <input type="text" name="q" placeholder="Buscar" class="pl-4 pr-10 py-2 rounded-full bg-[#F3F3F3] text-sm text-gray-700 focus:outline-none w-56 placeholder:text-gray-400">
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
    </div>
</header> 