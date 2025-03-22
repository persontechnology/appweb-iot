<?php

namespace App\Http\Controllers;

use App\DataTables\DeviceProfileDataTable;
use App\Models\DeviceProfile;
use App\Models\Lectura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DeviceProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(DeviceProfileDataTable $dataTable)
    {
        return $dataTable->render('device-profile.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('device-profile.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $dp = new DeviceProfile();
            $dp->tenant_id = Auth::user()->tenant_id;
            $dp->name = $request->nombre;
            $dp->region = $request->region;
            $dp->mac_version = $request->mac_version;
            $dp->reg_params_revision = $request->revision_parametros_regionales;
            $dp->adr_algorithm_id = 'default';
            $dp->payload_codec_runtime = 'JS';
            $dp->uplink_interval = $request->intervalo_enlace;
            $dp->device_status_req_interval = $request->intervalo_estadisticas;
            $dp->supports_otaa = true;
            $dp->supports_class_b = false;
            $dp->supports_class_c = false;
            $dp->class_b_timeout = 0;
            $dp->class_b_ping_slot_nb_k = 0;
            $dp->class_b_ping_slot_dr = 0;
            $dp->class_b_ping_slot_freq = 0;
            $dp->class_c_timeout = 0;
            $dp->abp_rx1_delay = 0;
            $dp->abp_rx1_dr_offset = 0;
            $dp->abp_rx2_dr = 0;
            $dp->abp_rx2_freq = 0;
            $dp->tags = json_encode(new \stdClass);
            $dp->payload_codec_script = $request->payload_codec_script;
            $dp->flush_queue_on_activate = true;
            $dp->description = $request->descripcion;
            $dp->measurements = json_encode(new \stdClass);
            $dp->auto_detect_measurements = true;
            $dp->region_config_id = null;
            $dp->is_relay = false;
            $dp->is_relay_ed = false;
            $dp->relay_ed_relay_only = false;
            $dp->relay_enabled = false;
            $dp->relay_cad_periodicity = 0;
            $dp->relay_default_channel_index = 0;
            $dp->relay_second_channel_freq = 0;
            $dp->relay_second_channel_dr = 0;
            $dp->relay_second_channel_ack_offset = 0;
            $dp->relay_ed_activation_mode = 0;
            $dp->relay_ed_smart_enable_level = 0;
            $dp->relay_ed_back_off = 0;
            $dp->relay_ed_uplink_limit_bucket_size = 0;
            $dp->relay_ed_uplink_limit_reload_rate = 0;
            $dp->relay_join_req_limit_reload_rate = 0;
            $dp->relay_notify_limit_reload_rate = 0;
            $dp->relay_global_uplink_limit_reload_rate = 0;
            $dp->relay_overall_limit_reload_rate = 0;
            $dp->relay_join_req_limit_bucket_size = 0;
            $dp->relay_notify_limit_bucket_size = 0;
            $dp->relay_global_uplink_limit_bucket_size = 0;
            $dp->relay_overall_limit_bucket_size = 0;
            $dp->rx1_delay = 0;
            $dp->allow_roaming = false;
            $dp->save();

            return redirect()->route('perfil-dispositivos.index')->with('success', $dp->name . ' ingresado exitosamente');
        } catch (\Throwable $th) {
            return back()->with('danger', $th->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DeviceProfile $deviceProfile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($deviceProfileId)
    {
        $deviceProfile = DeviceProfile::find($deviceProfileId);
        Gate::authorize('editar', $deviceProfile);
        $data = array(
            'dp' => $deviceProfile
        );

        return view('device-profile.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $deviceProfileId)
    {
        $dp = DeviceProfile::find($deviceProfileId);
        Gate::authorize('editar', $dp);
        try {
            $dp->name = $request->nombre;
            $dp->region = $request->region;
            $dp->mac_version = $request->mac_version;
            $dp->reg_params_revision = $request->revision_parametros_regionales;
            $dp->uplink_interval = $request->intervalo_enlace;
            $dp->device_status_req_interval = $request->intervalo_estadisticas;
            $dp->payload_codec_script = $request->payload_codec_script;
            $dp->description = $request->descripcion;
            $dp->save();

            return redirect()->route('perfil-dispositivos.index')->with('success', $dp->name . ' actualizado exitosamente');
        } catch (\Throwable $th) {
            return back()->with('danger', $th->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($deviceProfileId)
    {

        try {
            $dp = DeviceProfile::find($deviceProfileId);
            Gate::authorize('eliminar', $dp);
            $dp->delete();
            return redirect()->route('perfil-dispositivos.index')->with('success', 'Perfil de dispositivo eliminado.!');
        } catch (\Throwable $th) {
            return redirect()->route('perfil-dispositivos.index')->with('danger', 'Gateway no eliminado.!' . $th->getMessage());
        }
    }
  
}
