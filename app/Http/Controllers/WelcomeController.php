<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
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
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        // $user=User::where('email',config('app.ADMIN_EMAIL'))->first();
        // if($user){
        //     $user->password=Hash::make('12345678');
        //     $user->save();
        // }


        return view('welcome');
        
    }

    public function noTieneInquilino() {
        return view('partial.todo',['code'=>'Esta cuenta no está asociada a ningún inquilino. ']);
    }

    public function cuentaInactiva() {
        
        return view('partial.todo',['code'=>'Esta cuenta se encuentra inactiva. ']);
    }
}
