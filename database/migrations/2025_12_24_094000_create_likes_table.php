<?php
// database/migrations/xxxx_create_likes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
// database/migrations/xxxx_create_likes_table.php
public function up()
{
    Schema::create('likes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('cours_id')->constrained()->onDelete('cascade');
        $table->timestamps();

        $table->unique(['user_id', 'cours_id']); // Un utilisateur ne peut liker qu'une fois par cours
    });
}

    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
