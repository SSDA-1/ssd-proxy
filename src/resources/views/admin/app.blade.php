<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="image/x-icon" rel="shortcut icon" href="{{$settingsData->icon}}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{$settingsData->name}}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="/vendor/ssda-1/proxies/admin/css/new-style.css{{'?'.time()}}">

    <link rel="stylesheet" href="/vendor/ssda-1/proxies/admin/css/components.css{{'?'.time()}}">
    <link rel="stylesheet" href="/vendor/ssda-1/proxies/admin/css/sidebar.css">
    <link rel="stylesheet" href="/vendor/ssda-1/proxies/admin/css/vars.css">
    <link rel="stylesheet" href="/vendor/ssda-1/proxies/admin/css/media.css{{'?'.time()}}">


    {{-- <link rel="stylesheet" href="/vendor/ssda-1/proxies/admin/css/style.css"> --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>

    <script src="https://kit.fontawesome.com/4285cdfee8.js" crossorigin="anonymous"></script>

    @yield('summernote')
</head>

<body>
    <div id="app">

        @include('proxies::admin.sidebar')

        <section class="wrapper">
            <div class="content">@yield('content')</div>
        </section>
        @yield('modal')
        @include('proxies::admin.inc.modal')
    </div>

    <div class="notifications invisible">
        <div class="notification">
            <div class="title-notification">@lang('proxies::phrases.Уведомление')</div>
            <div class="text-notification">@lang('proxies::phrases.Текст уведомления')</div>
            <a href="" class="link-notification">@lang('proxies::phrases.Подробнее')</a>
        </div>
    </div>


    <script src="/vendor/ssda-1/proxies/admin/js/script.js"></script>
    @auth
        <script>
            let idUserMode = {{ Auth::user()->id }};
            let idMode = {{ Auth::user()->mode }};
            let sidebarIdMode = {{ Auth::user()->sidebarmode }};
            let modeUrl = "{{ route('mode') }}";
            let sidebarUrl = "{{ route('sidebarmode') }}";
        </script>
        <script src="/vendor/ssda-1/proxies/admin/js/modal.js"></script>
    @endauth

    <script src="/vendor/ssda-1/proxies/admin/js/mode.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    @yield('script')
    <script>
        console.log("%cDeveloped by SEASIDE Digital", "font-size: 24px; color: #557dfc; border: 1px solid #557dfc; padding: 10px; ");
        console.log("%chttp://ssdigital.ru/", "font-size: 20px; color: #557dfc; border: 1px solid #557dfc; padding: 10px; ");
      </script>
    @auth
        @stack('scripts')
    @endauth
</body>

</html>
