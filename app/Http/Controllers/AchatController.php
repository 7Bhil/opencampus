<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\AchatCours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AchatController extends Controller
{
    /**
     * Acheter un cours
     */
    public function acheter(Request $request, Cours $cours)
    {
        $user = auth()->user();

        // 1. Déjà acheté ?
        if (AchatCours::where('user_id', $user->id)->where('cours_id', $cours->id)->exists()) {
            return back()->with('info', 'Vous possédez déjà ce cours.');
        }

        // 2. Est-ce le propriétaire ?
        if ($user->id === $cours->user_id) {
            return back()->with('info', 'Vous êtes l\'auteur de ce cours.');
        }

        // 3. Vérifier si le cours est payant
        if (!$cours->est_payant) {
            // Créer un achat gratuit si nécessaire, ou juste autoriser l'accès
            AchatCours::create([
                'user_id' => $user->id,
                'cours_id' => $cours->id,
                'montant' => 0,
                'statut' => 'complete',
            ]);
            return back()->with('success', 'Cours ajouté à votre bibliothèque.');
        }

        // 4. Logique de paiement (Simplifiée : vérification du solde si existe)
        $prix = $cours->prix;
        
        // Si le système a un solde (balance)
        if (isset($user->balance) && $user->balance < $prix) {
             return back()->with('error', 'Solde insuffisant pour acheter ce cours.');
        }

        try {
            DB::transaction(function () use ($user, $cours, $prix) {
                // Déduire le solde si applicable
                if (isset($user->balance)) {
                    $user->decrement('balance', $prix);
                }

                // Créer l'enregistrement d'achat
                AchatCours::create([
                    'user_id' => $user->id,
                    'cours_id' => $cours->id,
                    'montant' => $prix,
                    'statut' => 'complete',
                    'transaction_id' => 'TRX-' . strtoupper(uniqid()),
                ]);

                // Optionnel : Créditer l'auteur
                $auteur = $cours->auteur;
                if ($auteur && isset($auteur->balance)) {
                    $auteur->increment('balance', $prix * 0.8); // 80% pour l'auteur
                }
                
                // Incrémenter le nombre d'achats du cours
                $cours->increment('nombre_achats');
            });

            return back()->with('success', 'Cours acheté avec succès ! Retrouvez-le dans votre espace personnel.');
        } catch (\Exception $e) {
            \Log::error('Erreur achat cours: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la transaction.');
        }
    }
}
