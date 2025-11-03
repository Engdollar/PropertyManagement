@extends('layouts.main')

@section('page-title')
    {{__('Property Details')}}
@endsection
@push('css')
    <style>
        .nav-tabs .nav-link-tabs.active {
            background: none;
        }
    </style>

@endpush

@php
@endphp

@push('scripts')
<script>
    var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: '#useradd-sidenav',
        offset: 300
    })
</script>
<script>
    $(document).ready(function() {
            var tab = 'property';
            @if ($tab = Session::get('status'))
                var tab = '{{ $tab }}';
            @endif
            $("#myTab2 .nav-link-tabs[href='#" + tab + "']").trigger("click");
        });

    if ($(".summernote").length > 0) {
        $('.summernote').summernote({
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                ['list', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'unlink']],
            ],
            height: 230,
        });
    }

    $(".deleteRecord").click(function() {
        var id = $(this).data("id");
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            url: '{{ route('property.images.delete', 'id') }}'.replace('id', id),
            type: 'DELETE',
            data: {
                "id": id,
                "_token": token,
            },
            success: function(data) {
                if (data.success) {
                    toastrs('success', data.message, 'success');
                    location.reload();
                } else {
                    toastrs('Error', data.error, 'error');
                }
            }
        });
    });
</script>

@endpush

@section('page-breadcrumb')
    {{$property->name}}
@endsection


@section('page-action')
    <div class="d-flex">
        @stack('addButtonHook')
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-12 mb-3">
            <div class="row">
                <div class="col-sm-3">
                    <div class="card sticky-top" style="top:30px">
                        <div  class="list-group list-group-flush" id="useradd-sidenav">
                            <a class="list-group-item list-group-item-action border-0" href="#property">{{__('Property Details')}}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a class="list-group-item list-group-item-action border-0" href="#property_image">{{__('Property Images')}}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a class="list-group-item list-group-item-action border-0" href="#units">{{__('Units')}}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a class="list-group-item list-group-item-action border-0" href="#property_map">{{__('Property Map')}}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-9">
                    <div id="property">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5>{{__('Property Details')}}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 table-border-style">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td><b>{{ __('Property Name') }}</b></td>
                                                        <td>{{ $property->name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>{{ __('Property Address') }}</b></td>
                                                        <td>{{ $property->address }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>{{ __('City') }}</b></td>
                                                        <td>{{ $property->city }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>{{ __('State') }}</b></td>
                                                        <td>{{ $property->state }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>{{ __('Country') }}</b></td>
                                                        <td>{{ $property->country }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>{{ __('Pin Code') }}</b></td>
                                                        <td>{{ $property->pincode }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>{{ __('Description') }}</b></td>
                                                        <td>
                                                            @php
                                                                $dec = wordwrap($property->description, 100, "<br/>", true);
                                                            @endphp
                                                            {!! $dec !!}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>{{ __('Security Deposit') }}</b></td>
                                                        <td>{{ currency_format_with_sym($property->security_deposit,$property->created_by,$property->workspace) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>{{ __('Maintenance Charge') }}</b></td>
                                                        <td>{{ currency_format_with_sym($property->maintenance_charge,$property->created_by,$property->workspace) }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="property_image">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card table-card">
                                        <div class="card-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5>{{__('Property Images')}}</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @foreach($property_images as $file)
                                                    <div class="col-md-4 pb-3">
                                                        <div class="position-relative p-2 border rounded border-primary overflow-hidden rounded">
                                                            <img src="{{ isset($file->name) && !empty($file->name) && check_file($file->name) ? get_file($file->name) : '' }}" alt="" class="w-100" style="height: 235px;">
                                                            <div class="position-absolute text-center top-50 end-0 start-0 ps-3 pb-3">
                                                                <a href="{{ isset($file->name) && !empty($file->name) && check_file($file->name) ? get_file($file->name) : '' }}" data-original-title="{{ __('Preview') }}" data-bs-toggle="tooltip" title="{{ __('Preview') }}" target="_blank"
                                                                    class="btn btn-sm btn-secondary me-1"><i class="ti ti-crosshair"></i></a>
                                                                <a href="{{ isset($file->name) && !empty($file->name) && check_file($file->name) ? get_file($file->name) : '' }}" download="" data-original-title="{{ __('Download') }}" data-bs-toggle="tooltip" title="{{ __('Download') }}"
                                                                    class="btn btn-sm btn-primary me-1"><i class="ti ti-download"></i></a>
                                                                @if (\Auth::user()->type != 'tenant')
                                                                    <a class="btn btn-sm btn-danger text-white deleteRecord" name="deleteRecord" data-bs-toggle="tooltip" title="{{ __('Delete') }}"
                                                                        data-id="{{ $file->id }}"><i class="ti ti-trash"></i></a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="units">
                        <div class="row pt-2">
                            <div class="col-12">
                                <div class="card table-card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5>{{__('Units')}}</h5>
                                            </div>
                                            @permission('property unit create')
                                                <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create Unit') }}" data-url="{{route('property-unit.create')}}" data-bs-toggle="tooltip"  data-bs-original-title="{{ __('Create') }}">
                                                    <i class="ti ti-plus"></i>
                                                </a>
                                            @endpermission
                                        </div>
                                    </div>
                                    <div class=" card-body table-border-style">

                                        <div class="table-responsive">
                                            <table class="table mb-0 pc-dt-simple" id="propertyUnit">
                                                <thead>
                                                    <tr>
                                                        <th width="">{{__('Name')}}</th>
                                                        <th>{{__('Bedroom')}}</th>
                                                        <th>{{__('Baths')}}</th>
                                                        <th>{{__('Kitchen')}}</th>
                                                        <th>{{__('Rent Type')}}</th>
                                                        <th>{{__('Rent')}}</th>
                                                        <th>{{__('Status')}}</th>
                                                        <th>{{__('Action')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($units as $unit)
                                                        <tr>
                                                            <td class="font-style">{{$unit['name']}}</td>
                                                            <td>{{$unit['bedroom']}}</td>
                                                            <td>{{$unit['baths']}}</td>
                                                            <td>{{$unit['kitchen']}}</td>
                                                            <td>{{$unit['rent_type']}}</td>
                                                            <td>{{currency_format_with_sym($unit['rent'])}}</td>
                                                            <td>
                                                            @if($unit['rentable_status'] == 'Vacant')
                                                               <span class="badge fix_badge bg-primary p-2 px-3">{{$unit['rentable_status']}}</span>
                                                            @else
                                                                <span class="badge fix_badge bg-danger p-2 px-3">{{$unit['rentable_status']}}</span>
                                                            @endif
                                                            </td>
                                                            @if (Laratrust::hasPermission('property unit edit') || Laratrust::hasPermission('property unit delete') || Laratrust::hasPermission('property unit show'))
                                                                <td class="Action">
                                                                    <span>
                                                                        @permission('property unit show')
                                                                            <div class="action-btn me-2">
                                                                                <a class="mx-3 btn bg-warning btn-sm align-items-center" data-url="{{ route('property-unit.show',\Crypt::encrypt($unit['id'])) }}"
                                                                                        data-ajax-popup="true"  data-size="md"
                                                                                        data-bs-toggle="tooltip" title="{{__('View')}}"
                                                                                        data-title="{{ __('Unit Detail') }}"
                                                                                        data-bs-original-title="{{ __('View') }}">
                                                                                    <i class="ti ti-eye text-white text-white"></i>
                                                                                </a>
                                                                            </div>
                                                                        @endpermission
                                                                        @permission('property unit edit')
                                                                            <div class="action-btn me-2">
                                                                                <a  class="mx-3 btn bg-info btn-sm  align-items-center"
                                                                                    data-url="{{ route('property-unit.edit',$unit['id']) }}" data-ajax-popup="true"  data-size="lg"
                                                                                    data-bs-toggle="tooltip" title=""
                                                                                    data-title="{{ __('Edit Unit') }}"
                                                                                    data-bs-original-title="{{ __('Edit') }}">
                                                                                    <i class="ti ti-pencil text-white"></i>
                                                                                </a>
                                                                            </div>
                                                                        @endpermission
                                                                        @permission('property unit delete')
                                                                            <div class="action-btn">
                                                                                {{Form::open(array('route'=>array('property-unit.destroy', $unit['id']),'class' => 'm-0'))}}
                                                                                @method('DELETE')
                                                                                    <a
                                                                                        class="mx-3 btn bg-danger btn-sm  align-items-center bs-pass-para show_confirm"
                                                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                                                        aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$unit['id']}}"><i
                                                                                            class="ti ti-trash text-white text-white"></i></a>
                                                                                {{Form::close()}}
                                                                            </div>
                                                                        @endpermission
                                                                    </span>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="property_map">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card table-card">
                                        <div class="card-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5>{{__('Property Map')}}</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row pb-4" style=" font-size: 16px; ">
                                                <div class="col-md-6 text-center"><b>{{ __('Latitude') }} : </b>{{ $property->latitude }}</div>
                                                <div class="col-md-6 text-center"><b>{{ __('Longitude') }} : </b>{{ $property->longitude }}</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <iframe style="width: inherit;height: 400px;" id="gmap_canvas" src="https://maps.google.com/maps?q={{'@'}}{{ $property->latitude }},{{ $property->longitude }}&t=&z=14&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
