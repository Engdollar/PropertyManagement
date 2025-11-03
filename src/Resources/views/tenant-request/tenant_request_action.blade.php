@if ($tenant_request->status == 'Pending')
    <div class="action-btn  me-2">
        <a class="mx-3 btn bg-success btn-sm align-items-center"
            data-bs-toggle="tooltip" title="{{ __('Convert To Tenant') }}"
            data-ajax-popup="true"
            data-url="{{ route('tenant.convert', $tenant_request->id) }}"
            data-size="lg" data-title="{{ __('Convert To Tenant') }}">
            <i class="ti ti-exchange text-white"></i>
        </a>
    </div>
@endif
@permission('tenant request delete')
        <div class="action-btn">
            {{Form::open(array('route'=>array('property-tenant-request.destroy', $tenant_request['id']),'class' => 'm-0'))}}
            @method('DELETE')
                <a
                    class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para show_confirm"
                    data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                    aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$tenant_request['id']}}"><i
                        class="ti ti-trash text-white text-white"></i></a>
            {{Form::close()}}
        </div>
@endpermission