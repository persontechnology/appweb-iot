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
        })
        ->selectRaw("encode(dev_eui, 'hex') as dev_eui_hex, *")
        ->get();
        
        //return $dispositivos;
        $data = array('dispositivos'=>$dispositivos);
        return view('dashboard',$data);
    }

    public function buscarDispositivo(Request $request)
    {
        $query = $request->get('query');

        // Realiza la búsqueda de dispositivos según el query
        $dispositivos = Dispositivo::whereHas('application', function ($query) {
            $query->whereHas('tenant', function ($query) {
                $query->where('id', Auth::user()->tenant_id);
            });
        })
        ->when($query, function ($query, $search) {
            return $query->where(DB::raw("encode(dev_eui, 'hex')"), 'like', "%$search%");
        })
        ->selectRaw("encode(dev_eui, 'hex') as dev_eui_hex, *")
        ->when(!$query, function ($query) {
            return $query->take(20);
        })
        ->get();

        return response()->json($dispositivos);
    }

 




}
