<?php

namespace App\DataTables;

use App\Models\Gateway;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class GatewayDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function($ga){
                return view('gateway.action',['ga'=>$ga])->render();
            })
            ->editColumn('conectado',function($ga){
                return view('gateway.conectado',['ga'=>$ga])->render();
            })
            ->setRowId('conectado')
            ->rawColumns(['action','conectado']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Gateway $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('gateway-table')
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
                  ->addClass('text-center'),
            Column::make('conectado'),
            Column::make('nombre'),
            Column::make('modelo'),
            Column::make('fcc_id'),
            Column::make('direccion_ip'),
            Column::make('usuario'),
            Column::make('password'),
            Column::make('imei'),
            Column::make('mac'),
            Column::make('foto'),
            Column::make('estado'),
            // Column::make('lat'),
            // Column::make('lng'),
            // Column::make('descripcion'),
            Column::make('categoria_gateway_id'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Gateway_' . date('YmdHis');
    }
}
