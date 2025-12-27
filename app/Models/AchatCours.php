<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AchatCours extends Model
{
    protected $fillable = ['user_id', 'cours_id', 'montant', 'transaction_id', 'statut'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }
}
