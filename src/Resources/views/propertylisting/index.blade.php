@extends('layouts.main')
@section('page-title')
    {{ __('Manage Listing') }}
@endsection
@section('page-breadcrumb')
    {{ __('Listing') }}
@endsection

@section('page-action')
<div>
    @stack('addButtonHook')
    @permission('property listing create')
        <a href="{{ route('property-listing.create') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
   @endpermission
</div>
@endsection

@push('css')
    @include('layouts.includes.datatable-css')
@endpush

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
