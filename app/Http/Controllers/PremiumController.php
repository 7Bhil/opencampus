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

        // Logique de souscription...
        $user->is_premium = true;
        $user->premium_until = now()->addMonths($request->duree);
        $user->save();

        return redirect()->route('etudiant.premium.index')
            ->with('success', 'Abonnement premium activé !');
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
        $user->premium_until = null;
        $user->save();

        return redirect()->route('etudiant.premium.index')
            ->with('success', 'Abonnement annulé.');
    }
}
