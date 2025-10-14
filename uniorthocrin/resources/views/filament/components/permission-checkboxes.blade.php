<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="space-y-4">
        <div class="text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ $getLabel() }}
        </div>
        
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($getUserTypes() as $userType)
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                        <div class="font-medium text-sm text-gray-900 dark:text-gray-100 mb-2">
                            {{ $userType['name'] }}
                        </div>
                        
                        <div class="space-y-2">
                            @foreach($getPermissions() as $permission)
                                <label class="flex items-center space-x-2">
                                    <input 
                                        type="checkbox" 
                                        name="{{ $getName() }}[{{ $userType['id'] }}][{{ $permission }}]"
                                        value="1"
                                        class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                        wire:model="{{ $getName() }}.{{ $userType['id'] }}.{{ $permission }}"
                                    >
                                    <span class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ $getPermissionLabels()[$permission] ?? ucfirst(str_replace('_', ' ', $permission)) }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="text-xs text-gray-500 dark:text-gray-400">
            Selecione quais tipos de usuário podem visualizar e baixar este conteúdo.
        </div>
    </div>
</x-dynamic-component>
