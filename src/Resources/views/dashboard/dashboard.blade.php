@extends('layouts.main')
@section('page-title')
    {{ __('Dashboard') }}
@endsection
@section('page-breadcrumb')
    {{ __('Property Management') }}
@endsection
@section('content')
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    @if(\Auth::user()->type == 'company')
        <div class="row row-gap mb-4 ">
            <div class="col-xl-6 col-12">
                <div class="dashboard-card">
                    <img src="{{ asset('assets/images/layer.png')}}" class="dashboard-card-layer" alt="layer">
                    <div class="card-inner">
                        <div class="card-content">
                            <h2>{{ $workspace->name }}</h2>
                            <p>{{__('Streamline property, lease, tenant, and maintenance management for owners.')}}</p>
                            <div class="btn-wrp d-flex gap-3">
                                <a href="#" class="btn btn-primary d-flex align-items-center gap-1 cp_link" tabindex="0" data-link="{{ route('property.listing',$workspace->slug) }}" data-bs-whatever="{{ __('Property Listing') }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Create Book') }}" title="{{ __('Click to copy property listing link') }}">
                                    <i class="ti ti-link text-white"></i>
                                <span>{{ __('Property Listing') }}</span></a>
                            </div>
                        </div>
                        <div class="card-icon  d-flex align-items-center justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="79" height="105" viewBox="0 0 79 105" fill="none">
                                <path d="M12.7969 98.8692H68.6684V102.442C68.6675 103.12 68.3977 103.77 67.9182 104.25C67.4387 104.729 66.7885 104.999 66.1104 105H2.55732C1.87931 104.999 1.22935 104.729 0.749958 104.25C0.270566 103.77 0.000867991 103.12 0 102.442V11.7214C0.00141242 11.0442 0.271487 10.3951 0.750943 9.91681C1.2304 9.43847 1.88006 9.1699 2.55732 9.17007H7.20644V93.2786C7.20807 94.7607 7.79758 96.1818 8.84563 97.2299C9.89368 98.2779 11.3147 98.8675 12.7969 98.8692ZM78.9141 20.0845V93.2786C78.9126 93.9559 78.6425 94.605 78.163 95.0833C77.6834 95.5617 77.0337 95.8302 76.3563 95.8299H12.7969C12.1203 95.8296 11.4715 95.5607 10.9931 95.0823C10.5147 94.6039 10.2458 93.9551 10.2455 93.2786V2.55773C10.2452 1.8804 10.5137 1.23063 10.9921 0.751088C11.4704 0.271544 12.1195 0.00141253 12.7969 0L58.8299 0V18.5659C58.8304 18.9684 58.9904 19.3542 59.2749 19.6388C59.5594 19.9235 59.9451 20.0838 60.3475 20.0845H78.9141ZM21.0299 36.4311C21.3309 36.9549 21.7328 37.4137 22.2125 37.7808C22.6922 38.148 23.24 38.4163 23.8241 38.5701C24.2169 38.6751 24.6217 38.7284 25.0283 38.7286C25.4958 38.7279 25.9604 38.6554 26.4059 38.5137V56.5981C26.4058 57.4272 26.7347 58.2225 27.3204 58.8094C27.9061 59.3963 28.7007 59.7269 29.5298 59.7286H59.6306C60.4596 59.7267 61.254 59.396 61.8395 58.8092C62.4251 58.2223 62.7539 57.4271 62.7539 56.5981V38.5137C63.5406 38.7641 64.3803 38.7979 65.1846 38.6115C65.989 38.4251 66.7282 38.0253 67.3245 37.4543C67.9208 36.8833 68.3523 36.1621 68.5734 35.3666C68.7945 34.5711 68.7971 33.7308 68.581 32.9339C68.4264 32.3504 68.1575 31.8035 67.7899 31.3247C67.4223 30.8459 66.9633 30.4449 66.4396 30.1448L45.3362 17.9562C45.1053 17.823 44.8435 17.7529 44.5769 17.7529C44.3104 17.7529 44.0485 17.823 43.8176 17.9562L22.7134 30.144C21.6591 30.7569 20.8902 31.7623 20.5748 32.9403C20.2593 34.1183 20.423 35.3733 21.0299 36.4311ZM17.9386 69.697C17.9387 70.0998 18.0987 70.486 18.3834 70.7708C18.6682 71.0556 19.0545 71.2156 19.4572 71.2156H37.1602C37.5629 71.2156 37.9492 71.0556 38.234 70.7709C38.5188 70.4861 38.6788 70.0998 38.6788 69.697C38.6788 69.2943 38.5188 68.908 38.234 68.6232C37.9492 68.3384 37.5629 68.1784 37.1602 68.1784H19.4572C19.2577 68.1783 19.0602 68.2175 18.8759 68.2937C18.6916 68.37 18.5241 68.4819 18.3831 68.6229C18.242 68.764 18.1302 68.9314 18.0539 69.1157C17.9776 69.3 17.9385 69.4976 17.9386 69.697ZM71.2152 86.9092C71.2152 86.5065 71.0554 86.1201 70.7709 85.835C70.4864 85.5499 70.1004 85.3892 69.6976 85.3884H19.4572C19.0572 85.393 18.6752 85.5552 18.3939 85.8397C18.1127 86.1242 17.955 86.5081 17.955 86.9081C17.955 87.3081 18.1127 87.692 18.3939 87.9765C18.6752 88.261 19.0572 88.4232 19.4572 88.4278H69.6966C70.0992 88.4274 70.4852 88.2672 70.7699 87.9825C71.0546 87.6978 71.2147 87.3119 71.2152 86.9092ZM71.2152 78.2988C71.2148 77.8964 71.0548 77.5105 70.7703 77.2259C70.4858 76.9412 70.1001 76.7809 69.6976 76.7802H19.4572C19.0544 76.7802 18.6682 76.9402 18.3834 77.225C18.0986 77.5098 17.9386 77.8961 17.9386 78.2988C17.9386 78.7016 18.0986 79.0879 18.3834 79.3726C18.6682 79.6574 19.0544 79.8174 19.4572 79.8174H69.6966C70.0994 79.8174 70.4856 79.6574 70.7704 79.3726C71.0552 79.0878 71.2152 78.7016 71.2152 78.2988ZM24.6094 35.635C24.8066 35.6896 25.0128 35.7043 25.2158 35.6782C25.4188 35.6521 25.6146 35.5858 25.7917 35.483L43.818 25.0786C44.0487 24.9449 44.3107 24.8745 44.5773 24.8745C44.844 24.8745 45.1059 24.9449 45.3366 25.0786L63.3601 35.483C63.7201 35.6892 64.147 35.7447 64.5477 35.6374C64.9484 35.5301 65.2905 35.2687 65.4993 34.9102C65.7029 34.5507 65.757 34.1255 65.6498 33.7265C65.5426 33.3275 65.2828 32.9866 64.9265 32.7774L44.5772 21.0265L24.2335 32.776C24.0565 32.878 23.9015 33.0142 23.7775 33.1765C23.6535 33.3389 23.563 33.5242 23.5112 33.7218C23.4018 34.1233 23.4564 34.5517 23.6629 34.9129C23.765 35.09 23.9012 35.245 24.0637 35.3689C24.2262 35.4929 24.4117 35.5833 24.6094 35.635ZM29.4427 36.8823V56.5981C29.4429 56.6217 29.4519 56.6443 29.468 56.6616C29.4841 56.6789 29.5061 56.6895 29.5296 56.6914H37.4805V42.9415C37.4804 42.7421 37.5195 42.5445 37.5958 42.3602C37.6721 42.1759 37.7839 42.0084 37.925 41.8674C38.066 41.7264 38.2335 41.6145 38.4178 41.5382C38.6021 41.462 38.7996 41.4228 38.9991 41.4229H50.1586C50.3582 41.4227 50.5559 41.4619 50.7403 41.5381C50.9247 41.6143 51.0924 41.7261 51.2336 41.8672C51.3748 42.0082 51.4868 42.1757 51.5633 42.36C51.6398 42.5443 51.6792 42.7419 51.6793 42.9415V56.6914H59.6306C59.6537 56.689 59.6751 56.6781 59.6906 56.6609C59.7062 56.6437 59.7149 56.6213 59.7151 56.5981V36.8866L44.5768 28.1462L29.4427 36.8823ZM40.5169 56.6914H48.6392V44.4609H40.5179L40.5169 56.6914ZM61.8694 2.14983V17.0473H76.7644L61.8694 2.14983Z" fill="#18BF6B"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-12">
                <div class="row dashboard-wrp">
                    <div class="col-sm-6 col-12">
                        <div class="dashboard-project-card">
                            <div class="card-inner  d-flex justify-content-between">
                                <div class="card-content">
                                    <div class="theme-avtar bg-white">
                                        <i class="ti ti-users text-danger"></i>
                                    </div>
                                    <a href="{{ route('tenant.index') }}"><h3 class="mt-3 mb-0 text-danger">{{ __('Total Tenants') }}</h3></a>
                                </div>
                                <h3 class="mb-0">{{ \Workdo\PropertyManagement\Entities\PropertyUtility::countTenants() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="dashboard-project-card">
                            <div class="card-inner  d-flex justify-content-between">
                                <div class="card-content">
                                    <div class="theme-avtar bg-white">
                                        <i class="ti ti-building-community"></i>
                                    </div>
                                    <a href="{{ route('property.index') }}"><h3 class="mt-3 mb-0">{{ __('Total Properties') }}</h3></a>
                                </div>
                                <h3 class="mb-0">{{ \Workdo\PropertyManagement\Entities\PropertyUtility::countProperties() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="dashboard-project-card">
                            <div class="card-inner  d-flex justify-content-between">
                                <div class="card-content">
                                    <div class="theme-avtar bg-white">
                                        <i class="ti ti-clipboard-list"></i>
                                    </div>
                                    <a href="{{ route('property-unit.index') }}"><h3 class="mt-3 mb-0">{{ __('Total Units') }}</h3></a>
                                </div>
                                <h3 class="mb-0">{{ \Workdo\PropertyManagement\Entities\PropertyUtility::countUnits() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="dashboard-project-card">
                            <div class="card-inner d-flex justify-content-between">
                                <div class="card-content">
                                    <div class="theme-avtar bg-white">
                                        <i class="ti ti-file-invoice"></i>
                                    </div>
                                    <h3 class="mt-3 mb-0">{{ __('Total Invoices') }}</h3>
                                </div>
                                <h3 class="mb-0">{{ \Workdo\PropertyManagement\Entities\PropertyInvoice::countPropertyInvoices() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mt-1 mb-0">{{ __('Invoice') }}</h5>
                        </div>
                        <div class="card-body">
                            <div id="invoice-count-chart"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mt-1 mb-0">{{ __('Recent Invoices') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('Tenant') }}</th>
                                            <th>{{ __('Issue Date') }}</th>
                                            <th>{{ __('Due Date') }}</th>
                                            <th>{{ __('Amount') }}</th>
                                            <th>{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentInvoice as $invoice)
                                            <tr>
                                                <td>
                                                    @permission('property invoice show')
                                                        <a href="{{ route('property-invoice.show', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id)) }}"
                                                            class="btn btn-outline-primary" data-title="{{ __('Invoice Details') }}">
                                                            {{ \Workdo\PropertyManagement\Entities\PropertyInvoice::tenantNumberFormat($invoice->id) }}
                                                        </a>
                                                    @else
                                                        <a href="#" class="btn btn-outline-primary"
                                                            data-title="{{ __('Invoice Details') }}">
                                                            {{ \Workdo\PropertyManagement\Entities\PropertyInvoice::tenantNumberFormat($invoice->id) }}
                                                        </a>
                                                    @endpermission
                                                </td>
                                                <td>{{ !empty($invoice->tenant->user) ? $invoice->tenant->user->name : '' }} </td>
                                                <td>{{ company_date_formate($invoice->issue_date) }}</td>
                                                <td>{{ company_date_formate($invoice->due_date) }}</td>
                                                <td>{{ currency_format_with_sym($invoice->total_amount) }}</td>
                                                <td>
                                                    @if($invoice->status == 'Not Paid')
                                                        <span class="p-2 px-3 fix_badges badge bg-danger">{{ $invoice->status }}</span>
                                                    @elseif($invoice->status == 'Pending')
                                                        <span class="p-2 px-3 fix_badges badge bg-warning">{{ $invoice->status }}</span>
                                                    @else
                                                        <span class="p-2 px-3 fix_badges badge bg-success">{{ $invoice->status }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            @include('layouts.nodatafound')
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('packages/workdo/PropertyManagement/src/Resources/assets/js/main.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script>
        (function() {
            var chartBarOptions = {
                series: [{
                        name: "{{ __('Invoice') }}",
                        data: {!! json_encode($invExpLineChartData['totalInvoice']) !!}
                        // data: ['1','3','3','2','1','1','3','4','2','1','1','4','3','2','1']
                    }
                ],

                chart: {
                    height: 250,
                    type: 'area',
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.2
                    },
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                title: {
                    text: '',
                    align: 'left'
                },
                xaxis: {
                    categories: {!! json_encode($invExpLineChartData['day']) !!},
                    title: {
                        text: '{{ __('Date') }}'
                    }
                },
                colors: ['#FF3A6E'],

                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },
                yaxis: {
                    title: {
                        text: '{{ __('') }}'
                    },

                }

            };
            var arChart = new ApexCharts(document.querySelector("#invoice-count-chart"), chartBarOptions);
            arChart.render();
        })();
    </script>
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
@endpush
