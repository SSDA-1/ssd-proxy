<!-- footer  -->
<footer class="footer center">
    <div class="footer__left">
        <div class="footer__logo">
            <a href="#"><img src="/assets/img/logo.svg" alt="ads-proxy"></a>
        </div>
        <div class="footer__language">
            <div class="footer__language-text">@lang('proxies::phrases.Язык'):</div>
            <div class="footer__language">
                @if (App::isLocale('en'))
                    <a href="{{ route('locale', ['language' => 'ru']) }}" class="footer__language-summary">RU</a>
                @else
                    <a href="{{ route('locale', ['language' => 'en']) }}" class="footer__language-summary">EN</a>
                @endif
            </div>
        </div>
    </div>
    <div class="footer__information">
        <div class="footer__title">
            <h2>@lang('proxies::phrases.Информация')</h2>
        </div>
        <ul class="footer__information-ul">
            {{-- <li class="footer__information-li"><a href="#">Тарифы</a></li>
            <li class="footer__information-li"><a href="#">Преимущества</a></li>
            <li class="footer__information-li"><a href="#">Как пользоваться</a></li>
            <li class="footer__information-li"><a href="#">Реферальная программа</a></li>
            <li class="footer__information-li"><a href="#">FAQ</a></li> --}}
            @foreach ($menusSite as $menu2)
                @if ($menu2->type_menu == 2)
                    <li class="footer__information-li"><a href="{{ $menu2->link }}">@lang('proxies::phrases.' . $menu2->name)</a></li>
                @endif
            @endforeach
        </ul>
        <div class="footer__cooperation footer__show ">
            <div class="footer__title">
                <h2>@lang('proxies::phrases.Сотрудничество')</h2>
            </div>
            <div class="footer__cooperation-ul">
                <li class="footer__cooperation-li"><a href="mailto:adsproxy@gmail.com">@lang('proxies::phrases.Почта'): adsproxy@gmail.com</a>
                </li>
                <li class="footer__cooperation-li"><a href="mailto:@adsproxysupport">Telegram: @adsproxysupport</a></li>
            </div>
        </div>
    </div>
    <div class="footer__social">

        <div class="footer__social-left">
            <div class="footer__title">
                <h2>@lang('proxies::phrases.Социальные сети')</h2>
            </div>
            <div class="footer__social-lik">
                @foreach ($menusSite as $menu3)
                    @if ($menu3->top_botton == 2)
                        @if ($menu3->name == 'telegram')
                            <a href="{{ $menu3->link }}" target="_blank"><img src="/assets/img/telegramm.svg"
                                    alt="telegramm"></a>
                        @elseif ($menu3->name == 'youtube')
                            <a href="{{ $menu3->link }}" target="_blank"><img src="/assets/img/youtube.svg"
                                    alt="youtube"></a>
                        @else
                        {{-- <a href="{{ $menu3->link }}" target="_blank"></a> --}}
                        @endif
                    @endif
                @endforeach
            </div>
        </div>

        <div class="footer__social-right">
            <div class="footer__title">
                <h2>@lang('proxies::phrases.Связь с нами')</h2>
            </div>
            <div class="footer__social-lik">
                <a href="{{ $settingsData->telegram }}" target="_blank"><img src="/assets/img/telegramm.svg"
                        alt="telegramm"></a>
                <a href="Mailto:{{ $settingsData->email }}"><img src="/assets/img/mymir.svg"
                        alt="mymir"></a>
            </div>
        </div>
    </div>
    <div class="footer__blocks">
        <div class="footer__cooperation">
            <div class="footer__title">
                <h2>@lang('proxies::phrases.Сотрудничество')</h2>
            </div>
            <div class="footer__cooperation-ul">
                <li class="footer__cooperation-li"><a href="mailto:{{$settingsData->cooperation_email}}">@lang('proxies::phrases.Почта'): {{$settingsData->cooperation_email}}</a>
                </li>
                <li class="footer__cooperation-li"><a href="https://t.me/{{$settingsData->cooperation_tg}}">Telegram: {{'@'.$settingsData->cooperation_tg}}</a></li>
            </div>
            <div class="footer__copyright footer__copyright-chow">
                <a href="#" class="footer__copyrigh-link">@lang('proxies::phrases.Политика конфиденциальности')</a>
                <a href="#" class="footer__copyrigh-link">@lang('proxies::phrases.Пользовательское соглашение')</a>
            </div>
        </div>
        <div class="footer__btn">
            <a href="/lk" class="btn no-hover">@lang('proxies::phrases.Личный кабинет')</a>
        </div>
    </div>
    <div class="footer__block-chow">
        <a href="#" class="footer__copyrigh-link">@lang('proxies::phrases.Политика конфиденциальности')</a>
        <a href="#" class="footer__copyrigh-link">@lang('proxies::phrases.Пользовательское соглашение')</a>
    </div>

</footer>
