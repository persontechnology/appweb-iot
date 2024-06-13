<?php

namespace App\Http\Controllers;

use App\DataTables\LecturaDataTable;
use App\Models\Lectura;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use PDF;

class LecturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(LecturaDataTable $dataTable)
    {
        return $dataTable->render('lecturas.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
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
        Gate::authorize('view',$lectura);
        $lectura->estado=true;
        $lectura->save();
        return view('lecturas.show',['lectura'=>$lectura]);

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
        Gate::authorize('delete',$lectura);
        try {
            $lectura->delete();
            return redirect()->route('lecturas.index')->with('success','Lectura eliminado.!');
        } catch (\Throwable $th) {
            return redirect()->route('lecturas.index')->with('danger','Lectura no eliminado,'.$th->getMessage());
        }
    }

    public function descartarTodo(){
        $tenant = Tenant::find(Auth::user()->tenant_id);
        $tenant->lecturas()->update(['estado' => true]);
        $tenant->lecturas()->get();
        return redirect()->back()->with('success','Lecturas descartadas.!');
    }

    public function descargarPdf($idLectura) {
        $lectura=Lectura::find($idLectura);
        $headerHtml = view()->make('pdf.header')->render();
        $footerHtml = view()->make('pdf.footer')->render();
        $pdf = PDF::loadView('lecturas.pdf',['lectura'=>$lectura])
        ->setOption('header-html', $headerHtml)
        ->setOption('footer-html', $footerHtml);
        return $pdf->download('Lectura-'.$lectura->id.'.pdf');
    }
}
