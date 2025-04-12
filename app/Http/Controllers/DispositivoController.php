<?php

namespace App\Http\Controllers;

use App\DataTables\DispositivoDataTable;
use App\Models\Application;
use App\Models\DeviceKeys;
use App\Models\DeviceProfile;
use App\Models\Dispositivo;
use App\Models\Lectura;
use App\Models\PuntosLocalizacion;
use App\Models\SensorData;
use App\Models\Tenant;
use App\Models\TipoDispositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class DispositivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(DispositivoDataTable $dataTable)
    {

        // $lectura=Lectura::first();
        // $dispo= $lectura->dipositivoXlecturaId($lectura->id);
        // return $dispo;

        return $dataTable->render('dispositivos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tenant = Tenant::find(Auth::user()->tenant_id);
        $data = array(
            'tipoDispositivos' => TipoDispositivo::get(),
            'aplicaciones' => $tenant->applications,
            'perfil_dispositivos' => $tenant->deviceProfiles
        );
        return view('dispositivos.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $tenant = Tenant::find(Auth::user()->tenant_id);
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
            ],
            'tipo_dispositivo' => 'required'
        ]);

        try {
            $dis = new Dispositivo();
            $dis->dev_eui = $request->dev_eui;
            $dis->application_id = $request->application_id;
            $dis->device_profile_id = $request->device_profile_id;
            $dis->name = $request->nombre;
            $dis->description = $request->descripcion;
            $dis->external_power_source = false;
            $dis->enabled_class = 'A';
            $dis->skip_fcnt_check = false;
            $dis->is_disabled = $request->is_disabled ? 1 : 0;
            $dis->use_tracking = $request->use_tracking ? 1 : 0;
            $dis->tags = json_encode(new \stdClass);
            $dis->variables = json_encode(new \stdClass);
            $dis->join_eui = $request->join_eui;
            $dis->latitude = $request->latitude;
            $dis->longitude = $request->longitude;
            $dis->battery_alert_level = $request->battery_alert_level;

            // $dis->type=$request->type;
            $dis->tipo_dispositivo_id = $request->tipo_dispositivo;
            $dis->save();

            $dis->save();
            $this->crearClaveApplicacion($request->dev_eui, $request->nwk_key);
            return redirect()->route('dispositivos.index')->with('success', $dis->name . ', ingresado exitosamente.!');
        } catch (\Throwable $th) {
            return back()->with('danger', 'Error.! ' . $th->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($dispositivoId)
    {
        return $dispositivoId;
    }

    public function crearClaveApplicacion($dispositivoId, $nwk_key)
    {


        try {
            $deviceIdBinary = DB::selectOne("SELECT decode(?, 'hex') as binary_value", [$dispositivoId])->binary_value;
            $nwk_keyBinary = DB::selectOne("SELECT decode(?, 'hex') as binary_value", [$nwk_key])->binary_value;
            $app_keyBinary = DB::selectOne("SELECT decode(?, 'hex') as binary_value", ['00000000000000000000000000000000'])->binary_value;
            $d_k = new DeviceKeys();
            $d_k->dev_eui = $deviceIdBinary;
            $d_k->nwk_key = $nwk_keyBinary;
            $d_k->app_key = $app_keyBinary;
            $d_k->dev_nonces = json_encode(new \stdClass);
            $d_k->join_nonce = 1;
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
        $dispositivo = Dispositivo::where('dev_eui', DB::raw("decode('$dispositivoId', 'hex')"))->first();

        Gate::authorize('editar', $dispositivo);

        $dk = DeviceKeys::where('dev_eui', DB::raw("decode('$dispositivoId', 'hex')"))->first();

        $tenant = Tenant::find(Auth::user()->tenant_id);
        $data = array(
            'aplicaciones' => $tenant->applications,
            'perfil_dispositivos' => $tenant->deviceProfiles,
            'dis' => $dispositivo,
            'nwk_key' => $dk->nwk_key,
            'dev_eui' => $dispositivoId,
            'tipoDispositivos' => TipoDispositivo::get()
        );
        return view('dispositivos.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $dev_eui)
    {

        $dis = Dispositivo::where('dev_eui', DB::raw("decode('$dev_eui', 'hex')"))->first();
        Log::info('DispositivoController@update', ['dis' => $dis]);
        Gate::authorize('editar', $dis);
        $tenant = Tenant::find(Auth::user()->tenant_id);
        $misApplicationsIds = $tenant->applications->pluck('id')->toArray();
        $misDeviceProfileIds = $tenant->deviceProfiles->pluck('id')->toArray();
        $request->validate([
            'application_id' => 'required|in:' . implode(',', $misApplicationsIds),
            'device_profile_id' => 'required|in:' . implode(',', $misDeviceProfileIds),
            'join_eui' => [
                'required',
                'regex:/^[0-9a-fA-F]{16}$/'
            ],
            'tipo_dispositivo' => 'required',
            'battery_alert_level' => 'required|numeric|min:0|max:100',
        ]);

        try {

            $dis = Dispositivo::where('dev_eui', DB::raw("decode('$dev_eui', 'hex')"))
                ->update([
                    'application_id' => $request->application_id,
                    'device_profile_id' => $request->device_profile_id,
                    'name' => $request->nombre,
                    'description' => $request->descripcion,
                    'is_disabled' => $request->is_disabled ? 1 : 0,
                    'use_tracking' => $request->use_tracking ? 1 : 0,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'battery_alert_level' => $request->battery_alert_level ?? 10,
                    'tipo_dispositivo_id' => $request->tipo_dispositivo
                ]);
            $this->actualizarClaveApplicacion($dev_eui, $request->nwk_key);
            return redirect()->route('dispositivos.index')->with('success', 'actualizado exitosamente.!');
        } catch (\Throwable $th) {
            return back()->with('danger', 'Error.! ' . $th->getMessage())->withInput();
        }
    }

    public function actualizarClaveApplicacion($dispositivoId, $nwk_key)
    {

        try {
            $nwk_keyBinary = DB::selectOne("SELECT decode(?, 'hex') as binary_value", [$nwk_key])->binary_value;
            $d_k = DeviceKeys::where('dev_eui', DB::raw("decode('$dispositivoId', 'hex')"))->first();
            $d_k->nwk_key = $nwk_keyBinary;
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
            $dis = Dispositivo::where('dev_eui', DB::raw("decode('$deviceId', 'hex')"))->first();
            Gate::authorize('eliminar', $dis);
            Dispositivo::where('dev_eui', DB::raw("decode('$deviceId', 'hex')"))->delete();
            return redirect()->route('dispositivos.index')->with('success', 'Dispositivo eliminado.!');
        } catch (\Throwable $th) {
            return redirect()->route('dispositivos.index')->with('warning', 'Gateway no eliminado.!' . $th->getMessage());
        }
    }
    /**
     * Buscar los puntos de localizacion.
     */
    public function showMap($dispositivoId)
    {
        $dispositivo = Dispositivo::where('dev_eui', DB::raw("decode('$dispositivoId', 'hex')"))->first();
        $dk = DeviceKeys::where('dev_eui', DB::raw("decode('$dispositivoId', 'hex')"))->first();
        $tenant = Tenant::find(Auth::user()->tenant_id);
        $puntosLocalizacion = PuntosLocalizacion::where('dev_eui', DB::raw("decode('$dispositivoId', 'hex')"))->where('tipo', 'LOCALIZACION')->limit(10)->orderBy('created_at', 'desc')->get();

        $data = array(
            'aplicaciones' => $tenant->applications,
            'perfil_dispositivos' => $tenant->deviceProfiles,
            'dis' => $dispositivo,
            'nwk_key' => $dk->nwk_key,
            'dev_eui' => $dispositivo->dev_eui,
            'puntos_Localizaciones' => $puntosLocalizacion,

        );
        return view('dispositivos.map', $data);
    }
    function lecturas(Request $request, $dispositivoId)
    {
        $dispositivo = Dispositivo::where('dev_eui', DB::raw("decode('$dispositivoId', 'hex')"))->first();

        $lecturas = Lectura::where('dev_eui', $dispositivoId);
        if ($request->orderBy) {
            $lecturas = $lecturas->orderBy('id', $request->orderBy);
        }
        if ($request->date) {
            $lecturas = $lecturas->whereDate('created_at', $request->date);
        }
        $lecturas = $lecturas->paginate($request->per_page ?? 10);
        return response()->json($lecturas);
    }
    function getLecturasNoconfiguradas(Request $request)
    {

        $lecturas = new SensorData();
        if ($request->orderBy) {
            $lecturas = $lecturas->orderBy('id', $request->orderBy);
        }
        if ($request->search) {
            $lecturas = $lecturas->where('data', 'like', '%"devEui":"' . $request->search . '"%');
        }
        if ($request->date) {
            $lecturas = $lecturas->whereDate('fecha', $request->date);
        }
        $lecturas = $lecturas->paginate($request->per_page ?? 10);
        return response()->json($lecturas);
    }
}
