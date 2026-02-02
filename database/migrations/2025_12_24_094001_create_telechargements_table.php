<?php
// database/migrations/xxxx_create_telechargements_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
// database/migrations/xxxx_create_telechargements_table.php
public function up()
{
    Schema::create('telechargements', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('cours_id')->constrained()->onDelete('cascade');
        $table->timestamps();

        $table->index(['user_id', 'cours_id']);
    });
}

    public function down(): void
    {
        Schema::dropIfExists('telechargements');
    }
};
