{{ Form::model($property_contractor, ['route' => ['property-contractors.update', $property_contractor->id], 'method' => 'PUT', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
        {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<x-required></x-required>
        {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Name')]) }}
    </div>
    <x-mobile divClass="col-md-12" type="mobile_no" name="mobile_no" class="form-control"
            label="{{ __('Mobile Number') }}" placeholder="{{ __('Mobile Number') }}" value="{{ $property_contractor->mobile_no}}" id="mobileField"
            required></x-mobile>
    <div class="form-group col-md-12">
        {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}<x-required></x-required>
        {{ Form::date('start_date', null, ['class' => 'form-control', 'required' => 'required']) }}
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}<x-required></x-required>
        {{ Form::date('end_date', null, ['class' => 'form-control', 'required' => 'required']) }}
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('service_type', __('Service type'), ['class' => 'form-label']) }}<x-required></x-required>
        {{ Form::text('service_type', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Service type')]) }}
    </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}
