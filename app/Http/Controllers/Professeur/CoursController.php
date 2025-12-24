<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage; // AJOUTE CETTE LIGNE
class CoursController extends Controller
{
    public function index(Request $request)
    {
        // MODIFIÉ : sans withCount pour les relations qui n'existent pas
        $cours = Cours::where('professeur_id', auth()->id())
            ->latest()
            ->paginate(10);

        // Statistiques basiques
        $userCours = Cours::where('professeur_id', auth()->id())->get();

        $stats = [
            'total_cours' => $userCours->count(),
            'total_telechargements' => 0, // Temporairement 0
            'total_likes' => 0, // Temporairement 0
            'cours_payants' => $userCours->where('est_payant', true)->count(),
        ];

        // Catégories uniques
        $categories = Cours::where('professeur_id', auth()->id())
            ->pluck('categorie')
            ->unique()
            ->values();

        return Inertia::render('Professeurs/Cours', [
            'cours' => $cours,
            'stats' => $stats,
            'categories' => $categories,
        ]);
    }

    public function show(Cours $cours)
    {
        if ($cours->professeur_id !== auth()->id()) {
            abort(403);
        }

        // MODIFIÉ : sans loadCount
        return Inertia::render('Professeurs/CoursShow', [
            'cours' => $cours,
        ]);
    }
// ... après les méthodes existantes

public function create()
{
    return Inertia::render('Professeurs/CoursCreate');
}

public function store(Request $request)
{
    $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'matiere' => 'required|string|max:100',
        'categorie' => 'required|string|max:100',
        'description' => 'nullable|string',
        'est_payant' => 'required|boolean',
        'prix' => 'required_if:est_payant,true|nullable|numeric|min:0',
        'fichier' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,txt|max:10240', // 10MB
        'est_public' => 'boolean',
    ]);

    // Gestion du fichier
    if ($request->hasFile('fichier')) {
        $path = $request->file('fichier')->store('cours', 'public');
        $validated['fichier_path'] = $path;
    }

    // Ajouter l'ID du professeur
    $validated['professeur_id'] = auth()->id();

    // Si non payant, prix = null
    if (!$validated['est_payant']) {
        $validated['prix'] = null;
    }

    // Créer le cours
    Cours::create($validated);

    return redirect()->route('professeur.cours.index')
        ->with('success', 'Cours publié avec succès !');
}

public function edit(Cours $cours)
{
    // Vérifier que le cours appartient au professeur
    if ($cours->professeur_id !== auth()->id()) {
        abort(403);
    }

    return Inertia::render('Professeurs/CoursEdit', [
        'cours' => $cours,
    ]);
}

public function update(Request $request, Cours $cours)
{
    // Vérifier que le cours appartient au professeur
    if ($cours->professeur_id !== auth()->id()) {
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
        'est_public' => 'boolean',
    ]);

    // Gestion du fichier (si nouveau fichier)
    if ($request->hasFile('fichier')) {
        // Supprimer l'ancien fichier si nécessaire
        Storage::disk('public')->delete($cours->fichier_path);

        $path = $request->file('fichier')->store('cours', 'public');
        $validated['fichier_path'] = $path;
    }

    // Si non payant, prix = null
    if (!$validated['est_payant']) {
        $validated['prix'] = null;
    }

    $cours->update($validated);

    return redirect()->route('professeur.cours.index')
        ->with('success', 'Cours mis à jour avec succès !');
}

public function destroy(Cours $cours)
{
    // Vérifier que le cours appartient au professeur
    if ($cours->professeur_id !== auth()->id()) {
        abort(403);
    }

    // Supprimer le fichier
    Storage::disk('public')->delete($cours->fichier_path);

    $cours->delete();

    return redirect()->route('professeur.cours.index')
        ->with('success', 'Cours supprimé avec succès !');
}
}
