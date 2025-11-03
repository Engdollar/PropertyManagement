{{ Form::open(['route' => 'property-inspections.store', 'class' => 'needs-validation', 'novalidate']) }}
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
            {{ Form::label('inspection_date', __('Inspection Date'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::date('inspection_date', '', ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('inspector_name', __('Inspector Name'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::text('inspector_name', '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Inspector Name')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('inspection_result', __('Inspection Result'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::text('inspection_result', '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Inspection Result')]) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('comments', __('Comments'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::textarea('comments', '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Comments'),'rows'=>3]) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}
