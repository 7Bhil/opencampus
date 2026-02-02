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

// app/Models/User.php
// app/Models/User.php
    public function canPublishCours()
    {
        $accountType = strtolower($this->account_type);

        // Les professeurs et admins peuvent toujours publier
        if (in_array($accountType, ['professeur', 'admin'])) {
            return true;
        }

        // Les étudiants doivent être premium
        if ($accountType === 'etudiant') {
            return (bool) $this->is_premium;
        }

        return false;
    }
}
