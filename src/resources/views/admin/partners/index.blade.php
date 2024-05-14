@extends('admin.app')

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Партнеры')</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('partners.create') }}">@lang('proxies::phrases.Добавить')</a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="block-background">
        <div class="title-block">
            <h3>@lang('proxies::phrases.Список партнеров')</h3>
        </div>
        <table class="table table-bordered">
            <thead>
            <tr class="tr-name">
                <th>No</th>
                <th>@lang('proxies::phrases.Название')</th>
                <th>@lang('proxies::phrases.Скидка')</th>
                <th>@lang('proxies::phrases.Промокод')</th>
                <th>@lang('proxies::phrases.Ссылка')</th>
                <th>@lang('proxies::phrases.Действие')</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($partners as $partner)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $partner->name }}</td>
                    <td>{{ $partner->discount }}</td>
                    <td>{{ $partner->promo }}</td>
                    <td>{{ $partner->link != Null ? "<a href=".$partner->link.">Ссылка</a>" : "" }}</td>
                    <td class="dayst">
                        <form action="{{ route('partners.destroy', $partner->id) }}" method="POST" data-fetch="none">
                            <a class="btn btn-action" href="{{ route('partners.edit', $partner->id) }}"><i
                                        class="fa-regular fa-pen-to-square"></i></a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{--{!! $menu->links() !!}--}}

@endsection