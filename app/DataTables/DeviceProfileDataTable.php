<?php

namespace App\DataTables;

use App\Models\DeviceProfile;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DeviceProfileDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function($dp){
                return view('device-profile.action',['dp'=>$dp])->render();
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(DeviceProfile $model): QueryBuilder
    {
        $tenantId = Auth::user()->tenant_id;

        return $model->newQuery()
            ->whereHas('tenant', function ($query) use ($tenantId) {
                $query->where('id', $tenantId);
            })
            ->with('tenant');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('deviceprofile-table')
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
            Column::make('region')->title('Región'),
            Column::make('mac_version')->title('Mac versión'),
            Column::make('reg_params_revision')->title('Revisión'),
            Column::make('tenant.name')->title('Inquilino'),
            Column::make('uplink_interval')->title('Intervalo de enlace'),
            Column::make('description')->title('Descripción'),
            
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'DeviceProfile_' . date('YmdHis');
    }
}
