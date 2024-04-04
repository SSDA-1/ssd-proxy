@extends('admin.app')

@section('content')
<div class="header-page">
    <div class="title-page">
        <h2>@lang('phrases.Преимущества')</h2>
    </div>
    <div class="buttons">
        <a class="btn btn-success" href="{{ route('advantag.create') }}">@lang('phrases.Добавить')</a>
    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<div class="block-background">
    <div class="title-block">
        <h3>@lang('phrases.Список преимуществ')</h3>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr class="tr-name">
                <th>No</th>
                <th>@lang('phrases.Название')</th>
                <th>@lang('phrases.Описание')</th>
                <th>@lang('phrases.Действие')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($advantags as $advantag)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $advantag->title }}</td>
                    <td>{{ $advantag->description }}</td>
                    <td class="dayst">
                        <form action="{{ route('advantag.destroy',$advantag->id) }}" method="POST" data-fetch="none">
                            <a class="btn btn-action" href="{{ route('advantag.edit',$advantag->id) }}"><i
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