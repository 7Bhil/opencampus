<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\User;
use App\Models\Devoir;
use App\Models\AchatCours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminController extends Controller
{
    /**
     * Dashboard Admin - Vue d'ensemble
     */
    public function dashboard()
    {
        try {
            $stats = [
                'totalUsers' => User::count(),
                'totalCours' => Cours::count(),
                'totalProfesseurs' => User::where('account_type', 'Professeur')->count(),
                'coursAModerer' => Cours::where(function($q) {
                                      $q->where('est_modere', false)
                                        ->orWhereNull('est_modere');
                                  })
                                  ->where(function($q) {
                                      $q->where('est_approuve', false)
                                        ->orWhereNull('est_approuve');
                                  })
                                  ->count(),
            ];

            // Construction des activités récentes (Safe)
            $users = User::latest()->take(5)->get()->map(fn($u) => [
                'type' => 'user',
                'description' => "Nouvel utilisateur inscrit : " . ($u->name ?? 'Anonyme'),
                'time' => $u->created_at ? $u->created_at->diffForHumans() : 'Date inconnue',
                'timestamp' => $u->created_at ? $u->created_at->timestamp : 0,
                'badge' => 'Inscription',
            ]);

            $cours = Cours::with('auteur')->latest()->take(5)->get()->map(fn($c) => [
                'type' => 'course',
                'description' => "Nouveau cours publié : " . ($c->titre ?? 'Sans titre'),
                'time' => $c->created_at ? $c->created_at->diffForHumans() : 'Date inconnue',
                'timestamp' => $c->created_at ? $c->created_at->timestamp : 0,
                'badge' => 'Cours',
            ]);

            $recentActivities = $users->concat($cours)
                                    ->sortByDesc('timestamp')
                                    ->values()
                                    ->all();
            
            return Inertia::render('Admin/Dashboard', [
                'stats' => $stats,
                'recentActivities' => $recentActivities,
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur Dashboard Admin: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Fallback pour éviter le 500 fatal
            return Inertia::render('Admin/Dashboard', [
                'stats' => [
                    'totalUsers' => 0,
                    'totalCours' => 0,
                    'totalProfesseurs' => 0,
                    'coursAModerer' => 0
                ],
                'recentActivities' => [],
                'error' => 'Erreur lors du chargement des statistiques : ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Liste des utilisateurs avec pagination
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Filtres
        if ($request->filled('type')) {
            $query->where('account_type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => $request->only(['type', 'search']),
        ]);
    }

    /**
     * Mettre à jour le statut d'un utilisateur
     */
    public function updateUserStatus(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);
        
        $request->validate([
            'account_type' => 'nullable|in:Etudiant,Professeur,Admin',
            'is_premium' => 'nullable|boolean',
            'balance' => 'nullable|numeric|min:0',
        ]);

        $data = [];
        if ($request->has('account_type')) $data['account_type'] = $request->account_type;
        if ($request->has('is_premium')) $data['is_premium'] = $request->boolean('is_premium');
        if ($request->has('balance')) $data['balance'] = $request->balance;

        $user->update($data);

        return back()->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Supprimer un utilisateur (admin seulement)
     */
    public function deleteUser(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        // Empêcher l'auto-suppression
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte administrateur.');
        }

        // 1. Supprimer les fichiers physiques des cours
        foreach ($user->cours as $cours) {
            if ($cours->fichier_path && Storage::disk('public')->exists($cours->fichier_path)) {
                Storage::disk('public')->delete($cours->fichier_path);
            }
        }

        // 2. Supprimer les fichiers physiques des soumissions
        $soumissions = \App\Models\Soumission::where('etudiant_id', $user->id)->get();
        foreach ($soumissions as $soumission) {
            if ($soumission->fichier_path && Storage::disk('public')->exists($soumission->fichier_path)) {
                Storage::disk('public')->delete($soumission->fichier_path);
            }
        }

        // 3. Supprimer l'utilisateur
        $user->delete();

        return back()->with('success', 'Utilisateur et toutes ses données associées ont été supprimés.');
    }

    /**
     * Liste des cours (tous)
     */
    public function cours(Request $request)
    {
        $query = Cours::with(['auteur', 'approbateur']);

        // Filtres
        if ($request->filled('statut')) {
            switch ($request->statut) {
                case 'attente':
                    $query->where('est_modere', false)
                          ->where('est_approuve', false);
                    break;
                case 'approuves':
                    $query->where('est_approuve', true);
                    break;
                case 'rejetes':
                    $query->where('est_modere', true)
                          ->where('est_approuve', false);
                    break;
                case 'publics':
                    $query->where('est_public', true);
                    break;
                case 'prives':
                    $query->where('est_public', false);
                    break;
            }
        }

        if ($request->filled('type_auteur')) {
            $query->whereHas('auteur', function($q) use ($request) {
                $q->where('account_type', $request->type_auteur);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('matiere', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $cours = $query->latest()->paginate(20);

        return Inertia::render('Admin/Cours/Index', [
            'cours' => $cours,
            'filters' => $request->only(['statut', 'type_auteur', 'search']),
        ]);
    }

    /**
     * Cours en attente de modération (spécial pour les étudiants)
     */
    public function coursAModerer()
    {
        $query = Cours::where('est_modere', false)
                 ->where('est_approuve', false)
                 ->whereHas('auteur', function($q) {
                     $q->where('account_type', 'Etudiant');
                 })
                 ->with('auteur')
                 ->latest();

    // CHANGÉ : utilisez ->get() au lieu de ->paginate()
    $cours = $query->get();
        return Inertia::render('Admin/Cours/Moderation', [
            'cours' => $cours,
        ]);
    }

    /**
     * Approuver un cours étudiant
     */
    public function approuver(Request $request, Cours $cours)
{
    // Vérifier que le cours est bien en attente
    if ($cours->est_modere || $cours->est_approuve) {
        return back()->with('error', 'Ce cours a déjà été modéré.');
    }

    // Vérifier que l'auteur est bien étudiant
    if ($cours->auteur->account_type !== 'Etudiant') {
        return back()->with('error', 'Seuls les cours étudiants nécessitent une modération.');
    }

    // SUPPRIMEZ 'statut' de l'update
    $cours->update([
        'est_public' => true,
        'est_modere' => true,
        'est_approuve' => true,
        'approuve_le' => now(),
        'approuve_par' => Auth::id(),
        // 'statut' => 'approuve', // Supprimez cette ligne
    ]);

    return back()->with('success', 'Cours approuvé et publié avec succès !');
}

public function rejeter(Request $request, Cours $cours)
{
    // Valider
    $validated = $request->validate([
        'raison_rejet' => 'required|string|min:10|max:500',
    ]);

    \Log::info('Tentative de rejet', [
        'cours_id' => $cours->id,
        'raison' => $validated['raison_rejet'],
        'est_modere_avant' => $cours->est_modere,
        'est_approuve_avant' => $cours->est_approuve
    ]);

    // Vérifier si déjà modéré
    if ($cours->est_modere) {
        return back()->with('error', 'Ce cours a déjà été modéré.');
    }

    try {
        // Met à jour SANS 'statut' si la colonne n'existe pas
        $updateData = [
            'est_modere' => true,
            'est_approuve' => false,
            'approuve_le' => now(),
            'approuve_par' => Auth::id(),
            'raison_rejet' => $validated['raison_rejet'],
            // 'statut' => 'rejete', // Supprime si colonne inexistante
        ];

        // Option : Si tu veux garder le cours privé quand rejeté
        $updateData['est_public'] = false;

        $cours->update($updateData);

        \Log::info('Cours rejeté avec succès', [
            'cours_id' => $cours->id,
            'data' => $updateData
        ]);

        return back()->with('success', 'Cours rejeté !');

    } catch (\Exception $e) {
        \Log::error('Erreur rejet cours', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->with('error', 'Erreur: ' . $e->getMessage());
    }
}

    /**
     * Supprimer un cours (admin seulement)
     */
    public function deleteCours(Request $request, Cours $cours)
    {
        $request->validate([
            'raison' => 'nullable|string|max:500',
        ]);

        // Supprimer le fichier physique
        if ($cours->fichier_path && Storage::disk('public')->exists($cours->fichier_path)) {
            Storage::disk('public')->delete($cours->fichier_path);
        }

        // Supprimer le cours de la base
        $cours->delete();

        // Ici, vous pouvez ajouter une notification à l'auteur

        return back()->with('success', 'Cours supprimé définitivement.');
    }

    /**
     * Vue détaillée d'un cours
     */
    public function showCours(Cours $cours)
    {
        $cours->load(['auteur', 'approbateur', 'achats.user', 'likes.user']);

        return Inertia::render('Admin/Cours/Show', [
            'cours' => $cours,
            'telechargements' => $cours->telechargements()->count(),
            'achats' => $cours->achats()->count(),
            'likes' => $cours->likes()->count(),
        ]);
    }

    /**
     * Liste des devoirs
     */
    public function devoirs(Request $request)
    {
        $query = Devoir::with(['professeur', 'cours']);

        if ($request->filled('statut')) {
            $query->where('est_actif', $request->statut === 'actif');
        }

        if ($request->filled('professeur')) {
            $query->where('user_id', $request->professeur);
        }

        $devoirs = $query->latest()->paginate(20);

        return Inertia::render('Admin/Devoirs/Index', [
            'devoirs' => $devoirs,
            'professeurs' => User::where('account_type', 'Professeur')->get(['id', 'name']),
            'filters' => $request->only(['statut', 'professeur']),
        ]);
    }

    /**
     * Voir un devoir spécifique
     */
    public function showDevoir(Devoir $devoir)
    {
        $devoir->load(['professeur', 'cours', 'soumissions.etudiant']);

        return Inertia::render('Admin/Devoirs/Show', [
            'devoir' => $devoir,
            'soumissionsCount' => $devoir->soumissions()->count(),
            'soumissionsCorrigees' => $devoir->soumissions()->whereNotNull('note')->count(),
        ]);
    }

    /**
     * Statistiques détaillées
     */
    public function statistiques()
    {
        // Statistiques mensuelles
        $statsMensuelles = [
            'inscriptions' => User::whereYear('created_at', date('Y'))
                                 ->whereMonth('created_at', date('m'))
                                 ->count(),
            'cours' => Cours::whereYear('created_at', date('Y'))
                           ->whereMonth('created_at', date('m'))
                           ->count(),
            'achats' => AchatCours::whereYear('created_at', date('Y'))
                            ->whereMonth('created_at', date('m'))
                            ->count(),
            'revenu' => AchatCours::whereYear('created_at', date('Y'))
                            ->whereMonth('created_at', date('m'))
                            ->sum('montant'),
        ];

        // Top 5 cours les plus vus
        $topCoursVus = Cours::where('est_public', true)
                           ->where('est_approuve', true)
                           ->orderBy('nombre_vues', 'desc')
                           ->take(5)
                           ->get(['id', 'titre', 'nombre_vues', 'auteur_id']);

        // Top 5 cours les plus achetés
        $topCoursAchetes = Cours::where('est_payant', true)
                               ->withCount('achats')
                               ->orderBy('achats_count', 'desc')
                               ->take(5)
                               ->get(['id', 'titre', 'prix']);

        // Évolution sur 6 mois
        $evolution = [];
        for ($i = 5; $i >= 0; $i--) {
            $mois = now()->subMonths($i);
            $moisFormat = $mois->format('Y-m');

            $evolution[$moisFormat] = [
                'cours' => Cours::whereYear('created_at', $mois->year)
                               ->whereMonth('created_at', $mois->month)
                               ->count(),
                'utilisateurs' => User::whereYear('created_at', $mois->year)
                                     ->whereMonth('created_at', $mois->month)
                                     ->count(),
                'revenu' => AchatCours::whereYear('created_at', $mois->year)
                                ->whereMonth('created_at', $mois->month)
                                ->sum('montant'),
            ];
        }

        return Inertia::render('Admin/Statistiques', [
            'statsMensuelles' => $statsMensuelles,
            'topCoursVus' => $topCoursVus,
            'topCoursAchetes' => $topCoursAchetes,
            'evolution' => $evolution,
        ]);
    }

    /**
     * Paramètres système
     */
    public function parametres()
    {
        $parametres = [
            'site' => [
                'nom' => config('app.name'),
                'url' => config('app.url'),
                'debug' => config('app.debug'),
                'env' => config('app.env'),
            ],
            'mail' => [
                'driver' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
            ],
            'fichiers' => [
                'driver' => config('filesystems.default'),
                'max_upload' => ini_get('upload_max_filesize'),
                'max_post' => ini_get('post_max_size'),
            ],
        ];

        return Inertia::render('Admin/Parametres', [
            'parametres' => $parametres,
        ]);
    }

    /**
     * Mettre à jour les paramètres
     */
    public function updateParametres(Request $request)
    {
        // Ici, vous pourriez implémenter la mise à jour de configuration
        // Attention : cela nécessite généralement un système plus avancé
        // avec stockage en base de données

        return back()->with('success', 'Paramètres mis à jour (simulation).');
    }

    /**
     * Journal d'activité
     */
    public function journal()
    {
        // Ici, vous pourriez intégrer un package comme spatie/laravel-activitylog
        // Pour l'instant, on montre un exemple basique

        $activites = [
            // Exemple de structure
            [
                'date' => now()->subHours(2),
                'utilisateur' => 'Admin Système',
                'action' => 'Cours approuvé',
                'details' => 'Cours "Introduction à Laravel" approuvé',
                'ip' => '192.168.1.1',
            ],
            // ...
        ];

        return Inertia::render('Admin/Journal', [
            'activites' => $activites,
        ]);
    }
}
