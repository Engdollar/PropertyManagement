@extends('layouts.main')
@section('page-title')
    {{ __('Invoice Detail') }}
@endsection
@section('page-breadcrumb')
    {{ __('Invoice') }}
@endsection
@php
    $currancy_symbol = company_setting('defult_currancy_symbol');
@endphp
@section('page-action')
    <div>
        <a href="{{ route('property.invoice.pdf', \Crypt::encrypt($invoice->id)) }}" target="_blank"
            class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" title="{{ __('Print') }}">
            <span class="btn-inner--icon text-white"><i class="ti ti-printer"></i></span>
        </a>

        @if(Auth::user()->type != 'tenant' && $invoice->status != 'Paid')
            <div class="btn-group ms-1" id="status_btn">
                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">{{ __('Status') }} : {{ __(ucfirst($invoice->status)) }}</button>
                <div class="dropdown-menu">
                    <h6 class="dropdown-header">{{ __('Set Invoice status') }}</h6>
                    <a class="dropdown-item" href="#" id="status" data-value="Paid">
                        {{ __('Paid') }}
                        @if ($invoice->status == 'Pending' || $invoice->status == 'Not Paid')
                            <i class="text-primary"></i>
                        @else
                            <i class="fa fa-check-double text-primary"></i>
                        @endif
                    </a>
                    <a class="dropdown-item text-danger" href="#" id="status" data-value="Not Paid">
                        {{ __('Not Paid') }}
                        @if ($invoice->status != 'Not Paid')
                            <i class="text-primary"></i>
                        @else
                            <i class="fa fa-check-double text-danger"></i>
                        @endif
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
@section('content')

    <div class="row">
        @if(Auth::user()->type == 'tenant' && $invoice->status != 'Paid')
            <div class="col-lg-8">
        @else
            <div class="col-lg-12">
        @endif
            <!-- [ Invoice ] start -->
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade active show" id="invoice" role="tabpanel" aria-labelledby="pills-user-tab-1">
                    <div class="card">
                        <div class="card-body">
                            <div class="invoice">
                                <div class="invoice-print">
                                    <div class="row row-gap invoice-title border-1 border-bottom  pb-3 mb-3">
                                        <div class="col-sm-4  col-12">
                                            <h2 class="h3 mb-0">{{ __('Invoice') }}</h2>
                                        </div>
                                        <div class="col-sm-8  col-12">
                                            <div
                                                class="d-flex invoice-wrp flex-wrap align-items-center gap-md-2 gap-1 justify-content-end">
                                                <div
                                                    class="d-flex invoice-date flex-wrap align-items-center justify-content-end gap-md-3 gap-1">
                                                    <p class="mb-0"><strong>{{ __('Issue Date') }} :</strong>
                                                        {{ company_date_formate($invoice->issue_date) }}</p>
                                                    <p class="mb-0"><strong>{{ __('Due Date') }} :</strong>
                                                        {{ company_date_formate($invoice->due_date) }}</p>
                                                </div>
                                                <h3 class="invoice-number mb-0">
                                                    {{ Workdo\PropertyManagement\Entities\PropertyInvoice::tenantNumberFormat($invoice->id) }}
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-sm-4 p-3 invoice-billed">
                                        <div class="row row-gap">
                                            <div class="col-lg-4 col-sm-6">
                                                @if (!empty($tenant_details->name) && !empty($tenant->address) && !empty($tenant->pincode))
                                                    <p class="mb-2"><strong
                                                            class="h5 mb-1 d-block">{{ __('Billed To') }}:</strong>
                                                        <span class="text-muted d-block" style="max-width:80%">
                                                            {{ !empty($tenant_details->name) ? $tenant_details->name : '' }}
                                                            {{ !empty($tenant->address) ? $tenant->address : '' }}
                                                            {{ !empty($tenant->city) ? $tenant->city . ' ,' : '' }}
                                                            {{ !empty($tenant->state) ? $tenant->state . ' ,' : '' }}
                                                            {{ !empty($tenant->pincode) ? $tenant->pincode : '' }}
                                                            {{ !empty($tenant->country) ? $tenant->country : '' }}
                                                        </span>
                                                    </p>
                                                    <p class="mb-1 text-dark">
                                                        {{ !empty($tenant_details->mobile_no) ? $tenant_details->mobile_no : '' }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                @if (!empty($property->name) && !empty($property->address) && !empty($unit->name) && !empty($property->pincode))
                                                    <p class="mb-2">
                                                        <strong class="h5 mb-1 d-block">{{ __('Shipped To') }}
                                                            :</strong>
                                                        <span class="text-muted d-block" style="max-width:80%">
                                                            {{ !empty($property->address) ? $property->address : '' }}
                                                            {{ !empty($property->city) ? $property->city . ' ,' : '' }}
                                                            {{ !empty($property->state) ? $property->state . ' ,' : '' }}
                                                            {{ !empty($property->pincode) ? $property->pincode : '' }}
                                                            {{ !empty($property->country) ? $property->country : '' }}
                                                        </span>
                                                    </p>
                                                @endif
                                            </div>

                                            <div class="col-lg-2 col-sm-6">
                                                <strong class="h5 d-block mb-2">{{ __('Status') }} :</strong>
                                                @if($invoice->status == 'Not Paid')
                                                    <span
                                                        class="badge fix_badge f-12 p-2 d-inline-block bg-danger">{{ $invoice->status }}</span>
                                                @elseif($invoice->status == 'Pending')
                                                    <span
                                                        class="badge fix_badge f-12 p-2 d-inline-block bg-warning">{{ $invoice->status }}</span>
                                                @else
                                                    <span
                                                        class="badge fix_badge f-12 p-2 d-inline-block bg-primary">{{ $invoice->status }}</span>
                                                @endif
                                            </div>

                                            <div class="col-lg-2 col-sm-6">
                                                <div class="float-sm-end qr-code">
                                                    <div class="col">
                                                        <div class="float-sm-end">
                                                            {!! DNS2D::getBarcodeHTML(
                                                                route('property-invoice.show', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id)),
                                                                'QRCODE',
                                                                2,
                                                                2,
                                                            ) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invoice-summary mt-3">
                                        <div class="invoice-title border-1 border-bottom mb-3 pb-2">
                                            <h3 class="h4 mb-0">{{ __('Properties') }}</h3>
                                        </div>
                                        <div class="table-responsive mt-2">
                                            <table class="table mb-0 table-striped">
                                               <thead>
                                                    <tr>
                                                        <th data-width="40" class="text-white bg-primary text-uppercase">#</th>
                                                        <th class="text-white bg-primary text-uppercase">{{ __('Property') }}</th>
                                                        <th class="text-white bg-primary text-uppercase">{{ __('Unit') }}</th>
                                                        <th class="text-white bg-primary text-uppercase">{{ __('Rent') }}</th>
                                                        <th class="text-right text-white bg-primary text-uppercase" width="12%">{{ __('Total Amount') }}</th>
                                                    </tr>
                                               </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{1}}</td>
                                                        <td>{{ !empty($property->name) ? $property->name : '' }}</td>
                                                        <td>{{ !empty($unit->name) ? $unit->name : '' }} </td>
                                                        <td>{{ currency_format_with_sym($unit->rent) }} </td>
                                                        <td class="text-right">
                                                            {{ currency_format_with_sym($unit->rent) }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    @php $colspan = 3; @endphp
                                                    <tr>
                                                        <td colspan="{{ $colspan }}"></td>
                                                        <td class="text-right">{{ __('Sub Total') }}</td>
                                                        <td class="text-right"><b>{{ currency_format_with_sym($unit->rent) }}</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="{{ $colspan }}"></td>
                                                        <td class="text-right">{{ __('Maintenance Charge') }}</td>
                                                        <td class="text-right"><b>{{ currency_format_with_sym($property->maintenance_charge) }}</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="{{ $colspan }}"></td>
                                                        <td class="blue-text text-right">{{ __('Total') }}</td>
                                                        <td class="blue-text text-right">
                                                            <b>{{ currency_format_with_sym($unit->rent + $property->maintenance_charge) }}</b>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="invoice-title border-1 border-bottom mb-3 pb-2">
                                    <h3 class="h4 mb-0">{{ __('Payment History') }}</h3>
                                </div>
                                <div class="table-responsive mt-2">
                                    <table class="table table-striped overflow-hidden rounded">
                                        <thead>
                                            <tr class="thead-default">
                                                <th class="text-white bg-primary">{{ __('Transaction ID') }}</th>
                                                <th class="text-white bg-primary">{{ __('Payment Date') }}</th>
                                                <th class="text-white bg-primary">{{ __('Payment Type') }}</th>
                                                <th class="text-white bg-primary">{{ __('Receipt') }}</th>
                                                <th class="text-right text-white bg-primary">{{ __('Amount') }}</th>
                                                @if (!empty($bank_transfer_payments))
                                                    <th class="text-white bg-primary">{{ __('Action') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i=0; @endphp
                                            @if (!empty($bank_transfer_payments))
                                                @foreach ($invoice->payments as $payment)
                                                    <tr>
                                                        <td>{{ sprintf('%05d', $payment->id) }}</td>
                                                        <td>{{ company_date_formate($payment->date) }}</td>
                                                        <td>{{ $payment->payment_type }}</td>
                                                        <td>
                                                            @if ($payment->payment_type == 'STRIPE')
                                                                <a href="{{ $payment->receipt }}" target="_blank">
                                                                    <i class="ti ti-file-invoice"></i>
                                                                </a>
                                                            @elseif($payment->payment_type == 'Bank Transfer')
                                                                <a href="{{ !empty($payment->receipt) ? (check_file($payment->receipt) ? get_file($payment->receipt) : '#!') : '#!' }}"
                                                                    target="_blank">
                                                                    <i class="ti ti-file"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td class="text-right">
                                                            {{ currency_format_with_sym($payment->amount) }}</td>
                                                        <td></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                @include('layouts.nodatafound')
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

        @if(Auth::user()->type == 'tenant' && $invoice->status != 'Paid')
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-footer py-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <span class="cart-sum-left"><h6 class="">{{ __('Payment Method') }}:</h6></span>
                                    <div class="row">
                                        @stack('company_property_payment')
                                    </div>

                                    <div class="cart-footer-total-row bg-primary text-white p-3 d-flex align-items-center justify-content-between">
                                        <div class="mini-total-price">
                                            <div class="price">
                                                <h3 class="text-white mb-0 total">{{ currency_format_with_sym($unit->rent + $property->maintenance_charge) }}</h3>
                                            </div>
                                        </div>
                                        {{Form::open(array('','method'=>'post','id'=>'payment_form','enctype' => 'multipart/form-data'))}}
                                            <input type="hidden" name="property_id" value="{{$property->id}}" class="property_id">
                                            <input type="hidden" name="unit_id" value="{{$unit->id}}" class="unit_id">
                                            <input type="hidden" name="invoice_id" value="{{$invoice->id}}" class="invoice_id">
                                            <input type="hidden" name="tenant_id" value="{{$tenant->id}}" class="tenant_id">
                                            <input type="hidden" name="tenant_details" value="{{$tenant_details->id}}" class="tenant_details">
                                            <div class="text-end form-btn">
                                            </div>
                                        {{Form::close()}}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- [ Invoice ] end -->
    </div>
@endsection
@push('scripts')
    <script>

        $('.cp_link').on('click', function() {
            var value = $(this).attr('data-link');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            toastrs('Success', '{{ __('Link Copy on Clipboard') }}', 'success')
        });
    </script>

<script>
    $(document).ready(function () {
        var numItems = $('.payment_method').length

        if(numItems > 0)
        {
            $('.form-btn').append('<button type="submit" class="btn btn-dark payment-btn" >{{ __("Buy Now") }}</button>');
            setTimeout(() => {
                $(".payment_method").first().attr('checked', true);
                $(".payment_method").first().trigger('click');
            }, 200);
        }
        else
        {
            $('.form-btn').append("<span class='text-danger'>{{ __('Company payment settings not set')}}</span>");
        }

    });
    $( "#payment_form" ).on( "submit", function( event ) {
        "{{session()->put('Subscription','custom_subscription')}}";
    });
     $(document).on("click",".payment_method",function() {
        var payment_action = $(this).attr("data-payment-action");
        if(payment_action != '' && payment_action != undefined)
        {
            $("#payment_form").attr("action",payment_action);
        }
        else
        {
            $("#payment_form").attr("action",'');
        }
        if ($('#bank-payment').prop('checked'))
        {
            $(".temp_receipt").attr("required", "required");
        }
        else
        {
            $(".temp_receipt").removeAttr("required");
        }
    });
    function Coupon()
    {
        var fp = 0;
        var currancy_symbol = '{{ $currancy_symbol }}';
        $( ".final_price" ).each(function( index ) {
            console.log($(this).text());
            var text = $(this).text();
            var matches = text.match(/\d+(\.\d+)?/);
            if (matches) {
                fp += parseFloat(matches[0]);
            }
        });
        $(".total").text(fp + currancy_symbol);
    }
</script>
@if (company_setting('bank_transfer_payment_is_on') == 'on')
<script>

    $('#payment_form').submit(function(e)
    {
        if ($('#bank-payment').prop('checked'))
        {
            e.preventDefault(); // Prevent form submission


            var file = document.getElementById('temp_receipt').files[0];

            if(file != undefined)
            {
                $('.error_msg').addClass('d-none');

                // Create a new FormData object
                const formData = new FormData();

                // Add file data from the file input element
                const file = $('#temp_receipt')[0].files[0];
                formData.append('payment_receipt', file, file.name);

                // Add data from the form's input elements
                $('#payment_form input').each(function() {
                formData.append(this.name, this.value);
                });

                var url = $('#payment_form').attr('action');


                $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if(response.status == 'success')
                    {
                        toastrs('Success', response.msg, 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                    else
                    {
                        toastrs('Error', response.msg, 'error');
                    }
                    // Handle success response
                },
                error: function(xhr, status, error) {
                    toastrs('Error',error, 'error');
                    // Handle error response
                }
                });

            }
            else
            {
                $('.error_msg').removeClass('d-none');
            }
        }
    });

</script>
@endif


<script>
    $("#status_btn").on('click', '#status', function() {
        var status = $(this).attr('data-value');
        var data = {
            status: status,
        }
        $.ajax({
            url: '{{ route('property.invoice.status.update', $invoice->id) }}',
            method: 'PUT',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                toastrs('Success', data.success, 'success');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }
        });
    });
</script>
@endpush
