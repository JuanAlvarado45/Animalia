<?php

namespace App\Console\Commands;

use App\Models\Recordatorio;
use App\Notifications\RecordatorioProximoNotification;
use App\Notifications\RecordatorioVencidoNotification;
use Illuminate\Console\Command;

class RevisarRecordatorios extends Command
{
    protected $signature = 'recordatorios:revisar';

    protected $description = 'Marca como vencidos los recordatorios cuya fecha ya pasó y notifica a los dueños sobre vacunas/desparasitaciones próximas o vencidas.';

    public function handle(): int
    {
        $this->marcarVencidos();
        $this->notificarVencidos();
        $this->notificarProximos();

        return self::SUCCESS;
    }

    protected function marcarVencidos(): void
    {
        $actualizados = Recordatorio::where('estado', 'pendiente')
            ->whereDate('fecha_programada', '<', now()->toDateString())
            ->update(['estado' => 'vencido']);

        if ($actualizados > 0) {
            $this->info("Recordatorios marcados como vencidos: {$actualizados}");
        }
    }

    protected function notificarVencidos(): void
    {
        $recordatorios = Recordatorio::with('mascota.user')
            ->where('estado', 'vencido')
            ->whereNull('notificado_at')
            ->get();

        foreach ($recordatorios as $recordatorio) {
            $usuario = $recordatorio->mascota->user;

            if ($usuario) {
                $usuario->notify(new RecordatorioVencidoNotification($recordatorio));
            }

            $recordatorio->update(['notificado_at' => now()]);
        }

        if ($recordatorios->isNotEmpty()) {
            $this->info("Notificaciones de vencidos enviadas: {$recordatorios->count()}");
        }
    }

    protected function notificarProximos(): void
    {
        $recordatorios = Recordatorio::with('mascota.user')
            ->proximos(7)
            ->whereNull('notificado_at')
            ->get();

        foreach ($recordatorios as $recordatorio) {
            $usuario = $recordatorio->mascota->user;

            if ($usuario) {
                $usuario->notify(new RecordatorioProximoNotification($recordatorio));
            }

            $recordatorio->update(['notificado_at' => now()]);
        }

        if ($recordatorios->isNotEmpty()) {
            $this->info("Notificaciones de próximos vencimientos enviadas: {$recordatorios->count()}");
        }
    }
}