<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Disable transaction for this migration
     */
    public $withinTransaction = false;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('configs', function (Blueprint $table) {
            // Drop unique constraint first
            $table->dropUnique(['group_code', 'key']);

            // Drop key column
            $table->dropColumn('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configs', function (Blueprint $table) {
            // Add key column back
            $table->string('key')->after('parent_id')->index();

            // Add unique constraint back
            $table->unique(['group_code', 'key']);
        });
    }
};
