<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioAdminSeeder extends Seeder
{
    public function run(): void
    {
        $ajar = Empresa::where('nombre', 'AJAR')->first();

        if (!$ajar) {
            return;
        }

        // Admin: puede ver todas las empresas (rol admin-cartera)
        $admin = User::updateOrCreate(
            ['email' => 'admin@todos.com'],
            [
                'empresa_id' => $ajar->id,
                'name' => 'Administrador',
                'password' => Hash::make('Rv@2026!'),
                'active' => true,
            ]
        );

        $admin->assignRole('admin-cartera');

        // Super Admin: gestión de usuarios
        $superAdmin = User::updateOrCreate(
            ['email' => 'super@todos.com'],
            [
                'empresa_id' => $ajar->id,
                'name' => 'Super Administrador',
                'password' => Hash::make('Rv@2026!'),
                'active' => true,
            ]
        );

        $superAdmin->assignRole('super_admin');

        // Vendedores: uno por empresa
        $vendedores = [
            ['email' => 'vendedor1@ajar.com', 'empresa' => 'AJAR', 'name' => 'Vendedor Demo AJAR'],
            ['email' => 'vendedor1@rinval.com', 'empresa' => 'RINVAL', 'name' => 'Vendedor Demo RINVAL'],
            ['email' => 'vendedor1@distmasivos.com', 'empresa' => 'DISTMASIVOS', 'name' => 'Vendedor Demo DISTMASIVOS'],
            ['email' => 'vendedor1@rinvalsuperricas.com', 'empresa' => 'RINVALSUPERRICAS', 'name' => 'Vendedor Demo RINVALSUPERRICAS'],
        ];

        foreach ($vendedores as $v) {
            $empresa = Empresa::where('nombre', $v['empresa'])->first();
            if ($empresa) {
                User::updateOrCreate(
                    ['email' => $v['email']],
                    [
                        'empresa_id' => $empresa->id,
                        'name' => $v['name'],
                        'password' => Hash::make('Rv@2026!'),
                        'active' => true,
                    ]
                )->assignRole('vendedor');
            }
        }
    }
}
