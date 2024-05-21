@extends('proxies::templates.' . (new Ssda1\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory() . '.layouts.app')

@section('style')
    <link rel="stylesheet" href="/vendor/ssda-1/proxies/assets/css/lk.css{{ '?' . time() }}">
@endsection
@section('body-class')
    personal-area
@endsection

@section('content')
    <div class="lk-block">
        @include('proxies::admin.lk.menu')
        <div class="lk-content">
            <section class="lk3">
                <div class="wrap">
                    <div class="wrap-btn action-proxy">
                        <div class="btn js-open-modal disabled" data-modal="extendAll">@lang('proxies::phrases.Продлить прокси')</div>
                        <div class="btn js-open-modal disabled" data-modal="rotationAll">@lang('proxies::phrases.Ротация')</div>
                        <div class="btn disabled" id="changeIpAll">@lang('proxies::phrases.Смена IP')</div>
                        <form action="/fetch/multi/download/time" method="POST" class="" data-fetch="none">
                            {{--  id="downloadAll" --}}
                            @csrf
                            <input type="hidden" name="ids" class="ids">
                            <button type="submit" class="btn disabled">
                                <img src="/assets/img/download-w.svg" />
                                <img src="/assets/img/download-p.svg" class="hov" />
                            </button>
                        </form>
                    </div>
                    <div class="table">
                        <div class="thead">
                            <div class="cell box1" style="width: fit-content"><input type="checkbox"
                                    class="proxy-checkbox-all"></div>
                            <div class="row">
                                <div class="cell">@lang('proxies::phrases.Статус')</div>
                                <div class="cell">IP @lang('proxies::phrases.Порт')</div>
                                <div class="cell">@lang('proxies::phrases.Логин') @lang('proxies::phrases.Пароль')</div>
                                <div class="cell">@lang('proxies::phrases.Протокол Гео')</div>
                                <div class="cell">@lang('proxies::phrases.Смена IP')</div>
                                <div class="cell">@lang('proxies::phrases.Ротация')</div>
                                <div class="cell">@lang('proxies::phrases.Период')</div>
                            </div>
                            <div class="cell box1">@lang('proxies::phrases.Действия')</div>
                        </div>
                        <div class="tbody">
                            @inject('proxyStatusService', 'App\Service\ProxyStatusService')
                            @php
                                $modemRow = 0;
                                $idFirstRow = 0;
                                $i = 0;
                                $proxyModal;
                                $password_user_proxy = null;
                            @endphp
                            @if (isset($proxis) && $proxis->isNotEmpty() && $error == null)
                                @foreach ($proxis as $proxy)
                                    @if ($proxy->password_user_proxy_kraken != $proxy->user->kraken_username)
                                        @php
                                            $password_user_proxy = $proxy->password_user_proxy_kraken;
                                        @endphp
                                    @break
                                @endif
                            @endforeach
                            {{-- @if (!$tokenUser)
                                <style>
                                    .lk3 .table .row {
                                        grid-template-columns: 5% 16% minmax(15%, 40%) 16% 15% 13%;
                                    }
                                </style>
                            @endif --}}
                            @foreach ($proxis as $proxy)
                                @if ($proxy->modem->id != $modemRow)
                                    @php
                                        $modemRow = $proxy->modem->id;
                                        $idFirstRow += $proxy->id;
                                    @endphp
                                    <div class="group unification{{ $proxy->modem->id }}" id="proxy{{ $proxy->id }}">
                                        <div class="cell box1" style="width: fit-content">
                                            {{-- Чеки --}}
                                            <div class="cell">
                                                <input type="checkbox" class="proxy-checkbox"
                                                    value="{{ $proxy->id }}">
                                            </div>
                                            {{-- /Чеки --}}
                                        </div>
                                        <div class="test">
                                            <div class="row" id="row{{ $proxy->id }}"
                                                data-status="{{ $tokenUser ? ($proxy->active == 1 ? 'Online' : 'Offline') : 'Offline' }}"
                                                data-ip="<?php echo parse_url($proxy->modem->server->data['url'], PHP_URL_HOST); ?>:{{ $proxy->number_proxy }}"
                                                data-country="{{ $proxy->modem->server->country }}">

                                                <div class="cell flex">
                                                    @if ($tokenUser)
                                                        <div class="status {{ $proxy->active == 1 ? '' : 'off' }}"
                                                            data-modem="{{ $proxy->modem->id }}"
                                                            data-type="{{ $proxy->type }}"
                                                            data-login="{{ $proxy->login_user_proxy_kraken }}">
                                                            {{ $proxy->active == 1 ? 'Online' : 'Offline' }}</div>
                                                    @else
                                                        <div class="status off" data-modem="{{ $proxy->modem->id }}"
                                                            data-type="{{ $proxy->type }}"
                                                            data-login="{{ $proxy->login_user_proxy_kraken }}">
                                                            @lang('proxies::phrases.Не отвечает')</div>
                                                    @endif
                                                </div>
                                                @if ($password_user_proxy == null)
                                                    <div class="cell" id="block{{ $proxy->id }}1"
                                                        data-target="block{{ $proxy->id }}1">
                                                        {{ $proxy->type }}://{{ $proxy->login_user_proxy_kraken ?: $proxy->user->kraken_username }}:{{ $proxy->password_user_proxy_kraken ?: $proxy->user->kraken_username }}@<?php echo parse_url($proxy->modem->server->data['url'], PHP_URL_HOST); ?>:{{ $proxy->number_proxy }}
                                                    </div>
                                                @else
                                                    <div class="cell" id="block{{ $proxy->id }}2"
                                                        data-target="block{{ $proxy->id }}2">
                                                        <?php echo parse_url($proxy->modem->server->data['url'], PHP_URL_HOST); ?>:{{ $proxy->number_proxy }}
                                                    </div>
                                                    <div class="cell" id="block{{ $proxy->id }}"
                                                        data-target="block{{ $proxy->id }}">
                                                        {{ $proxy->login_user_proxy_kraken ?: $proxy->user->kraken_username }}:{{ $password_user_proxy }}
                                                    </div>
                                                @endif
                                                <div class="cell"
                                                    style="display: flex; align-items: center; gap: 5px; justify-content: space-between;">
                                                    <span style="padding-top: 1px;">{{ $proxy->type }}</span>
                                                    <img src="https://ads-proxy.com/assets/img/kz-q.svg"
                                                        style="wwidth: 24px; border-radius: 3px;" alt="flag">
                                                    {{-- {{ $proxy->modem->server->country }} --}}
                                                </div>

                                                <div class="cell reconnect" id="reconnect{{ $i }}"
                                                    style="text-align: center"
                                                    data-href="{{ $settingModel->integration_ip }}:8000/api/devices/modem/reconnect?token={{ $tokenUser }}&id={{ $proxy->modem->id_kraken }}">
                                                    @if ($proxy->modem->locked_ip_type_change == 1)
                                                        @if ($proxy->modem->reconnect_type_fake == 'time_link')
                                                            @lang('proxies::phrases.По времени') @lang('proxies::phrases.и')
                                                            <a href="#get" id="" class="my-link"
                                                                style="color: #DA5583;"
                                                                data-id="{{ $proxy->modem->id }}"
                                                                data-proxy="{{ $proxy->id - 1 }}"
                                                                data-proxy2="{{ $proxy->id }}">@lang('proxies::phrases.ссылке')</a>
                                                            {{-- <div class="copyLink" data-text="Копировать"
                                                                style="display: inline-block;">
                                                                <img src="/assets/img/copy.svg" class="copy "
                                                                    data-target="link{{ $idFirstRow }}"
                                                                    data-link="{{ route('changeGetIP', ['proxy' => $idFirstRow - 1, 'proxy2' => $idFirstRow]) }}" />
                                                            </div> --}}
                                                        @elseif ($proxy->modem->reconnect_type_fake == 'time')
                                                            @lang('proxies::phrases.По времени')
                                                        @elseif ($proxy->modem->reconnect_type_fake == 'link')
                                                            <a href="#get" data-id="" class="copy-link my-link"
                                                                id="{{ $proxy->modem->id }}" style="color: #DA5583;"
                                                                data-proxy="{{ $proxy->id - 1 }}"
                                                                data-proxy2="{{ $proxy->id }}">@lang('proxies::phrases.По ссылке')</a>

                                                            {{-- <div class="copyLink" data-text="Копировать"
                                                                style="display: inline-block;">
                                                                <img src="/assets/img/copy.svg" class="copy "
                                                                    data-target="link{{ $idFirstRow }}"
                                                                    data-link="{{ route('changeGetIP', ['proxy' => $idFirstRow - 1, 'proxy2' => $idFirstRow]) }}" />
                                                            </div> --}}
                                                        @endif
                                                    @else
                                                        @if ($proxy->modem->reconnect_type == 'time_link')
                                                            @lang('proxies::phrases.По времени') @lang('proxies::phrases.и')
                                                            <a href="#get" id="" class="copy-link my-link"
                                                                style="color: #DA5583;"
                                                                data-id="{{ $proxy->modem->id }}"
                                                                data-proxy="{{ $proxy->id - 1 }}"
                                                                data-proxy2="{{ $proxy->id }}">@lang('proxies::phrases.ссылке')</a>
                                                            {{-- <div class="copyLink" data-text="Копировать"
                                                                style="display: inline-block;">
                                                                <img src="/assets/img/copy.svg" class="copy "
                                                                    data-target="link{{ $idFirstRow }}"
                                                                    data-link="{{ route('changeGetIP', ['proxy' => $idFirstRow - 1, 'proxy2' => $idFirstRow]) }}" />
                                                            </div> --}}
                                                        @elseif ($proxy->modem->reconnect_type == 'time')
                                                            @lang('proxies::phrases.По времени')
                                                        @elseif ($proxy->modem->reconnect_type == 'link')
                                                            <a href="#get" data-id=""
                                                                class="copy-link my-link"
                                                                id="{{ $proxy->modem->id }}" style="color: #DA5583;"
                                                                data-proxy="{{ $proxy->id - 1 }}"
                                                                data-proxy2="{{ $proxy->id }}">@lang('proxies::phrases.По ссылке')</a>

                                                            {{-- <div class="copyLink" data-text="Копировать"
                                                                style="display: inline-block;">
                                                                <img src="/assets/img/copy.svg" class="copy "
                                                                    data-target="link{{ $idFirstRow }}"
                                                                    data-link="{{ route('changeGetIP', ['proxy' => $idFirstRow - 1, 'proxy2' => $idFirstRow]) }}" />
                                                            </div> --}}
                                                        @endif
                                                    @endif

                                                </div>

                                                @if ($proxy->modem->reconnect_type_fake == 'link')
                                                    <div class="cell ifname" style="text-align: center">
                                                        -
                                                    </div>
                                                @else
                                                    <div class="cell ifname">
                                                        {{ $proxy->modem->reconnect_interval . ' ' }}@lang('proxies::phrases.секунд')
                                                    </div>
                                                @endif

                                                @php
                                                    $currentDate = \Carbon\Carbon::now();
                                                    $endDate = \Carbon\Carbon::parse($proxy->date_end, 'GMT');
                                                    $timeDifference = $currentDate->diff($endDate);
                                                    $totalDays = $timeDifference->days;
                                                @endphp
                                                <div class="cell">
                                                    {{ $formattedTime = $totalDays . ' дней ' . $timeDifference->h . ' часов' }}
                                                </div>
                                            </div>

                                            @php
                                                $i++;
                                            @endphp
                                        @else
                                            @php
                                                $modemRow = 0;
                                            @endphp
                                            <div class="row" id="row{{ $proxy->id }}"
                                                data-status="{{ $tokenUser ? ($proxy->active == 1 ? 'Online' : 'Offline') : 'Offline' }}">

                                                <div class="cell flex">
                                                    @if ($tokenUser)
                                                        <div class="status {{ $proxy->active == 1 ? '' : 'off' }}"
                                                            data-modem="{{ $proxy->modem->id }}"
                                                            data-type="{{ $proxy->type }}"
                                                            data-login="{{ $proxy->login_user_proxy_kraken }}">
                                                            {{ $proxy->active == 1 ? 'Online' : 'Offline' }}</div>
                                                    @else
                                                        <div class="status off" data-modem="{{ $proxy->modem->id }}"
                                                            data-type="{{ $proxy->type }}"
                                                            data-login="{{ $proxy->login_user_proxy_kraken }}">
                                                            @lang('proxies::phrases.Не отвечает')</div>
                                                    @endif
                                                </div>
                                                @if ($password_user_proxy == null)
                                                    <div class="cell " id="block{{ $proxy->id }}"
                                                        data-target="block{{ $proxy->id }}">
                                                        {{ $proxy->type }}://{{ $proxy->login_user_proxy_kraken ?: $proxy->user->kraken_username }}:{{ $proxy->password_user_proxy_kraken ?: $proxy->user->kraken_username }}@<?php echo parse_url($proxy->modem->server->data['url'], PHP_URL_HOST); ?>:{{ $proxy->number_proxy }}
                                                    </div>
                                                @else
                                                    <div class="cell " id="block{{ $proxy->id }}"
                                                        data-target="block{{ $proxy->id }}">
                                                        <?php echo parse_url($proxy->modem->server->data['url'], PHP_URL_HOST); ?>:{{ $proxy->number_proxy }}
                                                    </div>
                                                    <div class="cell " id="block{{ $proxy->id }}"
                                                        data-target="block{{ $proxy->id }}">
                                                        {{ $proxy->login_user_proxy_kraken ?: $proxy->user->kraken_username }}:{{ $password_user_proxy }}
                                                    </div>
                                                @endif
                                                <div class="cell"
                                                    style="display: flex; align-items: center; gap: 5px; justify-content: space-between;">
                                                    <span style="padding-top: 1px;">{{ $proxy->type }}</span>
                                                    <img src="https://ads-proxy.com/assets/img/kz-q.svg"
                                                        style="width: 24px; border-radius: 3px;" alt="flag">
                                                </div>
                                                <div class="cell">
                                                    @if ($proxy->modem->locked_ip_type_change == 1)
                                                        @if ($proxy->modem->reconnect_type_fake == 'time_link')
                                                            <div class="copyLink btnstup" data-text="Копировать"
                                                                style="display: inline-block;">
                                                                <span class="copy btn reboot"
                                                                    data-target="link{{ $idFirstRow }}"
                                                                    data-link="{{ route('changeGetIP', ['proxy' => $idFirstRow - 1, 'proxy2' => $idFirstRow]) }}">
                                                                    @lang('proxies::phrases.Ссылка для смены IP')</span>
                                                            </div>
                                                        @elseif ($proxy->modem->reconnect_type_fake == 'link')
                                                            <div class="copyLink btnstup" data-text="Копировать"
                                                                style="display: inline-block;">
                                                                <span class="copy btn reboot"
                                                                    data-target="link{{ $idFirstRow }}"
                                                                    data-link="{{ route('changeGetIP', ['proxy' => $idFirstRow - 1, 'proxy2' => $idFirstRow]) }}">
                                                                    @lang('proxies::phrases.Ссылка для смены IP')</span>
                                                            </div>
                                                        @endif
                                                    @else
                                                        @if ($proxy->modem->reconnect_type == 'time_link')
                                                            <div class="copyLink btnstup" data-text="Копировать"
                                                                style="display: inline-block;">
                                                                <span class="copy btn reboot"
                                                                    data-target="link{{ $idFirstRow }}"
                                                                    data-link="{{ route('changeGetIP', ['proxy' => $idFirstRow - 1, 'proxy2' => $idFirstRow]) }}">
                                                                    @lang('proxies::phrases.Ссылка для смены IP')</span>
                                                            </div>
                                                        @elseif ($proxy->modem->reconnect_type == 'link')
                                                            <div class="copyLink btnstup" data-text="Копировать"
                                                                style="display: inline-block;">
                                                                <span class="copy btn reboot"
                                                                    data-target="link{{ $idFirstRow }}"
                                                                    data-link="{{ route('changeGetIP', ['proxy' => $idFirstRow - 1, 'proxy2' => $idFirstRow]) }}">
                                                                    @lang('proxies::phrases.Ссылка для смены IP')</span>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="cell"></div>
                                            </div>
                                        </div>
                                        <div class="cell box1">
                                            <div class="extendWrap js-open-modal" data-text="Автопродление"
                                                data-modal="autorenewal">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="autopayButt {{ $proxy->autopay == 1 ? 'active' : '' }}"
                                                    data-id="{{ $proxy->id }}" viewBox="0 0 26 26" width="22px"
                                                    height="22px">
                                                    <path
                                                        d="M 13 1 C 6.382813 1 1 6.382813 1 13 C 1 19.617188 6.382813 25 13 25 C 19.617188 25 25 19.617188 25 13 C 25 6.382813 19.617188 1 13 1 Z M 13 3 C 18.535156 3 23 7.464844 23 13 C 23 18.535156 18.535156 23 13 23 C 7.464844 23 3 18.535156 3 13 C 3 7.464844 7.464844 3 13 3 Z M 17.1875 7.0625 C 17.039063 7.085938 16.914063 7.164063 16.8125 7.3125 L 11.90625 14.59375 L 9.59375 12.3125 C 9.394531 12.011719 9.011719 11.988281 8.8125 12.1875 L 7.90625 13.09375 C 7.707031 13.394531 7.707031 13.800781 7.90625 14 L 11.40625 17.5 C 11.605469 17.601563 11.886719 17.8125 12.1875 17.8125 C 12.386719 17.8125 12.707031 17.707031 12.90625 17.40625 L 18.90625 8.59375 C 19.105469 8.292969 18.992188 8.011719 18.59375 7.8125 L 17.59375 7.09375 C 17.492188 7.042969 17.335938 7.039063 17.1875 7.0625 Z" />
                                                </svg>
                                            </div>
                                            <div class="extendWrap js-open-modal" data-text="Продлить"
                                                data-modal="extend">
                                                <img src="/assets/img/extend.svg" class="extendButt"
                                                    data-id="{{ $proxy->id }}" />
                                            </div>
                                            <div class="js-open-modal getEdit" data-modal="proxy"
                                                data-unification="{{ $proxy->modem->id }}"
                                                onclick="editProxy([{{ $idFirstRow }}, {{ $idFirstRow - 1 }}]{{ $proxy->modem->osfp != null ? ',' . $proxy->modem->osfp : ', false' }})"
                                                data-text="Редактировать">
                                                <img src="/assets/img/editing.svg" />
                                            </div>
                                            <a href="/proxy/download/{{ $proxy->id }}" class="extendWrap"
                                                data-text="Скачать">
                                                <img src="/assets/img/download.svg" />
                                            </a>
                                            <div type="submit" class="btn reboot" name="reboot"
                                                style="width: 100%;" data-id="{{ $proxy->modem->id }}">
                                                @lang('proxies::phrases.Перезагрузить')
                                            </div>
                                        </div>

                                    </div>
                                    @php
                                        $idFirstRow = 0;
                                    @endphp
                                @endif
                            @endforeach
                        @elseif($error)
                            @if ($error == 'Undefined array key "key"')
                                <div class="group" style="align-items: center; justify-content: center; gap: 10px;">
                                    @lang('proxies::phrases.Возникла проблема с интеграцией пользователя') <a href="/support">@lang('proxies::phrases.тех поддержка')</a>
                                </div>
                            @else
                                <div class="group" style="align-items: center; justify-content: center; gap: 10px;">
                                    {{ $error }} <a href="/support">@lang('proxies::phrases.тех поддержка')</a>
                                </div>
                            @endif
                        @else
                            <div class="group" style="align-items: center; justify-content: center; gap: 10px;">
                                @lang('proxies::phrases.Тут будут ваши прокси')
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        <div class="indent"></div>
        <div class="list-proxy"></div>
    </div>
</div>
@endsection

@if ($settingModel != null && $settingModel->integration_login != null)
@section('modal')
    {{-- Редактирование прокси --}}
    <div class="modal proxy" data-modal="proxy">
        <div class="wrap-title">
            <p class="modal__title">@lang('proxies::phrases.Редактирование прокси')</p>
            <img src="/assets/img/cross.svg" class="modal__cross js-modal-close" />
        </div>
        <div class="wrap-modal-proxy"></div>
    </div>

    {{-- Продление --}}
    <div class="modal extend" data-modal="extend">
        <div class="background">
            <div class="body">
                {!! Form::open(['method' => 'POST', 'route' => 'controlExtend']) !!}
                <div class="textWrap">
                    <div class="title">@lang('proxies::phrases.Продлить прокси')
                        <span class="numberProxyText"></span>
                        <img src="{{ asset('assets/img/close_ring_light.svg') }}" class="js-modal-close">
                    </div>
                    <div class="massage">
                        <p>Пожалуйста, уточните, на какой срок планируется продление?</p>
                        <input type="hidden" name="id">
                    </div>
                </div>
                <div></div>
                <div class="buttonFormWrap">
                    @if ($tariffSettings->type_tariff)
                        <select name="idt">
                            @foreach ($tariffSettings->tariff as $key => $tariff)
                                @if ($tariff['lang'] == App::getLocale())
                                    @php
                                        $countryKey = array_search($tariffSettings->default_country, $tariff['country']);
                                    @endphp
                                    <option value="{{ $key }}" class="proxy__application-choice"
                                        {{ $key == 1 ? 'selected' : '' }}>
                                        {{ $tariff['period'] }} @lang('proxies::phrases.дней') -
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
                        <select name="month">
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
                    @endif
                    <button class="btn button">
                        @lang('proxies::phrases.Продлить')
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{-- Массовое продление --}}
    <div class="modal extend" data-modal="extendAll">
        <div class="background">
            <div class="body">
                {!! Form::open(['method' => 'POST', 'route' => 'multiControlExtendProxy']) !!}
                <div class="textWrap">
                    <div class="title">@lang('proxies::phrases.Продлить прокси')
                        <span class="numberProxyText"></span>
                        <img src="{{ asset('assets/img/close_ring_light.svg') }}" class="js-modal-close">
                    </div>
                    <div class="massage">
                        <p>Пожалуйста, уточните, на какой срок планируется продление?</p>
                        <input type="hidden" name="ids" class="ids">
                    </div>
                </div>
                <div></div>
                <div class="buttonFormWrap">
                    @if ($tariffSettings->type_tariff)
                        <select name="idt">
                            @foreach ($tariffSettings->tariff as $key => $tariff)
                                @if ($tariff['lang'] == App::getLocale())
                                    @php
                                        $countryKey = array_search($tariffSettings->default_country, $tariff['country']);
                                    @endphp
                                    <option value="{{ $key }}" class="proxy__application-choice"
                                        {{ $key == 1 ? 'selected' : '' }}>
                                        {{ $tariff['period'] }} @lang('proxies::phrases.дней') -
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
                        <select name="month">
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
                    @endif
                    <button class="btn button">
                        @lang('proxies::phrases.Продлить')
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{-- Массовое изменение времени смены IP --}}
    <div class="modal extend" data-modal="rotationAll">
        <div class="background">
            <div class="body">
                {!! Form::open(['method' => 'POST', 'route' => 'multiChangeTimeIP']) !!}
                <div class="textWrap">
                    <div class="title">@lang('proxies::phrases.Ротация IP')
                        <span class="numberProxyText"></span>
                        <img src="{{ asset('assets/img/close_ring_light.svg') }}" class="js-modal-close">
                    </div>
                    <div class="massage">
                        <p>Пожалуйста, уточните, интервал смены IP адреса прокси?</p>
                        <input type="hidden" name="ids" class="ids">
                    </div>
                </div>
                <div></div>
                <div class="buttonFormWrap">
                    <input type="number" placeholder="10 секунд" min="60" name="time" class="input-lk"
                        style="max-height: 50px; max-width: 375px;">
                    <button class="btn button">
                        @lang('proxies::phrases.Изменить')
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{-- Автопродление --}}
    <div class="modal autorenewal extend" data-modal="autorenewal">
        <div class="background">
            <div class="body">
                {!! Form::open(['method' => 'POST', 'route' => ['autopayProxy', 0]]) !!}
                <div class="textWrap">
                    <div class="title">@lang('proxies::phrases.Автопродление прокси')
                        <span class="numberProxyText"></span>
                        <img src="{{ asset('assets/img/close_ring_light.svg') }}" class="js-modal-close">
                    </div>
                    <div class="massage">
                        <p>Пожалуйста, уточните, на какой срок планируется продление?</p>
                        <input type="hidden" name="id">
                    </div>
                </div>
                <div></div>
                <div class="buttonFormWrap">
                    @if ($tariffSettings->type_tariff)
                        <select name="idt">
                            @foreach ($tariffSettings->tariff as $key => $tariff)
                                @if ($tariff['lang'] == App::getLocale())
                                    @php
                                        $countryKey = array_search($tariffSettings->default_country, $tariff['country']);
                                    @endphp
                                    <option value="{{ $key }}" class="proxy__application-choice"
                                        {{ $key == 1 ? 'selected' : '' }}>
                                        {{ $tariff['period'] }} @lang('proxies::phrases.дней') -
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
                        <select name="month">
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
                    @endif
                    <button class="btn button">
                        @lang('proxies::phrases.Включить автопродление')
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{-- Перезагрузка --}}
    <div class="modal rebut" data-modal="rebut">
        <div class="background">
            <div class="body">
                {!! Form::open(['method' => 'POST', 'route' => 'controlExtend']) !!}
                <div class="textWrap">
                    <div class="title">@lang('proxies::phrases.Перезагрузка') <span class="numberProxyText"></span></div>
                    <div class="massage">
                        @lang('proxies::phrases.Успешно выполнена')
                    </div>
                </div>
                <div></div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{-- Смена IP --}}
    <div class="modal reset" data-modal="reset">
        <div class="background">
            <div class="body">
                {!! Form::open(['method' => 'POST', 'route' => 'controlExtend']) !!}
                <div class="textWrap">
                    <div class="title">@lang('proxies::phrases.Смена IP по ссылке') <span class="numberProxyText"></span></div>
                    <div class="massage">
                        @lang('proxies::phrases.Успешно выполнена')
                    </div>
                </div>
                <div></div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{-- Ошибка и сообщения --}}
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
                        @lang('proxies::phrases.Обратится в тех поддержку')
                    </a>
                    <a class="close closeModal btn button modal__cross js-modal-close" href="#">
                        @lang('proxies::phrases.Окей')
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="overlay js-overlay-modal"></div>
@endsection
@endif

@section('script')
<script src="/vendor/ssda-1/proxies/assets/js/control.js{{ '?' . time() }}"></script>
{{-- 
    // Вывод текущего гео в модалке редактирования
        // <table>\
        //     <tr>\
        //         <td>' + document.getElementById("row" + proxyId[0]).dataset.ip.match(/(\d+\.\d+\.\d+\.\d+)/)[0] + ' - ' + document.getElementById("row" + proxyId[0]).dataset.country + '</td>\
        //     </tr>\
        //     <tr>\
        //         <td>' + document.getElementById("row" + proxyId[0]).dataset.ip.match(/(\d+\.\d+\.\d+\.\d+)/)[0] + ' - ' + document.getElementById("row" + proxyId[0]).dataset.country + '</td>\
        //     </tr>\
        // </table>\
        <label for="ifname">Смена отпечатка\
                                  <select name="ifname" id="' + osfp +
            '"><option value="none">Без отпечатка</option>' + ifnameOptions.join('') + '</select>\
                              </label>\
    --}}
<script>
    let btnstup = document.querySelector('.btnstup');
    let oldBtnstup = btnstup.querySelector('span').innerText;
    btnstup.addEventListener('click', function() {
        btnstup.querySelector('span').innerText = 'Скопировано';
        setTimeout(() => {
            btnstup.querySelector('span').innerText = oldBtnstup;
          }, "2000")
    });
</script>
<script>
    function editProxy(id, ifnameBase) {
        const proxy = {!! json_encode($proxis) !!};
        const ifnames = {!! $ifname == null ? 'false' : json_encode($ifname) !!};
        var ifnameOptions = '';
        if (ifnames) {
            const entries = Object.entries(ifnames);
            ifnameOptions = entries.map(ifname => '<option value="' + ifname[0] + '" ' + (ifnameBase == ifname[0] ?
                "selected" : "") + '>' + ifname[1] + '</option>');
        } else {
            ifnameOptions = ['<option value="0" selected>Сервер не отвечает</option>'];
        }
        const proxyId = [],
            proxyType = ['', ''],
            proxyName = ['', ''],
            proxyPass = ['', ''],
            proxyPort = ['', ''];
        let reconnectType, reconnectInterval, osfp, reconnectFake;
        let i = 0;
        let pass = '{{ $password_user_proxy }}';
        proxy.forEach(element => {
            id.forEach(idProxy => {
                if (element.id == idProxy) {
                    proxyId[i] = +idProxy;
                    proxyType[i] += element.type;
                    proxyName[i] += element.login_user_proxy_kraken || element.user.kraken_username;
                    if (pass == null) {
                        proxyPass[i] += element.password_user_proxy_kraken || element.user
                            .kraken_username;
                    } else {
                        proxyPass[0] = pass;
                        proxyPass[1] = pass;
                    }
                    reconnectFake = element.modem.reconnect_type_fake;
                    proxyPort[i] += element.number_proxy;
                    reconnectType += element.modem.reconnect_type;
                    reconnectInterval = +element.modem.reconnect_interval;
                    osfp += element.modem.osfp;
                    i++
                }
            });
        });

        const modalContent = '<div class="modal-table-div"><table>\
                      <tr>\
                          <td>' + proxyId[0] + '</td>\
                          <td class="flex">\
                              <div class="status ' + (document.getElementById("row" + proxyId[0]).dataset.status ==
                'Online' ? '' :
                'off') + '"></div>' + document.getElementById("row" + proxyId[0]).dataset.status + '\
                          </td>\
                          <td class="left">' + proxyType[0] + '://' + proxyName[0] + ':' + proxyPass[0] + '@' +
            document.getElementById("row" + proxyId[0]).dataset.ip.match(/(\d+\.\d+\.\d+\.\d+)/)[0] + ':' + proxyPort[
                0] + '</td>\
                      </tr>\
                      <tr>\
                          <td>' + proxyId[1] + '</td>\
                          <td class="flex">\
                              <div class="status ' + (document.getElementById("row" + proxyId[1]).dataset.status ==
                'Offline' ? 'off' : '') + '"></div>' + document.getElementById("row" + proxyId[1]).dataset.status + '\
                          </td>\
                          <td class="left">' + proxyType[1] + '://' + proxyName[1] + ':' + proxyPass[1] + '@' +
            document.getElementById("row" + proxyId[0]).dataset.ip.match(/(\d+\.\d+\.\d+\.\d+)/)[0] + ':' + proxyPort[
                1] + '</td>\
                      </tr>\
                      </table></div>\
                      <div class="modal-table-div dop-ip">\
                        <h3>Дополнительные IP</h3>\
                        <table>\
                            <tr>\
                                <td>212.118.39.237 - Сервер #1</td>\
                            </tr>\
                            <tr>\
                                <td>170.187.185.87 - Сервер #2</td>\
                            </tr>\
                        </table>\
                        </div>\
                      <form action="/fetch/save/proxy" class="settings-form">\
                          <h3>Редактирование настроек</h3>\
                          <div class="settings">\
                              <label for="reconnect_type">Тип смены IP\
                                  <select name="reconnect_type" id="' + reconnectType + '">\
                                      <option value="time_link" ' + (reconnectFake == "time_link" ? "selected" : "") + '>По времени и ссылке</option>\
                                      <option value="time" ' + (reconnectFake == "time" ? "selected" : "") + '>По времени</option>\
                                      <option value="link" ' + (reconnectFake == "link" ? "selected" : "") + '>По ссылке</option>\
                                  </select>\
                              </label>\
                              <input name="ifname" type="hidden" value="none">\
                              <label for="reconnect_interval">Время смены IP (минимум 60 сек.)\
                                  <input type="number" min="60" name="reconnect_interval" value="' +
            reconnectInterval + '" />\
                              </label>\
                          </div>\
                          <h3>Редактирование данных</h3>\
                          <div id="errorUser"></div>\
                          <div class="data">\
                              <label for="login">Логин прокси <strong>' + proxyName[0] + '</strong>\
                                <input type="text" name="login" value="' + proxyName[0] + '" hidden/>\
                              </label>\
                              <label for="password">Новый пароль прокси\
                                  <input type="text" name="password" value="' + proxyPass[1] + '" />\
                              </label>\
                              <label for="password1">Подтвердите новый пароль\
                                  <input type="text" name="password1" value="' + proxyPass[1] + '" />\
                              </label>\
                              <input type="hidden" name="id" value="' + proxyId[0] + '" />\
                              <input type="hidden" name="id2" value="' + proxyId[1] + '" />\
                          </div>\
                          <div class="wrap-btn">\
                              <button type="button" class="btn button modal__cross js-modal-close">Отмена</button>\
                              <button class="btn button save-form no-hover">\
                                <span class="lds-ripple"><span></span><span></span></span>\
                                Сохранить\
                                </button>\
                          </div>\
                      </form>';

        const modal = document.querySelector('.wrap-modal-proxy');
        modal.innerHTML = modalContent;
        var cancelButtons = document.querySelectorAll('.js-modal-close'),
            overlay = document.querySelector('.js-overlay-modal');
        cancelButtons.forEach(function(item) {
            item.addEventListener('click', function(e) {
                var parentModal = this.closest('.modal');
                parentModal.classList.remove('active');
                overlay.classList.remove('active');
            });
        });

    }
</script>
@endsection
