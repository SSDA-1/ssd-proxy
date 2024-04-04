@extends('templates.' . (new App\Http\Controllers\TemplateController())->getUserTemplateDirectory() . '.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/css/lk.css') }}{{ '?' . time() }}">
@endsection
@section('body-class')
personal-area
@endsection

@section('content')
    <style>
        .lk-content .help {
            display: flex;
            flex-direction: column;
            gap: 20px;
            list-style-type: none;
        }

        .lk-content .help li {
            max-width: 480px;
            width: 100%;
            border-radius: 10px;
            background: #FFF;
            box-shadow: 0px 18px 32px -4px rgba(24, 39, 75, 0.04);
        }

        .lk-content .help .link-help {
            height: 110px;
            color: #111;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .lk-content .help .link-text {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .lk-content .help .link-text .title {
            font-size: 20px;
            font-weight: 400;
        }

        .lk-content .help .link-text .text {
            color: #111;
            font-size: 14px;
            display: flex;
            flex-direction: column;
        }

        .lk-content .help .link-help .text a {
            display: flex;
            gap: 5px;
            color: #111;
        }
        .lk-content .help .link-help .text a span {
            font-weight: 400;
            color: #111;
        }
    </style>
    <div class="lk-block">
        @include('admin.lk.menu')
        <div class="lk-content">
            <ul class="help">
                <li>
                    <a class="link-help" href="https://t.me/Adsproxysupport" target="_blanlk">
                        <div class="link-text">
                            <div class="title">@lang('phrases.Живая связь с нами 24/7'):</div>
                            <div class="text">@lang('phrases.Мы ответим вам в течении 1 минуты')</div>
                        </div>
                        <img src="{{ asset('assets/img/telegramm.png') }}">
                    </a>
                </li>
                <li>
                    <a class="link-help" href="https://t.me/adsproxychannel" target="_blanlk">
                        <div class="link-text">
                            <div class="title">@lang('phrases.Чат')</div>
                            <div class="text">@lang('phrases.Присоединяйтесь! Здесь вы сможете пообщаться с нами и с участниками группы')
                            </div>
                        </div>
                        <img src="{{ asset('assets/img/chat.png') }}">
                    </a>
                </li>
                <li class="link-help">
                    <div class="link-text">
                        <div class="title">@lang('phrases.Сотрудничество')</div>
                        <div class="text">
                            <a href="mailto:{{$settingsData->cooperation_email}}">
                                <span>@lang('phrases.Почта'): </span>{{$settingsData->cooperation_email}}
                            </a>
                            <a href="https://t.me/{{$settingsData->cooperation_tg}}" target="_blanlk">
                                <span>Telegram: </span>{{'@'.$settingsData->cooperation_tg}}
                            </a>
                        </div>
                    </div>
                    <img src="{{ asset('assets/img/chat.png') }}">
                </li>
                <li>
                    <a class="link-help" href="/support">
                        <div class="link-text">
                            <div class="title">@lang('phrases.Тех. поддержка на сайте'):</div>
                            <div class="text">@lang('phrases.Всегда ответим, но не всегда быстро')</div>
                        </div>
                        <img src="{{ asset('assets/img/help.png') }}">
                    </a>
                </li>
            </ul>
        </div>
    </div>
@endsection
