<?php

namespace App\Http\Controllers;

use App\Models\ConfigurationRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ConfigurationRuleController extends Controller
{
    /**
     * Display a listing of the configuration rules.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rules = ConfigurationRule::all();
        return response()->json($rules);
    }

    /**
     * Store a newly created configuration rule in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rule = ConfigurationRule::create($request->all());
        return response()->json($rule, 201);
    }

    /**
     * Display the specified configuration rule.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rule = ConfigurationRule::findOrFail($id);
        return response()->json($rule);
    }

    /**
     * Update the specified configuration rule in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rule = ConfigurationRule::findOrFail($id);
        $rule->update($request->all());
        return response()->json($rule);
    }

    /**
     * Remove the specified configuration rule from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rule = ConfigurationRule::findOrFail($id);
        $rule->delete();

        return redirect()->back()->with('success', 'Regla eliminada correctamente.');
    }
    public function storeButtonConfiguration(Request $request)
    {
        $validatedData = $request->validate([
            'configuration_id' => 'required|exists:configurations,id',
            'event' => 'required|in:short,long,double',
            'alert' => 'required|boolean',
        ]);

        $rule = ConfigurationRule::where('configuration_id', $validatedData['configuration_id'])
            ->where('event', $validatedData['event'])
            ->first();
        if ($rule) {
            return redirect()->back()->with('danger', 'Ya existe una configuración de evento para este dispositivo');
        }

        // Crear la nueva configuración para el botón
        ConfigurationRule::create([
            'configuration_id' => $validatedData['configuration_id'],
            'sensor' => 'button',
            'condition_type' => 'evento',
            'event' => $validatedData['event'],
            'alert' => $validatedData['alert'],
        ]);

        return redirect()->back()->with('success', 'Configuración guardada exitosamente');
    }
    public function storeGps(Request $request)
    {
        $validatedData = $request->validate([
            'configuration_id' => 'required|exists:configurations,id',
            'event' => 'required|in:start,moving,stop',
            'alert' => 'required|boolean',
        ]);

        $rule = ConfigurationRule::where('configuration_id', $validatedData['configuration_id'])
            ->where('event', $validatedData['event'])
            ->first();
        if ($rule) {
            return redirect()->back()->with('danger', 'Ya existe una configuración de evento para este dispositivo');
        }

        // Crear la nueva configuración para el botón
        ConfigurationRule::create([
            'configuration_id' => $validatedData['configuration_id'],
            'sensor' => 'button',
            'condition_type' => 'evento',
            'event' => $validatedData['event'],
            'alert' => $validatedData['alert'],
        ]);
        return redirect()->back()->with('success', 'Regla de GPS creada correctamente.');
    }
    public function storeDistance(Request $request)
    {
        $validatedData = $request->validate([
            'configuration_id' => 'required|exists:configurations,id',
            'min_value' => 'required',
            'description' => 'required',
            'color' => 'required',
            'alert' => 'required',
        ]);

        $rule = ConfigurationRule::where('configuration_id', $validatedData['configuration_id'])
            ->where('min_value', $validatedData['min_value'])
            ->first();
        if ($rule) {
            return redirect()->back()->with('danger', 'Ya existe una configuración de evento para este dispositivo');
        }

        // Crear la nueva configuración para el botón
        ConfigurationRule::create([
            'configuration_id' => $validatedData['configuration_id'],
            'sensor' => 'distance',
            'condition_type' => 'evento',
            'min_value' => $validatedData['min_value'],
            'color' => $validatedData['color'],
            'description' => $validatedData['description'],
            'alert' => $validatedData['alert'],
        ]);
        return redirect()->back()->with('success', 'Regla de GPS creada correctamente.');
    }
}
