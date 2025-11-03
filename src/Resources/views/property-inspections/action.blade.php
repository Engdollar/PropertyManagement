@permission('property inspections show')
<div class="action-btn  me-2">
    <a class="mx-3 btn bg-warning btn-sm  align-items-center" data-url="{{ route('property-inspections.show',$property_inspection->id) }}"
        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="" data-title="{{ __('Inspection Detail') }}" data-bs-original-title="{{ __('View') }}">
        <i class="ti ti-eye text-white"></i>
    </a>
</div>
@endpermission
@permission('property inspections edit')
<div class="action-btn  me-2">
    <a class="mx-3 btn bg-info btn-sm  align-items-center"
        data-url="{{ route('property-inspections.edit', $property_inspection->id) }}"
        data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip"
        title="" data-title="{{ __('Edit Inspection') }}"
        data-bs-original-title="{{ __('Edit') }}">
        <i class="ti ti-pencil text-white"></i>
    </a>
</div>
@endpermission

@permission('property inspections delete')
    <div class="action-btn ">
        {!! Form::open([
            'method' => 'DELETE',
            'route' => ['property-inspections.destroy', $property_inspection->id],
            'id' => 'delete-form-' . $property_inspection->id,
        ]) !!}
        <a class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip"
            title="{{ __('Delete') }}" data-confirm="{{ __('Are You Sure?') }}" data-text="{{ __('This action can not be undone. Do you want to continue?') }}"><i
                class="ti ti-trash text-white text-white"></i></a>
        {!! Form::close() !!}
    </div>
@endpermission
