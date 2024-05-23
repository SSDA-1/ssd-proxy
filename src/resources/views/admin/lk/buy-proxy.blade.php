@extends('proxies::templates.' . (new Ssda1\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory() . '.layouts.app')

@section('style')
    <link rel="stylesheet" href="/vendor/ssda-1/proxies/assets/css/lk.css{{ '?' . time() }}">
@endsection
@section('body-class')
personal-area
@endsection

@section('content')
    {{-- @dd($tariffSettings['proxy_discount']) --}}
    <div class="lk-block">
        @include('proxies::admin.lk.menu')
        <div class="lk-content">
            <!-- Тарифы блок -->
            <section class="tariff center">
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
                                        <span class="days">{{ $tariff['period'] }}</span> @lang('proxies::phrases.дней') -
                                        @php
                                            $countryKey = array_search($tariffSettings->default_country, $tariff['country']);
                                        @endphp
                                        @if ($tariffSettings->type_proxy == 'general')
                                            <span
                                                    class="cost cost_tariff cost_day">{{ $tariff['general_price'][$countryKey] }}</span>$
                                        @elseif($tariffSettings->type_proxy == 'private')
                                            <span
                                                    class="cost cost_tariff cost_day">{{ $tariff['private_price'][$countryKey] }}</span>$ 
                                        @else
                                            <span
                                                    class="cost cost_tariff cost_day">{{ $tariff['general_price'][$countryKey] }}</span>$
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
                                                            <div class="proxy__application-text">@lang('proxies::phrases.Гео'):</div>
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
                                                                    <img src="/vendor/ssda-1/proxies/assets/img/kz.svg" alt="flag">
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="proxy__application-li">
                                                            <div class="proxy__application-text">@lang('proxies::phrases.Гео'):</div>
                                                            <div class="proxy__application-countries btn-submenu">
                                                                <input type="hidden" name="country"
                                                                       value="{{ $tariffSettings->default_country }}"
                                                                       class="countryInpup country_day" hidden>
                                                                <div class="proxy__application-country">
                                                                    {{ $tariffSettings->default_country }}
                                                                </div>
                                                                <div class="proxy__application-countryFlag">
                                                                    <img src="/vendor/ssda-1/proxies/assets/img/kz.svg" alt="flag">
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
                                                            <div class="proxy__application-text">@lang('proxies::phrases.Тип'):</div>
                                                            <div class="proxy__application-count type_proxy">
                                                                <div class="proxy__application-country">
                                                                    <select name="type" class="type__proxy type__proxy__day">
                                                                        <option value="general">@lang('proxies::phrases.Общие')</option>
                                                                        <option value="private">@lang('proxies::phrases.Приватные')</option>
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
                                                        <div class="proxy__application-text">@lang('proxies::phrases.Количество'):</div>
                                                        <div class="proxy__application-count">
                                                            <div class="proxy__decrease"></div>
                                                            <input class="proxy__application-quantity q_d" type="text"
                                                                   name="count" min="1" value="1">
                                                            <div class="proxy__add"></div>
                                                        </div>
                                                    </li>

                                                    {{-- Промокод --}}
                                                    <li class="proxy__application-li">
                                                        <div class="proxy__application-text">@lang('proxies::phrases.Промокод'):</div>
                                                        <div class="proxy__application-count">
                                                            <input class="promocode" type="text" name="promo"
                                                                   placeholder="@lang('proxies::phrases.Промокод')" data-id="{{ $key }}">
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="proxy__application-btn">
                                                @if (Str::startsWith(Auth::user()->email, '@'))
                                                    <div class="btn nomail" data-modal="nomail" style="width: 100%;">
                                                        @lang('proxies::phrases.Купить прокси')</div>
                                                {{--@elseif(Auth::user()->telegram_chat_id === null)
                                                    <div class="btn nomail" data-modal="nomail" style="width: 100%;">
                                                        @lang('proxies::phrases.Купить прокси')</div>--}}
                                                @else
                                                    <button type="submit" class="btn"
                                                            style="width: 100%;">@lang('proxies::phrases.Купить прокси')</button>
                                                @endif
                                            </div>
                                        </div>
                                    </form>
                                    <div class="sales__block"></div>
                                </figure>
                            @endif
                        @endforeach
                    </div>
                @else
                    <form action="{{ route('buyFetch') }}" class="tariff__blocks tariff__block__days tariff__block"
                        data-tariff="1">
                        {{-- <div class="wrap-cost-days tariff__block" data-tariff="1"> --}}
                        <div class="wrap-cost-days ">
                            <div class="sum">@lang('proxies::phrases.Стоимость') <span class="cost cost_day"></span> $ / <span
                                    class="days"></span>
                                @lang('proxies::phrases.дней')</div>
                        </div>
                        <div class="wrap-days-tariff">
                            {{-- Срок --}}
                            <select name="month" id="" class="proxy__application-li amount_days">
                                @for ($monthI = 5; $monthI <= $tariffSettings->max_days; $monthI++)
                                    @if ($monthI == 5)
                                        <option value="{{ $monthI }}" data-sale="">{{ $monthI }}
                                            @lang('proxies::phrases.дней')
                                        </option>
                                    @elseif($monthI % 10 == 0 and $monthI <= 30)
                                        <option value="{{ $monthI }}" data-sale="" data-sale-count="">
                                            {{ $monthI }}
                                            @lang('proxies::phrases.дней')
                                        </option>
                                    @elseif($monthI % 30 == 0)
                                        <option value="{{ $monthI }}" data-sale="" data-sale-count="">
                                            {{ $monthI }}
                                            @lang('proxies::phrases.дней')
                                        </option>
                                    @endif
                                @endfor
                            </select>
                            {{-- Количество --}}
                            <div class="proxy__application-li">
                                <div class="proxy__application-text">@lang('proxies::phrases.Количество'):</div>
                                <div class="proxy__application-count">
                                    <div class="proxy__decrease"></div>
                                    <input class="proxy__application-quantity q_d" type="text" name="count"
                                        min="1" value="1">
                                    <div class="proxy__add"></div>
                                </div>
                            </div>
                            {{-- Тип --}}
                            @if ($tariffSettings->type_proxy == 'all')
                                <select name="type" class="type__proxy__day proxy__application-li">
                                    <option value="general">@lang('proxies::phrases.Общие')</option>
                                    <option value="private">@lang('proxies::phrases.Приватные')</option>
                                </select>
                            @elseif($tariffSettings->type_proxy == 'general')
                                <input type="hidden" name="type" value="general" class="type__proxy__day" hidden>
                            @elseif($tariffSettings->type_proxy == 'private')
                                <input type="hidden" name="type" value="private" class="type__proxy__day" hidden>
                            @endif
                            {{-- Страна --}}
                            <select name="country" id="" class="country_day proxy__application-li">\
                                @if(isset($tariffSettings['days_tariff']))
                                    @foreach ($tariffSettings['days_tariff'] as $key2 => $country)
                                        <option value="{{ $country['country'] }}">
                                            {{ $country['country'] }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            {{-- Промокод --}}
                            <input class="promocode proxy__application-li" type="text" name="promo"
                                placeholder="@lang('proxies::phrases.Промокод')Промокод" data-id="1">



                            <div class="proxy__application-btn ">
                                @if (Str::startsWith(Auth::user()->email, '@'))
                                    <div class="btn nomail" data-modal="nomail">@lang('proxies::phrases.Купить прокси')</div>
                                @else
                                    <button type="submit" class="btn">@lang('proxies::phrases.Купить прокси')</button>
                                @endif
                            </div>
                        </div>
                        <div class="sales__block days_s"></div>
                    </form>
                @endif
            </section>

            @if ($tariffSettings->type_proxy == 'general')
                @php($i = 0)
                @if ($countProxyGeneralDiscounts->count() >= 1)
                    <div class="info">
                        @lang('proxies::phrases.Акция при покупке от')
                        @foreach ($countProxyGeneralDiscounts as $key => $item)
                            @if ($i < 2)
                                {{ $item->proxy }} @lang('proxies::phrases.портов') @lang('proxies::phrases.скидка') {{ $item->discount }}%
                                @if ($key === 0 && $countProxyGeneralDiscounts->count() >= 2)
                                    @lang('proxies::phrases.от')
                                @endif
                            @endif
                            @php($i++)
                        @endforeach
                    </div>
                @endif

                @php($i = 0)
                @if ($countDaysGeneralDiscounts->count() >= 1)
                    <div class="info">
                        @lang('proxies::phrases.Акция при покупке от')
                        @foreach ($countDaysGeneralDiscounts as $key => $item)
                            @if ($i < 2 && $item->type == 'general')
                                {{ $item->days }} @lang('proxies::phrases.дней') @lang('proxies::phrases.скидка') {{ $item->discount }}%
                                @if ($key === 0 && $countDaysGeneralDiscounts->count() >= 2)
                                    @lang('proxies::phrases.от')
                                @endif
                            @endif
                            @php($i++)
                        @endforeach
                    </div>
                @endif
            @elseif($tariffSettings->type_proxy == 'private')
                @php($i = 0)
                @if ($countProxyGeneralDiscounts->count() >= 1)
                    <div class="info">
                        @lang('proxies::phrases.Акция при покупке от')
                        @foreach ($countProxyPrivateDiscounts as $key => $item)
                            @if ($i < 2)
                                {{ $item->proxy }} @lang('proxies::phrases.портов') @lang('proxies::phrases.скидка') {{ $item->discount }}%
                                @if ($key === 0 && $countProxyPrivateDiscounts->count() >= 2)
                                    @lang('proxies::phrases.от')
                                @endif
                            @endif
                            @php($i++)
                        @endforeach
                    </div>
                @endif

                @php($i = 0)
                @if ($countDaysPrivateDiscounts->count() >= 1)
                    <div class="info">
                        @lang('proxies::phrases.Акция при покупке от')
                        @foreach ($countDaysPrivateDiscounts as $key => $item)
                            @if ($i < 2 && $item->type == 'private')
                                {{ $item->days }} @lang('proxies::phrases.дней') @lang('proxies::phrases.скидка') {{ $item->discount }}%
                                @if ($key === 0 && $countDaysPrivateDiscounts->count() >= 2)
                                    @lang('proxies::phrases.от')
                                @endif
                            @endif
                            @php($i++)
                        @endforeach
                    </div>
                @endif
            @elseif($tariffSettings->type_proxy == 'all')
                @php($i = 0)
                @if ($countProxyAllDiscounts->count() >= 1)
                    <div class="info">
                        @lang('proxies::phrases.Акция при покупке от')
                        @foreach ($countProxyAllDiscounts as $key => $item)
                            @if ($i < 2)
                                {{ $item->proxy }} @lang('proxies::phrases.скидка') @lang('proxies::phrases.скидка') {{ $item->discount }}%
                                @if ($key === 0 && $countProxyAllDiscounts->count() >= 2)
                                    @lang('proxies::phrases.от')
                                @endif
                            @endif
                            @php($i++)
                        @endforeach
                    </div>
                @endif

                @php($i = 0)
                @if ($countDaysAllDiscounts->count() >= 1)
                    <div class="info">
                        @lang('proxies::phrases.Акция при покупке от')
                        @foreach ($countDaysAllDiscounts as $key => $item)
                            @if ($i < 2 && $item->type == 'all')
                                {{ $item->days }} @lang('proxies::phrases.дней') @lang('proxies::phrases.скидка') {{ $item->discount }}%
                                @if ($key === 0 && $countDaysAllDiscounts->count() >= 2)
                                    @lang('proxies::phrases.от')
                                @endif
                            @endif
                            @php($i++)
                        @endforeach
                    </div>
                @endif
            @endif


        </div>
    </div>
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
                    <a class="btn" href="/help" style="width: 100%;">
                        @lang('proxies::phrases.Обратится в тех поддержку')
                    </a>
                    <a class="btn" id="myProxy" href="/control-panel" style="width: 100%;">
                        @lang('proxies::phrases.Мои прокси')
                    </a>
                </div>
            </div>
        </div>
    </div>


    <div class="modal nomail" data-modal="nomail">
        <div class="background">
            <div class="body">
                <div class="textWrap" style="flex-direction: row">
                    {{-- <i class="fa fa-check-circle"></i> --}}
                    <i class="fa fa-exclamation-triangle"></i>
                    @if (Str::startsWith(Auth::user()->email, '@') /*&& Auth::user()->telegram_chat_id === Null*/)
                    <div class="title">@lang('proxies::phrases.Для продолжения необходимо ввести Email и привязать свой телеграмм, затем повторить попытку')</div>
                    @elseif (Str::startsWith(Auth::user()->email, '@'))
                    <div class="title">@lang('proxies::phrases.Для продолжения необходимо ввести Email и повторить попытку')</div>
                    {{--@elseif (Auth::user()->telegram_chat_id === Null)
                    <div class="title">@lang('proxies::phrases.Для продолжения необходимо привязать свой телеграмм и повторить попытку')</div>
                        --}}
                    @endif
                    <i class="fa fa-exclamation-triangle"></i>
                </div>
                <div class="massage"></div>
                <div></div>
                <div class="buttonFormWrap" style="margin-top: 30px">
                    <form action="" id="emailForm" class="form-email">
                        {{--@if (Auth::user()->telegram_chat_id === Null)
                            <span style="font-size: 14px; color: #fff;">@lang('proxies::phrases.для получения id chata перейдите к боту') <a
                                    style="font-size: 14px; text-decoration: underline; color: #fff;" href="{{ $tgData->telegram_link }}"
                                    target="_blanck">{{ $tgData->telegram_link }}</a> @lang('proxies::phrases.и напишите /start либо нажмите кнопку')</span>
                            <input type="number" name="telegram_chat_id" id="telegram" class="input-lk"
                                placeholder="0000000" required>
                        @endif--}}
                        
                        @if (Str::startsWith(Auth::user()->email, '@'))
                            <input type="email" name="email" id="email" class="input-lk" placeholder="Email" required>
                        @endif
                        <button type="submit" class="btn">@lang('proxies::phrases.Сохранить')</button>
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
                    <div class="title">@lang('proxies::phrases.Продлить прокси ')<span class="numberProxyText"></span></div>
                    <div class="massage">
                        <select name="month">
                            @if(!empty($settingModel->proxy_mounth))
                                @for ($monthI = 7; $monthI <= $settingModel->proxy_mounth; $monthI++)
                                    @if ($monthI == 7)
                                        <option value="{{ $monthI }}" data-sale="">{{ $monthI }}
                                            @lang('proxies::phrases.дней')
                                        </option>
                                    @elseif($monthI % 20 == 0 and $monthI <= 30)
                                        <option value="{{ $monthI }}"
                                            data-sale="{{ $settingModel->proxy_two_sel_period }}"
                                            data-sale-count="{{ $settingModel->proxy_two_sel_count }}">{{ $monthI }}
                                            @lang('proxies::phrases.дней')
                                        </option>
                                    @elseif($monthI % 30 == 0)
                                        <option value="{{ $monthI }}"
                                            data-sale="{{ $settingModel->proxy_three_sel_period }}"
                                            data-sale-count="{{ $settingModel->proxy_three_sel_count }}">
                                            {{ $monthI }}
                                            @lang('proxies::phrases.дней')
                                        </option>
                                    @endif
                                @endfor
                            @endif
                        </select>
                        <input type="hidden" name="id">
                    </div>
                </div>
                <div></div>
                <div class="buttonFormWrap">
                    <button class="main_btn">
                        @lang('proxies::phrases.Продлить')
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
    <script src="/vendor/ssda-1/proxies/assets/js/prox.js{{ '?' . time() }}"></script>
@endsection
