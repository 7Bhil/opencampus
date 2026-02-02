<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Devoir;
use App\Models\Cours;
use App\Models\Soumission;
use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Vérification du rôle
        $user = auth()->user();
        if ($user->account_type !== 'Professeur' && $user->account_type !== 'Admin') {
            return redirect()->route('dashboard.redirect');
        }

        $professeurId = auth()->id();

        // Statistiques RÉELLES
        $stats = [
            'total_cours' => Cours::where('professeur_id', $professeurId)->count(),
            'total_devoirs' => Devoir::where('professeur_id', $professeurId)->count(),
            'total_etudiants' => User::where('account_type', 'etudiant')->count(),
            'a_corriger' => Soumission::whereHas('devoir', function($query) use ($professeurId) {
                $query->where('professeur_id', $professeurId);
            })->where('statut', 'en_attente')->count(),
        ];


        // Devoirs soumis RÉCENTS
        $devoirsRecents = Soumission::whereHas('devoir', function($query) use ($professeurId) {
                $query->where('professeur_id', $professeurId);
            })
            ->with(['etudiant', 'devoir'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function($soumission) {
                return [
                    'id' => $soumission->id,
                    'etudiant' => $soumission->etudiant->name ?? 'Étudiant inconnu',
                    'filiere' => $soumission->etudiant->filiere ?? 'Non spécifié',
                    'titre' => $soumission->devoir->titre ?? 'Devoir inconnu',
                    'matiere' => $soumission->devoir->matiere ?? 'Non spécifié',
                    'date' => $soumission->created_at->format('d M Y'),
                    'statut' => $this->getStatutText($soumission->statut),
                    'devoir_id' => $soumission->devoir_id,
                ];
            })->toArray();

        // Cours RÉCENTS du professeur
        $coursRecents = Cours::where('professeur_id', $professeurId)
            ->latest()
            ->take(3)
            ->get()
            ->map(function($cours) {
                return [
                    'id' => $cours->id,
                    'titre' => $cours->titre,
                    'type' => $cours->type ?? 'Cours',
                    'filiere' => $cours->filiere ?? 'Toutes filières',
                    'description' => substr($cours->description ?? '', 0, 100) . '...',
                    'date' => $cours->created_at->format('d M Y'),
                    'vues' => $cours->vues ?? 0,
                    'likes' => $cours->likes_count ?? 0,
                    'commentaires' => $cours->commentaires_count ?? 0,
                    'cours_id' => $cours->id,
                ];
            })->toArray();

        return Inertia::render('Professeurs/DashboardProfesseurs', [
            'stats' => $stats,
            'devoirsRecents' => $devoirsRecents,
            'coursRecents' => $coursRecents,
        ]);
    }

    private function getStatutText($statut)
    {
        $statuts = [
            'en_attente' => 'À corriger',
            'corrige' => 'Corrigé',
            'en_retard' => 'En retard',
        ];

        return $statuts[$statut] ?? $statut;
    }
}
