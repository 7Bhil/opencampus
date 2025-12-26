<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Pour la table likes
        if (Schema::hasTable('likes') && !Schema::hasColumn('likes', 'cours_id')) {
            Schema::table('likes', function (Blueprint $table) {
                $table->foreignId('cours_id')->constrained()->onDelete('cascade');
                $table->unique(['user_id', 'cours_id']); // Optionnel: un like unique par utilisateur/cours
            });
        }

        // Pour la table telechargements
        if (Schema::hasTable('telechargements') && !Schema::hasColumn('telechargements', 'cours_id')) {
            Schema::table('telechargements', function (Blueprint $table) {
                $table->foreignId('cours_id')->constrained()->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        // Pour la table likes
        if (Schema::hasTable('likes') && Schema::hasColumn('likes', 'cours_id')) {
            Schema::table('likes', function (Blueprint $table) {
                $table->dropForeign(['cours_id']);
                $table->dropColumn('cours_id');
            });
        }

        // Pour la table telechargements
        if (Schema::hasTable('telechargements') && Schema::hasColumn('telechargements', 'cours_id')) {
            Schema::table('telechargements', function (Blueprint $table) {
                $table->dropForeign(['cours_id']);
                $table->dropColumn('cours_id');
            });
        }
    }
};
