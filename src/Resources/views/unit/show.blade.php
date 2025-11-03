<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table modal-table">
                    <tbody>
                        <tr>
                            <th>{{ __('Property Name') }}</th>
                            <td>{{ !empty($unit->name) ? $unit->property->name : '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Unit Name') }}</th>
                            <td>{{ !empty($unit->name) ? $unit->name : '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Bedroom') }}</th>
                            <td>{{ !empty($unit->bedroom) ? $unit->bedroom : '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Baths') }}</th>
                            <td>{{ !empty($unit->baths) ? $unit->baths : '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Kitchen') }}</th>
                            <td>{{ !empty($unit->kitchen) ? $unit->kitchen : '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Status') }}</th>
                            <td>
                                @if (isset($unit->rentable_status) && $unit->rentable_status == 'Vacant')
                                    <dd class="ms-0 badge fix_badge bg-primary p-2">
                                        {{ !empty($unit->rentable_status) ? $unit->rentable_status : '-' }}</dd>
                                @else
                                    <dd class="ms-0 badge fix_badge bg-danger p-2">
                                        {{ !empty($unit->rentable_status) ? $unit->rentable_status : '-' }}</dd>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Rent Type') }}</th>
                            <td>{{ !empty($unit->rent_type) ? $unit->rent_type : '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Rent') }}</th>
                            <td>{{ !empty($unit->rent) ? currency_format_with_sym($unit->rent) : '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Amenities') }}</th>
                            <td>{{ !empty($unit->amenities) ? $unit->amenities : '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Utilities Included') }}</th>
                            <td>{{ !empty($unit->utilities_included) ? $unit->utilities_included : '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Description') }}</th>
                            <td><div class="text-wrap text-break">{{ !empty($unit->description) ? $unit->description : '--' }}</div></td>
                        </tr>
                        @if (!empty($customFields) && count($unit->customField) > 0)
                            @foreach ($customFields as $field)
                                <tr>
                                    <th>{{ $field->name }}</th>
                                    <td>
                                        @if ($field->type == 'attachment')
                                            <a href="{{ get_file($unit->customField[$field->id]) }}" target="_blank">
                                                <img src="{{ get_file($unit->customField[$field->id]) }}"
                                                    class="wid-75 rounded me-3">
                                            </a>
                                        @else
                                            {{ !empty($unit->customField[$field->id]) ? $unit->customField[$field->id] : '-' }}
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
