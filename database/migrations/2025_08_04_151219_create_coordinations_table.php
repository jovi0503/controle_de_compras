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
        Schema::create('coordinations', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignId('instituto_id')->constrained('institutos')->cascadeOnDelete();
            $table->string('name');
            $table->boolean('e_visivel')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coordinations');
    }
};
