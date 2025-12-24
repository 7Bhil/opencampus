<?php
// app/Http/Controllers/Professeur/SoumissionController.php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Soumission;
use App\Models\Devoir;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class SoumissionController extends Controller
{
    public function corriger(Soumission $soumission, Request $request)
    {
        $devoir = $soumission->devoir;

        if ($devoir->professeur_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'note' => 'required|numeric|min:0|max:' . $devoir->points,
            'commentaire' => 'nullable|string|max:1000',
        ]);

        $validated['corrige_par'] = auth()->id();
        $validated['date_correction'] = now();
        $validated['statut'] = 'corrige';

        $soumission->update($validated);

        return redirect()->back()
            ->with('success', 'Soumission corrigée avec succès !');
    }

    public function download(Soumission $soumission)
    {
        $devoir = $soumission->devoir;

        if ($devoir->professeur_id !== auth()->id()) {
            abort(403);
        }

        return Storage::disk('public')->download($soumission->fichier_path);
    }

    public function destroy(Soumission $soumission)
    {
        $devoir = $soumission->devoir;

        if ($devoir->professeur_id !== auth()->id()) {
            abort(403);
        }

        Storage::disk('public')->delete($soumission->fichier_path);
        $soumission->delete();

        return redirect()->back()
            ->with('success', 'Soumission supprimée avec succès !');
    }
}
