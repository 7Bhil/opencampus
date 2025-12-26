<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Like;
use App\Models\Telechargement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CoursController extends Controller
{
    /**
     * Affiche la liste de tous les cours publics
     */
    public function index(Request $request)
    {
        // Récupérer les filtres
        $filtres = $request->only(['categorie', 'matiere', 'type', 'search']);

        // Construire la requête
        $query = Cours::where('est_public', true)
            ->with(['professeur'])
            ->withCount(['likes', 'telechargements'])
            ->latest();

        // Appliquer les filtres
        if (!empty($filtres['categorie'])) {
            $query->where('categorie', $filtres['categorie']);
        }

        if (!empty($filtres['matiere'])) {
            $query->where('matiere', 'like', '%' . $filtres['matiere'] . '%');
        }

        if (!empty($filtres['type'])) {
            if ($filtres['type'] === 'gratuit') {
                $query->where('est_payant', false);
            } elseif ($filtres['type'] === 'payant') {
                $query->where('est_payant', true);
            }
        }

        if (!empty($filtres['search'])) {
            $query->where(function($q) use ($filtres) {
                $q->where('titre', 'like', '%' . $filtres['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filtres['search'] . '%')
                  ->orWhere('matiere', 'like', '%' . $filtres['search'] . '%');
            });
        }

        // Pagination
        $cours = $query->paginate(12)->withQueryString();

        // Statistiques pour les filtres
        $statistiques = [
            'total_cours' => Cours::where('est_public', true)->count(),
            'cours_gratuits' => Cours::where('est_public', true)->where('est_payant', false)->count(),
            'cours_payants' => Cours::where('est_public', true)->where('est_payant', true)->count(),
            'categories' => Cours::where('est_public', true)->whereNotNull('categorie')->distinct()->pluck('categorie'),
            'matieres' => Cours::where('est_public', true)->whereNotNull('matiere')->distinct()->pluck('matiere'),
        ];

        return Inertia::render('Etudiants/Cours/Index', [
            'cours' => $cours,
            'filtres' => $filtres,
            'statistiques' => $statistiques,
        ]);
    }

    /**
     * Affiche les détails d'un cours
     */
    public function show(Cours $cours)
    {
        // Vérifier que le cours est public
        if (!$cours->est_public) {
            abort(404, 'Ce cours n\'est pas disponible');
        }

        // Incrémenter le compteur de vues
        $cours->increment('nombre_vues');

        // Charger les relations
        $cours->load(['professeur']);
        $cours->loadCount(['likes', 'telechargements']);

        // Vérifier si l'étudiant a déjà liké ce cours
        $userLiked = false;
        if (auth()->check()) {
            $userLiked = Like::where('cours_id', $cours->id)
                ->where('user_id', auth()->id())
                ->exists();
        }

        // Vérifier si l'étudiant a déjà téléchargé ce cours
        $userDownloaded = false;
        if (auth()->check()) {
            $userDownloaded = Telechargement::where('cours_id', $cours->id)
                ->where('user_id', auth()->id())
                ->exists();
        }

        // Cours similaires (même catégorie ou matière)
        $coursSimilaires = Cours::where('est_public', true)
            ->where('id', '!=', $cours->id)
            ->where(function($query) use ($cours) {
                $query->where('categorie', $cours->categorie)
                      ->orWhere('matiere', $cours->matiere);
            })
            ->with(['professeur'])
            ->withCount(['likes', 'telechargements'])
            ->take(4)
            ->get();

        return Inertia::render('Etudiants/Cours/Show', [
            'cours' => $cours,
            'userLiked' => $userLiked,
            'userDownloaded' => $userDownloaded,
            'coursSimilaires' => $coursSimilaires,
        ]);
    }

    /**
     * Télécharge un cours
     */
    public function download(Cours $cours)
    {
        // Vérifier que le cours est public
        if (!$cours->est_public) {
            abort(404);
        }

        // Vérifier les droits d'accès pour les cours payants
        if ($cours->est_payant) {
            // TODO: Vérifier si l'étudiant a acheté ce cours
            // Pour l'instant, on autorise tout le monde
        }

        // Enregistrer le téléchargement
        if (auth()->check()) {
            Telechargement::firstOrCreate([
                'cours_id' => $cours->id,
                'user_id' => auth()->id(),
            ]);
        }

        // Incrémenter le compteur de téléchargements
        $cours->increment('telechargements_count');

        // Retourner le fichier
        $path = storage_path('app/public/' . $cours->fichier_path);

        if (!file_exists($path)) {
            abort(404, 'Fichier non trouvé');
        }

        return response()->download($path, $cours->titre . '.' . pathinfo($path, PATHINFO_EXTENSION));
    }

    /**
     * Like/Unlike un cours
     */
    public function toggleLike(Cours $cours)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Connectez-vous pour liker'], 401);
        }

        $like = Like::where('cours_id', $cours->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            Like::create([
                'cours_id' => $cours->id,
                'user_id' => auth()->id(),
            ]);
            $liked = true;
        }

        // Recharger le compteur
        $cours->loadCount('likes');

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $cours->likes_count,
        ]);
    }
}
