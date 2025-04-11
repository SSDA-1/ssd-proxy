@extends('proxies::templates.'. (new Ssda1\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory() .'.layouts.app')
@section('style')
    <link rel="stylesheet" href="/vendor/ssda-1/proxies/assets/css/auth.css">
@endsection
@section('content')

<div class="wraper-lk-block">
    <div class="card-header">
        <h1>Verify Your Email Address</h1>
    </div>

    <div class="wraper-lk-content">
        @if (session('resent'))
        <div class="alert alert-success" role="alert">
            A fresh verification link has been sent to your email address.
        </div>
        @endif

        Before proceeding, please check your email for a verification link.
        If you did not receive the email,
        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}" data-fetch="none">
            @csrf
            <div class="left-block-lk">
                <button type="submit" class="btn button">click here to request another</button>.
            </div>
        </form>
    </div>
</div>

@endsection
