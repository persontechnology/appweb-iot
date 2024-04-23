<?php

namespace App\Http\Controllers;

use App\DataTables\ApplicationDataTable;
use App\Events\LecturaGuardadoEvent;
use App\Models\Application;
use App\Models\ApplicationIntegration;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;



class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ApplicationDataTable $dataTable)
    {
        return $dataTable->render('aplicaciones.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = array(
            'tenants'=>Tenant::get()
        );
        return view('aplicaciones.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'nombre' => [
                'required',
                Rule::unique('application','name')->where(function ($query) use ($request) {
                    return $query->where('tenant_id', Auth::user()->tenant_id);
                }),
            ]
        ]);
        

        try {
            $aplication=new Application();
            $aplication->name=$request->nombre;
            $aplication->description=$request->descripcion;
            $aplication->tenant_id=Auth::user()->tenant_id;
            $aplication->tags=json_encode(new \stdClass);
            $aplication->mqtt_tls_cert=null;
            $aplication->save();

            $this->crearIntegracionAplicacion($aplication->id);
            

            return redirect()->route('applicaciones.index')->with('success',$aplication->name.', ingresado exitosamente.!');
        } catch (\Throwable $th) {
            return back()->with('danger', 'Error.! '.$th->getMessage());
        }
    }


    public function crearIntegracionAplicacion($applicationId){
        try {
            $ai=new ApplicationIntegration();
            $ai->application_id=$applicationId;
            $ai->kind='Http';
            $ai->configuration = [
                "Http" => [
                    "json" => true,
                    "headers" => (object) [],
                    "event_endpoint_url" => url('/')."/api/sensor-data"
                ]
            ];
            $ai->save();
            return true;
            // return redirect()->route('applicaciones.index')->with('success','Integración de aplicación, ingresado exitosamente.!');
        } catch (\Throwable $th) {
            // return back()->with('danger', 'Error.! '.$th->getMessage());
            return false;
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($applicationId)
    {
        
        return $applicationId;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($applicationId)
    {
        $application= Application::find($applicationId);
        Gate ::authorize('editar', $application);
        $data = array(
            'application'=>$application,
            'tenants'=>Tenant::get()
        );

        return view('aplicaciones.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $applicationId)
    {
        $request->validate([
            // 'tenant_id' => 'required',
            'nombre' => [
                'required',
                Rule::unique('application', 'name')->ignore($applicationId)->where(function ($query) use ($request) {
                    return $query->where('tenant_id', Auth::user()->tenant_id);
                }),
            ],
        ]);

        
        $aplication= Application::find($applicationId);
        Gate ::authorize('editar', $aplication);
        try {
            $aplication->name=$request->nombre;
            $aplication->description=$request->descripcion;
            // $aplication->tenant_id=$request->tenant_id;
            $aplication->save();

            return redirect()->route('applicaciones.index')->with('success',$aplication->name.', actualizado exitosamente.!');
        } catch (\Throwable $th) {
            return back()->with('danger', 'Error.! '.$th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($applicationId)
    {
        try {    
            $aplication= Application::find($applicationId);
            Gate ::authorize('eliminar', $aplication);
            Application::find($applicationId)->delete();
            return redirect()->route('applicaciones.index')->with('success','Aplicación eliminado.!');
        } catch (\Throwable $th) {
            return redirect()->route('applicaciones.index')->with('warning','Aplicación no eliminado.!'.$th->getMessage());
        }
    }
}
