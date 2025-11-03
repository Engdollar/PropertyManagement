<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table modal-table">
                    <tbody>
                        <tr>
                            <th>{{ __('Tenant') }}</th>
                            <td>{{ !empty($tenant) ? $tenant[0] : '' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Property') }}</th>
                            <td>
                                {{ !empty($property->name) ? $property->name : '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Unit') }}</th>
                            <td>{{ !empty($unit->name) ? $unit->name : '' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Status') }}</th>
                            <td>
                                @if (isset($maintenance->status) && $maintenance->status == 'Completed')
                                    <span class="badge fix_badge bg-primary p-2 px-3">
                                        {{ !empty($maintenance->status) ? $maintenance->status : '' }}</span>
                                @elseif(isset($maintenance->status) && $maintenance->status == 'Pending')
                                    <span class="badge fix_badge bg-warning p-2 px-3">
                                        {{ !empty($maintenance->status) ? $maintenance->status : '' }}</span>
                                @else
                                    <span class="badge fix_badge bg-danger p-2 px-3">
                                        {{ !empty($maintenance->status) ? $maintenance->status : '' }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Issue') }}</th>
                            <td>{{ !empty($maintenance->issue) ? $maintenance->issue : '' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Description of Issue') }}</th>
                            <td>{{ !empty($maintenance->description_of_issue) ? $maintenance->description_of_issue : '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Attachment') }}</th>
                            <td><a href="{{ !empty($maintenance->attachment) ? get_file('uploads/property_maintenance_image/' . $maintenance->attachment) : '' }}"
                                    target="_blank">
                                    <img src="{{ !empty($maintenance->attachment) ? get_file('uploads/property_maintenance_image/' . $maintenance->attachment) : '' }}"
                                        class="wid-150 rounded me-3">
                                </a></td>
                        </tr>
                        @if (!empty($customFields) && count($maintenance->customField) > 0)
                            @foreach ($customFields as $field)
                                <tr>
                                    <th>{{ $field->name }}</th>
                                    <td>
                                        @if ($field->type == 'attachment')
                                            <a href="{{ get_file($maintenance->customField[$field->id]) }}" target="_blank">
                                                <img src="{{ get_file($maintenance->customField[$field->id]) }}"
                                                    class="wid-75 rounded me-3">
                                            </a>
                                        @else
                                            {{ !empty($maintenance->customField[$field->id]) ? $maintenance->customField[$field->id] : '-' }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
