<?php
// app/Models/Soumission.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Soumission extends Model
{
    protected $fillable = [
        'devoir_id',
        'etudiant_id',
        'fichier_path',
        'note',
        'commentaire',
        'corrige_par',
        'date_correction',
        'statut', // 'en_attente', 'corrige', 'rendu_en_retard'
    ];

    protected $casts = [
        'date_correction' => 'datetime',
        'note' => 'decimal:2',
    ];

    public function devoir(): BelongsTo
    {
        return $this->belongsTo(Devoir::class);
    }

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'etudiant_id');
    }

    public function correcteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'corrige_par');
    }
}
