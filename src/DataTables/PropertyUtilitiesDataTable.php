<?php

namespace Workdo\PropertyManagement\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Workdo\PropertyManagement\Entities\PropertyUtilities;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PropertyUtilitiesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rawColumn = ['property_id', 'reading_date', 'amount_due'];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('property_id', function ($property_utility) {
                return $property_utility->property ? $property_utility->property->name : '-' ; // Use the package relationship to get the name
            })
            ->editColumn('reading_date', function (PropertyUtilities $property_utility) {
                return company_date_formate($property_utility->reading_date);
            })
            ->filterColumn('reading_date', function ($query, $keyword) {
                try {
                    if (\Carbon\Carbon::hasFormat($keyword, 'd-m-Y')) {
                        $date = \Carbon\Carbon::createFromFormat('d-m-Y', $keyword)->format('Y-m-d');
                        return $query->where('reading_date', 'LIKE', "%$date%");
                    } elseif (\Carbon\Carbon::hasFormat($keyword, 'm-Y')) {
                        $date = \Carbon\Carbon::createFromFormat('m-Y', $keyword)->format('Y-m');
                        return $query->where('reading_date', 'LIKE', "%$date%");
                    } elseif (\Carbon\Carbon::hasFormat($keyword, 'd-m')) {
                        $date = \Carbon\Carbon::createFromFormat('d-m', $keyword)->format('m-d');
                        return $query->where('reading_date', 'LIKE', "%$date%");
                    } else {
                        $hasDay = false;
                        $hasMonth = false;
                        $hasYear = false;
                        if (\Carbon\Carbon::hasFormat($keyword, 'd') && strlen($keyword) <= 2) {
                            $day = \Carbon\Carbon::createFromFormat('d', $keyword)->format('d');
                            $query->whereRaw('DAY(reading_date) = ?', [$day]);
                            $hasDay = true;
                        }
                        if (\Carbon\Carbon::hasFormat($keyword, 'm') && strlen($keyword) <= 2) {
                            $month = \Carbon\Carbon::createFromFormat('m', $keyword)->format('m');
                            $query->orWhereRaw('MONTH(reading_date) = ?', [$month]);
                            $hasMonth = true;
                        }
                        if (preg_match('/^\d{4}$/', $keyword)) {
                            $year = \Carbon\Carbon::createFromFormat('Y', $keyword)->format('Y');
                            $query->orWhereRaw('YEAR(reading_date) = ?', [$year]);
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
            ->editColumn('amount_due', function (PropertyUtilities $property_utility) {
                return currency_format_with_sym($property_utility->amount_due);
            });

        if (\Laratrust::hasPermission('property utilities show') || \Laratrust::hasPermission('property utilities edit') || \Laratrust::hasPermission('property utilities delete')) {
            $dataTable->addColumn('action', function (PropertyUtilities $property_utility) {
                return view('property-management::property-utilities.action', compact('property_utility'));
            });
            $rawColumn[] = 'action';
        }
        return  $dataTable->rawColumns($rawColumn);
    }
    public function query(PropertyUtilities $model): QueryBuilder
    {
        return $model->with('property')
            ->where('property_utilities.workspace', getActiveWorkSpace()) // specify the table
            ->where('property_utilities.created_by', creatorId()); // specify the table
    }
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('property_utilities-table')
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
            Column::make('property_id')->title(__('Property'))->name('property.name'),
            Column::make('utility_type')->title(__('Utility Type')),
            Column::make('reading_date')->title(__('Reading Date')),
            Column::make('current_reading')->title(__('Current Reading')),
            Column::make('previous_reading')->title(__('Previous Reading')),
            Column::make('amount_due')->title(__('Amount Due')),
        ];

        if (
            \Laratrust::hasPermission('property utilities show') ||
            \Laratrust::hasPermission('property utilities delete')
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
        return 'PropertyUtilities_' . date('YmdHis');
    }
}
