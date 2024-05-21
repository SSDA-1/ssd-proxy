@extends('proxies::templates.' . (new Ssda1\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory() . '.layouts.app')
@section('style')
    <link rel="stylesheet" href="/vendor/ssda-1/proxies/assets/css/auth.css">
@endsection
@section('content')
    <div class="wraper-lk-block">
        <div class="card-header">
            <h1>{{ __('auth.Confirm Password') }}</h1>
        </div>

        <div class="wraper-lk-content">
            {{ __('auth.Please confirm your password before continuing.') }}

            <form method="POST" action="{{ route('password.confirm') }}" data-fetch="none">
                @csrf

                <div class="left-block-lk">
                    <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('auth.Password') }}</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="input-lk @error('password') is-invalid @enderror"
                            name="password" required autocomplete="current-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn button">
                    {{ __('auth.Confirm Password') }}
                </button>

                <div class="wrap-reg-form">
                    <div class="reg login-reg">
                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('auth.Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </div>



            </form>
        </div>
    </div>
@endsection
