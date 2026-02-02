<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Copy data from est_premium to is_premium if est_premium is true
        if (Schema::hasColumn('users', 'est_premium') && Schema::hasColumn('users', 'is_premium')) {
            DB::table('users')
                ->where('est_premium', true)
                ->update(['is_premium' => true]);
        }

        // Drop the redundant column
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'est_premium')) {
                $table->dropColumn('est_premium');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('est_premium')->default(false)->after('account_type');
        });

        // Copy back if needed
        DB::table('users')
            ->where('is_premium', true)
            ->update(['est_premium' => true]);
    }
};
