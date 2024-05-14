@extends('admin.app')

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>{{ __('Домашняя страница') }}</h2>
            {{-- <div class="subtitle-page">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </div> --}}
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    {{-- <div class="grid-block grid-column-big fr2-px300"> --}}
    <div class="flex-block">
        <div class="block-background max-width-750 box-column-2">
            @include('admin.layouts.chart')
        </div>

        <div class="flex-block-column width-300">
            <div class="block-background">
                <div class="row">
                    <div class="field">
                        <h3>@lang('proxies::phrases.Информация о подписке')</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="field">
                        <div><b>@lang('proxies::phrases.Подписка'): </b> {{ $nameOfSubscription }}</div>
                        <div><b>@lang('proxies::phrases.Активна'): </b> @lang('proxies::phrases.Бессрочно')</div>
                    </div>
                </div>
                <div class="row">
                    <div class="field">
                        <a class="btn btn-primary">@lang('proxies::phrases.Продлить')</a>
                    </div>
                </div>
            </div>

            <div class="block-background"> 
                <div class="row">
                    <div class="field">
                        <h3>@lang('proxies::phrases.Статистика по портам/прокси')</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="field">
                        <div><b>@lang('proxies::phrases.Серверов'):</b> {{ $countKraken }} / {{ $totalAmountKraken }}</div>
                        <div><b>@lang('proxies::phrases.Портов'):</b> {{ $countModems }} / @lang('proxies::phrases.Не ограничено')</div>
                        <div><b>@lang('proxies::phrases.Портов занято'):</b> {{ $countModemsByFilledUsers }} / {{ $countModems }}</div>
                        <div><b>@lang('proxies::phrases.Всего пар прокси'): </b> {{ $countProxies / 2 }}</div>
                        <div><b>@lang('proxies::phrases.Пар прокси на паузе'): </b> {{ $getProxiesPause / 2 }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="field">
                        <a href="{{ route('proxy.index') }}" class="btn btn-primary">@lang('proxies::phrases.Список портов')</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="block-background box-column-2 max-width-750 width-100 h-500">
            <div class="title-block">
                <h3>@lang('proxies::phrases.Статистика по продажам прокси')</h3>
                <div class="row">
                    <div class="field">
                        <button id="btnYear" type="button" class="btn btn-primary">@lang('proxies::phrases.По годам')</button>
                    </div>
                    <div class="field">
                        <button id="btnMonth" type="button" class="btn btn-primary">@lang('proxies::phrases.По месяцам')</button>
                    </div>
                    <div class="field">
                        <button id="btnDay" type="button" class="btn btn-primary">@lang('proxies::phrases.По дням')</button>
                    </div>
                </div>
            </div>
            <div class="table table-bordered" id="table-sell">
                <div class="row table-row">
                    <div class="cell">ID</div>
                    <div class="cell">@lang('proxies::phrases.Дата и время')</div>
                    <div class="cell">@lang('proxies::phrases.Кол-во продаж')</div>
                    <div class="cell">@lang('proxies::phrases.Сумма')</div>
                    <div class="cell">@lang('proxies::phrases.Действие')</div>
                </div>

                @foreach ($statistics as $key => $statistic)
                    {{-- Раскрывающийся список --}}
                    <div class="row table-row closeOpen" data-item="{{ $key }}">
                        <div class="cell"></div>
                        <div class="cell">{{ $statistic['date'] }}</div>
                        <div class="cell">{{ $statistic['count'] }}</div>
                        <div class="cell">{{ $statistic['sum'] }}</div>
                        <div class="cell closeOpen">
                            <i class='bx bx-chevron-down open'></i>
                            <i class='bx bx-chevron-up close no'></i>
                        </div>
                    </div>
                    {{-- //Раскрывающийся список --}}

                    {{-- Конечный элемент --}}
                    <table class="table table-bordered" data-id="{{ $key }}">
                        <thead>
                            <tr class="tr-name">
                                <th>ID</th>
                                <th>@lang('proxies::phrases.Кто купил')</th>
                                <th>@lang('proxies::phrases.Во сколько')</th>
                                <th>@lang('proxies::phrases.Кол-во')</th>
                                <th>@lang('proxies::phrases.Сумму')</th>
                                <th>@lang('proxies::phrases.Срок')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($statistic['day'] as $day)
                                <tr>
                                    <td>{{ $day['id'] }}</td>
                                    <td>{{ $day['user'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($day['created_at'])->format('d.m.y, H:i:s') }}</td>
                                    <td>{{ $day['quantity'] }}</td>
                                    <td>{{ $day['amount'] }}</td>
                                    <td>{{ $day['duration'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
                {{-- //Конечный элемент --}}
            </div>
        </div>

        <div class="block-background box-column-2 max-width-750 width-100">
            <div class="title-block">
                <h3>@lang('proxies::phrases.Статистика по серверам')</h3>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr class="tr-name">
                        <th>ID</th>
                        <th>@lang('proxies::phrases.Название сервера')</th>
                        <th>@lang('proxies::phrases.Страна сервера')</th>
                        <th>@lang('proxies::phrases.Занято/Свободно')</th>
                        <th>@lang('proxies::phrases.Статус')</th>
                    </tr>
                </thead>
                <tbody>
                    @inject('ServerStatusService', 'App\Service\ServerStatusService')
                    @forelse($servers as $server)
                        @php
                            $countries = Countries::getList('en', 'php', 'cldr');
                            $regionCode = $server->country;
                            $filteredCountries = array_filter($countries, function ($country) use ($regionCode) {
                                return isset($country['name']) && $country['name'] == $regionCode;
                            });
                            $countrySearch = reset($filteredCountries);

                            $countModems = App\Models\Modem::where('server_id', '=', $server->id)->count();
                            $countModemsFull = App\Models\Modem::where('server_id', '=', $server->id)
                                ->get()
                                ->where('proxyfull', '==', 'full')
                                ->count();
                        @endphp
                        <tr>
                            <td>{{ $server->id }}</td>
                            <td>{{ $server->name }}</td>
                            <td>{{ $countrySearch['citizenship'] }}</td>
                            <td>{{ $countModemsFull }}/{{ $countModems }}</td>
                            @if ($ServerStatusService->check($server))
                                <td class="on">Online</td>
                            @else
                                <td class="off">Offline</td>
                            @endif

                        </tr>
                    @empty
                        <tr>
                            <td>@lang('proxies::phrases.Нет ни одного сервера')</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
    <div class="indent"></div>
@endsection
@section('script')
    <script>
        var closeOpenButtons;

        function btnsList() {
            closeOpenButtons = document.querySelectorAll(".closeOpen");

            closeOpenButtons.forEach(button => {
                button.addEventListener("click", () => {
                    const dataId = button.getAttribute("data-item");
                    const table = document.querySelector(`.table[data-id="${dataId}"]`);

                    if (table) {
                        if (table.style.display === "none" || table.style.display === "") {
                            table.style.display = "table";
                            button.classList.add('open');
                        } else {
                            table.style.display = "none";
                            button.classList.remove('open');
                        }
                    }
                });
            });
        }

        btnsList();
    </script>
    <script>
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.getElementById('btnDay').addEventListener('click', function() {
            fetch("/fetch/statisticsell/day", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': token
                }
            })
                .then(response => response.json())
                .then(data => {
                    createTableDay(data);
                })
        });

        document.getElementById('btnMonth').addEventListener('click', function() {
            fetch("/fetch/statisticsell/month", {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
            })
            .then(response => response.json())
            .then(data => {
                createTableMonth(data);
            })
        });

        document.getElementById('btnYear').addEventListener('click', function() {
            fetch("/fetch/statisticsell/year", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': token
                }
            })
            .then(response => response.json())
            .then(data => {
                createTableYear(data);
            })
        });

        function createTableYear(data) {
            let container = document.querySelector('#table-sell');
            container.innerHTML = '';
            let html = `<div class="row table-row">
                            <div class="cell">ID</div>
                            <div class="cell">@lang('proxies::phrases.Дата и время')</div>
                            <div class="cell">@lang('proxies::phrases.Кол-во продаж')</div>
                            <div class="cell">@lang('proxies::phrases.Сумма')</div>
                            <div class="cell">@lang('proxies::phrases.Действие')</div>
                        </div>`;

            for (const year in data) {
                for (const month in data[year]) { // Года
                    let totalSalesCountYear = 0;
                    let totalSalesSumYear = 0;
                    html += `<div class="row table-row closeOpen" data-item="${month}">
                                <div class="cell"></div>
                                <div class="cell">${month}</div>
                                <div class="cell month count">-</div>
                                <div class="cell month sum">-</div>
                                <div class="cell closeOpen">
                                    <i class='bx bx-chevron-down open'></i>
                                    <i class='bx bx-chevron-up close no'></i>
                                </div>
                            </div><div class="row data-row closeOpen table" data-id="${month}">`;
                    for (const date in data[year][month]) { // Месяца
                        const rowData = data[year][month][date];
                        let totalSalesCount = 0;
                        let totalSalesSum = 0;
                        html += `<div class="row table-row closeOpen" data-item="${date}">
                                <div class="cell"></div>
                                <div class="cell">${date}</div>
                                <div class="cell count">-</div>
                                <div class="cell sum">-</div>
                                <div class="cell closeOpen">
                                    <i class='bx bx-chevron-down open'></i>
                                    <i class='bx bx-chevron-up close no'></i>
                                </div>
                            </div><div class="row data-row closeOpen table" data-id="${date}">`;
                        for (const element in data[year][month][date]) { // Дни
                            const timeRow = data[year][month][date][element];
                            totalSalesCount += timeRow.count;
                            totalSalesSum += timeRow.sum;

                            totalSalesCountYear += timeRow.count;
                            totalSalesSumYear += timeRow.sum;

                            html += `<div class="row table-row closeOpen table" data-item="${timeRow.date}">
                                    <div class="cell"></div>
                                    <div class="cell">${timeRow.date}</div>
                                    <div class="cell">${timeRow.count}</div>
                                    <div class="cell">${timeRow.sum.toFixed(2)}</div>
                                    <div class="cell closeOpen">
                                        <i class='bx bx-chevron-down open'></i>
                                        <i class='bx bx-chevron-up close no'></i>
                                    </div>
                                </div>
                                <table class="table table-bordered" data-id="${timeRow.date}">
                                    <thead>
                                        <tr class="tr-name">
                                            <th>ID</th>
                                            <th>@lang('proxies::phrases.Кто купил')</th>
                                            <th>@lang('proxies::phrases.Во сколько')</th>
                                            <th>@lang('proxies::phrases.Кол-во')</th>
                                            <th>@lang('proxies::phrases.Сумму')</th>
                                            <th>@lang('proxies::phrases.Срок')</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                            timeRow.day.forEach(day => { // Данны за день
                                html += `<tr>
                                        <td>${day.id}</td>
                                        <td>${day.user}</td>
                                        <td>${new Date(day.created_at).toLocaleDateString('en-GB', {
                                            day: '2-digit',
                                            month: '2-digit',
                                            year: '2-digit',
                                            hour: '2-digit',
                                            minute: '2-digit',
                                            second: '2-digit'}).replace(/\//g, '.')}</td>
                                        <td>${(day.quantity != null) ? day.quantity : ""}</td>
                                        <td>${day.amount}</td>
                                        <td>${(day.duration != null) ? day.duration : ""}</td>
                                    </tr>`;
                            });
                            html += `</tbody></table>`;
                        }
                        html += `</div>`;
                        html = html.replace('<div class="cell count">-</div>', `<div class="cell">${totalSalesCount}</div>`);
                        html = html.replace('<div class="cell sum">-</div>', `<div class="cell">${totalSalesSum.toFixed(2)}</div>`);

                    }
                    html = html.replace('<div class="cell month count">-</div>', `<div class="cell">${totalSalesCountYear}</div>`);
                    html = html.replace('<div class="cell month sum">-</div>', `<div class="cell">${totalSalesSumYear.toFixed(2)}</div>`);
                    container.innerHTML += html;
                }
            }
            btnsList();
        }

        function createTableMonth(data) {
            let container = document.querySelector('#table-sell');
            container.innerHTML = '';
            let html = `<div class="row table-row">
                            <div class="cell">ID</div>
                            <div class="cell">@lang('proxies::phrases.Дата и время')</div>
                            <div class="cell">@lang('proxies::phrases.Кол-во продаж')</div>
                            <div class="cell">@lang('proxies::phrases.Сумма')</div>
                            <div class="cell">@lang('proxies::phrases.Действие')</div>
                        </div>`;

            for (const month in data) { // Года
                for (const date in data[month]) { // Месяца
                    const rowData = data[month][date];
                    let totalSalesCount = 0;
                    let totalSalesSum = 0;
                    html += `<div class="row table-row closeOpen" data-item="${date}">
                                <div class="cell"></div>
                                <div class="cell">${date}</div>
                                <div class="cell count">-</div>
                                <div class="cell sum">-</div>
                                <div class="cell closeOpen">
                                    <i class='bx bx-chevron-down open'></i>
                                    <i class='bx bx-chevron-up close no'></i>
                                </div>
                            </div><div class="row data-row closeOpen table" data-id="${date}">`;
                    for (const element in data[month][date]) { // Дни
                        const timeRow = data[month][date][element];
                        totalSalesCount += timeRow.count;
                        totalSalesSum += timeRow.sum;
                        html += `<div class="row table-row closeOpen table" data-item="${timeRow.date}">
                                    <div class="cell"></div>
                                    <div class="cell">${timeRow.date}</div>
                                    <div class="cell">${timeRow.count}</div>
                                    <div class="cell">${timeRow.sum.toFixed(2)}</div>
                                    <div class="cell closeOpen">
                                        <i class='bx bx-chevron-down open'></i>
                                        <i class='bx bx-chevron-up close no'></i>
                                    </div>
                                </div>
                                <table class="table table-bordered" data-id="${timeRow.date}">
                                    <thead>
                                        <tr class="tr-name">
                                            <th>ID</th>
                                            <th>@lang('proxies::phrases.Кто купил')</th>
                                            <th>@lang('proxies::phrases.Во сколько')</th>
                                            <th>@lang('proxies::phrases.Кол-во')</th>
                                            <th>@lang('proxies::phrases.Сумму')</th>
                                            <th>@lang('proxies::phrases.Срок')</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                        timeRow.day.forEach(day => { // Данны за день
                            html += `<tr>
                                        <td>${day.id}</td>
                                        <td>${day.user}</td>
                                        <td>${new Date(day.created_at).toLocaleDateString('en-GB', {
                                                day: '2-digit',
                                                month: '2-digit',
                                                year: '2-digit',
                                                hour: '2-digit',
                                                minute: '2-digit',
                                                second: '2-digit'}).replace(/\//g, '.')}</td>
                                        <td>${(day.quantity != null) ? day.quantity : ""}</td>
                                        <td>${day.amount}</td>
                                        <td>${(day.duration != null) ? day.duration : ""}</td>
                                    </tr>`;
                        });
                        html += `</tbody></table>`;
                    }
                    html += `</div>`;
                    html = html.replace('<div class="cell count">-</div>', `<div class="cell">${totalSalesCount}</div>`);
                    html = html.replace('<div class="cell sum">-</div>', `<div class="cell">${totalSalesSum.toFixed(2)}</div>`);
                }
                container.innerHTML += html;
            }
            btnsList();
        }

        function createTableDay(data) {
            let container = document.querySelector('#table-sell');
            container.innerHTML = '';
            let html = `<div class="row table-row">
                            <div class="cell">ID</div>
                            <div class="cell">@lang('proxies::phrases.Дата и время')</div>
                            <div class="cell">@lang('proxies::phrases.Кол-во продаж')</div>
                            <div class="cell">@lang('proxies::phrases.Сумма')</div>
                            <div class="cell">@lang('proxies::phrases.Действие')</div>
                        </div>`;

                for (const date in data) { // Месяца
                    for (const element in data[date]) { // Дни
                        const timeRow = data[date][element];
                        html += `<div class="row table-row closeOpen table" data-item="${timeRow.date}">
                                    <div class="cell"></div>
                                    <div class="cell">${timeRow.date}</div>
                                    <div class="cell">${timeRow.count}</div>
                                    <div class="cell">${timeRow.sum.toFixed(2)}</div>
                                    <div class="cell closeOpen">
                                        <i class='bx bx-chevron-down open'></i>
                                        <i class='bx bx-chevron-up close no'></i>
                                    </div>
                                </div>
                                <table class="table table-bordered" data-id="${timeRow.date}">
                                    <thead>
                                        <tr class="tr-name">
                                            <th>ID</th>
                                            <th>@lang('proxies::phrases.Кто купил')</th>
                                            <th>@lang('proxies::phrases.Во сколько')</th>
                                            <th>@lang('proxies::phrases.Кол-во')</th>
                                            <th>@lang('proxies::phrases.Сумму')</th>
                                            <th>@lang('proxies::phrases.Срок')</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                        timeRow.day.forEach(day => { // Данны за день
                            html += `<tr>
                                        <td>${day.id}</td>
                                        <td>${day.user}</td>
                                        <td>${new Date(day.created_at).toLocaleDateString('en-GB', {
                                                day: '2-digit',
                                                month: '2-digit',
                                                year: '2-digit',
                                                hour: '2-digit',
                                                minute: '2-digit',
                                                second: '2-digit'}).replace(/\//g, '.')}</td>
                                        <td>${(day.quantity != null) ? day.quantity : ""}</td>
                                        <td>${day.amount}</td>
                                        <td>${(day.duration != null) ? day.duration : ""}</td>
                                    </tr>`;
                        });
                        html += `</tbody></table>`;
                    }
                    html += `</div>`;
                }
                container.innerHTML += html;
            btnsList();
        }
    </script>
@endsection
