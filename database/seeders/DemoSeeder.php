<?php

namespace Database\Seeders;

use App\Models\Mascota;
use App\Models\User;
use App\Models\Veterinaria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command->error('DemoSeeder no se debe correr en producción.');
            return;
        }

        // unguarded: ignora $fillable, útil para datos de prueba
        Model::unguarded(fn () => $this->sembrar());
    }

    private function sembrar(): void
    {
        // ---------------- VETERINARIO ----------------
        $vet = User::updateOrCreate(['email' => 'vet@animalia.test'], [
            'name' => 'Dra. Ana Torres',
            'password' => 'password',
            'rol' => 'veterinario',
            'telefono' => '5598765432',
            'direccion' => 'Av. Central 123, Col. Centro, Tecámac, Estado de México',
            'email_verified_at' => now(),
        ]);

        $clinica = Veterinaria::updateOrCreate(['user_id' => $vet->id], [
            'nombre_clinica' => 'Clínica Veterinaria San Jorge',
            'cedula_profesional' => '12345678',
            'telefono' => '5598765432',
            'direccion' => 'Av. Central 123, Col. Centro, Tecámac, Estado de México',
            'latitud' => 19.7133000,
            'longitud' => -98.9686000,
            'horario' => 'Abierto las 24 horas, todos los días.',
            'urgencias_24h' => true,
        ]);

        // ---------------- DUEÑA CON TODOS SUS DATOS ----------------
        $laura = User::updateOrCreate(['email' => 'laura@animalia.test'], [
            'name' => 'Laura Martínez',
            'password' => 'password',
            'rol' => 'propietario',
            'telefono' => '5512345678',
            'direccion' => 'Calle Hidalgo 45, Col. Los Héroes, Tecámac, Estado de México',
            'email_verified_at' => now(),
        ]);

        // Para probar con foto: copia una imagen a storage/app/public/mascotas/
        // y pon aquí, por ejemplo, 'foto' => 'mascotas/firulais.jpg'
        $firulais = $this->mascota($laura, [
            'nombre' => 'Firulais',
            'especie' => 'perro',
            'raza' => 'Labrador Retriever',
            'sexo' => 'macho',
            'fecha_nacimiento' => now()->subYears(3)->subMonths(2)->toDateString(),
            'peso' => 28.50,
            'alergias' => 'Alergia al pollo y a la picadura de pulga.',
            'condiciones_medicas' => 'Displasia de cadera leve.',
            'foto' => null,
            'extraviado' => false,
        ]);

        $michi = $this->mascota($laura, [
            'nombre' => 'Michi',
            'especie' => 'gato',
            'raza' => 'Siamés',
            'sexo' => 'hembra',
            'fecha_nacimiento' => now()->subYears(2)->subMonths(5)->toDateString(),
            'peso' => 4.20,
            'alergias' => null,
            'condiciones_medicas' => null,
            'foto' => null,
            'extraviado' => false,
        ]);

        $rocky = $this->mascota($laura, [
            'nombre' => 'Rocky',
            'especie' => 'perro',
            'raza' => 'Schnauzer miniatura',
            'sexo' => 'macho',
            'fecha_nacimiento' => now()->subYears(5)->toDateString(),
            'peso' => 7.80,
            'alergias' => null,
            'condiciones_medicas' => 'Hipotiroidismo controlado con medicamento.',
            'foto' => null,
            'extraviado' => true,
        ]);

        // ---------------- SEGUNDO DUEÑO (prueba de aislamiento) ----------------
        $carlos = User::updateOrCreate(['email' => 'carlos@animalia.test'], [
            'name' => 'Carlos Hernández',
            'password' => 'password',
            'rol' => 'propietario',
            'telefono' => '5587654321',
            'direccion' => 'Calle Morelos 210, Col. Ojo de Agua, Tecámac, Estado de México',
            'email_verified_at' => now(),
        ]);

        $luna = $this->mascota($carlos, [
            'nombre' => 'Luna',
            'especie' => 'perro',
            'raza' => 'Husky Siberiano',
            'sexo' => 'hembra',
            'fecha_nacimiento' => now()->subYear()->toDateString(),
            'peso' => 19.00,
            'foto' => null,
            'extraviado' => false,
        ]);

        // ---------------- RECORDATORIOS (solo si la mascota es nueva) ----------------
        if ($firulais->wasRecentlyCreated) {
            $firulais->recordatorios()->createMany([
                [
                    'tipo' => 'vacuna',
                    'titulo' => 'Vacuna antirrábica',
                    'descripcion' => 'Refuerzo anual.',
                    'fecha_programada' => now()->subDays(20)->toDateString(),
                    'estado' => 'vencido',
                ],
                [
                    'tipo' => 'desparasitacion',
                    'titulo' => 'Desparasitación interna',
                    'fecha_programada' => now()->addDays(5)->toDateString(),
                    'estado' => 'pendiente',
                ],
                [
                    'tipo' => 'vacuna',
                    'titulo' => 'Vacuna múltiple (parvovirus y moquillo)',
                    'fecha_programada' => now()->subMonths(2)->toDateString(),
                    'fecha_aplicacion' => now()->subMonths(2)->toDateString(),
                    'estado' => 'aplicado',
                ],
            ]);

            // ---------------- CONSULTAS DEL VETERINARIO ----------------
            // created_at antiguo = no muestra la etiqueta "Nueva"
            $firulais->consultas()->createMany([
                [
                    'veterinaria_id' => $clinica->id,
                    'motivo' => 'Revisión general',
                    'diagnostico' => 'Buen estado general. Leve sobrepeso.',
                    'tratamiento' => 'Ajustar porciones y paseos diarios de 30 minutos.',
                    'fecha_consulta' => now()->subDays(3)->toDateString(),
                    'created_at' => now()->subDays(3),
                ],
                [
                    'veterinaria_id' => $clinica->id,
                    'motivo' => 'Control de cadera',
                    'diagnostico' => 'Displasia leve sin progresión.',
                    'tratamiento' => 'Suplemento articular y evitar saltos.',
                    'fecha_consulta' => now()->subMonths(2)->toDateString(),
                    'created_at' => now()->subMonths(2),
                ],
            ]);
        }

        if ($michi->wasRecentlyCreated) {
            $michi->recordatorios()->createMany([
                [
                    'tipo' => 'vacuna',
                    'titulo' => 'Triple felina',
                    'fecha_programada' => now()->subMonths(3)->toDateString(),
                    'fecha_aplicacion' => now()->subMonths(3)->toDateString(),
                    'estado' => 'aplicado',
                ],
                [
                    'tipo' => 'chequeo',
                    'titulo' => 'Chequeo anual',
                    'fecha_programada' => now()->addDays(30)->toDateString(),
                    'estado' => 'pendiente',
                ],
            ]);

            $michi->consultas()->create([
                'veterinaria_id' => $clinica->id,
                'motivo' => 'Vacunación triple felina',
                'tratamiento' => 'Dosis aplicada. Próximo refuerzo en un año.',
                'fecha_consulta' => now()->subMonths(3)->toDateString(),
                'created_at' => now()->subMonths(3),
            ]);
        }

        if ($rocky->wasRecentlyCreated) {
            $rocky->recordatorios()->create([
                'tipo' => 'medicamento',
                'titulo' => 'Levotiroxina',
                'descripcion' => 'Una tableta cada 12 horas.',
                'fecha_programada' => now()->addDays(2)->toDateString(),
                'estado' => 'pendiente',
            ]);
        }

        // ---------------- RESUMEN EN CONSOLA ----------------
        $this->command->info('Cuentas de prueba (contraseña: password)');
        $this->command->table(['Rol', 'Correo'], [
            ['Veterinario', 'vet@animalia.test'],
            ['Dueña', 'laura@animalia.test'],
            ['Dueño', 'carlos@animalia.test'],
        ]);

        $this->command->info('Códigos para buscar en el panel del veterinario');
        $this->command->table(
            ['Mascota', 'Código QR'],
            collect([$firulais, $michi, $rocky, $luna])
                ->map(fn ($m) => [$m->nombre, $m->codigo_qr])
                ->all()
        );
    }

    private function mascota(User $dueno, array $datos): Mascota
    {
        return Mascota::firstOrCreate(
            ['user_id' => $dueno->id, 'nombre' => $datos['nombre']],
            $datos
        );
    }
}
