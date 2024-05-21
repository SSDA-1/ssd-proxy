@extends('proxies::admin.app')
@section('content')
    <div class="header-page">
        <div class="page-title">
            <div class="pull-left">
                <h2>@lang('proxies::phrases.Покупка шаблона')</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('template-management') }}"> @lang('proxies::phrases.Назад')</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
@endsection
