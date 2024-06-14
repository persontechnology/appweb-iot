<?php

namespace App\Http\Controllers;

use App\DataTables\UsuariosDataTable;
use App\Models\TenantUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UsuariosDataTable $dataTable)
    {
        return $dataTable->render('usuarios.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Buscar o crear un usuario con el correo electrónico proporcionado

            $user=User::where('email',$request->email)->first();

            if(!$user){
                $user=new User();
                $user->email = $request->email;
                $user->is_admin = false;
                $user->is_active = false;
                $user->email_verified = true;
                $user->password_hash = Hash::make($request->contrasena);
                $user->note = $request->descripcion;
                $user->password = Hash::make($request->contrasena);
                $user->apellidos = $request->apellidos;
                $user->nombres = $request->nombres;
                $user->identificacion = $request->identificacion;
                $user->tenant_id=Auth::user()->tenant_id;
                $user->save();
            }

            // Verificar si ya existe una relación tenantUser para este usuario en el mismo tenant
            $tenantUser = TenantUser::firstOrNew([
                'tenant_id' => Auth::user()->tenant_id,
                'user_id' => $user->id
            ]);

            if (!$tenantUser->exists) {
                // Si no existe, guardar la nueva relación tenantUser
                $tenantUser->is_admin = true;
                $tenantUser->is_device_admin = false;
                $tenantUser->is_gateway_admin = false;
                $tenantUser->save();
            }

            return redirect()->route('usuarios.index')->with('success', $user->nombres . ', ingresado exitosamente.!');
        } catch (\Throwable $th) {
            return back()->with('danger', 'Error.! ' . $th->getMessage())->withInput();
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
        $tenantUser = TenantUser::firstOrNew([
            'tenant_id' => Auth::user()->tenant_id,
            'user_id' => $userId
        ]);

        Gate::authorize('editar',$tenantUser);
        $data = array('user'=>$tenantUser->user);
        return view('usuarios.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $userId)
    {
        $tenantUser = TenantUser::firstOrNew([
            'tenant_id' => Auth::user()->tenant_id,
            'user_id' => $userId
        ]);
        
        try {
            $user=$tenantUser->user;
            $user->is_active=$request->esta_activo?1:0;
            $user->email=$request->email;
            $user->note=$request->descripcion;

            if($request->contrasena){
                $user->password=Hash::make($request->contrasena);
            }
            
            $user->apellidos=$request->apellidos;
            $user->nombres=$request->nombres;
            $user->identificacion=$request->identificacion;
            $user->save();
            return redirect()->route('usuarios.index')->with('success',$user->nombres.', actualizado exitosamente.!');
        } catch (\Throwable $th) {
            return back()->with('danger', 'Error.! '.$th->getMessage())->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     */
   
    public function destroy($user_id)
    {

        try {
            // Ejecutar una consulta SQL para eliminar la entrada en la tabla tenant_user
            $deleted = DB::table('tenant_user')
                ->where('tenant_id', Auth::user()->tenant_id)
                ->where('user_id', $user_id)
                ->delete();

            if ($deleted) {
                return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente');
            } else {
                return back()->with('danger', 'No se pudo eliminar el usuario');
            }
        } catch (\Throwable $th) {
            return back()->with('danger', 'Usuario no eliminado, ' . $th->getMessage());
        }
    }


}
