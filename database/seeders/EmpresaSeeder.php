<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        $empresas = [
            [
                'nombre' => 'AJAR',
                'razon_social' => 'DISTRIBUCIONES AJAR SAS',
                'nit' => '901234567-1',
                'direccion' => 'Calle 16 #6-03 Barrio Villa Johana',
                'correo' => 'cartera@distribucionesajar.com',
                'celular' => '3172712559',
                'color_hex' => '#1e3a5f',
                'activa' => true,
            ],
            [
                'nombre' => 'RINVAL',
                'razon_social' => 'RINVAL SAS',
                'nit' => '901234567-2',
                'direccion' => null,
                'correo' => null,
                'celular' => null,
                'color_hex' => '#2d6a4f',
                'activa' => true,
            ],
            [
                'nombre' => 'AERJ',
                'razon_social' => 'AERJ SAS',
                'nit' => '901234567-3',
                'direccion' => null,
                'correo' => null,
                'celular' => null,
                'color_hex' => '#e76f51',
                'activa' => true,
            ],
            [
                'nombre' => 'DISTMASIVOS',
                'razon_social' => 'DISTMASIVOS SAS',
                'nit' => '901234567-4',
                'direccion' => null,
                'correo' => null,
                'celular' => null,
                'color_hex' => '#7c3aed',
                'activa' => true,
            ],
            [
                'nombre' => 'RINVALSUPERRICAS',
                'razon_social' => 'RINVAL SUPER RICAS SAS',
                'nit' => '901234567-5',
                'direccion' => null,
                'correo' => null,
                'celular' => null,
                'color_hex' => '#0d9488',
                'activa' => true,
            ],
        ];

        foreach ($empresas as $empresa) {
            Empresa::updateOrCreate(
                ['nombre' => $empresa['nombre']],
                $empresa
            );
        }
    }
}
