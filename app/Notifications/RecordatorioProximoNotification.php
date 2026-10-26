<?php

namespace App\Notifications;

use App\Models\Recordatorio;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RecordatorioProximoNotification extends Notification implements ShouldQueue
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
            ->subject("Recordatorio: {$this->recordatorio->titulo} de {$mascota->nombre}")
            ->greeting("¡Hola {$notifiable->name}!")
            ->line("Este es un recordatorio de Animalía sobre la salud de {$mascota->nombre}.")
            ->line("**{$this->recordatorio->tipo_label}:** {$this->recordatorio->titulo}")
            ->line("Fecha programada: {$fecha}")
            ->when($this->recordatorio->descripcion, function (MailMessage $mail) {
                return $mail->line($this->recordatorio->descripcion);
            })
            ->action('Ver expediente completo', route('mascotas.show', $mascota))
            ->line('Mantener al día el esquema preventivo ayuda a proteger la salud de tu mascota.');
    }
}