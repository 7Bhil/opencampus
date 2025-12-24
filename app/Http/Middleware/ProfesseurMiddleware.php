<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfesseurMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est authentifié
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Vérifier si l'utilisateur est un professeur ou admin
        $user = auth()->user();
        if ($user->account_type !== 'Professeur' ) {
            abort(403, 'Accès réservé aux professeurs et administrateurs.');
        }

        return $next($request);
    }
}
