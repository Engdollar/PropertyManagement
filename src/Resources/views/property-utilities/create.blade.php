{{ Form::open(['route' => 'property-utilities.store', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('property_id', __('Property'), ['class' => 'form-label']) }}<x-required></x-required>
            <select name="property_id" class="form-control select" required>
                <option value="">{{ __('Select Property') }}</option>
                    @foreach ($properties as $property)
                        <option value="{{ $property->id }}">{{ $property->name }}</option>
                    @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('utility_type', __('Utility Type'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::text('utility_type', '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Utility Type')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('reading_date', __('Reading Date'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::date('reading_date', '', ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('amount_due', __('Amount Due'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::number('amount_due', '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Amount Due')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('current_reading', __('Current Reading'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::text('current_reading', '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Current Reading')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('previous_reading', __('Previous Reading'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::text('previous_reading', '', ['class' => 'form-control', 'required' => 'required', 'placeholder' =>__( 'Enter Previous Reading')]) }}
        </div>



    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}
