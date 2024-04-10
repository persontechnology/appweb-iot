<?php

namespace App\DataTables;

use App\Models\Dispositivo;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DispositivoDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function($dis){
                return view('dispositivos.action',['dis'=>$dis])->render();
            })
            ->filterColumn('device_id_hex',function($query, $keyword){
                $query->whereRaw("encode(dev_eui, 'hex') like ?", ["%{$keyword}%"]);
           
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Dispositivo $model): QueryBuilder
    {
        // return $model->newQuery();
        return $model->selectRaw("encode(dev_eui, 'hex') as device_id_hex,encode(join_eui, 'hex') as join_eui_hex,name,description,application_id,battery_level,is_disabled");


    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('dispositivo-table')
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
            Column::make('device_id_hex'),
            Column::make('is_disabled'),
            Column::make('name'),
            Column::make('join_eui_hex'),
            Column::make('battery_level')->title('%Batería'),
            Column::make('application_id'),
            
            
            
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Dispositivo_' . date('YmdHis');
    }
}
