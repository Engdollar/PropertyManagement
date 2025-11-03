@if(!empty($tenant))
    {{Form::model($tenant,array('route' => array('tenant.update', $tenant->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data','class'=>'needs-validation','novalidate')) }}
@else
    {{ Form::open(['route' => ['tenant.store'], 'method' => 'post']) }}
@endif
<div class="modal-body">
    <input type="hidden" name="user_id" value="{{ $user->id}}">
    <h5 class="sub-title mb-3">{{ __('Basic Info') }}</h5>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
                {{Form::label('name',__('Name'),array('class'=>'form-label')) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{Form::text('name',!empty($user->name) ? $user->name : null,array('class'=>'form-control','required'=>'required','placeholder' => __('Enter Customer Name')))}}
                </div>
            </div>
        </div>
        <x-mobile divClass="col-lg-4 col-md-4 col-sm-6" class="form-control" name="contact" label="{{__('Contact')}}" placeholder="{{__('Enter Contact Number')}}" value="{{ !empty($user->mobile_no) ? $user->mobile_no : null }}" required></x-mobile>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('total_member', __('Total Family Member'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('total_member', !empty($tenant->total_family_member) ? $tenant->total_family_member : null, ['class' => 'form-control', 'placeholder' => __('Enter Total Family Member')]) }}
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
                    {{ Form::text('zip', !empty($tenant->pincode) ? $tenant->pincode : null, ['class' => 'form-control', 'placeholder' => __('Enter Zip Code'), 'required' => 'required']) }}
                </div>
            </div>
        </div>
        @if (module_is_active('CustomField') && !$customFields->isEmpty())
            <div class="col-md-12">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    @include('custom-field::formBuilder', ['fildedata' => $tenant->customField])
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
                {{ Form::select('unit_id', $units, null, ['class' => 'form-control', 'id' => 'unit_id', 'required' => 'required', 'placeholder' => __('Select Unit')]) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {!! Form::label('', __('Total Rent'), ['class' => 'form-label']) !!}<x-required></x-required>
                {!! Form::number('total', $unit_rent, ['class' => 'form-control total', 'required' => true, 'readonly' => true , 'placeholder' => __('Total Rent')]) !!}
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {!! Form::label('', __('Rent Type'), ['class' => 'form-label']) !!}<x-required></x-required>
                {!! Form::text('rent_type', $unit_rent_type, ['class' => 'form-control rent_type', 'required' => true, 'readonly' => true , 'placeholder' => __('Enter Rent Type')]) !!}
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {!! Form::label('', __('Security Deposit'), ['class' => 'form-label']) !!}<x-required></x-required>
                {!! Form::text('security_deposit', $security_deposit, ['class' => 'form-control security_deposit', 'required' => true, 'readonly' => true , 'placeholder' => __('Enter Security Deposit')]) !!}
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {!! Form::label('', __('Maintenance Charge'), ['class' => 'form-label']) !!}<x-required></x-required>
                {!! Form::text('maintenance_charge', $maintenance_charge, ['class' => 'form-control maintenance_charge', 'required' => true, 'readonly' => true , 'placeholder' => __('Enter Maintenance Charge')]) !!}
            </div>
        </div>
    </div>

    <h6 class="sub-title">{{ __('Document Details') }}</h6>
    @foreach ($document_types as $key => $document)
        <div class="row">
            <div class="form-group col-12 d-flex">
                <div class="float-left col-4">
                    <label for="document" class=" form-label">{{ $document->name }}
                        @if ($document->is_required == 1)
                            <span class="text-danger">*</span>
                        @endif
                    </label>
                </div>
                <div class="float-right col-8">
                    <input type="hidden" name="emp_doc_id[{{ $document->id }}]" id=""
                        value="{{ $document->id }}">
                    @php
                    if(!empty($tenant)){
                        $doc = \Workdo\PropertyManagement\Entities\TenantDocument::where('user_id',$tenant->id)->pluck('document_value','document_id');
                    }
                    $tenantdoc = isset($doc)&&!empty($doc)?$doc:[];
                    @endphp
                    <div class="choose-files">
                        <label for="document[{{ $document->id }}]">
                            <div class=" bg-primary "> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                            <input type="file" class="form-control file" data-filename="documents" @error('document') is-invalid @enderror @if (!isset($tenantdoc[$document->id]) && $document->is_required == 1) required @endif name="document[{{ $document->id }}]" id="document[{{ $document->id }}]"
                            data-filename="{{ $document->id . '_filename' }}" onchange="document.getElementById('{{'blah'.$key}}').src = window.URL.createObjectURL(this.files[0])">
                        </label>
                        <img class="max-with-120 mx-3" id="{{'blah'.$key}}" width="30%" src="{{ (isset($tenantdoc[$document->id]) && !empty($tenantdoc[$document->id])? get_file($tenantdoc[$document->id]):'') }}" />
                    </div>
                    @if (!empty($tenantdoc[$document->id]))
                            <span class="text-xs-1"><a
                                href="{{ !empty($tenantdoc[$document->id]) ? get_file($tenantdoc[$document->id]) : '' }}"
                                target="_blank"></a>
                        </span>
                    @endif
                </div>

            </div>
        </div>
    @endforeach
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Update'), ['class' => 'btn  btn-primary','id' => 'submit']) }}
</div>
{{Form::close()}}


<script>
    $(document).on('change', 'select[name=property_id]', function() {
        var property_id = $(this).val();
        getDesignation(property_id);
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
                    $('#unit_id').append('<option value="' + key + '" data-url="{{ route('property.getunitrent') }}">' + value +
                        '</option>');
                });
            }
        });
    }

    $(document).on('change', '#unit_id', function(event) {
        var unit_id = $('option:selected', this).attr('value');
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

    $("#submit").click(function() {
        $(".doc_data").each(function() {
            if(!isNaN(this.value)) {
                var id ='#doc_validation-'+$(this).data("key");
                $(id).removeClass('d-none')
                return false;
            }
        });
    });
</script>

