<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('achat_cours', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('cours_id')->constrained()->onDelete('cascade');
        $table->decimal('montant', 10, 2);
        $table->string('transaction_id')->nullable();
        $table->enum('statut', ['en_attente', 'complete', 'echoue'])->default('complete');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achat_cours');
    }
};
