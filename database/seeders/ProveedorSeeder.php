<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\Proveedor;
use Illuminate\Database\Seeder;

class ProveedorSeeder extends Seeder
{
    public function run(): void
    {
        $empresas = Empresa::take(5)->get();
        if ($empresas->isEmpty()) {
            $this->command->warn('No hay empresas. Ejecute EmpresaSeeder primero.');
            return;
        }

        $proveedores = [
            [
                'nombre' => 'Distribuidora Nacional S.A.',
                'nit_rut' => '900.123.456-7',
                'telefono' => '3001234567',
                'email' => 'contacto@distnacional.com',
                'ciudad' => 'Bogotá',
                'empresa_id' => $empresas->first()->id,
                'activo' => true,
            ],
            [
                'nombre' => 'Suministros Industriales del Valle',
                'nit_rut' => '900.234.567-8',
                'telefono' => '3109876543',
                'email' => 'ventas@suministrosvalle.com',
                'ciudad' => 'Cali',
                'empresa_id' => $empresas->skip(1)->first()?->id ?? $empresas->first()->id,
                'activo' => true,
            ],
            [
                'nombre' => 'Proveedores Andinos S.A.S.',
                'nit_rut' => '900.345.678-9',
                'telefono' => '3205551234',
                'email' => 'info@proveedoresandinos.co',
                'ciudad' => 'Medellín',
                'empresa_id' => $empresas->skip(2)->first()?->id ?? $empresas->first()->id,
                'activo' => true,
            ],
        ];

        foreach ($proveedores as $data) {
            Proveedor::updateOrCreate(
                [
                    'nit_rut' => $data['nit_rut'],
                    'empresa_id' => $data['empresa_id'],
                ],
                $data
            );
        }
    }
}
