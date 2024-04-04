@extends('admin.app')

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('phrases.Создание новой роли')</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('roles.index') }}">@lang('phrases.Назад')</a>
        </div>
    </div>

    @if (count($errors) > 0)
        <div class="alert alert-danger block-background">
            <strong>@lang('phrases.Упс')!</strong> @lang('phrases.Были некоторые проблемы с вашим вводом').<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {!! Form::open(['route' => 'roles.store', 'data-fetch' => 'none', 'method' => 'POST']) !!}
    <div class="block-background basement-form">
        <div class="row">
            <div class="field">
                <div class="title-field">@lang('phrases.Имя'):</div>
                {!! Form::text('name', null, ['placeholder' => trans('phrases.Имя'), 'class' => 'input-text']) !!}
            </div>
        </div>
        <div class="row">
            <div class="field list">
                <div class="title-field">@lang('phrases.Разрешение'):</div>
                <div class="list-check">
                    @foreach ($permission as $value)
                        <div class="checkbox">
                            {{ Form::checkbox('permission[]', $value->id, false, ['id' => $value->name, 'class' => 'custom-checkbox']) }}
                            <label for="{{ $value->name }}">{{ __('roles.' . $value->name) }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="footer-block">
        <button type="submit" class="btn btn-primary">@lang('phrases.Сохранить')</button>
    </div>
    {!! Form::close() !!}

@endsection
