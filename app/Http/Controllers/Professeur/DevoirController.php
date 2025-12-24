<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Devoir;
use App\Models\Soumission;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class DevoirController extends Controller
{
    /**
     * Affiche la liste des devoirs du professeur avec statistiques
     */
    public function index()
    {
        $professeurId = auth()->id();

        // Récupération optimisée des devoirs avec pagination
        $devoirs = Devoir::where('professeur_id', $professeurId)
            ->withCount([
                'soumissions',
                'soumissions as soumissions_corrigees_count' => function ($query) {
                    $query->where('statut', 'corrige');
                }
            ])
            ->latest()
            ->paginate(10);

        // Récupération des IDs des devoirs du professeur (une seule requête)
        $devoirIds = Devoir::where('professeur_id', $professeurId)->pluck('id');

        // Statistiques des soumissions en une seule requête
        $soumissionsStats = Soumission::whereIn('devoir_id', $devoirIds)
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN statut = "en_attente" THEN 1 ELSE 0 END) as en_attente
            ')
            ->first();

        // Calcul des statistiques complètes
        $stats = [
            'total_devoirs' => $devoirIds->count(),
            'total_soumissions' => $soumissionsStats->total ?? 0,
            'soumissions_en_attente' => $soumissionsStats->en_attente ?? 0,
            'devoirs_actifs' => Devoir::where('professeur_id', $professeurId)
                ->where('est_actif', true)
                ->where('date_limite', '>', now())
                ->count(),
        ];

        // Récupération des matières uniques (directement depuis la base de données)
        $matieres = Devoir::where('professeur_id', $professeurId)
            ->whereNotNull('matiere')
            ->distinct()
            ->orderBy('matiere')
            ->pluck('matiere');

        return Inertia::render('Professeurs/Devoirs/Index', [
            'devoirs' => $devoirs,
            'stats' => $stats,
            'matieres' => $matieres,
        ]);
    }

    /**
     * Affiche le formulaire de création d'un devoir
     */
    public function create()
    {
        return Inertia::render('Professeurs/Devoirs/Create');
    }

    /**
     * Enregistre un nouveau devoir
     */
    public function store(Request $request)
    {

    $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'matiere' => 'required|string|max:100',
        'description' => 'nullable|string',
        'fichier' => 'required|file|mimes:pdf,doc,docx,txt|max:5120',
        'date_limite' => 'required|date|after:now',
        'points' => 'required|integer|min:1|max:20',  // Valide 'points'
        'est_actif' => 'boolean',
    ]);


        $validated['professeur_id'] = auth()->id();

        // Gestion du fichier joint
        if ($request->hasFile('fichier')) {
            $validated['fichier_path'] = $request->file('fichier')->store('devoirs', 'public');
        }

        $devoir = Devoir::create($validated);

        return redirect()
            ->route('professeur.devoirs.index')
            ->with('success', 'Devoir créé avec succès.');
    }

    /**
     * Affiche les détails d'un devoir
     */
    public function show(Devoir $devoir)
    {
        $this->authorize('view', $devoir);

        $devoir->load([
            'soumissions' => function ($query) {
                $query->with('eleve:id,nom,prenom')
                    ->latest();
            }
        ]);

        return Inertia::render('Professeurs/Devoirs/Show', [
            'devoir' => $devoir,
        ]);
    }

    /**
     * Affiche le formulaire d'édition d'un devoir
     */
    public function edit(Devoir $devoir)
    {
        $this->authorize('update', $devoir);

        return Inertia::render('Professeurs/Devoirs/Edit', [
            'devoir' => $devoir,
        ]);
    }

    /**
     * Met à jour un devoir
     */
    public function update(Request $request, Devoir $devoir)
    {
        $this->authorize('update', $devoir);

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'matiere' => 'required|string|max:100',
            'date_limite' => 'required|date',
            'note_maximale' => 'required|numeric|min:0|max:100',
            'est_actif' => 'boolean',
            'fichier' => 'nullable|file|max:10240|mimes:pdf,doc,docx,txt,zip',
        ]);

        // Gestion du nouveau fichier
        if ($request->hasFile('fichier')) {
            // Suppression de l'ancien fichier
            if ($devoir->fichier_path) {
                Storage::disk('public')->delete($devoir->fichier_path);
            }
            $validated['fichier_path'] = $request->file('fichier')->store('devoirs', 'public');
        }

        $devoir->update($validated);

        return redirect()
            ->route('professeur.devoirs.show', $devoir)
            ->with('success', 'Devoir mis à jour avec succès.');
    }

    /**
     * Supprime un devoir
     */
    public function destroy(Devoir $devoir)
    {
        $this->authorize('delete', $devoir);

        // Suppression du fichier associé
        if ($devoir->fichier_path) {
            Storage::disk('public')->delete($devoir->fichier_path);
        }

        // Suppression des fichiers de soumissions
        foreach ($devoir->soumissions as $soumission) {
            if ($soumission->fichier_path) {
                Storage::disk('public')->delete($soumission->fichier_path);
            }
        }

        $devoir->delete();

        return redirect()
            ->route('professeur.devoirs.index')
            ->with('success', 'Devoir supprimé avec succès.');
    }

    /**
     * Bascule le statut actif/inactif d'un devoir
     */
    public function toggleStatus(Devoir $devoir)
    {
        $this->authorize('update', $devoir);

        $devoir->update([
            'est_actif' => !$devoir->est_actif
        ]);

        return back()->with('success', 'Statut du devoir modifié.');
    }
}
