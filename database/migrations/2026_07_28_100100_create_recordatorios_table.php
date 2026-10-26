<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recordatorios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mascota_id')->constrained()->onDelete('cascade');

            $table->enum('tipo', ['vacuna', 'desparasitacion', 'chequeo', 'medicamento', 'otro'])
                ->default('otro');

            $table->string('titulo');
            $table->text('descripcion')->nullable();

            $table->date('fecha_programada');
            $table->date('fecha_aplicacion')->nullable();

            $table->enum('estado', ['pendiente', 'aplicado', 'vencido'])
                ->default('pendiente');

            $table->timestamp('notificado_at')->nullable();

            $table->timestamps();

            $table->index(['mascota_id', 'estado']);
            $table->index('fecha_programada');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recordatorios');
    }
};