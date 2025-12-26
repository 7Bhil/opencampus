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
    $etudiantId = auth()->id();

    // RÉDUISEZ AU MAXIMUM POUR TESTER
    // 1. D'abord testez sans aucune condition
    $devoirsSansFiltre = Devoir::all();
    \Log::info('Tous les devoirs (sans filtre):', $devoirsSansFiltre->toArray());

    // 2. Puis avec seulement est_actif
    $devoirsActifs = Devoir::where('est_actif', true)->get();
    \Log::info('Devoirs avec est_actif=true:', $devoirsActifs->toArray());

    // 3. Puis avec date limite
    $devoirsActifsFuturs = Devoir::where('est_actif', true)
        ->where('date_limite', '>', now())
        ->get();
    \Log::info('Devoirs actifs avec date future:', $devoirsActifsFuturs->toArray());

    // Pour le test, utilisez une requête SIMPLIFIÉE
    $devoirsARendre = Devoir::where('est_actif', true)
        ->where('date_limite', '>', now())
        ->with('professeur')
        ->orderBy('date_limite')
        ->take(5)
        ->get()
        ->map(function($devoir) {
            return [
                'id' => $devoir->id,
                'titre' => $devoir->titre . ' (TEST)',
                'matiere' => $devoir->matiere,
                'date_limite' => $devoir->date_limite->format('d M Y'),
                'jours_restants' => now()->diffInDays($devoir->date_limite, false),
                'professeur' => $devoir->professeur->name ?? 'Professeur inconnu',
            ];
        });

    \Log::info('Devoirs à rendre FINAL:', $devoirsARendre->toArray());

    // 1. Statistiques
    $stats = [
        'cours_disponibles' => Cours::where('est_public', true)->count(),
        'devoirs_soumis' => Soumission::where('etudiant_id', $etudiantId)->count(),
        'points_gagnes' => Soumission::where('etudiant_id', $etudiantId)
            ->whereNotNull('note')
            ->sum('note'),
        'devoirs_trouves' => count($devoirsARendre), // Ajoutez ceci pour debug
    ];

    // 3. Cours récents
    $coursRecents = Cours::where('est_public', true)
        ->latest()
        ->take(4)
        ->get()
        ->map(function($cours) {
            return [
                'id' => $cours->id,
                'titre' => $cours->titre,
                'description' => substr($cours->description ?? '', 0, 120) . '...',
                'date_publication' => $cours->created_at->format('d M Y'),
                'professeur' => $cours->professeur->name ?? 'Professeur inconnu',
                'est_payant' => $cours->est_payant,
                'prix' => $cours->prix,
            ];
        });

    return Inertia::render('Etudiants/DashboardEtudiants', [
        'stats' => $stats,
        'devoirs_a_rendre' => $devoirsARendre,
        'cours_recents' => $coursRecents,
    ]);
}

}
