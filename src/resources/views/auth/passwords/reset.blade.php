@extends('prxoies::templates.'. (new Ssda1\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory() .'.layouts.app')
@section('style')
    <link rel="stylesheet" href="/vendor/ssda-1/proxies/assets/css/auth.css">
@endsection
@section('content')

<div class="wraper-lk-block">
    <div class="card-header">
        <h1>Reset Password</h1>
    </div>

    <div class="wraper-lk-content">
        <form method="POST" action="{{ route('password.update') }}" data-fetch="none">
            @csrf
            <div class="left-block-lk">

                <input type="hidden" name="token" value="{{ $token }}">


                <label for="email" class="col-md-4 col-form-label text-md-end">Email Address</label>


                <input id="email" type="email" class="input-lk @error('email') is-invalid @enderror" name="email"
                    value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror



                <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>


                <input id="password" type="password" class="input-lk @error('password') is-invalid @enderror"
                    name="password" required autocomplete="new-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror



                <label for="password-confirm"
                    class="col-md-4 col-form-label text-md-end">Confirm Password</label>

                <input id="password-confirm" type="password" class="input-lk" name="password_confirmation" required
                    autocomplete="new-password">
            </div>
            <button type="submit" class="btn button">
                Reset the password
            </button>
        </form>
    </div>
</div>

@endsection
