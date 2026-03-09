<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogService
{
    public function registrarCreacion(Model $modelo, ?string $descripcion = null): ActivityLog
    {
        return ActivityLog::registrar(
            accion: 'crear',
            modelo: get_class($modelo),
            modeloId: $modelo->getKey(),
            descripcion: $descripcion ?? "Se creó {$this->nombreModelo($modelo)} #{$modelo->getKey()}",
            datosNuevos: $modelo->toArray(),
        );
    }

    public function registrarActualizacion(Model $modelo, array $datosAnteriores, ?string $descripcion = null): ActivityLog
    {
        return ActivityLog::registrar(
            accion: 'actualizar',
            modelo: get_class($modelo),
            modeloId: $modelo->getKey(),
            descripcion: $descripcion ?? "Se actualizó {$this->nombreModelo($modelo)} #{$modelo->getKey()}",
            datosAnteriores: $datosAnteriores,
            datosNuevos: $modelo->getChanges(),
        );
    }

    public function registrarEliminacion(Model $modelo, ?string $descripcion = null): ActivityLog
    {
        return ActivityLog::registrar(
            accion: 'eliminar',
            modelo: get_class($modelo),
            modeloId: $modelo->getKey(),
            descripcion: $descripcion ?? "Se eliminó {$this->nombreModelo($modelo)} #{$modelo->getKey()}",
            datosAnteriores: $modelo->toArray(),
        );
    }

    public function registrarAccion(string $accion, string $modelo, ?int $modeloId = null, ?string $descripcion = null): ActivityLog
    {
        return ActivityLog::registrar(
            accion: $accion,
            modelo: $modelo,
            modeloId: $modeloId,
            descripcion: $descripcion,
        );
    }

    private function nombreModelo(Model $modelo): string
    {
        return class_basename($modelo);
    }
}
