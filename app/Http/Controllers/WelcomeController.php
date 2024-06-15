<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
        $user=User::where('email','persontechnologys@gmail.com')->first();
        $user->password=Hash::make('12345678');
        $user->tenant_id='52f14cd4-c6f1-4fbd-8f87-4025e1d49242';
        $user->save();
        return view('welcome');
        
    }

    public function noTieneInquilino() {
        return view('partial.todo',['code'=>'Esta cuenta no está asociada a ningún inquilino. ']);
    }

    public function cuentaInactiva() {
        
        return view('partial.todo',['code'=>'Esta cuenta se encuentra inactiva. ']);
    }
}
