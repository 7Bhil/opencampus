<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanPublishCours
{
    // Dans CanPublishCours.php - Ajoute du logging :
public function handle(Request $request, Closure $next): Response
{
    \Log::info('CanPublish middleware - URL:', [$request->url()]);
    \Log::info('User:', [$request->user() ? $request->user()->id : 'null']);

    $user = $request->user();

    if (!$user || !$user->canPublishCours()) {
        \Log::warning('Access denied for user:', [
            'user_id' => $user ? $user->id : null,
            'account_type' => $user ? $user->account_type : null
        ]);

        if ($user && $user->account_type === 'Etudiant') {
            return redirect()->route('etudiant.premium.index')
                ->with('error', 'Vous devez être abonné premium pour publier des cours.');
        }
        abort(403, 'Vous n\'avez pas la permission de publier des cours.');
    }

    return $next($request);
}
}
