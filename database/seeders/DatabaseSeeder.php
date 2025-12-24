<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer un admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'account_type' => 'Professeur',
        ]);

        // Créer des utilisateurs de test
        User::factory()->create([
            'name' => 'Étudiant Test',
            'email' => 'etudiant@example.com',
            'account_type' => 'Etudiant',
            'filiere' => 'IG',
        ]);

        User::factory()->create([
            'name' => 'Professeur Test',
            'email' => 'professeur@example.com',
            'account_type' => 'Professeur',
        ]);

        // Créer d'autres utilisateurs
        User::factory(10)->create();
    }
}
