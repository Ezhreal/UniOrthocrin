@props(['item' => null, 'type' => 'product'])

@php
use App\Models\OneDriveSync;

if ($item) {
    $syncs = OneDriveSync::where('syncable_type', get_class($item))
                         ->where('syncable_id', $item->id)
                         ->get();
    
    $hasFiles = $syncs->count() > 0;
    $allSynced = $syncs->count() > 0 && $syncs->where('status', '!=', 'synced')->count() === 0;
    $hasFailed = $syncs->where('status', 'failed')->count() > 0;
    $hasPending = $syncs->where('status', 'pending')->count() > 0;
} else {
    $hasFiles = $allSynced = $hasFailed = $hasPending = false;
}
@endphp

<div class="onedrive-sync-actions">
    @if($hasFiles)
        @if($allSynced)
            <button type="button" 
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-700 bg-green-100 rounded-lg hover:bg-green-200 transition-colors duration-200"
                    disabled>
                <i class="fas fa-check-circle mr-1"></i>
                Sincronizado
            </button>
        @elseif($hasFailed)
            <button type="button" 
                    onclick="retryOneDriveSync('{{ $type }}', {{ $item->id }})"
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 transition-colors duration-200">
                <i class="fas fa-redo mr-1"></i>
                Tentar Novamente
            </button>
        @elseif($hasPending)
            <button type="button" 
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-lg"
                    disabled>
                <i class="fas fa-clock mr-1"></i>
                Aguardando
            </button>
        @else
            <button type="button" 
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg"
                    disabled>
                <i class="fas fa-spinner fa-spin mr-1"></i>
                Processando
            </button>
        @endif
    @else
        <button type="button" 
                onclick="syncToOneDrive('{{ $type }}', {{ $item->id }})"
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
            <i class="fas fa-cloud-upload-alt mr-1"></i>
            Sincronizar
        </button>
    @endif
</div>

<script>
function syncToOneDrive(type, id) {
    if (confirm('Deseja sincronizar todos os arquivos para o OneDrive?')) {
        fetch(`/admin/${type}/${id}/onedrive/sync`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao sincronizar: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao sincronizar arquivos');
        });
    }
}

function retryOneDriveSync(type, id) {
    if (confirm('Deseja tentar sincronizar novamente os arquivos com erro?')) {
        fetch(`/admin/${type}s/${id}/onedrive/retry`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao tentar novamente: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao tentar sincronizar novamente');
        });
    }
}
</script>
