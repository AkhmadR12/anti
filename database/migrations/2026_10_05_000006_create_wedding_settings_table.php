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
        Schema::create('wedding_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->unique()->constrained()->cascadeOnDelete();
            $table->text('opening_text')->nullable();
            $table->text('closing_text')->nullable();
            $table->string('music_url')->nullable();
            $table->string('background_image')->nullable();
            $table->string('primary_color', 20)->default('#4F46E5');
            $table->string('secondary_color', 20)->default('#EC4899');
            $table->string('font_family', 50)->default('Playfair Display');
            $table->boolean('show_gift')->default(true);
            $table->boolean('show_rsvp')->default(true);
            $table->boolean('show_guestbook')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wedding_settings');
    }
};
