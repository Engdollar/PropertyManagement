{{ Form::open(['url' => 'tenant', 'method' => 'post', 'enctype' => 'multipart/form-data','class'=>'needs-validation','novalidate']) }}
<div class="modal-body">
    <h5 class="sub-title mb-3">{{ __('Basic Info') }}</h5>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Name')]) }}
                </div>
            </div>
        </div>
        <x-mobile divClass="col-lg-4 col-md-4 col-sm-6" class="form-control" name="contact" label="{{__('Contact')}}" placeholder="{{__('Enter Contact Number')}}" required></x-mobile>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('total_member', __('Total Family Member'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::number('total_member', null, ['class' => 'form-control', 'placeholder' => __('Enter Total Family Member')]) }}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::email('email', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Email')]) }}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('password', __('Password'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::password('password', ['class' => 'form-control', 'required' => 'required', 'minlength' => '6', 'placeholder' => __('Enter Password')]) }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('address', __('Address'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="input-group">
                    {{ Form::textarea('address', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Address'), 'required' => 'required']) }}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('city', __('City'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::text('city', null, ['class' => 'form-control', 'placeholder' => __('Enter City'), 'required' => 'required']) }}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('state', __('State'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::text('state', null, ['class' => 'form-control', 'placeholder' => __('Enter State'), 'required' => 'required']) }}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('country', __('Country'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::text('country', null, ['class' => 'form-control', 'placeholder' => __('Enter Country'), 'required' => 'required']) }}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('zip', __('Zip Code'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::text('zip', null, ['class' => 'form-control', 'placeholder' => __('Enter Zip Code'), 'required' => 'required']) }}
                </div>
            </div>
        </div>
        @if (module_is_active('CustomField') && !$customFields->isEmpty())
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                        @include('custom-field::formBuilder')
                    </div>
                </div>
            </div>
        @endif
    </div>

    <h5 class="sub-title mb-3">{{ __('Property Details') }}</h5>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('property_id', __('Property'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('property_id', $properties, null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('unit_id', __('Unit'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('unit_id', [], null, ['class' => 'form-control', 'id' => 'unit_id', 'required' => 'required', 'placeholder' => __('Select Unit')]) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {!! Form::label('', __('Total Rent'), ['class' => 'form-label']) !!}<x-required></x-required>
                {!! Form::number('total', null, ['class' => 'form-control total', 'required' => true, 'readonly' => true , 'placeholder' => __('Enter Total Rent')]) !!}
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {!! Form::label('', __('Rent Type'), ['class' => 'form-label']) !!}<x-required></x-required>
                {!! Form::text('rent_type', null, ['class' => 'form-control rent_type', 'required' => true, 'readonly' => true , 'placeholder' => __('Enter Rent Type')]) !!}
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {!! Form::label('', __('Security Deposit'), ['class' => 'form-label']) !!}<x-required></x-required>
                {!! Form::text('security_deposit', null, [
                    'class' => 'form-control security_deposit',
                    'required' => true,
                    'readonly' => true,
                    'placeholder' => __('Enter Security Deposit')
                ]) !!}
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {!! Form::label('', __('Maintenance Charge'), ['class' => 'form-label']) !!}<x-required></x-required>
                {!! Form::text('maintenance_charge', null, [
                    'class' => 'form-control maintenance_charge',
                    'required' => true,
                    'readonly' => true,
                    'placeholder' => __('Enter Maintenance Charge')
                ]) !!}
            </div>
        </div>


    </div>

    <h6 class="sub-title mb-3">{{ __('Document Details') }}</h6>
    @foreach ($documents as $key => $document)
        <div class="row">
            <div class="form-group col-12 d-flex">
                <div class="float-left col-4">
                    <label for="document" class="float-left pt-1 form-label">{{ $document->name }} @if ($document->is_required == 1)
                            <span class="text-danger">*</span>
                        @endif
                    </label>
                </div>
                <div class="float-right col-8">
                    <input type="hidden" name="emp_doc_id[{{ $document->id }}]" value="{{ $document->id }}">
                    <div class="choose-files ">
                        <label for="document[{{ $document->id }}]">
                            <div class=" bg-primary document "> <i
                                    class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                            </div>
                            <input type="file"
                                class="form-control file  d-none @error('document') is-invalid @enderror doc_data"
                                @if ($document->is_required == 1) data-key="{{ $key }}" required @endif
                                name="document[{{ $document->id }}]" id="document[{{ $document->id }}]"
                                data-filename="{{ $document->id . '_filename' }}"
                                onchange="document.getElementById('{{ 'blah' . $key }}').src = window.URL.createObjectURL(this.files[0])">
                        </label>

                        <p class="text-danger d-none" id="{{ 'doc_validation-' . $key }}">
                            {{ __('This filed is required.') }}</p>
                        <img id="{{ 'blah' . $key }}" width="30%" />
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" id="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}

<script>
    $(document).on('change', 'select[name=property_id]', function() {
        var property_id = $(this).val();
        getUnit(property_id);
    });

    function getUnit(did) {
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

        console.log($('option:selected', this).attr('data-url'));
        $.ajax({
            type: "POST",
            url: $('option:selected', this).attr('data-url'),
            data: {
                "unit_id": unit_id,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                $('.total').val(data[0].rent);
                $('.rent_type').val(data[0].rent_type);
                $('.security_deposit').val(data[1].security_deposit);
                $('.maintenance_charge').val(data[1].maintenance_charge);
            },
        });
    });

    $("#submit").click(function(event) {
        var isValid = true;

        $(".doc_data").each(function() {
            // Check if the file input is required
            if ($(this).prop('required')) {
                // Check if the file input is empty (no file selected)
                if (!this.files || !this.files.length) {
                    var id = '#doc_validation-' + $(this).data("key");
                    $(id).removeClass('d-none'); // Show the validation error
                    isValid = false;
                } else {
                    var id = '#doc_validation-' + $(this).data("key");
                    $(id).addClass('d-none'); // Hide the validation error if file is selected
                }
            }
        });

        if (!isValid) {
            event.preventDefault(); // Prevent form submission if any required file is missing
        }
    });
</script>

