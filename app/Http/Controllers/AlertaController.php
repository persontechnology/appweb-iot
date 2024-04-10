<?php

namespace App\Http\Controllers;

use App\DataTables\AlertaDataTable;
use App\Models\Alerta;
use App\Models\Application;
use App\Models\DeviceProfile;
use App\Models\Horario;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        $data = array(
            'aplicaciones'=>Application::get(),
            'perfil_dispositivos'=>DeviceProfile::get()
        );
        return view('alertas.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       

        try {
            $request['estado']=$request->estado?1:0;
            $alerta=Alerta::create($request->all());
            return redirect()->route('alertas.show',$alerta)->with('success',$alerta->nombre.', ingresado exitosamente.!');
        } catch (\Throwable $th) {
            return back()->with('danger', 'Error.! '.$th->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Alerta $alerta)
    {
        

        $dias = array(
            'Lunes'=>1,
            'Martes'=>2,
            'Miércoles'=>3,
            'Jueves'=>4,
            'Viernes'=>5,
            'Sábado'=>6,
            'Domingo'=>7
        );

        foreach ($dias as $dia => $numero) {
            $horario=Horario::where(['dia'=>$dia,'alerta_id'=>$alerta->id])->first();
            if(!$horario){
                $horario=new Horario();
                $horario->dia=$dia;
                $horario->numero_dia=$numero;
                $horario->alerta_id=$alerta->id;
                $horario->save();
            }
        }

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
        //
    }


    public function actualizarHorario(Request $request)
    {
        foreach ($request->horarios as $id => $datos) {
            $horario = Horario::findOrFail($id);

            // Verificar si el estado está presente y es verdadero
            if (isset($datos['estado'])) {
                // Si $datos['estado'] es un array, convertimos su valor a booleano
                $estado = is_array($datos['estado']) ? in_array('on', $datos['estado']) : (bool)$datos['estado'];

                // Si el estado es verdadero, validamos las horas de apertura y cierre
                if ($estado) {
                    $validatedData = $request->validate([
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

        return redirect()->route('alertas.show',$request->alerta_id)->with('success','Horario actualizado.!');
    }


}
