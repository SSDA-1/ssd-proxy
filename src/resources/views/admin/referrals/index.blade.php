@extends('proxies::admin.app')
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
        <h3>@lang('proxies::phrases.Заявки на вывод')</h3>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr class="tr-name">
                <th>№</th>
                <th>Email</th>
                <th>@lang('proxies::phrases.Дата заявки')</th>
                <th>@lang('proxies::phrases.Дата выполнения')</th>
                <th>@lang('proxies::phrases.Статус заявки')</th>
                <th>@lang('proxies::phrases.Сумма')</th>
                <th>@lang('proxies::phrases.Действия')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($withdrawalRequest as $request)
                @php
                $status = $request->status == 0 ? trans('proxies::phrases.Активна') : ($request->status == 1 ? trans('proxies::phrases.В работе') : trans('proxies::phrases.Выполнена'))
                @endphp
                <tr>
                    <td>{{ $request->id }}</td>
                    <td>{{ $request->user->email }}</td>
                    <td>
                        @if (!$request->execution_date)
                            {{ $status }}
                        @else
                            {{ \Carbon\Carbon::parse($request->execution_date)->format('d.m.Y') }}
                        @endif 
                    </td>
                    <td>
                        @if (!$request->execution_date)
                            {{ $status }}
                        @else
                            {{ \Carbon\Carbon::parse($request->execution_date)->format('H:i:s') }}
                        @endif 
                    </td>
                    <td>{{ $status }}</td>
                    <td>{{ $request->amount }} $</td>
                    <td>
                        <a class="btn btn-action" href="{{ route('withdrawalrequest.show',$request->id) }}"><i
                        class="fa-regular fa-eye"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection