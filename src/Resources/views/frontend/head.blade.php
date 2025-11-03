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

    $favicon = isset($company_settings['favicon']) ? $company_settings['favicon'] : (isset($admin_settings['favicon']) ? $admin_settings['favicon'] : 'uploads/logo/favicon.png');
    $rtl = isset($company_settings['site_rtl']) ? $company_settings['site_rtl'] : (isset($admin_settings['site_rtl']) ? $admin_settings['site_rtl'] : 'off');
    if ($company_settings['cust_darklayout'] == 'on') {
        $logo = isset($company_settings['logo_light']) ? $company_settings['logo_light'] : (isset($admin_settings['logo_light']) ? $admin_settings['logo_light'] : 'uploads/logo/logo_light.png');
    } else {
        $logo = isset($company_settings['logo_dark']) ? $company_settings['logo_dark'] : (isset($admin_settings['logo_dark']) ? $admin_settings['logo_dark'] : 'uploads/logo/logo_dark.png');
    }
@endphp


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="property">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>{{ __('Property') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="property">
    <meta name="keywords" content="property">


    <meta name="title"
        content="{{ !empty($admin_settings['meta_title']) ? $admin_settings['meta_title'] : 'WOrkdo Dash' }}">
    <meta name="keywords"
        content="{{ !empty($admin_settings['meta_keywords']) ? $admin_settings['meta_keywords'] : 'WorkDo Dash,SaaS solution,Multi-workspace' }}">
    <meta name="description"
        content="{{ !empty($admin_settings['meta_description']) ? $admin_settings['meta_description'] : 'Discover the efficiency of Dash, a user-friendly web application by WorkDo.' }}">
    <!-- Open Graph / Facebook -->

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:title"
        content="{{ !empty($admin_settings['meta_title']) ? $admin_settings['meta_title'] : 'WOrkdo Dash' }}">
    <meta property="og:description"
        content="{{ !empty($admin_settings['meta_description']) ? $admin_settings['meta_description'] : 'Discover the efficiency of Dash, a user-friendly web application by WorkDo.' }} ">
    <meta property="og:image"
        content="{{ get_file(!empty($admin_settings['meta_image']) ? (check_file($admin_settings['meta_image']) ? $admin_settings['meta_image'] : 'uploads/meta/meta_image.png') : 'uploads/meta/meta_image.png') }}{{ '?' . time() }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:title"
        content="{{ !empty($admin_settings['meta_title']) ? $admin_settings['meta_title'] : 'Workdo Dash' }}">
    <meta property="twitter:description"
        content="{{ !empty($admin_settings['meta_description']) ? $admin_settings['meta_description'] : 'Discover the efficiency of Dash, a user-friendly web application by WorkDo.' }} ">
    <meta property="twitter:image"
        content="{{ get_file(!empty($admin_settings['meta_image']) ? (check_file($admin_settings['meta_image']) ? $admin_settings['meta_image'] : 'uploads/meta/meta_image.png') : 'uploads/meta/meta_image.png') }}{{ '?' . time() }}">
    <link rel="icon"
        href="{{ check_file($favicon) ? get_file($favicon) : get_file('uploads/logo/favicon.png') }}{{ '?' . time() }}"
        type="image/x-icon" />

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('packages/workdo/PropertyManagement/src/Resources/assets/css/swiper-bundle.min.css')}}">
    <link rel="stylesheet" href="{{ asset('packages/workdo/PropertyManagement/src/Resources/assets/css/lightgallery.min.css')}}">
    <link rel="stylesheet" href="{{ asset('packages/workdo/PropertyManagement/src/Resources/assets/css/main-style.css')}}">
    <link rel="stylesheet" href="{{ asset('packages/workdo/PropertyManagement/src/Resources/assets/css/responsive.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-switch-button.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}">

<style>
    .alert-content{
        background-color: white;
    }
    .alert-success{
        color: #0CAF60;
    }
    .alert-danger{
        color: #cc2e58;
    }
    .toastr-close{
        background-color: transparent;
        border: 0px;
        color: black;
        font-size: 20px;
    }
    .text-primary{
        color: var(--primary-color);
    }
    .text-danger {
        --bs-danger-rgb: 255, 58, 110;
        --bs-text-opacity: 1;
        color: rgba(var(--bs-danger-rgb), var(--bs-text-opacity)) !important;
    }
    .text-xs {
        font-size: 0.850rem !important;
    }
    .mt-1
    {
        margin-top: 0.25rem !important;
    }

</style>


    @stack('css')
</head>
