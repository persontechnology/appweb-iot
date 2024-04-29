<?php

namespace App\Http\Controllers;

use App\DataTables\tenant\UserTenantNoAsignadosDataTable;
use App\DataTables\TenantDataTable;
use App\DataTables\TenantUserDataTable;
use App\DataTables\Tenat\UserDataTable;
use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\User;
use Illuminate\Http\Request;

class TenantController extends Controller
{

    public function __construct()
    {
        $this->middleware(['role:ADMINISTRADOR']);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(TenantDataTable $dataTable)
    {
        
        return $dataTable->render('tenant.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return view('tenant.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'=>'required|string|max:255|unique:tenant,name'
        ]);
        try{
            $tena=new Tenant();
            $tena->name=$request->nombre;
            $tena->description=$request->descripcion;
            $tena->max_device_count=$request->maximo_dispositivos_permitidos;
            $tena->max_gateway_count=$request->max_gateway_permitidos;
            $tena->can_have_gateways=true;
            $tena->private_gateways_up=false;
            $tena->private_gateways_down=false;
            $tena->tags=json_encode(new \stdClass);
            $tena->save();
        return redirect()->route('inquilinos.index')->with('success',$tena->name.', ingresado exitosamente.!');
        } catch (\Throwable $th) {
            return back()->with('danger', 'Error.! '.$th->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($tenantId)
    {
        $tenant= Tenant::find($tenantId);
        $data = array(
            'tenant'=>$tenant
        );
        return view('tenant.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $tenantId)
    {
        $request->validate([
            'nombre'=>'required|string|max:255|unique:tenant,name,'.$tenantId
        ]);

        $tena= Tenant::find($tenantId);
        
        try{
            
            $tena->name=$request->nombre;
            $tena->description=$request->descripcion;
            $tena->max_device_count=$request->maximo_dispositivos_permitidos;
            $tena->max_gateway_count=$request->max_gateway_permitidos;
            $tena->save();
        return redirect()->route('inquilinos.index')->with('success',$tena->name.', actualizado exitosamente.!');
        } catch (\Throwable $th) {
            return back()->with('danger', 'Error.! '.$th->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($tenantId)
    {
        try {
            Tenant::find($tenantId)->delete();
            return redirect()->route('inquilinos.index')->with('success','Usuario eliminado.!');
        } catch (\Throwable $th) {
            return back()->with('danger', 'Error.! '.$th->getMessage())->withInput();
        }
    }


    public function clientes(TenantUserDataTable $tenantUserDatatable,UserTenantNoAsignadosDataTable $tenantNoUserDatatable,$tenantId)
    {
        $tenantConUsers = Tenant::with('users')->findOrFail($tenantId);
        // $UserIds = $tenantConUsers->users->pluck('id')->toArray();
        // $usuariosNoAsignados = User::whereNotIn('id', $UserIds)->get();
        $data = array(
            'tenant'=>$tenantConUsers,
            'tenantUserDatatable'=>$tenantUserDatatable,
            'tenantNoUserDatatable'=>$tenantNoUserDatatable
            
        );
        
        if(request()->get('table')=='tenantUserDatatable'){
            return $tenantUserDatatable->with('tenantId',$tenantConUsers->id)->render('tenant.clientes.index',$data);
        }


        return $tenantNoUserDatatable->with('tenantId',$tenantConUsers->id)->render('tenant.clientes.index',$data);
    }

    public function clientesAsignar(Request $request) {
        
        
        if($request->user){
            foreach ($request->user as $user) {
                $tu=new TenantUser();
                $tu->tenant_id=$request->tenant_id;
                $tu->user_id=$user;
                $tu->is_admin=true;
                $tu->is_device_admin=false;
                $tu->is_gateway_admin=false;
                $tu->save();
                $tu->user->tenant_id=$tu->tenant_id;
                $tu->user->save();

            }

            return redirect()->route('inquilinos.clientes',$request->tenant_id)->with('success','Cliente asignados exisosamente');
        }else{
            return redirect()->route('inquilinos.clientes',$request->tenant_id)->with('info','Clientes no asignados porque no seleciono ninguno');
        }

    }

    public function clientesEliminar($tenantId,$userId) {
        try {    
            TenantUser::where(['tenant_id'=>$tenantId,'user_id'=>$userId,])->delete();
            return redirect()->route('inquilinos.clientes',$tenantId)->with('success','Usuario eliminado.!');
        } catch (\Throwable $th) {
            return back()->with('danger', 'Error.! '.$th->getMessage())->withInput();
        }
    }
    


}
