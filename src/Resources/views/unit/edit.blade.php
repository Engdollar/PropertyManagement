@if (!empty($unit))
    {{ Form::model($unit, ['route' => ['property-unit.update', $unit->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data','class'=>'needs-validation','novalidate']) }}
@else
    {{ Form::open(['route' => ['property-unit.store'], 'method' => 'post','class'=>'needs-validation','novalidate']) }}
@endif
<div class="modal-body">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('property_id', __('Property'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::select('property_id', $properties, null, ['class' => 'form-control', 'required' => 'required']) }}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('unit_name', __('Unit Name'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::text('unit_name', $unit->name, ['class' => 'form-control', 'placeholder' => __('Enter Unit Name'), 'required' => 'required']) }}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('bedroom', __('Bedroom'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::number('bedroom', null, ['class' => 'form-control', 'min' => 0, 'required' => 'required', 'placeholder' => __('Enter Total Bedroom')]) }}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('baths', __('Baths'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::number('baths', null, ['class' => 'form-control', 'min' => 0, 'required' => 'required', 'placeholder' => __('Enter Total Baths')]) }}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('kitchen', __('Kitchen'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::number('kitchen', null, ['class' => 'form-control', 'min' => 0, 'placeholder' => __('Enter Total Kitchen'), 'required' => 'required']) }}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('amenities', __('Amenities'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('amenities[]', $amenities, null, ['class' => 'form-control choices', 'id' => 'choices-multiple', 'multiple' => '', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('rent_type', __('Rent Type'), ['class' => 'form-label']) }}<x-required></x-required>
                <select class="form-control" name="rent_type" required id="rent_type">
                    <option value="Monthly" @if (isset($unit) && $unit->rent_type == 'Monthly') selected @endif>{{ __('Monthly') }}
                    </option>
                    <option value="Quarterly" @if (isset($unit) && $unit->rent_type == 'Quarterly') selected @endif>{{ __('Quarterly') }}
                    </option>
                    <option value="Yearly" @if (isset($unit) && $unit->rent_type == 'Yearly') selected @endif>{{ __('Yearly') }}
                    </option>
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('rent', __('Rent'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::number('rent', null, ['class' => 'form-control', 'min' => 0, 'placeholder' => __('Enter Rent'), 'required' => 'required']) }}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('utilities_included', __('Utilities Included'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('utilities_included', null, ['class' => 'form-control', 'placeholder' => __('Enter Utilities Included')]) }}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                <div class="input-group">
                    {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Description')]) }}
                </div>
            </div>
        </div>
        @if (module_is_active('CustomField') && !$customFields->isEmpty())
            <div class="col-md-12">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    @include('custom-field::formBuilder', ['fildedata' => $unit->customField])
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

@push('scripts')
@endpush
