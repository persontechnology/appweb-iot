<?php

namespace App\DataTables\Alerta;

use App\Models\TenantUser;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UsuariosDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function($tu){
                return view('alertas.usuarios.action',['tu'=>$tu,'alertaId'=>$this->alertaId])->render();
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(TenantUser $model): QueryBuilder
    {
        return $model->newQuery()->where('tenant_id',Auth::user()->tenant_id)->with('user');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('usuarios-table')
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
                  ->title('Selecionar')
                  ->addClass('text-center'),
            Column::make('user.email')->title('Email'),
            Column::make('user.apellidos')->title('Apellidos'),
            Column::make('user.nombres')->title('Nombres'),
            Column::make('user.identificacion')->title('Identificación'),
            Column::make('user.is_active')->title('Activo')
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Usuarios_' . date('YmdHis');
    }
}
