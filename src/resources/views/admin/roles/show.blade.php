@extends('admin.app')

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Роль') {{ $role->name }}</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('roles.index') }}"> @lang('proxies::phrases.Назад')</a>
        </div>
    </div>

    <div class="block-background">
        <div class="row">
            <div class="field">
                <div class="title-field">@lang('proxies::phrases.Имя'):</div>
                <div class="input-text">{{ $role->name }}</div>
            </div>
        </div>
        <div class="row">
            <div class="field list">
                <div class="title-field">@lang('proxies::phrases.Разрешение'):</div>
                <div class="list-check">
                    @if (!empty($rolePermissions))
                        @foreach ($rolePermissions as $value)
                            <label for="{{ $value->name }}">{{ __('roles.' . $value->name) }}</label>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
