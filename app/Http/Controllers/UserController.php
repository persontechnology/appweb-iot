<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(['role:ADMINISTRADOR']);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(UserDataTable $dataTable)
    {
        
        return $dataTable->render('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email'=>'required|email|string|max:255|unique:user,email'
        ]);
        
        try {
            $user=new User();
            $user->is_admin=$request->es_administrador?1:0;
            $user->is_active=$request->esta_activo?1:0;
            $user->email=$request->email;
            $user->email_verified=true;
            $user->password_hash=Hash::make($request->contrasena);
            $user->note=$request->descripcion;
            $user->password=Hash::make($request->contrasena);
            $user->apellidos=$request->apellidos;
            $user->nombres=$request->nombres;
            $user->identificacion=$request->identificacion;
            $user->save();
            
            if($request->es_administrador){
                $user->assignRole('ADMINISTRADOR');
            }

            return redirect()->route('usuarios.index')->with('success',$user->nombres.', ingresado exitosamente.!');
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
        return view('users.edit',$data);
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
            if($request->password){
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

            return redirect()->route('usuarios.index')->with('success',$user->nombres.', actualizado exitosamente.!');
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
            return redirect()->route('usuarios.index')->with('success','Usuario eliminado exitosamente');
        } catch (\Throwable $th) {
            return back()->with('danger','Usuario no eliminado, '.$th->getMessage());
        }
    }
}
