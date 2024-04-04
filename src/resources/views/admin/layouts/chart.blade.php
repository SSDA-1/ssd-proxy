<div class="row">
    <div class="field width-100">
        <div class="title-field statistics">@lang('phrases.Статистика продаж')</div>
        <canvas id="myChart"></canvas>
    </div>
</div>
<div class="row">
    <div class="field">
        <div class="title-field">@lang('phrases.Введите дату')</div>
        <form id="chartForm" method="post" action="{{ route('chartValue') }}">
            @csrf
            <div class="wrap-input">
                <input type="text" id="datePicker" name="datePicker" value="" class="input-text copy" />
                <button type="submit" class="btn-input-success">@lang('phrases.Применить')</button>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="field">
        <button id="0" type="button" class="btn btn-primary">@lang('phrases.За все время')</button>
    </div>
    <div class="field">
        <button id="1" type="button" class="btn btn-primary">@lang('phrases.За текущий год')</button>
    </div>
    <div class="field">
        <button id="2" type="button" class="btn btn-primary">@lang('phrases.За текущий месяц')</button>
    </div>
</div>

@push('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js">
    </script>
    <script>
        var startDateText, endDateText;
        $('#datePicker').daterangepicker({
            "showDropdowns": true,
            "minYear": 2022,
            "maxYear": 2030,
            "showWeekNumbers": true,
            //"autoApply": true,
            "autoUpdateInput": true,
            ranges: {
                '2022': [moment().startOf('year').subtract(365, 'days'), moment().endOf('year').subtract(365,
                    'days')],
                '2023': [moment().startOf('year'), moment().endOf('year')],
                'Сегодня': [moment(), moment()],
                'Вчера': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'За 7 дней': [moment().subtract(6, 'days'), moment()],
                'За 30 дней': [moment().subtract(29, 'days'), moment()],
                'Этот Месяц': [moment().startOf('month'), moment().endOf('month')],
                'Прошлый месяц': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                    .endOf('month')
                ]
            },
            "locale": {
                "format": "DD.MM.YYYY",
                "separator": " - ",
                "applyLabel": "Применить",
                "cancelLabel": "Очистить",
                "fromLabel": "От",
                "toLabel": "До",
                "customRangeLabel": "Пользовательский",
                "weekLabel": "W",
                "daysOfWeek": [
                    "Вс",
                    "Пн",
                    "Вт",
                    "Ср",
                    "Чт",
                    "Пт",
                    "Сб"
                ],
                "monthNames": [
                    "Январь",
                    "Февраль",
                    "Март",
                    "Апрель",
                    "Май",
                    "Июнь",
                    "Июль",
                    "Август",
                    "Сентябрь",
                    "Октябрь",
                    "Ноябрь",
                    "Декабрь"
                ],
                "firstDay": 1
            },

        }, function(start, end, label) {
            // console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format(
            //     'YYYY-MM-DD') + ' (predefined range: ' + label + ')');
        });
        $('input[name="datePicker"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD.MM.YYYY') + ' - ' + picker.endDate.format('DD.MM.YYYY'));
            // console.log('New date range selected: ' + picker.startDate.format('YYYY.MM.DD') + ' to ' + picker
            //     .endDate.format('YYYY.MM.DD'));
            startDateText = picker.startDate.format('YYYY.MM.DD');
            endDateText = picker.endDate.format('YYYY.MM.DD');
        });
        $('input[name="datePicker"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    </script>
    <script>
        const statistics = body.querySelector('.statistics');
        const DATA_COUNT = 12;
        var ctx = document.getElementById("myChart").getContext('2d');
        const NUMBER_CFG = {
            count: DATA_COUNT,
            min: 0
        };
        const labels = {{ Js::from($chart->attributes['labelsAllTime']) }};
        if (idMode == 1) {
            Chart.defaults.color = '#fff';
        } else {
            Chart.defaults.color = '#000';
        }
        const data = {
            labels: labels,
            datasets: [{
                label: 'Количество продаж',
                data: {{ Js::from($chart->attributes['datasetAllTime']) }},
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgb(255, 99, 132)',
                textColor: 'rgb(255, 255, 255)',
                spanGaps: true,
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    // title: {
                    //     display: true,
                    //     text: 'Статистика продаж за все время',
                    // }
                },
                scales: {
                    y: {
                        suggestedMin: 0,
                        suggestedMax: 100
                    }
                }
            },
        };

        var forecast_chart = new Chart(ctx, config);
        $("#0").click(function() {
            var data = forecast_chart.config.data;
            statistics.innerText = 'Статистика продаж за все время';
            // forecast_chart.config.options.plugins.title.text = 'Статистика продаж за все время';
            data.datasets[0].data = {{ Js::from($chart->attributes['datasetAllTime']) }};
            data.labels = {{ Js::from($chart->attributes['labelsAllTime']) }};
            forecast_chart.update();
        });
        $("#1").click(function() {
            statistics.innerText = 'Статистика продаж за текущий год';
            // forecast_chart.config.options.plugins.title.text = 'Статистика продаж за текущий год';
            var chart_labels = {{ Js::from($chart->attributes['labelsByCurrentYear']) }};
            var temp_dataset = {{ Js::from($chart->attributes['datasetByCurrentYear']) }};
            var data = forecast_chart.config.data;
            data.datasets[0].data = temp_dataset;
            data.labels = chart_labels;
            forecast_chart.update();
        });
        $("#2").click(function() {
            statistics.innerText = 'Статистика продаж за текущий месяц';
            // forecast_chart.config.options.plugins.title.text = 'Статистика продаж за текущий месяц';
            var chart_labels = {{ Js::from($chart->attributes['labelsByCurrentMonth']) }};
            var temp_dataset = {{ Js::from($chart->attributes['datasetByCurrentMonth']) }};
            var data = forecast_chart.config.data;
            data.datasets[0].data = temp_dataset;
            data.labels = chart_labels;
            forecast_chart.update();
        });

        $(document).ready(function() {
            $('#chartForm').submit(function(event) {
                event.preventDefault();
                // Собираем данные с формы.
                // Здесь будут все поля у которых есть `name`,
                // включая метод `_method` и `_token`.
                var dataB = new FormData(this);

                $.ajax({
                    method: 'POST', // начиная с версии 1.9 `type` - псевдоним для `method`.
                    url: this.action, // атрибут `action="..."` из формы.
                    cache: false, // запрошенные страницы не будут закешированы браузером.
                    data: dataB, // больше ничего тут не надо!
                    dataType: 'json', // чтобы jQuery распарсил `success` ответ.
                    processData: false, // чтобы jQuery не обрабатывал отправляемые данные.
                    contentType: false, // чтобы jQuery не передавал в заголовке поле `Content-Type` совсем.
                    success: function(dataB) {
                        var data = forecast_chart.config.data;
                        if (startDateText != undefined) {
                            statistics.innerText = 'Статистика продаж c ' + startDateText +
                                ' по ' + endDateText;
                        } else {
                            statistics.innerText = 'Статистика продаж сегодня';
                        }
                        data.datasets[0].data = dataB.datasetByInput;
                        data.labels = dataB.labelsByInput;
                        forecast_chart.update();
                        $('#invalid-feedback').hide();
                        $('#invalid-feedback').text('');
                    },
                    error: function(error) {
                        $('#invalid-feedback').show();
                        $('#invalid-feedback').text(error.responseJSON.message);
                    }
                });
            })
        });
    </script>
@endpush
