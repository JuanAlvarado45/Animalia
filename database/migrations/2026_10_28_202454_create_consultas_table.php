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
    Schema::create('consultas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('mascota_id')->constrained()->onDelete('cascade');
        $table->foreignId('veterinaria_id')->nullable()->constrained()->nullOnDelete();

        $table->string('motivo');
        $table->text('diagnostico')->nullable();
        $table->text('tratamiento')->nullable();
        $table->date('fecha_consulta');

        $table->timestamps();

        $table->index(['mascota_id', 'fecha_consulta']);
    });
}

public function down(): void
{
    Schema::dropIfExists('consultas');
}
};
