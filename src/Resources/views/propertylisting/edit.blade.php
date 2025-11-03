@extends('layouts.main')
@section('page-title')
    {{ __('Edit Listing') }}
@endsection
@section('page-breadcrumb')
    {{ __('Listing') }}
@endsection

@push('css')
    <style>
        .dropzone .avatar img {
            width: 100%;
        }
        .avatar {
            position: relative;
            color: #FFF;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            vertical-align: middle;
            font-size: 1rem;
            font-weight: 600;
            height: 3.125rem;
            width: 3.125rem;
        }
        [dir="rtl"] .end-0 {
            left: 0 !important;
            right: 0 !important;
        }
    </style>
@endpush
@php
    $logo = get_file('uploads/property_list');
@endphp
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="mb-4 col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                <div class="col-md-6">
                    <ul class="nav nav-pills nav-fill cust-nav information-tab" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="listing-details" data-bs-toggle="pill"
                                data-bs-target="#listing-details-tab" type="button">{{ __('Listing Details') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="media-details" data-bs-toggle="pill"
                                data-bs-target="#media-details-tab" type="button">{{ __('Media Details') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="unit-details" data-bs-toggle="pill"
                                data-bs-target="#unit-details-tab"
                                type="button">{{ __('Unit Information Details') }}</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            {{ Form::open(['route' => ['property.list.update', $propertylist['id']], 'id' => 'property-listing', 'class'=>'w-100 property-submit needs-validation','novalidate', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}

            <div class="card">
                <div class="card-body">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="listing-details-tab" role="tabpanel"
                            aria-labelledby="pills-user-tab-1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" id="customer-box">
                                        {{ Form::label('property_id', __('Property'), ['class' => 'form-label']) }}<x-required></x-required>
                                        {{ Form::select('property_id', $properties, !empty($propertylist->property_id) ? $propertylist->property_id : '', ['class' => 'form-control', 'id' => 'property', 'data-url' => route('property.details'), 'required' => 'required', 'placeholder' => __('Please Select')]) }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}<x-required></x-required>

                                        <select class="form-control" name="status" required id="status">
                                            <option value="0" @if (isset($propertylist) && $propertylist->status == '0') selected @endif>
                                                {{ __('Visible') }}
                                            </option>
                                            <option value="1" @if (isset($propertylist) && $propertylist->status == '1') selected @endif>
                                                {{ __('Not Visible') }}
                                            </option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('address', __('Address'), ['class' => 'form-label']) }}
                                        <input type="text" id="address" name="address" class="form-control"
                                            readonly=true,>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('country', __('Country'), ['class' => 'form-label']) }}
                                        <input type="text" id="country" name="country" class="form-control"
                                            readonly=true,>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('state', __('State'), ['class' => 'form-label']) }}
                                        <input type="text" id="state" name="state" class="form-control"
                                            readonly=true,>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('city', __('City'), ['class' => 'form-label']) }}
                                        <input type="text" id="city" name="city" class="form-control"
                                            readonly=true,>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('pincode', __('Pincode'), ['class' => 'form-label']) }}
                                        <input type="text" id="zipcode" name="pincode" class="form-control"
                                            readonly="readonly">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description" class="form-label">{{ __('Description') }}</label>
                                        {!! Form::textarea('description', null, [
                                            'class' => 'form-control',
                                            'rows' => '3',
                                            'cols' => '50',
                                            'id' => 'description',
                                            'readonly' => true,
                                        ]) !!}
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col"></div>
                                <div class="col-6 text-end">
                                    <button class="btn btn-primary d-inline-flex align-items-center"
                                        onClick="changetab('#media-details-tab')" type="button">{{ __('Next') }}<i
                                            class="ti ti-chevron-right ms-2"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="media-details-tab" role="tabpanel"
                            aria-labelledby="pills-user-tab-2">
                            <div class="col-md-12">
                                <div class="dropzone dropzone-multiple" data-toggle="dropzone1" data-dropzone-url="http://"
                                    data-dropzone-multiple>
                                    <div class="fallback">
                                        <div class="custom-file">
                                            <input type="file" name="file" id="dropzone-1"
                                                class="fcustom-file-input"
                                                onchange="document.getElementById('dropzone').src = window.URL.createObjectURL(this.files[0])"
                                                multiple>
                                            <img id="dropzone"src="" width="20%" class="mt-2" />
                                            <label class="custom-file-label"
                                                for="customFileUpload">{{ __('Choose file') }}</label>
                                        </div>
                                    </div>
                                    <ul class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush">
                                        <li class="list-group-item px-0">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <div class="avatar">
                                                        <img class="rounded" src="" alt="Image placeholder"
                                                            data-dz-thumbnail>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <h6 class="text-sm mb-1" data-dz-name>...</h6>
                                                    <p class="small text-muted mb-0" data-dz-size>
                                                    </p>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="dropdown-item" data-dz-remove>
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row gy-3 gx-3 mt-3">
                                    @foreach ($propertylist->propertyListImage as $file)
                                        <div class="col-sm-3 product_Image remove_{{ $file->id }}"
                                            data-id="{{ $file->id }}">
                                            <div
                                                class="position-relative p-2 border rounded border-primary overflow-hidden rounded">
                                                <img src="{{ isset($file->name) && !empty($file->name) && check_file($file->name) ? get_file($file->name) : '' }}"
                                                    alt="" class="w-100">
                                                <div class="position-absolute text-center top-50 end-0 start-0 ps-3 pb-3">
                                                    <a href="{{ isset($file->name) && !empty($file->name) && check_file($file->name) ? get_file($file->name) : '' }}"
                                                        download="" data-original-title="{{ __('Download') }}" data-bs-toggle="tooltip" title="{{ __('Download') }}"
                                                        class="btn btn-sm btn-primary me-1"><i
                                                            class="ti ti-download"></i></a>
                                                    <a class="btn btn-sm btn-danger text-white deleteRecord" data-bs-toggle="tooltip" title="{{ __('Delete') }}"
                                                        name="deleteRecord" data-id="{{ $file->id }}"><i
                                                            class="ti ti-trash"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button class="btn btn-outline-secondary d-inline-flex align-items-center"
                                        onClick="changetab('#listing-details-tab')" type="button"><i
                                            class="ti ti-chevron-left me-2"></i>{{ __('Previous') }}</button>
                                </div>
                                <div class="col-6 text-end">
                                    <button class="btn btn-primary d-inline-flex align-items-center"
                                        onClick="changetab('#unit-details-tab')" type="button">{{ __('Next') }}<i
                                            class="ti ti-chevron-right ms-2"></i></button>
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane fade" id="unit-details-tab" role="tabpanel"
                            aria-labelledby="pills-user-tab-3">
                            <div class="row">

                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('unit', __('Unit'), ['class' => 'form-label']) }}<x-required></x-required>
                                                {{ Form::select('unit', $units, !empty($propertylist->unit) ? $propertylist->unit : '', ['class' => 'form-control', 'id' => 'unit_id', 'required' => 'required', 'placeholder' => __('Select Unit')]) }}
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('', __('Rent Type'), ['class' => 'form-label']) !!}<x-required></x-required>
                                                {!! Form::text('rent_type', $unit_rent_type, [
                                                    'class' => 'form-control rent_type',
                                                    'required' => true,
                                                    'readonly' => true,
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('', __('Security Amount'), ['class' => 'form-label']) !!}<x-required></x-required>
                                            {!! Form::number('security_deposit', $security_deposit, [
                                                'class' => 'form-control',
                                                'id' => 'security_amount',
                                                'required' => true,
                                                'readonly' => true,
                                            ]) !!}

                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                {{ Form::label('tax', __('Tax'), ['class' => 'form-label']) }}<x-required></x-required>
                                                <div class="form-icon-user">
                                                    {{ Form::number('tax', !empty($propertylist->tax) ? $propertylist->tax : '', ['class' => 'form-control', 'placeholder' => __('Enter Tax'), 'required' => 'required']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                {{ Form::label('list_type', __('List Type'), ['class' => 'form-label']) }}<x-required></x-required>
                                                <select class="form-control" name="list_type" required id="list_type">
                                                    <option value="sales"  @if (isset($propertylist) && $propertylist->list_type == 'sales') selected @endif>{{ __('Sales') }}</option>
                                                    <option value="rental"  @if (isset($propertylist) && $propertylist->list_type == 'rental') selected @endif>{{ __('Rental') }}</option>
                                                    <option value="development"  @if (isset($propertylist) && $propertylist->list_type == 'development') selected @endif>{{ __('Development') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                {!! Form::label('', __('Rental Amount'), ['class' => 'form-label']) !!}<x-required></x-required>
                                                {!! Form::number('total', $unit_rent, ['class' => 'form-control total', 'required' => true, 'readonly' => true]) !!}
                                            </div>

                                        </div>

                                        <div class="form-group col-md-6">
                                            {!! Form::label('', __('Maintenance Charge'), ['class' => 'form-label']) !!}<x-required></x-required>
                                            {!! Form::text('maintenance_charge', $maintenance_charge, [
                                                'class' => 'form-control',
                                                'id' => 'maintenance_charge',
                                                'required' => true,
                                                'readonly' => true,
                                            ]) !!}

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                {{ Form::label('bedroom', __('Bedroom'), ['class' => 'form-label']) }}<x-required></x-required>
                                                <div class="form-icon-user">
                                                    {{ Form::number('bedroom', $bedroom, [
                                                        'id' => 'bedroom',
                                                        'class' => 'form-control',
                                                        'required' => true,
                                                        'placeholder' => __('Enter Total Bedroom'),
                                                        'readonly' => true,
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                {{ Form::label('en_suites', __('En-Suites'), ['class' => 'form-label']) }}<x-required></x-required>
                                                <div class="form-icon-user">
                                                    {{ Form::number('en_suites', !empty($propertylist->en_suites) ? $propertylist->en_suites : '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Total En-Suites')]) }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                {{ Form::label('lounge', __('Lounge'), ['class' => 'form-label']) }}<x-required></x-required>
                                                <div class="form-icon-user">
                                                    {{ Form::number('lounge', !empty($propertylist->lounge) ? $propertylist->lounge : '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Total Lounge')]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                {{ Form::label('Garage/Parking', __('Garage/Parking'), ['class' => 'form-label']) }}<x-required></x-required>
                                                <div class="form-icon-user">
                                                    {{ Form::number('garage_parking', !empty($propertylist->garage_parking) ? $propertylist->garage_parking : '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Total Garage/Parking')]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                {{ Form::label('utilities_included', __('Utilities included'), ['class' => 'form-label']) }}<x-required></x-required>
                                                <div class="form-icon-user">
                                                    {{ Form::text('utilities_included', $utilities_included, ['id' => 'utilities_included', 'class' => 'form-control', 'required' => true, 'placeholder' => __('Enter Total Utilities included'), 'readonly' => true]) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                {{ Form::label('bathrooms', __('Bathrooms'), ['class' => 'form-label']) }}<x-required></x-required>
                                                <div class="form-icon-user">
                                                    {{ Form::number('bathrooms', $baths, ['id' => 'bathrooms', 'class' => 'form-control', 'required' => true, 'placeholder' => __('Enter Total Bathrooms'), 'readonly' => true]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                {{ Form::label('kitchen', __('Kitchen'), ['class' => 'form-label']) }}<x-required></x-required>
                                                <div class="form-icon-user">
                                                    {{ Form::number('kitchen', $kitchen, ['id' => 'kitchen', 'class' => 'form-control', 'required' => true, 'placeholder' => __('Enter Total Kitchen'), 'readonly' => true]) }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                {{ Form::label('dining', __('Dining'), ['class' => 'form-label']) }}<x-required></x-required>
                                                <div class="form-icon-user">
                                                    {{ Form::number('dining', !empty($propertylist->dining) ? $propertylist->dining : '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Total Dining')]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                {{ Form::label('amenities', __('Amenities'), ['class' => 'form-label']) }}<x-required></x-required>
                                                <div class="form-icon-user">
                                                    {{ Form::text('amenities', !empty($unit->amenities) ? $unit->amenities : null, ['id' => 'amenities', 'class' => 'form-control amenities', 'required' => true, 'placeholder' => __('amenities'), 'readonly' => true]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                {{ Form::label('total_sq ', __('Total Sq'), ['class' => 'form-label']) }}<x-required></x-required>
                                                <div class="form-icon-user">
                                                    {{ Form::number('total_sq', !empty($propertylist->total_sq) ? $propertylist->total_sq : '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Total Sq')]) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div>
                                    <button class="btn btn-outline-secondary d-inline-flex align-items-center"
                                        onClick="changetab('#media-details-tab')" type="button"><i
                                            class="ti ti-chevron-left me-2"></i>{{ __('Previous') }}</button>
                                </div>
                                <div class="d-flex justify-content-end text-end">
                                    <a class="btn btn-secondary btn-submit"
                                        href="{{ route('property-listing.index') }}">{{ __('Cancel') }}</a>
                                    <button class="btn btn-primary btn-submit ms-2" type="submit"
                                        id="submit-all">{{ __('Update') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{ Form::close() }}

        </div>
    </div>
@endsection



@push('scripts')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('public/assets/css/plugins/dropzone.min.css') }}">
    <script src="{{ asset('assets/js/plugins/dropzone-amd-module.min.js') }}"></script>
    <script>
      var Dropzones = function() {
        var propertyId = $('.property_id').val();
        var e = $('[data-toggle="dropzone1"]'),
            t = $(".dz-preview");
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        e.length && (Dropzone.autoDiscover = !1, e.each(function() {
            var e, a, n, o, i;
            e = $(this), a = void 0 !== e.data("dropzone-multiple"), n = e.find(t), o = void 0, i = {
                url: "{{ route('property.list.update', [$propertylist->id]) }}",
                headers: {
                    'x-csrf-token': CSRF_TOKEN,
                },
                thumbnailWidth: null,
                thumbnailHeight: null,
                previewsContainer: n.get(0),
                previewTemplate: n.html(),
                maxFiles: 10,
                parallelUploads: 10,
                autoProcessQueue: false,
                uploadMultiple: true,
                acceptedFiles: a ? null : "image/*",
                success: function(file, response) {
                    if (response.flag == "success") {
                        toastr.success(response.msg, 'Success');
                        window.location.href = "{{ route('property-listing.index') }}";
                    } else {
                        toastr.error(response.msg, 'Error');
                    }
                },
                error: function(file, response) {
                    if (response && response.error) {
                        toastr.error(response.error, 'Error');
                    } else {
                        toastr.error(response, 'Error');
                    }
                },
                init: function() {
                    var myDropzone = this;
                    this.on("addedfile", function(e) {
                        !a && o && this.removeFile(o), o = e
                    })
                }
            }, n.html(""), e.dropzone(i)
        }))
    }();

    // $('#submit-all').on('click', function(event) {
    $(document).on("submit", ".property-submit", function (event) {
        event.preventDefault();
        var propertyId = $('.property_id').val();
        $('#submit-all').attr('disabled', true);
        var fd = new FormData();
        var files = $('[data-toggle="dropzone1"]').get(0).dropzone.getAcceptedFiles();
        $.each(files, function(key, file) {
            fd.append('multiple_files[' + key + ']', file);
        });
        var other_data = $('#property-listing').serializeArray();
        $.each(other_data, function(key, input) {
            fd.append(input.name, input.value);
        });
        $.ajax({
            url: "{{ route('property.list.update', [$propertylist->id]) }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: fd,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                if (data.flag == "success") {
                    $('#submit-all').attr('disabled', true);
                    toastrs('success', data.msg, 'success');
                    setTimeout(() => {
                        window.location.href = "{{ route('property-listing.index') }}";
                    }, 800);
                } else {
                    toastrs('Error', data.msg, 'error');
                    $('#submit-all').attr('disabled', false);
                }
            },
            error: function(data) {
                $('#submit-all').attr('disabled', false);
                // Dropzones.removeFile(file);
                if (data.error) {
                    toastrs('Error', data.error, 'error');
                } else {
                    toastrs('Error', data, 'error');
                }
            },
        });
    });



        $(".deleteRecord").click(function() {
            var id = $(this).data("id");
            var token = $("meta[name='csrf-token']").attr("content");
            $.ajax({
                url: '{{ route('property.list.images.delete', 'id') }}'.replace('id', id),
                type: 'DELETE',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function(data) {
                    if (data.success) {
                        toastrs('success', data.message, 'success');
                        $('.product_Image[data-id="' + data.id + '"]').remove();
                    } else {
                        toastrs('Error', data.error, 'error');
                    }
                }
            });
        });
    </script>
    <script>
        function changetab(tabname) {
            var someTabTriggerEl = document.querySelector('button[data-bs-target="' + tabname + '"]');
            var actTab = new bootstrap.Tab(someTabTriggerEl);
            actTab.show();
        }
    </script>
    <script>
        $('input[type=radio][name=preview_type]').change(function() {
            if (this.value === 'file') {
                $('.url').addClass('d-none');
                $('.file').removeClass('d-none');
            } else {
                $('.url').removeClass('d-none');
                $('.file').addClass('d-none');
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            var initialPropertyId = $('select[name=property_id]').val();

            $(document).on('change', 'select[name=property_id]', function() {
                var property_id = $(this).val();
                if (property_id && property_id !== initialPropertyId) {
                    getDesignation(property_id);
                }
            });
        });


        function getDesignation(did) {

            $.ajax({
                url: '{{ route('property.getunit') }}',
                type: 'POST',
                data: {
                    "property_id": did,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#unit_id').empty();
                    $('#unit_id').append(
                        '<option value="">{{ __('Select Unit') }}</option>');
                    $.each(data, function(key, value) {
                        $('#unit_id').append('<option value="' + key +
                            '" data-url="{{ route('property.getunitrent') }}">' + value +
                            '</option>');
                    });
                }
            });
        }

        $(document).on('change', '#unit_id', function(event) {
            var unit_id = $('option:selected', this).attr('value');
            $.ajax({
                type: "POST",
                url: '{{ route('property.getunitrent') }}',
                data: {
                    "unit_id": unit_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('.total').val(data[0].rent);
                    $('.rent_type').val(data[0].rent_type);
                    $('#bedroom').val(data[0].bedroom);
                    $('#utilities_included').val(data[0].utilities_included);
                    $('#bathrooms').val(data[0].baths);
                    $('#kitchen').val(data[0].kitchen);
                    $('#security_amount').val(data[1].security_deposit);
                    $('#maintenance_charge').val(data[1].maintenance_charge);
                    $('#amenities').val(data[0].amenities);

                },
            });
        });

        $("#submit").click(function() {
            $(".doc_data").each(function() {
                if (!isNaN(this.value)) {
                    var id = '#doc_validation-' + $(this).data("key");
                    $(id).removeClass('d-none')
                    return false;
                }
            });
        });
    </script>
    <script>
        $(document).on('change', '#property', function() {
            var id = $(this).val();
            var url = $(this).data('url');
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'id': id
                },
                cache: false,
                success: function(data) {

                    if (data != '') {
                        $('#address').val(data.property.address);
                        $('#country').val(data.property.country);
                        $('#state').val(data.property.state);
                        $('#city').val(data.property.city);
                        $('#zipcode').val(data.property.pincode);
                        $('#description').val(data.property.description);

                    } else {
                        $('#address').val('');
                        $('#country').val('');
                        $('#state').val('');
                        $('#city').val('');
                        $('#zipcode').val('');
                        $('#description').val('');

                    }
                },
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#property').on('change', function() {
                var id = $(this).val();
                var url = $(this).data('url');
                $.ajax({
                    url: '{{ route('property.details') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': jQuery('#token').val()
                    },
                    data: {
                        'id': id
                    },
                    cache: false,
                    success: function(data) {
                        if (data != '') {
                            $('#address').val(data.property.address);
                            $('#country').val(data.property.country);
                            $('#state').val(data.property.state);
                            $('#city').val(data.property.city);
                            $('#zipcode').val(data.property.pincode);
                            $('#description').val(data.property.description);
                        } else {
                            $('#address').val('');
                            $('#country').val('');
                            $('#state').val('');
                            $('#city').val('');
                            $('#zipcode').val('');
                            $('#description').val('');
                        }
                    },
                });
            });

            $('#property').trigger('change');
        });
    </script>
@endpush
