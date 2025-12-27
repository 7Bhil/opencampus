<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EtudiantOnly
{
   public function handle(Request $request, Closure $next): Response
{
    $user = $request->user();

    // Ajoute un délai pour la session (développement seulement)
    if (app()->environment('local') && !$user) {
        usleep(50000); // 50ms
        $user = $request->user(); // Réessaye
    }

    if (!$user) {
        return redirect()->route('login');
    }

    $accountType = strtolower($user->account_type);

    // Les admins peuvent aussi accéder aux pages étudiantes si besoin
    if ($accountType !== 'etudiant' && $accountType !== 'admin') {
        // Redirige vers le dashboard approprié
        if ($accountType === 'professeur') {
            return redirect()->route('professeur.dashboard');
        }
        return redirect()->route('dashboard.redirect');
    }

    return $next($request);
}
}
