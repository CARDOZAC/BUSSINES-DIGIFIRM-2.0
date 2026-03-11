<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'proveedores';

    protected $fillable = [
        'codigo_alterno',
        'nombre',
        'nit_rut',
        'telefono',
        'email',
        'ciudad',
        'pais',
        'empresa_id',
        'observaciones',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Proveedor $proveedor) {
            if (empty($proveedor->codigo_alterno)) {
                $ultimoNumero = self::where('empresa_id', $proveedor->empresa_id)->count();
                $proveedor->codigo_alterno = 'PROV-' . str_pad($ultimoNumero + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class, 'proveedor_id');
    }

    public function scopeBuscar($query, ?string $keyword)
    {
        if (empty($keyword)) {
            return $query;
        }

        return $query->where(function ($q) use ($keyword) {
            $q->where('nombre', 'like', "%{$keyword}%")
                ->orWhere('codigo_alterno', 'like', "%{$keyword}%")
                ->orWhere('nit_rut', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%");
        });
    }

    public function scopePorEmpresa($query, ?int $empresaId)
    {
        if (empty($empresaId)) {
            return $query;
        }

        return $query->where('empresa_id', $empresaId);
    }
}
