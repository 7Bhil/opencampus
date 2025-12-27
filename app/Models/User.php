<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'account_type',
        'filiere',
        'matricule',
        'is_premium', // AJOUTÉ
        'premium_until', // AJOUTÉ
        'balance', // AJOUTÉ
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_premium' => 'boolean', // AJOUTÉ
        'premium_until' => 'datetime', // AJOUTÉ
        'balance' => 'decimal:2', // AJOUTÉ
    ];

    // AJOUTER ces méthodes :
    public function cours()
    {
        return $this->hasMany(Cours::class, 'user_id');
    }

public function canPublishCours()
{
    \Log::info('=== canPublishCours CHECK ===');
    \Log::info('User ID: ' . $this->id);
    \Log::info('Account type: ' . $this->account_type);
    \Log::info('Is premium: ' . ($this->is_premium ? 'yes' : 'no'));

    if ($this->account_type === 'Professeur') {
        \Log::info('→ Professeur → AUTORISÉ');
        return true;
    }

    if ($this->account_type === 'Etudiant' && $this->is_premium) {
        \Log::info('→ Étudiant premium → AUTORISÉ');
        return true;
    }

    \Log::info('→ NON AUTORISÉ');
    return false;
}

    public function hasPremiumAccess()
    {
        return $this->is_premium === true;
    }

    // Pour la compatibilité avec l'ancien code
    public function coursEnseignes()
    {
        return $this->hasMany(Cours::class, 'user_id')
                    ->where('account_type', 'Professeur');
    }
}
