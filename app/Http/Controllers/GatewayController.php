<?php

namespace App\Http\Controllers;

use App\DataTables\GatewayDataTable;
use App\Models\Gateway;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class GatewayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GatewayDataTable $dataTable)
    {
        
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
        try {
            $gateway=new Gateway();
            $gatewayIdBinary = DB::selectOne("SELECT decode(?, 'hex') as binary_value", [$request->gateway_id])->binary_value;
            $gateway->gateway_id=$gatewayIdBinary;
            $gateway->tenant_id = $request->tenant_id;
            $gateway->name=$request->nombre;
            $gateway->description=$request->descripcion;
            $gateway->latitude=0;
            $gateway->longitude=0;
            $gateway->altitude=0;
            $gateway->stats_interval_secs=$request->intervalo_estadisticas;
            $gateway->tags=json_encode(new \stdClass);
            $gateway->properties=json_encode(new \stdClass);
            $gateway->save();
            return redirect()->route('gateways.index')->with('success',$gateway->name.', ingresado exitosamente.!');
        } catch (\Throwable $th) {
            return back()->with('danger', 'Error.! '.$th->getMessage());
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
    public function edit(Gateway $gateway)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gateway $gateway)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($gatewayId)
    {       
        try {    
            Gateway::where('gateway_id', DB::raw("decode('$gatewayId', 'hex')"))->delete();
            return redirect()->route('gateways.index')->with('success','Gateway eliminado.!');
        } catch (\Throwable $th) {
            return redirect()->route('gateways.index')->with('warning','Gateway no eliminado.!'.$th->getMessage());
        }
    }
}
