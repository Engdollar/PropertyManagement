{{ Form::model($property_utility, ['route' => ['property-utilities.update', $property_utility->id], 'method' => 'PUT', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('property_id', __('Property'), ['class' => 'form-label']) }}<x-required></x-required>
            <select name="property_id" class="form-control select" required>
                <option value="">{{ __('Select Property') }}</option>
                @foreach ($properties as $property)
                    <option value="{{ $property->id }}"
                        {{ isset($property_utility) && $property_utility->property_id == $property->id ? 'selected' : '' }}>
                        {{ $property->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('utility_type', __('Utility Type'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::text('utility_type', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Utility Type')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('reading_date', __('Reading Date'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::date('reading_date', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('amount_due', __('Amount Due'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::number('amount_due', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Amount Due')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('current_reading', __('Current Reading'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::text('current_reading', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Current Reading')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('previous_reading', __('Previous Reading'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::text('previous_reading', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' =>__( 'Enter Previous Reading')]) }}
        </div>



    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}
