<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mascotas', function (Blueprint $table) {
            $table->decimal('peso', 5, 2)->nullable()->after('sexo');
            $table->text('alergias')->nullable()->after('peso');
            $table->text('condiciones_medicas')->nullable()->after('alergias');
        });
    }

    public function down(): void
    {
        Schema::table('mascotas', function (Blueprint $table) {
            $table->dropColumn(['peso', 'alergias', 'condiciones_medicas']);
        });
    }
};