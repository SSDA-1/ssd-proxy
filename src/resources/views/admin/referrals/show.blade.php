@extends('admin.app')

@section('content')
    @php
        $status = $withdrawalrequest->status == 0 ? trans('phrases.Активна') : ($withdrawalrequest->status == 1 ? trans('phrases.В работе') : trans('phrases.Выполнена'));
    @endphp

    <div class="header-page">
        <div class="title-page">
            <h2>@lang('phrases.Заявка на вывод') #{{ $withdrawalrequest->id }}</h2>
        </div>
        <div class="buttons">
            @if ($withdrawalrequest->status != 2)
                <a class="btn btn-success" href="{{ route('withdrawalrequest.edit', $withdrawalrequest->id) }}"
                    style="background-color: #45cd5a;"><i class="bx bx-check-double icon"></i> @lang('phrases.Выполнено')</a>
            @endif
            <a class="btn btn-success" href="{{ route('withdrawalrequest.index') }}"><i class="bx bx-left-arrow-alt icon"></i>
                @lang('phrases.Назад')</a>
        </div>
    </div>

    <div class="block-background">
        <div class="row">
            <div class="field">
                <div class="title-field">@lang('phrases.Пользователь'):</div>
                <div class="input-text"><a
                        href="/users/{{ $withdrawalrequest->user->id }}/edit">{{ $withdrawalrequest->user->name }}</a></div>
            </div>
            <div class="field">
                <div class="title-field">@lang('phrases.Дата заявки'):</div>
                <div class="input-text">
                    @if (!$withdrawalrequest->execution_date)
                        {{ $status }}
                    @else
                        {{ \Carbon\Carbon::parse($withdrawalrequest->execution_date)->format('d.m.Y') }}
                    @endif
                </div>
            </div>

            <div class="field">
                <div class="title-field">@lang('phrases.Дата выполнения'):</div>
                <div class="input-text">
                    @if (!$withdrawalrequest->execution_date)
                        {{ $status }}
                    @else
                        {{ \Carbon\Carbon::parse($withdrawalrequest->execution_date)->format('H:i:s') }}
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="field">
                <div class="title-field">@lang('phrases.Сумма'):</div>
                <div class="input-text">{{ $withdrawalrequest->amount }} $</div>
            </div>
            <div class="field">
                <div class="title-field">@lang('phrases.Способ'):</div>
                <div class="input-text">
                    @if($withdrawalrequest->name_ecash == 'Карта')@lang('phrases.Карта')
                    @elseif($withdrawalrequest->name_ecash == 'USDT')USDT TRC 20
                    @elseif($withdrawalrequest->name_ecash == 'Capitalist')Capitalist
                    @else {{$withdrawalrequest->name_ecash}}
                    @endif
                    {{ $withdrawalrequest->name_ecash }}</div>
            </div>
            <div class="field">
                <div class="title-field">@lang('phrases.Статус'):</div>
                <div class="input-text">{{ $status }}</div>
            </div>
        </div>
        <div class="row full" style="max-height: calc(100% - 95px);">
            <div class="field">
                <div class="title-field">@lang('phrases.Реквизиты'):</div>
                <div class="input-text full">{{ $withdrawalrequest->name_ecash ?: '' }}
                    {{ $withdrawalrequest->card_number }}</div>
            </div>
        </div>

    </div>
@endsection
