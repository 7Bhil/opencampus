<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_add_est_premium_to_users_table.php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->boolean('est_premium')->default(false)->after('account_type');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('est_premium');
    });
}
};
