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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('location');
            $table->string('floor')->nullable();
            $table->date('event_date');
            $table->time('event_time');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('event_date');
            $table->index(['event_date', 'event_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
