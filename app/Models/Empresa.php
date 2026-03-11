<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresas';

    protected $fillable = [
        'nombre',
        'razon_social',
        'nit',
        'direccion',
        'correo',
        'celular',
        'logo_path',
        'color_hex',
        'activa',
    ];

    protected function casts(): array
    {
        return [
            'activa' => 'boolean',
        ];
    }

    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class, 'empresa_id');
    }

    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class, 'empresa_id');
    }

    public function proveedores(): HasMany
    {
        return $this->hasMany(Proveedor::class, 'empresa_id');
    }

    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }
}
