<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cours extends Model
{
    protected $fillable = [
        'professeur_id',
        'titre',
        'matiere',
        'description',
        'fichier_path',
        'est_payant',
        'prix',
        'est_public', // Utilisez est_public comme vous préférez
        'categorie',
        'nombre_vues',
        // Note: supprimez 'est_actif' si vous ne l'utilisez pas
    ];

    protected $casts = [
        'est_payant' => 'boolean',
        'est_public' => 'boolean', // Utilisez seulement est_public
        'prix' => 'decimal:2',
    ];

    public function professeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professeur_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function telechargements(): HasMany
    {
        return $this->hasMany(Telechargement::class);
    }

    // Ajoutez cette méthode pour filtrer les cours publics
    public function scopePublic($query)
    {
        return $query->where('est_public', true);
    }
}
