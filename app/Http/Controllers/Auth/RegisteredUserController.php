<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validation avec les nouveaux champs
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'account_type' => ['required', 'in:Etudiant,Professeur'],
            'filiere' => ['nullable', 'required_if:account_type,Etudiant', 'in:IG,GC,GE,GB,GRH,GTL'],
        ], [
            'filiere.required_if' => 'La filière est obligatoire pour les étudiants.',
            'filiere.in' => 'La filière sélectionnée n\'est pas valide.',
        ]);

        // Création de l'utilisateur avec les nouveaux champs
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'account_type' => $request->account_type,
        ];

        // Ajouter la filière seulement si c'est un étudiant
        if ($request->account_type === 'Etudiant') {
            $userData['filiere'] = $request->filiere;
        }

        $user = User::create($userData);

        event(new Registered($user));

        Auth::login($user);
if ($user->account_type === 'Admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->account_type === 'Professeur') {
        return redirect()->route('professeur.dashboard');
    }

        return redirect()->intended(route('etudiant.dashboard', absolute: false));
    }
}
