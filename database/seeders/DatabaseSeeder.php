<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Tenant;
use App\Models\TipoDispositivo;
use Illuminate\Support\Str;

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

        
        $tenant_id=Tenant::first()->id;

        $user=User::where('email',config('app.ADMIN_EMAIL'))->first();
        if(!$user){
            $user= new User();
            $user->email = config('app.ADMIN_EMAIL');
            $user->is_admin=true;
            $user->is_active=true;
            $user->password_hash=Hash::make(config('app.ADMIN_EMAIL'));
            $user->email_verified=false;
            $user->note='';
            $user->tenant_id=$tenant_id;
            
        }
        $user->password=Hash::make(config('app.ADMIN_EMAIL'));
        $user->save();


        if(!$user->tenant_id){
            $user->tenant_id=$tenant_id;
            $user->save();
        }
        $user->syncRoles($roleAdmin);


        // tipos de dispositivos
        $tiposDispositivos = array(
            'Smart Button',
            'Environment Monitoring Sensor',
            'Tracking'
        );
        
        foreach ($tiposDispositivos as $tipoDispositivo) {
            TipoDispositivo::firstOrCreate(['nombre' => $tipoDispositivo]);
        }

    }
}
