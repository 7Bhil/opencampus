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
    Schema::table('cours', function (Blueprint $table) {
        $table->boolean('est_modere')->default(false)->after('est_public');
        $table->boolean('est_approuve')->default(false)->after('est_modere');
        $table->timestamp('approuve_le')->nullable()->after('est_approuve');
        $table->foreignId('approuve_par')->nullable()->constrained('users')->after('approuve_le');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            //
        });
    }
};
