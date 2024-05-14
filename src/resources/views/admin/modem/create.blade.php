@extends('admin.app')

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Создание нового порта')</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('proxy.index') }}"><i class="bx bx-left-arrow-alt icon"></i> @lang('proxies::phrases.Назад')</a>
        </div>
    </div>

    @if (count($errors) > 0)
        <div class="alert alert-danger block-background">
            <strong>@lang('proxies::phrases.Упс')!</strong> @lang('proxies::phrases.Были некоторые проблемы с вашим вводом').<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {!! Form::open(['route' => 'port.store', 'data-fetch' => 'none', 'method' => 'POST']) !!}
    <div class="block-background basement-form">
        <div class="row">
            <div class="field">
                <div class="title-field">@lang('proxies::phrases.Наименование порта'):</div>
                {!! Form::text('name', null, ['placeholder' => trans('proxies::phrases.Наименование'), 'class' => 'input-text']) !!}
            </div>
        </div>
        <div class="row">
            <div class="field">
                <div class="title-field">@lang('proxies::phrases.Сетевой интерфейс'):</div>
                {!! Form::select('interface', $freeInterface, 2, ['class' => 'select-multiple', 'required' => 'required']) !!}
            </div>
            <div class="field">
                <div class="title-field">@lang('proxies::phrases.Модель модема'):</div>
                {!! Form::select('model', $modelModems, 2, ['class' => 'select-multiple']) !!}
            </div>
            <div class="field">
                <div class="title-field">@lang('proxies::phrases.Сервер'):</div>
                {!! Form::select('server', $servers, 0, array('class' => 'select-multiple')) !!}
            </div>
        </div>
        <div class="row">
            <div class="field">
                <div class="title-field">​@lang('proxies::phrases.Тип смены ip'):</div>
                {!! Form::select(
                    'changeip',
                    ['time' => trans('proxies::phrases.По времени'), 'link' => trans('proxies::phrases.По ссылке'), 'time_link' => trans('proxies::phrases.По времени и ссылке')],
                    'time_link',
                    ['class' => 'select-multiple'],
                ) !!}
            </div>
            <div class="field">
                <div class="title-field">@lang('proxies::phrases.Блокировать изменение типа IP'):</div>
                {!! Form::select('locked_ip_type_change', ['0' => trans('proxies::phrases.Нет'), '1' => trans('proxies::phrases.Да')], null, ['class' => 'input-text']) !!}
            </div>
            <div class="field">
                <div class="title-field">@lang('proxies::phrases.Время смены'):</div>
                {!! Form::text('reconnect_interval', 120, ['placeholder' => '0', 'class' => 'input-text']) !!}
            </div>
        </div>
        <div class="row">
            <div class="field">
                <div class="title-field">@lang('proxies::phrases.Доступ к порту'):</div>
                {!! Form::select('user', $users, 2, [
                    'multiple' => 'multiple',
                    'name' => 'userapi[]',
                    'class' => 'select-multiple',
                ]) !!}
            </div>
        </div>
        <div class="row">
            <div class="field">
                <div class="title-field">@lang('proxies::phrases.Тип порта'):</div>
                {!! Form::select('typepay', ['private' =>  trans('proxies::phrases.Приватный'), 'general' => trans('proxies::phrases.Общий')], 2, ['class' => 'input-text']) !!}
            </div>
            <div class="field">
                <div class="title-field">@lang('proxies::phrases.Количество человек на порт'):</div>
                {!! Form::number('usercount', 1, ['readonly' => 'readonly', 'placeholder' => '0', 'class' => 'input-text']) !!}
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
        // Получение элементов select
    const serverSelect = document.querySelector('select[name="server"]');
    const otherSelectInterface = document.querySelector('select[name="interface"]');
    const otherSelectModem = document.querySelector('select[name="model"]');

    // Функция, которая обновляет другой select на основе полученных данных
    function updateOtherSelect(interface,model) {
    // очистить другой select
    otherSelectInterface.innerHTML = '';
    otherSelectModem.innerHTML = '';

    // добавить новые пункты в другой select
    // if (Array.isArray(interface)) {
        for (const key in interface) {
            const option = document.createElement('option');
            option.value = key;
            option.text = key;
            otherSelectInterface.appendChild(option);
        };
        for (const key in model) {
            const option = document.createElement('option');
            option.value = key;
            option.text = model[key];
            otherSelectModem.appendChild(option);
        };
    // } else {
    // console.error('interfaces is not an array');
    // }
    }

    // Событие onchange для server select
    serverSelect.onchange = function() {
    // Получение выбранного значения
    const selectedOption = this.options[this.selectedIndex].value;

    // Отправка запроса fetch на сервер с выбранным значением
    fetch(`/admin/fetch/server/getintmod/${selectedOption}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
    })
        .then(response => response.json())
        .then(data => {
        // Обновление другого select на основе полученных данных
        // console.log(data.interface);
        let interface = data.interface,
            model = data.model;
        updateOtherSelect(interface,model);
        })
        .catch(error => {
        console.error('Ошибка:', error);
        });
    }
</script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('select[name="typepay"]').addEventListener('change', function(e) {

                if (e.target.value == 'general') {
                    document.querySelector('input[name="usercount"]').readOnly = false;
                } else {
                    document.querySelector('input[name="usercount"]').readOnly = true;
                }
            });
        });
    </script>
@endsection
