<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            if (!Schema::hasColumn('cours', 'est_modere')) {
                $table->boolean('est_modere')->default(false)->after('nombre_vues');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            if (Schema::hasColumn('cours', 'est_modere')) {
                $table->dropColumn('est_modere');
            }
        });
    }
};
