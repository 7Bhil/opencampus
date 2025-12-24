<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'account_type',
        'filiere',
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
        ];
    }

    /**
     * Relation avec les cours créés (pour les professeurs)
     */
    public function cours(): HasMany
    {
        return $this->hasMany(Cours::class, 'professeur_id');
    }

    /**
     * Scope pour les étudiants
     */
    public function scopeEtudiants($query)
    {
        return $query->where('account_type', 'Etudiant');
    }

    /**
     * Scope pour les professeurs
     */
    public function scopeProfesseurs($query)
    {
        return $query->where('account_type', 'Professeur');
    }

    /**
     * Vérifie si l'utilisateur est un étudiant
     */
    public function isEtudiant()
    {
        return $this->account_type === 'Etudiant';
    }

    /**
     * Vérifie si l'utilisateur est un professeur
     */
    public function isProfesseur()
    {
        return $this->account_type === 'Professeur';
    }

}
