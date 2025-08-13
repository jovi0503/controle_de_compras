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
       Schema::create('item_compras', function (Blueprint $table) {
        $table->id();
        $table->uuid()->unique();
        $table->foreignId('compra_id')->constrained('compras')->cascadeOnDelete();
        $table->foreignId('material_id')->constrained('materials');
        $table->integer('quantidade');
        $table->text('dec'); 
        $table->decimal('valor_unitario', 10, 2)->nullable();
        $table->decimal('valor_total', 10, 2)->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_compras');
    }
};
