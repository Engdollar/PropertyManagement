@if(!empty($maintenance))
    {{Form::model($maintenance,array('route' => array('property-maintenance-request.update', $maintenance->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data','class'=>'needs-validation','novalidate')) }}
@else
    {{ Form::open(['route' => ['property-maintenance-request.store'], 'method' => 'post']) }}
@endif
<div class="modal-body">

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('tenant_id', __('Tenant'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('tenant_id', $tenants, isset($maintenance->user_id)?$maintenance->user_id:null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('property_name', __('Property'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('property_name', isset($property->name)?$property->name:null, ['class' => 'form-control', 'id' => 'property_name', 'required' => 'required', 'readonly' => true , 'placeholder' => __('Enter Property')]) }}
                {{ Form::hidden('property_id', isset($maintenance->property_id)?$maintenance->property_id:null, ['class' => 'form-control', 'id' => 'property_id', 'required' => 'required', 'readonly' => true]) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('unit_name', __('Unit'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('unit_name', isset($unit->name)?$unit->name:null, ['class' => 'form-control', 'id' => 'unit_name', 'required' => 'required', 'readonly' => true , 'placeholder' => __('Enter Unit')]) }}
                {{ Form::hidden('unit_id', isset($maintenance->unit_id)?$maintenance->unit_id:null, ['class' => 'form-control', 'id' => 'unit_id', 'required' => 'required', 'readonly' => true]) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}<x-required></x-required>
                <select class="form-control" name="status" required id="status">
                    <option value="Completed" @if(isset($maintenance) && $maintenance->status == 'Completed') selected @endif>{{__('Completed')}}</option>
                    <option value="Pending" @if(isset($maintenance) && $maintenance->status == 'Pending') selected @endif>{{__('Pending')}}</option>
                    <option value="Cancelled" @if(isset($maintenance) && $maintenance->status == 'Cancelled') selected @endif>{{__('Cancelled')}}</option>
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('issue', __('Issue'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::text('issue', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Issue')]) }}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('description', __('Description of Issue'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="input-group">
                    {{ Form::textarea('description', isset($maintenance->description_of_issue)?$maintenance->description_of_issue:null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Description of Issue'), 'required' => 'required']) }}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('attachment', __('Attachment'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="choose-file">
                    <label for="Image">
                        <input type="file" class="form-control" name="attachment" id="attachment"
                            data-filename="attachment"
                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])" >
                        <img id="blah" width="25%" class="mt-3" src="{{ (isset($maintenance->attachment) && !empty($maintenance->attachment)? get_file('uploads/property_maintenance_image/'.$maintenance->attachment):'') }}">
                    </label>
                </div>
            </div>
        </div>
        @if (module_is_active('CustomField') && !$customFields->isEmpty())
            <div class="col-md-6">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    @include('custom-field::formBuilder', ['fildedata' => $maintenance->customField])
                </div>
            </div>
        @endif
    </div>

</div>

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" id="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}

<script>
    $(document).on('change', 'select[name=tenant_id]', function() {
        var tenant_id = $(this).val();
        getproperty(tenant_id);
    });

    function getproperty(tid) {
        $.ajax({
            url: '{{ route('property.getproperty') }}',
            type: 'POST',
            data: {
                "tenant_id": tid,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                $('#property_id').val(data[0][0].id);
                $('#property_name').val(data[0][0].name);
                $('#unit_id').val(data[1][0].id);
                $('#unit_name').val(data[1][0].name);
            }
        });
    }
</script>
