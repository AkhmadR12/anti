<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wedding_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained()->cascadeOnDelete();
            $table->string('type', 50)->default('akad');
            $table->string('title', 150);
            $table->date('event_date')->index();
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->string('venue', 200);
            $table->text('address');
            $table->text('maps_url')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['wedding_id', 'event_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wedding_events');
    }
};
