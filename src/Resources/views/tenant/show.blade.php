@extends('layouts.main')
@section('page-title')
    {{ __('Tenant Detail') }}
@endsection
@section('page-breadcrumb')
    {{ $tenant['name'] }}
@endsection
@push('css')
    <style>
        .cus-card {
            min-height: 204px;
        }
    </style>
@endpush
@push('scripts')
    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script>
        var filename = $('#filename').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 4,
                    dpi: 72,
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A4'
                }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
@endpush
@php
    $company_settings = getCompanyAllSetting();

@endphp
@section('page-action')
    <div>
        @php
            $user_id = !empty($tenant->user_id) ? $tenant->user_id : null;
        @endphp
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade active show" id="tenant-details" role="tabpanel"
                    aria-labelledby="pills-user-tab-1">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 mb-0">
                                <div class="card-body">
                                    <h5 class="card-title mb-2">{{ __('Tenant Info') }}</h5>
                                    <p class="card-text mb-0">{{ $tenant['name'] }}</p>
                                    <p class="card-text mb-0">{{ $tenant['email'] }}</p>
                                    <p class="card-text mb-0">{{ $tenant['contact'] }}</p>
                                    <p class="card-text mb-0">{{ $tenant['address'] }}</p>
                                    <p class="card-text mb-0">
                                        {{ $tenant['city'] . ', ' . $tenant['state'] . ', ' . $tenant['country'] }}
                                    </p>
                                    <p class="card-text mb-0">{{ $tenant['zip'] }}</p>
                                    @if (!empty($customFields) && count($tenant->customField) > 0)
                                        @foreach ($customFields as $field)
                                            <tr>
                                                <th>{{ $field->name }}</th>
                                                <td>
                                                    @if ($field->type == 'attachment')
                                                        <a href="{{ get_file($tenant->customField[$field->id]) }}"
                                                            target="_blank">
                                                            <img src="{{ get_file($tenant->customField[$field->id]) }}"
                                                                class="wid-75 rounded me-3">
                                                        </a>
                                                    @else
                                                        {{ !empty($tenant->customField[$field->id]) ? $tenant->customField[$field->id] : '-' }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 mb-0">
                                <div class="card-body">
                                    @php
                                        $property = \Workdo\PropertyManagement\Entities\Property::find(
                                            $tenant['property_id'],
                                        );
                                        $unit = \Workdo\PropertyManagement\Entities\PropertyUnit::find(
                                            $tenant['unit_id'],
                                        );
                                    @endphp
                                    <h5 class="card-title mb-2">{{ __('Property Info') }}</h5>
                                    <address class="mb-0">
                                        <dl class="row align-items-center mb-0">
                                            <dt class="col-lg-4 col-sm-6 h6 text-sm">{{ __('Property Name') }}</dt>
                                            <dd class="col-lg-8 col-sm-6 text-sm ms-0">
                                                {{ !empty($property->name) ? $property->name : '' }}</dd>
                                            <dt class="col-lg-4 col-sm-6 h6 text-sm">{{ __('Unit Name') }}</dt>
                                            <dd class="col-lg-8 col-sm-6 text-sm ms-0">
                                                {{ !empty($unit->name) ? $unit->name : '' }}</dd>
                                            <dt class="col-lg-4 col-sm-6 h6 text-sm">{{ __('Address') }}</dt>
                                            <dd class="col-lg-8 col-sm-6 text-sm ms-0">
                                                {{ !empty($property->address) ? $property->address : '' }}</dd>
                                        </dl>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body table-border-style table-border-style">
                                    <h5 class="d-inline-block mb-3">{{ __('Document Info') }}</h5>
                                    <div class="table-responsive">
                                        <table class="table overflow-hidden rounded">
                                            <thead>
                                                <tr>
                                                    <th class="bg-primary text-white">{{ __('No.') }}</th>
                                                    <th class="bg-primary text-white">{{ __('Document Type') }}</th>
                                                    <th class="bg-primary text-white">{{ __('Document images') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($documents) > 0)
                                                    @php
                                                        if (!empty($tenant)) {
                                                            $doc = \Workdo\PropertyManagement\Entities\TenantDocument::where(
                                                                'user_id',
                                                                $tenant->tenant_id,
                                                            )->pluck('document_value', 'document_id');
                                                        }
                                                        $tenantdoc = isset($doc) && !empty($doc) ? $doc : [];
                                                        $i = 1;
                                                    @endphp
                                                    @forelse ($documents as $key => $document)
                                                        <tr>
                                                            <td>{{ $i++ }}</td>
                                                            <td>{{ $document->name }}</td>
                                                            <td>
                                                                <a href="{{ !empty($tenantdoc[$document->id]) ? get_file($tenantdoc[$document->id]) : '' }}"
                                                                    target="_blank">
                                                                    <img src="{{ !empty($tenantdoc[$document->id]) ? get_file($tenantdoc[$document->id]) : '' }}"
                                                                        class="wid-75 rounded me-3">
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        @include('layouts.nodatafound')
                                                    @endforelse
                                                @else
                                                    <div class="text-center">
                                                        No Document Type Added.!
                                                    </div>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
