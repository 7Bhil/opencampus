<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Redirection après login
     */
    protected function authenticated(Request $request, $user)
    {
        // Debug
        \Log::info('=== LOGIN REDIRECTION ===');
        \Log::info('User ID: ' . $user->id);
        \Log::info('Account Type: ' . $user->account_type);

        $accountType = trim(strtolower($user->account_type));

        if ($accountType === 'admin') {
            \Log::info('ADMIN -> /admin/dashboard');
            return redirect()->route('admin.dashboard');
        }

        if ($accountType === 'professeur') {
            \Log::info('PROFESSEUR -> /professeur/dashboard');
            return redirect()->route('professeur.dashboard');
        }

        \Log::info('ETUDIANT -> /etudiant/dashboard');
        return redirect()->route('etudiant.dashboard');
    }
}
