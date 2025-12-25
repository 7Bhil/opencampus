<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Soumission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SoumissionController extends Controller
{
    public function download(Soumission $soumission)
    {
        $this->authorize('view', $soumission->devoir);

        if (!Storage::disk('public')->exists($soumission->fichier_path)) {
            abort(404, 'Fichier non trouvé');
        }

        return Storage::disk('public')->download($soumission->fichier_path);
    }

    public function corriger(Request $request, Soumission $soumission)
    {
        $this->authorize('update', $soumission->devoir);

        $validated = $request->validate([
            'note' => 'required|numeric|min:0|max:' . $soumission->devoir->points,
            'commentaire' => 'nullable|string|max:1000',
        ]);

        $soumission->update([
            'note' => $validated['note'],
            'commentaire' => $validated['commentaire'],
            'statut' => 'corrige',
            'correcteur_id' => auth()->id(),
        ]);

        return back()->with('success', 'Soumission corrigée avec succès.');
    }

    public function destroy(Soumission $soumission)
    {
        $this->authorize('delete', $soumission->devoir);

        if ($soumission->fichier_path) {
            Storage::disk('public')->delete($soumission->fichier_path);
        }

        $soumission->delete();

        return back()->with('success', 'Soumission supprimée avec succès.');
    }
}
