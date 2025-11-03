@extends('layouts.main')
@section('page-title')
    {{ __('Manage Tenants') }}
@endsection
@section('page-breadcrumb')
    {{ __('Tenants') }}
@endsection

@push('css')
    @include('layouts.includes.datatable-css')
@endpush

@section('page-action')
    <div class="d-flex">
        @stack('addButtonHook')
        <a href="{{ route('tenant.grid') }}" class="btn btn-sm btn-primary btn-icon me-2"
                data-bs-toggle="tooltip"title="{{ __('Grid View') }}">
                <i class="ti ti-layout-grid text-white"></i>
            </a>
        @permission('tenant create')
            <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create Tenant') }}" data-url="{{route('tenant.create')}}" data-bs-toggle="tooltip"  data-bs-original-title="{{ __('Create') }}">
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
