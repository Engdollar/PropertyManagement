@php
    $company_settings = getCompanyAllSetting($workspace->created_by, $workspace->id);

    if (!empty(session()->get('lang'))) {
        $currantLang = session()->get('lang');
    } else {
        $currantLang = $company_settings['defult_language'];
    }
    $languages = languages();
    \App::setLocale($currantLang);
    $lang = $currantLang;

    $admin_settings = getAdminAllSetting();
    if ($company_settings['cust_darklayout'] == 'on') {
        $logo = isset($company_settings['logo_light']) ? $company_settings['logo_light'] : (isset($admin_settings['logo_light']) ? $admin_settings['logo_light'] : 'uploads/logo/logo_light.png');
    } else {
        $logo = isset($company_settings['logo_dark']) ? $company_settings['logo_dark'] : (isset($admin_settings['logo_dark']) ? $admin_settings['logo_dark'] : 'uploads/logo/logo_dark.png');
    }

    $footer_logo = isset($company_settings['logo_light']) ? $company_settings['logo_light'] : (isset($admin_settings['logo_light']) ? $admin_settings['logo_light'] : 'uploads/logo/logo_light.png');
    $rtl = isset($company_settings['site_rtl']) ? $company_settings['site_rtl'] : (isset($admin_settings['site_rtl']) ? $admin_settings['site_rtl'] : 'off');

@endphp

<!DOCTYPE html>
<html lang="{{ $lang }}">

@include('property-management::frontend.head')

<body>

    @include('property-management::frontend.header')

    <main>
        @yield('content')
    </main>


     <!-- footer start here -->
     <footer class="site-footer">
        <div class="container">
            <div class="footer-wrapper flex justify-between align-center">
                <div class="footer-left">
                    <a class="footer-logo" href="#">
                        <img src="{{ check_file($footer_logo) ? get_file($footer_logo) : get_file('uploads/logo/logo.png') }}{{ '?' . time() }}"
                        alt="logo" class="logo logo-lg" style="width: 125px;" loading="lazy" />
                    </a>
                </div>
                <div class="footer-right">
                    <p class="copyright">
                        @if (!empty($admin_settings['footer_text'])) {{$admin_settings['footer_text']}} @else{{__('Copyright')}} &copy; {{ config('app.name', 'WorkDo') }}@endif{{date('Y')}}
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer end here -->

     <!--scripts start here-->
     <script src="{{ asset('packages/workdo/PropertyManagement/src/Resources/assets/js/jquery.min.js') }}"></script>
     <script src="{{ asset('packages/workdo/PropertyManagement/src/Resources/assets/js/swiper-bundle.min.js') }}"></script>
     <script src="{{ asset('packages/workdo/PropertyManagement/src/Resources/assets/js/lightgallery.min.js') }}"></script>
     <script src="{{ asset('packages/workdo/PropertyManagement/src/Resources/assets/js/custom.js') }}"></script>
     <script src="{{ asset('packages/workdo/PropertyManagement/src/Resources/assets/js/bootstrap-notify/bootstrap-notify.min.js') }}">
     </script>
     <!--scripts end here-->


    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script>
        function show_toastr(title, message, type) {

            var o, i;
            var icon = '';
            var cls = '';
            if (type == 'success') {
                icon = 'fas fa-check-circle';
                cls = 'success';
            } else {
                icon = 'fas fa-times-circle';
                cls = 'danger';
            }
            $.notify({
                icon: icon,
                title: " " + title,
                message: message,
                url: ""
            }, {
                element: "body",
                type: cls,
                allow_dismiss: !0,
                placement: {
                    from: 'top',
                    align: 'right'
                },
                offset: {
                    x: 15,
                    y: 15
                },
                spacing: 10,
                z_index: 1080,
                delay: 2500,
                timer: 2000,
                url_target: "_blank",
                mouse_over: !1,
                animate: {
                    enter: o,
                    exit: i
                },
                template: '<div class="alert alert-{0} alert-icon alert-group alert-notify" data-notify="container" role="alert"><div class="alert-group-prepend alert-content"><span class="alert-group-icon"><i data-notify="icon"></i></span></div><div class="alert-content"><strong data-notify="title">{1}</strong><div data-notify="message">{2}</div></div><button type="button" class="close toastr-close" data-notify="dismiss" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
            });
        }
    </script>
    <!--scripts end here-->
    @stack('script-page')
    @if ($message = Session::get('success'))
        <script>
            show_toastr('{{ __('Success') }}', '{{ __($message) }}', 'success');
        </script>
    @endif
    @if ($message = Session::get('error'))
        <script>
            show_toastr('{{ __('Error') }}', '{{ __($message) }}', 'error');
        </script>
    @endif

</body>

</html>
