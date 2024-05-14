@extends('admin.app')

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Управление ролями')</h2>
        </div>
        <div class="buttons">
            @can('role-create')
                <a class="btn btn-success" href="{{ route('roles.create') }}">@lang('proxies::phrases.Создать новую роль')</a>
            @endcan
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="block-background">
        <div class="title-block">
            <h3>@lang('proxies::phrases.Список ролей')</h3>
        </div>
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>@lang('proxies::phrases.Имя')</th>
                <th width="280px">@lang('proxies::phrases.Действие')</th>
            </tr>
            @if ($roles->isNotEmpty())
                @foreach ($roles as $key => $role)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $role->name }}</td>
                        <td class="dayst">
                            <a class="btn btn-action" href="{{ route('roles.show', $role->id) }}"><i
                                    class="fa-regular fa-eye"></i></a>
                            @can('role-edit')
                                <a class="btn btn-action" href="{{ route('roles.edit', $role->id) }}"><i
                                        class="fa-regular fa-pen-to-square"></i></a>
                            @endcan
                            @can('role-delete')
                                {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id], 'style' => 'display:inline']) !!}
                                <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                                {!! Form::close() !!}
                            @endcan
                        </td>
                    </tr>
                @endforeach
            @else
                <td colspan="3" class="absent">@lang('proxies::phrases.Записи отсутствуют')</td>
            @endif
        </table>
    </div>


    {!! $roles->links('vendor.pagination.default') !!}
    {{-- {!! $roles->render() !!} --}}

@endsection
