<?php

namespace Workdo\PropertyManagement\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Workdo\PropertyManagement\Entities\PropertyMaintenanceRequest;
use Workdo\PropertyManagement\Entities\Tenant;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PropertyMaintenanceRequestDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['user_id', 'property_id', 'unit_id', 'description_of_issue', 'status'];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('user_id', function (PropertyMaintenanceRequest $maintenance) {
                return isset($maintenance->tenant->user)?ucfirst($maintenance->tenant->user->name):'-';
            })
            ->filterColumn('user_id', function ($query, $keyword) {
                $query->whereHas('tenant', function ($q) use ($keyword) {
                    $q->whereHas('user', function ($qu) use ($keyword) {
                        $qu->where('name', 'like', "%$keyword%");
                    });
                });
            })
            ->editColumn('property_id', function (PropertyMaintenanceRequest $maintenance) {
                return ucfirst(optional($maintenance->property)->name ?? '-');
            })
            ->filterColumn('property_id', function ($query, $keyword) {
                $query->whereHas('property', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%");
                });
            })
            ->editColumn('unit_id', function (PropertyMaintenanceRequest $maintenance) {
                return ucfirst(optional($maintenance->unit)->name ?? '-');
            })
            ->filterColumn('unit_id', function ($query, $keyword) {
                $query->whereHas('unit', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%");
                });
            })
            ->editColumn('description_of_issue', function (PropertyMaintenanceRequest $maintenance) {
                $url = route('property.maintenance.request.description', \Crypt::encrypt($maintenance->id));
                $html = '<a class="action-item" data-url="' . $url . '" data-ajax-popup="true" data-bs-toggle="tooltip" title="' . __('Description Of Issue') . '" data-title="' . __('Description Of Issue') . '"><i class="fa fa-comment"></i></a>';
                return $html;
            })
            ->editColumn('status', function (PropertyMaintenanceRequest $maintenance) {
                if ($maintenance->status == 'Completed'){
                    $html = '<span class="badge fix_badge bg-primary p-2 px-3">' . $maintenance->status . '</span>';
                }elseif ($maintenance->status == 'Pending'){
                    $html = '<span class="badge fix_badge bg-warning p-2 px-3">' . $maintenance->status . '</span>';
                }else{
                    $html = '<span class="badge fix_badge bg-danger p-2 px-3">' . $maintenance->status . '</span>';
                }
                return $html;
            });

            if (\Laratrust::hasPermission('maintenance request show') ||
            \Laratrust::hasPermission('maintenance request edit') ||
            \Laratrust::hasPermission('maintenance request delete'))
            {
                $dataTable->addColumn('action', function (PropertyMaintenanceRequest $maintenance) {
                    return view('property-management::maintenance-request.maintenance_request_action', compact('maintenance'));
                });
                $rowColumn[] = 'action';
            }
        return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(PropertyMaintenanceRequest $model): QueryBuilder
    {
        if (Auth::user()->type == 'tenant') {
            $tenant = Tenant::where('user_id', Auth::user()->id)->first();
            return $model->where('user_id', $tenant->id)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId());
        } else {
            return $model->where('workspace', getActiveWorkSpace())->where('created_by', creatorId());
        }
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('property-maintenance-request-table')
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
            Column::make('id')->searchable(false)->visible(false)->exportable(false)->printable(false),
            Column::make('user_id')->title(__('Tenant')),
            Column::make('property_id')->title(__('Property')),
            Column::make('unit_id')->title(__('Unit')),
            Column::make('issue')->title(__('Issue')),
            Column::make('description_of_issue')->title(__('Description of Issue')),
            Column::make('status')->title(__('Status')),
        ];

        if (\Laratrust::hasPermission('maintenance request show') ||
            \Laratrust::hasPermission('maintenance request edit') ||
            \Laratrust::hasPermission('maintenance request delete')) {
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
        return 'PropertyMaintenanceRequest_' . date('YmdHis');
    }
}
