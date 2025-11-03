{{ Form::open(['route' => 'tenant-communications.store', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('tenant_id', __('Tenant'), ['class' => 'form-label']) }}<x-required></x-required>
            <select name="tenant_id" class="form-control select" required>
                <option value="">{{ __('Select Tenant') }}</option>
                @foreach ($tenants as $tenant)
                    <option value="{{ $tenant->id }}">{{ $tenant->user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('communication_date', __('Communication Date'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::date('communication_date', '', ['class' => 'form-control', 'required' => 'required']) }}
        </div>

        <div class="form-group col-md-12">
            {{ Form::label('sender', __('Sender'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::text('sender', '', ['class' => 'form-control', 'required' => 'required', 'placeholder' =>__('Enter Sender')]) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('message', __('Message'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::textarea('message', '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Message'),'rows'=>3]) }}
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}
