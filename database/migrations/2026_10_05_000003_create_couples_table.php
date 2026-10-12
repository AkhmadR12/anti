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
        Schema::create('couples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->unique()->constrained()->cascadeOnDelete();
            
            // Mempelai Pria
            $table->string('groom_name', 150);
            $table->string('groom_full_name', 200);
            $table->string('groom_nickname', 50)->nullable();
            $table->string('groom_father_name', 150)->nullable();
            $table->string('groom_mother_name', 150)->nullable();
            $table->string('groom_photo')->nullable();

            // Mempelai Wanita
            $table->string('bride_name', 150);
            $table->string('bride_full_name', 200);
            $table->string('bride_nickname', 50)->nullable();
            $table->string('bride_father_name', 150)->nullable();
            $table->string('bride_mother_name', 150)->nullable();
            $table->string('bride_photo')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('couples');
    }
};
