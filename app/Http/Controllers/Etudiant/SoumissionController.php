<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SoumissionController extends Controller
{
    /**
     * Enregistrer une soumission de devoir
     */
    public function store(Request $request, \App\Models\Devoir $devoir)
    {
        // 1. Vérifier si le devoir est actif et non expiré (facultatif, selon la politique)
        if (!$devoir->est_actif) {
            return back()->with('error', 'Ce devoir n\'est plus actif.');
        }

        // 2. Vérifier si l'étudiant a déjà soumis ce devoir
        $dejaSoumis = \App\Models\Soumission::where('devoir_id', $devoir->id)
            ->where('etudiant_id', auth()->id())
            ->exists();

        if ($dejaSoumis) {
            return back()->with('error', 'Vous avez déjà envoyé un fichier pour ce devoir.');
        }

        // 3. Validation
        $request->validate([
            'fichier' => 'required|file|mimes:pdf,doc,docx,zip,txt|max:10240', // 10MB max
        ]);

        try {
            // 4. Stockage du fichier
            $path = $request->file('fichier')->store('soumissions/' . $devoir->id, 'public');

            // 5. Création de la soumission
            \App\Models\Soumission::create([
                'devoir_id' => $devoir->id,
                'etudiant_id' => auth()->id(),
                'fichier_path' => $path,
                'statut' => now()->gt($devoir->date_limite) ? 'rendu_en_retard' : 'en_attente',
                'created_at' => now(),
            ]);

            return back()->with('success', 'Votre devoir a été transmis avec succès !');
        } catch (\Exception $e) {
            \Log::error('Erreur soumission devoir: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de l\'envoi du fichier.');
        }
    }
}
