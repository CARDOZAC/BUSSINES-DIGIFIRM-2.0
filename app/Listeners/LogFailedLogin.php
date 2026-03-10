<?php

namespace App\Listeners;

use App\Services\ActivityLogService;
use Illuminate\Auth\Events\Failed;

class LogFailedLogin
{
    public function handle(Failed $event): void
    {
        $email = $event->credentials['email'] ?? 'desconocido';

        app(ActivityLogService::class)->registrarLoginFallido($email);
    }
}
