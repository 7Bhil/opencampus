<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Devoir;
use App\Models\Soumission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DevoirController extends Controller
{
    public function index()
{
    // Debug
    $allDevoirs = Devoir::all();
    \Log::info('Tous les devoirs:', $allDevoirs->map(function($d) {
        return [
            'id' => $d->id,
            'titre' => $d->titre,
            'est_actif' => $d->est_actif,
            'date_limite' => $d->date_limite,
            'professeur' => $d->professeur_id
        ];
    })->toArray());

    // Votre requête normale
    $devoirs = Devoir::where('est_actif', true)
        ->where('date_limite', '>', now())
        ->with(['professeur'])
        ->orderBy('date_limite')
        ->paginate(10);

    return Inertia::render('Etudiants/Devoirs/Index', [
        'devoirs' => $devoirs
    ]);
}

    public function show(Devoir $devoir)
    {
        // Vérifier que le devoir est ACTIF
        if (!$devoir->est_actif) {
            abort(404, 'Ce devoir n\'est pas disponible');
        }

        // Vérifier si la date limite est dépassée
        $estEnRetard = now()->gt($devoir->date_limite);

        // Vérifier si l'étudiant a déjà soumis
        $soumissionExistante = Soumission::where('devoir_id', $devoir->id)
            ->where('etudiant_id', auth()->id())
            ->first();

        return Inertia::render('Etudiants/Devoirs/Show', [
            'devoir' => $devoir->load(['professeur']),
            'soumissionExistante' => $soumissionExistante,
            'estEnRetard' => $estEnRetard,
            'joursRestants' => now()->diffInDays($devoir->date_limite, false),
        ]);
    }

    public function soumettre(Request $request, Devoir $devoir)
    {
        // Validation
        $request->validate([
            'fichier' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'commentaire' => 'nullable|string|max:500',
        ]);

        // Vérifications
        if (!$devoir->est_actif) {
            return back()->withErrors(['error' => 'Ce devoir n\'est plus disponible']);
        }

        if (now()->gt($devoir->date_limite)) {
            return back()->withErrors(['error' => 'La date limite est dépassée']);
        }

        // Vérifier si déjà soumis
        $soumissionExistante = Soumission::where('devoir_id', $devoir->id)
            ->where('etudiant_id', auth()->id())
            ->exists();

        if ($soumissionExistante) {
            return back()->withErrors(['error' => 'Vous avez déjà soumis ce devoir']);
        }

        // Enregistrer le fichier
        $fichierPath = $request->file('fichier')->store('soumissions', 'public');

        // Créer la soumission
        Soumission::create([
            'devoir_id' => $devoir->id,
            'etudiant_id' => auth()->id(),
            'fichier_path' => $fichierPath,
            'commentaire' => $request->commentaire,
            'statut' => 'en_attente',
            'date_soumission' => now(),
        ]);

        return redirect()->route('etudiant.devoirs.show', $devoir)
            ->with('success', 'Devoir soumis avec succès !');
    }
}
