@permission('expenses tracking show')
<div class="action-btn me-2">
    <a class="mx-3 btn btn-sm bg-warning align-items-center" data-url="{{ route('expenses-tracking.show',$expense_tracking->id) }}"
         data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="" data-title="{{ __('Expense Tracking Detail') }}"
         data-bs-original-title="{{ __('View') }}">
        <i class="ti ti-eye text-white"></i>
    </a>
</div>
@endpermission
@permission('expenses tracking edit')
<div class="action-btn me-2">
    <a class="mx-3 btn bg-info btn-sm  align-items-center"
        data-url="{{ route('expenses-tracking.edit', $expense_tracking->id) }}"
        data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip"
        title="" data-title="{{ __('Edit Expense Tracking') }}"
        data-bs-original-title="{{ __('Edit') }}">
        <i class="ti ti-pencil text-white"></i>
    </a>
</div>
@endpermission

@permission('expenses tracking delete')
    <div class="action-btn">
        {!! Form::open([
            'method' => 'DELETE',
            'route' => ['expenses-tracking.destroy', $expense_tracking->id],
            'id' => 'delete-form-' . $expense_tracking->id,
        ]) !!}
        <a class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip"
            title="{{ __('Delete') }}" data-confirm="{{ __('Are You Sure?') }}" data-text="{{ __('This action can not be undone. Do you want to continue?') }}"><i
                class="ti ti-trash text-white text-white"></i></a>
        {!! Form::close() !!}
    </div>
@endpermission
