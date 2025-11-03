{{ Form::open(['route' => 'expenses-tracking.store', 'class' => 'needs-validation', 'novalidate']) }}
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
            {{ Form::label('amount', __('Amount'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::number('amount', '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Amount')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('category', __('Category'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::text('category', '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Category')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('expense_date', __('Expense Date'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::date('expense_date', '', ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::textarea('description', '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Description'),'rows'=>3]) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}
