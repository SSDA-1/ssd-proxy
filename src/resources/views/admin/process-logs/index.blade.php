@extends('admin.app')

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('phrases.Управление Логами')</h2>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="block-background">
        <div class="title-block">
            <h3>@lang('phrases.Список логов')</h3>
        </div>
        <div class="row"></div>

        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>@lang('phrases.Название')</th>
                <th>@lang('phrases.Описание')</th>
                <th>@lang('phrases.Дата')</th>
            </tr>
            <tbody>
            @foreach ($data as $key => $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>@lang('phrases.' . $log->name)</td>
                    <td>@lang('phrases.' . $log->description)</td>
                    <td>{{ $log->created_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {!! $data->links('vendor.pagination.default') !!}
@endsection
