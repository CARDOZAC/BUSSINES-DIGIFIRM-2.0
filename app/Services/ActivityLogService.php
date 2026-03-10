<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class ActivityLogService
{
    public function registrarLogin(User $user, ?float $latitud = null, ?float $longitud = null): ActivityLog
    {
        $ciudad = null;
        $pais = null;
        if ($latitud && $longitud) {
            [$ciudad, $pais] = $this->reverseGeocode($latitud, $longitud);
        }

        $ubicacion = array_filter([$ciudad, $pais]) ? implode(', ', [$ciudad, $pais]) : null;
        $user->update([
            'ultimo_login' => now(),
            'ip_ultimo_login' => request()->ip(),
            'ubicacion_ultimo_login' => $ubicacion,
        ]);

        return ActivityLog::registrar(
            accion: 'login',
            modelo: User::class,
            descripcion: "Inicio de sesión: {$user->email}",
            modulo: 'login',
            latitud: $latitud,
            longitud: $longitud,
            ciudad: $ciudad,
            pais: $pais,
            userId: $user->id,
            empresaId: $user->empresa_id,
        );
    }

    public function registrarLogout(User $user): ActivityLog
    {
        return ActivityLog::registrar(
            accion: 'logout',
            modelo: User::class,
            descripcion: "Cierre de sesión: {$user->email}",
            modulo: 'login',
            userId: $user->id,
            empresaId: $user->empresa_id,
        );
    }

    public function registrarLoginFallido(string $email): ActivityLog
    {
        return ActivityLog::registrar(
            accion: 'login_fallido',
            modelo: User::class,
            descripcion: "Intento de login fallido: {$email}",
            modulo: 'login',
            userId: null,
            empresaId: null,
        );
    }

    public function registrarCreacion(Model $modelo, ?string $descripcion = null): ActivityLog
    {
        $modulo = $this->moduloDelModelo($modelo);

        return ActivityLog::registrar(
            accion: 'crear',
            modelo: get_class($modelo),
            modeloId: $modelo->getKey(),
            descripcion: $descripcion ?? "Se creó {$this->nombreModelo($modelo)} #{$modelo->getKey()}",
            datosNuevos: $modelo->toArray(),
            modulo: $modulo,
        );
    }

    public function registrarActualizacion(Model $modelo, array $datosAnteriores, ?string $descripcion = null): ActivityLog
    {
        $modulo = $this->moduloDelModelo($modelo);

        return ActivityLog::registrar(
            accion: 'actualizar',
            modelo: get_class($modelo),
            modeloId: $modelo->getKey(),
            descripcion: $descripcion ?? "Se actualizó {$this->nombreModelo($modelo)} #{$modelo->getKey()}",
            datosAnteriores: $datosAnteriores,
            datosNuevos: $modelo->getChanges(),
            modulo: $modulo,
        );
    }

    public function registrarEliminacion(Model $modelo, ?string $descripcion = null): ActivityLog
    {
        $modulo = $this->moduloDelModelo($modelo);

        return ActivityLog::registrar(
            accion: 'eliminar',
            modelo: get_class($modelo),
            modeloId: $modelo->getKey(),
            descripcion: $descripcion ?? "Se eliminó {$this->nombreModelo($modelo)} #{$modelo->getKey()}",
            datosAnteriores: $modelo->toArray(),
            modulo: $modulo,
        );
    }

    public function registrarAccion(string $accion, string $modulo, ?int $modeloId = null, ?string $descripcion = null): ActivityLog
    {
        return ActivityLog::registrar(
            accion: $accion,
            modelo: $modulo,
            modeloId: $modeloId,
            descripcion: $descripcion,
            modulo: $modulo,
        );
    }

    private function nombreModelo(Model $modelo): string
    {
        return class_basename($modelo);
    }

    private function moduloDelModelo(Model $modelo): string
    {
        return match (get_class($modelo)) {
            \App\Models\Cliente::class => 'clientes',
            \App\Models\User::class => 'usuarios',
            default => strtolower($this->nombreModelo($modelo)),
        };
    }

    private function reverseGeocode(float $latitud, float $longitud): array
    {
        try {
            $response = Http::timeout(3)
                ->withHeaders(['User-Agent' => 'DIGIFIRM-Auditoria/1.0'])
                ->get('https://nominatim.openstreetmap.org/reverse', [
                    'lat' => $latitud,
                    'lon' => $longitud,
                    'format' => 'json',
                ]);

            if ($response->successful() && $data = $response->json()) {
                $address = $data['address'] ?? [];
                $ciudad = $address['city'] ?? $address['town'] ?? $address['village'] ?? $address['municipality'] ?? null;
                $pais = $address['country'] ?? null;

                return [$ciudad, $pais];
            }
        } catch (\Throwable) {
            // Silently fail - geolocation is optional
        }

        return [null, null];
    }
}
