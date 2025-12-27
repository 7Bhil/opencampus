<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Debug : vérifiez ce qui se passe
        \Log::info('AdminOnly Middleware - User:', [
            'user' => $user ? $user->id : 'null',
            'account_type' => $user ? $user->account_type : 'null',
            'path' => $request->path(),
        ]);

        if (!$user) {
            \Log::warning('AdminOnly: No user found, redirecting to login');
            return redirect()->route('login');
        }

        $accountType = strtolower($user->account_type);

        if ($accountType !== 'admin') {
            \Log::warning('AdminOnly: User is not admin', [
                'user_id' => $user->id,
                'account_type' => $user->account_type,
                'expected' => 'admin'
            ]);

            // Redirigez vers le dashboard approprié
            if ($accountType === 'professeur') {
                return redirect()->route('professeur.dashboard');
            }

            // Par défaut : étudiant
            return redirect()->route('etudiant.dashboard');
        }

        \Log::info('AdminOnly: Access granted for admin user', ['user_id' => $user->id]);
        return $next($request);
    }
}