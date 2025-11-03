@permission('maintenance request show')
    <div class="action-btn me-2">
        <a class="mx-3 btn bg-warning btn-sm align-items-center"
            data-url="{{ route('property-maintenance-request.show',\Crypt::encrypt($maintenance['id'])) }}" data-ajax-popup="true"  data-size="lg"
            data-bs-toggle="tooltip" title="{{ __('View') }}"
            data-title="{{ __('Maintenance Request Detail') }}"
            data-bs-original-title="{{ __('View') }}">
            <i class="ti ti-eye text-white text-white"></i>
        </a>
    </div>
@endpermission
@permission('maintenance request edit')
    <div class="action-btn me-2">
        <a  class="mx-3 btn bg-info btn-sm  align-items-center"
            data-url="{{ route('property-maintenance-request.edit',$maintenance['id']) }}" data-ajax-popup="true"  data-size="lg"
            data-bs-toggle="tooltip" title=""
            data-title="{{ __('Edit Maintenance Request') }}"
            data-bs-original-title="{{ __('Edit') }}">
            <i class="ti ti-pencil text-white"></i>
        </a>
    </div>
@endpermission
@permission('maintenance request delete')
    <div class="action-btn">
        {{Form::open(array('route'=>array('property-maintenance-request.destroy', $maintenance['id']),'class' => 'm-0'))}}
        @method('DELETE')
            <a class="mx-3 btn bg-danger btn-sm  align-items-center bs-pass-para show_confirm"
                data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$maintenance['id']}}"><i
                class="ti ti-trash text-white text-white"></i></a>
        {{Form::close()}}
    </div>
@endpermission
