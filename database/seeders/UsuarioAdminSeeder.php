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

        $admin = User::updateOrCreate(
            ['email' => 'admin@gruporv.com'],
            [
                'empresa_id' => $ajar->id,
                'name' => 'Administrador R&V',
                'password' => Hash::make('Rv@2026!'),
                'active' => true,
            ]
        );

        $admin->assignRole('admin-cartera');

        $vendedor = User::updateOrCreate(
            ['email' => 'vendedor@gruporv.com'],
            [
                'empresa_id' => $ajar->id,
                'name' => 'Vendedor Demo AJAR',
                'password' => Hash::make('Rv@2026!'),
                'active' => true,
            ]
        );

        $vendedor->assignRole('vendedor');
    }
}
