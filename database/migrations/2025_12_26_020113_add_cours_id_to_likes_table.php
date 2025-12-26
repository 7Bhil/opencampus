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
    Schema::table('likes', function (Blueprint $table) {
        // Si la colonne n'existe pas du tout
        $table->foreignId('cours_id')->constrained()->onDelete('cascade');

        // Ou si elle existe sous un autre nom, renommez-la
        // $table->renameColumn('cour_id', 'cours_id');
    });
}

public function down()
{
    Schema::table('likes', function (Blueprint $table) {
        $table->dropForeign(['cours_id']);
        $table->dropColumn('cours_id');
    });
}
};
