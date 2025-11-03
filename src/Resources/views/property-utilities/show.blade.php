<div class="modal-body">
    <div class="row">
        <div class="col-lg-12 table-responsive">
                <table class="table modal-table">
                    <tbody>
                    <tr>
                        <th>{{__('Property')}}</th>
                        <td>{{ $property_utility->property ? $property_utility->property->name : '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Utility Type')}}</th>
                        <td>{{ $property_utility->utility_type }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Reading Date')}}</th>
                        <td>{{ company_date_formate($property_utility->reading_date) }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Current Reading')}}</th>
                        <td>{{ $property_utility->current_reading }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Previous Reading')}}</th>
                        <td>{{ $property_utility->previous_reading }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Amount Due')}}</th>
                        <td>{{ currency_format_with_sym($property_utility->amount_due) }}</td>
                    </tr>
                </tbody>
            </table>
    </div>
</div>
</div>
