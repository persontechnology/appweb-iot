<?php

namespace App\DataTables;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TenantDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function($tena){
                return view('tenant.action',['tena'=>$tena])->render();
            })
            ->editColumn('can_have_gateways',function($tena){
                return $tena->can_have_gateways?'SI':'NO';
            })
            ->editColumn('max_device_count',function($tena){
                return $tena->max_device_count==0?'Ilimitado':$tena->max_device_count;
            })
            ->editColumn('max_gateway_count',function($tena){
                return $tena->max_gateway_count==0?'Ilimitado':$tena->max_gateway_count;
            })
            ->editColumn('id',function($tena){
                return $tena->users->count();
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Tenant $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('tenant-table')
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
            
            Column::make('name')->title('Nombre'),
            Column::make('description')->title('Descripción'),
            Column::make('max_device_count')->title('Max. dispositivos'),
            Column::make('max_gateway_count')->title('Max. gateways'),
            Column::make('id')->title('Cantidad Usuarios')->searchable(false)
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Tenant_' . date('YmdHis');
    }
}
