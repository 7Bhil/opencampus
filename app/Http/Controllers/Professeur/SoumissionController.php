<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Devoir;
use App\Models\Soumission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SoumissionController extends Controller
{
    /**
     * Télécharger le fichier de soumission
     */
    public function download(Devoir $devoir, Soumission $soumission)
    {
        // Vérifier que la soumission appartient bien au devoir
        if ($soumission->devoir_id !== $devoir->id) {
            abort(404);
        }

        // Vérifier les permissions
        if ($devoir->professeur_id !== auth()->id()) {
            abort(403);
        }

        // Vérifier si le fichier existe
        if (!Storage::exists($soumission->fichier_path)) {
            abort(404);
        }

        return Storage::download($soumission->fichier_path);
    }

    /**
     * Corriger une soumission
     */
    public function corriger(Request $request, Devoir $devoir, Soumission $soumission)
    {
        // Vérifier que la soumission appartient bien au devoir
        if ($soumission->devoir_id !== $devoir->id) {
            abort(404);
        }

        // Vérifier les permissions
        if ($devoir->professeur_id !== auth()->id()) {
            abort(403);
        }

        // Valider les données
        $validated = $request->validate([
            'note' => 'required|numeric|min:0|max:' . $devoir->points,
            'commentaire' => 'nullable|string|max:1000',
        ]);

        // Mettre à jour la soumission
        $soumission->update([
            'note' => $validated['note'],
            'commentaire' => $validated['commentaire'] ?? null,
            'corrige_par' => auth()->id(),
            'date_correction' => now(),
            'statut' => 'corrige',
        ]);

        return back()->with('success', 'Soumission corrigée avec succès.');
    }

    /**
     * Supprimer une soumission
     */
    public function destroy(Devoir $devoir, Soumission $soumission)
    {
        // Vérifier que la soumission appartient bien au devoir
        if ($soumission->devoir_id !== $devoir->id) {
            abort(404);
        }

        // Vérifier les permissions
        if ($devoir->professeur_id !== auth()->id()) {
            abort(403);
        }

        // Supprimer le fichier si nécessaire
        if (Storage::exists($soumission->fichier_path)) {
            Storage::delete($soumission->fichier_path);
        }

        $soumission->delete();

        return back()->with('success', 'Soumission supprimée avec succès.');
    }
}
