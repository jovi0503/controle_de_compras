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
       Schema::create('compras', function (Blueprint $table) {
        $table->id();
        $table->uuid()->unique();
        $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
        $table->foreignId('instituto_id')->constrained('institutos');
        $table->foreignId('coordination_id')->constrained('coordinations');
        $table->foreignId('macro_id')->constrained('macros');
        $table->string('name'); 
        $table->year('exercicio');
        $table->date('data');
        $table->string('status')->default('pendente');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
        $table->dropForeign(['efetivado_por_id']);
        $table->dropColumn('efetivado_por_id');
    }
};
