<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_add_statut_to_cours_table.php
public function up(): void
{
    Schema::table('cours', function (Blueprint $table) {
        $table->string('statut')->default('en_attente')->after('nombre_achats');
        // ou si vous voulez un enum :
        // $table->enum('statut', ['en_attente', 'approuve', 'rejete'])->default('en_attente');
    });
}

public function down(): void
{
    Schema::table('cours', function (Blueprint $table) {
        $table->dropColumn('statut');
    });
}
};
