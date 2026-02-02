<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer l'admin principal
        User::firstOrCreate(
            ['email' => '7bhilal.chitou7@gmail.com'],
            [
                'name' => 'Admin Principal',
                'password' => bcrypt('Bh7777777'),
                'account_type' => 'Admin',
            ]
        );

        // Créer des utilisateurs de test
        User::firstOrCreate(
            ['email' => 'etudiant@example.com'],
            [
                'name' => 'Étudiant Test',
                'account_type' => 'Etudiant',
                'filiere' => 'IG',
                'password' => bcrypt('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'professeur@example.com'],
            [
                'name' => 'Professeur Test',
                'account_type' => 'Professeur',
                'password' => bcrypt('password'),
            ]
        );

        // Créer d'autres utilisateurs
        User::factory(10)->create();
    }
}
