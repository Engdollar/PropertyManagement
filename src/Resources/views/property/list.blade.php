@extends('layouts.main')
@section('page-title')
    {{ __('Manage Property') }}
@endsection

@section('page-breadcrumb')
    {{ __('Property') }}
@endsection

@section('page-action')
    <div>
        <a href="{{ route('property.index') }}" class="btn btn-sm btn-primary"
            data-bs-toggle="tooltip"title="{{ __('Grid View') }}">
            <i class="ti ti-layout-grid text-white"></i>
        </a>
        @permission('property create')
            <a class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{ __('Create') }}" data-ajax-popup="true"
                data-url="{{ route('property.create') }}" data-size="lg" data-title="{{ __('Create Property') }}">
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
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="property">
                            <thead>
                                <tr>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Address') }}</th>
                                    <th>{{ __('Total Unit') }}</th>
                                    <th>{{ __('Available Unit') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                    $properties = $properties->load('propertyImage');
                                @endphp
                                @foreach ($properties as $property)
                                    <tr>
                                        <td>
                                            <a href="{{ route('property.show', [$property->id]) }}">
                                                <img src="{{ isset($property->propertyImage[0]->name) && !empty($property->propertyImage[0]->name) && check_file($property->propertyImage[0]->name) ? get_file($property->propertyImage[0]->name) : asset('packages/workdo/PropertyManagement/src/Resources/assets/upload/thumbnail-not-found.png') }}"
                                                    alt="PropertyImage" id="thumbnail" class="card-img" style="height: 80px;width: 80%;">
                                            </a>
                                        </td>
                                        <td>
                                            <h5 class="mb-2" style="white-space: nowrap;
                                            width: 250px;
                                            overflow: hidden;
                                            text-overflow: ellipsis;">
                                            <a href="{{ route('property.show', $property->id) }}">{{ $property->name }}</a>
                                            </h5>
                                        </td>
                                        <td>
                                            <p style="white-space: nowrap;
                                                width: 500px;
                                                overflow: hidden;
                                                text-overflow: ellipsis;" class="mt-3">{{ !empty($property->address) ? $property->address : '' }}
                                            </p>
                                        </td>
                                        <td>{{ $property->propertyUnitCount() }}</td>
                                        <td>{{ $property->availablePropertyUnit() }}</td>
                                        <td class="Action text-end">
                                            @permission('property show')
                                                <div class="action-btn bg-warning ms-2">
                                                    <a href="{{ route('property.show', [$property->id]) }}"
                                                        class="btn btn-sm d-inline-flex align-items-center text-white "
                                                        data-title="{{ __('View') }}" data-bs-toggle="tooltip"
                                                        title="{{ __('View') }}"><i class="ti ti-eye"></i></a>
                                                </div>
                                            @endpermission
                                            @permission('property edit')
                                                <div class="action-btn bg-info ms-2">
                                                    <a data-size="lg" data-url="{{ route('property.edit', $property->id) }}"
                                                        class="btn btn-sm d-inline-flex align-items-center text-white "
                                                        data-ajax-popup="true" data-bs-toggle="tooltip"
                                                        data-title="{{ __('Property Edit') }}" title="{{ __('Edit') }}"><i
                                                            class="ti ti-pencil"></i></a>
                                                </div>
                                            @endpermission
                                            @permission('property delete')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['property.destroy', $property->id]]) !!}
                                                    <a href="#!"
                                                        class="btn btn-sm   align-items-center text-white show_confirm"
                                                        data-bs-toggle="tooltip" title='Delete'>
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endpermission
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush

