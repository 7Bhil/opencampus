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

    // Page de création (pour tous les utilisateurs autorisés)
public function create()
{
    // Log pour debug
    \Log::info('=== COURS/CREATE ACCESSED ===');
    \Log::info('User ID: ' . auth()->id());
    \Log::info('User type: ' . auth()->user()->account_type);
    \Log::info('Can publish: ' . (auth()->user()->canPublishCours() ? 'yes' : 'no'));

    // Vérifier les permissions
    if (!auth()->user()->canPublishCours()) {
        if (auth()->user()->account_type === 'Etudiant') {
            return redirect()->route('etudiant.premium.index')
                ->with('error', 'Vous devez être abonné premium pour publier des cours.');
        }
        abort(403);
    }

    // Retourner la vue Inertia
    return Inertia::render('Cours/Create');
}

    // Enregistrer un nouveau cours
    public function store(Request $request)
{
    \Log::info('=== STORE COURS CALLED ===');
    \Log::info('Request data:', $request->all());
    \Log::info('Files:', $request->hasFile('fichier') ? ['has_file' => true] : ['has_file' => false]);

    if (!auth()->user()->canPublishCours()) {
        abort(403);
    }

    // Validation STRICTE avec messages d'erreur
    $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'matiere' => 'required|string|max:100',
        'categorie' => 'required|string|max:100',
        'description' => 'nullable|string',
        'est_payant' => 'required|boolean',
        'prix' => 'required_if:est_payant,true|nullable|numeric|min:0',
        'fichier' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,txt|max:10240',
        'est_public' => 'boolean',
    ], [
        'titre.required' => 'Le titre est obligatoire',
        'matiere.required' => 'La matière est obligatoire',
        'categorie.required' => 'La catégorie est obligatoire',
        'fichier.required' => 'Le fichier est obligatoire',
        'fichier.file' => 'Le fichier est invalide',
        'fichier.max' => 'Le fichier ne doit pas dépasser 10MB',
    ]);

    \Log::info('Validation passed:', $validated);

    // Gestion du fichier
    if ($request->hasFile('fichier')) {
        $path = $request->file('fichier')->store('cours', 'public');
        $validated['fichier_path'] = $path;
        \Log::info('File stored at:', ['path' => $path]);
    }

    // Ajouter l'ID de l'utilisateur
    $validated['user_id'] = auth()->id();

    // Si non payant, prix = null
    if (!$validated['est_payant']) {
        $validated['prix'] = null;
    }

    // Pour les étudiants : le cours n'est pas public immédiatement
    if (auth()->user()->account_type === 'Etudiant') {
        $validated['est_public'] = false;
        $validated['est_modere'] = false;
    }

    \Log::info('Final data to create:', $validated);

    // Créer le cours
    $cours = Cours::create($validated);

    \Log::info('Cours created:', ['id' => $cours->id]);

    $message = auth()->user()->account_type === 'Professeur'
        ? 'Cours publié avec succès !'
        : 'Cours soumis pour modération ! Il sera publié après vérification.';

    return redirect()->route('cours.index')
        ->with('success', $message);
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
}
