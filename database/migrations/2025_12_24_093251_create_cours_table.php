<?php
// database/migrations/xxxx_create_cours_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professeur_id')->constrained('users')->onDelete('cascade');
            $table->string('titre');
            $table->string('matiere');
            $table->text('description')->nullable();
            $table->string('fichier_path');
            $table->boolean('est_payant')->default(false);
            $table->decimal('prix', 8, 2)->nullable();
            $table->boolean('est_public')->default(true);
            $table->string('categorie');
            $table->integer('nombre_vues')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cours');
    }
};
