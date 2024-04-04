@extends('admin.app')

@section('content')
<div class="header-page">
    <div class="title-page">
        <h2>@lang('phrases.Добавление нового сервера')</h2>
    </div>
    <div class="buttons">
        <a class="btn btn-success" href="/admin/proxy/setting"><i class="bx bx-left-arrow-alt icon"></i> @lang('phrases.Назад')</a>
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

{!! Form::open(array('route' => 'servers.store','method'=>'POST','data-fetch' => 'none')) !!}
<div class="block-background basement-form">
    <div class="row">
        <div class="field">
            <div class="title-field">@lang('phrases.Название'):</div>
            {!! Form::text('name', null, array('placeholder' => trans('phrases.Сервер') . ' №1','class' => 'input-text')) !!}
        </div>
        <div class="field list">
            <div class="title-field">@lang('phrases.Страна'):</div>
            <select name="country" class="select-multiple">
                @foreach (Countries::getList('ru', 'php', 'cldr') as $key => $value)
                    <option value="{{ $value['name'] }}">{{ $value['name'] }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="field">
            <div class="title-field">@lang('phrases.Логин Кракена'):</div>
            {!! Form::text('data[login]', null, array('placeholder' => 'login','class' => 'input-text')) !!}
        </div>
        <div class="field">
            <div class="title-field">@lang('phrases.Пароль Кракена'):</div>
            {!! Form::text('data[password]', null, array('placeholder' => 'password','class' => 'input-text')) !!}
        </div>
        <div class="field">
            <div class="title-field">@lang('phrases.Адрес сервера'):</div>
            {!! Form::text('data[url]', null, array('placeholder' => 'http://000.000.000.00:80000','class' => 'input-text')) !!}
        </div>
    </div>
</div>
<div class="footer-block">
    <button type="submit" class="btn btn-primary">@lang('phrases.Сохранить')</button>
</div>
{!! Form::close() !!}

@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelector('select[name="type"]').addEventListener('change', function (e) {
            
            if (e.target.value == '1') {
                document.getElementsByName('number')[0].placeholder = 'Число от 1010 до 1999';
            }else{
                document.getElementsByName('number')[0].placeholder = 'Число от 2010 до 2999';
            }
        });
    });
</script>
@endsection