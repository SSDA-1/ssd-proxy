@extends('admin.app')

@section('content')

    <div class="header-page">
        <div class="title-page">
            <h2>@lang('phrases.Создание нового прокси')</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('proxy.index') }}"><i class="bx bx-left-arrow-alt icon"></i> @lang('phrases.Назад')</a>
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

    {!! Form::open(['route' => 'proxy.store', 'method' => 'POST', 'data-fetch' => 'none']) !!}
    <div class="block-background basement-form">
        <div class="row">
            <div class="field">
                <div class="title-field">@lang('phrases.ID порта'):</div>
                {!! Form::select('port', $ports, null, ['class' => 'select-multiple']) !!}
            </div>
            <div class="field">
                <div class="title-field">@lang('phrases.Тип прокси'):</div>
                {!! Form::select('type', ['http' => 'http', 'socks' => 'socks'], 2, ['class' => 'select-multiple']) !!}
            </div>
            <div class="field">
                <div class="title-field">@lang('phrases.Номер порта') <span class="font-small">(@lang('phrases.Порт должен быть открыт у вас в роутере'))</span>:</div>
                {!! Form::text('number', null, ['placeholder' => trans('phrases.Число от') . ' 2010 ' .  trans('phrases.до') . ' 2999', 'class' => 'input-text']) !!}
            </div>
        </div>
        <div class="row">
            <div class="field">
                <div class="title-field">​@lang('phrases.Максимальное число подключений'):</div>
                {!! Form::text('maxconnect', 0, ['placeholder' => '0', 'class' => 'input-text']) !!}
            </div>
            <div class="field">
                <div class="title-field">@lang('phrases.Действителен до'):</div>
                {!! Form::date('end_date', 0, ['placeholder' => '0', 'class' => 'input-text']) !!}
                {!! Form::time('end_time', 0, ['placeholder' => '0', 'class' => 'input-text']) !!}
            </div>
            <div class="field">
                <div class="title-field">@lang('phrases.Пользователь'):</div>
                {!! Form::select('user', $users, 2, ['class' => 'select-multiple']) !!}
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
            document.querySelector('select[name="type"]').addEventListener('change', function(e) {

                if (e.target.value == '1') {
                    document.getElementsByName('number')[0].placeholder = '@lang('phrases.Число от') 1010 @lang('phrases.до') 1999';
                } else {
                    document.getElementsByName('number')[0].placeholder = '@lang('phrases.Число от') 2010 @lang('phrases.до') 2999';
                }
            });
        });
    </script>
@endsection
