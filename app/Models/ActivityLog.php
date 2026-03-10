<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'empresa_id',
        'accion',
        'modulo',
        'modelo',
        'modelo_id',
        'descripcion',
        'datos_anteriores',
        'datos_nuevos',
        'ip',
        'user_agent',
        'latitud',
        'longitud',
        'ciudad',
        'pais',
    ];

    protected function casts(): array
    {
        return [
            'datos_anteriores' => 'array',
            'datos_nuevos' => 'array',
            'latitud' => 'decimal:7',
            'longitud' => 'decimal:7',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function scopeDelModelo($query, string $modelo)
    {
        return $query->where('modelo', $modelo);
    }

    public function scopeDelUsuario($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeDelModulo($query, ?string $modulo)
    {
        return $modulo ? $query->where('modulo', $modulo) : $query;
    }

    public function scopeDeEmpresa($query, ?int $empresaId)
    {
        return $empresaId ? $query->where('empresa_id', $empresaId) : $query;
    }

    public function scopeDeAccion($query, ?string $accion)
    {
        return $accion ? $query->where('accion', $accion) : $query;
    }

    public function scopeEntreFechas($query, ?string $desde, ?string $hasta)
    {
        if ($desde) {
            $query->whereDate('created_at', '>=', $desde);
        }
        if ($hasta) {
            $query->whereDate('created_at', '<=', $hasta);
        }
        return $query;
    }

    public function ubicacionTexto(): string
    {
        $partes = array_filter([$this->ciudad, $this->pais]);

        return implode(', ', $partes) ?: ($this->latitud && $this->longitud
            ? "{$this->latitud}, {$this->longitud}"
            : '—');
    }

    public static function registrar(
        string $accion,
        string $modelo,
        ?int $modeloId = null,
        ?string $descripcion = null,
        ?array $datosAnteriores = null,
        ?array $datosNuevos = null,
        ?string $modulo = null,
        ?float $latitud = null,
        ?float $longitud = null,
        ?string $ciudad = null,
        ?string $pais = null,
        ?int $userId = null,
        ?int $empresaId = null,
    ): self {
        return self::create([
            'user_id' => $userId ?? auth()->id(),
            'empresa_id' => $empresaId ?? auth()->user()?->empresa_id,
            'accion' => $accion,
            'modulo' => $modulo,
            'modelo' => $modelo,
            'modelo_id' => $modeloId,
            'descripcion' => $descripcion,
            'datos_anteriores' => $datosAnteriores,
            'datos_nuevos' => $datosNuevos,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'latitud' => $latitud,
            'longitud' => $longitud,
            'ciudad' => $ciudad,
            'pais' => $pais,
        ]);
    }
}
