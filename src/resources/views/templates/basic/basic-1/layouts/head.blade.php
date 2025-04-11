<!-- Меню настолка -->
<header class="main header center">
    <div class="header__logo">
        @if ($settingsData->logo !== null)
            <a href="/"><img src="{{ $settingsData->logo }}"></a>
        @else
            <a href="/">{{ $settingsData->name }}</a>
        @endif
    </div>
    <nav class="header__nav">
        @foreach ($menusSite as $menu1)
            @if ($menu1->type_menu == 1)
                <a href="{{ $menu1->link }}" class="header__link">@lang('proxies::phrases.' . $menu1->name)</a>
            @endif
        @endforeach
        <div class="footer__language" style="align-items: center; padding: 0;">
            <div class="footer__language-text">@lang('proxies::phrases.Язык'):</div>
            <div class="footer__language" style="padding: 0;">
                @if (App::isLocale('en'))
                    <a href="{{ route('locale', ['language' => 'ru']) }}" class="footer__language-summary">RU</a>
                @else
                    <a href="{{ route('locale', ['language' => 'en']) }}" class="footer__language-summary">EN</a>
                @endif
            </div>
        </div>
    </nav>
    <div class="header__social">
        @foreach ($menusSite as $menu4)
            @if ($menu4->top_botton == 2)
                @if ($menu4->name == 'telegram')
                    <a href="{{ $menu4->link }}" target="_blank"><img src="/vendor/ssda-1/proxies/assets/img/telegramm.svg"
                            alt="telegramm"></a>
                @endif
                @if ($menu4->name == 'youtube')
                    <a href="{{ $menu4->link }}" target="_blank"><img src="/vendor/ssda-1/proxies/assets/img/youtube.svg" alt="youtube"></a>
                @endif
                @if ($menu4->name == 'email')
                    <a href="{{ $menu4->link }}" target="_blank"><img src="/vendor/ssda-1/proxies/assets/img/mymir.svg" alt="mymir"></a>
                @endif
            @endif
        @endforeach
    </div>
    @guest
        <a href="/login" class="btn header__private-btn none no-hover">@lang('proxies::phrases.Войти')</a>
    @else
        <a href="/lk" class="btn header__private-btn none no-hover">@lang('proxies::phrases.Личный кабинет')</a>
    @endguest
    
    @include(
        'proxies::templates.' .
            (new Ssda1\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory() .
            '.layouts.mob-menu')
</header>
