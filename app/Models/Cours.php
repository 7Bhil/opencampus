<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titre',
        'matiere',
        'description',
        'fichier_path',
        'est_payant',
        'prix',
        'est_public',
        'categorie',
        'nombre_vues',
        'statut',

        // AJOUTEZ CES COLONNES SI ELLES EXISTENT DANS LA TABLE
        'est_modere',
        'est_approuve',
        'approuve_le',
        'approuve_par',
        'raison_rejet',
        'nombre_achats',
    ];

    protected $casts = [
        'est_public' => 'boolean',
        'est_payant' => 'boolean',
        'prix' => 'decimal:2',
        'nombre_vues' => 'integer',
        'est_modere' => 'boolean',
        'est_approuve' => 'boolean',
        'nombre_achats' => 'integer',
        'approuve_le' => 'datetime',
    ];

    // Valeurs par défaut
    protected $attributes = [
        'est_public' => true,
        'est_payant' => false,
        'nombre_vues' => 0,
        'nombre_achats' => 0,
        'est_modere' => false,
        'est_approuve' => false,
    ];

    // Relations
    public function auteur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approbateur()
    {
        return $this->belongsTo(User::class, 'approuve_par');
    }

    public function achats()
    {
        return $this->hasMany(Achat::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function telechargements()
    {
        return $this->hasMany(Telechargement::class);
    }

    // Méthodes utiles
    public function estApprouve()
    {
        return $this->est_modere && $this->est_approuve && $this->est_public;
    }

    public function estEnAttente()
    {
        return !$this->est_modere && $this->auteur && $this->auteur->account_type === 'Etudiant';
    }

    public function peutEtreVuPar(User $user = null)
    {
        // Public et approuvé
        if ($this->est_public && $this->est_approuve) {
            return true;
        }

        // L'auteur peut toujours voir son cours
        if ($user && $user->id === $this->user_id) {
            return true;
        }

        // Les admins peuvent tout voir
        if ($user && $user->account_type === 'Admin') {
            return true;
        }

        return false;
    }

    public function formatPrix()
    {
        if (!$this->est_payant) {
            return 'Gratuit';
        }
        return number_format($this->prix, 0, ',', ' ') . ' f';
    }
}
