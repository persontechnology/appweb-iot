<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        // Deivid, crear roles
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roleAdmin = Role::firstOrCreate(['name' => config('app.ROLE_ADMIN')]);

        $permisos = array(
            'CATEGORIA DE NODOS',
            'NODOS',
            'CATEGORIA DE GATEWAY',
            'GATEWAY',
            'USUARIOS',
            'EMPRESA',
            'ROLES Y PERMISOS',
            'LECTURAS Y TRAMAS',
            'REPORTES',
        );
        

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        $user = User::firstOrCreate(
            ['name' => config('app.ADMIN_EMAIL')],
            [
                'email' => "fab@gmail.com",
                'password' => Hash::make("123456"),
                'estado'=>'ACTIVO'
            ]
        );
        $user->syncRoles($roleAdmin);
        
    }
}
