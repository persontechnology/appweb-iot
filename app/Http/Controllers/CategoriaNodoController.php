<?php

namespace App\Http\Controllers;

use App\DataTables\CategoriaNodoDataTable;
use App\Models\CategoriaNodo;
use Illuminate\Http\Request;

class CategoriaNodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CategoriaNodoDataTable $dataTable)
    {
        return $dataTable->render('categoria-nodo.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categoria-nodo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'=>'required|string|max:255',
            'descripcion'=>'required|string|max:255',
        ]);

        
        $cg=CategoriaNodo::create($request->all());
        return redirect()->route('categoria-nodo.index')->with('success',$cg->nombre.', ingresado exitosamente.!');
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoriaNodo $categoriaNodo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoriaNodo $categoriaNodo)
    {
        return view('categoria-nodo.edit',['cn'=>$categoriaNodo]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoriaNodo $categoriaNodo)
    {
        $request->validate([
            'nombre'=>'required|string|max:255',
            'descripcion'=>'required|string|max:255',
        ]);

        $cg=$categoriaNodo->update($request->all());
        return redirect()->route('categoria-nodo.index')->with('success',$categoriaNodo->nombre.', actualizado exitosamente.!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoriaNodo $categoriaNodo)
    {
        try {
            $categoriaNodo->delete();
            return redirect()->route('categoria-nodo.index')->with('success',$categoriaNodo->nombre.', eliminado.!');
        } catch (\Throwable $th) {
            return redirect()->route('categoria-nodo.index')->with('warning',$categoriaNodo->nombre.', No eliminado.!');
        }
    }
}
