@extends('proxies::admin.app')

@section('content')
<div class="header-page">
    <div class="title-page">
        <h2>@lang('proxies::phrases.Новости')</h2>
    </div>
    <div class="buttons">
        <a class="btn btn-success" href="{{ route('news.create') }}">@lang('proxies::phrases.Добавить новость')</a>
    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<div class="block-background">
    <div class="title-block">
        <h3>@lang('proxies::phrases.Список статей')</h3>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr class="tr-name">
                <th>No</th>
                <th>@lang('proxies::phrases.Название')</th>
                <th>@lang('proxies::phrases.Автор')</th>
                <th>@lang('proxies::phrases.Дата публикации')</th>
                <th>@lang('proxies::phrases.Обновлена')</th>
                <th>@lang('proxies::phrases.Действие')</th>
            </tr>
        </thead>
        <tbody>
            @if($newss->isNotEmpty())
            @foreach ($newss as $news)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $news->name }}</td>
                <td>{{ $news->author }}</td>
                <td>{{ $news->created_at }}</td>
                <td>{{ $news->updated_at }}</td>
                <td class="dayst">
                    <form action="{{ route('news.destroy',$news->id) }}" method="POST" data-fetch="none">
                        <a class="btn btn-action" href="{{ route('news.show',$news->id) }}"><i
                                class="fa-regular fa-eye"></i></a>
                        <a class="btn btn-action" href="{{ route('news.edit',$news->id) }}"><i
                                class="fa-regular fa-pen-to-square"></i></a>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
            @else
            <td colspan="6" class="absent">@lang('proxies::phrases.Записи отсутствуют')</td>
            @endif
        </tbody>
    </table>
</div>

{!! $newss->links('vendor.pagination.default') !!}
{{-- {!! $newss->links() !!} --}}

@endsection