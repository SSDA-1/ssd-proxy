@extends('templates.'. (new App\Http\Controllers\TemplateController())->getUserTemplateDirectory() .'.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
@endsection
@section('content')

<div class="wraper-lk-block">
    <div class="card-header">
        <h1>{{ __('auth.Verify Your Email Address') }}</h1>
    </div>

    <div class="wraper-lk-content">
        @if (session('resent'))
        <div class="alert alert-success" role="alert">
            {{ __('auth.A fresh verification link has been sent to your email address.') }}
        </div>
        @endif

        {{ __('auth.Before proceeding, please check your email for a verification link.') }}
        {{ __('auth.If you did not receive the email') }},
        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}" data-fetch="none">
            @csrf
            <div class="left-block-lk">
                <button type="submit" class="btn button">{{ __('auth.click here to request another') }}</button>.
            </div>
        </form>
    </div>
</div>

@endsection
