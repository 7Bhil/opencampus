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
        $etudiantId = auth()->id();

        // Vérifier que le devoir est actif
        if (!$devoir->est_actif) {
            abort(404, 'Ce devoir n\'est plus disponible');
        }

        // Vérifier si la date limite est dépassée
        $estEnRetard = now()->gt($devoir->date_limite);

        // Vérifier si l'étudiant a déjà soumis
        $soumissionExistante = Soumission::where('devoir_id', $devoir->id)
            ->where('etudiant_id', $etudiantId)
            ->first();

        // Charger le professeur
        $devoir->load('professeur');

        return Inertia::render('Etudiants/Devoirs/Show', [
            'devoir' => $devoir,
            'soumissionExistante' => $soumissionExistante,
            'estEnRetard' => $estEnRetard,
            'joursRestants' => now()->diffInDays($devoir->date_limite, false),
            'peutSoumettre' => !$estEnRetard && !$soumissionExistante,
        ]);
    }

    public function soumettre(Request $request, Devoir $devoir)
    {
        // Validation
        $request->validate([
            'fichier' => 'required|file|mimes:pdf,doc,docx,zip,rar|max:10240', // 10MB max
            'commentaire' => 'nullable|string|max:1000',
        ]);

        // Vérifications
        if (!$devoir->est_actif) {
            return back()->withErrors([
                'error' => 'Ce devoir n\'est plus disponible pour soumission.'
            ]);
        }

        if (now()->gt($devoir->date_limite)) {
            return back()->withErrors([
                'error' => 'La date limite de soumission est dépassée.'
            ]);
        }

        $etudiantId = auth()->id();

        // Vérifier si déjà soumis
        $soumissionExistante = Soumission::where('devoir_id', $devoir->id)
            ->where('etudiant_id', $etudiantId)
            ->exists();

        if ($soumissionExistante) {
            return back()->withErrors([
                'error' => 'Vous avez déjà soumis ce devoir.'
            ]);
        }

        try {
            // Enregistrer le fichier
            $fichierPath = $request->file('fichier')->store('soumissions/devoirs', 'public');

            // Créer la soumission
            Soumission::create([
                'devoir_id' => $devoir->id,
                'etudiant_id' => $etudiantId,
                'fichier_path' => $fichierPath,
                'commentaire' => $request->commentaire,
                'statut' => 'en_attente',
                'date_soumission' => now(),
            ]);

            return redirect()->route('etudiant.devoirs.show', $devoir)
                ->with('success', 'Devoir soumis avec succès !');

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors de l\'envoi du fichier.'
            ]);
        }
    }
public function download(Devoir $devoir)
    {
        if (!$devoir->est_actif) {
            abort(404);
        }

        $path = storage_path('app/public/' . $devoir->fichier_path);

        if (!file_exists($path)) {
            abort(404, 'Fichier non trouvé');
        }

        return response()->download($path, 'enonce-' . $devoir->titre . '.' . pathinfo($path, PATHINFO_EXTENSION));
    }
}

