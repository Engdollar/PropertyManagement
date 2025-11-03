{{ Form::open(['url' => 'property-maintenance-request', 'method' => 'post', 'enctype' => 'multipart/form-data','class'=>'needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('tenant_id', __('Tenant'), ['class' => 'form-label']) }}<x-required></x-required>
                @if(\Auth::user()->type != 'tenant')
                    {{ Form::select('tenant_id', $tenants, null, ['class' => 'form-control', 'required' => 'required']) }}
                @else
                    @php
                        $tenant = \Workdo\PropertyManagement\Entities\Tenant::where('user_id',\Auth::user()->id)->first();
                    @endphp
                    {{ Form::text('tenant_name', \Auth::user()->name, ['class' => 'form-control', 'required' => 'required', 'readonly' => true]) }}
                    {{ Form::hidden('tenant_id', $tenant->id, ['class' => 'form-control tenant_id', 'required' => 'required', 'readonly' => true]) }}
                @endif
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('property_name', __('Property'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('property_name', null, ['class' => 'form-control', 'id' => 'property_name', 'required' => 'required', 'readonly' => true , 'placeholder' => __('Enter Property')]) }}
                {{ Form::hidden('property_id', null, ['class' => 'form-control', 'id' => 'property_id', 'required' => 'required', 'readonly' => true]) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('unit_name', __('Unit'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('unit_name', null, ['class' => 'form-control', 'id' => 'unit_name', 'required' => 'required', 'readonly' => true , 'placeholder' => __('Enter Unit')]) }}
                {{ Form::hidden('unit_id', null, ['class' => 'form-control', 'id' => 'unit_id', 'required' => 'required', 'readonly' => true]) }}
            </div>
        </div>
        @if (\Auth::user()->type != 'tenant')
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}<x-required></x-required>
                    <select class="form-control" name="status" required id="status">
                        <option value="Completed">{{__('Completed')}}</option>
                        <option value="Pending">{{__('Pending')}}</option>
                        <option value="Cancelled">{{__('Cancelled')}}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
        @else
            <input type="hidden" value="Pending" name="status" id="status">
            <div class="col-md-6">
        @endif
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
                    {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Description of Issue'), 'required' => 'required']) }}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('attachment', __('Attachment'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="choose-file">
                    <label for="Image">
                        <input type="file" class="form-control" name="attachment" id="attachment"
                            data-filename="attachment" required="required"
                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])" >
                            @php
                                $path = asset('packages/workdo/ProductService/src/Resources/assets/image/img01.jpg');
                            @endphp
                        <img id="blah" width="100" height="100" src="{{ $path }}" class="mt-3">
                    </label>
                </div>
            </div>
        </div>

        @if (module_is_active('CustomField') && !$customFields->isEmpty())
            <div class="col-md-6">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    @include('custom-field::formBuilder')
                </div>
            </div>
        @endif
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" id="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}

<script>
if("{{ \Auth::user()->type }}" != 'tenant'){
    $(document).on('change', 'select[name=tenant_id]', function() {
        var tenant_id = $(this).val();
        getproperty(tenant_id);
    });
}else{
    $(document).ready(function() {
        var tenant_id = $('.tenant_id').val();
        getproperty(tenant_id);
    });
}
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
