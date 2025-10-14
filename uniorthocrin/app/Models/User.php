<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type_id',
        'last_access',
        'status',
        'representante_nome',
        'nome_fantasia',
        'razao_social',
        'cpf_cnpj'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_access' => 'datetime',
            'status' => 'string'
        ];
    }

    /**
     * Get the user type that owns the user.
     */
    public function userType(): BelongsTo
    {
        return $this->belongsTo(UserType::class);
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Check if the user is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Verifica se o usuário é Admin
     */
    public function isAdmin(): bool
    {
        return $this->user_type_id === 1;
    }

    /**
     * Verifica se o usuário é Franqueado
     */
    public function isFranqueado(): bool
    {
        return $this->user_type_id === 2;
    }

    /**
     * Verifica se o usuário é Lojista
     */
    public function isLojista(): bool
    {
        return $this->user_type_id === 3;
    }

    /**
     * Verifica se o usuário é Representante
     */
    public function isRepresentante(): bool
    {
        return $this->user_type_id === 4;
    }

    /**
     * Get the avatar for the user.
     */
    public function avatar(): HasOne
    {
        return $this->hasOne(File::class, 'user_id');
    }
}
