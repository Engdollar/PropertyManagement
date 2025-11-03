@permission('property invoice show')
    <div class="action-btn me-2">
        <a href="{{ route('property-invoice.show', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id)) }}"
            data-bs-toggle="tooltip" title="{{ __('View') }}"
            class="mx-3 btn bg-warning btn-sm align-items-center text-white "
            data-title="{{ __('Invoice Details') }}">
            <i class="ti ti-eye"></i>
        </a>
    </div>
@endpermission
@permission('property invoice delete')
    <div class="action-btn">
        {!! Form::open(['method' => 'DELETE', 'route' => ['property-invoice.destroy', $invoice->id]]) !!}
        <a href="#!"
            class="mx-3 btn btn-sm  bg-danger align-items-center text-white show_confirm"
            data-bs-toggle="tooltip" title='Delete'>
            <i class="ti ti-trash"></i>
        </a>
        {!! Form::close() !!}
    </div>
@endpermission
