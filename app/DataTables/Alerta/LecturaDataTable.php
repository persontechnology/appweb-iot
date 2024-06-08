<?php

namespace App\DataTables\Alerta;

use App\Models\Lectura;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class LecturaDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function($lectura){
                return view('alertas.configuracion.lecturas-action',['lectura'=>$lectura])->render();
                
            })
            
            ->addColumn('nombre',function($lectura){
                // acceder a la lectura por id y obtener el dev_eui
                $dev_eui= $lectura->xId($lectura->id)->dev_eui;
                // buscar dispositivo por dev_eui 
                return $lectura->buscarDispositivoDevEui($dev_eui)->name;
            })
            ->addColumn('mapa',function($lectura){
                $dev_eui= $lectura->xId($lectura->id)->dev_eui;

                 $link='<a href="#" data-lat="23" data-long="23551" title="Ver mapa" onclick="event.preventDefault(); verMapa(this);" ><i class="ph ph-map-pin"></i></a>';
                return $link;
            })

            ->editColumn('created_at',function($lectura){
                return $lectura->created_at;
            })
            ->setRowId('id')
            ->rawColumns(['mapa','action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Lectura $model): QueryBuilder
    {
        return $model->newQuery()
        ->whereHas('alerta',function($query){
            $query->whereHas('application', function ($query) {
                $query->whereHas('tenant', function ($query) {
                    $query->where('id', Auth::user()->tenant_id);
                });
            });
        })->latest();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('lectura-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->parameters($this->getBuilderParameters());
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->title('Acción')
                  ->addClass('text-center'),
            Column::computed('dev_eui'),
            Column::computed('nombre'),
            Column::computed('mapa'),
            Column::make('estado'),
            Column::make('data'),
            Column::make('created_at')->title('Fecha')
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Lectura_' . date('YmdHis');
    }
}
