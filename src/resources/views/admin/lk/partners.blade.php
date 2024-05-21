@extends('proxies::templates.' . (new Ssda1\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory() . '.layouts.app')

@section('style')
    <link rel="stylesheet" href="/vendor/ssda-1/proxies/assets/css/lk.css{{ '?' . time() }}">
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
            padding-right: 0;
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

        .lk-content .help .link-help img {
            width: 100%;
            max-width: 200px;
        }
    </style>
@endsection
@section('body-class')
    personal-area
@endsection

@section('content')
    <div class="lk-block">
        @include('proxies::admin.lk.menu')
        <div class="lk-content">
            <div class="wrap-title" style="margin-bottom: 40px; padding: 20px 20px 0">
                <h3>Партнеры и промокоды</h3>
            </div>
            <ul class="help">
                @foreach ($partners as $partner)
                    <li>
                        @if ($partner->link != null)
                            <a class="link-help" href="{{ $partner->link }}" target="_blanlk">
                                <div class="link-text">
                                    <div class="title">{{ $partner->name }}</div>
                                    <div class="text">
                                        {{ 'Скидка ' . $partner->discount . ('%')($partner->promo != null ? 'по промокоду ' . $partner->promo : '') }}
                                    </div>
                                </div>
                                <img src="{{ asset('$partner->logo') }}">
                            </a>
                        @else
                            <div class="link-help">
                                <div class="link-text">
                                    <div class="title">{{ $partner->name }}</div>
                                    <div class="text">
                                        {{ 'Скидка ' . $partner->discount . '% ' . ($partner->promo != null ? 'по промокоду ' . $partner->promo : '') }}
                                    </div>
                                </div>
                                <img src="{{ asset($partner->logo) }}">
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
