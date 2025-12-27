<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class MarketplaceCoursController extends Controller
{
    public function create()
{
    $user = auth()->user();

    if (!$user->canPublishCours()) {
        if ($user->account_type === 'Etudiant') {
            return redirect()->route('etudiant.premium.index')
                ->with('error', 'Vous devez être abonné premium pour publier des cours.');
        }
        abort(403);
    }

    return Inertia::render('Cours/Create', [
        'userType' => $user->account_type,
        'isPremium' => $user->is_premium,
    ]);
}
}
