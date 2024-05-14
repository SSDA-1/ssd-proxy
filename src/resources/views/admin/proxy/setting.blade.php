@extends('admin.app')
@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Основные настройки прокси')</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('proxy.index') }}"><i class="bx bx-left-arrow-alt icon"></i>
                @lang('proxies::phrases.Назад')</a>
        </div>
    </div>

    @if (count($errors) > 0)
        <div class="alert alert-danger block-background">
            <strong>@lang('proxies::phrases.Упс')!</strong> @lang('proxies::phrases.Были некоторые проблемы с вашим вводом').<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="settings-tabs">
        <div class="tabs__nav background">
            <button class="tabs__btn tabs__btn_active first">@lang('proxies::phrases.Сервера')</button>
            <button class="tabs__btn">@lang('proxies::phrases.Настройки')</button>
            <button class="tabs__btn">@lang('proxies::phrases.Система покупки')</button>
            <button class="tabs__btn last">@lang('proxies::phrases.Акции и скидки')</button>
        </div>
        <div class="tabs__content block-background">
            {{-- Сервера --}}
            <div class="tabs__pane tabs__pane_show flex-block">
                <div class="footer-block not-radius">
                    <div class="row title-block proxy">
                        <h2>@lang('proxies::phrases.Список Серверов')</h2>
                        <a class="btn btn-success" href="{{ route('servers.create') }}"><span>+</span></a>
                    </div>
                </div>
                <table class="table table-bordered padding-20" style="padding-top: 0">
                    <thead>
                        <tr class="tr-name">
                            <th>ID</th>
                            <th>@lang('proxies::phrases.Название')</th>
                            <th>@lang('proxies::phrases.Страна')</th>
                            <th>@lang('proxies::phrases.Адрес')</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($servers as $server)
                            <tr id="server_{{ $server->id }}">
                                <td>{{ $server->id }}</td>
                                @php
                                    $countries = Countries::getList('en', 'php', 'cldr');
                                    $regionCode = $server->country;
                                    $filteredCountries = array_filter($countries, function ($country) use ($regionCode) {
                                        return isset($country['name']) && $country['name'] == $regionCode;
                                    });
                                    $countrySearch = reset($filteredCountries);
                                    // print_r(reset($filteredCountries))
                                @endphp
                                <td>{{ $server->name }}</td>
                                <td>{{ $countrySearch['citizenship'] }}</td>
                                <td>{{ $server->data['url'] }}</td>
                                {{-- <td>{{ $port->active == 1 ? 'Активный' : 'Не активный' }}</td>
                                <td>{{ $port->type }}</td>
                                <td>{{ $port->net_mode }}</td>
                                <td>{{ $port->is_osfp == 0 ? 'Отпечаток не включен' : $port->osfp }}</td>
                                <td>{{ $port->reconnect_type }}</td>
                                <td>{{ $port->reconnect_interval }} сек</td>
                                <td>{{ $port->type_pay == 'private' ? 'Приватный' : 'Общий' }}</td>
                                <td>{{ $port->max_users }} / {{ $port->proxycount }}</td> --}}
                                <td class="dayst">
                                    <a class="btn btn-action" href="{{ route('servers.edit', $server->id) }}"><i
                                            class="fa-regular fa-pen-to-square"></i></a>
                                    {{-- <button type="submit" class="btn btn-danger"><i class="fa-solid fa-bullseye"></i></button> --}}
                                    <button type="submit" data-title="@lang('proxies::phrases.Вы точно хотите удалить Порт с сайта')? <br> Ps: @lang('proxies::phrases.В системе Кракен его нужно удалить вручную')"
                                        data-fetch="yes" data-action="{{ route('servers.destroy', $server->id) }}"
                                        data-modal="del" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="absent">@lang('proxies::phrases.Записи отсутствуют')</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Настройки --}}
            <div class="tabs__pane flex-block">
            </div>

            {{-- Тарифы --}}
            <div class="tabs__pane flex-block">
                <div class="footer-block not-radius">
                    <div class="row">
                        <div class="field">
                            <div class="wrap-input title">
                                <label for="highload1" class="title-field">@lang('proxies::phrases.Тарифная система'):</label>
                                <input type="checkbox" id="highload1" name="tariff_system" class="tariff-system"
                                    {{ $tariffSetting->type_tariff ? 'checked' : '' }}>
                                <label for="highload1" data-onlabel="" data-offlabel="" class="lb1"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="field">
                            <label for="type-proxy" class="title-field"
                                style="text-align: left;">@lang('proxies::phrases.Типы прокси'):</label>
                            <select name="type-proxy" id="type-proxy" class="input-text">
                                <option value="general" @if ($tariffSetting->type_proxy == 'general') selected @endif>@lang('proxies::phrases.Общие')
                                </option>
                                <option value="private" @if ($tariffSetting->type_proxy == 'private') selected @endif>@lang('proxies::phrases.Приватные')
                                </option>
                                <option value="all" @if ($tariffSetting->type_proxy == 'all') selected @endif>@lang('proxies::phrases.Общие и Приватные')
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="field">
                            <label for="country-default" class="title-field"
                                style="text-align: left;">@lang('proxies::phrases.Страна по умолчанию'):</label>
                            <select name="country-default" id="country-default" class="input-text">
                                @foreach ($tariffCountries as $tariffCountry)
                                    <option value="{{ $tariffCountry }}" selected>{{$tariffCountry}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ТАРИФЫ --}}
                <form action="{{ route('tariffsettings.store') }}" method="POST" class="rates-wrap"
                    style="display: none;">
                    @csrf
                    <input type="text" name="globalType" value="{{ $tariffSetting->type_proxy }}" hidden="hidden"
                        class="globalType">
                    {{-- СЮДА ПОДСТАВЬ ЗНАЧЕНИЕ ИЗ БД!!! --}}
                    <input type="text" name="globalTariff" value="{{ $tariffSetting->type_tariff }}" hidden="hidden"
                        class="globalTariff">
                    {{-- СЮДА ПОДСТАВЬ ЗНАЧЕНИЕ ИЗ БД!!! --}}
                    <input type="text" name="country-default" value="{{ $tariffSetting->default_country }}"
                        hidden="" class="country-default">
                    {{-- СЮДА ПОДСТАВЬ ЗНАЧЕНИЕ ИЗ БД!!! --}}
                    <div class="row padding-20">
                        <div class="field">
                            <h3>@lang('proxies::phrases.Настройки тарифов')</h3>
                        </div>
                        <div class="field">
                            <select name="language" id="language" class="input-text">
                                <option value="ru" selected>Русский</option>
                                <option value="en">English</option>
                            </select>
                        </div>
                        <div class="field">
                            <div class="btn btn-primary addRates" style="height: 45px">@lang('proxies::phrases.Добавить тариф')</div>
                        </div>
                    </div>
                    <div class="row rates padding-20">
                        @foreach ($tariffSetting->tariff as $key => $tariff)
                            <div class="field rate" data-id="{{ $key }}" data-lang="{{ $tariff['lang'] }}">
                                <input type="hidden" name="id" value="{{ $key }}">
                                <input type="hidden" name="lang_{{ $key }}" value="{{ $tariff['lang'] }}">
                                <input type="text" name="name_{{ $key }}" value="{{ $tariff['name'] }}"
                                    class="input-text" placeholder="@lang('proxies::phrases.Название')">
                                <div class="properties">
                                    @foreach ($tariff['properties'] as $key2 => $property)
                                        @if ($key2 != 0)
                                            <div class="wrap-input numberFormat">
                                                <span class="delProperty"><i class="fa-solid fa-trash"></i></span>
                                                <input type="text" name="properties_{{ $key }}[]"
                                                    value="{{ $property }}" placeholder="@lang('proxies::phrases.Свойство')"
                                                    class="input-text">
                                            </div>
                                        @else
                                            <input type="text" name="properties_{{ $key }}[]"
                                                value="{{ $property }}" class="input-text"
                                                placeholder="@lang('proxies::phrases.Свойство')">
                                        @endif
                                    @endforeach
                                    <div class="addProperty">+ @lang('proxies::phrases.Добавить свойство')</div>
                                </div>
                                <input type="number" name="period_{{ $key }}" value="{{ $tariff['period'] }}"
                                    min="1" class="input-text" placeholder="@lang('proxies::phrases.Срок')">
                                <div class="wrap-countries" data-id="{{ $key }}">
                                    @foreach ($tariff['country'] as $key2 => $country)
                                        <div class="countries">
                                            <select class="input-text" name="country_{{ $key }}[]">
                                                @foreach ($tariffCountries as $tariffCountry)
                                                    <option value="{{ $tariffCountry }}" selected>{{$tariffCountry}}</option>
                                                @endforeach
                                            </select>
                                            <input type="number" name="cost-general_{{ $key }}[]"
                                                value="{{ $tariff['general_price'][$key2] }}" min="1"
                                                class="input-text" placeholder="@lang('proxies::phrases.Цена') @lang('proxies::phrases.Общие')">
                                            <input type="number" name="cost-private_{{ $key }}[]"
                                                value="{{ $tariff['private_price'][$key2] }}" min="1"
                                                class="input-text" placeholder="@lang('proxies::phrases.Цена') @lang('proxies::phrases.Приватные')">
                                            @if ($key2 != 0)
                                                <div class="delCountry">@lang('proxies::phrases.Удалить страну')</div>
                                            @else
                                                <div class="addCountry">+ @lang('proxies::phrases.Добавить страну')</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                @if ($key != 1)
                                    <div class="delRate btn btn-primary">@lang('proxies::phrases.Удалить тариф')</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="footer-block not-radius">
                        <button type="submit" class="btn btn-primary">@lang('proxies::phrases.Сохранить')</button>
                    </div>
                </form>

                {{-- ПО ДНЯМ --}}
                <form action="{{ route('tariffsettings.store') }}" method="POST" class="payment-day"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="globalType" value="{{ $tariffSetting->type_proxy }}" hidden="hidden"
                        class="globalType">
                    <input type="text" name="globalTariff" value="{{ $tariffSetting->type_tariff }}" hidden="hidden"
                        class="globalTariff">
                    <input type="text" name="country-default" value="{{ $tariffSetting->default_country }}"
                        hidden="" class="country-default">
                    <div class="row padding-20">
                        <div class="field">
                            <div class="title-field">@lang('proxies::phrases.Дней') (@lang('proxies::phrases.Максимальный срок')):</div>
                            <input type="number" class="input-text" placeholder="30" name="max_days"
                                value="{{ $tariffSetting->max_days }}">
                        </div>
                        <div class="field wrap-btn">
                            <div class="btn btn-primary addPrices">@lang('proxies::phrases.Добавить тариф')</div>
                        </div>
                    </div>

                    <div class="row group padding-20 prices">
                        @foreach ($tariffSetting->days_tariff as $key => $day)
                            <div class="row price">
                                <div class="field type-proxy" data-type="general"
                                    @if (!in_array($tariffSetting->type_proxy, ['general', 'all'])) disabled="disabled" @endif>
                                    <div class="title-field">@lang('proxies::phrases.Цена за 1 день (Общие прокси)'):</div>
                                    <div class="wrap-input numberFormat">
                                        <span>$</span>
                                        <input type="number" class="input-text" placeholder="0" name="general_price[]"
                                            value="{{ $day['general_price'] }}">
                                    </div>
                                </div>
                                <div class="field type-proxy" data-type="private"
                                    @if (!in_array($tariffSetting->type_proxy, ['private', 'all'])) disabled="disabled" @endif>
                                    <div class="title-field">@lang('proxies::phrases.Цена за 1 день (Приватные прокси)'):</div>
                                    <div class="wrap-input numberFormat">
                                        <span>$</span><input type="number" class="input-text" placeholder="0"
                                            name="private_price[]" value="{{ $day['private_price'] }}">
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="title-field">@lang('proxies::phrases.Для страны'):</div>
                                    <select class="input-text" name="country[]">
                                        @foreach ($tariffCountries as $tariffCountry)
                                            <option value="{{ $tariffCountry }}" selected>{{$tariffCountry}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($key != 0)
                                    <div class="field wrap-btn">
                                        <div class="delPrice btn btn-primary">@lang('proxies::phrases.Удалить тариф')</div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="footer-block not-radius">
                        <button type="submit" class="btn btn-primary">@lang('proxies::phrases.Сохранить')</button>
                    </div>
                </form>
            </div>

            {{-- Акции и скидки --}}
            <div class="tabs__pane flex-block">
                <form action="{{ route('countproxydiscount.store') }}" method="POST" class="">
                    @csrf
                    {{-- Скидка на количество --}}
                    <div class="footer-block not-radius">
                        <div class="row title-block proxy">
                            <h2>@lang('proxies::phrases.Скидка на кол-во прокси (Скидка в процентах)'):</h2>
                            <button type="submit" class="btn btn-primary">@lang('proxies::phrases.Сохранить')</button>
                            {{-- <div class="btn btn-success addQuantityDiscount"><span>+</span></div> --}}
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="wrap-input title">
                                    <label for="highload3" class="title-field">@lang('proxies::phrases.Скидка суммируется с другими')?</label>
                                    <input type="checkbox" id="highload3" name="proxy_discount" class=""
                                        {{ $tariffSetting->proxy_discount ? 'checked' : '' }}>
                                    <label for="highload3" data-onlabel="" data-offlabel="" class="lb1"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="padding-20 flex-block quantity-discount">
                        <table class="table table-bordered padding-20" style="padding-top: 0">
                            <thead>
                                <tr class="tr-name">
                                    <th>№</th>
                                    <th>@lang('proxies::phrases.От скольки пар прокси')</th>
                                    <th>@lang('proxies::phrases.Скидка')</th>
                                    <th>@lang('proxies::phrases.Тип прокси')</th>
                                    <th>@lang('proxies::phrases.Страна')</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($countProxyDiscounts as $countProxyDiscount)
                                    <tr id="countProxyDiscount_{{ $countProxyDiscount->id }}">
                                        <th>{{ $countProxyDiscount->id }}</th>
                                        <td>{{ $countProxyDiscount->proxy }}</td>
                                        <td>{{ $countProxyDiscount->discount }}%</td>
                                        <td>{{ $countProxyDiscount->type == 'general' ? trans('proxies::phrases.Общие') : ($countProxyDiscount->type == 'private' ? trans('proxies::phrases.Приватные') : trans('proxies::phrases.Общие и Приватные')) }}
                                        </td>
                                        <td>{{ $countProxyDiscount->country }}</td>
                                        <td class="dayst">
                                            <button type="button" data-title="@lang('proxies::phrases.Вы точно хотите удалить скидку')?" data-fetch="yes"
                                                data-action="{{ route('countproxydiscount.destroy', $countProxyDiscount->id) }}"
                                                data-modal="del" class="btn btn-danger">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row group discount">
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.От скольки пар прокси')</div>
                                <input type="text" class="input-text" placeholder="3" name="proxy">
                            </div>
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.Процент скидки')</div>
                                <input type="text" class="input-text" placeholder="10" name="discount">
                            </div>
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.Для типа прокси')</div>
                                <select name="type-proxy" id="type-proxy" class="input-text">
                                    <option value="general" selected>@lang('proxies::phrases.Общие')</option>
                                    <option value="private">@lang('proxies::phrases.Приватные')</option>
                                    <option value="all">@lang('proxies::phrases.Общие и Приватные')</option>
                                </select>
                            </div>
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.Для страны')</div>
                                <select class="input-text" name="country">
                                    @foreach ($tariffCountries as $tariffCountry)
                                        <option value="{{ $tariffCountry }}" selected>{{$tariffCountry}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="{{ route('countdaysdiscount.store') }}" method="POST" class="">
                    @csrf
                    {{-- Скидка на срок --}}
                    <div class="footer-block not-radius">
                        <div class="row title-block proxy">
                            <h2>@lang('proxies::phrases.Скидка на кол-во дней (Скидка в процентах)'):</h2>
                            <button type="submit" class="btn btn-primary">@lang('proxies::phrases.Сохранить')</button>
                            {{-- <div class="btn btn-success addDiscountDuration"><span>+</span></div> --}}
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="wrap-input title">
                                    <label for="highload4" class="title-field">@lang('proxies::phrases.Скидка суммируется с другими')?</label>
                                    <input type="checkbox" id="highload4" name="days_discount" class=""
                                        {{ $tariffSetting->days_discount ? 'checked' : '' }}>
                                    <label for="highload4" data-onlabel="" data-offlabel="" class="lb1"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="padding-20 flex-block discount-duration">
                        <table class="table table-bordered padding-20" style="padding-top: 0">
                            <thead>
                                <tr class="tr-name">
                                    <th>№</th>
                                    <th>@lang('proxies::phrases.От скольки дней')</th>
                                    <th>@lang('proxies::phrases.Скидка')</th>
                                    <th>@lang('proxies::phrases.Тип прокси')</th>
                                    <th>@lang('proxies::phrases.Страна')</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($countDaysDiscounts as $countDaysDiscount)
                                    <tr id="countDaysDiscount_{{ $countDaysDiscount->id }}">
                                        <th>{{ $countDaysDiscount->id }}</th>
                                        <td>{{ $countDaysDiscount->days }}</td>
                                        <td>{{ $countDaysDiscount->discount }}%</td>
                                        <td>{{ $countDaysDiscount->type == 'general' ? trans('proxies::phrases.Общие') : ($countProxyDiscount->type == 'private' ? trans('proxies::phrases.Приватные') : trans('proxies::phrases.Общие и Приватные')) }}
                                        </td>
                                        <td>{{ $countDaysDiscount->country }}</td>
                                        <td class="dayst">
                                            <button type="button" data-title="@lang('proxies::phrases.Вы точно хотите удалить скидку')?" data-fetch="yes"
                                                data-action="{{ route('countdaysdiscount.destroy', $countDaysDiscount->id) }}"
                                                data-modal="del" class="btn btn-danger">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row group duration">
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.От скольки дней')</div>
                                <input type="text" class="input-text" placeholder="3" name="days">
                            </div>
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.Процент скидки')</div>
                                <input type="text" class="input-text" placeholder="10" name="discount">
                            </div>
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.Для типа прокси')</div>
                                <select name="type-proxy" id="type-proxy" class="input-text">
                                    <option value="general" selected>@lang('proxies::phrases.Общие')</option>
                                    <option value="private">@lang('proxies::phrases.Приватные')</option>
                                    <option value="all">@lang('proxies::phrases.Общие и Приватные')</option>
                                </select>
                            </div>
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.Для страны')</div>
                                <select class="input-text" name="country">
                                    @foreach ($tariffCountries as $tariffCountry)
                                        <option value="{{ $tariffCountry }}" selected>{{$tariffCountry}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Промокоды --}}
                <form action="{{ route('promocodes.store') }}" method="POST" class="">
                    @csrf

                    <div class="footer-block not-radius">
                        <div class="row title-block proxy">
                            <h2>@lang('proxies::phrases.Промокоды'):</h2>
                            <button type="submit" class="btn btn-primary">@lang('proxies::phrases.Сохранить')</button>
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="wrap-input title">
                                    <label for="highload5" class="title-field">@lang('proxies::phrases.Скидка суммируется с другими')?</label>
                                    <input type="checkbox" id="highload5" name="promocode_discount" class=""
                                        {{ $tariffSetting->promocode_discount ? 'checked' : '' }}>
                                    <label for="highload5" data-onlabel="" data-offlabel="" class="lb1"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="padding-20">
                        <div class="row">
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.Срок действия')</div>
                                <input type="text" class="input-text" placeholder="3" name="date_end">
                            </div>
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.Количество активаций')</div>
                                <input type="text" class="input-text" placeholder="10" name="max_activated">
                            </div>
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.Размер скидки (в %)')</div>
                                <input type="text" class="input-text" placeholder="10" name="discount">
                            </div>
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.Мин. кол-во прокси')</div>
                                <input type="text" class="input-text" placeholder="10" name="min_quantity">
                            </div>
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.Мин. срок аренды')</div>
                                <input type="text" class="input-text" placeholder="10" name="min_rent">
                            </div>
                            <div class="field">
                                <label for="highload2" class="title-field">@lang('proxies::phrases.Многоразовый')</label>
                                <input type="checkbox" id="highload2" name="multi_activating" class="">
                                <label for="highload2" data-onlabel="" data-offlabel="" class="lb1"></label>
                            </div>
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.Промокод')</div>
                                <div class="wrap-input numberFormat promo">
                                    <span class="generate-code"><i class="fa-solid fa-shuffle"></i></span>
                                    <input type="text" name="name" placeholder="PROXY23" class="input-text code">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <table class="table table-bordered padding-20" style="padding-top: 0">
                    <thead>
                        <tr class="tr-name">
                            <th>№</th>
                            <th>@lang('proxies::phrases.Срок действия')</th>
                            <th>@lang('proxies::phrases.Кол-во активаций')</th>
                            <th>@lang('proxies::phrases.Скидка')</th>
                            <th>@lang('proxies::phrases.Мин. кол-во прокси')</th>
                            <th>@lang('proxies::phrases.Мин. срок аренды')</th>
                            <th>@lang('proxies::phrases.Многоразовый')</th>
                            <th>@lang('proxies::phrases.Промокод')</th>
                            <th>@lang('proxies::phrases.Активный')</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($promocodes as $promocode)
                            <tr id="promocode_{{ $promocode->id }}">
                                <th>{{ $promocode->id }}</th>
                                <td>{{ Carbon\Carbon::parse($promocode->date_end)->format('d.m.y H:i:s') }}</td>
                                <td>{{ $promocode->count_activated }}/{{ $promocode->max_activated }}</td>
                                <td>{{ $promocode->discount }}%</td>
                                <td>{{ $promocode->min_quantity }}</td>
                                <td>{{ $promocode->min_rent }}</td>
                                <td>{{ $promocode->multi_activating ? trans('proxies::phrases.Да') : trans('proxies::phrases.Нет') }}</td>
                                <td>{{ $promocode->name }}</td>
                                <td>{{ $promocode->is_active ? trans('proxies::phrases.Да') : trans('proxies::phrases.Нет') }}</td>
                                <td class="dayst">
                                    <button type="submit" data-title="@lang('proxies::phrases.Вы точно хотите удалить промокод')?" data-fetch="yes"
                                        data-action="{{ route('promocodes.destroy', $promocode->id) }}" data-modal="del"
                                        class="btn btn-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <form action="{{ route('countpairsproxydiscount.store') }}" method="POST" class="">
                    @csrf
                    {{-- Скидка на покупку и продление --}}
                    <div class="footer-block not-radius">
                        <div class="row title-block proxy">
                            <h2>@lang('proxies::phrases.Скидка на покупку и продление'):</h2>
                            <button type="submit" class="btn btn-primary">@lang('proxies::phrases.Сохранить')</button>
                            {{-- <div class="btn btn-success addDiscountDuration"><span>+</span></div> --}}
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="wrap-input title">
                                    <label for="highload6" class="title-field">@lang('proxies::phrases.Скидка суммируется с другими')?</label>
                                    <input type="checkbox" id="highload6" name="proxy_pairs_discount" class=""
                                        {{ $tariffSetting->proxy_pairs_discount ? 'checked' : '' }}>
                                    <label for="highload6" data-onlabel="" data-offlabel="" class="lb1"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="padding-20 flex-block discount-duration">
                        <table class="table table-bordered padding-20" style="padding-top: 0">
                            <thead>
                                <tr class="tr-name">
                                    <th>№</th>
                                    <th>@lang('proxies::phrases.От кол-ва активных пар прокси')</th>
                                    <th>@lang('proxies::phrases.Скидка на продление')</th>
                                    <th>@lang('proxies::phrases.Скидка на покупку')</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($countPairsProxyDiscounts as $countPairsProxyDiscount)
                                    <tr id="countPairsProxyDiscount_{{ $countPairsProxyDiscount->id }}">
                                        <th>{{ $countPairsProxyDiscount->id }}</th>
                                        <td>{{ $countPairsProxyDiscount->count_pairs }}</td>
                                        <td>{{ $countPairsProxyDiscount->discount_buy }}%</td>
                                        <td>{{ $countPairsProxyDiscount->discount_extension }}%</td>
                                        <td class="dayst">
                                            <button type="button" data-title="@lang('proxies::phrases.Вы точно хотите удалить скидку')?" data-fetch="yes"
                                                data-action="{{ route('countpairsproxydiscount.destroy', $countPairsProxyDiscount->id) }}"
                                                data-modal="del" class="btn btn-danger">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row group duration">
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.От кол-ва активных пар прокси')</div>
                                <input type="text" class="input-text" placeholder="3" name="count_pairs">
                            </div>
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.Скидка на покупку')</div>
                                <input type="text" class="input-text" placeholder="10" name="discount_buy">
                            </div>
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.Скидка на продление')</div>
                                <input type="text" class="input-text" placeholder="10" name="discount_extension">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="indent"></div>
@endsection

@section('script')
    {{-- Табы --}}
    <script src="{{ asset('admin/js/tabs.js') }}{{ '?' . time() }}"></script>
    <script>
        new ItcTabs('.settings-tabs', {}, 'settings-tabs');
    </script>

    {{-- Тип прокси --}}
    <script>
        var typeProxyContainers;
        const tariffCountries = @json($tariffCountries);

        function typeProxy() {
            typeProxyContainers = document.querySelectorAll('.type-proxy');
        }
        typeProxy()
        document.addEventListener('DOMContentLoaded', function() {
            const typeProxySelect = document.getElementById('type-proxy');

            typeProxySelect.addEventListener('change', function() {
                var selectedValue = typeProxySelect.value;
                typeProxy();
                searchInput(selectedValue, "globalType");

                typeProxyContainers.forEach(function(container) {
                    const dataValue = container.getAttribute('data-type');
                    if (selectedValue === 'all' || dataValue === selectedValue) {
                        container.removeAttribute('disabled');
                    } else {
                        container.setAttribute('disabled', 'disabled');
                    }
                });
            });
        });

        // Функция для создания и проверки input
        function createOrUpdateInput(container, className, value) {
            // Проверяем, есть ли input с указанным классом в контейнере
            var existingInput = container.querySelector('.' + className);

            if (existingInput) {
                existingInput.value = value;
            } else {
                // Если input не существует, создаем новый элемент input
                var inputElement = document.createElement("input");
                inputElement.setAttribute("type", "text");
                inputElement.setAttribute("name", className);
                inputElement.setAttribute("value", value);
                inputElement.setAttribute("hidden", "");
                inputElement.classList.add(className); // Добавляем класс
                container.appendChild(inputElement);
            }
        }

        function searchInput(value, className) {
            // Находим все элементы с классом .rates-wrap и .payment-day
            var ratesWrapElements = document.querySelectorAll(".rates-wrap");
            var paymentDayElements = document.querySelectorAll(".payment-day");

            // Вызываем функцию createOrUpdateInput для каждого блока .rates-wrap
            ratesWrapElements.forEach(function(element) {
                createOrUpdateInput(element, className, value);
            });

            // Вызываем функцию createOrUpdateInput для каждого блока .payment-day
            paymentDayElements.forEach(function(element) {
                createOrUpdateInput(element, className, value);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            var countryDefault = document.querySelector("#country-default");
            console.log(countryDefault);
            searchInput(countryDefault.value, "country-default")
        });
    </script>

    {{-- Оплата по дням --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Находим элементы для добавления и удаляения тарифов
            const addPricesButton = document.querySelector(".addPrices");
            const pricesContainer = document.querySelector(".prices");

            // Функция для создания нового тарифа
            function createNewPrice() {
                const newPrice = document.createElement("div");
                newPrice.classList.add("row", "price");
                const typeProx = '{{ $tariffSetting->type_proxy }}';

                // Создаем структуру нового тарифа
                let newHTML = `
                    <div class="field type-proxy" data-type="general" ${typeProx == 'private' ? 'disabled="disabled"' : '' }>
                        <div class="title-field">@lang('proxies::phrases.Цена за 1 день (Общие прокси)'):</div>
                        <div class="wrap-input numberFormat">
                            <span>$</span>
                            <input type="number" class="input-text" placeholder="0" name="general_price[]" value="">
                        </div>
                    </div>
                    <div class="field type-proxy" data-type="private" ${typeProx == 'general' ? 'disabled="disabled"' : '' }>
                        <div class="title-field">@lang('proxies::phrases.Цена за 1 день (Приватные прокси)'):</div>
                        <div class="wrap-input numberFormat">
                            <span>$</span><input type="number" class="input-text" placeholder="0" name="private_price[]" value="">
                        </div>
                    </div>
                    <div class="field">
                        <div class="title-field">@lang('proxies::phrases.Для страны'):</div>
                        <select class="input-text" name="country[]">`;
                    tariffCountries.forEach(tariffCountry => {
                        newHTML += `<option value="${ tariffCountry }" selected>${tariffCountry}</option>`;
                    });
                newHTML += `
                        </select>
                    </div>
                    <div class="field wrap-btn">
                        <div class="delPrice btn btn-primary">@lang('proxies::phrases.Удалить тариф')</div>
                    </div>
                `;

                newPrice.innerHTML = newHTML;
                // Добавляем обработчик события для удаления тарифа
                const delPriceButton = newPrice.querySelector(".delPrice");
                delPriceButton.addEventListener("click", function() {
                    removePrice(newPrice);
                });

                pricesContainer.appendChild(newPrice);
            }

            // Функция для удаления тарифа
            function removePrice(price) {
                if (pricesContainer.contains(price)) {
                    pricesContainer.removeChild(price);
                }
            }

            pricesContainer.addEventListener("click", function(event) {
                if (event.target.classList.contains("delPrice")) {
                    const priceToDelete = event.target.closest(".price");
                    if (priceToDelete) {
                        removePrice(priceToDelete);
                    }
                }
            });

            // Добавляем обработчик события для создания нового тарифа
            addPricesButton.addEventListener("click", createNewPrice);
        });
    </script>

    {{-- Тарифы --}}
    <script>
        var selectElement;
        var defaultValue;
        var selectedValue;
        var lang;
        
        langs()
        defaultValue = selectElement.value;
        language(defaultValue)

        // Добавляем обработчик события change
        selectElement.addEventListener("change", function() {
            selectedValue = selectElement.value;
            language(selectedValue)
            langs()
        });

        function language(value) {
            let rateContainer = document.querySelector('.row.rates');
            const ratesLang = rateContainer.querySelectorAll('.field.rate');

            ratesLang.forEach(rate => {
                lang = rate.getAttribute('data-lang');
                if (lang !== value) {
                    rate.style.display = "none";
                } else {
                    rate.style.display = "flex";
                }
            });
        }
        function langs() {
            selectElement = document.getElementById("language");
        }
        
        let rateIdCounter = @json(count($tariffSetting->tariff) + 1); // ID из бд

        // Функция для создания нового тарифа
        function createRate() {
            const rateContainer = document.querySelector('.row.rates');
            const newRate = document.createElement('div');
            newRate.className = 'field rate';
            newRate.setAttribute('data-id', rateIdCounter);
            newRate.setAttribute('data-lang', selectElement.value);

            let newHTML = `
                <input type="hidden" name="id" value="${rateIdCounter}">
                <input type="hidden" name="lang_${rateIdCounter}" value="${selectElement.value}">
                <input type="text" name="name_${rateIdCounter}" value="" class="input-text" placeholder="@lang('proxies::phrases.Название')">
                <div class="properties">
                    <input type="text" name="properties_${rateIdCounter}[]" value="" class="input-text" placeholder="@lang('proxies::phrases.Свойство')">
                    <div class="addProperty">+ @lang('proxies::phrases.Добавить свойство')</div>
                </div>
                <input type="number" name="period_${rateIdCounter}" value="" min="1" class="input-text" placeholder="@lang('proxies::phrases.Срок')">
                <div class="wrap-countries" data-id="${rateIdCounter}">
                    <div class="countries">
                        <select class="input-text" name="country_${rateIdCounter}[]">
                            `;
            tariffCountries.forEach(tariffCountry => {
                newHTML += `<option value="${ tariffCountry }" selected>${tariffCountry}</option>`;
            });
            newHTML += `
                        </select>
                        <input type="number" name="cost-general_${rateIdCounter}[]" value="" min="1" class="input-text" placeholder="@lang('proxies::phrases.Цена') @lang('proxies::phrases.Общие')">
                        <input type="number" name="cost-private_${rateIdCounter}[]" value="" min="1" class="input-text" placeholder="@lang('proxies::phrases.Цена') @lang('proxies::phrases.Приватные')">
                        <div class="addCountry">+ @lang('proxies::phrases.Добавить страну')</div>
                    </div>
                </div>
                <div class="delRate btn btn-primary">@lang('proxies::phrases.Удалить тариф')</div>
            `;
            newRate.innerHTML = newHTML;
            rateContainer.appendChild(newRate);
            rateIdCounter++;
        }

        // Функция для добавления нового свойства
        function addProperty(parentElement) {
            var id = parentElement.getAttribute("data-id"); // Получаем ID тарифа
            const propertyContainer = document.createElement('div');
            propertyContainer.className = 'wrap-input numberFormat';

            const deletePropertyButton = document.createElement('span');
            deletePropertyButton.className = 'delProperty';
            deletePropertyButton.innerHTML = '<i class="fa-solid fa-trash"></i>';
            deletePropertyButton.addEventListener('click', function() {
                const parentOfPropertyContainer = propertyContainer.parentElement;
                if (parentOfPropertyContainer) {
                    parentOfPropertyContainer.removeChild(propertyContainer);
                }
                propertyContainer.removeChild(deletePropertyButton);
            });

            const propertyInput = document.createElement('input');
            propertyInput.type = 'text';
            propertyInput.name = 'properties_' + id + '[]'; // Подставляем ID тарифа - свойства
            propertyInput.value = '';
            propertyInput.placeholder = '@lang('proxies::phrases.Свойство')';
            propertyInput.classList.add('input-text');

            propertyContainer.appendChild(deletePropertyButton);
            propertyContainer.appendChild(propertyInput);
            parentElement.querySelector('.properties').appendChild(propertyContainer);
        }

        // Функция для добавления новой страны с полями для цены
        function addCountry(parentElement) {
            console.log(parentElement);
            var id = parentElement.getAttribute("data-id");
            const countryContainer = document.createElement('div');
            countryContainer.className = 'countries';

            const selectCountry = document.createElement('select');
            selectCountry.className = 'input-text';
            selectCountry.name = 'country_' + id + '[]'; // Подставляем ID тарифа - Страна

            var option = document.createElement('option');
            tariffCountries.forEach(tariffCountry => {
                option.value = tariffCountry;
                option.text = tariffCountry;
                selectCountry.appendChild(option);
            });

            const generalPriceInput = document.createElement('input');
            generalPriceInput.type = 'number';
            generalPriceInput.name = 'cost-general_' + id + '[]'; // Подставляем ID тарифа - Общие
            generalPriceInput.value = '';
            generalPriceInput.min = '1';
            generalPriceInput.className = 'input-text';
            generalPriceInput.placeholder = '@lang('proxies::phrases.Цена') @lang('proxies::phrases.Общие')';

            const privatePriceInput = document.createElement('input');
            privatePriceInput.type = 'number';
            privatePriceInput.name = 'cost-private_' + id + '[]'; // Подставляем ID тарифа - Приватные
            privatePriceInput.value = '';
            privatePriceInput.min = '1';
            privatePriceInput.className = 'input-text';
            privatePriceInput.placeholder = '@lang('proxies::phrases.Цена') @lang('proxies::phrases.Приватные')';

            const deleteCountryButton = document.createElement('div');
            deleteCountryButton.className = 'delCountry';
            deleteCountryButton.textContent = '@lang('proxies::phrases.Удалить страну')';
            deleteCountryButton.addEventListener('click', function() {
                const parentOfCountryContainer = countryContainer.parentElement;
                if (parentOfCountryContainer) {
                    parentOfCountryContainer.removeChild(countryContainer);
                }
                countryContainer.removeChild(deleteCountryButton);
            });

            countryContainer.appendChild(selectCountry);
            countryContainer.appendChild(generalPriceInput);
            countryContainer.appendChild(privatePriceInput);
            countryContainer.appendChild(deleteCountryButton);
            parentElement.appendChild(countryContainer);
        }

        // Функция для удаления тарифа
        function deleteRate(rateId) {
            const rateContainer = document.querySelector('.row.rates');
            const rateToRemove = document.querySelector(`.field.rate[data-id="${rateId}"]`);
            rateContainer.removeChild(rateToRemove);
        }

        // Функция для скрытия/отображения блока rates
        function toggleRatesVisibility() {
            const tariffSystemCheckbox = document.querySelector('.tariff-system');
            const ratesContainer = document.querySelector('.rates-wrap');
            const paymentContainer = document.querySelector('.payment-day');
            if (tariffSystemCheckbox.checked) {
                ratesContainer.style.display = 'block';
                paymentContainer.style.display = 'none';
                searchInput(1, "globalTariff");

            } else {
                ratesContainer.style.display = 'none';
                paymentContainer.style.display = 'block';
                searchInput(0, "globalTariff");
            }
        }

        // Обработчики событий
        document.querySelector('.addRates').addEventListener('click', createRate);
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('delRate')) {
                const rateId = event.target.parentElement.getAttribute('data-id');
                deleteRate(rateId);
            }
            if (event.target.classList.contains('addProperty')) {
                addProperty(event.target.parentElement.parentElement);
            }
            if (event.target.classList.contains('delProperty') || event.target.closest('.delProperty')) {
                const delProperty = event.target.classList.contains('delProperty') ? event.target : event.target
                    .closest('.delProperty');
                const propertyContainer = delProperty.parentElement;
                const parentOfPropertyContainer = propertyContainer.parentElement;
                if (parentOfPropertyContainer) {
                    parentOfPropertyContainer.removeChild(propertyContainer);
                }
            }
            if (event.target.classList.contains('delCountry') || event.target.classList.contains(
                    'delCountryText')) {
                const delCountry = event.target.classList.contains('delCountry') ? event.target : event.target
                    .closest('.delCountry');
                const countryContainer = delCountry.parentElement;
                const parentOfCountryContainer = countryContainer.parentElement;
                if (parentOfCountryContainer) {
                    parentOfCountryContainer.removeChild(countryContainer);
                }
            }
            if (event.target.classList.contains('addCountry')) {
                addCountry(event.target.parentElement.parentElement);
            }
            if (event.target.classList.contains('tariff-system')) {
                toggleRatesVisibility();
            }
        });

        // Инициализация видимости блока rates при загрузке страницы
        toggleRatesVisibility();
    </script>

    {{-- Скидка за количество --}}
    {{-- <script>
        function createDiscount() {
            const quantityDiscount = document.querySelector('.quantity-discount');
            const newDiscount = document.createElement('div');
            newDiscount.className = 'row discount';

            newDiscount.innerHTML = `<div class="field">
                                        <div class="title-field">От штук</div>
                                        <input type="text" class="input-text" placeholder="3" name="proxy">
                                    </div>
                                    <div class="field">
                                        <div class="title-field">Процент скидки</div>
                                        <input type="text" class="input-text" placeholder="10" name="discount"
                                            value="{{ $settingModel->proxy_two_sel_count }}">
                                    </div>
                                    <div class="field">
                                        <div class="title-field">Для страны</div>
                                        <select class="input-text" name="country">
                                            <option value="1" selected>Казахстан</option>
                                        </select>
                                    </div>
                                    <div class="field wrap-btn">
                                        <div class="btn btn-primary delQuantityDiscount">Удалить</div>
                                    </div>`;

            quantityDiscount.appendChild(newDiscount);
        }

        function deleteDiscount(event) {
            if (event.target.classList.contains('delQuantityDiscount')) {
                const discount = event.target.closest('.discount');
                if (discount) {
                    discount.remove();
                }
            }
        }

        document.querySelector('.addQuantityDiscount').addEventListener('click', createDiscount);
        document.addEventListener('click', deleteDiscount);
    </script> --}}

    {{-- Скидка на срок --}}
    {{-- <script>
        function createDiscount() {
            const quantityDiscount = document.querySelector('.discount-duration');
            const newDiscount = document.createElement('div');
            newDiscount.className = 'row duration';

            newDiscount.innerHTML = `<div class="field">
                                        <div class="title-field">От штук</div>
                                        <input type="text" class="input-text" placeholder="3" name="amount_days">
                                    </div>
                                    <div class="field">
                                        <div class="title-field">Процент скидки</div>
                                        <input type="text" class="input-text" placeholder="10" name="sel_period"
                                            value="{{ $settingModel->proxy_two_sel_count }}">
                                    </div>
                                    <div class="field">
                                        <div class="title-field">Для страны</div>
                                        <select class="input-text" name="country">
                                            <option value="1" selected>Казахстан</option>
                                        </select>
                                    </div>
                                    <div class="field wrap-btn">
                                        <div class="btn btn-primary delDiscountDuration">Удалить</div>
                                    </div>`;

            quantityDiscount.appendChild(newDiscount);
        }

        function deleteDiscount(event) {
            if (event.target.classList.contains('delDiscountDuration')) {
                const discount = event.target.closest('.duration');
                if (discount) {
                    discount.remove();
                }
            }
        }

        document.querySelector('.addDiscountDuration').addEventListener('click', createDiscount);
        document.addEventListener('click', deleteDiscount);
    </script> --}}

    {{-- Промокоды --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const generateCodeButton = document.querySelector('.generate-code');
            const codeInput = document.querySelector('.code');

            generateCodeButton.addEventListener('click', function() {
                const generatedCode = generatePromoCode();
                codeInput.value = generatedCode;
            });

            function generatePromoCode() {
                const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                const codeLength = 10;
                let code = '';

                for (let i = 0; i < codeLength; i++) {
                    const randomIndex = Math.floor(Math.random() * characters.length);
                    code += characters.charAt(randomIndex);
                }

                return code;
            }
        });
    </script>

    {{-- Хз зачем это тут --}}
    <script>
        //     document.addEventListener('DOMContentLoaded', () => {
        //         document.querySelector('#changePass').addEventListener('click', function(e) {
        //             e.target.style.display = 'none'
        //             document.querySelector('#passKraken').style.display = 'block'
        //         });
        //     });
        // 
    </script>
@endsection
