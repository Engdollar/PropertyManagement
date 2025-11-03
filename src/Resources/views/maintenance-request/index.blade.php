@extends('layouts.main')
@section('page-title')
    {{ __('Manage Maintenance Request') }}
@endsection
@section('page-breadcrumb')
    {{ __('Maintenance Request') }}
@endsection

@push('css')
    @include('layouts.includes.datatable-css')
@endpush

@section('page-action')
<div class="d-flex">
    @stack('addButtonHook')
    @permission('maintenance request create')
        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create Maintenance Request') }}" data-url="{{route('property-maintenance-request.create')}}" data-bs-toggle="tooltip"  data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endpermission
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body table-border-style">
                <h5></h5>
                <div class="table-responsive">
                    {{ $dataTable->table(['width' => '100%']) }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @include('layouts.includes.datatable-js')
    {{ $dataTable->scripts() }}
@endpush
