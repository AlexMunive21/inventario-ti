<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesYUsuariosSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permisos = [
            'ver todo',
            'ver colaboradores',
            'ver dashboard',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // Crear roles
        $gerente    = Role::firstOrCreate(['name' => 'GerenteTIDS']);
        $analistaDS = Role::firstOrCreate(['name' => 'AnalistaDS']);
        $analistaTI = Role::firstOrCreate(['name' => 'AnalistaTI']);
        $rh         = Role::firstOrCreate(['name' => 'rh']);

        // Gerente tiene todos los permisos
        $gerente->syncPermissions(['ver todo', 'ver colaboradores', 'ver dashboard']);

        // AnalistaTI puede ver todo y colaboradores
        $analistaTI->syncPermissions(['ver todo', 'ver colaboradores', 'ver dashboard']);

        // AnalistaDS solo dashboard y colaboradores
        $analistaDS->syncPermissions(['ver colaboradores', 'ver dashboard']);

        // RH solo colaboradores
        $rh->syncPermissions(['ver colaboradores', 'ver dashboard']);

        // Crear usuario Gerente principal
        $usuario = User::firstOrCreate(
            ['email' => 'gerente@inventario.com'],
            [
                'name'     => 'Gerente TI',
                'password' => Hash::make('password123'),
            ]
        );

        $usuario->assignRole('GerenteTIDS');

        $this->command->info('✅ Roles, permisos y usuario creados correctamente.');
        $this->command->info('📧 Email:    gerente@inventario.com');
        $this->command->info('🔑 Password: password123');
    }
}