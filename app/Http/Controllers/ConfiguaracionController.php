<?php

namespace App\Http\Controllers;

use App\DataTables\ClienteDataTable;
use App\Models\Application;
use App\Models\Configuracion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ConfiguaracionController extends Controller
{

    public function __construct()
    {
        $this->middleware(['role:ADMINISTRADOR']);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $configuraciones= Configuracion::orderBy('valor');
        
        if($request->has('application_id')&& isset($request->application_id)){
            $configuraciones=$configuraciones->where('application_id',$request->application_id);
        }
        $configuraciones=$configuraciones->get();
        return view('configuraciones.index',['configuraciones'=>$configuraciones]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $application=Application::find($request->application_id);
        return $application;
        $request->validate([
            'valor' => 'required|integer|min:0|max:100',
            'descripcion' => 'required|string|max:255',
            'color' => 'required|string',
            'notification' => 'boolean',
        ]);
        try {
            $configuracion=new Configuracion();
            $configuracion->valor=$request->valor;
            $configuracion->descripcion=$request->descripcion;
            $configuracion->color=$request->color;
            $configuracion->notification=$request->notification;
            $configuracion->save();

            return redirect()->route('configuraciones.index')->with('success',$configuracion->valor.', ingresado exitosamente.!');
        } catch (\Throwable $th) {
            return back()->with('danger', 'Error.! '.$th->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($userId)
    {
        $data = array(
            'user'=>User::find($userId)
        );
        return view('clientes.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $userId)
    {
        $request->validate([
            'email'=>'required|email|string|max:255|unique:user,email,'.$userId
        ]);


        $user=User::find($userId);
        try {
            
            $user->is_admin=$request->es_administrador?1:0;
            $user->is_active=$request->esta_activo?1:0;
            $user->email=$request->email;
            // $user->email_verified=true;
            // $user->password_hash=Hash::make($request->contrasena);
            $user->note=$request->descripcion;

            if($request->contrasena){
                $user->password=Hash::make($request->contrasena);
            }
            
            $user->apellidos=$request->apellidos;
            $user->nombres=$request->nombres;
            $user->identificacion=$request->identificacion;
            $user->save();

            if($request->es_administrador){
                $user->syncRoles('ADMINISTRADOR');
            }else{
                $user->removeRole('ADMINISTRADOR');
            }

            return redirect()->route('clientes.index')->with('success',$user->nombres.', actualizado exitosamente.!');
        } catch (\Throwable $th) {
            return back()->with('danger', 'Error.! '.$th->getMessage())->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($userId)
    {
        try {
            $user=User::destroy($userId);
            return redirect()->route('clientes.index')->with('success','Usuario eliminado exitosamente');
        } catch (\Throwable $th) {
            return back()->with('danger','Usuario no eliminado, '.$th->getMessage());
        }
    }
}
