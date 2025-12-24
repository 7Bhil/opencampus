<?php
// app/Models/Devoir.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Devoir extends Model
{
    protected $fillable = [
        'professeur_id',
        'titre',
        'matiere',
        'description',
        'fichier_path', // Énoncé du devoir
        'date_limite',
        'points',
        'est_actif',
    ];

    protected $casts = [
        'date_limite' => 'datetime',
        'est_actif' => 'boolean',
        'points' => 'integer',
    ];

    public function professeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professeur_id');
    }

    public function soumissions(): HasMany
    {
        return $this->hasMany(Soumission::class);
    }
}
