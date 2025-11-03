<style>
    .dropzone .avatar img{
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
</style>
{{ Form::open(['route' => 'property.store', 'method' => 'post', 'id' => 'property', 'enctype' => 'multipart/form-data','class'=>'submit-property needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {!! Form::label('', __('Property Name'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::text('property_name', null, [
                'class' => 'form-control',
                'placeholder' => __('Enter Property Name'),
                'required' => true,
            ]) !!}
        </div>
        <div class="form-group col-md-12">
            {!! Form::label('', __('Address'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::textarea('address', null, [
                'class' => 'form-control',
                'placeholder' => __('Enter Address'),
                'rows' => '3',
                'cols' => '50',
                'id' => 'property-address',
                'required' => true,
            ]) !!}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('City'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::text('city', null, [
                'class' => 'form-control',
                'placeholder' => __('Enter City'),
                'required' => true,
            ]) !!}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('State'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::text('state', null, [
                'class' => 'form-control',
                'placeholder' => __('Enter State'),
                'required' => true,
            ]) !!}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('Country'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::text('country', null, [
                'class' => 'form-control',
                'placeholder' => __('Enter Country'),
                'required' => true,
            ]) !!}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('Zip Code'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::number('zipcode', null, [
                'class' => 'form-control',
                'placeholder' => __('Enter Zip Code'),
                'required' => true,
            ]) !!}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('Latitude'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::number('latitude', null, [
                'class' => 'form-control latitude',
                'placeholder' => __('Enter Latitude'),
                'step' => '0.000001',
                'required' => true,
            ]) !!}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('Longitude'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::number('longitude', null, [
                'class' => 'form-control longitude',
                'placeholder' => __('Enter Longitude'),
                'step' => '0.000001',
                'required' => true,
            ]) !!}
        </div>
        <div class="form-group col-md-12">
            {!! Form::label('', __('Description'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::textarea('description', null, [
                'class' => 'form-control',
                'placeholder' => __('Enter Description'),
                'rows' => '3',
                'cols' => '50',
                'id' => 'property-desc',
                'required' => true,
            ]) !!}
        </div>
        <div class="form-group col-md-12">
            <iframe style="width: inherit;" id="gmap_canvas" src="https://maps.google.com/maps?q=@40.7071972,-74.0188321&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe> {{-- height: 200px; --}}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('Security Deposit'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::number('security_deposit', null, [
                'class' => 'form-control',
                'placeholder' => __('Enter Security Deposit'),
                'min' => '0',
                'required' => true,
            ]) !!}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('Maintenance Charge'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::number('maintenance_charge', null, [
                'class' => 'form-control',
                'placeholder' => __('Enter Maintenance Charge'),
                'min' => '0',
                'required' => true,
            ]) !!}
        </div>
        <div class="col-md-12">
            <div class="dropzone dropzone-multiple border-primary" data-toggle="dropzone1" data-dropzone-url="http://"
                data-dropzone-multiple>
                <div class="fallback">
                    <div class="custom-file">
                        <input type="file" name="file" id="dropzone-1" class="fcustom-file-input"
                            onchange="document.getElementById('dropzone').src = window.URL.createObjectURL(this.files[0])"
                            multiple>
                        <img id="dropzone"src="" width="20%" class="mt-2" />
                        <label class="custom-file-label" for="customFileUpload">{{ __('Choose file') }}</label>
                    </div>
                </div>
                <ul class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush">
                    <li class="list-group-item px-0">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar">
                                    <img class="rounded" src="" alt="Image placeholder" data-dz-thumbnail>
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
        <div class="modal-footer pb-0">
            <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
            <input type="submit" value="{{__('Create')}}" id="submit-all" class="btn btn-primary bg-primary">
        </div>
    </div>
</div>
{!! Form::close() !!}
<link rel="stylesheet" href="{{ asset('public/assets/css/plugins/dropzone.min.css') }}">
<script src="{{ asset('assets/js/plugins/dropzone-amd-module.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/dropzone-amd-module.min.js') }}"></script>
<script>
    var Dropzones = function() {
        var e = $('[data-toggle="dropzone1"]'),
            t = $(".dz-preview");
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        e.length && (Dropzone.autoDiscover = !1, e.each(function() {
            var e, a, n, o, i;
            e = $(this), a = void 0 !== e.data("dropzone-multiple"), n = e.find(t), o = void 0, i = {
                url: "{{ route('property.store') }}",
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
                        toastrs('success', response.msg, 'success');
                        window.location.href = "{{ route('property.index') }}";
                    } else {
                        toastrs('Error', response.msg, 'error');
                    }
                },
                error: function(file, response) {
                    // Dropzones.removeFile(file);
                    if (response.error) {
                        toastrs('Error', response.error, 'error');
                    } else {
                        toastrs('Error', response, 'error');
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
    }()

    $(document).on("submit", ".submit-property", function (event) {
        event.preventDefault();
        $('#submit-all').attr('disabled', true);
        var fd = new FormData();
        var files = $('[data-toggle="dropzone1"]').get(0).dropzone.getAcceptedFiles();
        $.each(files, function(key, file) {
            fd.append('multiple_files[' + key + ']', $('[data-toggle="dropzone1"]')[0].dropzone
                .getAcceptedFiles()[key]); // attach dropzone image element
        });
        var other_data = $('#property').serializeArray();
        $.each(other_data, function(key, input) {
            fd.append(input.name, input.value);
        });
        $.ajax({
            url: "{{ route('property.store') }}",
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
                        window.location.href = "{{ route('property.index') }}";
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
</script>
<script>
    $(document).on('keyup change', '.latitude',function(){
        var longitude = $('.longitude').val();
        var latitude = $(this).val();

        document.getElementById('gmap_canvas').src = 'https://maps.google.com/maps?q='+latitude+','+longitude+'&t=&z=13&ie=UTF8&iwloc=&output=embed';
    });
    $(document).on('keyup change', '.longitude',function(){
        var latitude = $('.latitude').val();
        var longitude = $(this).val();

        document.getElementById('gmap_canvas').src = 'https://maps.google.com/maps?q='+latitude+','+longitude+'&t=&z=13&ie=UTF8&iwloc=&output=embed';
    });
</script>
