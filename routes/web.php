<?php

use App\Http\Controllers\CategoriaGatewayController;
use App\Http\Controllers\CategoriaNodoController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\NodoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestMqttController;
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

Route::get('/mqtt-publish',[TestMqttController::class,'index']);
Route::get('/mqtt-subscribe',[TestMqttController::class,'index2']);



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/gateway/{gateway}/updateAction', [GatewayController::class, 'updateAction'])->name('profile.updateAction');

    // categoria de gateway
    Route::resource('categoria-gateway', CategoriaGatewayController::class);
    // categorria de nodos
    Route::resource('categoria-nodo', CategoriaNodoController::class);
    // gateway
    Route::resource('gateway', GatewayController::class);
    // nodos
    Route::resource('nodo', NodoController::class);

});

require __DIR__.'/auth.php';
