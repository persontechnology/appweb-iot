<?php

namespace App\Http\Controllers;

use App\Models\Dispositivo;
use App\Models\Lectura;
use App\Models\PuntosLocalizacion;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PDF;


class DashboardController extends Controller
{
    public function index() {
        

        
        $dispositivos=Dispositivo::whereHas('application', function ($query) {
            $query->whereHas('tenant', function ($query) {
                $query->where('id', Auth::user()->tenant_id);
            });
        })->with(['deviceprofile:id,name,description','puntosLocalizacion','lecturas','lecturasLatest'])
        ->selectRaw("encode(dev_eui, 'hex') as dev_eui_hex, *")
        ->get();
        $data = array('dispositivos'=>$dispositivos);
        return view('dashboard',$data);
    }

    public function buscarDispositivo(Request $request)
    {
        $query = $request->get('query');

        // Realiza la búsqueda de dispositivos según el query
        $dispositivos=Dispositivo::whereHas('application', function ($query) {
            $query->whereHas('tenant', function ($query) {
                $query->where('id', Auth::user()->tenant_id);
            });
        })->with(['deviceprofile:id,name,description','puntosLocalizacion','lecturas','lecturasLatest','puntosLocalizacionLatest'])
        ->selectRaw("encode(dev_eui, 'hex') as dev_eui_hex, *");
        if(isset($query)){
            $dispositivos=$dispositivos->when($query, function ($query, $search) {
                return $query->where(DB::raw("encode(dev_eui, 'hex')"), 'like', "%$search%");
            });
        }
        $dispositivos=$dispositivos->get();
     
        return response()->json($dispositivos);
    }

 




}
