<?php

namespace App\Http\Controllers;

use App\DataTables\DispositivoDataTable;
use App\Models\Application;
use App\Models\DeviceKeys;
use App\Models\DeviceProfile;
use App\Models\Dispositivo;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DispositivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(DispositivoDataTable $dataTable)
    {
        

        return $dataTable->render('dispositivos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tenant=Tenant::find(Auth::user()->tenant_id);
        $data = array(
            'aplicaciones'=>$tenant->applications,
            'perfil_dispositivos'=>$tenant->deviceProfiles
        );
        return view('dispositivos.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        
        $tenant=Tenant::find(Auth::user()->tenant_id);
        $misApplicationsIds = $tenant->applications->pluck('id')->toArray();
        $misDeviceProfileIds = $tenant->deviceProfiles->pluck('id')->toArray();

        $request->validate([
            'application_id' => 'required|in:' . implode(',', $misApplicationsIds),
            'device_profile_id' => 'required|in:' . implode(',', $misDeviceProfileIds),
            'dev_eui' => [
                'required',
                'regex:/^[0-9a-fA-F]{16}$/',
                function ($attribute, $value, $fail) {
                    $binaryValue = DB::selectOne("SELECT decode(?, 'hex') as binary_value", [$value])->binary_value;
                    $exists = Dispositivo::where('dev_eui', $binaryValue)->exists();
                    if ($exists) {
                        $fail("El dispositivo ID (EUI64) ya esta registrado.");
                    }
                }
            ],
            'join_eui' => [
                'required',
                'regex:/^[0-9a-fA-F]{16}$/'
            ]
        ]);

        try {
            $dis=new Dispositivo();
            $dis->dev_eui=$request->dev_eui;
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
            $dis->join_eui=$request->join_eui;
            $dis->latitude=$request->latitude;
            $dis->longitude=$request->longitude;
            $dis->save();
            $this->crearClaveApplicacion($request->dev_eui,$request->nwk_key);
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
      return $dispositivoId;

    }

    public function crearClaveApplicacion($dispositivoId,$nwk_key)
    {
        
        
        try {
            $deviceIdBinary = DB::selectOne("SELECT decode(?, 'hex') as binary_value", [$dispositivoId])->binary_value;
            $nwk_keyBinary=DB::selectOne("SELECT decode(?, 'hex') as binary_value", [$nwk_key])->binary_value;
            $app_keyBinary=DB::selectOne("SELECT decode(?, 'hex') as binary_value", ['00000000000000000000000000000000'])->binary_value;
            $d_k=new DeviceKeys();
            $d_k->dev_eui=$deviceIdBinary;
            $d_k->nwk_key=$nwk_keyBinary;
            $d_k->app_key=$app_keyBinary;
            $d_k->dev_nonces=json_encode(new \stdClass);
            $d_k->join_nonce=1;
            $d_k->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }

    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($dispositivoId)
    {
        $dispositivo=Dispositivo::where('dev_eui', DB::raw("decode('$dispositivoId', 'hex')"))->first();
        
        Gate::authorize('editar',$dispositivo);

        $dk=DeviceKeys::where('dev_eui', DB::raw("decode('$dispositivoId', 'hex')"))->first();
        
        $tenant=Tenant::find(Auth::user()->tenant_id);
        $data = array(
            'aplicaciones'=>$tenant->applications,
            'perfil_dispositivos'=>$tenant->deviceProfiles,
            'dis'=>$dispositivo,
            'nwk_key'=>$dk->nwk_key,
            'dev_eui'=>$dispositivo->dev_eui
        );
        return view('dispositivos.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $dispositivoId)
    {
        $dis=Dispositivo::where('dev_eui', DB::raw("decode('$dispositivoId', 'hex')"))->first();
        Gate::authorize('editar',$dis);
        $tenant=Tenant::find(Auth::user()->tenant_id);
        $misApplicationsIds = $tenant->applications->pluck('id')->toArray();
        $misDeviceProfileIds = $tenant->deviceProfiles->pluck('id')->toArray();
        $request->validate([
            'application_id' => 'required|in:' . implode(',', $misApplicationsIds), 
            'device_profile_id' => 'required|in:' . implode(',', $misDeviceProfileIds),
            'join_eui' => [
                'required',
                'regex:/^[0-9a-fA-F]{16}$/'
            ]
        ]);
        
        try {
            
            $dis->application_id=$request->application_id;
            $dis->device_profile_id=$request->device_profile_id;
            $dis->name=$request->nombre;
            $dis->description=$request->descripcion;
            $dis->is_disabled=$request->is_disabled?1:0;
            $dis->join_eui=$request->join_eui;
            $dis->latitude=$request->latitude;
            $dis->longitude=$request->longitude;
            $dis->save();
            
            $this->actualizarClaveApplicacion($dispositivoId,$request->nwk_key);
            return redirect()->route('dispositivos.index')->with('success',$dis->name.', actualizado exitosamente.!');
        } catch (\Throwable $th) {
            return back()->with('danger', 'Error.! '.$th->getMessage())->withInput();
        }


    }

    public function actualizarClaveApplicacion($dispositivoId,$nwk_key)
    {
        
        try {
            $nwk_keyBinary=DB::selectOne("SELECT decode(?, 'hex') as binary_value", [$nwk_key])->binary_value;
            $d_k=DeviceKeys::where('dev_eui', DB::raw("decode('$dispositivoId', 'hex')"))->first();
            $d_k->nwk_key=$nwk_keyBinary;
            $d_k->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($deviceId)
    {      
    
        try {    
            $dis=Dispositivo::where('dev_eui', DB::raw("decode('$deviceId', 'hex')"))->first();
            Gate::authorize('eliminar',$dis);
            Dispositivo::where('dev_eui', DB::raw("decode('$deviceId', 'hex')"))->delete();
            return redirect()->route('dispositivos.index')->with('success','Dispositivo eliminado.!');
        } catch (\Throwable $th) {
            return redirect()->route('dispositivos.index')->with('warning','Gateway no eliminado.!'.$th->getMessage());
        }
    }
}
