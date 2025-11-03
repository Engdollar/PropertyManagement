<?php

namespace Workdo\PropertyManagement\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Workdo\PropertyManagement\Entities\Property;
use Workdo\PropertyManagement\Entities\PropertyUnit;
use Workdo\PropertyManagement\Entities\Tenant;
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
        $rowColumn = ['name', 'property_id', 'unit_id', 'lease_end_date'];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('name', function (User $tenant) {
                $user = Tenant::where('user_id', $tenant->id)->first();
                if (!empty($user['user_id'])) {
                    $url = \Laratrust::hasPermission('tenant show')
                        ? route('tenant.show', \Crypt::encrypt($tenant['id']))
                        : '#!';
                    $html = '<a class="text-primary" href="' . $url . '">
                            ' . $tenant['name'] . '
                        </a>';
                } else {
                    $url = '-';
                    $html = '--';
                }
                return $html;
            })
            ->editColumn('property_id', function (User $tenant) {
                $property = Property::find($tenant['property_id']);
                return isset($property->name)?$property->name:'-';
            })
            ->filterColumn('property_id', function ($query, $keyword) {
                $property = Property::where('name', 'like', "%$keyword%")->pluck('id')->toArray();
                $query->where(function($q) use ($property) {
                    foreach ($property as $cutomerId) {
                        $q->orWhereRaw("FIND_IN_SET(?, property_id)", [$cutomerId]);
                    }
                });
            })
            ->editColumn('unit_id', function (User $tenant) {
                $unit = PropertyUnit::find($tenant['unit_id']);
                return isset($unit->name)?$unit->name:'-';
            })
            ->filterColumn('unit_id', function ($query, $keyword) {
                $propertyUnit = PropertyUnit::where('name', 'like', "%$keyword%")->pluck('id')->toArray();
                $query->where(function($q) use ($propertyUnit) {
                    foreach ($propertyUnit as $cutomerId) {
                        $q->orWhereRaw("FIND_IN_SET(?, unit_id)", [$cutomerId]);
                    }
                });
            })
            ->editColumn('lease_end_date', function (User $tenant) {
                if ($tenant['lease_end_date'] > \Carbon\Carbon::today()){
                    $html = '<p class="text-primary">' . (isset($tenant['lease_end_date'])?company_date_formate($tenant['lease_end_date']):'-') . '</p>';
                }else{
                    $html = '<p class="text-danger">' . (isset($tenant['lease_end_date'])?company_date_formate($tenant['lease_end_date']):'-') . '</p>';
                }
                return $html;
            });

            if (\Laratrust::hasPermission('tenant show') ||
            \Laratrust::hasPermission('tenant edit') ||
            \Laratrust::hasPermission('tenant delete'))
            {
                $dataTable->addColumn('action', function (User $tenant) {
                    return view('property-management::tenant.tenant_action', compact('tenant'));
                });
                $rowColumn[] = 'action';
            }
        return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        if (Auth::user()->type == 'tenant'){
            return $model->where('workspace_id',getActiveWorkSpace())
                    ->leftjoin('tenants', 'users.id', '=', 'tenants.user_id')
                    ->where('users.type', 'tenant')
                    ->where('users.id', Auth::user()->id)
                    ->select('users.*','tenants.*', 'users.name as name', 'users.email as email', 'users.id as id','users.mobile_no as contact','tenants.id as tenant_id');
        }else{

            return $model->where('workspace_id',getActiveWorkSpace())
                    ->leftjoin('tenants', 'users.id', '=', 'tenants.user_id')
                    ->where('users.type', 'tenant')
                    ->select('users.*','tenants.*', 'users.name as name', 'users.email as email', 'users.id as id','users.mobile_no as contact','tenants.id as tenant_id');
        }
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('tenants-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->language([
                "paginate" => [
                    "next" => '<i class="ti ti-chevron-right"></i>',
                    "previous" => '<i class="ti ti-chevron-left"></i>'
                ],
                'lengthMenu' => "_MENU_" . __('Entries Per Page'),
                "searchPlaceholder" => __('Search...'),
                "search" => "",
                "info" => __('Showing _START_ to _END_ of _TOTAL_ entries')
            ])
            ->initComplete('function() {
                var table = this;
                var searchInput = $(\'#\'+table.api().table().container().id+\' label input[type="search"]\');
                searchInput.removeClass(\'form-control form-control-sm\');
                searchInput.addClass(\'dataTable-input\');
                var select = $(table.api().table().container()).find(".dataTables_length select").removeClass(\'custom-select custom-select-sm form-control form-control-sm\').addClass(\'dataTable-selector\');
            }');

        $exportButtonConfig = [
            'extend' => 'collection',
            'className' => 'btn btn-light-secondary dropdown-toggle',
            'text' => '<i class="ti ti-download me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Export"></i>',
            'buttons' => [
                [
                    'extend' => 'print',
                    'text' => '<i class="fas fa-print me-2"></i> ' . __('Print'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 3]],
                ],
                [
                    'extend' => 'csv',
                    'text' => '<i class="fas fa-file-csv me-2"></i> ' . __('CSV'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 3]],
                ],
                [
                    'extend' => 'excel',
                    'text' => '<i class="fas fa-file-excel me-2"></i> ' . __('Excel'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 3]],
                ],
            ],
        ];

        $buttonsConfig = array_merge([
            $exportButtonConfig,
            [
                'extend' => 'reset',
                'className' => 'btn btn-light-danger',
            ],
            [
                'extend' => 'reload',
                'className' => 'btn btn-light-warning',
            ],
        ]);

        $dataTable->parameters([
            "dom" =>  "
        <'dataTable-top'<'dataTable-dropdown page-dropdown'l><'dataTable-botton table-btn dataTable-search tb-search  d-flex justify-content-end gap-2'Bf>>
        <'dataTable-container'<'col-sm-12'tr>>
        <'dataTable-bottom row'<'col-5'i><'col-7'p>>",
            'buttons' => $buttonsConfig,
            "drawCallback" => 'function( settings ) {
                var tooltipTriggerList = [].slice.call(
                    document.querySelectorAll("[data-bs-toggle=tooltip]")
                  );
                  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                  });
                  var popoverTriggerList = [].slice.call(
                    document.querySelectorAll("[data-bs-toggle=popover]")
                  );
                  var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                    return new bootstrap.Popover(popoverTriggerEl);
                  });
                  var toastElList = [].slice.call(document.querySelectorAll(".toast"));
                  var toastList = toastElList.map(function (toastEl) {
                    return new bootstrap.Toast(toastEl);
                  });
            }'
        ]);

        $dataTable->language([
            'buttons' => [
                'create' => __('Create'),
                'export' => __('Export'),
                'print' => __('Print'),
                'reset' => __('Reset'),
                'reload' => __('Reload'),
                'excel' => __('Excel'),
                'csv' => __('CSV'),
            ]
        ]);

        return $dataTable;
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $columns = [
            Column::make('id')->name('tenants.id')->searchable(false)->visible(false)->exportable(false)->printable(false),
            Column::make('name')->title(__('Name'))->name('users.name'),
            Column::make('contact')->title(__('Contact'))->name('users.mobile_no'),
            Column::make('email')->title(__('Email'))->name('users.email'),
            Column::make('property_id')->title(__('Property')),
            Column::make('unit_id')->title(__('Unit')),
            Column::make('lease_end_date')->title(__('Lease Expiry Date'))->name('tenants.lease_end_date'),
        ];

        if (\Laratrust::hasPermission('tenant show') ||
            \Laratrust::hasPermission('tenant edit') ||
            \Laratrust::hasPermission('tenant delete')) {
            $columns[] = Column::computed('action')
                ->title(__('Action'))
                ->searchable(false)
                ->orderable(false)
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ;
        }

        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Tenants_' . date('YmdHis');
    }
}
