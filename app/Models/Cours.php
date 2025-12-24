<?php
// app/Models/Cours.php

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
        'est_public',
        'categorie',
        'nombre_vues',
    ];

    protected $casts = [
        'est_payant' => 'boolean',
        'est_public' => 'boolean',
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
}
