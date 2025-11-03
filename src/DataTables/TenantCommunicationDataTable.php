<?php

namespace Workdo\PropertyManagement\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Workdo\PropertyManagement\Entities\TenantCommunication;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TenantCommunicationDataTable extends DataTable
{

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rawColumn = ['tenant_id', 'communication_date', 'message'];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('tenant_id', function ($tenant_communication) {
                return $tenant_communication->tenant->user ? $tenant_communication->tenant->user->name : '-' ; // Use the package relationship to get the name
            })
            ->filterColumn('tenant_id', function ($query, $keyword){
                $query->whereHas('tenant', function ($q) use ($keyword) {
                    $q->whereHas('user', function ($db) use ($keyword) {
                        $db->where('name', 'like', "%$keyword%");
                    });
                });
            })
            ->editColumn('communication_date', function (TenantCommunication $tenant_communication) {
                return company_date_formate($tenant_communication->communication_date);
            })
            ->filterColumn('communication_date', function ($query, $keyword) {
                try {
                    if (\Carbon\Carbon::hasFormat($keyword, 'd-m-Y')) {
                        $date = \Carbon\Carbon::createFromFormat('d-m-Y', $keyword)->format('Y-m-d');
                        return $query->where('communication_date', 'LIKE', "%$date%");
                    } elseif (\Carbon\Carbon::hasFormat($keyword, 'm-Y')) {
                        $date = \Carbon\Carbon::createFromFormat('m-Y', $keyword)->format('Y-m');
                        return $query->where('communication_date', 'LIKE', "%$date%");
                    } elseif (\Carbon\Carbon::hasFormat($keyword, 'd-m')) {
                        $date = \Carbon\Carbon::createFromFormat('d-m', $keyword)->format('m-d');
                        return $query->where('communication_date', 'LIKE', "%$date%");
                    } else {
                        $hasDay = false;
                        $hasMonth = false;
                        $hasYear = false;
                        if (\Carbon\Carbon::hasFormat($keyword, 'd') && strlen($keyword) <= 2) {
                            $day = \Carbon\Carbon::createFromFormat('d', $keyword)->format('d');
                            $query->whereRaw('DAY(communication_date) = ?', [$day]);
                            $hasDay = true;
                        }
                        if (\Carbon\Carbon::hasFormat($keyword, 'm') && strlen($keyword) <= 2) {
                            $month = \Carbon\Carbon::createFromFormat('m', $keyword)->format('m');
                            $query->orWhereRaw('MONTH(communication_date) = ?', [$month]);
                            $hasMonth = true;
                        }
                        if (preg_match('/^\d{4}$/', $keyword)) {
                            $year = \Carbon\Carbon::createFromFormat('Y', $keyword)->format('Y');
                            $query->orWhereRaw('YEAR(communication_date) = ?', [$year]);
                            $hasYear = true;
                        }

                        if ($hasDay || $hasMonth || $hasYear) {
                            return $query;
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('Invalid date format: ' . $keyword);
                }
            })
            ->editColumn('message', function (TenantCommunication $tenant_communication) {
                $url = route('tenant.communications.message', \Crypt::encrypt($tenant_communication->id));
                $html = '<a class="action-item" data-url="' . $url . '" data-ajax-popup="true" data-bs-toggle="tooltip" title="' . __('Message') . '" data-title="' . __('Message') . '"><i class="fa fa-comment"></i></a>';
                return $html;
            });

        if (\Laratrust::hasPermission('tenant communications show') || \Laratrust::hasPermission('tenant communications edit') || \Laratrust::hasPermission('tenant communications delete')) {
            $dataTable->addColumn('action', function (TenantCommunication $tenant_communication) {
                return view('property-management::tenant-communications.action', compact('tenant_communication'));
            });
            $rawColumn[] = 'action';
        }
        return  $dataTable->rawColumns($rawColumn);
    }
    public function query(TenantCommunication $model): QueryBuilder
    {
        return $model->with('tenant')
            ->where('tenant_communications.workspace', getActiveWorkSpace()) // specify the table
            ->where('tenant_communications.created_by', creatorId()); // specify the table
    }
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('tenant_communications-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
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

    public function getColumns(): array
    {
        $columns = [
            Column::make('id')->searchable(false)->visible(false)->exportable(false)->printable(false),
            Column::make('No')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('tenant_id')->title(__('Tenant')),
            Column::make('communication_date')->title(__('Communication Date')),
            Column::make('sender')->title(__('Sender')),
            Column::make('message')->title(__('Message')),
        ];

        if (
            \Laratrust::hasPermission('tenant communications show') ||
            \Laratrust::hasPermission('tenant communications delete')
        ) {
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
    protected function filename(): string
    {
        return 'TenantCommunication_' . date('YmdHis');
    }
}
