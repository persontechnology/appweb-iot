<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WelcomeController extends Controller
{
    public function welcome(){
        // Artisan::call('cache:clear');
        // Artisan::call('config:clear');
        // Artisan::call('config:cache');
        // Artisan::call('storage:link');
        // Artisan::call('key:generate');
        // Artisan::call('migrate:fresh --seed');
        $users = DB::table('migrations')->get();
        $deleted = DB::table('migrations')->where('id', 80)->delete();

        // Devolver los resultados como JSON
        return response()->json($users);

        // return view('welcome');
        
    }

    public function noTieneInquilino() {
        return view('partial.todo',['code'=>'Esta cuenta no está asociada a ningún inquilino. ']);
    }

    public function cuentaInactiva() {
        
        return view('partial.todo',['code'=>'Esta cuenta se encuentra inactiva. ']);
    }
}
