<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Devoir;
use App\Models\Cours;
use App\Models\Soumission;
use Inertia\Inertia;

class DashboardController extends Controller
{
// app/Http\Controllers\Etudiant\DashboardController.php
    public function index()
    {
        try {
            $etudiantId = auth()->id();

            // 1. Devoirs à rendre (Simplifié et Robuste)
            $devoirsARendre = Devoir::where('est_actif', true)
                ->where('date_limite', '>', now())
                ->with('professeur')
                ->orderBy('date_limite')
                ->take(5)
                ->get()
                ->map(function($devoir) {
                    return [
                        'id' => $devoir->id,
                        'titre' => $devoir->titre,
                        'matiere' => $devoir->matiere,
                        'date_limite' => $devoir->date_limite ? $devoir->date_limite->format('d M Y') : 'Non définie',
                        'jours_restants' => $devoir->date_limite ? now()->diffInDays($devoir->date_limite, false) : 0,
                        'professeur' => optional($devoir->professeur)->name ?? 'Professeur inconnu',
                    ];
                });

            // 2. Statistiques
            $stats = [
                'cours_disponibles' => Cours::where('est_public', true)->count(),
                'devoirs_soumis' => Soumission::where('etudiant_id', $etudiantId)->count(),
                'points_gagnes' => Soumission::where('etudiant_id', $etudiantId)
                    ->whereNotNull('note')
                    ->sum('note'),
            ];

            // 3. Cours récents (Fix relationship to auteur)
            $coursRecents = Cours::where('est_public', true)
                ->with('auteur')
                ->latest()
                ->take(4)
                ->get()
                ->map(function($cours) {
                    return [
                        'id' => $cours->id,
                        'titre' => $cours->titre,
                        'description' => $cours->description ? (substr($cours->description, 0, 120) . '...') : '',
                        'date_publication' => $cours->created_at ? $cours->created_at->format('d M Y') : 'Date inconnue',
                        'professeur' => optional($cours->auteur)->name ?? 'Professeur inconnu',
                        'est_payant' => (bool)$cours->est_payant,
                        'prix' => $cours->prix,
                    ];
                });

            return Inertia::render('Etudiants/DashboardEtudiants', [
                'stats' => $stats,
                'devoirs_a_rendre' => $devoirsARendre,
                'cours_recents' => $coursRecents,
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur Dashboard Étudiant: ' . $e->getMessage());
            return Inertia::render('Etudiants/DashboardEtudiants', [
                'stats' => ['cours_disponibles' => 0, 'devoirs_soumis' => 0, 'points_gagnes' => 0],
                'devoirs_a_rendre' => [],
                'cours_recents' => [],
                'error' => 'Une erreur est survenue lors du chargement de votre tableau de bord.'
            ]);
        }
    }

}
