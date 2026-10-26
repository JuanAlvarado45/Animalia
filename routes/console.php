<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Módulo de Medicina Preventiva Automatizada: cada día revisa la base de
// recordatorios, actualiza los que ya vencieron y dispara las notificaciones
// automáticas por correo a los dueños (vencidos y próximos a 7 días).
Schedule::command('recordatorios:revisar')
    ->dailyAt('08:00')
    ->withoutOverlapping();
