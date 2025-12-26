<?php
// app/Models/Like.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// app/Models/Like.php
class Like extends Model
{
    protected $fillable = [
        'user_id',
        'cours_id', // ← Cette colonne doit exister
    ];

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class, 'cours_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
