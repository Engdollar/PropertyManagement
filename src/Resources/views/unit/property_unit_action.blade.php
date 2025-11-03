@permission('property unit show')
    <div class="action-btn me-2">
        <a class="mx-3 btn bg-warning btn-sm align-items-center"
            data-url="{{ route('property-unit.show',\Crypt::encrypt($unit['id'])) }}"
            data-ajax-popup="true"  data-size="md"
            data-bs-toggle="tooltip" title="{{__('View')}}"
            data-title="{{ __('Unit Detail') }}"
            data-bs-original-title="{{ __('View') }}">
            <i class="ti ti-eye text-white text-white"></i>
        </a>
    </div>
@endpermission
@permission('property unit edit')
    <div class="action-btn me-2">
        <a  class="mx-3 btn bg-info btn-sm  align-items-center"
            data-url="{{ route('property-unit.edit',$unit['id']) }}" data-ajax-popup="true"  data-size="lg"
            data-bs-toggle="tooltip" title=""
            data-title="{{ __('Edit Unit') }}"
            data-bs-original-title="{{ __('Edit') }}">
            <i class="ti ti-pencil text-white"></i>
        </a>
    </div>
@endpermission
@permission('property unit delete')
    <div class="action-btn">
        {{Form::open(array('route'=>array('property-unit.destroy', $unit['id']),'class' => 'm-0'))}}
        @method('DELETE')
            <a
                class="mx-3 bg-danger btn btn-sm  align-items-center bs-pass-para show_confirm"
                data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$unit['id']}}"><i
                    class="ti ti-trash text-white text-white"></i></a>
        {{Form::close()}}
    </div>
@endpermission
