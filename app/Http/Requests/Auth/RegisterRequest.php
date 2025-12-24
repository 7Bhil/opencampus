<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'account_type' => ['required', 'in:Etudiant,Professeur'],
            'filiere' => ['nullable', 'required_if:account_type,Etudiant', 'in:IG,GC,GE,GB,GRH,GTL'],
        ];
    }

    public function messages(): array
    {
        return [
            'filiere.required_if' => 'La filière est obligatoire pour les étudiants.',
            'filiere.in' => 'La filière sélectionnée n\'est pas valide.',
        ];
    }
}
