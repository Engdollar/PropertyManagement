<?php

namespace Workdo\PropertyManagement\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Workdo\PropertyManagement\Entities\PropertyInvoice;
use Workdo\PropertyManagement\Entities\Tenant;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PropertyInvoiceDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['invoice_id', 'user_id', 'status', 'total_amount', 'issue_date', 'due_date'];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('invoice_id', function (PropertyInvoice $invoice) {
                $html = '';
                if (\Laratrust::hasPermission('property invoice show')) {
                    $html .= '<a href="' . route('property-invoice.show', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id)) . '" data-title="' . __('Invoice Details') . '" class="btn btn-outline-primary">';
                    $html .= PropertyInvoice::tenantNumberFormat($invoice->id);
                    $html .= '</a>';
                } else {
                    $html .= '<a href="#" data-title="' . __('Invoice Details') . '" class="btn btn-outline-primary">';
                    $html .= PropertyInvoice::tenantNumberFormat($invoice->id);
                    $html .= '</a>';
                }
                return $html;
            })
            ->filterColumn('id', function ($query, $keyword) {
                $prefix         = !empty(company_setting('tenant_prefix')) ? company_setting('tenant_prefix') : '#PMS';
                $formattedValue = str_replace($prefix, '', $keyword);
                $query->where('id', $formattedValue);
            })
            ->editColumn('user_id', function (PropertyInvoice $invoice) {
                return isset($invoice->tenant->user)?ucfirst($invoice->tenant->user->name):'-';
            })
            ->filterColumn('user_id', function ($query, $keyword) {
                $query->whereHas('tenant', function ($q) use ($keyword) {
                    $q->whereHas('user', function ($qu) use ($keyword) {
                        $qu->where('name', 'like', "%$keyword%");
                    });
                });
            })
            ->editColumn('status', function (PropertyInvoice $invoice) {
                if ($invoice->status == 'Not Paid'){
                    $html = '<span class="badge fix_badge bg-danger p-2 px-3">' . $invoice->status . '</span>';
                }elseif ($invoice->status == 'Pending'){
                    $html = '<span class="badge fix_badge bg-warning p-2 px-3">' . $invoice->status . '</span>';
                }else{
                    $html = '<span class="badge fix_badge bg-success p-2 px-3">' . $invoice->status . '</span>';
                }
                return $html;
            })
            ->editColumn('total_amount', function (PropertyInvoice $invoice) {
                return currency_format_with_sym($invoice->total_amount);
            })
            ->editColumn('issue_date', function (PropertyInvoice $invoice) {
                return company_date_formate($invoice->issue_date);
            })
            ->editColumn('due_date', function (PropertyInvoice $invoice) {
                if ($invoice->due_date > \Carbon\Carbon::today()){
                    $html = '<p class="text-primary">' . company_date_formate($invoice->due_date) . '</p>';
                }else{
                    $html = '<p class="text-danger">' . company_date_formate($invoice->due_date) . '</p>';
                }
                return $html;
            });

            if (\Laratrust::hasPermission('property invoice show') ||
            \Laratrust::hasPermission('property invoice delete'))
            {
                $dataTable->addColumn('action', function (PropertyInvoice $invoice) {
                    return view('property-management::propertyinvoice.property_invoice_action', compact('invoice'));
                });
                $rowColumn[] = 'action';
            }
        return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(PropertyInvoice $model): QueryBuilder
    {
        if (Auth::user()->type == 'tenant'){
            $tenant = Tenant::where('user_id',Auth::user()->id)->first();
            return $model->where('user_id',$tenant->id)->where('created_by',creatorId())->where('workspace',getActiveWorkSpace());
        }else{
            return $model->where('created_by',creatorId())->where('workspace',getActiveWorkSpace());
        }
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('property-invoice-table')
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
            Column::make('invoice_id')->title(__('ID'))->name('id'),
            Column::make('user_id')->title(__('Name')),
            Column::make('status')->title(__('Status')),
            Column::make('total_amount')->title(__('Amount')),
            Column::make('issue_date')->title(__('Created At')),
            Column::make('due_date')->title(__('Due Date')),
        ];

        if (\Laratrust::hasPermission('property invoice show') ||
            \Laratrust::hasPermission('property invoice delete')) {
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
        return 'PropertyInvoice_' . date('YmdHis');
    }
}
