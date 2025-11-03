@php
    $admin_settings = getAdminAllSetting($workspace->created_by, $workspace->id);
    $company_settings = getCompanyAllSetting($workspace->created_by, $workspace->id);
    $check = false;

    if (
        ((isset($company_settings['stripe_is_on']) ? $company_settings['stripe_is_on'] : 'off') == 'on' &&
            !empty($company_settings['stripe_key']) &&
            !empty($company_settings['stripe_secret'])) ||
        ((isset($company_settings['paypal_payment_is_on']) ? $company_settings['paypal_payment_is_on'] : 'off') ==
            'on' &&
            !empty($company_settings['company_paypal_client_id']) &&
            !empty($company_settings['company_paypal_secret_key']))
    ) {
        $check = true;
    }
@endphp

@extends('property-management::frontend.front')

@section('content')
    <section class="payment-sec pt pb">
        <div class="container">
            <div class="payment-wrapper">
                {{ Form::open(['route' => ['property.booking.store', $slug], 'id' => 'payment_form','method' => 'post',  'enctype' => 'multipart/form-data']) }}

                <div class="wrapper-content">
                    <div class="wrapper-header section-border " style="display: flex;justify-content: space-between;">
                        <h3>
                        {{isset($property->property)?$property->property->name:'-'}} - {{isset($property->unit_id)?$property->unit_id->name:'-'}}
                        </h3>
                        @php
                            $property_rent = isset($property->unit_id)?$property->unit_id->rent:0;
                            $property_maintenance_charge = isset($property->property)?$property->property->maintenance_charge:0;
                            $property_total_rent = $property_rent + $property_maintenance_charge;
                        @endphp
                        <h3 style="color: var(--primary-color);">{{__('Total Rent')}} : {{ currency_format_with_sym($property_total_rent,$property->created_by, $property->workspace) }}</h3>
                    </div>

                    <input type="hidden" name="property" value="{{ $property->property_id }}">
                    <input type="hidden" name="unit" value="{{ $property->unit }}">
                    <input type="hidden" name="price" value="{{ $total_amount }}">

                    <div class="form-wrapper section-border">
                        <div class="row">
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="form-group">

                                    <label class="form-label">{{ __('Name') }}</label>
                                    <input type="text" class="form-control" name="name"
                                        placeholder="{{ __('Enter Your Name') }}" required="required">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Email') }}</label>
                                    <input type="email" class="form-control" name="email"
                                        placeholder="{{ __('Enter Your Email') }}" required="required">
                                </div>
                            </div>
                            <x-mobile divClass="col-lg-4 col-sm-6 col-12"  name="mobile_number" label="{{__('Mobile No')}}" required></x-mobile>

                            <div class="col-12">
                                <div class="form-group payment-type">
                                    <label class="form-label">{{ __('Select Payment Option') }}</label>
                                    <ul>
                                        <li class="radio-group flex">
                                            <input class="form-check-input" type="radio" name="payment_option"
                                            id="online_payment" value="Online" required>
                                            <label class="form-check-label" for="online_payment">
                                                {{ __('Online') }}
                                            </label>
                                        </li>
                                        <li class="radio-group flex">
                                            <input class="form-check-input" type="radio" name="payment_option"
                                            id="offline_payment" value="Offline" required>
                                            <label class="form-check-label" for="offline_payment">
                                                {{ __('Offline') }}
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="offlineSection" style="display:none;">
                        @if ($check == true)
                            <div class="payment-options section-border flex">
                                @stack('property_payment')
                            </div>
                        @else
                            <p>{{ __('Please payment setting save.') }}</p>
                        @endif

                    </div>
                    <div class="wrapper-btns">
                        <button type="button" class="btn btn-light">
                            {{ __('Cancel') }}
                        </button>
                        <button id = "submitBtn" type="submit" class="btn">{{ __('Book Now') }}</button>
                    </div>
                </div>

                {{ Form::close() }}

            </div>
        </div>
    </section>
@endsection

@push('script-page')
    <script>
        $(document).ready(function() {
            $('input[name="payment_option"]').change(function() {

                if ($(this).val() == 'Offline') {
                    $('#offlineSection').hide();
                } else {
                    $('#offlineSection').show();
                }
            });
        });
    </script>
    <script>
        $(document).on("click", ".payment_method", function() {
            var payment_action = $(this).attr("data-payment-action");
            if (payment_action != '' && payment_action != undefined) {
                $('#payment').val(payment_action);
                $("#payment_form").attr("action", payment_action);
            } else {
                $("#payment_form").attr("action", '');
            }
        });
    </script>

    <script src="{{ asset('packages/workdo/PropertyManagement/src/Resources/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('packages/workdo/PropertyManagement/src/Resources/assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('packages/workdo/PropertyManagement/src/Resources/assets/js/lightgallery.min.js') }}"></script>
    <script src="{{ asset('packages/workdo/PropertyManagement/src/Resources/assets/js/custom.js') }}"></script>
    <script src="{{ asset('packages/workdo/PropertyManagement/src/Resources/assets/js/bootstrap-notify/bootstrap-notify.min.js') }}">
    </script>
@endpush
