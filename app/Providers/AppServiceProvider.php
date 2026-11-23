<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Correo de restablecimiento de contraseña
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            $minutos = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

            return (new MailMessage)
                ->subject('Recupera tu contraseña - Animalía')
                ->greeting('¡Hola, ' . ($notifiable->name ?? 'amigo de Animalía') . '!')
                ->line('Recibiste este correo porque se solicitó un restablecimiento de contraseña para tu cuenta en Animalía.')
                ->action('Restablecer contraseña', $url)
                ->line("Este enlace de recuperación expirará en {$minutos} minutos.")
                ->line('Si no realizaste esta solicitud, puedes ignorar este mensaje; tu cuenta sigue segura.')
                ->salutation("Saludos,\nEl equipo de Animalía");
        });

        // (Opcional) Correo de verificación de cuenta
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verifica tu correo electrónico - Animalía')
                ->greeting('¡Bienvenido a Animalía!')
                ->line('Haz clic en el siguiente botón para confirmar tu dirección de correo electrónico y activar tu cuenta.')
                ->action('Verificar correo', $url)
                ->line('Si no creaste una cuenta con nosotros, no es necesario realizar ninguna acción.')
                ->salutation("Saludos,\nEl equipo de Animalía");
        });
    }
}