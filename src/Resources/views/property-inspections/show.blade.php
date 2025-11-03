<div class="modal-body">
    <div class="row">
        <div class="col-lg-12 mt-2 table-responsive">
            <table class="table modal-table">
                <tbody>
                    <tr>
                        <th>{{__('Property')}}</th>
                        <td>{{ $property_inspection->property ? $property_inspection->property->name : '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Inspection Date')}}</th>
                        <td>{{ company_date_formate($property_inspection->inspection_date) }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Inspector Name')}}</th>
                        <td>{{ $property_inspection->inspector_name }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Inspection Result')}}</th>
                        <td>{{ $property_inspection->inspection_result }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Comments')}}</th>
                        <td style="white-space: break-spaces;">{{ $property_inspection->comments }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
