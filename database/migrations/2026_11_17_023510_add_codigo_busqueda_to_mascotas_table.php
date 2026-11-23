<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mascotas', function (Blueprint $table) {
            $table->string('codigo_busqueda', 8)->nullable()->unique()->after('codigo_qr');
        });

        // Asignar código a las mascotas que ya existen
        $alfabeto = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

        foreach (DB::table('mascotas')->whereNull('codigo_busqueda')->pluck('id') as $id) {
            do {
                $codigo = '';
                for ($i = 0; $i < 6; $i++) {
                    $codigo .= $alfabeto[random_int(0, strlen($alfabeto) - 1)];
                }
            } while (DB::table('mascotas')->where('codigo_busqueda', $codigo)->exists());

            DB::table('mascotas')->where('id', $id)->update(['codigo_busqueda' => $codigo]);
        }
    }

    public function down(): void
    {
        Schema::table('mascotas', function (Blueprint $table) {
            $table->dropUnique(['codigo_busqueda']);
            $table->dropColumn('codigo_busqueda');
        });
    }
};
