@extends('layouts.main')
@section('page-title')
    {{ __('Manage Document Type') }}
@endsection
@section('page-breadcrumb')
{{ __('Document Type') }}
@endsection
@section('page-action')
<div>
    @permission('tenant document create')
        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create Document Type') }}" data-url="{{route('tenant-document-type.create')}}" data-toggle="tooltip" title="{{ __('Create') }}">
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
