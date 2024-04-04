@extends('admin.app')

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2> @lang('phrases.Показать пользователя')</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('users.index') }}"> @lang('phrases.Назад')</a>
        </div>
    </div>

    <div class="block-background">
        <div class="row">
            <div class="field">
                <div class="title-field">@lang('phrases.Имя'):</div>
                <div class="input-text">{{ $user->name }}</div>
            </div>
            <div class="field">
                <div class="title-field">Email:</div>
                <div class="input-text">{{ $user->email }}</div>
            </div>
        </div>
        <div class="row">
            <div class="field list">
                <div class="title-field">@lang('phrases.Роль'):</div>
                <div class="select-multiple">
                    @if (!empty($user->getRoleNames()))
                        @foreach ($user->getRoleNames() as $v)
                            {{ $v }}
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
