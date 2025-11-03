@if($tenant->is_disable == 1)
    <span>
        @if($tenant['lease_end_date'] != null && $tenant['lease_end_date'] < \Carbon\Carbon::today())
            @permission('tenant show')
                <div class="action-btn me-2">
                    <a  class="mx-3 btn bg-secondary btn-sm  align-items-center"
                        data-url="{{ route('tenant.status',$tenant['id']) }}" data-ajax-popup="true"  data-size="md"
                        data-bs-toggle="tooltip" title=""
                        data-title="{{ __('Tenant Status') }}"
                        data-bs-original-title="{{ __('Action') }}">
                        <i class="ti ti-caret-right text-white"></i>
                    </a>
                </div>
            @endpermission
        @endif
        @if (!empty($tenant['tenant_id']))
            @permission('tenant show')
            <div class="action-btn me-2">
                <a href="{{ route('tenant.show',\Crypt::encrypt($tenant['id'])) }}" class="mx-3 bg-warning btn btn-sm align-items-center"
                data-bs-toggle="tooltip" title="{{__('View')}}">
                    <i class="ti ti-eye text-white text-white"></i>
                </a>
            </div>
            @endpermission
        @endif
        @if (\Auth::user()->type != 'tenant')
            @permission('tenant edit')
                <div class="action-btn me-2">
                    <a  class="mx-3 btn bg-info btn-sm  align-items-center"
                        data-url="{{ route('tenant.edit',$tenant['id']) }}" data-ajax-popup="true"  data-size="lg"
                        data-bs-toggle="tooltip" title=""
                        data-title="{{ __('Edit Tenant') }}"
                        data-bs-original-title="{{ __('Edit') }}">
                        <i class="ti ti-pencil text-white"></i>
                    </a>
                </div>
            @endpermission
            @if (!empty($tenant['tenant_id']))
                @permission('tenant delete')
                    <div class="action-btn">
                        {{Form::open(array('route'=>array('tenant.destroy', $tenant['id']),'class' => 'm-0'))}}
                        @method('DELETE')
                            <a
                                class="mx-3 btn bg-danger btn-sm  align-items-center bs-pass-para show_confirm"
                                data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$tenant['id']}}"><i
                                    class="ti ti-trash text-white text-white"></i></a>
                        {{Form::close()}}
                    </div>
                @endpermission
            @endif
        @endif
    </span>
@else
    <div class="text-center">
        <i class="ti ti-lock"></i>
    </div>
@endif