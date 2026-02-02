<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PremiumController extends Controller
{
    /**
     * Afficher la page premium
     */
    public function index()
    {
        $user = Auth::user();

        return Inertia::render('Etudiants/Premium', [  // NOTE: 'Premium' pas 'Premium/Index'
            'user' => [
                'is_premium' => $user->is_premium,
                'premium_until' => $user->premium_until,
                'balance' => $user->balance,
            ]
        ]);
    }

    /**
     * Souscrire au premium
     */
    public function souscrire(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'duree' => 'required|in:1,3,6,12',
        ]);

        $prices = [
            1 => 1000,
            3 => 2500,
            6 => 4500,
            12 => 8000,
        ];

        $prix = $prices[$request->duree];

        if ($user->balance < $prix) {
            return redirect()->route('etudiant.premium.index')
                ->with('error', 'Solde insuffisant (Prix: ' . $prix . ' f). Veuillez recharger votre compte.');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($user, $prix, $request) {
                // Déduire le solde
                $user->decrement('balance', $prix);

                // Activer le premium
                $dejaPremium = $user->is_premium && $user->premium_until && $user->premium_until->gt(now());
                $startDate = $dejaPremium ? $user->premium_until : now();

                $user->is_premium = true;
                $user->premium_until = $startDate->addMonths($request->duree);
                $user->save();
            });

            return redirect()->route('etudiant.premium.index')
                ->with('success', 'Abonnement premium activé avec succès !');
        } catch (\Exception $e) {
            \Log::error('Erreur souscription premium: ' . $e->getMessage());
            return redirect()->route('etudiant.premium.index')
                ->with('error', 'Une erreur est survenue lors de la souscription.');
        }
    }

    /**
     * Ajouter des fonds (Simulé pour le test)
     */
    public function deposer(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric|min:100|max:100000',
        ]);

        $user = Auth::user();
        $user->increment('balance', $request->montant);

        return back()->with('success', $request->montant . ' f ajoutés à votre solde !');
    }

    /**
     * Annuler l'abonnement
     */
    public function annuler(Request $request)
    {
        $user = Auth::user();

        if (!$user->is_premium) {
            return redirect()->route('etudiant.premium.index')
                ->with('error', 'Vous n\'êtes pas abonné premium.');
        }

        $user->is_premium = false;
        // Optionnel : On garde premium_until mais on marque comme "ne pas renouveler"
        // Ici on simplifie en coupant tout
        $user->premium_until = null;
        $user->save();

        return redirect()->route('etudiant.premium.index')
            ->with('success', 'Abonnement annulé.');
    }
}
