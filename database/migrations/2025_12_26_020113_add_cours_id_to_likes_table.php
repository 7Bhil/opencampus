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
        // Redundant migration, cours_id is already created in the create_likes_table migration.
    }

public function down()
{
    Schema::table('likes', function (Blueprint $table) {
        $table->dropForeign(['cours_id']);
        $table->dropColumn('cours_id');
    });
}
};
