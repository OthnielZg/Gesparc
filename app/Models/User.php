<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'prenom',
        'nom',
        'email',
        'password',
        'contact',
        'actif',
        'role',
        'id_bureau',
        'photo_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'actif' => 'boolean',
        ];
    }

    // Role constants
    const ROLE_ADMIN       = 'admin';
    const ROLE_AGENT       = 'agent';
    const ROLE_CHEF_CDI    = 'chef_cdi';
    const ROLE_DR          = 'DR';
    const ROLE_GESTIONNAIRE = 'gestionnaire';
    const ROLE_SDREVT      = 'SDREVT';
    const ROLE_DSI         = 'DSI';

    public static function allRoles(): array
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_AGENT,
            self::ROLE_CHEF_CDI,
            self::ROLE_DR,
            self::ROLE_GESTIONNAIRE,
            self::ROLE_SDREVT,
            self::ROLE_DSI,
        ];
    }

    public static function roleLabels(): array
    {
        return [
            self::ROLE_ADMIN        => 'Administrateur',
            self::ROLE_AGENT        => 'Agent',
            self::ROLE_CHEF_CDI     => 'Chef CDI',
            self::ROLE_DR           => 'Directeur Régional (DR)',
            self::ROLE_GESTIONNAIRE => 'Chef SEDMI (Gestionnaire)',
            self::ROLE_SDREVT       => 'SDREVT',
            self::ROLE_DSI          => 'DSI',
        ];
    }

    public function getRoleLabel(): string
    {
        return self::roleLabels()[$this->role] ?? $this->role;
    }

    public function hasRole(string|array $roles): bool
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }
        return $this->role === $roles;
    }

    public function isAdmin(): bool    { return $this->role === self::ROLE_ADMIN; }
    public function isAgent(): bool    { return $this->role === self::ROLE_AGENT; }
    public function isChefCdi(): bool  { return $this->role === self::ROLE_CHEF_CDI; }
    public function isDR(): bool       { return $this->role === self::ROLE_DR; }
    public function isGestionnaire(): bool { return $this->role === self::ROLE_GESTIONNAIRE; }
    public function isSDREVT(): bool   { return $this->role === self::ROLE_SDREVT; }
    public function isDSI(): bool      { return $this->role === self::ROLE_DSI; }

    public function getFullNameAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    // Relationships
    public function bureau()
    {
        return $this->belongsTo(Bureau::class, 'id_bureau');
    }

    public function demandes()
    {
        return $this->hasMany(Demande::class, 'id_utilisateur');
    }

    public function validations()
    {
        return $this->hasMany(Validation::class, 'id_utilisateur');
    }

    public function affectations()
    {
        return $this->hasMany(Affectation::class, 'id_utilisateur');
    }
}
