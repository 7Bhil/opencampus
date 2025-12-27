<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfesseurOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !in_array(strtolower($user->account_type), ['professeur', 'admin'])) {
            abort(403, 'Accès réservé aux professeurs et administrateurs.');
        }

        return $next($request);
    }
}
