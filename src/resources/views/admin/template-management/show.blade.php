@extends('proxies::admin.app')
@section('content')

    <div class="header-page">
        <div class="page-title">
            <div class="pull-left">
                <h2>@lang('proxies::phrases.Информация о шаблоне')</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('template-management') }}"> @lang('proxies::phrases.Назад')</a>
            </div>
        </div>
    </div>

    <div class="content-block who-is-entrusted">

        <div class="pull-left">
            <h4>@lang('proxies::phrases.Имя'):</h4>
        </div>
        <div class="indoor-unit">
            {{ $template->name }}
        </div>
        <div class="pull-left">
            <h4>Type:</h4>
        </div>
        <div class="indoor-unit">
            {{ $template->type }}
        </div>

    </div>

@endsection
