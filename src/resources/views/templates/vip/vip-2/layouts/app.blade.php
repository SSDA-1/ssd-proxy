<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="image/x-icon" rel="shortcut icon" href="{{$settingsData->icon}}">
    <meta name="keywords" content="{{$settingsData->ceo_keywords}}"/>
    <meta name="description" content="{{$settingsData->ceo_desc}}"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{$settingsData->name}}</title>

    <!-- Fonts -->
    <!-- <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> -->
    {{-- <link rel="stylesheet"
    href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" /> --}}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>


    <!-- Styles -->
    <link rel="stylesheet" href="/vendor/ssda-1/proxies/assets/css/style.css">

</head>

<body>
<div class="wraper sidebar-menu-container" id="sidebar-menu-container">

    @include('templates.'. (new App\Http\Controllers\TemplateController())->getUserTemplateDirectory() .'.layouts.head')
    @yield('content')
    @include('templates.'. (new App\Http\Controllers\TemplateController())->getUserTemplateDirectory() .'.layouts.footer')
    @include('templates.'. (new App\Http\Controllers\TemplateController())->getUserTemplateDirectory() .'.layouts.mob-menu')
    @yield('modal')
    @yield('javascript')

</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="/vendor/ssda-1/proxies/assets/js/all.js"></script>
@yield('script')
{!! $settingsData->google_m !!}
{!! $settingsData->yandex_m !!}
</body>

</html>
