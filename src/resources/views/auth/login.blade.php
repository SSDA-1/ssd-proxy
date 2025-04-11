@extends('proxies::templates.' . (new Ssda1\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory() . '.layouts.app')
@section('style')
    <link rel="stylesheet" href="/vendor/ssda-1/proxies/assets/css/auth.css{{'?'.time()}}">
@endsection

@section('content')
    <div class="wraper-lk-block">
        <div class="card-header">
            <h1>Login</h1>
        </div>

        <div class="wraper-lk-content">
            <form method="POST" action="{{ route('login') }}" data-fetch="none">
                <div class="left-block-lk">
                    @csrf


                    <label for="email" class="col-md-4 col-form-label text-md-end">Email Address

                        <input id="email" type="email" class="input-lk @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    </label>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    <label for="password" class="col-md-4 col-form-label text-md-end">Password

                        <input id="password" type="password" class="input-lk @error('password') is-invalid @enderror"
                            name="password" required autocomplete="current-password">
                    </label>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    {{-- <script async src="https://telegram.org/js/telegram-widget.js?22" data-telegram-login="NotificationProxyBot" data-size="large" data-auth-url="https://beta.ads-proxy.com/auth/telegram" data-request-access="write"></script> --}}

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>

                        <label class="form-check-label" for="remember">
                            Remember Me
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-link button">
                    Login
                </button>
                
                <script async src="https://telegram.org/js/telegram-widget.js?22" data-telegram-login="NotificationProxyBot" data-size="large" data-auth-url="https://ads-proxy.com/auth/telegram/nocode" data-request-access="write"></script>

                <div class="wrap-reg-form">
                    <div class="reg">
                        {{-- Еще нет аккаунта? --}}
                        <a href="/register">Зарегистрироваться!</a>
                        @if (Route::has('password.request'))
                            <a class="" href="{{ route('password.request') }}">
                                Forgot Your Password?
                            </a>
                        @endif
                    </div>

                    
                </div>

            </form>
        </div>
    </div>
@endsection
