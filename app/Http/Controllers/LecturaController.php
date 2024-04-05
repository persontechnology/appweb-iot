<?php

namespace App\Http\Controllers;

use App\DataTables\LecturaDataTable;
use App\Events\LecturaGuardadoEvent;
use App\Models\Lectura;
use Illuminate\Http\Request;

class LecturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(LecturaDataTable $dataTable)
    {
        // $lectura=Lectura::select('id')->first();
       
        return $dataTable->render('lecturas.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Lectura $lectura)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lectura $lectura)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lectura $lectura)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lectura $lectura)
    {
        //
    }
}
