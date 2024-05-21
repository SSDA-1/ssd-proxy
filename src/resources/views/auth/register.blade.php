@extends('proxies::templates.' . (new Ssda1\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory() . '.layouts.app')
@section('style')
    <link rel="stylesheet" href="/vendor/ssda-1/proxies/assets/css/auth.css{{'?'.time()}}">
@endsection
@section('content')
    <div class="wraper-lk-block">

        <div class="card-header">
            <h1>{{ __('auth.Register') }}</h1>
        </div>
        @if ($errors->has('message'))
            <div class="alert alert-danger">
                {{ $errors->first('message') }}
            </div>
        @endif
        <div class="wraper-lk-content">
            <form method="POST" action="{{ route('register') }}" data-fetch="none">
                @csrf

                <div class="left-block-lk">
                    <label for="email">E-mail:
                        <input type="email" name="email" class="input-lk @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required autocomplete="email" autofocus>
                    </label>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    <label for="name">{{ __('auth.Name') }}:
                        <input id="name" type="text" class="input-lk @error('name') is-invalid @enderror"
                            name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    </label>

                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    <label for="password">{{ __('auth.Password') }}:
                        <input id="password" type="password" class="input-lk @error('password') is-invalid @enderror"
                            name="password" required autocomplete="new-password">
                    </label>

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <label for="password-confirm">{{ __('auth.Confirm Password') }}:

                        <input id="password-confirm" type="password" class="input-lk" name="password_confirmation" required
                            autocomplete="new-password">
                    </label>
                    @if (request()->has('ref'))
                        <input type="hidden" name="ref" value="{{ request()->get('ref') }}">
                    @endif

                    {{-- <label for="telegram">Telegram ID:
                        <input type="text" name="telegram_chat_id" id="telegram"
                            class="input-lk @error('email') is-invalid @enderror" required autocomplete="telegram" autofocus
                            placeholder="00000000">
                        <span style="font-size: 11px; color: #fff;">Для получения id chata перейдите к боту <br>
                            <a style="font-size: 11px;text-decoration: underline;" href="{{ $tgData->telegram_link }}"
                                target="_blanck">{{ $tgData->telegram_link }}</a><br>
                            и напишите /start либо нажмите кнопку start
                        </span>
                    </label>
                    @error('telegram_chat_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror --}}
                </div>
                <div class="form-check">
                    <input type="checkbox" name="check" id="check" required>
                    <label for="check">
                        Согласен с <a href="/rules">политикой
                            конфиденциальности</a>
                    </label><br>
                </div>

                <div class="right-block-lk">
                    <button type="submit" class="btn button" style="    width: 100%;">
                        {{ __('auth.Registers') }}
                    </button>
                </div>
                
                <script async src="https://telegram.org/js/telegram-widget.js?22" data-telegram-login="NotificationProxyBot" data-size="large" data-auth-url="@if (request()->has('ref')) https://ads-proxy.com/auth/telegram/{{ request()->get('ref') }} @else https://ads-proxy.com/auth/telegram/nocode @endif" data-request-access="write"></script>

                <div class="wrap-reg-form">
                    <div class="reg login-reg">
                        <a href="/login">{{ __('auth.Login') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
