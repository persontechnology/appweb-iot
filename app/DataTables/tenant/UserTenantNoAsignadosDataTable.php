<?php

namespace App\DataTables\tenant;

use App\Models\Tenant;
use App\Models\User;
use App\Models\UserTenantNoAsignado;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserTenantNoAsignadosDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function($user){
                return view('tenant.usuarios.selecionar',['user'=>$user])->render();
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {


        $tenantWithUsers = Tenant::with('users')->findOrFail($this->tenantId);
    
        // Obtener los IDs de los usuarios relacionados con el Tenant
        $relatedUserIds = $tenantWithUsers->users->pluck('id')->toArray();
        
        // Crear una consulta Eloquent a partir del modelo User
        $query = $model->newQuery();
    
        // Filtrar la consulta para incluir solo los usuarios relacionados con el Tenant
        $query->whereNotIn('id', $relatedUserIds);
    
        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('usertenantnoasignados-table')
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
            Column::make('email'),
            Column::make('apellidos'),
            Column::make('nombres'),
            Column::make('identificacion')->title('Identificación'),
            Column::make('is_active')->title('Activo')
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'UserTenantNoAsignados_' . date('YmdHis');
    }
}
