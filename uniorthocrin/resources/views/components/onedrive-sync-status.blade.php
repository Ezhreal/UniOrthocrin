@props(['item' => null, 'type' => 'product'])

@php
use App\Models\OneDriveSync;

if ($item) {
    $syncs = OneDriveSync::where('syncable_type', get_class($item))
                         ->where('syncable_id', $item->id)
                         ->get();
    
    $pendingCount = $syncs->where('status', 'pending')->count();
    $processingCount = $syncs->where('status', 'processing')->count();
    $syncedCount = $syncs->where('status', 'synced')->count();
    $failedCount = $syncs->where('status', 'failed')->count();
    $totalCount = $syncs->count();
} else {
    $pendingCount = $processingCount = $syncedCount = $failedCount = $totalCount = 0;
}
@endphp

<div class="onedrive-sync-status">
    @if($totalCount > 0)
        <div class="flex items-center space-x-2 text-sm">
            <!-- Status Icons -->
            @if($syncedCount > 0)
                <span class="flex items-center text-green-600" title="{{ $syncedCount }} arquivo(s) sincronizado(s)">
                    <i class="fas fa-check-circle mr-1"></i>
                    <span class="font-medium">{{ $syncedCount }}</span>
                </span>
            @endif
            
            @if($processingCount > 0)
                <span class="flex items-center text-blue-600" title="{{ $processingCount }} arquivo(s) processando">
                    <i class="fas fa-spinner fa-spin mr-1"></i>
                    <span class="font-medium">{{ $processingCount }}</span>
                </span>
            @endif
            
            @if($pendingCount > 0)
                <span class="flex items-center text-yellow-600" title="{{ $pendingCount }} arquivo(s) aguardando">
                    <i class="fas fa-clock mr-1"></i>
                    <span class="font-medium">{{ $pendingCount }}</span>
                </span>
            @endif
            
            @if($failedCount > 0)
                <span class="flex items-center text-red-600" title="{{ $failedCount }} arquivo(s) com erro">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    <span class="font-medium">{{ $failedCount }}</span>
                </span>
            @endif
        </div>
        
        <!-- Status Text -->
        <div class="mt-1 text-xs text-gray-500">
            @if($syncedCount === $totalCount)
                <span class="text-green-600 font-medium">
                    <i class="fas fa-check mr-1"></i>Arquivos sincronizados
                </span>
            @elseif($processingCount > 0)
                <span class="text-blue-600 font-medium">
                    <i class="fas fa-spinner fa-spin mr-1"></i>Sincronizando...
                </span>
            @elseif($pendingCount > 0)
                <span class="text-yellow-600 font-medium">
                    <i class="fas fa-clock mr-1"></i>Aguardando sincronização
                </span>
            @elseif($failedCount > 0)
                <span class="text-red-600 font-medium">
                    <i class="fas fa-exclamation-triangle mr-1"></i>Erro na sincronização
                </span>
            @endif
        </div>
    @else
        <div class="text-xs text-gray-400">
            <i class="fas fa-cloud mr-1"></i>Não sincronizado
        </div>
    @endif
</div>
