@permission('property contractors show')
    <div class="action-btn  me-2">
        <a class="mx-3 btn btn-sm bg-warning align-items-center"
            data-url="{{ route('property-contractors.show', $property_contractor->id) }}" data-ajax-popup="true" data-size="md"
            data-bs-toggle="tooltip" title="" data-title="{{ __('Contractor Detail') }}"
            data-bs-original-title="{{ __('View') }}">
            <i class="ti ti-eye text-white"></i>
        </a>
    </div>
@endpermission
@permission('property contractors edit')
    <div class="action-btn  me-2">
        <a class="mx-3 btn btn-sm bg-info align-items-center"
            data-url="{{ route('property-contractors.edit', $property_contractor->id) }}" data-ajax-popup="true" data-size="md"
            data-bs-toggle="tooltip" title="" data-title="{{ __('Edit Contractor') }}"
            data-bs-original-title="{{ __('Edit') }}">
            <i class="ti ti-pencil text-white"></i>
        </a>
    </div>
@endpermission
@permission('property contractors delete')
    <div class="action-btn  me-2">
        {!! Form::open([
            'method' => 'DELETE',
            'route' => ['property-contractors.destroy', $property_contractor->id],
            'id' => 'delete-form-' . $property_contractor->id,
        ]) !!}
        <a class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip"
            title="{{ __('Delete') }}" data-confirm="{{ __('Are You Sure?') }}"
            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"><i
                class="ti ti-trash text-white text-white"></i></a>
        {!! Form::close() !!}
    </div>
@endpermission
