<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            // Ajoutez SEULEMENT les colonnes qui n'existent pas
            if (!Schema::hasColumn('cours', 'approuve_le')) {
                $table->timestamp('approuve_le')->nullable()->after('est_modere');
            }

            if (!Schema::hasColumn('cours', 'approuve_par')) {
                $table->foreignId('approuve_par')->nullable()->constrained('users')->after('approuve_le');
            }

            if (!Schema::hasColumn('cours', 'raison_rejet')) {
                $table->text('raison_rejet')->nullable()->after('approuve_par');
            }

            if (!Schema::hasColumn('cours', 'nombre_vues')) {
                $table->integer('nombre_vues')->default(0)->after('raison_rejet');
            }

            if (!Schema::hasColumn('cours', 'nombre_achats')) {
                $table->integer('nombre_achats')->default(0)->after('nombre_vues');
            }

            // Assurez-vous que est_approuve existe
            if (!Schema::hasColumn('cours', 'est_approuve')) {
                $table->boolean('est_approuve')->default(false)->after('est_modere');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            // Vous pouvez choisir de ne pas supprimer ces colonnes
            // ou les supprimer si vous voulez
        });
    }
};
