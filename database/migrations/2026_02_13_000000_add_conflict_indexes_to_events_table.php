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
            // Composite index for conflict detection queries on location, floor, and start datetime
            $table->index(['location_id', 'floor_id', 'event_start_datetime'], 'idx_location_floor_start');

            // Composite index for conflict detection queries on location, floor, and end datetime
            $table->index(['location_id', 'floor_id', 'event_end_datetime'], 'idx_location_floor_end');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('idx_location_floor_start');
            $table->dropIndex('idx_location_floor_end');
        });
    }
};
