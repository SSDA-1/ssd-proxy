@extends('templates.'. (new App\Http\Controllers\TemplateController())->getUserTemplateDirectory() .'.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
@endsection
@section('content')

<div class="wraper-lk-block">
    <div class="card-header">
        <h1>{{ __('auth.Reset Password') }}</h1>
    </div>

    <div class="wraper-lk-content">
        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" data-fetch="none">
            @csrf

            <div class="left-block-lk">
                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('auth.Email Address') }}</label>


                <input id="email" type="email" class="input-lk @error('email') is-invalid @enderror" name="email"
                    value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

            </div>


            <button type="submit" class="btn button">
                {{ __('auth.Reset the password') }}
            </button>

        </form>
    </div>
</div>

@endsection
