<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table modal-table">
                    <tbody>
                    <tr>
                        <th>{{__('Name')}}</th>
                        <td>{{ $property_contractor->name }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Mobile Number')}}</th>
                        <td>{{ $property_contractor->mobile_no }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Service Type')}}</th>
                        <td>{{ $property_contractor->service_type }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Start Date')}}</th>
                        <td>{{ company_date_formate($property_contractor->start_date) }}</td>
                    </tr>
                    <tr>
                        <th>{{__('End Date')}}</th>
                        <td>{{ company_date_formate($property_contractor->end_date) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
