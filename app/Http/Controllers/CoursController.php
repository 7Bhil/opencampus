<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CoursController extends Controller
{
    // Afficher tous les cours publics
    public function index()
{
    $cours = Cours::where('est_public', true)
        ->where('est_approuve', true) // Seulement les cours approuvés
        ->with('auteur')
        ->latest()
        ->get();

    return Inertia::render('Cours/Index', [
        'cours' => $cours,
        'canPublish' => auth()->check() ? auth()->user()->canPublishCours() : false,
    ]);
}

    // Afficher un cours spécifique
    public function show(Cours $cours)
    {
        // Vérifier si le cours est public ou si l'utilisateur est l'auteur
        if (!$cours->est_public && (!auth()->check() || $cours->user_id !== auth()->id())) {
            abort(403);
        }

        // Incrémenter le compteur de vues
        $cours->increment('nombre_vues');

        return Inertia::render('Cours/Show', [
            'cours' => $cours->load('auteur'),
            'isOwner' => auth()->check() && $cours->user_id === auth()->id(),
        ]);
    }

public function create()
{
    \Log::info('=== COURS/CREATE ACCESSED ===');
    \Log::info('User ID: ' . auth()->id());
    \Log::info('User type: ' . auth()->user()->account_type);
    \Log::info('Can publish: ' . (auth()->user()->canPublishCours() ? 'yes' : 'no'));

    // Vérifier les permissions
    if (!auth()->user()->canPublishCours()) {
        if (auth()->user()->account_type === 'Etudiant') {
            \Log::info('Redirection vers premium pour étudiant');
            return redirect()->route('etudiant.premium.index')
                ->with('error', 'Vous devez être abonné premium pour publier des cours.');
        }
        abort(403);
    }

    \Log::info('Affiche la page création');
    return Inertia::render('Cours/Create');
}

    // Enregistrer un nouveau cours
    public function store(Request $request)
{
    \Log::info('=== STORE COURS ===');

    // Vérifier les permissions
    if (!auth()->user()->canPublishCours()) {
        abort(403);
    }

    $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'matiere' => 'required|string|max:100',
        'categorie' => 'required|string|max:100',
        'description' => 'nullable|string',
        'est_payant' => 'required|boolean',
        'prix' => 'required_if:est_payant,true|nullable|numeric|min:0',
        'fichier' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,txt|max:10240',
    ]);

    // Gestion du fichier
    if ($request->hasFile('fichier')) {
        $path = $request->file('fichier')->store('cours', 'public');
        $validated['fichier_path'] = $path;
    }

    $validated['user_id'] = auth()->id();

    // Reset du prix si pas payant
    if (!$validated['est_payant']) {
        $validated['prix'] = null;
    }

    // LOGIQUE CRITIQUE : Statut selon le type d'utilisateur
    $user = auth()->user();
    $accountType = strtolower($user->account_type);

    if ($accountType === 'etudiant') {
        // Étudiant : cours en attente de modération
        $validated['est_public'] = false; // Pas public tant que pas approuvé
        $validated['est_modere'] = false;
        $validated['est_approuve'] = false;
        $message = 'Cours soumis pour modération !';

    } elseif ($accountType === 'professeur' || $accountType === 'admin') {
        // Professeur ou Admin : publié directement
        $validated['est_public'] = true;
        $validated['est_modere'] = true;
        $validated['est_approuve'] = true;
        $validated['approuve_le'] = now();
        $validated['approuve_par'] = auth()->id();
        $message = 'Cours publié avec succès !';

    } else {
        abort(403, 'Type d\'utilisateur non reconnu.');
    }

    // Valeurs par défaut
    $validated['statut'] = $validated['est_approuve'] ? 'approuve' : 'en_attente';
    $validated['nombre_vues'] = 0;
    $validated['nombre_achats'] = 0;

    $cours = Cours::create($validated);

    // Redirection différente selon le rôle
    if ($accountType === 'etudiant') {
        return redirect()->route('etudiant.cours.index')->with('success', $message);
    } else {
        return redirect()->route('cours.index')->with('success', $message);
    }
}

    // Télécharger le fichier du cours
    public function download(Cours $cours)
    {
        if (!$cours->est_public && (!auth()->check() || $cours->user_id !== auth()->id())) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($cours->fichier_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($cours->fichier_path);
    }
public function mesCours()
{
    $cours = Cours::where('user_id', auth()->id())
        ->withCount('achats')
        ->latest()
        ->get();

    return Inertia::render('Cours/MesCours', [
        'cours' => $cours,
    ]);
}
public function coursAModerer()
{
    // Cours soumis par les étudiants, non modérés
    $cours = Cours::where('est_modere', false)
        ->where('est_public', false)
        ->whereHas('auteur', function($q) {
            $q->where('account_type', 'Etudiant');
        })
        ->with('auteur')
        ->latest()
        ->get();

    return Inertia::render('Admin/CoursAModerer', [
        'cours' => $cours,
    ]);
}

public function approuver(Cours $cours)
{
    $cours->update([
        'est_public' => true,
        'est_modere' => true,
        'est_approuve' => true,
        'approuve_le' => now(),
        'approuve_par' => auth()->id(),
    ]);

    // Optionnel : Envoyer une notification à l'étudiant

    return back()->with('success', 'Cours approuvé et publié !');
}

public function rejeter(Cours $cours)
{
    $cours->update([
        'est_modere' => true,
        'est_approuve' => false,
    ]);

    // Optionnel : Envoyer une notification avec motif du rejet

    return back()->with('success', 'Cours rejeté.');
}
public function edit(Cours $cours)
{
    // Vérifie que l'utilisateur peut éditer (propriétaire ou admin)
    if (!auth()->check() || (auth()->id() !== $cours->user_id && auth()->user()->account_type !== 'Admin')) {
        abort(403);
    }

    return Inertia::render('Cours/Edit', [
        'cours' => $cours,
    ]);
}

public function update(Request $request, Cours $cours)
{
    // Vérifie les permissions
    if (!auth()->check() || (auth()->id() !== $cours->user_id && auth()->user()->account_type !== 'Admin')) {
        abort(403);
    }

    $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'matiere' => 'required|string|max:100',
        'categorie' => 'required|string|max:100',
        'description' => 'nullable|string',
        'est_payant' => 'required|boolean',
        'prix' => 'required_if:est_payant,true|nullable|numeric|min:0',
        'fichier' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,txt|max:10240',
    ]);

    // Si nouveau fichier
    if ($request->hasFile('fichier')) {
        // Supprime l'ancien fichier
        if ($cours->fichier_path && Storage::disk('public')->exists($cours->fichier_path)) {
            Storage::disk('public')->delete($cours->fichier_path);
        }

        $path = $request->file('fichier')->store('cours', 'public');
        $validated['fichier_path'] = $path;
    }

    $cours->update($validated);

    return redirect()->route('cours.show', $cours)
        ->with('success', 'Cours mis à jour avec succès !');
}
}
