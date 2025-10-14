<div class="relative" x-data="{ open: false }">
    <!-- Botão de Notificações -->
    <button 
        @click="open = !open"
        class="relative p-2 text-gray-600 hover:text-[#910039] transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#910039] focus:ring-opacity-50 rounded-lg"
        wire:click="toggleDropdown"
        wire:loading.attr="disabled"
        wire:loading.class="opacity-50 cursor-not-allowed"
    >
        <!-- Ícone de Notificação -->
        <i class="fas fa-bell text-xl"></i>
        
        <!-- Contador de Notificações Não Lidas -->
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-semibold">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
        
        <!-- Indicador de Carregamento -->
        <div wire:loading class="absolute inset-0 flex items-center justify-center">
            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-[#910039]"></div>
        </div>
    </button>

    <!-- Dropdown de Notificações -->
    <div 
        x-show="open" 
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50 max-h-96 overflow-hidden"
        style="display: none;"
    >
        <!-- Header do Dropdown -->
        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">
                    Notificações
                </h3>
                @if($unreadCount > 0)
                    <button 
                        wire:click="markAllAsRead"
                        class="text-xs text-[#910039] hover:text-[#7a0030] font-medium transition-colors duration-200"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                    >
                        Marcar todas como lidas
                    </button>
                @endif
            </div>
        </div>

        <!-- Lista de Notificações -->
        <div class="max-h-64 overflow-y-auto">
            @if($isLoading)
                <!-- Estado de Carregamento -->
                <div class="p-4 text-center">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-[#910039] mx-auto mb-2"></div>
                    <p class="text-sm text-gray-500">Carregando notificações...</p>
                </div>
            @elseif(empty($notifications))
                <!-- Estado Vazio -->
                <div class="p-6 text-center">
                    <i class="fas fa-bell-slash text-3xl text-gray-300 mb-3"></i>
                    <p class="text-sm text-gray-500">Nenhuma notificação</p>
                    <p class="text-xs text-gray-400 mt-1">Você está em dia!</p>
                </div>
            @else
                <!-- Lista de Notificações -->
                @foreach($notifications as $notification)
                    <div class="border-b border-gray-100 last:border-b-0">
                        <div class="p-4 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex items-start space-x-3">
                                <!-- Ícone do Tipo -->
                                <div class="flex-shrink-0 mt-1">
                                    <i class="{{ $this->getTypeIcon($notification['type']) }} {{ $this->getTypeColor($notification['type']) }} text-lg"></i>
                                </div>
                                
                                <!-- Conteúdo da Notificação -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900 {{ $notification['is_read'] ? '' : 'font-semibold' }}">
                                                {{ $notification['title'] }}
                                            </p>
                                            <p class="text-sm text-gray-600 mt-1 line-clamp-2">
                                                {{ $notification['message'] }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-2">
                                                {{ $notification['created_at'] }}
                                            </p>
                                        </div>
                                        
                                        <!-- Indicador de Não Lida -->
                                        @if(!$notification['is_read'])
                                            <div class="flex-shrink-0 ml-2">
                                                <div class="w-2 h-2 bg-[#910039] rounded-full"></div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Ações da Notificação -->
                                    <div class="flex items-center space-x-2 mt-3">
                                        @if($notification['related_type'] && $notification['related_id'])
                                            <button 
                                                wire:click="navigateToRelated({{ json_encode($notification) }})"
                                                class="text-xs text-[#910039] hover:text-[#7a0030] font-medium transition-colors duration-200"
                                            >
                                                Ver conteúdo
                                            </button>
                                        @endif
                                        
                                        <button 
                                            wire:click="markAsRead({{ $notification['id'] }})"
                                            class="text-xs text-gray-500 hover:text-gray-700 transition-colors duration-200"
                                            wire:loading.attr="disabled"
                                            wire:loading.class="opacity-50 cursor-not-allowed"
                                        >
                                            Marcar como lida
                                        </button>
                                        
                                        <button 
                                            wire:click="deleteNotification({{ $notification['id'] }})"
                                            class="text-xs text-red-500 hover:text-red-700 transition-colors duration-200"
                                            wire:loading.attr="disabled"
                                            wire:loading.class="opacity-50 cursor-not-allowed"
                                        >
                                            Remover
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Footer do Dropdown -->
        @if(!empty($notifications))
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">
                        {{ count($notifications) }} notificação(ões)
                    </span>
                    <a 
                        href="{{ route('notifications.user') }}" 
                        class="text-xs text-[#910039] hover:text-[#7a0030] font-medium transition-colors duration-200"
                    >
                        Ver todas
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
