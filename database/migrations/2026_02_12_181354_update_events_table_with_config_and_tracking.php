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
        Schema::table('events', function (Blueprint $table) {
            // Add config foreign keys
            $table->unsignedBigInteger('location_id')->nullable()->after('title');
            $table->unsignedBigInteger('floor_id')->nullable()->after('location_id');

            // Add user tracking
            $table->unsignedBigInteger('created_by')->nullable()->after('deleted_at');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');

            // Foreign key constraints
            $table->foreign('location_id')->references('id')->on('configs')->onDelete('restrict');
            $table->foreign('floor_id')->references('id')->on('configs')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            // Drop old string columns
            $table->dropColumn(['location', 'floor']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Drop foreign keys
            $table->dropForeign(['location_id']);
            $table->dropForeign(['floor_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);

            // Drop columns
            $table->dropColumn(['location_id', 'floor_id', 'created_by', 'updated_by']);

            // Restore old columns
            $table->string('location')->after('title');
            $table->string('floor')->nullable()->after('location');
        });
    }
};
