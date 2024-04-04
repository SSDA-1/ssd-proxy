@extends('admin.app')

@section('content')
<div class="header-page">
    <div class="title-page">
        <h2>@lang('phrases.Тех поддержка')</h2>
    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<div class="block-background">
    <div class="title-block">
        <h3>@lang('phrases.Список Обращений')</h3>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr class="tr-name">
                <th>ID</th>
                <th>@lang('phrases.Пользователь')</th>
                <th>@lang('phrases.Проблема')</th>
                <th>@lang('phrases.Дата обращения')</th>
                <th>@lang('phrases.Обновлен')</th>
                <th>@lang('phrases.Статус')</th>
                <th>@lang('phrases.Действие')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($supports as $support)
            <tr>
                <td>{{$support->id}}</td>
                <td>{{$support->user->name}}</td>
                <td>{{$support->firstsuppmassage ? $support->firstsuppmassage->massage : ''}}</td>
                <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $support->created_at)}}</td>
                <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $support->updated_at)}}</td>
                <td>{!! $support->status ? '<span style="color:red;">' . trans('phrases.Закрыт') . '</span>' : '<span style="color:green;">' . trans('phrases.Открыт') . '</span>'!!}</td>
                <td class="dayst">
                    {{-- <form action="{{ route('reviews-adm.destroy',$support->id) }}" method="POST"> --}}
                        <a class="btn btn-action" href="{{ route('support.show',$support->id) }}"><i class="fa-regular fa-eye"></i></a>
                        {{-- @csrf --}}
                        {{-- @method('DELETE') --}}
                        {{-- <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button> --}}
                        <button type="submit" data-title="@lang('phrases.Вы точно хотите удалить Обращение с сайта')? <br>" data-action="{{ route('support.destroy', $support->id) }}" data-modal="del" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                    {{-- </form> --}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{!! $supports->links() !!}

@endsection