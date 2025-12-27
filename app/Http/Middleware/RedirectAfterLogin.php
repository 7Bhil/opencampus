<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // AJOUTE CET IMPORT
use Symfony\Component\HttpFoundation\Response;

class RedirectAfterLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Après une connexion réussie
        if (Auth::check() && $request->route()->named('login')) { // Auth:: au lieu de Auth::
            $user = Auth::user();
            $accountType = trim(strtolower($user->account_type));

            if ($accountType === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            if ($accountType === 'professeur') {
                return redirect()->route('professeur.dashboard');
            }
            return redirect()->route('etudiant.dashboard');
        }

        return $response;
    }
}
