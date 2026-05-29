<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FtpSync extends Model
{
    use HasFactory;

    protected $table = 'ftp_syncs';

    protected $fillable = [
        'syncable_type',
        'syncable_id',
        'file_id',
        'local_path',
        'remote_path',
        'status',
        'error_message',
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

    // Relacionamento com arquivo
    public function file()
    {
        return $this->belongsTo(File::class, 'file_id');
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

    public function markAsSynced()
    {
        $this->update([
            'status' => 'synced',
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
