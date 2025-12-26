<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use Inertia\Inertia;

class CoursController extends Controller
{
    public function index()
    {
        // Récupérer seulement les cours publics
        $cours = Cours::where('est_public', true)
            ->with(['professeur'])
            ->latest()
            ->paginate(12); // Pagination pour mieux gérer les données

        return Inertia::render('Etudiants/Cours/Index', [
            'cours' => $cours
        ]);
    }

    public function show(Cours $cours)
    {
        // Vérifier que le cours est public
        if (!$cours->est_public) {
            abort(404, 'Ce cours n\'est pas disponible');
        }

        // Incrémenter le compteur de vues
        $cours->increment('nombre_vues');

        return Inertia::render('Etudiants/Cours/Show', [
            'cours' => $cours->load(['professeur'])
        ]);
    }
}
