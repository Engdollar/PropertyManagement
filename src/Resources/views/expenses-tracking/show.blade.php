<div class="modal-body">
    <div class="row">
        <div class="col-lg-12 mt-2 table-responsive">
            <table class="table modal-table">
                <tbody>
                    <tr>
                        <th>{{__('Property')}}</th>
                        <td>{{ $expense_tracking->property ? $expense_tracking->property->name : '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Amount')}}</th>
                        <td>{{ currency_format_with_sym($expense_tracking->amount) }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Category')}}</th>
                        <td>{{ $expense_tracking->category }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Expense Date')}}</th>
                        <td>{{ company_date_formate($expense_tracking->expense_date) }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Description')}}</th>
                        <td style="white-space: break-spaces;">{{ $expense_tracking->description }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
