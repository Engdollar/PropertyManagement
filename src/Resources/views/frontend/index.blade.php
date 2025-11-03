@extends('property-management::frontend.front')

@section('content')
    <section class="our-product-sec pt pb">
        <div class="container">
            <div class="row product-row">
                @foreach ($propertylists as $propertylist)
                <div class="col-lg-4 col-sm-6  col-12">
                    <div class="product-card">
                        <div class="product-card-image">
                            <a href="{{ url('/property-details/'.$workspace->slug.'/'.$propertylist->property_id.'/'.$propertylist->unit) }}" target="_blank"  class="product-img img-wrapper" >
                                @php
                                    $image = asset('packages/workdo/PropertyManagement/src/Resources/assets/upload/thumbnail-not-found.png');

                                    if (!empty($propertylist->propertyListImage) && isset($propertylist->propertyListImage[0]->name) && check_file($propertylist->propertyListImage[0]->name)) {
                                    $image = get_file($propertylist->propertyListImage[0]->name);
                                    } elseif (!empty($propertylist->propertyImage) && isset($propertylist->propertyImage[0]->name) && check_file($propertylist->propertyImage[0]->name)) {
                                    $image = get_file($propertylist->propertyImage[0]->name);
                                    }
                                @endphp
                            <img src="{{ $image }}" alt="PropertyImage" id="thumbnail" class="card-img">
                            </a>
                            <label>{{$propertylist->list_type}}</label>
                        </div>

                        <div class="product-card-content">
                            <div class="product-content-top">
                                <ul class="product-data flex justify-center">
                                    @php
                                    $amenities = explode(',', $propertylist->unit_id->amenities);
                                    @endphp

                                    @foreach($amenities as $amenity)
                                        <li>{{ $amenity }}</li>
                                    @endforeach

                                </ul>

                                <div class="product-price flex justify-between no-wrap">
                                    <h2>{{isset($propertylist->property)?$propertylist->property->name:'-'}} - {{isset($propertylist->unit_id)?$propertylist->unit_id->name:'-'}}</h2>
                                    @php
                                        $property_rent = isset($propertylist->unit_id)?$propertylist->unit_id->rent:0;
                                        $property_maintenance_charge = isset($propertylist->property)?$propertylist->property->maintenance_charge:0;
                                        $property_total_rent = $property_rent + $property_maintenance_charge;
                                    @endphp
                                    <span> {{ currency_format_with_sym($property_total_rent,$propertylist->created_by, $propertylist->workspace) }}</span>
                                </div>
                            </div>
                            <ul class="product-data-inner flex ">
                                <li class="flex direction-column">
                                    <div class="product-bg">
                                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_237_408)">
                                                <path
                                                    d="M20.5965 10.8063V3.89149C20.5965 2.20953 19.2281 0.841187 17.5462 0.841187H4.36614C2.68417 0.841187 1.31583 2.20957 1.31583 3.89149V10.8955C0.501145 11.7472 0 12.9012 0 14.1701V19.4133C0 20.3757 0.78302 21.1588 1.74552 21.1588C2.70798 21.1588 3.491 20.3757 3.491 19.4133V18.8718H18.509V19.4133C18.509 20.3757 19.2921 21.1588 20.2545 21.1588C21.217 21.1588 22 20.3758 22 19.4133V14.1701C22 12.8563 21.4627 11.6657 20.5965 10.8063ZM4.36614 1.70056H17.5462C18.7543 1.70056 19.7371 2.68343 19.7371 3.89149V10.13C19.4739 9.96796 19.1938 9.83089 18.9001 9.72218V8.2933C18.9001 7.02658 17.8695 5.99602 16.6028 5.99602H12.8676C12.0987 5.99602 11.4172 6.37616 11 6.958C10.5829 6.37616 9.90142 5.99602 9.13245 5.99602H5.39726C4.13054 5.99602 3.09998 7.02654 3.09998 8.2933V9.72218C2.77337 9.84305 2.46361 9.99894 2.17525 10.1853V3.89149C2.17521 2.68343 3.15803 1.70056 4.36614 1.70056ZM4.74087 9.42922C4.47468 9.42922 4.2136 9.45165 3.95927 9.49402V8.2933C3.95927 7.50044 4.60432 6.85539 5.39718 6.85539H9.13236C9.92522 6.85539 10.5703 7.50044 10.5703 8.2933V9.42927H4.74087V9.42922ZM11.4297 8.29326C11.4297 7.5004 12.0747 6.85535 12.8676 6.85535H16.6028C17.3956 6.85535 18.0407 7.5004 18.0407 8.29326V9.49398C17.7863 9.45161 17.5253 9.42918 17.2591 9.42918H11.4297V8.29326ZM4.74087 10.2886H17.2591C19.3994 10.2886 21.1406 12.0298 21.1406 14.1701V15.4448H16.831C16.5937 15.4448 16.4013 15.6373 16.4013 15.8745C16.4013 16.1118 16.5937 16.3042 16.831 16.3042H21.1406V18.0124H0.859375V16.3042H12.5341C12.7714 16.3042 12.9638 16.1118 12.9638 15.8745C12.9638 15.6373 12.7714 15.4448 12.5341 15.4448H0.859375V14.1701C0.859375 12.0298 2.6006 10.2886 4.74087 10.2886ZM2.63162 19.4133C2.63162 19.9019 2.23412 20.2994 1.74552 20.2994C1.25692 20.2994 0.859375 19.9019 0.859375 19.4133V18.8718H2.63162V19.4133ZM20.2545 20.2994C19.7659 20.2994 19.3684 19.9019 19.3684 19.4133V18.8718H21.1406V19.4133C21.1406 19.9019 20.7431 20.2994 20.2545 20.2994Z"
                                                    fill="#131313" />
                                                <path
                                                    d="M14.6823 16.3044C14.9198 16.3044 15.1122 16.1119 15.1122 15.8745C15.1122 15.6371 14.9198 15.4446 14.6823 15.4446C14.4449 15.4446 14.2524 15.6371 14.2524 15.8745C14.2524 16.1119 14.4449 16.3044 14.6823 16.3044Z"
                                                    fill="#131313" />
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_237_408">
                                                    <rect width="22" height="22" fill="white" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </div>
                                    <div class="product-bg-content">
                                        <h3>{{ __('Beds')}}</h3>
                                        <span>{{isset($propertylist->unit_id)?$propertylist->unit_id->bedroom:'-'}}</span>
                                    </div>
                                </li>
                                <li class="flex direction-column">
                                    <div class="product-bg">
                                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M20.625 10.0839H2.29165V2.35636C2.29165 1.83386 2.7775 1.37551 3.33205 1.37551H5.2479C5.78875 1.37551 6.22415 1.81551 6.22875 2.35176C5.335 2.57636 4.58335 3.40136 4.58335 4.27217V5.50051C4.58335 5.75261 4.7896 5.95886 5.0417 5.95886H8.25C8.5021 5.95886 8.70835 5.75261 8.70835 5.50051V4.27221C8.70835 3.38761 8.0071 2.57636 7.14545 2.35636C7.14545 1.31136 6.29754 0.458862 5.24795 0.458862H3.3321C2.27335 0.458862 1.375 1.32971 1.375 2.35636V10.0839C0.61875 10.0793 0.00459766 10.6935 0 11.4497C0 11.8439 0.165 12.2151 0.458348 12.4764V14.878C0.458348 16.7434 1.28795 18.343 2.63085 19.3834L1.8792 20.8776C1.7646 21.1022 1.85629 21.3772 2.08545 21.4917C2.31004 21.6063 2.58504 21.5146 2.6996 21.2855L3.40545 19.8784C4.33129 20.3734 5.3671 20.63 6.4167 20.6209H15.5833C16.6329 20.63 17.6688 20.3734 18.5946 19.8784L19.3004 21.2855C19.415 21.5101 19.69 21.6017 19.9146 21.4917C20.1392 21.3817 20.2308 21.1021 20.1208 20.8776L19.3692 19.3788C20.7121 18.343 21.5417 16.7388 21.5417 14.8734V12.4718C21.8304 12.2197 22 11.8484 22 11.4589C22 10.6981 21.3858 10.0839 20.625 10.0839ZM6.6871 3.20886C7.2325 3.20886 7.7917 3.74511 7.7917 4.27221V5.04221H5.5V4.27221C5.5 3.76346 6.11875 3.20886 6.6871 3.20886ZM20.625 14.8781C20.625 17.6327 18.4571 19.7089 15.5833 19.7089H6.41665C3.5429 19.7089 1.375 17.6326 1.375 14.8781V12.8339H20.625V14.8781ZM20.625 11.9172H1.38875C1.1596 11.9172 0.953348 11.7522 0.92125 11.5276C0.88 11.2435 1.1 11.0005 1.375 11.0005H20.6112C20.8404 11.0005 21.0467 11.1655 21.0788 11.3901C21.12 11.6743 20.9 11.9172 20.625 11.9172Z" fill="#131313"/>
                                            </svg>
                                    </div>
                                    <div class="product-bg-content">
                                        <h3>{{ __('Baths')}}</h3>
                                        <span>{{isset($propertylist->unit_id)?$propertylist->unit_id->baths:'-'}}</span>
                                    </div>
                                </li>
                                <li class="flex direction-column">
                                    <div class="product-bg">
                                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2.21016 0.683449C2.35169 0.494676 2.31337 0.226917 2.1246 0.0854118C1.93583 -0.0561145 1.66809 -0.0177961 1.52656 0.170977L0.0854589 2.09331C-0.0283642 2.24515 -0.0283642 2.45391 0.0854589 2.60578L1.52656 4.52811C1.66809 4.71688 1.93583 4.75518 2.1246 4.61367C2.31337 4.47215 2.35169 4.20441 2.21016 4.01564L1.2814 2.77672H14.4923L13.5134 4.00573C13.3664 4.19025 13.3968 4.45901 13.5814 4.60601C13.7659 4.753 14.0347 4.72256 14.1817 4.53802L15.7128 2.61569C15.8369 2.45994 15.8369 2.23913 15.7128 2.0834L14.1817 0.161066C14.0347 -0.0234777 13.7659 -0.0539358 13.5814 0.0930584C13.3968 0.240053 13.3664 0.508816 13.5134 0.693359L14.4923 1.92235H1.2814L2.21016 0.683449Z" fill="#131313"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M0 6.72815C0 6.2563 0.382522 5.87378 0.854368 5.87378H15.0582C15.5301 5.87378 15.9126 6.2563 15.9126 6.72815V21.1456C15.9126 21.6174 15.5301 22 15.0582 22H0.854368C0.382522 22 0 21.6174 0 21.1456V6.72815ZM15.0582 6.72815H0.854368V21.1456H15.0582V6.72815Z" fill="#131313"/>
                                            <path d="M17.3863 19.8754C17.5278 19.6868 17.7956 19.6484 17.9844 19.79L19.2233 20.7187V7.50781L17.9943 8.48672C17.8097 8.63372 17.541 8.60328 17.394 8.41874C17.247 8.23419 17.2774 7.96543 17.462 7.81843L19.3843 6.28725C19.54 6.1632 19.7609 6.1632 19.9166 6.28725L21.8389 7.81843C22.0235 7.96543 22.054 8.23419 21.9068 8.41874C21.7599 8.60328 21.4912 8.63372 21.3066 8.48672L20.0776 7.50781V20.7187L21.3165 19.79C21.5053 19.6484 21.7731 19.6868 21.9145 19.8754C22.0561 20.0642 22.0177 20.3321 21.8291 20.4735L19.9068 21.9146C19.7549 22.0284 19.546 22.0284 19.3941 21.9146L17.4719 20.4735C17.2831 20.3321 17.2448 20.0642 17.3863 19.8754Z" fill="#131313"/>
                                            </svg>
                                    </div>
                                    <div class="product-bg-content">
                                        <h3>{{__('Sq Ft')}}</h3>
                                        <span>{{$propertylist->total_sq}}</span>
                                    </div>
                                </li>
                            </ul>
                            <div class="product-content-bottom">
                                <a href="{{ url('/property-details/'.$workspace->slug.'/'.$propertylist->property_id.'/'.$propertylist->unit) }}"  class="btn text-center">
                                    <span>{{ __('View Details')}}</span>
                                    <svg width="17" height="12" viewBox="0 0 17 12" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16.5303 6.53033C16.8232 6.23744 16.8232 5.76256 16.5303 5.46967L11.7574 0.696699C11.4645 0.403806 10.9896 0.403806 10.6967 0.696699C10.4038 0.989593 10.4038 1.46447 10.6967 1.75736L14.9393 6L10.6967 10.2426C10.4038 10.5355 10.4038 11.0104 10.6967 11.3033C10.9896 11.5962 11.4645 11.5962 11.7574 11.3033L16.5303 6.53033ZM0 6.75L16 6.75V5.25L0 5.25L0 6.75Z"
                                            fill="white"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('script-page')
<script src="{{ asset('packages/workdo/PropertyManagement/src/Resources/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('packages/workdo/PropertyManagement/src/Resources/assets/js/custom.js') }}"></script>
<script src="{{ asset('packages/workdo/PropertyManagement/src/Resources/assets/js/bootstrap-notify/bootstrap-notify.min.js') }}">
</script>
@endpush
