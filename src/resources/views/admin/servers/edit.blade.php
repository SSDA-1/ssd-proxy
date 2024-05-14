@extends('admin.app')

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Редактирование сервера') "{{ $server->name }}"</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="/admin/proxy/setting"><i class="bx bx-left-arrow-alt icon"></i> @lang('proxies::phrases.Назад')</a>
        </div>
    </div>

    @if (count($errors) > 0)
        <div class="alert alert-danger block-background">
            <p><strong>@lang('proxies::phrases.Упс')!</strong> @lang('proxies::phrases.Были некоторые проблемы с вашим вводом').</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {!! Form::model($server, [
        'method' => 'PATCH',
        'data-fetch' => 'none',
        'enctype' => 'multipart/form-data',
        'route' => ['servers.update', $server->id],
    ]) !!}

    <div class="block-background basement-form">
        <div class="row">
            <div class="field">
                <div class="title-field">@lang('proxies::phrases.Название'):</div>
                {!! Form::text('name', null, ['placeholder' => trans('proxies::phrases.Сервер') . ' №1', 'class' => 'input-text']) !!}
            </div>
            <div class="field list">
                <div class="title-field">@lang('proxies::phrases.Страна'):</div>
                <select name="country" class="select-multiple">
                    @foreach (Countries::getList('ru', 'php', 'cldr') as $key => $value)
                        <option value="{{ $value['name'] }}" {{$server->country == $value['name'] ? 'selected' : ''}}>{{ $value['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="field">
                <div class="title-field">HTTP (@lang('proxies::phrases.Мин').):</div>
                {!! Form::text('data[httpmin]', isset($server->data['httpmin']) ? $server->data['httpmin'] : '', [
                    'placeholder' => '53001',
                    'class' => 'input-text',
                ]) !!}
            </div>
            <div class="field">
                <div class="title-field">HTTP (@lang('proxies::phrases.Макс').):</div>
                {!! Form::text('data[httpmax]', isset($server->data['httpmax']) ? $server->data['httpmax'] : '', [
                    'placeholder' => '53200',
                    'class' => 'input-text',
                ]) !!}
            </div>
            <div class="field">
                <div class="title-field">SOCKS (@lang('proxies::phrases.Мин').):</div>
                {!! Form::text('data[socksmin]', isset($server->data['socksmin']) ? $server->data['socksmin'] : '', [
                    'placeholder' => '53001',
                    'class' => 'input-text',
                ]) !!}
            </div>
            <div class="field">
                <div class="title-field">SOCS (@lang('proxies::phrases.Макс').):</div>
                {!! Form::text('data[socksmax]', isset($server->data['socksmax']) ? $server->data['socksmax'] : '', [
                    'placeholder' => '53200',
                    'class' => 'input-text',
                ]) !!}
            </div>
        </div>
        <div class="row">
            <div class="field">
                <div class="title-field">@lang('proxies::phrases.Логин Кракена'):</div>
                {!! Form::text('data[login]', isset($server->data['login']) ? $server->data['login'] : '', [
                    'placeholder' => 'login',
                    'class' => 'input-text',
                ]) !!}
            </div>
            <div class="field">
                <div class="title-field">@lang('proxies::phrases.Пароль Кракена'):</div>
                {!! Form::text('data[password]', isset($server->data['password']) ? $server->data['password'] : '', [
                    'placeholder' => 'password',
                    'class' => 'input-text',
                ]) !!}
            </div>
            <div class="field">
                <div class="title-field">@lang('proxies::phrases.Адрес сервера'):</div>
                {!! Form::text('data[url]', isset($server->data['url']) ? $server->data['url'] : '', [
                    'placeholder' => 'url',
                    'class' => 'input-text',
                ]) !!}
            </div>
        </div>
    </div>
    <div class="footer-block">
        <button type="submit" class="btn btn-primary">@lang('proxies::phrases.Сохранить')</button>
    </div>

    {!! Form::close() !!}

@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('select[name="type"]').addEventListener('change', function(e) {

                if (e.target.value == '1') {
                    document.getElementsByName('number')[0].placeholder = 'Число от 1010 до 1999';
                } else {
                    document.getElementsByName('number')[0].placeholder = 'Число от 2010 до 2999';
                }
            });
        });
    </script>
@endsection
