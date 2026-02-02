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
        try {
            // Vérification du rôle
            $user = auth()->user();
            if ($user->account_type !== 'Professeur' && $user->account_type !== 'Admin') {
                return redirect()->route('dashboard');
            }

            $professeurId = auth()->id();

            // Statistiques (Fix professeur_id -> user_id in Cours)
            $stats = [
                'total_cours' => Cours::where('user_id', $professeurId)->count(),
                'total_devoirs' => Devoir::where('professeur_id', $professeurId)->count(),
                'total_etudiants' => User::where('account_type', 'Etudiant')->count(),
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
                        'etudiant' => optional($soumission->etudiant)->name ?? 'Étudiant inconnu',
                        'filiere' => optional($soumission->etudiant)->filiere ?? 'Non spécifié',
                        'titre' => optional($soumission->devoir)->titre ?? 'Devoir inconnu',
                        'matiere' => optional($soumission->devoir)->matiere ?? 'Non spécifié',
                        'date' => $soumission->created_at ? $soumission->created_at->format('d M Y') : 'Date inconnue',
                        'statut' => $this->getStatutText($soumission->statut),
                        'devoir_id' => $soumission->devoir_id,
                    ];
                })->toArray();

            // Cours RÉCENTS du professeur (Fix professeur_id -> user_id)
            $coursRecents = Cours::where('user_id', $professeurId)
                ->latest()
                ->take(3)
                ->get()
                ->map(function($cours) {
                    return [
                        'id' => $cours->id,
                        'titre' => $cours->titre,
                        'type' => $cours->type ?? 'Cours',
                        'filiere' => $cours->filiere ?? 'Toutes filières',
                        'description' => $cours->description ? (substr($cours->description, 0, 100) . '...') : '',
                        'date' => $cours->created_at ? $cours->created_at->format('d M Y') : 'Date inconnue',
                        'vues' => $cours->nombre_vues ?? 0, // Fix vues -> nombre_vues
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
        } catch (\Exception $e) {
            \Log::error('Erreur Dashboard Professeur: ' . $e->getMessage());
            return Inertia::render('Professeurs/DashboardProfesseurs', [
                'stats' => ['total_cours' => 0, 'total_devoirs' => 0, 'total_etudiants' => 0, 'a_corriger' => 0],
                'devoirsRecents' => [],
                'coursRecents' => [],
                'error' => 'Une erreur est survenue lors du chargement de votre tableau de bord.'
            ]);
        }
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
