<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cours extends Model
{
    protected $fillable = [
        'user_id', // CHANGÉ : professeur_id → user_id
        'titre',
        'matiere',
        'description',
        'fichier_path',
        'est_payant',
        'prix',
        'est_public',
        'categorie',
        'nombre_vues',
        'statut', // AJOUTÉ : pour la modération des étudiants
    ];

    protected $casts = [
        'est_payant' => 'boolean',
        'est_public' => 'boolean',
        'prix' => 'decimal:2',
    ];

    // CHANGÉ : relation avec User au lieu de seulement professeur
    public function auteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // MÉTHODE AJOUTÉE : pour la compatibilité avec l'ancien code
    public function professeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class, 'cours_id');
    }

    public function telechargements(): HasMany
    {
        return $this->hasMany(Telechargement::class, 'cours_id');
    }

    public function scopePublic($query)
    {
        return $query->where('est_public', true);
    }

    // MÉTHODE AJOUTÉE : pour vérifier si c'est un professeur
    public function estPublieParProfesseur()
    {
        return $this->auteur && $this->auteur->account_type === 'Professeur';
    }
}
