@permission('property listing edit')
    <div class="action-btn me-2">
        <a href="{{ route('property-listing.edit', $propertylist->id) }}" class="mx-3 btn bg-info btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{ __('Edit') }}"> <span class="text-white"> <i class="ti ti-pencil"></i></span></a>
    </div>
@endpermission

@permission('property listing delete')
    <div class="action-btn">
        {{Form::open(array('route'=>array('property-listing.destroy', $propertylist['id']),'class' => 'm-0'))}}
        @method('DELETE')
            <a
                class="mx-3 btn bg-danger btn-sm  align-items-center bs-pass-para show_confirm"
                data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$propertylist['id']}}"><i
                    class="ti ti-trash text-white text-white"></i></a>
        {{Form::close()}}
    </div>
@endpermission