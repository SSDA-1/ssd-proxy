@extends('admin.app')

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('phrases.Создание нового пользователя')</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('users.index') }}"> @lang('phrases.Назад')</a>
        </div>
    </div>

    @if (count($errors) > 0)
        <div class="alert alert-danger block-background">
            <p><strong>@lang('phrases.Упс')!</strong> @lang('phrases.Были некоторые проблемы с вашим вводом').</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {!! Form::open(['route' => 'users.store', 'data-fetch' => 'none', 'method' => 'POST']) !!}
    <div class="block-background basement-form">
        <div class="row">
            <div class="field">
                <div class="title-field">@lang('phrases.Имя'):</div>
                {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'input-text']) !!}
            </div>
            <div class="field">
                <div class="title-field">Email:</div>
                {!! Form::text('email', null, ['placeholder' => 'Email', 'class' => 'input-text']) !!}
            </div>
            <div class="field">
                <div class="title-field">@lang('phrases.Пароль'):</div>
                {!! Form::password('password', ['placeholder' => 'Password', 'class' => 'input-text']) !!}
            </div>
            <div class="field">
                <div class="title-field">@lang('phrases.Повтор пароля'):</div>
                {!! Form::password('confirm-password', ['placeholder' => 'Confirm Password', 'class' => 'input-text']) !!}
            </div>
        </div>
        <div class="row">
            <div class="field list">
                <div class="title-field">@lang('phrases.Роль'):</div>
                {!! Form::select('roles[]', $roles, [], ['class' => 'select-multiple', 'multiple']) !!}
            </div>
        </div>
    </div>
    <div class="footer-block">
        <button type="submit" class="btn btn-primary">@lang('phrases.Сохранить')</button>
    </div>
    {!! Form::close() !!}

@endsection
