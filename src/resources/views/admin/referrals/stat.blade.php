@extends('admin.app')
@section('content')
<div class="header-page">
    <div class="title-page">
        <h2>@lang('proxies::phrases.Реферальная система')</h2>
    </div>
    <div class="buttons">
    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<div class="block-background">
    <div class="title-block">
        <h3>@lang('proxies::phrases.Статистика')</h3>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr class="tr-name">
                <th>Email</th>
                <th>@lang('proxies::phrases.Пополнений за всё время')</th>
                <th>@lang('proxies::phrases.Пополнений за месяц')</th>
                <th>@lang('proxies::phrases.Новых рефералов за месяц')</th>
                <th>@lang('proxies::phrases.Всего выведено')</th>
                <th>@lang('proxies::phrases.Количество выводов')</th>
                <th>@lang('proxies::phrases.Дата последнего вывода')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($collection as $item)
            <tr>
                <td><a href="/users/{{$item['user']['id']}}">{{ $item['user']['email'] }}</a></td>
                <td>{{ $item['countPlusMoney'] }}</td>
                <td>{{ $item['countPlusMoneyMonth'] }}</td>
                <td>{{ $item['referrerReferralsMonth'] }}</td>
                <td>{{ $item['withdrawalRequestsAmountSum'] }}</td>
                <td>{{ $item['withdrawalRequestsAll'] }}</td>
                <td>{{ $item['lastWithdrawalRequestDate'] != null ? \Carbon\Carbon::parse($item['lastWithdrawalRequestDate'])->format('d.m.Y H:i:s') : trans('proxies::phrases.Ещё не было выводов') }}</td>
            </tr>
            @endforeach

            {{-- <td colspan="7" class="absent">Записи отсутствуют</td> --}}
        </tbody>
    </table>
</div>

@endsection