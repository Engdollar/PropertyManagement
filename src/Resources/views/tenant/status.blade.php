<div class="modal-body">
    <h6 class="sub-title">{{ __('Basic Info') }}</h6>
    <dl class="row mt-4 align-items-center">
        <dt class="col-sm-4 h6 text-sm">{{ __('Name') }}</dt>
        <dd class="col-sm-8 text-sm ms-0"> {{ !empty($tenant->name) ? $tenant->name : '' }}</dd>
        <dt class="col-sm-4 h6 text-sm">{{ __('Email') }}</dt>
        <dd class="col-sm-8 text-sm ms-0"> {{ !empty($tenant->email) ? $tenant->email  : '' }}</dd>
        <dt class="col-sm-4 h6 text-sm">{{ __('Phone Number') }}</dt>
        <dd class="col-sm-8 text-sm ms-0">{{ !empty($tenant->contact) ? $tenant->contact : '' }}</dd>
    </dl>

    <h6 class="sub-title">{{ __('Property Details') }}</h6>
    <dl class="row mt-4 align-items-center">
        <dt class="col-sm-4 h6 text-sm">{{ __('Property Name') }}</dt>
        <dd class="col-sm-8 text-sm ms-0"> {{ !empty($property->name) ? $property->name : '' }}</dd>
        <dt class="col-sm-4 h6 text-sm">{{ __('Unit Name') }}</dt>
        <dd class="col-sm-8 text-sm ms-0"> {{ !empty($unit->name) ? $unit->name  : '' }}</dd>
        <dt class="col-sm-4 h6 text-sm">{{ __('Address') }}</dt>
        <dd class="col-sm-8 text-sm ms-0">{{ !empty($property->address) ? $property->address : '' }}</dd>
    </dl>

</div>

<div class="modal-footer">
    <input type="button" id="submit-status" value="{{ __('Cancel') }}" class="btn btn-danger">
    <input type="submit" id="submit-all" value="{{ __('Renew') }}" class="btn btn-primary">
</div>

<script>
    $('#submit-all').on('click', function(event) {
        event.preventDefault();
        $('#submit-all').attr('disabled', true);
        $.ajax({
            url: '{{ route('tenant.renew') }}',
            type: 'POST',
            data: {
                    "tenant_id": "{{ $tenant['tenant_id'] }}",
                    "unit_id": "{{ $tenant['unit_id'] }}",
                    "status": "renew",
                    "_token": "{{ csrf_token() }}",
                },
            success: function(data) {
                if (data.flag == "success") {
                    $('#submit-all').attr('disabled', true);
                    toastrs('success', data.msg, 'success');
                    setTimeout(function() {
                        window.location.href = "{{ route('tenant.index') }}";
                    }, 800);
                } else {
                    toastrs('Error', data.msg, 'error');
                    $('#submit-all').attr('disabled', false);
                }
            },
            error: function(data) {
                $('#submit-all').attr('disabled', false);
                // Dropzones.removeFile(file);
                if (data.error) {
                    toastrs('Error', data.error, 'error');
                } else {
                    toastrs('Error', data, 'error');
                }
            },
        });
    });

    $('#submit-status').on('click', function(event) {
        event.preventDefault();
        $('#submit-status').attr('disabled', true);
        $.ajax({
            url: '{{ route('tenant.renew') }}',
            type: 'POST',
            data: {
                    "tenant_id": "{{ $tenant['tenant_id'] }}",
                    "unit_id": "{{ $tenant['unit_id'] }}",
                    "status": "cancel",
                    "_token": "{{ csrf_token() }}",
                },
            success: function(data) {
                if (data.flag == "success") {
                    $('#submit-status').attr('disabled', true);
                    toastrs('success', data.msg, 'success');
                    setTimeout(function() {
                        window.location.href = "{{ route('tenant.index') }}";
                    }, 800);
                } else {
                    toastrs('Error', data.msg, 'error');
                    $('#submit-status').attr('disabled', false);
                }
            },
            error: function(data) {
                $('#submit-status').attr('disabled', false);
                // Dropzones.removeFile(file);
                if (data.error) {
                    toastrs('Error', data.error, 'error');
                } else {
                    toastrs('Error', data, 'error');
                }
            },
        });
    });
</script>