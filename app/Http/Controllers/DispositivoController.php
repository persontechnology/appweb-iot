<?php

namespace App\Http\Controllers;

use App\DataTables\DispositivoDataTable;
use App\Models\Application;
use App\Models\DeviceKeys;
use App\Models\DeviceProfile;
use App\Models\Dispositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DispositivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(DispositivoDataTable $dataTable)
    {
        // return DeviceKeys::where('dev_eui', DB::raw("decode('24e124535d387374', 'hex')"))->selectRaw("encode(nwk_key, 'hex') as device_id_hex")->first();

        return $dataTable->render('dispositivos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = array(
            'aplicaciones'=>Application::get(),
            'perfil_dispositivos'=>DeviceProfile::get()
        );
        return view('dispositivos.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        try {
            $deviceIdBinary = DB::selectOne("SELECT decode(?, 'hex') as binary_value", [$request->dev_eui])->binary_value;
            $joinUuiBinary = DB::selectOne("SELECT decode(?, 'hex') as binary_value", [$request->join_eui])->binary_value;
           
            
            $dis=new Dispositivo();
            $dis->dev_eui=$deviceIdBinary;
            $dis->application_id=$request->application_id;
            $dis->device_profile_id=$request->device_profile_id;
            $dis->name=$request->nombre;
            $dis->description=$request->descripcion;
            $dis->external_power_source=false;
            $dis->enabled_class='A';
            $dis->skip_fcnt_check=false;
            $dis->is_disabled=$request->is_disabled?1:0;
            $dis->tags=json_encode(new \stdClass);
            $dis->variables=json_encode(new \stdClass);
            $dis->join_eui=$joinUuiBinary;
            $dis->save();

            return redirect()->route('dispositivos.index')->with('success',$dis->name.', ingresado exitosamente.!');
        } catch (\Throwable $th) {
            return back()->with('danger', 'Error.! '.$th->getMessage())->withInput();
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($dispositivoId)
    {
        
        try {
            $deviceIdBinary = DB::selectOne("SELECT decode(?, 'hex') as binary_value", [$dispositivoId])->binary_value;
            $nwk_keyBinary=DB::selectOne("SELECT decode(?, 'hex') as binary_value", ['5572404c696e6b4c6f52613230313823'])->binary_value;
            $app_keyBinary=DB::selectOne("SELECT decode(?, 'hex') as binary_value", ['00000000000000000000000000000000'])->binary_value;
            $d_k=new DeviceKeys();
            $d_k->dev_eui=$deviceIdBinary;
            $d_k->nwk_key=$nwk_keyBinary;
            $d_k->app_key=$app_keyBinary;
            $d_k->dev_nonces=json_encode(new \stdClass);
            $d_k->join_nonce=1;
            $d_k->save();
            return redirect()->route('dispositivos.index')->with('success','Clave, ingresado exitosamente.!');
        } catch (\Throwable $th) {
            return back()->with('danger', 'Error.! '.$th->getMessage())->withInput();
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dispositivo $dispositivo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dispositivo $dispositivo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($deviceId)
    {      
    
        try {    
            Dispositivo::where('dev_eui', DB::raw("decode('$deviceId', 'hex')"))->delete();
            return redirect()->route('dispositivos.index')->with('success','Dispositivo eliminado.!');
        } catch (\Throwable $th) {
            return redirect()->route('dispositivos.index')->with('warning','Gateway no eliminado.!'.$th->getMessage());
        }
    }
}
