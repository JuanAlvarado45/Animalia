<?php

namespace App\Notifications;

use App\Models\Recordatorio;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RecordatorioVencidoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Recordatorio $recordatorio)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mascota = $this->recordatorio->mascota;
        $fecha = $this->recordatorio->fecha_programada->translatedFormat('d \d\e F \d\e Y');

        return (new MailMessage)
            ->subject("⚠️ Venció: {$this->recordatorio->titulo} de {$mascota->nombre}")
            ->greeting("Hola {$notifiable->name},")
            ->line("El siguiente evento de salud de {$mascota->nombre} venció y sigue pendiente:")
            ->line("**{$this->recordatorio->tipo_label}:** {$this->recordatorio->titulo}")
            ->line("Estaba programado para el {$fecha}.")
            ->action('Regularizar en el expediente', route('mascotas.show', $mascota))
            ->line('Te recomendamos agendar cita con tu veterinario de confianza lo antes posible.');
    }
}