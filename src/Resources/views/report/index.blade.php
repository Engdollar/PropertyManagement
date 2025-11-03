@extends('layouts.admin')

@section('page-title')
{{ __('Property Invoice Report') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('propertymanagement.index') }}">{{ __('Property Management') }}</a></li>
<li class="breadcrumb-item">{{ __('Invoice Report') }}</li>
@endsection

@section('action-btn')
<div class="float-end">
    <button type="button" class="btn btn-sm btn-primary" onclick="generateReport()">
        <i class="ti ti-chart-bar"></i> {{ __('Generate Report') }}
    </button>
    <button type="button" class="btn btn-sm btn-success" onclick="exportPDF()">
        <i class="ti ti-file-text"></i> {{ __('Export PDF') }}
    </button>
    <button type="button" class="btn btn-sm btn-info" onclick="exportExcel()">
        <i class="ti ti-file-spreadsheet"></i> {{ __('Export Excel') }}
    </button>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Property Invoice Due Date Report') }}</h5>
                <small>{{ __('Generate comprehensive reports for property invoices, payments, and vacant units')
                    }}</small>
            </div>
            <div class="card-body">
                <form id="reportForm">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Start Date') }}</label>
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ date('Y-m-d', strtotime('-1 month')) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('End Date') }}</label>
                                <input type="date" name="end_date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Property') }}</label>
                                <select name="property_id" class="form-control">
                                    <option value="">{{ __('All Properties') }}</option>
                                    @foreach($properties as $property)
                                    <option value="{{ $property->id }}">{{ $property->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="loadingSpinner" style="display: none;">
    <div class="text-center">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">{{ __('Loading...') }}</span>
        </div>
    </div>
</div>

<div id="reportContent" style="display: none;">
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-primary rounded">
                            <i class="ti ti-building avatar-title fs-22 text-white"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <h5 class="mb-1" id="totalProperties">0</h5>
                            <p class="text-muted mb-0">{{ __('Total Properties') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-success rounded">
                            <i class="ti ti-home avatar-title fs-22 text-white"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <h5 class="mb-1" id="occupiedUnits">0</h5>
                            <p class="text-muted mb-0">{{ __('Occupied Units') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-warning rounded">
                            <i class="ti ti-key avatar-title fs-22 text-white"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <h5 class="mb-1" id="vacantUnits">0</h5>
                            <p class="text-muted mb-0">{{ __('Vacant Units') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-info rounded">
                            <i class="ti ti-currency-dollar avatar-title fs-22 text-white"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <h5 class="mb-1" id="totalRevenue">$0</h5>
                            <p class="text-muted mb-0">{{ __('Expected Revenue') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Property Portfolio Summary -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Property Portfolio Summary') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="portfolioTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Property Name') }}</th>
                                    <th>{{ __('Total Units') }}</th>
                                    <th>{{ __('Occupied Units') }}</th>
                                    <th>{{ __('Vacant Units') }}</th>
                                    <th>{{ __('Monthly Rent') }}</th>
                                    <th>{{ __('Expected Revenue') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody id="portfolioTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Paid Invoices -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Paid Property Invoices') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="paidTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Invoice ID') }}</th>
                                    <th>{{ __('Property Name') }}</th>
                                    <th>{{ __('Unit Number') }}</th>
                                    <th>{{ __('Tenant Name') }}</th>
                                    <th>{{ __('Amount Paid') }}</th>
                                    <th>{{ __('Payment Date') }}</th>
                                    <th>{{ __('Payment Method') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody id="paidTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Unpaid Invoices -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Outstanding Property Invoices') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="unpaidTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Invoice ID') }}</th>
                                    <th>{{ __('Property Name') }}</th>
                                    <th>{{ __('Unit Number') }}</th>
                                    <th>{{ __('Tenant Name') }}</th>
                                    <th>{{ __('Amount Due') }}</th>
                                    <th>{{ __('Days Overdue') }}</th>
                                    <th>{{ __('Late Fee') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody id="unpaidTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vacant Units -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Vacant Units - Available for Rent') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="vacantTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Property Name') }}</th>
                                    <th>{{ __('Unit Number') }}</th>
                                    <th>{{ __('Unit Type') }}</th>
                                    <th>{{ __('Monthly Rent') }}</th>
                                    <th>{{ __('Bedrooms') }}</th>
                                    <th>{{ __('Bathrooms') }}</th>
                                    <th>{{ __('Days Vacant') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody id="vacantTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Payment Performance Metrics') }}</h5>
                </div>
                <div class="card-body">
                    <div id="paymentMetrics" class="row"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Risk Assessment') }}</h5>
                </div>
                <div class="card-body">
                    <div id="riskAssessment" class="row"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    function generateReport() {
        $('#loadingSpinner').show();
        $('#reportContent').hide();
        
        $.ajax({
            url: '{{ route("propertymanagement.report.generate") }}',
            method: 'POST',
            data: $('#reportForm').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    populateReportData(response.data);
                    $('#reportContent').show();
                    
                    // Update summary cards
                    updateSummaryCards(response.data);
                }
                $('#loadingSpinner').hide();
            },
            error: function(xhr) {
                show_toastr('error', 'Error generating report: ' + xhr.responseJSON.message);
                $('#loadingSpinner').hide();
            }
        });
    }

    function updateSummaryCards(data) {
        let totalProperties = data.portfolio.length;
        let totalUnits = data.portfolio.reduce((sum, item) => sum + item.total_units, 0);
        let occupiedUnits = data.portfolio.reduce((sum, item) => sum + item.occupied_units, 0);
        let vacantUnits = data.portfolio.reduce((sum, item) => sum + item.vacant_units, 0);
        let totalRevenue = data.portfolio.reduce((sum, item) => sum + item.expected_revenue, 0);

        $('#totalProperties').text(totalProperties);
        $('#occupiedUnits').text(occupiedUnits);
        $('#vacantUnits').text(vacantUnits);
        $('#totalRevenue').text(formatCurrency(totalRevenue));
    }

    function populateReportData(data) {
        // Portfolio Table
        let portfolioHtml = '';
        data.portfolio.forEach(item => {
            portfolioHtml += `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.total_units}</td>
                    <td>${item.occupied_units}</td>
                    <td>${item.vacant_units}</td>
                    <td>${formatCurrency(item.monthly_rent)}</td>
                    <td>${formatCurrency(item.expected_revenue)}</td>
                    <td><span class="badge bg-success">${item.status}</span></td>
                </tr>
            `;
        });
        $('#portfolioTableBody').html(portfolioHtml);

        // Paid Invoices
        let paidHtml = '';
        data.paidInvoices.forEach(item => {
            paidHtml += `
                <tr>
                    <td>${item.id}</td>
                    <td>${item.property_name}</td>
                    <td>${item.unit_number}</td>
                    <td>${item.tenant_name}</td>
                    <td>${formatCurrency(item.amount)}</td>
                    <td>${item.payment_date}</td>
                    <td>${item.payment_method}</td>
                    <td><span class="badge bg-success">${item.status}</span></td>
                </tr>
            `;
        });
        $('#paidTableBody').html(paidHtml);

        // Unpaid Invoices
        let unpaidHtml = '';
        data.unpaidInvoices.forEach(item => {
            unpaidHtml += `
                <tr>
                    <td>${item.id}</td>
                    <td>${item.property_name}</td>
                    <td>${item.unit_number}</td>
                    <td>${item.tenant_name}</td>
                    <td>${formatCurrency(item.amount)}</td>
                    <td>${item.days_overdue}</td>
                    <td>${formatCurrency(item.late_fee)}</td>
                    <td><span class="badge bg-danger">${item.status}</span></td>
                </tr>
            `;
        });
        $('#unpaidTableBody').html(unpaidHtml);

        // Vacant Units
        let vacantHtml = '';
        data.vacantUnits.forEach(item => {
            vacantHtml += `
                <tr>
                    <td>${item.property_name}</td>
                    <td>${item.unit_number}</td>
                    <td>${item.unit_type}</td>
                    <td>${formatCurrency(item.monthly_rent)}</td>
                    <td>${item.bedrooms}</td>
                    <td>${item.bathrooms}</td>
                    <td>${item.days_vacant}</td>
                    <td><span class="badge bg-warning">${item.status}</span></td>
                </tr>
            `;
        });
        $('#vacantTableBody').html(vacantHtml);

        // Analytics
        let paymentMetrics = `
            <div class="col-md-4">
                <div class="text-center">
                    <h4 class="text-success">${data.analytics.payment_rate}%</h4>
                    <p class="text-muted">Payment Success Rate</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <h4 class="text-info">${data.analytics.total_invoices}</h4>
                    <p class="text-muted">Total Invoices</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <h4 class="text-warning">${data.analytics.unpaid_invoices}</h4>
                    <p class="text-muted">Unpaid Invoices</p>
                </div>
            </div>
        `;
        $('#paymentMetrics').html(paymentMetrics);

        let riskAssessment = `
            <div class="col-md-6">
                <div class="text-center">
                    <h4 class="text-danger">${data.analytics.high_risk}</h4>
                    <p class="text-muted">High Risk (30+ days)</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-center">
                    <h4 class="text-warning">${data.analytics.medium_risk}</h4>
                    <p class="text-muted">Medium Risk (15-30 days)</p>
                </div>
            </div>
        `;
        $('#riskAssessment').html(riskAssessment);

        // Initialize DataTables
        if ($.fn.DataTable.isDataTable('#portfolioTable')) {
            $('#portfolioTable').DataTable().destroy();
        }
        if ($.fn.DataTable.isDataTable('#paidTable')) {
            $('#paidTable').DataTable().destroy();
        }
        if ($.fn.DataTable.isDataTable('#unpaidTable')) {
            $('#unpaidTable').DataTable().destroy();
        }
        if ($.fn.DataTable.isDataTable('#vacantTable')) {
            $('#vacantTable').DataTable().destroy();
        }

        $('#portfolioTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'asc']]
        });

        $('#paidTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[5, 'desc']]
        });

        $('#unpaidTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[5, 'desc']]
        });

        $('#vacantTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[6, 'desc']]
        });
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(amount);
    }

function exportExcel() {
show_toastr('info', 'Excel export functionality will be implemented soon.');
}

// Auto-generate report on page load
$(document).ready(function() {
setTimeout(function() {
generateReport();
}, 500);
});