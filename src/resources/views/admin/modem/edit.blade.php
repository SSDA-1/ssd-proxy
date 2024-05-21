@extends('proxies::admin.app')

@section('content')
<div class="header-page">
    <div class="title-page">
        <h2>@lang('proxies::phrases.Редактирование порта') #{{ $port->id_kraken }}</h2>
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

{{-- {!! Form::open(array('route' => ['port.update', $port->id_kraken],'data-fetch' => 'none','method'=>'PATCH')) !!} --}}
{!! Form::open(array('route' => ['port.update', $port->id],'data-fetch' => 'none','method'=>'PATCH')) !!}

<div class="block-background basement-form">
    <div class="row">
        <div class="field">
            <div class="title-field">@lang('proxies::phrases.Наименование порта'):</div>
            {!! Form::text('name', $port->name, array('placeholder' => trans('proxies::phrases.Наименование'), 'class' => 'input-text')) !!}
        </div>
    </div>
    <div class="row">
        <div class="field">
            <div class="title-field">@lang('proxies::phrases.Сетевой интерфейс'):</div>
            {!! Form::select('interface', $freeInterface, 1, array('class' => 'select-multiple')) !!}
        </div>
        <div class="field">
            <div class="title-field">@lang('proxies::phrases.Модель модема'):</div>
            {!! Form::select('model', $modelModems, $port->type, array('class' => 'select-multiple')) !!}
        </div>
        <div class="field">
            <div class="title-field">@lang('proxies::phrases.Сервер'):</div>
            {!! Form::select('server', $servers, $port->server_id, array('class' => 'select-multiple')) !!}
        </div>
    </div>
    <div class="row">
        <div class="field">
            <div class="title-field">​@lang('proxies::phrases.Тип смены ip'):</div>
            {!! Form::select('changeip', ['time' => trans('proxies::phrases.По времени'), 'link' => trans('proxies::phrases.По ссылке'), 'time_link' => trans('proxies::phrases.По времени и ссылке')], $port->reconnect_type, array('class' => 'select-multiple')) !!}
        </div>
        <div class="field">
            <div class="title-field">@lang('proxies::phrases.Блокировать изменение типа IP'):</div>
            {!! Form::select('locked_ip_type_change', ['0' => trans('proxies::phrases.Нет'), '1' => trans('proxies::phrases.Да')], $port->locked_ip_type_change, ['class' => 'input-text']) !!}
        </div>
        <div class="field">
            <div class="title-field">@lang('proxies::phrases.Время смены (в секундах)'):</div>
            {!! Form::text('reconnect_interval', $port->reconnect_interval, ['placeholder' => '0', 'class' => 'input-text']) !!}
        </div>
    </div>
    <div class="row">
        <div class="field">
            <div class="title-field">@lang('proxies::phrases.Доступ к порту'):</div>
            {!! Form::select('user[]', $users, $port->users, array('multiple'=>'multiple', 'name'=>'userapi[]', 'class' => 'select-multiple')) !!}
        </div>
    </div>
    <div class="row">
        <div class="field">
            <div class="title-field">@lang('proxies::phrases.Тип порта'):</div>
            {!! Form::select('typepay', ['private' => trans('proxies::phrases.Приватный'), 'general' => trans('proxies::phrases.Общий')], $port->type_pay, array('class' => 'input-text')) !!}
        </div>
        <div class="field">
            <div class="title-field">@lang('proxies::phrases.Количество человек на порт'):</div>
            @if ($port->type_pay == 'general')
            {!! Form::number('usercount', $port->max_users, array('placeholder' => '0','class' => 'input-text')) !!}
            @else
            {!! Form::number('usercount', $port->max_users, array('readonly' => 'readonly', 'placeholder' => '0','class' => 'input-text')) !!}
            @endif
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
const serverSelect = document.querySelector('select[name="server"]');
const otherSelectInterface = document.querySelector('select[name="interface"]');
const otherSelectModem = document.querySelector('select[name="model"]');

function updateOtherSelect(interface,model) {
otherSelectInterface.innerHTML = '';
otherSelectModem.innerHTML = '';


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
        document.querySelector('select[name="typepay"]').addEventListener('change', function (e) {
            if (e.target.value == 'general') {
                document.querySelector('input[name="usercount"]').readOnly = false;
            }else{
                document.querySelector('input[name="usercount"]').readOnly = true;
            }
        });
    });
</script>
@endsection
