@permission('tenant communications show')
    <div class="action-btn  me-2">
        <a class="mx-3 btn bg-warning btn-sm  align-items-center"
            data-url="{{ route('tenant-communications.show', $tenant_communication->id) }}" data-ajax-popup="true" data-size="md"
            data-bs-toggle="tooltip" title="" data-title="{{ __('Tenant Communication Detail') }}"
            data-bs-original-title="{{ __('View') }}">
            <i class="ti ti-eye text-white"></i>
        </a>
    </div>
@endpermission
@permission('tenant communications edit')
    <div class="action-btn  me-2">
        <a class="mx-3 btn bg-info btn-sm  align-items-center"
            data-url="{{ route('tenant-communications.edit', $tenant_communication->id) }}" data-ajax-popup="true" data-size="md"
            data-bs-toggle="tooltip" title="" data-title="{{ __('Edit Tenant Communication') }}"
            data-bs-original-title="{{ __('Edit') }}">
            <i class="ti ti-pencil text-white"></i>
        </a>
    </div>
@endpermission
@permission('tenant communications delete')
    <div class="action-btn ">
        {!! Form::open([
            'method' => 'DELETE',
            'route' => ['tenant-communications.destroy', $tenant_communication->id],
            'id' => 'delete-form-' . $tenant_communication->id,
        ]) !!}
        <a class="mx-3 btn btn-sm  bg-danger align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip"
            title="{{ __('Delete') }}" data-confirm="{{ __('Are You Sure?') }}"
            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"><i
                class="ti ti-trash text-white text-white"></i></a>
        {!! Form::close() !!}
    </div>
@endpermission
