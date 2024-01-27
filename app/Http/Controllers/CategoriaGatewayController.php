<?php

namespace App\Http\Controllers;

use App\DataTables\CategoriaGatewayDataTable;
use App\Models\CategoriaGateway;
use Illuminate\Http\Request;

class CategoriaGatewayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CategoriaGatewayDataTable $dataTable)
    {
        return $dataTable->render('categoria-gateway.index');

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categoria-gateway.create');
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

        
        $cg=CategoriaGateway::create($request->all());
        return redirect()->route('categoria-gateway.index')->with('success',$cg->nombre.', ingresado exitosamente.!');
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoriaGateway $categoriaGateway)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoriaGateway $categoriaGateway)
    {
        return view('categoria-gateway.edit',['cg'=>$categoriaGateway]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoriaGateway $categoriaGateway)
    {
        $request->validate([
            'nombre'=>'required|string|max:255',
            'descripcion'=>'required|string|max:255',
        ]);

        $cg=$categoriaGateway->update($request->all());
        return redirect()->route('categoria-gateway.index')->with('success',$categoriaGateway->nombre.', actualizado exitosamente.!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoriaGateway $categoriaGateway)
    {
        try {
            $categoriaGateway->delete();
            return redirect()->route('categoria-gateway.index')->with('success',$categoriaGateway->nombre.', eliminado.!');
        } catch (\Throwable $th) {
            return redirect()->route('categoria-gateway.index')->with('warning',$categoriaGateway->nombre.', No eliminado.!');
        }
    }
}
