<?php

namespace App\Http\Controllers;

use App\DataTables\GatewayDataTable;
use App\Events\GatewayDataUpdated;
use App\Models\CategoriaGateway;
use App\Models\Gateway;
use Illuminate\Http\Request;

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
        $cg=CategoriaGateway::get();
        $data = array(
            'categoriaGateway'=>$cg
        );
        
        return view('gateway.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'=>'required',
            'modelo'=>'required',
            'fcc_id'=>'required',
            'direccion_ip'=>'required',
            'usuario'=>'required',
            'contrasena'=>'required',
            'imei'=>'required',
            'mac'=>'required',
            'foto'=>'nullable',
            // 'estado'=>'required',
            // 'conectado'=>'required',
            // 'lat'=>'required',
            // 'lng'=>'required',
            'descripcion'=>'required',
            'categoria_gateway'=>'required',
        ]);

        $request['categoria_gateway_id']=$request->categoria_gateway;
        $request['password']=$request->contrasena;
        $gateway=Gateway::create($request->except(['categoria_gateway','contrasena']));
        event(new GatewayDataUpdated($gateway));
        return redirect()->route('gateway.index')->with('succes',$gateway->nombre.', ingresado exitosamente.!');
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
    public function destroy(Gateway $gateway)
    {
        //
    }

    public function updateAction(Request $request, Gateway $gateway)
    {
        $gateway->conectado="NO";
        $gateway->save();
        event(new GatewayDataUpdated($gateway));
        return "ok";
    }
}
