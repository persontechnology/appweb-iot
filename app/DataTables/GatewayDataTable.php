<?php

namespace App\DataTables;

use App\Models\Gateway;
use Carbon\Carbon;
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
            ->addColumn('action', function($gw){
                return view('gateway.action',['gw'=>$gw])->render();
            })

            ->filterColumn('gateway_id_hex',function($query, $keyword){
                $query->whereRaw("encode(gateway_id, 'hex') like ?", ["%{$keyword}%"]);
           
            })

            ->editColumn('last_seen_at',function($gw){
                return Carbon::parse($gw->last_seen_at)->format('Y-m-d H:i:s');
            })

            ->setRowId('tenant_id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Gateway $model): QueryBuilder
    {
        return $model->selectRaw("encode(gateway_id, 'hex') as gateway_id_hex,name,description,last_seen_at,stats_interval_secs");
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('gateway-tablex')
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
            Column::make('last_seen_at')->title('Ultima vez visto'),      
            Column::make('gateway_id_hex')->title('Gateway Id'),      
            Column::make('name')->title('Nombre'),
            Column::make('description')->title('Descripción')->searchable(false),
            Column::make('stats_interval_secs')->title('Intervalo (segundos)')->searchable(false),
            

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
