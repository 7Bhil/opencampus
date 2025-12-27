<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cours;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AdminController extends Controller
{
    /**
     * Afficher le dashboard admin
     */
    public function dashboard()
    {
        // Vérifier que l'utilisateur est admin
        if (auth()->user()->account_type !== 'Admin') {
            abort(403);
        }

        $stats = [
            'totalUsers' => User::count(),
            'totalCours' => Cours::count(),
            'totalProfesseurs' => User::where('account_type', 'Professeur')->count(),
            'coursAModerer' => Cours::where('est_modere', false)
                ->where('est_public', false)
                ->whereHas('auteur', function($q) {
                    $q->where('account_type', 'Etudiant');
                })
                ->count(),
        ];

        // Activités récentes
        $recentActivities = [
            [
                'description' => 'Nouvel utilisateur inscrit',
                'time' => 'Il y a 2 heures',
                'datetime' => now()->subHours(2)->toISOString(),
            ],
            [
                'description' => 'Cours soumis par un étudiant',
                'time' => 'Il y a 4 heures',
                'datetime' => now()->subHours(4)->toISOString(),
            ],
            [
                'description' => 'Professeur a publié un nouveau cours',
                'time' => 'Il y a 1 jour',
                'datetime' => now()->subDay()->toISOString(),
            ],
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentActivities' => $recentActivities,
        ]);
    }

    /**
     * Afficher tous les utilisateurs
     */
    public function users()
    {
        if (auth()->user()->account_type !== 'Admin') {
            abort(403);
        }

        $users = User::withCount(['cours as cours_count'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Inertia::render('Admin/Users', [
            'users' => $users,
        ]);
    }

    /**
     * Afficher tous les cours
     */
    public function cours()
    {
        if (auth()->user()->account_type !== 'Admin') {
            abort(403);
        }

        $cours = Cours::with('auteur')
            ->withCount(['achats as achat_count'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Inertia::render('Admin/Cours', [
            'cours' => $cours,
        ]);
    }

    /**
     * Afficher les cours à modérer
     */
    public function coursAModerer()
    {
        if (auth()->user()->account_type !== 'Admin') {
            abort(403);
        }

        $cours = Cours::where('est_modere', false)
            ->where('est_public', false)
            ->whereHas('auteur', function($q) {
                $q->where('account_type', 'Etudiant');
            })
            ->with('auteur')
            ->orderBy('created_at', 'asc')
            ->get();

        return Inertia::render('Admin/CoursAModerer', [
            'cours' => $cours,
        ]);
    }

    /**
     * Approuver un cours
     */
    // Dans AdminController.php
public function rejeter(Cours $cours, Request $request)
{
    if (auth()->user()->account_type !== 'Admin') {
        abort(403);
    }

    $request->validate([
        'raison_rejet' => 'nullable|string|max:500'
    ]);

    $cours->update([
        'est_modere' => true,
        'est_approuve' => false,
        'raison_rejet' => $request->raison_rejet,
    ]);

    // Optionnel: Envoyer une notification à l'étudiant
    // $cours->auteur->notify(new CoursRejeteNotification($cours, $request->raison_rejet));

    return redirect()->route('admin.cours-a-moderer')
        ->with('success', 'Cours rejeté.' . ($request->raison_rejet ? ' Raison enregistrée.' : ''));
}

public function approuver(Cours $cours)
{
    if (auth()->user()->account_type !== 'Admin') {
        abort(403);
    }

    $cours->update([
        'est_public' => true,
        'est_modere' => true,
        'est_approuve' => true,
        'approuve_le' => now(),
        'approuve_par' => auth()->id(),
        'raison_rejet' => null, // Effacer la raison si elle existait
    ]);

    // Optionnel: Envoyer une notification à l'étudiant
    // $cours->auteur->notify(new CoursApprouveNotification($cours));

    return redirect()->route('admin.cours-a-moderer')
        ->with('success', 'Cours approuvé et publié !');
}

    /**
     * Modifier le statut d'un utilisateur
     */
    public function updateUserStatus(User $user)
    {
        if (auth()->user()->account_type !== 'Admin') {
            abort(403);
        }

        request()->validate([
            'account_type' => 'required|in:Etudiant,Professeur,Admin',
            'is_premium' => 'boolean',
            'balance' => 'numeric|min:0',
        ]);

        $user->update(request()->only(['account_type', 'is_premium', 'balance']));

        return back()->with('success', 'Utilisateur mis à jour.');
    }

    /**
     * Supprimer un cours
     */
    public function deleteCours(Cours $cours)
    {
        if (auth()->user()->account_type !== 'Admin') {
            abort(403);
        }

        $cours->delete();

        return back()->with('success', 'Cours supprimé.');
    }
// Dans AdminController
public function coursRejetes()
{
    if (auth()->user()->account_type !== 'Admin') {
        abort(403);
    }

    $cours = Cours::where('est_modere', true)
        ->where('est_approuve', false)
        ->whereNotNull('raison_rejet')
        ->with('auteur')
        ->orderBy('updated_at', 'desc')
        ->get();

    return Inertia::render('Admin/CoursRejetes', [
        'cours' => $cours,
    ]);
}
}
