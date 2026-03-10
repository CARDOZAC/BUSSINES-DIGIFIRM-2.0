<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermisosSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permisos = [
            'clientes.ver',
            'clientes.crear',
            'clientes.editar',
            'clientes.eliminar',
            'clientes.exportar',
            'clientes.pdf',
            'usuarios.ver',
            'usuarios.crear',
            'usuarios.editar',
            'usuarios.eliminar',
            'dashboard.ver',
            'reportes.ver',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->syncPermissions($permisos);

        $adminCartera = Role::firstOrCreate(['name' => 'admin-cartera']);
        $adminCartera->syncPermissions($permisos);

        $vendedor = Role::firstOrCreate(['name' => 'vendedor']);
        $vendedor->syncPermissions([
            'clientes.ver',
            'clientes.crear',
            'clientes.editar',
            'clientes.pdf',
        ]);
    }
}
