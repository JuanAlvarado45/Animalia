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
    Schema::create('veterinarias', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');

        $table->string('nombre_clinica');
        $table->string('cedula_profesional')->nullable();
        $table->string('telefono');
        $table->string('direccion');

        // Para el mapa (opcionales por ahora)
        $table->decimal('latitud', 10, 7)->nullable();
        $table->decimal('longitud', 10, 7)->nullable();

        $table->text('horario')->nullable();
        $table->boolean('urgencias_24h')->default(false);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('veterinarias');
    }
};
