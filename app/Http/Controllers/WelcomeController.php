<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class WelcomeController extends Controller
{
    public function welcome(){
        // Artisan::call('cache:clear');
        // Artisan::call('config:clear');
        // Artisan::call('config:cache');
        // Artisan::call('storage:link');
        // Artisan::call('key:generate');
        // Artisan::call('migrate:fresh --seed');
        // $users = DB::table('password_reset_tokens')->get();
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_has_permissions');
        
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('permissions');
        
        
        
        // $deleted = DB::table('migrations')->whereIn('id', [77,79])->delete();

        // Devolver los resultados como JSON
        return response()->json('ok');

        // return view('welcome');
        
    }

    public function noTieneInquilino() {
        return view('partial.todo',['code'=>'Esta cuenta no está asociada a ningún inquilino. ']);
    }

    public function cuentaInactiva() {
        
        return view('partial.todo',['code'=>'Esta cuenta se encuentra inactiva. ']);
    }
}
