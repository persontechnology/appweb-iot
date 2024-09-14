<?php

namespace App\Http\Controllers;

use App\DataTables\Alerta\LecturaDataTable;
use App\DataTables\Alerta\UsuariosDataTable;
use App\DataTables\AlertaDataTable;
use App\Models\Alerta;
use App\Models\AlertaTipo;
use App\Models\AlertaUser;
use App\Models\Application;
use App\Models\DeviceProfile;
use App\Models\Horario;
use App\Models\Tenant;
use App\Models\TipoDispositivo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class AlertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AlertaDataTable $dataTable)
    {
        return $dataTable->render('alertas.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tenant=Tenant::find(Auth::user()->tenant_id);
        $data = array(
            'aplicaciones'=>$tenant->applications,
            'tipoDispositivos'=>TipoDispositivo::get()
        );
        return view('alertas.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         
        $request->validate([
            'nombre' => [
                'required',
                Rule::unique('alertas', 'nombre')->where(function ($query) use ($request) {
                    return $query->where('application_id', $request->application_id);
                }),
            ],
            'application_id' => 'required',
            'tipo_dispositivos' => 'required|array', // Asegúrate de que se seleccionen dispositivos
            'tipo_dispositivos.*' => 'exists:tipo_dispositivos,id' // Asegúrate de que los dispositivos existan en la base de datos
        ]);
        
        $application=Application::find($request->application_id);
        Gate::authorize('crearAlerta',$application);

        try {
            DB::beginTransaction();
            $request['estado']=$request->estado?1:0;
            $request['puede_enviar_email']=$request->puede_enviar_email?1:0;
            $alerta=Alerta::create($request->all());
            $alerta->tipoDispositivos()->sync($request->input('tipo_dispositivos'));
            DB::commit();
            return redirect()->route('alertas.configuracion',['id'=>$alerta->id,'op'=>'inicio'])->with('success',$alerta->nombre.', ingresado exitosamente.!');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('danger', 'Error.! '.$th->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Alerta $alerta)
    {
        $data = array(
            'alerta'=>$alerta,
            'horarios'=>$alerta->horarios()->orderBy('id')->get()
        );
        return view('alertas.show',$data);
    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alerta $alerta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alerta $alerta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alerta $alerta)
    {
        try {
            $alerta->delete();
            return redirect()->route('alertas.index')->with('success','Alerta eliminado');
        } catch (\Throwable $th) {
            return redirect()->route('alertas.index')->with('danger','Alerta no eliminado '.$th->getMessage());
        }
    }


    public function actualizarHorario(Request $request)
    {
        $alerta=Alerta::find($request->alerta_id);
        Gate::authorize('editarHorario',$alerta);
        foreach ($request->horarios as $id => $datos) {
            $horario = Horario::findOrFail($id);

            // Verificar si el estado está presente y es verdadero
            if (isset($datos['estado'])) {
                // Si $datos['estado'] es un array, convertimos su valor a booleano
                $estado = is_array($datos['estado']) ? in_array('on', $datos['estado']) : (bool)$datos['estado'];

                // Si el estado es verdadero, validamos las horas de apertura y cierre
                if ($estado) {
                    
                    $request->validate([
                        "horarios.$id.hora_apertura" => 'required',
                        "horarios.$id.hora_cierre" => 'required',
                    ]);
                    
                    // Actualizar las horas de apertura y cierre
                    $horario->hora_apertura = $datos['hora_apertura'] ?? null;
                    $horario->hora_cierre = $datos['hora_cierre'] ?? null;
                }

                // Actualizar el estado del horario
                $horario->estado = $estado;
            } else {
                // Si el estado no está presente, establecemos el estado como false y las horas como nulas
                $horario->estado = false;
                $horario->hora_apertura = null;
                $horario->hora_cierre = null;
            }

            $horario->save();
        }

        return redirect()->route('alertas.configuracion',['id'=>$alerta->id,'op'=>'horario'])->with('success','Horario actualizado.!');
    }


    public function inicio(UsuariosDataTable $usuariosDataTable,LecturaDataTable $lecturaDatatable, $id,$op) {
        
        $alerta=Alerta::findOrFail($id);
        $data = array(
            'alerta'=>$alerta,
            'horarios'=>$alerta->horarios()->orderBy('numero_dia')->get(),
            'opcion'=>$op
        );

        switch ($op) {
            case 'inicio':
                return view('alertas.configuracion.inicio',$data);
                break;
            case 'horario':
                return view('alertas.configuracion.horario',$data);
                break;
            case 'usuarios':
                return $usuariosDataTable->with('alertaId',$id)->render('alertas.configuracion.usuarios',$data);
                break;
            case 'lecturas':
                return $lecturaDatatable->with('alertaId',$id)->render('alertas.configuracion.lecturas',$data);
                break;
            
            default:
                return abort(404);
                break;
        }
    }



    public function actualizarEstado(Request $request) {
        $alerta=Alerta::find($request->alerta_id);
        $alerta->estado=$request->estado?1:0;
        $alerta->puede_enviar_email=$request->puede_enviar_email?1:0;
        $alerta->save();
        return redirect()->route('alertas.configuracion',['id'=>$alerta->id,'op'=>'inicio'])->with('success','Estado de alerta actualizado.!');
    }


    public function actualizarUsuarios(Request $request)  {
        $request->validate([
            'usuarios' => 'nullable|array', // La entrada de usuarios puede ser un array o null
            'usuarios.*' => 'exists:user,id', // Verifica que cada usuario exista en la tabla usuarios
        ]);

        
        $alertaId = $request->input('alerta_id');

        $usuarios = $request->input('usuarios', []);
        foreach ($usuarios as $usuarioId) {
            // Verificar si ya existe un registro para este usuario y alerta_id en alerta_user
            $registroExistente = AlertaUser::where('user_id', $usuarioId)
                                            ->where('alerta_id', $alertaId)
                                            ->exists();
    
            // Si no existe, crear un nuevo registro
            if (!$registroExistente) {
                $alertaUser = new AlertaUser();
                $alertaUser->user_id = $usuarioId;
                $alertaUser->alerta_id = $alertaId;
                $alertaUser->save();
            }
        }

        return redirect()->route('alertas.configuracion',['id'=>$alertaId,'op'=>'usuarios'])->with('success','Usuarios actualizado.!');
    }


    public function eliminarUsuario($alertaId,$userId)  {
        $mensaje='Usuario eliminado.!';
        try {
            $alertaUser=AlertaUser::where(['alerta_id'=>$alertaId,'user_id'=>$userId])->first();
            $alertaUser->delete();
        } catch (\Throwable $th) {
            $mensaje=$th->getMessage();
        }
        return redirect()->route('alertas.configuracion',['id'=>$alertaId,'op'=>'usuarios'])->with('success',$mensaje);
    }





}
