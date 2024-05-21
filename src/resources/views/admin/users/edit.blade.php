@extends('proxies::admin.app')

@section('content')
{{-- @dd($userHistoryOperation) --}}

    <div class="header-page">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Редактирование пользователя')</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('users.index') }}"><i class="bx bx-left-arrow-alt icon"></i> @lang('proxies::phrases.Назад')</a>
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

    <div class="grid-block bottom-indent">
        {!! Form::model($user, ['method' => 'PATCH', 'data-fetch' => 'none', 'route' => ['users.update', $user->id]]) !!}
        <div class="block-background basement-form">
            <div class="row">
                <div class="title-block">
                    <h2>@lang('proxies::phrases.Основная информация')</h2>
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Имя'):</div>
                    {!! Form::text('name', null, ['placeholder' => trans('proxies::phrases.Имя'), 'class' => 'input-text']) !!}
                </div>
                <div class="field">
                    <div class="title-field">Email:</div>
                    {!! Form::text('email', null, ['placeholder' => 'Email', 'class' => 'input-text']) !!}
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Пароль'):</div>
                    {!! Form::password('password', ['placeholder' => trans('proxies::phrases.Пароль'), 'class' => 'input-text']) !!}
                </div>
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Повтор пароля'):</div>
                    {!! Form::text('confirm-password', null, ['placeholder' => trans('proxies::phrases.Повторите пароль'), 'class' => 'input-text']) !!}
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Имя в Кракене'):</div>
                    {!! Form::text('kraken_username', null, ['placeholder' => trans('proxies::phrases.Имя в Кракене'), 'class' => 'input-text']) !!}
                </div>
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Пароль в Кракене'):</div>
                    {!! Form::text('kraken_password', null, ['placeholder' => trans('proxies::phrases.Пароль в Кракене'), 'class' => 'input-text']) !!}
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.ID в Кракене'):</div>
                    {!! Form::text('id_kraken', null, ['placeholder' => trans('proxies::phrases.ID в Кракене'), 'class' => 'input-text']) !!}
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <div class="title-field">Telegram ID:</div>
                    {!! Form::text('telegram_auth_id', null, ['placeholder' => 'Telegram ID', 'class' => 'input-text']) !!}
                </div>
                <div class="field">
                    <div class="title-field">Telegram Chat-ID:</div>
                    {!! Form::text('telegram_chat_id', null, ['placeholder' => 'Telegram ID', 'class' => 'input-text']) !!}
                </div>
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Имя пользователя в Telegram'):</div>
                    {!! Form::text('telegram_name', null, ['placeholder' => trans('proxies::phrases.Имя пользователя в Telegram'), 'class' => 'input-text']) !!}
                </div>
            </div>
            <div class="row">
                <div class="field list">
                    <div class="title-field">@lang('proxies::phrases.Роль'):</div>
                    {!! Form::select('roles[]', $roles, $userRole, ['class' => 'select-multiple', 'multiple']) !!}
                </div>
            </div>
        </div>
        <div class="footer-block">
            <button type="submit" class="btn btn-primary">@lang('proxies::phrases.Сохранить')</button>
        </div>
        {!! Form::close() !!}

        <form action="{{ route('balanceChanges') }}" method="POST">
            @csrf
            <div class="block-background basement-form">
                <div class="row">
                    <div class="title-block">
                        <h2>@lang('proxies::phrases.Пополнить/списать баланс')</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="field">
                        <div class="title-field">@lang('proxies::phrases.Текущий баланс'): {{ $user->balance == 0 ? 0 : $user->balance }} $</div>
                        <div class="title-field">@lang('proxies::phrases.Реферальный баланс'):
                            {{ $user->referral_balance == 0 ? 0 : $user->referral_balance }} $</div>
                    </div>
                </div>
                <div class="row">
                    <div class="field">
                        <div class="title-field">@lang('proxies::phrases.Реферальный баланс')</div>
                        <div class="wrap-input numberFormat">
                            <span>$</span><input type="number" placeholder="0" name="referral_amount" value=""
                                class="input-text">
                            <input type="hidden" name="id" class="input-text" value="{{ $user->id }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="field">
                        <div class="title-field">@lang('proxies::phrases.Сумма')</div>
                        <div class="wrap-input numberFormat">
                            <span>$</span><input type="number" placeholder="0" name="amount" value=""
                                class="input-text">
                            <input type="hidden" name="id" class="input-text" value="{{ $user->id }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="field">
                        <div class="title-field">@lang('proxies::phrases.Тип операции')</div>
                        <select name="type" class="select-multiple">
                            <option value="plus">@lang('proxies::phrases.Пополнить')</option>
                            <option value="minus">@lang('proxies::phrases.Списать')</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="field">
                        <div class="title-field">@lang('proxies::phrases.Комментарий')</div>
                        <textarea name="notes" rows="5" class="input-text"></textarea>
                    </div>
                </div>
            </div>
            <div class="footer-block">
                <button type="submit" class="btn btn-primary">@lang('proxies::phrases.Выполнить')</button>
            </div>
        </form>
    </div>
    <div class="indent"></div>
    <div class="block-background bottom-indent">
        <div class="row">
            <div class="title-block">
                <h2>@lang('proxies::phrases.Прокси пользователя')</h2>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr class="tr-name">
                    <th>ID</th>
                    <th>@lang('proxies::phrases.Тип прокси')</th>
                    <th>@lang('proxies::phrases.Состояние прокси')</th>
                    <th>IP</th>
                    <th>@lang('proxies::phrases.Страна')</th>
                    <th>@lang('proxies::phrases.Сервер')</th>
                    <th>@lang('proxies::phrases.Порт')</th>
                    <th>@lang('proxies::phrases.Дата окончания')</th>
                    <th>@lang('proxies::phrases.Действие')</th>
                </tr>
            </thead>
            <tbody>
                @if ($user->proxys->isNotEmpty())
                    @foreach ($user->proxys as $proxy)
                        <tr id="proxy_{{ $proxy->id }}">
                            <td>{{ $proxy->id }}</td>
                            <td>{{ $proxy->type }}</td>
                            <td>{{ $proxy->active == 1 ? trans('proxies::phrases.Активный') : trans('proxies::phrases.Не активный') }}</td>
                            <td>{{ $proxy->type }}://{{ $proxy->user->kraken_username }}:{{ $proxy->password_user_proxy_kraken ?: $proxy->user->kraken_username }}@<?php echo parse_url($proxy->modem->server->data['url'], PHP_URL_HOST); ?>:{{ $proxy->number_proxy }}
                            </td>
                            <td>{{ $proxy->modem->server->country }}</td>
                            <td>{{ $proxy->modem->server->name }}</td>
                            <td>{{ $proxy->modem->name }}</td>
                            <td>{{isset($proxy->date_end) ? $proxy->date_end : ''}}</td>
                            <td class="dayst">

                                <a class="btn btn-action" href="{{ route('proxy.edit', $proxy->id) }}"><i
                                        class="fa-regular fa-pen-to-square"></i></a>

                                <button type="submit" data-title="@lang('proxies::phrases.Вы точно хотите удалить прокси')?"
                                    data-action="{{ route('proxy.destroy', $proxy->id) }}" data-modal="del"
                                    data-fetch="yes" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>

                            </td>
                        </tr>
                    @endforeach
                @else
                    <td colspan="9" class="absent">@lang('proxies::phrases.Записи отсутствуют')</td>
                @endif
            </tbody>
        </table>
    </div>
    <div class="block-background">
        <div class="row">
            <div class="title-block">
                <h2>@lang('proxies::phrases.История операций пользователя')</h2>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr class="tr-name">
                    <th>№</th>
                    <th>@lang('proxies::phrases.Тип операции')</th>
                    <th>@lang('proxies::phrases.Сумма')</th>
                    <th>@lang('proxies::phrases.Комментарий')</th>
                    <th>@lang('proxies::phrases.Статус')</th>
                    <th>@lang('proxies::phrases.Дата')</th>
                </tr>
            </thead>
            <tbody>
                @if ($userHistoryOperation->isNotEmpty())
                    @foreach ($userHistoryOperation as $operation)
                        @if ($operation->type != 'buySub')
                            <tr>
                                <td>{{ $operation->id }}</td>
                                <td>{{ $operation->type == 'plus' ? trans('proxies::phrases.Пополнение') : ($operation->type == 'minus' ? trans('proxies::phrases.Списание') : trans('proxies::phrases.Другая')) }}
                                </td>
                                <td>{{ $operation->type == 'plus' ? '+' : ($operation->type == 'minus' ? '-' : '') }}{{ $operation->amount }}
                                </td>
                                <td>{{ $operation->notes }}</td>
                                <td><img
                                        src="/assets/img/{{ $operation->type == 'plus' ? 'iconoir_plus.svg' : ($operation->type == 'minus' ? 'minus.svg' : ($operation->type == 'buySub' ? 'iconoir_plus.svg' : '')) }}" />
                                </td>
                                <td>{{ $operation->created_at }}</td>
                            </tr>
                        @elseif($operation->status != null)
                            <tr>
                                <td>{{ $operation->id }}</td>
                                <td>{{ $operation->type == 'plus' ? trans('proxies::phrases.Пополнение') : ($operation->type == 'minus' ? trans('proxies::phrases.Списание') : trans('proxies::phrases.Другая')) }}
                                </td>
                                <td>{{ $operation->type == 'plus' ? '+' : ($operation->type == 'minus' ? '-' : '') }}{{ $operation->amount }}
                                </td>
                                <td>{{ $operation->notes }}</td>
                                <td><img
                                        src="/assets/img/{{ $operation->type == 'plus' ? 'iconoir_plus.svg' : ($operation->type == 'minus' ? 'minus.svg' : ($operation->type == 'buySub' ? 'iconoir_plus.svg' : '')) }}" />
                                </td>
                                <td>{{ $operation->created_at }}</td>
                            </tr>
                        @endif
                    @endforeach
                @else
                    <td colspan="5" class="absent">@lang('proxies::phrases.Записи отсутствуют')</td>
                @endif
            </tbody>
        </table>
    </div>
    <div class="indent"></div>

@endsection
