<?php

namespace App\Http\Controllers;

use App\Models\Dispositivo;
use App\Models\Lectura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
        // buscando los dispositivos 
        /*$lectura=Lectura::first();
        $dispo=Lectura::buscarDispositivoUsoDevEui($lectura->dev_eui);
        $lectura["dispositivo_convertido_dev_eui"]=$dispo;
        return $lectura->dispositivo_convertido_dev_eui;*/
        $dispositivos=    Dispositivo::whereHas('application', function ($query) {
            $query->whereHas('tenant', function ($query) {
                $query->where('id', Auth::user()->tenant_id);
            });
        })
        ->selectRaw("encode(dev_eui, 'hex') as dev_eui_hex, *")
        ->get();
        
        // return $dispositivos;
        $data = array('dispositivos'=>$dispositivos);
        return view('dashboard',$data);
    }


}
