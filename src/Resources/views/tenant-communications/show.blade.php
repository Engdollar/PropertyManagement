<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table modal-table">
                    <tbody>
                    <tr>
                        <th>{{__('Tenant')}}</th>
                        <td>{{ $tenant_communication->tenant ? $tenant_communication->tenant->user->name : '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Communication Date')}}</th>
                        <td>{{ company_date_formate($tenant_communication->communication_date) }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Sender')}}</th>
                        <td>{{ $tenant_communication->sender }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Message')}}</th>
                        <td style="white-space: break-spaces;">{{ $tenant_communication->message }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
