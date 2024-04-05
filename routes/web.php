<?php

use App\Http\Controllers\AlertaController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CategoriaGatewayController;
use App\Http\Controllers\CategoriaNodoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DispositivoController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\LecturaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NodoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SensorDataController;
use App\Http\Controllers\TestMqttController;
use App\Models\Alerta;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('gateways', GatewayController::class);
    Route::resource('applicaciones', ApplicationController::class);
    Route::resource('dispositivos', DispositivoController::class);

    Route::resource('alertas', AlertaController::class);
    Route::post('alertas/actualizarHorario', [AlertaController::class,'actualizarHorario'])->name('alertas.actualizarHorario');

    Route::resource('lecturas', LecturaController::class);
    


    


});

require __DIR__.'/auth.php';
