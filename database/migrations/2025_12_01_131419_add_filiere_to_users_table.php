<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Vérifier si la colonne existe déjà avant de l'ajouter
            if (!Schema::hasColumn('users', 'filiere')) {
                $table->string('filiere')->nullable()->after('account_type');
            }

            // Si account_type n'existe pas non plus
            if (!Schema::hasColumn('users', 'account_type')) {
                $table->enum('account_type', ['Etudiant', 'Professeur'])
                      ->default('Etudiant')
                      ->after('password');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['filiere', 'account_type']);
        });
    }
};
