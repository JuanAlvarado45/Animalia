<?php

namespace App\Policies;

use App\Models\Recordatorio;
use App\Models\User;

class RecordatorioPolicy
{
    public function view(User $user, Recordatorio $recordatorio): bool
    {
        return $user->id === $recordatorio->mascota->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Recordatorio $recordatorio): bool
    {
        return $user->id === $recordatorio->mascota->user_id;
    }

    public function delete(User $user, Recordatorio $recordatorio): bool
    {
        return $user->id === $recordatorio->mascota->user_id;
    }
}