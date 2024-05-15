<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="image/x-icon" rel="shortcut icon" href="{{ $settingsData->icon }}">
    <meta name="keywords" content="{{ $settingsData->ceo_keywords }}" />
    <meta name="description" content="{{ $settingsData->ceo_desc }}" />

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $settingsData->name }}</title>

    {{-- Fonts --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

    {{-- Styles --}}
    <link rel="stylesheet" href="/vendor/ssda-1/proxies/assets/css/style.css{{ '?' . time() }}">
    <link rel="stylesheet" href="/vendor/ssda-1/proxies/assets/css/components.css{{ '?' . time() }}">
    <link rel="stylesheet" href="/vendor/ssda-1/proxies/assets/css/vars.css">
    <link rel="stylesheet" href="/vendor/ssda-1/proxies/assets/css/modal.css{{ '?' . time() }}">
    @yield('style')
    <link rel="stylesheet" href="/vendor/ssda-1/proxies/assets/css/media.css{{ '?' . time() }}">

    {{-- Стороннее --}}
    {{-- подключаем CSS-селекта --}}
    <link rel="stylesheet" href="/vendor/ssda-1/proxies/assets/css/itc-custom-select.css">
    {{-- подключаем JS-селекта --}}
    <script src="/vendor/ssda-1/proxies/assets/js/itc-custom-select.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

</head>

<body class="@yield('body-class')">

    @if (Request::is('lk') ||
            Request::is('control-panel') ||
            Request::is('referral') ||
            Request::is('buy-proxy') ||
            Request::is('replenishment') ||
            Request::is('support') ||
            Request::is('support/*') ||
            Request::is('help') ||
            Request::is('training-center') ||
            Request::is('partners'))
        @include(
            'proxies::templates.' .
                (new Ssda1\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory() .
                '.layouts.head-lk')
    @else
        @include(
            'proxies::templates.' .
                (new Ssda1\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory() .
                '.layouts.head')
    @endif

    @if (config('license.key'))
        @yield('content')
        @include('proxies::templates.' . (new Ssda1\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory() . '.layouts.footer')
        @yield('modal')
        <div class="overlay js-overlay-modal"></div>
    @else
        <div class="modal notifications active">
            <div class="background">
                <div class="body">
                    <div class="textWrap">
                        <i class="fa fa-exclamation-triangle"></i>
                        <div class="title">Отсутствует лицензионный ключ!!!</div>
                        <i class="fa fa-exclamation-triangle"></i>
                    </div>
                    <div class="massage">Множество функций сервиса не будет работать пока вы не укажете ваш лицензионный ключ!</div>
                    <div></div>
                    <div class="buttonFormWrap">
                        <form action="{{ route('check.key') }}" method="POST" style="display: flex; gap: 20px; width: 100%;">
                            <input type="text" name="key" class="proxy__application-li" placeholder="Jfhua#kfhFHyNvl35SAuykAn" required style="outline: none; border: none; height: 50px; margin-bottom: 0; width: 70%;">
                            <button type="submit" class="btn button modal__cross">Применить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="overlay js-overlay-modal active"></div>
    @endif

    
    <!-- Scripts -->
    @yield('script')
    <script>
        console.log("%cDeveloped by SIASEDE Digital",
            "font-size: 24px; color: #557dfc; border: 1px solid #557dfc; padding: 10px; ");
        console.log("%chttp://ssdigital.ru/",
            "font-size: 20px; color: #557dfc; border: 1px solid #557dfc; padding: 10px; ");
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="/vendor/ssda-1/proxies/assets/js/all.js{{ '?' . time() }}"></script>
    <script src="/vendor/ssda-1/proxies/assets/js/modal.js{{ '?' . time() }}"></script>
    @yield('javascript')
    <script>
        const allMenuBtns = document.querySelectorAll('.menu-btn');
        const menu = document.querySelector('.menu');
        const ground = document.querySelector('.ground');

        allMenuBtns.forEach(function(menuBtn) {
            menuBtn.addEventListener('click', function() {
                allMenuBtns.forEach(function(btn) {
                    btn.classList.toggle('active');
                });
                menu.classList.toggle('active');
                ground.classList.toggle('active');
            });
        });

        ground.addEventListener('click', function() {
            allMenuBtns.forEach(function(btn) {
                btn.classList.remove('active');
            });
            menu.classList.remove('active');
            ground.classList.remove('active');
        });
    </script>

    <script>
        var cancelButtons = document.querySelectorAll('.js-modal-close'),
            overlayModal = document.querySelector('.js-overlay-modal');

        cancelButtons.forEach(function(item) {
            item.addEventListener('click', function(e) {
                var parentModal = this.closest('.modal');
                parentModal.classList.remove('active');
                overlayModal.classList.remove('active');
            });
        });
    </script>

    {!! $settingsData->google_m !!}
    {!! $settingsData->yandex_m !!}
</body>

</html>
