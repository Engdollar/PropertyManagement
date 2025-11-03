<!DOCTYPE html>
<html lang="en" dir="{{ isset($settings['site_rtl']) && $settings['site_rtl'] == 'on' ? 'rtl' : '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        {{ \Workdo\PropertyManagement\Entities\PropertyInvoice::tenantNumberFormat($invoice->id) }}
        |
        {{ !empty(company_setting('title_text', $invoice->created_by, $invoice->workspace)) ? company_setting('title_text', $invoice->created_by, $invoice->workspace) : (!empty(admin_setting('title_text')) ? admin_setting('title_text') : 'WorkDo') }}
    </title>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">
    <style type="text/css">
        :root {
            /* --theme-color: #ff8d8d; */
            --theme-color: {{ $color }};
            --white: #ffffff;
            --black: #000000;
        }

        body {
            font-family: 'Lato', sans-serif;
        }

        p,
        li,
        ul,
        ol {
            margin: 0;
            padding: 0;
            list-style: none;
            line-height: 1.5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table tr th {
            padding: 0.75rem;
            text-align: left;
        }

        table tr td {
            padding: 0.75rem;
            text-align: left;
        }

        table th small {
            display: block;
            font-size: 12px;
        }

        .invoice-preview-main {
            max-width: 700px;
            width: 100%;
            margin: 0 auto;
            background: #ffff;
            box-shadow: 0 0 10px #ddd;
        }

        .invoice-logo {
            max-width: 200px;
            width: 100%;
        }

        .invoice-header table td {
            padding: 15px 30px;
        }

        .text-right {
            text-align: right;
        }

        .no-space tr td {
            padding: 0;
            white-space: nowrap;
        }

        .vertical-align-top td {
            vertical-align: top;
        }

        .view-qrcode {
            max-width: 139px;
            height: 139px;
            width: 100%;
            margin-left: auto;
            margin-top: 15px;
            background: var(--white);
            padding: 13px;
            /* padding: 9px; */
            border-radius: 10px;
        }

        .view-qrcode img {
            width: 100%;
            height: 100%;
        }

        .invoice-body {
            padding: 30px 25px 0;
        }

        table.add-border tr {
            /* border-top: 1px solid var(--theme-color); */
            border-top: 1px solid #000000;
        }

        tfoot tr:first-of-type {
            /* border-bottom: 1px solid var(--theme-color); */
            border-bottom: 1px solid #000000;
        }

        .total-table tr:first-of-type td {
            padding-top: 0;
        }

        .total-table tr:first-of-type {
            border-top: 0;
        }

        .sub-total {
            padding-right: 0;
            padding-left: 0;
        }

        .border-0 {
            border: none !important;
        }

        .invoice-summary td,
        .invoice-summary th {
            font-size: 13px;
            font-weight: 600;
        }

        .invoice-summary th {
            font-size: 15px;
            font-weight: 600;
        }

        .total-table td:last-of-type {
            width: 146px;
        }

        .invoice-footer {
            padding: 15px 20px;
        }

        .itm-description td {
            padding-top: 0;
        }

        html[dir="rtl"] table tr td,
        html[dir="rtl"] table tr th {
            text-align: right;
        }

        html[dir="rtl"] .text-right {
            text-align: left;
        }

        html[dir="rtl"] .view-qrcode {
            margin-left: 0;
            margin-right: auto;
        }

        p:not(:last-of-type) {
            margin-bottom: 15px;
        }

        .invoice-summary p {
            margin-bottom: 0;
        }
        .wid-75 {
            width: 75px;
        }
    </style>
</head>

<body>
    <div class="invoice-preview-main" id="boxes">
        <div class="invoice-header" style="background-color: var(--theme-color); color: {{ $font_color }};">
            <table>
                <tbody>
                    <tr>
                        <td>
                            <img class="invoice-logo" src="{{ $img }}" alt="">
                        </td>
                        <td class="text-right">
                            <h3 style="text-transform: uppercase; font-size: 40px; font-weight: bold; ">
                                {{ __('INVOICE') }}</h3>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="vertical-align-top">
                <tbody>
                    <tr>
                        <td>
                            <b>{{__('FROM')}} :</b>
                            <p>
                                @if (!empty($settings['company_name']))
                                    {{ $settings['company_name'] }}
                                @endif
                                <br>
                                @if (!empty($settings['company_email']))
                                    {{ $settings['company_email'] }}
                                @endif
                                <br>
                                @if (!empty($settings['company_telephone']))
                                    {{ $settings['company_telephone'] }}
                                @endif
                                <br>
                                @if (!empty($settings['company_address']))
                                    {{ $settings['company_address'] }}
                                @endif
                                @if (!empty($settings['company_city']))
                                    <br> {{ $settings['company_city'] }},
                                @endif
                                @if (!empty($settings['company_state']))
                                    {{ $settings['company_state'] }}
                                @endif
                                @if (!empty($settings['company_country']))
                                    <br>{{ $settings['company_country'] }}
                                @endif
                                @if (!empty($settings['company_zipcode']))
                                    - {{ $settings['company_zipcode'] }}
                                @endif
                                <br>
                                @if (!empty($settings['registration_number']))
                                    {{ __('Registration Number') }} : {{ $settings['registration_number'] }}
                                @endif
                                <br>
                            </p>
                        </td>
                        <td style="width: 60%;">
                            <table class="no-space">
                                <tbody>
                                    <tr>
                                        <td><b>{{ __('Number: ') }}</b></td>
                                        <td class="text-right">
                                            {{ \Workdo\PropertyManagement\Entities\PropertyInvoice::tenantNumberFormat($invoice->id) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ __('Issue Date:') }}</b></td>
                                        <td class="text-right">
                                            {{ company_date_formate($invoice->issue_date, $invoice->created_by, $invoice->workspace) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ __('Due Date') }}:</b></td>
                                        <td class="text-right">
                                            {{ company_date_formate($invoice->due_date, $invoice->created_by, $invoice->workspace) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="view-qrcode">
                                                {!! DNS2D::getBarcodeHTML(
                                                    route('property-invoice.show', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id)),
                                                    'QRCODE',
                                                    2,
                                                    2,
                                                ) !!}
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="invoice-body">
            <table>
                <tbody>
                    <tr>
                        @if (!empty($tenant_details->name) && !empty($tenant->address) && !empty($tenant->pincode))
                            <td>
                                <strong style="margin-bottom: 10px; display:block;">{{ __('Bill To') }}:</strong>
                                <p>
                                    {{ !empty($tenant_details->name) ? $tenant_details->name : '' }}<br>
                                    {{ !empty($tenant->address) ? $tenant->address : '' }}<br>
                                    {{ !empty($tenant->city) ? $tenant->city . ' ,' : '' }}
                                    {{ !empty($tenant->state) ? $tenant->state . ' ,' : '' }}
                                    {{ !empty($tenant->pincode) ? $tenant->pincode : '' }}<br>
                                    {{ !empty($tenant->country) ? $tenant->country : '' }}<br>
                                    {{ !empty($tenant_details->mobile_no) ? $tenant_details->mobile_no : '' }}<br>
                                </p>
                            </td>
                        @endif
                        @if (!empty($property->name) && !empty($property->address) && !empty($unit->name) && !empty($property->pincode))
                            <td class="text-right">
                                <strong style="margin-bottom: 10px; display:block;">{{ __('Property Address') }}:</strong>
                                <p>
                                    {{ !empty($property->address) ? $property->address : '' }}<br>
                                    {{ !empty($property->city) ? $property->city . ' ,' : '' }}
                                    {{ !empty($property->state) ? $property->state . ' ,' : '' }}
                                    {{ !empty($property->pincode) ? $property->pincode : '' }}<br>
                                    {{ !empty($property->country) ? $property->country : '' }}<br>
                                </p>
                            </td>
                        @endif
                    </tr>
                </tbody>
            </table>
            <table class="add-border invoice-summary" style="margin-top: 30px;">
                <thead style="background-color: var(--theme-color);color: {{ $font_color }};">
                    <tr>
                        <th>#</th>
                        <th>{{ __('Property') }}</th>
                        <th>{{ __('Unit') }}</th>
                        <th>{{ __('Rent') }}</th>
                        <th style=" text-align: center; ">{{ __('Total Amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{1}}</td>
                        <td>{{ !empty($property->name) ? $property->name : '' }}</td>
                        <td>{{ !empty($unit->name) ? $unit->name : '' }} </td>
                        <td>{{ currency_format_with_sym($unit->rent, $invoice->created_by, $invoice->workspace) }} </td>
                        <td style=" text-align: center; ">
                            {{ currency_format_with_sym($unit->rent, $invoice->created_by, $invoice->workspace) }}
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        @php
                            $colspan = 3;
                            $payment = Workdo\PropertyManagement\Entities\PropertyInvoicePayment::where('invoice_id',$invoice->id)->count();
                        @endphp
                        <td colspan="{{$colspan}}"></td>
                        <td colspan="2" class="sub-total">
                            <table class="total-table">
                                <tr>
                                    <td>{{ __('Subtotal') }}:</td>
                                    <td>{{ currency_format_with_sym($unit->rent, $invoice->created_by, $invoice->workspace) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ __('Maintenance Charge') }}:</td>
                                    <td>{{ currency_format_with_sym($property->maintenance_charge, $invoice->created_by, $invoice->workspace) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ __('Total') }}:</td>
                                    <td>{{ currency_format_with_sym($unit->rent + $property->maintenance_charge, $invoice->created_by, $invoice->workspace) }}
                                    </td>
                                </tr>
                                @if($payment == 0)
                                    <tr>
                                        <td>{{ __('Paid') }}:</td>
                                        <td>{{ currency_format_with_sym(0, $invoice->created_by, $invoice->workspace) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Due Amount') }}:</td>
                                        <td>{{ currency_format_with_sym($unit->rent + $property->maintenance_charge, $invoice->created_by, $invoice->workspace) }}
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>{{ __('Paid') }}:</td>
                                        <td>{{ currency_format_with_sym($unit->rent + $property->maintenance_charge, $invoice->created_by, $invoice->workspace) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Due Amount') }}:</td>
                                        <td>{{ currency_format_with_sym(0, $invoice->created_by, $invoice->workspace) }}
                                        </td>
                                    </tr>
                                @endif

                            </table>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @if (!isset($preview))
        @include('property-management::propertyinvoice.script');
    @endif
</body>

</html>
