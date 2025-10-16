<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OneDriveSync extends Model
{
    use HasFactory;

    protected $table = 'onedrive_syncs';

    protected $fillable = [
        'syncable_type',
        'syncable_id',
        'file_path',
        'remote_path',
        'status',
        'error_message',
        'onedrive_url',
        'synced_at'
    ];

    protected $casts = [
        'synced_at' => 'datetime',
    ];

    // Relacionamento polimórfico
    public function syncable()
    {
        return $this->morphTo();
    }

    // Scopes para status
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeSynced($query)
    {
        return $query->where('status', 'synced');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // Métodos de status
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isSynced(): bool
    {
        return $this->status === 'synced';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    // Métodos para atualizar status
    public function markAsProcessing()
    {
        $this->update(['status' => 'processing']);
    }

    public function markAsSynced(string $onedriveUrl = null)
    {
        $this->update([
            'status' => 'synced',
            'onedrive_url' => $onedriveUrl,
            'synced_at' => now()
        ]);
    }

    public function markAsFailed(string $errorMessage = null)
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage
        ]);
    }
}
