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
        Schema::create('mascotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nombre');
            $table->enum('especie', ['perro', 'gato', 'otro']);
            $table->string('raza')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('sexo', ['macho', 'hembra'])->nullable();
            $table->string('foto')->nullable();
            $table->uuid('codigo_qr')->unique();
            $table->boolean('extraviado')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mascotas');
    }
};
