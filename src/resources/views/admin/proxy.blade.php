@extends('proxies::admin.app')

@section('content')

    <div class="header-page">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Прокси')</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('proxySetting') }}">@lang('proxies::phrases.Настройки')</a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="title-block proxy">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Список портов')</h2>
        </div>
        <div class="buttons" style="display: flex;">
            <a class="btn btn-success" data-title="@lang('proxies::phrases.Вы точно хотите импортировать порты с системы Кракен? Выберите сервер') "
                data-fetch="yes" data-action="{{ route('exportPorts') }}" data-modal="exportports"
                data-servers="{{ $servers }}"
                style="width: 100%;margin-right: 15px;letter-spacing: 1.15px;font-size: 14px;padding: 10px 25px;"
                href="#"><span>@lang('proxies::phrases.Импортировать порты')</span></a>
            <a class="btn btn-success" href="{{ route('port.create') }}"><span>+</span></a>
        </div>
    </div>



    <div class="tabs bottom-indent">

        <div class="tabs__nav background">
            @foreach ($serversId as $key => $item)
                <button class="tabs__btn {{ $key === 0 ? 'tabs__btn_active' : '' }}">{{ $item->name }}</button>
            @endforeach

        </div>

        <div class="tabs__content block-background">

            <form id="search-form" class="padding-20" style="padding-bottom: 0">
                <div class="row" style="justify-content: space-between">
                    <input type="text" name="query" placeholder="@lang('proxies::phrases.Поиск пользователей')" class="input-text"
                        style="max-width: 90%">
                    <button type="submit" class="btn btn-primary">@lang('proxies::phrases.Искать')</button>
                </div>
            </form>
            @foreach ($serversId as $key => $item)
                <div class="tabs__pane {{ $key === 0 ? 'tabs__pane_show' : '' }} flex-block h-500">
                    <table class="table table-bordered padding-20">
                        <thead>
                            <tr class="tr-name">
                                <th>ID</th>
                                <th>@lang('proxies::phrases.Название')</th>
                                <th>@lang('proxies::phrases.Статус')</th>
                                <th>@lang('proxies::phrases.Пользователи')</th>
                                <th>Telegram</th>
                                <th>@lang('proxies::phrases.Дата окончания')</th>
                                {{-- <th>Тип сети</th> --}}
                                {{-- <th>TCP отпечаток</th> --}}
                                <th>@lang('proxies::phrases.Тип смены ip')</th>
                                <th>@lang('proxies::phrases.Интервал смены ip')</th>
                                <th>@lang('proxies::phrases.Тип')</th>
                                <th>@lang('proxies::phrases.Макс. человек')</th>
                                <th>@lang('proxies::phrases.Действие')</th>
                            </tr>
                        </thead>
                        <tbody class="proxy-table-body" data-server="{{ $item->id }}">
                            @forelse ($ports->sortBy('id_kraken') as $port)
                                @if ($item->id == $port->server_id)
                                    @php
                                        $users = App\Models\User::whereIn('id_kraken', $port->users)->get(); // Получение пользователей по идентификаторам
                                    @endphp
                                    <tr id="modem_{{ $port->id }}">
                                        <td>{{ $port->id_kraken }}</td>
                                        <td>{{ $port->name }}</td>
                                        <td>{{ $port->active == 1 ? trans('proxies::phrases.Активный') : trans('proxies::phrases.Не активный') }}</td>
                                        <td>
                                            @forelse ($port->proxys as $proxy)
                                                @if ($loop->iteration % 2 == 0)
                                                    <a
                                                        href="/users/{{ $proxy->user->id }}/edit">{{ $proxy->user->name }}</a><br>
                                                @endif
                                            @empty
                                                @lang('proxies::phrases.Порт пустой')
                                            @endforelse
                                        </td>
                                        <td>
                                            @forelse ($port->proxys as $proxy)
                                                @if ($loop->iteration % 2 == 0)
                                                    <a href="https://t.me/{{ str_replace('@', '', $proxy->user->telegram_name) }}"
                                                        target="_blank">{{ $proxy->user->telegram_name }}</a> <br>
                                                @endif
                                            @empty
                                            @endforelse
                                        </td>
                                        <td>
                                            @forelse ($port->proxys as $proxy)
                                                @if ($loop->iteration % 2 == 0)
                                                    <span>{{ isset($proxy->date_end) ? $proxy->date_end : '' }}</span> <br>
                                                @endif
                                            @empty
                                            @endforelse
                                        </td>
                                        <td>{{ $port->reconnect_type }}</td>
                                        <td>{{ $port->reconnect_interval }} @lang('proxies::phrases.сек')</td>
                                        <td>{{ $port->type_pay == 'private' ? trans('proxies::phrases.Приватный') : trans('proxies::phrases.Общий') }}</td>
                                        <td>{{ $port->proxycount }} / {{ $port->max_users }}</td>
                                        <td class="dayst">
                                            <a class="btn btn-action" href="{{ route('port.edit', $port->id) }}"><i
                                                    class="fa-regular fa-pen-to-square"></i></a>
                                            <button type="submit" class="btn btn-danger"><i
                                                    class="fa-solid fa-bullseye"></i></button>
                                            <button type="submit"
                                                data-title="@lang('proxies::phrases.Вы точно хотите удалить Порт с сайта')? <br> Ps: @lang('proxies::phrases.В системе Кракен его нужно удалить вручную')"
                                                data-fetch="yes" data-action="{{ route('port.destroy', $port->id) }}"
                                                data-modal="del" class="btn btn-danger"><i
                                                    class="fa-solid fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="13" class="absent">@lang('proxies::phrases.Записи отсутствуют')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>

    </div>



    <div class="title-block proxy">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Список прокси')</h2>
        </div>
        <div class="buttons" style="display: flex;flex: 1;justify-content: flex-end;">
            <a class="btn btn-success edit-button" data-title="<b>@lang('proxies::phrases.Добавление времени')</b>" data-fetch="yes"
                data-action="{{ route('exportProxy') }}" data-modal="editcheckproxy"
                style="display: none;width: auto;margin-right: 15px;letter-spacing: 1.15px;font-size: 14px;padding: 10px 25px;background-color: #557dfc;">@lang('proxies::phrases.Добавить')
                время</a>
            <a class="btn btn-success"
                data-title="<b>@lang('proxies::phrases.Перед импортом прокси импортируйте порты, настройте их тип (Общий/Приватный) и укажите количество человек на порт').</b><br> @lang('proxies::phrases.Вы точно хотите импортировать прокси с системы Кракен')?"
                data-fetch="yes" data-action="{{ route('exportProxy') }}" data-servers="{{ $servers }}"
                data-modal="exportproxy"
                style="width: auto;margin-right: 15px;letter-spacing: 1.15px;font-size: 14px;padding: 10px 25px;"
                href="#"><span>@lang('proxies::phrases.Импортировать прокси')</span></a>
            <a class="btn btn-success" href="{{ route('proxy.create') }}"><span>+</span></a>
        </div>
    </div>

    <div class="second-tabs bottom-indent">

        <div class="tabs__nav background">
            @foreach ($serversId as $key => $item)
                <button class="tabs__btn {{ $key === 0 ? 'tabs__btn_active' : '' }}">{{ $item->name }}</button>
            @endforeach
        </div>
        <div class="tabs__content block-background">
            @foreach ($serversId as $key => $item)
                <div class="tabs__pane {{ $key === 0 ? 'tabs__pane_show' : '' }} flex-block h-500">
                    <table class="table table-bordered padding-20">
                        <thead>
                            <tr class="tr-name">
                                <th></th>
                                <th>ID</th>
                                <th>@lang('proxies::phrases.Тип прокси')</th>
                                <th>@lang('proxies::phrases.Состояние прокси')</th>
                                <th>IP</th>
                                <th>@lang('proxies::phrases.Порт')</th>
                                <th>@lang('proxies::phrases.Пользователь')</th>
                                <th>@lang('proxies::phrases.Действителен до')</th>
                                <th>@lang('proxies::phrases.Действие')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($proxys as $proxy)
                                @if ($item->id == $proxy->modem->server_id)
                                    <tr id="proxy_{{ $proxy->id }}">
                                        <td><input type="checkbox" class="proxy-checkbox" value="{{ $proxy->id }}"></td>
                                        <td>{{ $proxy->id }}</td>
                                        <td>{{ $proxy->type }}</td>
                                        <td>{{ $proxy->active == 1 ? trans('proxies::phrases.Активный') : trans('proxies::phrases.Не активный') }}</td>
                                        <td>{{ $proxy->type }}://{{ $proxy->login_user_proxy_kraken ?: $proxy->user->kraken_username }}:{{ $proxy->password_user_proxy_kraken ?: $proxy->user->kraken_username }}@<?php echo parse_url($proxy->modem->server->data['url'], PHP_URL_HOST); ?>:{{ $proxy->number_proxy }}
                                        </td>
                                        <td>{{ $proxy->modem->name }}</td>
                                        <td><a href="/users/{{ $proxy->user->id }}/edit">{{ $proxy->user->name }}</a></td>
                                        <td>{{ $proxy->date_end }}</td>
                                        <td class="dayst">
                                            <a class="btn btn-action" href="{{ route('proxy.edit', $proxy->id) }}"><i
                                                    class="fa-regular fa-pen-to-square"></i></a>
                                            <button type="submit" data-title="@lang('proxies::phrases.Вы точно хотите удалить прокси')?"
                                                data-fetch="yes" data-action="{{ route('proxy.destroy', $proxy->id) }}"
                                                data-modal="del" class="btn btn-danger"><i
                                                    class="fa-solid fa-trash"></i></button>

                                            {{-- <button type="submit" data-title="Вы точно хотите удалить прокси?"
                                data-action="{{ route('proxy.destroy', $proxy->id) }}" data-modal="del"
                                class="btn btn-danger"><i class="fa-solid fa-trash"></i></button> --}}

                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="9" class="absent">@lang('proxies::phrases.Записи отсутствуют')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>

    </div>
    <div class="indent"></div>
@endsection
@section('script')
    <script src="/vendor/ssda-1/proxies/admin/js/tabs.js{{ '?' . time() }}"></script>
    <script>
        new ItcTabs('.second-tabs', {}, 'second-tabs');

        function checkIfAnyChecked() {
            const checkedBoxes = document.querySelectorAll('.proxy-checkbox:checked');
            const editButton = document.querySelector('.edit-button');

            if (checkedBoxes.length > 0) {
                editButton.style.display = 'block';
            } else {
                editButton.style.display = 'none';
            }
        }
        const proxyCheckboxes = document.querySelectorAll('.proxy-checkbox');
        proxyCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                checkIfAnyChecked();
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var searchForm = document.getElementById('search-form');

            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();

                var query = document.querySelector('input[name="query"]').value;

                fetch('/proxy/ports/search?query=' + encodeURIComponent(query))
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        var servers = data.servers;
                        var ports = data.ports;

                        // Iterate through each server and find the matching ports
                        servers.forEach(function(server) {
                            var serverId = server.id;
                            var portsTableBody = document.querySelector(
                                '.proxy-table-body[data-server="' + serverId + '"]');
                            if (!portsTableBody) {
                                // Handle cases where the corresponding table body is not found
                                return;
                            }

                            let userI = 0;
                            let i = 0;
                            var html = '';

                            ports.forEach(function(port) {
                                if (port.server_id === serverId) {
                                    html += '<tr id="modem_' + port.id + '">';
                                    html += '<td>' + port.id_kraken + '</td>';
                                    html += '<td>' + port.name + '</td>';
                                    var status = port.active == 1 ? 'Активный' :
                                        'Не активный';
                                    html += '<td>' + status + '</td>';

                                    var proxys = port.proxys;
                                    proxys.forEach(function(proxy) {
                                        userI++;
                                        i++;
                                        if(i % 2 === 0) {
                                        var user = proxy.user;
                                        html += '<td><a href="/users/' + user
                                            .id + '/edit">' + user.name +
                                            '</a><br></td>';}
                                    });

                                    proxys.forEach(function(proxy) {
                                        i++;
                                        if(i % 2 === 0) {
                                        var user = proxy.user;
                                        var telegramName = user.telegram_name ?
                                            user.telegram_name.replace(
                                                /@/g, '') : '';
                                        html += '<td><a href="https://t.me/' +
                                            telegramName + '">' +
                                            telegramName + '</a><br></td>';}
                                    });

                                    proxys.forEach(function(proxy) {
                                        i++;
                                        if(i % 2 === 0) {
                                        html += '<td><span>' + proxy.date_end +
                                            '</span><br></td>';}
                                    });

                                    html += '<td>' + port.reconnect_type + '</td>';
                                    html += '<td>' + port.reconnect_interval + 'сек' +
                                        '</td>';
                                    var type = port.type_pay == 'private' ?
                                        'Приватный' : 'Общий';
                                    html += '<td>' + type + '</td>';

                                    html += '<td>' + userI/2 + '/' + port
                                        .max_users + '</td>';

                                    html += '<td class="dayst">\
                                        <a class="btn btn-action" href="port/' + port.id + '/edit"><i\
                                    class="fa-regular fa-pen-to-square"></i></a>\
                                    <button type="submit" class="btn btn-danger"><i class="fa-solid fa-bullseye"></i></button>\
                                    </td>';

                                    html += '</tr>';
                                }
                            });

                            portsTableBody.innerHTML = html;
                        });
                    })
                    .catch(function(error) {
                        console.error('Ошибка:', error);
                    });
            });
        });
    </script>
@endsection
