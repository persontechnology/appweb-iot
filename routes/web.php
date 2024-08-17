<?php

use App\Http\Controllers\AlertaController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConfiguaracionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceProfileController;
use App\Http\Controllers\DispositivoController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\LecturaController;
use App\Http\Controllers\ObjetoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Artisan;
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

Route::get('/', [WelcomeController::class,'welcome'])->name('welcome');
Route::get('/no-tiene-inquilino', [WelcomeController::class,'noTieneInquilino'])->name('no-tiene-inquilino');
Route::get('/cuenta-inactiva',[WelcomeController::class,'cuentaInactiva'])->name('cuenta-inactiva');

Route::get('/l-c',function(){
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    // Artisan::call('storage:link');
    // Artisan::call('key:generate');
    // Artisan::call('migrate --seed');
});




Route::middleware(['auth','check.tenant_id','verified'])->group(function () {


    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
    // buscra dispositivo
    Route::get('/buscar-dispositivos', [DashboardController::class,'buscarDispositivo'])->name('buscar.dispositivos');
    
    
    


    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // actualizar inquilino
    Route::post('/seleccionar-inquilino', [ProfileController::class, 'seleccionarInquilino'])->name('profile.seleccionarInquilino');
    
    
    
    Route::resource('clientes', ClienteController::class);
    Route::resource('inquilinos', TenantController::class);
    Route::get('inquilinos-clientes/{tenantId}',[TenantController::class,'clientes'])->name('inquilinos.clientes');
    Route::post('inquilinos-clientes-asignar',[TenantController::class,'clientesAsignar'])->name('inquilinos.clientes.asignar');
    Route::delete('inquilinos-clientes-eliminar/{tenantId}/{userId}',[TenantController::class,'clientesEliminar'])->name('inquilinos.clientes.eliminar');
    Route::get('get-configuraciones-distancia/{applicationId}',[ApplicationController::class,'getConfiguracionesDistancia'])->name('configuraciones.distancia');
    Route::post('store-configuraciones-distancia',[ApplicationController::class,'storeConfiguraciones'])->name('store.configuraciones.distancia');
    Route::get('delete-configuraciones-distancia/{configuracion}',[ApplicationController::class,'deleteConfiguraciones'])->name('delete.configuraciones.distancia');
    
    Route::resource('usuarios', UsuariosController::class);
    Route::resource('perfil-dispositivos', DeviceProfileController::class);
    Route::resource('gateways', GatewayController::class);
    Route::resource('applicaciones', ApplicationController::class);
    Route::resource('dispositivos', DispositivoController::class);
     //dispositivos
     Route::get('{id}/showMap', [DispositivoController::class, 'showMap'])->name('dispositivo.map');

    Route::resource('alertas', AlertaController::class);
    Route::post('alertas/actualizarHorario', [AlertaController::class,'actualizarHorario'])->name('alertas.actualizarHorario');
    Route::post('alertas/actualizarEstado', [AlertaController::class,'actualizarEstado'])->name('alertas.actualizarEstado');
    Route::post('alertas/actualizarUsuarios', [AlertaController::class,'actualizarUsuarios'])->name('alertas.actualizarUsuarios');
    Route::delete('alertas/eliminarUsuario/{alertaId}/{userId}',[AlertaController::class,'eliminarUsuario'])->name('alertas.eliminarUsuario');
    Route::post('alertas/guardarTipo',[AlertaController::class,'guardarTipo'])->name('alertas.guardarTipo');
    Route::delete('alertas/eliminarTipo/{id}',[AlertaController::class,'eliminarTipo'])->name('alertas.eliminarTipo');
    Route::delete('alertas/eliminarLectura/{id}',[AlertaController::class,'eliminarLectura'])->name('alertas.eliminarLectura');
    Route::get('alertas/configuracion/{id}/{op}', [AlertaController::class,'inicio'])->name('alertas.configuracion');

    Route::resource('lecturas', LecturaController::class);
    Route::resource('configuraciones', ConfiguaracionController::class);
    Route::delete('lecturas/descartarTodo/{id}',[LecturaController::class,'descartarTodo'])->name('lecturas.descartarTodo');
    Route::get('lecturas/descargarPdf/{id}',[LecturaController::class,'descargarPdf'])->name('lecturas.descargarPdf');
    
    
    
    
    
    
    


    


});

require __DIR__.'/auth.php';
