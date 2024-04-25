@extends('proxies::templates.' . (new ssda1\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory() . '.layouts.app')

@section('content')
    {{-- <!-- Прокси блок --> --}}
    {{-- <section class="proxy center">
        <div class="proxy__left">
            <div class="proxy__left-b">
                <div class="proxy__title">
                    <h1>Мобильные прокси</h1>
                </div>
                <div class="proxy__text">
                    Приватные SOCKS5/ HTTP(S) прокси под любые задачи
                </div>
                <div class="proxy__btn-block">
                    <a href="/#proxy" class="btn proxy__btn-buy">Купить прокси</a>
                    @auth
                        <a href="/lk" class="btn proxy__btn no-pink">Личный кабинет</a>
                    @else
                        <a href="/login" class="btn proxy__btn no-pink">Личный кабинет</a>
                    @endauth
                </div>
                <div class="proxy__ul-block">
                    <ul class="proxy__ul">
                        @foreach ($advantagSite as $key => $item)
                            @if ($key <= 2)
                                <li class="proxy__li">
                                    {!! $item->description !!}
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    <ul class="proxy__ul">
                        @foreach ($advantagSite as $key => $item)
                            @if ($key >= 3)
                                <li class="proxy__li">
                                    {!! $item->description !!}
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="proxy__right">

            <div class="proxy__img">
                <img src="/assets/img/proxy.png" alt="proxy">
            </div>
        </div>
    </section> --}}

    <section class="proxy center proxy__fotter" style="margin-top: 35px;">
        <div class="proxy__title proxy__title-footter">
            <h2>@lang('phrases.Ads-proxy удобный и качественный сервис прокси')</h2>
        </div>
        <div class="proxy__blocks">

            <div class="proxy__left-body">
                <div class="proxy__subtitle">
                    <h2>
                        @lang('phrases.Автоматизированная система выдаст вам новый прокси менее чем за 60 секунд. Да, это так просто и быстро')
                    </h2>
                </div>

                <div class="proxy__ul-block">
                    <ul class="proxy__ul">
                        <li class="proxy__li">@lang('phrases.Безлимитный интернет')</li>
                        <li class="proxy__li">@lang('phrases.Смена IP по времени или по ссылке')</li>
                        <li class="proxy__li">@lang('phrases.Высокие скорости для комфортной работы')</li>
                    </ul>
                    <ul class="proxy__ul">
                        <li class="proxy__li">@lang('phrases.Приватные прокси только в 1 руки')</li>
                        <li class="proxy__li">@lang('phrases.Протоколы') <br>socks5/http(s)</li>
                        <li class="proxy__li">@lang('phrases.Подключение') <br>@lang('phrases.без впн')</li>
                    </ul>
                </div>
            </div>

            <form action="{{ route('buyFetch') }}" data-getting="buy" class="proxy__application">
                {{--<input type="text" name="month" hidden value="30">--}}
                <div class="proxy__application">
                    <div class="proxy__application-content">
                        <div class="proxy__application-form">
                            <ul class="proxy__application-ul">
                                <li class="proxy__application-li">
                                    <div class="proxy__application-text">@lang('phrases.Гео'):</div>
                                    <div class="proxy__application-countries">
                                        <div class="proxy__application-country">@lang('phrases.Казахстан')</div>
                                        <div class="proxy__application-countryFlag">
                                            <img src="{{ asset('assets/img/kz.svg') }}" alt="flag">
                                        </div>
                                    </div>

                                </li>
                                <li class="proxy__application-li">
                                    <div class="proxy__application-text">@lang('phrases.Количество'):</div>
                                    <div class="proxy__application-count">
                                        <div class="proxy__decrease"></div>
                                        <input class="proxy__application-quantity" type="text" name="count"
                                               min="1" value="1">
                                        <div class="proxy__add"></div>
                                    </div>
                                </li>
                            </ul>


                            <div class="proxy__select-wrapper down">
                                @if ($tariffSettings->type_tariff)
                                    <select action="" class="proxy__application-details" name="id">
                                        @foreach ($tariffSettings->tariff as $key => $tariff)
                                            @if ($tariff['lang'] == App::getLocale())
                                                @php
                                                    $countryKey = array_search($tariffSettings->default_country, $tariff['country']);
                                                @endphp
                                                <option value="{{ $key }}" class="proxy__application-choice"
                                                        {{ $key == 1 ? 'selected' : '' }}>
                                                    {{ $tariff['period'] }} @lang('phrases.дней') -
                                                    @if ($tariffSettings->type_proxy == 'general')
                                                        {{ $tariff['general_price'][$countryKey] }} $
                                                    @elseif($tariffSettings->type_proxy == 'private')
                                                        {{ $tariff['private_price'][$countryKey] }} $
                                                    @else
                                                        {{ $tariff['general_price'][$countryKey] }} $
                                                    @endif
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                @else
                                    <select action="" class="proxy__application-details" name="month">
                                        @for ($monthI = 5; $monthI <= $tariffSettings->max_days; $monthI++)
                                            @if ($monthI == 5)
                                                <option value="{{ $monthI }}" data-sale="">{{ $monthI }}
                                                    @lang('phrases.дней')
                                                </option>
                                            @elseif($monthI % 10 == 0 and $monthI <= 30)
                                                <option value="{{ $monthI }}" data-sale="" data-sale-count="">
                                                    {{ $monthI }}
                                                    @lang('phrases.дней')
                                                </option>
                                            @elseif($monthI % 30 == 0)
                                                <option value="{{ $monthI }}" data-sale="" data-sale-count="">
                                                    {{ $monthI }}
                                                    @lang('phrases.дней')
                                                </option>
                                            @endif
                                        @endfor
                                    </select>
                                @endif

                                <input type="hidden" name="country" value="Kazakhstan">
                                <input type="hidden" name="type" value="general">
                            </div>




                        </div>
                        <div class="proxy__application-btn">

                            @auth
                                @if (Str::startsWith(Auth::user()->email, '@'))
                                    <div class="btn nomail" data-modal="nomail" style="width: 100%;">@lang('phrases.Купить прокси')</div>
                                @else
                                    <button type="submit" class="btn" style="width: 100%;">@lang('phrases.Купить прокси')</button>
                                @endif
                            @else
                                <a href="/login" class="btn" style="width: 100%;">@lang('phrases.Купить прокси')</a>
                            @endauth

                        </div>
                    </div>
                </div>
            </form>
        </div>

    </section>

    {{-- <!-- Тарифы блок --> --}}
    <section class="tariff center">
        <div class="tariff__title">
            <h2>@lang('phrases.Тарифы')</h2>
        </div>
        @if ($tariffSettings->type_tariff)
            <div class="tariff__blocks">
                @foreach ($tariffSettings->tariff as $key => $tariff)
                    @if ($tariff['lang'] == App::getLocale())
                        <figure class="tariff__block" data-tariff="{{ $key }}">
                            @if ($tariff['name'] != null)
                                <figcaption class="tariff__price subtitle">
                                    {{ $tariff['name'] }}
                                </figcaption>
                            @endif
                            <figcaption class="tariff__price subtitle">
                                <span class="days">{{ $tariff['period'] }}</span> @lang('phrases.дней') -
                                @php
                                    $countryKey = array_search($tariffSettings->default_country, $tariff['country']);
                                @endphp
                                @if ($tariffSettings->type_proxy == 'general')
                                    <span class="cost cost_tariff cost_day">{{ $tariff['general_price'][$countryKey] }}</span>$
                                @elseif($tariffSettings->type_proxy == 'private')
                                    <span class="cost cost_tariff cost_day">{{ $tariff['private_price'][$countryKey] }}</span>$
                                @else
                                    <span class="cost cost_tariff cost_day">{{ $tariff['general_price'][$countryKey] }}</span>$
                                @endif
                            </figcaption>
                            <ul class="tariff__descriptions">
                                @foreach ($tariff['properties'] as $key2 => $property)
                                    <li class="tariff__descriptions-li">
                                        {{ $property }}
                                    </li>
                                @endforeach
                            </ul>
                            <form action="{{ route('buyFetch') }}" data-getting="buy" class="proxy__application">
                                <input type="text" name="id" value="{{ $key }}" hidden>
                                <input type="text" name="month" hidden value="{{ $tariff['period'] }}"
                                       class="amount_days">
                                <div class="proxy__application-content application-content">
                                    <div class="proxy__application-form">
                                        <ul class="proxy__application-ul">
                                            {{-- Страна --}}
                                            @if (count($tariff['country']) == 1)
                                                <li class="proxy__application-li">
                                                    <div class="proxy__application-text">@lang('phrases.Гео'):</div>
                                                    <div class="proxy__application-countries">
                                                        <input type="hidden" name="country"
                                                               value="{{ $tariffSettings->default_country }}"
                                                               class="countryInpup country_day" hidden>
                                                        <div class="proxy__application-country">
                                                            {{ $tariffSettings->default_country }}
                                                        </div>
                                                        <div class="submenu" data-id="{{ $key }}"
                                                             data-general="{{ $tariff['general_price'][$countryKey] == null ? 'Не доступно' : $tariff['general_price'][$countryKey] }}"
                                                             data-private="{{ $tariff['private_price'][$countryKey] == null ? 'Не доступно' : $tariff['private_price'][$countryKey] }}">
                                                        </div>
                                                        <div class="proxy__application-countryFlag">
                                                            <img src="{{ asset('assets/img/kz.svg') }}" alt="flag">
                                                        </div>
                                                    </div>
                                                </li>
                                            @else
                                                <li class="proxy__application-li">
                                                    <div class="proxy__application-text">@lang('phrases.Гео'):</div>
                                                    <div class="proxy__application-countries btn-submenu">
                                                        <input type="hidden" name="country"
                                                               value="{{ $tariffSettings->default_country }}"
                                                               class="countryInpup country_day" hidden>
                                                        <div class="proxy__application-country">
                                                            {{ $tariffSettings->default_country }}
                                                        </div>
                                                        <div class="proxy__application-countryFlag">
                                                            <img src="{{ asset('assets/img/kz.svg') }}" alt="flag">
                                                        </div>
                                                    </div>
                                                    <ul class="submenu" data-id="{{ $key }}"
                                                        data-general="{{ $tariff['general_price'][$countryKey] }}"
                                                        data-private="{{ $tariff['private_price'][$countryKey] }}">
                                                        @foreach ($tariff['country'] as $key2 => $country)
                                                            <li data-general="{{ $tariff['general_price'][$key2] }}"
                                                                data-private="{{ $tariff['private_price'][$key2] }}"
                                                                data-country="{{ $country }}">
                                                                {{ $country }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @endif

                                            {{-- Типы прокси --}}
                                            @if ($tariffSettings->type_proxy == 'all')
                                                <li class="proxy__application-li">
                                                    <div class="proxy__application-text">@lang('phrases.Тип'):</div>
                                                    <div class="proxy__application-count type_proxy">
                                                        <div class="proxy__application-country">
                                                            <select name="type" class="type__proxy type__proxy__day">
                                                                <option value="general">@lang('phrases.Общие')</option>
                                                                <option value="private">@lang('phrases.Приватные')</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </li>
                                            @elseif($tariffSettings->type_proxy == 'general')
                                                <input type="hidden" name="type" value="general"
                                                       class="type__proxy__day" hidden>
                                            @elseif($tariffSettings->type_proxy == 'private')
                                                <input type="hidden" name="type" value="private"
                                                       class="type__proxy__day" hidden>
                                            @endif

                                            {{-- Количество --}}
                                            <li class="proxy__application-li">
                                                <div class="proxy__application-text">@lang('phrases.Количество'):</div>
                                                <div class="proxy__application-count">
                                                    <div class="proxy__decrease"></div>
                                                    <input class="proxy__application-quantity q_d" type="text"
                                                           name="count" min="1" value="1">
                                                    <div class="proxy__add"></div>
                                                </div>
                                            </li>

                                            {{-- Промокод --}}
                                            <li class="proxy__application-li">
                                                <div class="proxy__application-text">@lang('phrases.Промокод'):</div>
                                                <div class="proxy__application-count">
                                                    <input class="promocode" type="text" name="promo"
                                                           placeholder="@lang('phrases.Промокод')" data-id="{{ $key }}">
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="proxy__application-btn">
                                        @auth
                                            @if (Str::startsWith(Auth::user()->email, '@'))
                                                <div class="btn nomail" data-modal="nomail" style="width: 100%;">@lang('phrases.Купить прокси')</div>
                                            @else
                                                <button type="submit" class="btn" style="width: 100%;">@lang('phrases.Купить прокси')</button>
                                            @endif
                                        @else
                                            <a href="/login" class="btn" style="width: 100%;">@lang('phrases.Купить прокси')</a>
                                        @endauth

                                    </div>
                                </div>
                            </form>
                            <div class="sales__block"></div>
                        </figure>
                    @endif
                @endforeach
                <a name="proxy"></a>
            </div>
        @else
            <form action="{{ route('buyFetch') }}" class="tariff__blocks tariff__block__days tariff__block"
                  data-tariff="1">
                {{-- <div class="wrap-cost-days tariff__block" data-tariff="1"> --}}
                <div class="wrap-cost-days ">
                    <div class="sum">@lang('phrases.Стоимость') <span class="cost cost_day"></span> $ / <span class="days"></span>
                        дней</div>
                </div>
                <div class="wrap-days-tariff">
                    {{-- Срок --}}
                    <select name="month" id="" class="proxy__application-li amount_days">
                        @for ($monthI = 5; $monthI <= $tariffSettings->max_days; $monthI++)
                            @if ($monthI == 5)
                                <option value="{{ $monthI }}" data-sale="">{{ $monthI }} @lang('phrases.дней')
                                </option>
                            @elseif($monthI % 10 == 0 and $monthI <= 30)
                                <option value="{{ $monthI }}" data-sale="" data-sale-count="">
                                    {{ $monthI }}
                                    @lang('phrases.дней')
                                </option>
                            @elseif($monthI % 30 == 0)
                                <option value="{{ $monthI }}" data-sale="" data-sale-count="">
                                    {{ $monthI }}
                                    @lang('phrases.дней')
                                </option>
                            @endif
                        @endfor
                    </select>
                    {{-- Количество --}}
                    <div class="proxy__application-li">
                        <div class="proxy__application-text">@lang('phrases.Количество'):</div>
                        <div class="proxy__application-count">
                            <div class="proxy__decrease"></div>
                            <input class="proxy__application-quantity q_d" type="text" name="count" min="1"
                                   value="1">
                            <div class="proxy__add"></div>
                        </div>
                    </div>
                    {{-- Тип --}}
                    @if ($tariffSettings->type_proxy == 'all')
                        <select name="type" class="type__proxy__day proxy__application-li">
                            <option value="general">@lang('phrases.Общие')</option>
                            <option value="private">@lang('phrases.Приватные')</option>
                        </select>
                    @elseif($tariffSettings->type_proxy == 'general')
                        <input type="hidden" name="type" value="general" class="type__proxy__day" hidden>
                    @elseif($tariffSettings->type_proxy == 'private')
                        <input type="hidden" name="type" value="private" class="type__proxy__day" hidden>
                    @endif
                    {{-- Страна --}}
                    <select name="country" id="" class="country_day proxy__application-li">
                        @foreach ($tariffSettings['days_tariff'] as $key2 => $country)
                            <option value="{{ $country['country'] }}">
                                {{ $country['country'] }}
                            </option>
                        @endforeach
                    </select>
                    {{-- Промокод --}}
                    <input class="promocode proxy__application-li" type="text" name="promo" placeholder="@lang('phrases.Промокод')"
                           data-id="1">



                    <div class="proxy__application-btn ">
                        @auth
                            @if (Str::startsWith(Auth::user()->email, '@'))
                                <div class="btn nomail" data-modal="nomail">@lang('phrases.Купить прокси')</div>
                            @else
                                <button type="submit" class="btn">@lang('phrases.Купить прокси')</button>
                            @endif
                        @else
                            <a href="/login" class="btn" style="width: 100%;">@lang('phrases.Купить прокси')</a>
                        @endauth
                    </div>
                </div>
                <div class="sales__block days_s"></div>
            </form>
        @endif
    </section>

    {{-- <!-- Для чего подходят прокси ads-proxy --> --}}
    <section class="ads-proxy center">
        <div class="ads-proxy__title">
            <h2>@lang('phrases.Для чего подходят прокси ads-proxy')</h2>
        </div>
        <div class="ads-proxy__blocks">
            <div class="ads-proxy__left">
                <div class="ads-proxy__block ads-proxy__traffic">
                    <div class="ads-proxy__subtitle">
                        <h2>@lang('phrases.Для работы с трафиком')</h2>
                    </div>
                    <div class="ads-proxy__text">
                        @lang('phrases.Прокси прекрасно подходят для работы со всеми видами трафика')
                    </div>
                </div>
                <div class="ads-proxy__block ads-proxy__source">
                    <div class="ads-proxy__subtitle">
                        <h2>@lang('phrases.Любые источники')</h2>
                    </div>
                    <div class="ads-proxy__text">
                        Facebook tik-tok google yandex
                    </div>
                </div>
                <div class="ads-proxy__block ads-proxy__marketing">
                    <div class="ads-proxy__subtitle">
                        <h2>@lang('phrases.SEO и маркетинг')</h2>
                    </div>
                    <div class="ads-proxy__text">
                        @lang('phrases.Комфортная работа со своими проектами')
                    </div>
                </div>

            </div>


            <div class="ads-proxy__right">
                <div class="ads-proxy__block ads-proxy__account">
                    <div class="ads-proxy__subtitle">
                        <h2>@lang('phrases.Регистрация аккаунтов')</h2>
                    </div>
                    <div class="ads-proxy__text">
                        @lang('phrases.Без ограничений по гео и колличеству регистарций')
                    </div>
                </div>
                <div class="ads-proxy__block ads-proxy__ananim">
                    <div class="ads-proxy__subtitle">
                        <h2>@lang('phrases.Анонимное использование')</h2>
                    </div>
                    <div class="ads-proxy__text">
                        @lang('phrases.Пользуйтесь прокси для любых ваших задач')
                    </div>
                </div>
                <div class="ads-proxy__block ads-proxy__mobile btns-block">
                    <div class="ads-proxy__subtitle">
                        <h2>@lang('phrases.Попробуйте Ads-proxy')</h2>
                        <div class="ads-proxy__text">
                            @lang('phrases.Приватные, быстрые мобильные прокси')
                        </div>
                    </div>
                    <div class="ads-proxy__block-btn">
                        <a href="/#proxy" class="btn ads-proxy__btn">@lang('phrases.Купить') &nbsp<span>@lang('phrases.прокси')</span></a>
                        @auth
                            <a href="/lk" class="btn ads-proxy__btn">@lang('phrases.Личный кабинет')</a>
                        @else
                            <a href="/login" class="btn ads-proxy__btn">@lang('phrases.Личный кабинет')</a>
                        @endauth
                    </div>
                    <a name="advantages"></a>
                </div>
            </div>
        </div>
    </section>

    {{-- <!-- Почему ads-proxy - удобно? --> --}}
    <section class="advantages center">
        <div class="advantages__title">
            <h2>@lang('phrases.Почему ads-proxy - удобно')?</h2>
        </div>
        <div class="advantages__blocks">
            <div class="advantages__block">
                <div class="advantages__block-body">
                    <h3>@lang('phrases.Приватный канал')</h3>
                    <p>@lang('phrases.При покупке прокси с ним работаете только вы, мы выдаем строго один прокси в одни руки')</p>

                </div>
            </div>
            <div class="advantages__block">
                <div class="advantages__block-body">
                    <h3>@lang('phrases.Скорость')</h3>
                    <p>@lang('phrases.Для комфортоной работы с любым источником траффика')</p>
                </div>
            </div>
            <div class="advantages__block">
                <div class="advantages__block-body">
                    <h3>@lang('phrases.Смена IP')</h3>
                    <p>@lang('phrases.Смена ip по ссылке или времни, можно выбрать в личной кабинете, как вам будет удобнее')!</p>
                </div>
            </div>
            <div class="advantages__block">
                <div class="advantages__block-body">
                    <h3>@lang('phrases.Протоколы')
                    </h3>
                    <p>@lang('phrases.Socks 5 или http(s) два самых удобных и распростроненных протокола')</p>
                </div>
            </div>
            <div class="advantages__block">
                <div class="advantages__block-body">
                    <h3>@lang('phrases.Подключение')
                    </h3>
                    <p>@lang('phrases.Наш сервис поддерживате подключение без VPN, покупаете и пользуетесь без ограничений')!</p>
                </div>
            </div>
            <div class="advantages__block">
                <div class="advantages__block-body">
                    <h3>@lang('phrases.Качественная поддержка')
                    </h3>
                    <p>@lang('phrases.Всегда придем на помощь и все расскажем, поможем с настройкой и работой прокси')!</p>
                </div>
            </div>
        </div>
    </section>

    {{-- <!-- как все работает --> --}}
    <section class="instruction center">
        <div class="instruction__title">
            <h2>@lang('phrases.Как все работает')</h2>
        </div>
        <div class="instruction__blocks">
            <div class="instruction__left">
                <div class="instruction__img">
                    <img src="/assets/img/seting.png" alt="img">
                </div>
                <div class="instruction__link">
                    <p>@lang('phrases.Наш') <a href="#">@lang('phrases.софт')</a></p>
                </div>

            </div>
            <div class="instruction__center">
                <div class="instruction__center-top">
                    <div class="instruction__img">
                        <img src="/assets/img/map.png" alt="img">
                    </div>
                    <div class="instruction__link">
                        <p>@lang('phrases.Наш') <a href="#">@lang('phrases.впн')</a></p>
                    </div>
                </div>
                <div class="instruction__center-bot">
                    <div class="instruction__img">
                        <img src="/assets/img/sistem.png" alt="img">
                    </div>
                    <div class="instruction__link">
                        <p>@lang('phrases.Наши') <a href="#">@lang('phrases.сервера')</a></p>
                    </div>
                </div>


            </div>
            <div class="instruction__right">
                <div class="instruction__img">
                    <img src="/assets/img/window.png" alt="img">
                </div>
                <div class="instruction__link">
                    <p>@lang('phrases.Наш сервис')</p>
                    <p>@lang('phrases.и') @lang('phrases.Ваш') <a href="#">@lang('phrases.доступ к прокси')!</a></p>
                </div>

            </div>
        </div>

    </section>

    {{-- <!-- Как пользоваться прокси --> --}}
    <a name="how-to-use"></a>
    <section class="rule center">
        <div class="rule__title">
            <h2>@lang('phrases.Как пользоваться прокси')</h2>
        </div>
        <div class="rule__body">
            <div class="rule__left">
                <ul class="rule__left-ul">
                    <li><span>1</span>@lang('phrases.Регистрируетесь на сайте ads-proxy.com')</li>
                    <li><span>2</span>@lang('phrases.Пополняете баланс и выбираете тариф')</li>
                    <li><span>3</span>@lang('phrases.В вашем кабинете появится прокси в двух протоколах socks5 и http(s)')</li>
                    <li><span>4</span>@lang('phrases.Сменить айпи можно по полученной ссылке')</li>
                    <li><span>5</span>@lang('phrases.Посмотрите видео как настроить прокси и гайд лайн по личному кабинету')</li>
                </ul>
            </div>
            <div class="rule__right">
                <div class="rule__right-img">
                    <iframe src="https://www.youtube.com/embed/5VuaKhHkUIA?si=6BOKk93df97aFxdn&amp;controls=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    {{-- <img src="/assets/img/rule.png" alt="rule">  --}}
                </div>
            </div>
        </div>
    </section>

    {{-- <!-- Ответы на частозадаваемые вопросы --> --}}
    <section class="answers center">
        <a name="FAQ"></a>
        <div class="answers__title">
            <h2>@lang('phrases.Ответы на частозадаваемые вопросы')</h2>
        </div>
        <div class="answers__body">
            @foreach ($mainFaq as $faq)
                <details class="answers__details">
                    <summary class="answers__summary">@lang('phrases.'.$faq->question)</summary>
                    <div class="answers__text">
                        <p>{!! trans('phrases.'.$faq->answer) !!}</p>
                    </div>
                </details>
            @endforeach
        </div>
    </section>

    {{-- <!-- Реферальная программа --> --}}
    <section class="referral center">
        <div class="referral__title">
            <h2>@lang('phrases.Реферальная программа')</h2>
        </div>
        <div class="referral__blocks">
            <div class="referral__blocks-left">
                <img src="/assets/img/referral.png" alt="picture">
            </div>
            <div class="referral__blocks-right">
                <div class="referral__content">
                    <figure class="referral__content-text">
                        <div class="referral__subtitle">
                            <h3>@lang('phrases.Привлекай клиентов, и зарабатывай')!</h3>
                        </div>
                        <div class="referral__text">
                            <p>@lang('phrases.Стань нашим партнером и получай доход со всех заказов приведенных клиентов').</p>
                        </div>
                    </figure>
                    <figure class="referral__content-text">
                        <div class="referral__subtitle">
                            <h3>@lang('phrases.Стабильный заработок')!</h3>
                        </div>
                        <div class="referral__text">
                            <p>@lang('phrases.Ответы на частозадаваемые вопросы').</p>
                        </div>
                    </figure>
                    <figure class="referral__content-text">
                        <div class="referral__subtitle">
                            <h3>@lang('phrases.Не обязательно быть покупателем наших прокси')!</h3>
                        </div>
                        <div class="referral__text">
                            <p>@lang('phrases.Для участия в реферальной программе не обязательно быть покупателем наших прокси').</p>
                        </div>
                    </figure>
                    <figure class="referral__content-text">
                        <div class="referral__subtitle">
                            <h3>@lang('phrases.Статистика и рекламные материалы')</h3>
                        </div>
                        <div class="referral__text">
                            <p>@lang('phrases.В личном кабинете, в разделе «Реферальная программа»').</p>
                        </div>
                    </figure>

                </div>
                <div class="referral__btn">
                    <a href="/referral" class="btn">@lang('phrases.Присоединиться')</a>
                    <a href="/lk" class="btn">@lang('phrases.Личный кабинет')</a>
                </div>
            </div>
        </div>

    </section>

    {{-- <!-- Ads-proxy удобный и качественный сервис прокси. --> --}}
    <section class="proxy center proxy__fotter">
        <div class="proxy__title proxy__title-footter">
            <h2>@lang('phrases.Ads-proxy удобный и качественный сервис прокси')</h2>
        </div>
        <div class="proxy__blocks">

            <div class="proxy__left-body">
                <div class="proxy__subtitle">
                    <h2>
                        @lang('phrases.Автоматизированная система выдаст')!
                    </h2>
                </div>

                <div class="proxy__ul-block">
                    <ul class="proxy__ul">
                        <li class="proxy__li">@lang('phrases.Безлимитный интернет')
                        </li>
                        <li class="proxy__li">@lang('phrases.Смена IP по времени или по ссылке')</li>
                        <li class="proxy__li">@lang('phrases.Высокие скорости для комфортной работы')</li>
                    </ul>
                    <ul class="proxy__ul">
                        <li class="proxy__li">@lang('phrases.Приватные прокси только в 1 руки')</li>
                        <li class="proxy__li">@lang('phrases.Протоколы') <br>socks5/http(s)</li>
                        <li class="proxy__li">@lang('phrases.Подключение') <br>@lang('phrases.без впн')</li>
                    </ul>
                </div>
            </div>

            <form action="{{ route('buyFetch') }}" data-getting="buy" class="proxy__application">
                {{--<input type="text" name="month" hidden value="30">--}}
                <div class="proxy__application">
                    <div class="proxy__application-content">
                        <div class="proxy__application-form">
                            <ul class="proxy__application-ul">
                                <li class="proxy__application-li">
                                    <div class="proxy__application-text">Гео:</div>
                                    <div class="proxy__application-countries">
                                        <div class="proxy__application-country">Казахстан</div>
                                        <div class="proxy__application-countryFlag">
                                            <img src="{{ asset('assets/img/kz.svg') }}" alt="flag">
                                        </div>
                                    </div>

                                </li>
                                <li class="proxy__application-li">
                                    <div class="proxy__application-text">Количество:</div>
                                    <div class="proxy__application-count">
                                        <div class="proxy__decrease"></div>
                                        <input class="proxy__application-quantity" type="text" name="count"
                                               min="1" value="1">
                                        <div class="proxy__add"></div>
                                    </div>
                                </li>
                            </ul>


                            <div class="proxy__select-wrapper down">
                                @if ($tariffSettings->type_tariff)
                                    <select action="" class="proxy__application-details" name="id">
                                        @foreach ($tariffSettings->tariff as $key => $tariff)
                                            @if ($tariff['lang'] == App::getLocale())
                                                @php
                                                    $countryKey = array_search($tariffSettings->default_country, $tariff['country']);
                                                @endphp
                                                <option value="{{ $key }}" class="proxy__application-choice"
                                                        {{ $key == 1 ? 'selected' : '' }}>
                                                    {{ $tariff['period'] }} дней -
                                                    @if ($tariffSettings->type_proxy == 'general')
                                                        {{ $tariff['general_price'][$countryKey] }} $
                                                    @elseif($tariffSettings->type_proxy == 'private')
                                                        {{ $tariff['private_price'][$countryKey] }} $
                                                    @else
                                                        {{ $tariff['general_price'][$countryKey] }} $
                                                    @endif
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>

                                @else
                                    <select action="" class="proxy__application-details" name="month">
                                        @for ($monthI = 5; $monthI <= $tariffSettings->max_days; $monthI++)
                                            @if ($monthI == 5)
                                                <option value="{{ $monthI }}" data-sale="">{{ $monthI }}
                                                    дней
                                                </option>
                                            @elseif($monthI % 10 == 0 and $monthI <= 30)
                                                <option value="{{ $monthI }}" data-sale="" data-sale-count="">
                                                    {{ $monthI }}
                                                    дней
                                                </option>
                                            @elseif($monthI % 30 == 0)
                                                <option value="{{ $monthI }}" data-sale="" data-sale-count="">
                                                    {{ $monthI }}
                                                    дней
                                                </option>
                                            @endif
                                        @endfor
                                    </select>
                                @endif
                                <input type="hidden" name="country" value="Kazakhstan">
                                <input type="hidden" name="type" value="general">
                            </div>




                        </div>
                        <div class="proxy__application-btn">

                            @auth
                                @if (Str::startsWith(Auth::user()->email, '@'))
                                    <div class="btn nomail" data-modal="nomail" style="width: 100%;">Купить прокси</div>
                                @else
                                    <button type="submit" class="btn" style="width: 100%;">Купить прокси</button>
                                @endif
                            @else
                                <a href="/login" class="btn" style="width: 100%;">Купить прокси</a>
                            @endauth

                        </div>
                    </div>
                </div>
            </form>
        </div>

    </section>





    {{-- <section class="s1">
        <div class="wrap">
            <div class="content">
                <h1>
                    <span class="text-gradient">Мобильные приватные прокси</span> под любые задачи
                </h1>
                <h4>Арбитраж трафика, регистрация аккаунтов, полная анонимность в интернете</h4>
                <a href="" class="btn gradient">Начать</a>
            </div>
        </div>
    </section>
    <span id="advantages"></span>
    <section class="s2">
        <div class="wrap">
            @foreach ($advantagSite as $item)
                <div class="content">
                    <img src="{{ $item->image }}">
                    {!! $item->description !!}
                </div>
            @endforeach
        </div>
    </section>
    <section class="s3">
        <div class="wrap">
            <div class="content">
                <h2>Почему нас выбирают?</h2>
                <p>Мы команда Ads-proxy работаем с трафиком уже свыше 15 лет и знаем,
                    <span class="text-gradient">как важны хорошие приватные мобильные прокси.</span>
                </p>
                <p>Поэтому в нашем сервисе вы сможете найти приватные прокси под ваши любые задачи,
                    от работы с рекламными кабинетами, как facebook, google ads, tik-tok до регистрации
                    аккаунтов и любых задач под ваши потребности.</p>
            </div>
        </div>
    </section>
    <span id="rates"></span> --}}
    {{-- <section class="s4">
        <div class="wrap">


            <form action="{{ route('buyFetch') }}" class="content" data-getting="buy">
                <!-- Изначально не активна ни одна опция -->
                <div class="itc-select " id="countryProxyBuy" name="country">
                    <!-- Кнопка для открытия выпадающего списка -->
                    <button type="button" class="itc-select__toggle text-gradient" name="car" value=""
                        data-select="toggle" data-index="-1"><img src="{{ asset('assets/img/geo.svg') }}">Выбрать
                        Гео</button>
                    <!-- Выпадающий список -->
                    <div class="itc-select__dropdown">
                        <ul class="itc-select__options">

                            @foreach (array_unique($serversCountry) as $item)
                                @php
                                    $countries = Countries::getList('en', 'php', 'cldr');
                                    $regionCode = $item;
                                    $filteredCountries = array_filter($countries, function ($country) use ($regionCode) {
                                        return isset($country['name']) && $country['name'] == $regionCode;
                                    });
                                    $countrySearch = reset($filteredCountries);
                                    // print_r(reset($filteredCountries))
                                @endphp
                                <li class="itc-select__option" data-select="option" data-value="{{ $item }}"
                                    data-index="0">
                                    <div class="wrap-country">
                                        <img style="border-radius: 50%;border: 1px solid #ffffff52;"
                                            src="{{ asset('vendor/webpatser/laravel-countries/src/flags/' . $countrySearch['flag']) }}">
                                        {{ $item }}
                                    </div>
                                </li>
                            @endforeach --}}
    {{-- <li class="itc-select__option" data-select="option" data-value="belarus" data-index="0">
                                <div class="wrap-country"><img src="{{ asset('assets/img/br.svg') }}">Беларусь</div>
                            </li>
                            <li class="itc-select__option" data-select="option" data-value="kazakhstan" data-index="1">
                                <div class="wrap-country"><img src="{{ asset('assets/img/kz.svg') }}">Казахстан</div>
                            </li> --}}
    {{-- </ul>
                    </div>
                </div>

                <select name="country" id="countryProxyBuyOld" style="display: none">
                    @foreach ($serversCountry as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>

                <div class="itc-select " name="month" id="monthProxyBuy">
                    <!-- Кнопка для открытия выпадающего списка -->
                    <button type="button" class="itc-select__toggle text-gradient" name="car" value=""
                        data-select="toggle" data-index="-1"><img src="{{ asset('assets/img/clock-small.svg') }}">Срок
                        действия</button>
                    <!-- Выпадающий список -->
                    <div class="itc-select__dropdown">
                        <ul class="itc-select__options">
                            @for ($monthI = 7; $monthI <= $settingModel->proxy_mounth; $monthI++)
                                @if ($monthI == 7)
                                    <li class="itc-select__option" data-select="option" data-value="{{ $monthI }}"
                                        data-index="{{ $monthI }}" data-sale="">
                                        {{ $monthI }} дней
                                    </li>
                                @elseif($monthI % 20 == 0 and $monthI <= 30)
                                    <li class="itc-select__option" data-select="option" data-value="{{ $monthI }}"
                                        data-index="{{ $monthI }}"
                                        data-sale="{{ $settingModel->proxy_two_sel_period }}"
                                        data-sale-count="{{ $settingModel->proxy_two_sel_count }}">
                                        {{ $monthI }} дней
                                    </li>
                                @elseif($monthI % 30 == 0)
                                    <li class="itc-select__option" data-select="option" data-value="{{ $monthI }}"
                                        data-index="{{ $monthI }}"
                                        data-sale="{{ $settingModel->proxy_three_sel_period }}"
                                        data-sale-count="{{ $settingModel->proxy_three_sel_count }}">
                                        {{ $monthI }} дней
                                    </li>
                                @endif
                            @endfor
                        </ul>
                    </div>
                </div>

                <select name="month" id="monthProxyBuyOld" style="display: none">
                    @for ($monthI = 7; $monthI <= $settingModel->proxy_mounth; $monthI++)
                        @if ($monthI == 7)
                            <option value="{{ $monthI }}" data-sale="">{{ $monthI }} дней
                            </option>
                        @elseif($monthI % 20 == 0 and $monthI <= 30)
                            <option value="{{ $monthI }}" data-sale="{{ $settingModel->proxy_two_sel_period }}"
                                data-sale-count="{{ $settingModel->proxy_two_sel_count }}">{{ $monthI }}
                                дней
                            </option>
                        @elseif($monthI % 30 == 0)
                            <option value="{{ $monthI }}" data-sale="{{ $settingModel->proxy_three_sel_period }}"
                                data-sale-count="{{ $settingModel->proxy_three_sel_count }}">
                                {{ $monthI }}
                                дней
                            </option>
                        @endif
                    @endfor
                </select>

                <div class="itc-select" name="type" id="typeProxyBuy">
                    <!-- Кнопка для открытия выпадающего списка -->
                    <button type="button" class="itc-select__toggle text-gradient" name="car" value=""
                        data-select="toggle" data-index="-1"><img
                            src="{{ asset('assets/img/type.svg') }}">Приватные</button>
                    <!-- Выпадающий список -->
                    <div class="itc-select__dropdown">
                        <ul class="itc-select__options"> --}}
    {{-- <li class="itc-select__option" data-select="option" data-value="private" data-index="0"
                                data-price="{{ $settingModel->proxy_privat_price }}">Приватные</li>
                            <li class="itc-select__option" data-select="option" data-value="general" data-index="1"
                                data-price="{{ $settingModel->proxy_all_price }}">Общие</li> --}}
    {{-- <li class="itc-select__option" data-select="option" data-value="general" data-index="0"
                                data-price="{{ $settingModel->proxy_privat_price }}">Приватные</li>
                        </ul>
                    </div>
                </div>

                <select name="type"general id="typeProxyBuyOld" style="display: none"> --}}
    {{-- <option value="private" data-price="{{ $settingModel->proxy_privat_price }}">Приватные
                    </option>
                    <option value="general" data-price="{{ $settingModel->proxy_all_price }}">Общие</option> --}}
    {{-- <option value="general" data-price="{{ $settingModel->proxy_all_price }}">Приватные</option>
                </select>

                <div class="input-img">
                    <input type="number" class="input text-gradient" placeholder="Количество"
                        style="padding-left: 84px;" name="count" min="1" value="1" id="countProxyBuy"
                        data-sale-count-two="{{ $settingModel->proxy_two_sel_count }}"
                        data-sale-count-five="{{ $settingModel->proxy_three_sel_count }}" required>
                </div>
                <div class="promo input">
                    <input type="text" placeholder="Промокод" class="text-gradient">
                    <button class="btn prom">Применить промокод</button>
                </div>
                <button class="btn prom mob">Применить промокод</button>
                <div class="price-payment">
                    <div class="price header_price">
                        <p class="text-gradient title topform-total">{{ $settingModel->proxy_all_price * 7 }} $</p>
                        <span><span class="topform-price">{{ $settingModel->proxy_all_price * 7 }} $</span>/шт</span> --}}
    {{-- <span>200 $/шт.</span> --}}
    {{-- </div>
                    @auth
                        <button type="submit" class="btn payment w346">Купить</button>
                    @else
                        <a href="/login" class="btn payment w346">Купить</a>
                    @endauth
                </div>
            </form>
        </div>
    </section>
    <section class="s5">
        <div class="wrap">
            <h4>Ещё немного преимуществ</h4>
            <ul>
                <li>Удобные тарифные планы с минимальным сроком аренды 7 дней<img
                        src="{{ asset('assets/img/star.svg') }}"></li>
                <li>Удобный личный кабинет с гибкими настрйоками прокси<img src="{{ asset('assets/img/star.svg') }}"></li>
                <li>Приватные прокси выдаются толко в одни руки<img src="{{ asset('assets/img/star.svg') }}"></li>
                <li>Несколько стран на выбор<img src="{{ asset('assets/img/star.svg') }}"></li>
                <li>Стабильные скорости для комфортной работы<img src="{{ asset('assets/img/star.svg') }}"></li>
            </ul>
        </div>
    </section>
    <section class="s6">
        <div class="wrap"> --}}
    {{-- <img src="{{ asset('assets/img/logo-purple 1.png') }}"> --}}
    {{-- <img src="{{ asset('assets/img/logo 1.png') }}">
            <img src="{{ asset('assets/img/Logo_Adv2 1.png') }}">
            <img src="{{ asset('assets/img/Group 130.png') }}">
        </div>
    </section>
    <section class="s7">
        <div class="wrap">
            <div class="content">
                <h2>Реферальная программа</h2>
                <p>Станьте участником нашей реферальной программы
                    просто приводите клиентов по своей реферальной
                    ссылке и получайте до 20% по реферальной системе с каждого клиента</p>
                <a href="/lk" class="btn payment">В личный кабинет</a>
            </div>
        </div>
    </section> --}}


    {{-- <!-- Экран под меню --> --}}
    {{-- <div class="top-section">
        <div class="wraper-top-section">
            <div class="header-title">
                <h1>Персональные и общие прокси</h1>
                <div>Социальные сети, парсинг поисковых систем, онлайн игры, веб-серфинг и многое другое.</div>
                <div class="header_divider"></div>
            </div>
            <form action="{{ route('buyFetch') }}">
                <div class="buying-proxies">
                    <ul>
                        <li>
                            <select name="country" id="countryProxyBuy">
                                <option value="ru">Россия</option>
                                <option value="uk">Украина</option>
                            </select>
                        </li>
                        <li>
                            <select name="month" id="monthProxyBuy">
                                @for ($monthI = 5; $monthI <= $settingModel->proxy_mounth; $monthI++)
                                    @if ($monthI == 5)
                                        <option value="{{ $monthI }}"
                                            data-sale="{{ $settingModel->proxy_two_sel_period }}">{{ $monthI }} дней
                                        </option>
                                    @elseif($monthI % 10 == 0 and $monthI <= 30)
                                        <option value="{{ $monthI }}"
                                            data-sale="{{ $settingModel->proxy_three_sel_period }}"
                                            data-sale-count="{{ $settingModel->proxy_three_sel_count }}">{{ $monthI }}
                                            дней
                                        </option>
                                    @elseif($monthI % 30 == 0)
                                        <option value="{{ $monthI }}"
                                            data-sale="{{ $settingModel->proxy_three_sel_period }}"
                                            data-sale-count="{{ $settingModel->proxy_three_sel_count }}">
                                            {{ $monthI }}
                                            дней
                                        </option>
                                    @endif
                                @endfor
                            </select>
                        </li>
                        <li>
                            <select name="type" id="typeProxyBuy">
                                <option value="private" data-price="{{ $settingModel->proxy_privat_price }}">Приватные
                                </option>
                                <option value="general" data-price="{{ $settingModel->proxy_all_price }}">Общие</option>
                            </select>
                        </li>
                        <li>
                            <input type="number" name="count" min="0" value="0" id="countProxyBuy"
                                   data-sale-count-two="{{ $settingModel->proxy_two_sel_count }}"
                                   data-sale-count-five="{{ $settingModel->proxy_three_sel_count }}"
                                   placeholder="Количество" required>
                        </li>
                    </ul>
                </div>
                <div class="header-bottom">
                    <ul>
                        <li><a href="#">Есть промокод?</a></li>
                        <li class="header_price">
                            <p class="title topform-total">0 RUB</p>
                            <span><span class="topform-price">0 RUB</span>/шт</span>
                        </li>
                        <li>
                            @auth
                            <button class="main_btn topform-button" data-modal="modal-order">
                                Купить прокси
                            </button>
                            @else
                            <a href="/login" class="main_btn topform-button" data-modal="modal-order">
                                Купить прокси
                            </a>
                            @endauth

                        </li>
                        <!-- <li></li>
                        <li></li> -->
                    </ul>
                </div>
            </form>
        </div>
    </div> --}}

    {{-- <!-- Приемущества --> --}}
    {{-- <div class="info-section">
        <h2>Наши преимущества</h2>
        <div class="wraper-info-section">

            <div class="card-info">
                <div class="title-card">
                    <span></span>
                    <img src="{{ asset('') }}assets/img/countries_and_prices_left_item_img1.svg" alt="">
                    <span></span>
                </div>
                <div class="card-content">
                    <h3>Скорость</h3>
                    <p>Интернет канал 1 Гбит/c</p>
                </div>
            </div>
            <div class="card-info">
                <div class="title-card">
                    <span></span>
                    <img src="{{ asset('') }}assets/img/countries_and_prices_left_item_img2.svg" alt="">
                    <span></span>
                </div>
                <div class="card-content">
                    <h3>Подсети</h3>
                    <p>Прокси выдаются более чем из 800 разных подсетей и 400 сетей</p>
                </div>
            </div>
            <div class="card-info">
                <div class="title-card">
                    <span></span>
                    <img src="{{ asset('') }}assets/img/countries_and_prices_left_item_img3.svg" alt="">
                    <span></span>
                </div>
                <div class="card-content">
                    <h3>Поддержка</h3>
                    <p>Круглосуточная техническая поддержка 24/7/365</p>
                </div>
            </div>
            <div class="card-info">
                <div class="title-card">
                    <span></span>
                    <img src="{{ asset('') }}assets/img/countries_and_prices_left_item_img4.svg" alt="">
                    <span></span>
                </div>
                <div class="card-content">
                    <h3>Возврат или замена</h3>
                    <p>Замена прокси или возврат средств в течение суток после выдачи</p>
                </div>
            </div>
            <div class="card-info">
                <div class="title-card">
                    <span></span>
                    <img src="{{ asset('') }}assets/img/countries_and_prices_left_item_img1.svg" alt="">
                    <span></span>
                </div>
                <div class="card-content">
                    <h3>Низкие цены</h3>
                    <p>У нас одни из самых низких цен на рынке</p>
                </div>
            </div>
            <div class="card-info">
                <div class="title-card">
                    <span></span>
                    <img src="{{ asset('') }}assets/img/countries_and_prices_left_item_img2.svg" alt="">
                    <span></span>
                </div>
                <div class="card-content">
                    <h3>Все автоматизировано</h3>
                    <p>Прокси активируются сразу же после оплаты</p>
                </div>
            </div>
            <div class="card-info">
                <div class="title-card">
                    <span></span>
                    <img src="{{ asset('') }}assets/img/countries_and_prices_left_item_img3.svg" alt="">
                    <span></span>
                </div>
                <div class="card-content">
                    <h3>HTTP/SOCKS5</h3>
                    <p>Прокси переключаются с HTTPS на SOCKS5 и обратно в личном кабинете</p>
                </div>
            </div>
            <div class="card-info">
                <div class="title-card">
                    <span></span>
                    <img src="{{ asset('') }}assets/img/countries_and_prices_left_item_img4.svg" alt="">
                    <span></span>
                </div>
                <div class="card-content">
                    <h3>В одни руки</h3>
                    <p>Продажа прокси ведется исключительно в одни руки</p>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- <!-- Отзывы --> --}}
    {{-- <div class="reviews-section">
        <h2>Отзывы наших клиентов</h2>
        <div class="wraper-reviews-section">
            @php
                $i = 0;
            @endphp
            @foreach ($reviewsSite as $reviews)
                @if ($i < 4)
                    <div class="card-reviews">
                        <div class="img-reviews">
                            <img src="{{ $reviews->avatar }}" alt="">
                        </div>
                        <div class="content-reviews">
                            <p>{!! $reviews->description !!}</p>
                            <div class="signature">
                                <span>{{ $reviews->name }}</span>
                                <a href="{{ $reviews->link }}">{{ $reviews->linkName }}</a>
                            </div>
                        </div>
                    </div>
                @else
                @endif
                @php
                    $i++
                @endphp
            @endforeach
        </div>
        <div class="center">
            <a class="main_btn" href='{{route('reviews')}}'>
                Посмотреть все
            </a>
        </div>
    </div> --}}
@endsection

@section('modal')
    <div class="modal notifications">
        <div class="background">
            <div class="body">
                <div class="textWrap">
                    <i class="fa fa-exclamation-triangle"></i>
                    <div class="title"></div>
                    <i class="fa fa-exclamation-triangle"></i>
                </div>
                <div class="massage"></div>
                <div></div>
                <div class="buttonFormWrap">
                    <a class="main_btn closeModal dopButt btn button" href="/support">
                        @lang('phrases.Обратится в тех поддержку')
                    </a>
                    <a class="close closeModal btn button modal__cross js-modal-close" href="#">
                        @lang('phrases.Окей')
                    </a>
                </div>
            </div>
        </div>
    </div>


    <div class="modal nomail">
        <div class="background">
            <div class="body">
                <div class="textWrap">
                    <i class="fa fa-exclamation-triangle"></i>
                    <div class="title"> @lang('phrases.Для продолжения необходимо ввести Email и повторить попытку')</div>
                    <i class="fa fa-exclamation-triangle"></i>
                </div>
                <div class="massage"></div>
                <div></div>
                <div class="buttonFormWrap">
                    <form action="" id="emailForm">
                        <input type="email" name="email" id="email">
                        <button type="submit"> @lang('phrases.Сохранить')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal extend">
        <div class="background">
            <div class="body">
                {!! Form::open(['method' => 'POST', 'route' => 'controlExtend']) !!}
                <div class="textWrap">
                    <div class="title"> @lang('phrases.Продлить прокси') <span class="numberProxyText"></span></div>
                    <div class="massage">
                        @if ($tariffSettings->type_tariff)
                            <select name="idt">
                                @foreach ($tariffSettings->tariff as $key => $tariff)
                                    @php
                                        $countryKey = array_search($tariffSettings->default_country, $tariff['country']);
                                    @endphp
                                    <option value="{{ $key }}" class="proxy__application-choice"
                                            {{ $key == 1 ? 'selected' : '' }}>
                                        {{ $tariff['period'] }}  @lang('phrases.дней') -
                                        @if ($tariffSettings->type_proxy == 'general')
                                            {{ $tariff['general_price'][$countryKey] }} $
                                        @elseif($tariffSettings->type_proxy == 'private')
                                            {{ $tariff['private_price'][$countryKey] }} $
                                        @else
                                            {{ $tariff['general_price'][$countryKey] }} $
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <select name="month">
                                @for ($monthI = 5; $monthI <= $tariffSettings->max_days; $monthI++)
                                    @if ($monthI == 5)
                                        <option value="{{ $monthI }}" data-sale="">{{ $monthI }}
                                            @lang('phrases.дней')
                                        </option>
                                    @elseif($monthI % 10 == 0 and $monthI <= 30)
                                        <option value="{{ $monthI }}" data-sale="" data-sale-count="">
                                            {{ $monthI }}
                                            @lang('phrases.дней')
                                        </option>
                                    @elseif($monthI % 30 == 0)
                                        <option value="{{ $monthI }}" data-sale="" data-sale-count="">
                                            {{ $monthI }}
                                            @lang('phrases.дней')
                                        </option>
                                    @endif
                                @endfor
                            </select>
                        @endif

                        <input type="hidden" name="id">
                    </div>
                </div>
                <div></div>
                <div class="buttonFormWrap">
                    <button class="main_btn">
                        @lang('phrases.Продлить')
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        const costd = @JSON($tariffSettings->type_tariff ? $tariffSettings['tariff'] : $tariffSettings['days_tariff']);
        const proxy_discount = {!! $tariffSettings['proxy_discount'] !!};
        const days_discount = {!! $tariffSettings['days_discount'] !!};
        const proxy_pairs_discount = {!! $tariffSettings['proxy_pairs_discount'] !!};
        const promocode_discount = {!! $tariffSettings['promocode_discount'] !!};
        const tariffs = {!! $tariffSettings->type_tariff !!};
        const dom = "{{ url('/') }}/";
    </script>
    <script src="{{ asset('assets/js/prox.js') }}{{ '?' . time() }}"></script>

    {{-- <script>
        var mounthProxyBuy = document.getElementById("monthProxyBuyOld")
        var typeProxyBuy = document.getElementById("typeProxyBuyOld")
        var countProxyBuy = document.getElementById("countProxyBuy")
        var totalPrice = document.querySelector('.topform-total');
        var onePrice = document.querySelector('.topform-price');

        function calculatorFunc() {
            let thisQ = +countProxyBuy.value,
                month = +mounthProxyBuy.value,
                baseQ = +typeProxyBuy.options[typeProxyBuy.selectedIndex].dataset.price,
                baseSaleCountTwo = +countProxyBuy.dataset.saleCountTwo,
                baseSaleCountFive = +countProxyBuy.dataset.saleCountFive,
                baseSaleMounth = +mounthProxyBuy.options[mounthProxyBuy.selectedIndex].dataset.sale,
                saleCount = thisQ >= 5 ? baseSaleCountFive : (thisQ >= 2 ? baseSaleCountTwo : 0);
            saleAllPricent = +baseSaleMounth + saleCount;

            let total = baseQ * thisQ * month;
            total = saleAllPricent != 0 ? total - (total * (saleAllPricent / 100)) : total;

            // if(thisQ == 1){
            // 	total = baseQ * month;
            // }else{
            // 	if (thisQ == 2) {
            // 		let numb_percent = total / 100 * baseSale;
            // 		total = total - numb_percent;
            // 	}else if (thisQ >= 3) {
            // 		let numb_percent = total / 100 * baseSale;
            // 		total = total - numb_percent;
            // 	}
            // 	if(month == 2){
            // 		let numb_percent_month = total / 100 * baseSale2;
            // 		total = total - numb_percent_month;
            // 	}else if (month >= 3) {
            // 		let numb_percent_month = total / 100 * baseSale2;
            // 		total = total - numb_percent_month;
            // 	}
            // }
            // console.log("Total: " + total)
            // total = total > 0 ? total : 0
            totalOne = thisQ > 1 ? total / thisQ : total

            totalPrice.innerText = Math.round(total) + " $"
            onePrice.innerText = Math.round(totalOne) + " $"
        }

        mounthProxyBuy.addEventListener('change', function(e) {
            // console.log("Changed to: " + e.target.value)
            // console.log("Data to: " + e.target.options[event.target.selectedIndex].dataset.sale)
            // console.log("Data to saleCount: " + e.target.options[event.target.selectedIndex].dataset.saleCount)
            calculatorFunc()
            // alert('Сменился месяц')
        });
        typeProxyBuy.addEventListener('change', function(e) {
            // console.log("Changed to: " + e.target.value)
            calculatorFunc()
        });
        countProxyBuy.addEventListener('input', function(e) {
            // console.log("Changed input: " + e.target.value)
            calculatorFunc()
        });
    </script> --}}
    {{-- <script>
        document.querySelector('a[href="#"]').addEventListener('click', function(e) {
            e.preventDefault();
        });
        // select-1 – id элемента
        const select1 = new ItcCustomSelect('#countryProxyBuy');
        const select2 = new ItcCustomSelect('#monthProxyBuy');
        const select3 = new ItcCustomSelect('#typeProxyBuy');
        document.querySelector('#countryProxyBuy').addEventListener('itc.select.change', (e) => {
            const btn = e.target.querySelector('.itc-select__toggle');
            // выбранное значение
            console.log(`Выбранное значение: ${btn.value}`);
            var selectElement = document.getElementById("countryProxyBuyOld");
            selectElement.value = btn.value;

        });
        document.querySelector('#monthProxyBuy').addEventListener('itc.select.change', (e) => {
            const btn = e.target.querySelector('.itc-select__toggle');
            // выбранное значение
            console.log(`Выбранное значение: ${btn.value}`);
            var selectElement = document.getElementById("monthProxyBuyOld");
            selectElement.value = btn.value; // получаем ссылку на select по id
            // Создаем событие change
            var changeEvent = new Event('change');

            // Вызываем созданное событие на элементе select
            selectElement.dispatchEvent(changeEvent);
            // const select = document.querySelector('select[id="monthProxyBuyOld"]');

            // // выбираем нужный option по значению кнопки
            // const btnValue = btn.value; // здесь должно быть значение кнопки
            // const option = select.querySelector(`option[value="${btnValue}"]`);
            // // устанавливаем выбранный option в select
            // option.selected = true;
            // alert('Выбранный месяц: ' + document.querySelector('select[id="monthProxyBuyOld"]').value);
        });

        document.querySelector('#typeProxyBuy').addEventListener('itc.select.change', (e) => {
            const btn = e.target.querySelector('.itc-select__toggle');
            // выбранное значение
            console.log(`Выбранное значение: ${btn.value}`);
            var selectElement = document.getElementById("typeProxyBuyOld");
            selectElement.value = btn.value;

        });
    </script> --}}
@endsection
