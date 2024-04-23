<?php

namespace App\Http\Controllers;

use App\DataTables\GatewayDataTable;
use App\Models\Gateway;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class GatewayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GatewayDataTable $dataTable)
    {
        //  $tenant=Tenant::find(Auth::user()->tenant_id);
        //  return $tenant->gateways->count();
        return $dataTable->render('gateway.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = array(
            'tenants'=>Tenant::get()
        );
        return view('gateway.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'gateway_id' => [
                'required',
                'regex:/^[0-9a-fA-F]{16}$/',
                function ($attribute, $value, $fail) {
                    $binaryValue = DB::selectOne("SELECT decode(?, 'hex') as binary_value", [$value])->binary_value;
                    $exists = Gateway::where('gateway_id', $binaryValue)->exists();
                    if ($exists) {
                        $fail("El gateway ID (EUI64) ya existe en la tabla gateways.");
                    }
                }
            ],
            'latitude' => 'required',
            'longitude' => 'required'
        ], [
            'gateway_id.required' => 'El EUI64 es obligatorio.',
            'gateway_id.regex' => 'El EUI64 debe ser un valor hexadecimal de 16 caracteres.',
            'latitude.required' => 'Debe seleccionar la ubicación del gateway en el mapa.',
            'longitude.required' => 'Debe seleccionar la ubicación del gateway en el mapa.',
        ]);
    


        try {
            $gateway=new Gateway();
            $gateway->gateway_id=$request->gateway_id;
            $gateway->tenant_id = Auth::user()->tenant_id;
            $gateway->name=$request->nombre;
            $gateway->description=$request->descripcion;
            $gateway->latitude=$request->latitude;
            $gateway->longitude=$request->longitude;
            $gateway->altitude=0;
            $gateway->stats_interval_secs=$request->intervalo_estadisticas;
            $gateway->tags=json_encode(new \stdClass);
            $gateway->properties=json_encode(new \stdClass);
            $gateway->save();
            return redirect()->route('gateways.index')->with('success',$gateway->name.', ingresado exitosamente.!');
        } catch (\Throwable $th) {
            return back()->with('danger', 'Error.! '.$th->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Gateway $gateway)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($gatewayId)
    {
        $gateway=Gateway::where('gateway_id', DB::raw("decode('$gatewayId', 'hex')"))->first();
        $tenants=Tenant::get();
        Gate ::authorize('actualizar', $gateway);
        $data = array(
            'gateway'=>$gateway,
            // 'tenants'=>$tenants,
            'gateway_id_text'=>$gateway->gateway_id
        );
        return view('gateway.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $gatewayId)
    {
        
        
        try {
            $gateway = Gateway::where('gateway_id', DB::raw("decode('$gatewayId', 'hex')"))->firstOrFail();
            Gate ::authorize('actualizar', $gateway);
            // $gateway->tenant_id = $request->tenant_id;
            $gateway->name=$request->nombre;
            $gateway->description=$request->descripcion;
            $gateway->stats_interval_secs=$request->intervalo_estadisticas;
             $gateway->latitude=$request->latitude;
            $gateway->longitude=$request->longitude;
            $gateway->save();
            return redirect()->route('gateways.index')->with('success','Gateway actualizado.!');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($gatewayId)
    {       
        try {    
            $gateway = Gateway::where('gateway_id', DB::raw("decode('$gatewayId', 'hex')"))->firstOrFail();
            Gate ::authorize('eliminar', $gateway);
            Gateway::where('gateway_id', DB::raw("decode('$gatewayId', 'hex')"))->delete();
            return redirect()->route('gateways.index')->with('success','Gateway eliminado.!');
        } catch (\Throwable $th) {
            return redirect()->route('gateways.index')->with('warning','Gateway no eliminado.!'.$th->getMessage());
        }
    }
}
