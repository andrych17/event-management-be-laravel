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
            // Add new datetime columns
            $table->dateTime('event_start_datetime')->nullable()->after('floor_id');
            $table->dateTime('event_end_datetime')->nullable()->after('event_start_datetime');

            // Keep old columns for backward compatibility during transition
            // They can be removed in a future migration after data migration
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['event_start_datetime', 'event_end_datetime']);
        });
    }
};
