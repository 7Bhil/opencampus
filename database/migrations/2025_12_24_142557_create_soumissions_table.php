<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soumissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('devoir_id')->constrained()->onDelete('cascade');
            $table->foreignId('etudiant_id')->constrained('users')->onDelete('cascade');
            $table->string('fichier_path');
            $table->decimal('note', 5, 2)->nullable();
            $table->text('commentaire')->nullable();
            $table->foreignId('corrige_par')->nullable()->constrained('users');
            $table->timestamp('date_correction')->nullable();
            $table->enum('statut', ['en_attente', 'corrige', 'rendu_en_retard'])->default('en_attente');
            $table->timestamps();

            $table->unique(['devoir_id', 'etudiant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soumissions');
    }
};
