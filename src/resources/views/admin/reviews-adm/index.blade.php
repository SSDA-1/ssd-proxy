@extends('proxies::admin.app')

@section('content')
<div class="header-page">
    <div class="title-page">
        <h2>@lang('proxies::phrases.Отзывы')</h2>
    </div>
    <div class="buttons">
        <a class="btn btn-success" href="{{ route('reviews-adm.create') }}">@lang('proxies::phrases.Добавить отзыв')</a>
    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<div class="block-background">
    <div class="title-block">
        <h3>@lang('proxies::phrases.Список Отзывов')</h3>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr class="tr-name">
                <th>No</th>
                <th>@lang('proxies::phrases.Автор')</th>
                <th>@lang('proxies::phrases.Отзыв')</th>
                <th>@lang('proxies::phrases.Дата публикации')</th>
                <th>@lang('proxies::phrases.Обновлен')</th>
                <th>@lang('proxies::phrases.Действие')</th>
            </tr>
        </thead>
        <tbody>
            @if($reviews->isNotEmpty())
            @foreach ($reviews as $reviewss)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $reviewss->name }}</td>
                <td>@if(strlen($reviewss->description)>40)
                    {!!  mb_substr( $reviewss->description, 0, 40 ).'...'  !!} 
                    @else
                    {!! $reviewss->description !!}
                    @endif
                </td>
                <td>{{ $reviewss->created_at }}</td>
                <td>{{ $reviewss->updated_at }}</td>
                <td class="dayst">
                    <form action="{{ route('reviews-adm.destroy',$reviewss->id) }}" method="POST" data-fetch="none">
                        <a class="btn btn-action" href="{{ route('reviews-adm.edit',$reviewss->id) }}"><i
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

{!! $reviews->links() !!}

@endsection